<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    var $CI;

    //this is the expiration for a non-remember session
    var $session_expire	= 1800;

    function __construct()
    {
	$this->CI =& get_instance();
	$this->CI->load->database();
	$this->CI->load->library('encrypt');
	$this->CI->load->library('session');
	$this->CI->load->helper('url');
	$this->CI->lang->load('admin');
    }

    /**
     * We could store this in the session, but by accessing it this way 
     * if an admin's access level gets changed while they're logged in the 
     * system will act accordingly.
     */
    function check_access($access, $default_redirect=false, $redirect = false)
    {
	$admin = $this->CI->session->userdata('admin');

	$this->CI->db->select('access');
	$this->CI->db->where('id', $admin['id']);
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('manager_users');
	$result	= $result->row();

	//result should be an object I was getting odd errors in relation to the object.
	//if $result is an array then the problem is present.
	if(!$result || is_array($result))
	{
	    $this->logout();
	    return false;
	}
	//	echo $result->access;
	if ($access)
	{
	    if ($access == $result->access)
	    {
		return true;
	    }
	    else
	    {
		if ($redirect)
		{
		    redirect($redirect);
		}
		elseif($default_redirect)
		{
		    redirect($this->CI->config->item('admin_folder').'/dashboard/');
		}
		else
		{
		    return false;
		}
	    }

	}
    }

	/*
	this checks to see if the admin is logged in
	we can provide a link to redirect to, and for the login page, we have $default_redirect,
	this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
	 */
    function is_logged_in($redirect = false, $default_redirect = true)
    {

	//var_dump($this->CI->session->userdata('session_id'));

	//$redirect allows us to choose where a customer will get redirected to after they login
	//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.

	$admin = $this->CI->session->userdata('admin');

	if (!$admin)
	{
	    if ($redirect)
	    {
		$this->CI->session->set_flashdata('redirect', $redirect);
	    }

	    if ($default_redirect)
	    {	
		redirect($this->CI->config->item('admin_folder').'/login');
	    }

	    return false;
	}
	else
	{

	    //check if the session is expired if not reset the timer
	    if($admin['expire'] && $admin['expire'] < time())
	    {

		$this->logout();
		if($redirect)
		{
		    $this->CI->session->set_flashdata('redirect', $redirect);
		}

		if($default_redirect)
		{
		    redirect($this->CI->config->item('admin_folder').'/login');
		}

		return false;
	    }
	    else
	    {

		//update the session expiration to last more time if they are not remembered
		if($admin['expire'])
		{
		    $admin['expire'] = time()+$this->session_expire;
		    $this->CI->session->set_userdata(array('admin'=>$admin));
		}

	    }

	    return true;
	}
    }
	/*
	this function does the logging in.
	 */
    function login_admin($email, $password, $remember=false)
    {
	$this->CI->db->select('*');
	$this->CI->db->where('email', $email);
	$this->CI->db->where('password',  sha1($password));
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('admin_users');
	$result	= $result->row_array();

	if (sizeof($result) > 0)
	{
	    $admin = array();
	    $admin['admin']			= array();
	    $admin['admin']['id']		= $result['id'];
	    $admin['admin']['access'] 	= $result['access'];
	    $admin['admin']['realname']	= $result['realname'];
	    $admin['admin']['email']	= $result['email'];

	    if(!$remember)
	    {
		$admin['admin']['expire'] = time()+$this->session_expire;
	    }
	    else
	    {
		$admin['admin']['expire'] = false;
	    }

	    $this->CI->session->set_userdata($admin);
	    // write log
	    $data = array(
		'user_id'=>$result['id'], 
		'username'=>empty($result['realname'])?$result['email']:$result['realname'], 
		'info'=>$this->CI->lang->line('admin_right_user_login'), 
		'ip_address'=>$_SERVER['SERVER_NAME']
	    );
	    $this->CI->db->insert('admin_log', $data);
	    return true;
	}
	else
	{
	    return false;
	}
    }

	/*
	this function does the logging out
	 */
    function logout()
    {
	$admin = $this->CI->session->userdata('admin');
	// write log
	$data = array(
	    'user_id'=>$admin['id'], 
	    'username'=>empty($admin['realname'])?$admin['email']:$admin['realname'], 
	    'info'=>$this->CI->lang->line('admin_right_user_logout'),
	    'ip_address'=>$_SERVER['SERVER_NAME']
	);
	$this->CI->db->insert('admin_log', $data);
	$this->CI->session->unset_userdata('admin');
	$this->CI->session->sess_destroy();
    }

	/*
	This function resets the admins password and emails them a copy
	 */
    function reset_password($email)
    {
	$admin = $this->get_admin_by_email($email);
	if ($admin)
	{
	    $this->CI->load->helper('string');
	    $this->CI->load->library('email');

	    $new_password		= random_string('alnum', 8);
	    $admin['password']	= sha1($new_password);
	    $this->save_admin($admin);

	    $this->CI->email->from($this->CI->config->item('email'), $this->CI->config->item('site_name'));
	    $this->CI->email->to($email);
	    $this->CI->email->subject($this->CI->config->item('site_name').': Admin Password Reset');
	    $this->CI->email->message('Your password has been reset to '. $new_password .'.');
	    $this->CI->email->send();
	    return true;
	}
	else
	{
	    return false;
	}
    }

    // Get the admin by id and return the values in an array.
    function get_admin_by_id($id)
    {
	$this->CI->db->select('*');
	$this->CI->db->where('id', $id);
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('admin_users');
	$result = $result->row_array();

	if (sizeof($result) > 0)
	{
	    return $result;
	}
	else
	{
	    return false;
	}
    }

    // Get the admin by email and return the values in an array.
    private function get_admin_by_email($email)
    {
	$this->CI->db->select('*');
	$this->CI->db->where('email', $email);
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('admin_users');
	//$result = $result->row();
	$result = $result->row_array();

	if (sizeof($result) > 0)
	{
	    return $result;	
	}
	else
	{
	    return false;
	}
    }

    function get_user_grid_data($params)
    {
	$result = array(
	    'total'=>0, 
	    'rows'=>array()    
	);
	$tb_name = 'admin_users';
	$join_tb_name = 'admin_roles';
	$this->CI->db->from($tb_name);
	$result['total'] = $this->CI->db->count_all_results();
	$this->CI->db->select('*,'.$tb_name.'.id as id,'.$tb_name.'.creation_date as creation_date,'.$tb_name.'.enabled as enabled');
	$this->CI->db->from($tb_name);
	$this->CI->db->join($join_tb_name, $tb_name.'.role_id='.$join_tb_name.'.id', 'left');
	$this->CI->db->order_by($params['sort'], $params['order']);
	$this->CI->db->limit($params['limit'], $params['offset']);
	$result['rows'] = $this->CI->db->get()->result();
	return $result;
    }

    // Save admin user 
    function save_user($admin)
    {
	$tb_name = 'admin_users';
	if (array_key_exists('id', $admin))
	{
	    $this->CI->db->where('id', $admin['id']);
	    $this->CI->db->update($tb_name, $admin);
	}
	else
	{
	    $this->CI->db->insert($tb_name, $admin);
	}
    }
    // Enable user or not.
    function enable_user($id, $enable)
    {
	$tb_name = 'admin_users';
	$this->CI->db->where('id', $id);
	$this->CI->db->update($tb_name, array('enabled'=>!$enable));
    }
    
    // This function gets a complete list of all admin
    function get_admin_list()
    {
	$this->CI->db->select('*');
	$this->CI->db->order_by('lastname', 'ASC');
	$this->CI->db->order_by('firstname', 'ASC');
	$this->CI->db->order_by('email', 'ASC');
	$result = $this->CI->db->get('admin_users');
	$result	= $result->result();

	return $result;
    }

    function check_id($str)
    {
	$this->CI->db->select('id');
	$this->CI->db->from('admin_users');
	$this->CI->db->where('id', $str);
	$count = $this->CI->db->count_all_results();

	if ($count > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}	
    }

    function check_email($str, $id=false)
    {
	$this->CI->db->select('email');
	$this->CI->db->from('admin_users');
	$this->CI->db->where('email', $str);
	if ($id)
	{
	    $this->CI->db->where('id !=', $id);
	}
	$count = $this->CI->db->count_all_results();

	if ($count > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    // Delete user
    function delete_user($ids)
    {
	$tb_name = 'admin_users';
	$this->CI->db->where_in('id', $ids);
	$this->CI->db->delete($tb_name);
	return true;
    }

    // Get role list
    function get_role_list()
    {
	$this->CI->db->select('*');
	$this->CI->db->order_by('id', 'DESC');
	$result = $this->CI->db->get('admin_roles');
	$result	= $result->result();

	return $result;
    }

    // Get role array
    function get_role_array()
    {
	$roles = $this->get_role_list();
	$result = array();
	foreach ($roles as $role)
	{
	    $result[$role->id] = $role->name;
	}
	return $result;
    }

    // Get the admin by id and return the values in an array.
    function get_role_by_id($id)
    {
	$tb_name = 'admin_roles';
	$this->CI->db->select('*');
	$this->CI->db->where('id', $id);
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('admin_roles');
	$result = $result->row_array();

	if (sizeof($result) > 0)
	{
	    return $result;
	}
	else
	{
	    return false;
	}
    }

    // Check if role id exists or not.
    function check__role_id($str)
    {
	$tb_name = 'admin_roles';
	$this->CI->db->select('id');
	$this->CI->db->from($tb_name);
	$this->CI->db->where('id', $str);
	$count = $this->CI->db->count_all_results();

	if ($count > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}	
    }

    // Save role 
    function save_role($role)
    {
	$tb_name = 'admin_roles';
	if (array_key_exists('id', $role))
	{
	    $this->CI->db->where('id', $role['id']);
	    $this->CI->db->update($tb_name, $role);
	}
	else
	{
	    $this->CI->db->insert($tb_name, $role);
	}
    }

    // Delete role
    function delete_role($ids)
    {
	$tb_name = 'admin_roles';
	$this->CI->db->where_in('id', $ids);
	$this->CI->db->delete($tb_name);
	return true;
    }

    // Enable role
    function enable_role($id, $enable)
    {
	$tb_name = 'admin_roles';
	$this->CI->db->where('id', $id);
	$this->CI->db->update($tb_name, array('enabled'=>!$enable));
    }

    // Delete log
    function delete_log($ids)
    {
	$tb_name = 'admin_log';
	$this->CI->db->where_in('id', $ids);
	$this->CI->db->delete($tb_name);
	return true;
    }
}
