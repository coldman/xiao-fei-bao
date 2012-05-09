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
    }

    function is_logged_in($redirect = false, $default_redirect = true)
    {
	$manage = $this->CI->session->userdata('manage');

	if (!$manage)
	{
	    if ($redirect)
	    {
		$this->CI->session->set_flashdata('redirect', $redirect);
	    }

	    if ($default_redirect)
	    {	
		redirect('login');
	    }
	    return false;
	}
	else
	{
	    //check if the session is expired if not reset the timer
	    if($manage['expire'] && $manage['expire'] < time())
	    {
		$this->logout();
		if($redirect)
		{
		    $this->CI->session->set_flashdata('redirect', $redirect);
		}

		if($default_redirect)
		{
		    redirect('login');
		}

		return false;
	    }
	    else
	    {
		//update the session expiration to last more time if they are not remembered
		if($manage['expire'])
		{
		    $manage['expire'] = time()+$this->session_expire;
		    $this->CI->session->set_userdata(array('manage'=>$manage));
		}
	    }

	    return true;
	}
    }

    /**
     * Do login 
     */
    function login($username, $password, $remember=false)
    {
	$this->CI->db->select('*');
	$this->CI->db->where('username', $username);
	$this->CI->db->where('password',  sha1($password));
	$this->CI->db->limit(1);
	$result = $this->CI->db->get('manage_users');
	$result	= $result->row_array();

	if (sizeof($result) > 0)
	{
	    $manage = array();
	    $manage['manage']		    = array();
	    $manage['manage']['id']	    = $result['id'];
	    $manage['manage']['username']   = $result['username'];
	    $manage['manage']['phone']	    = $result['phone'];
	    $manage['manage']['email']	    = $result['email'];
	    $manage['manage']['role']	    = $result['role'];

	    if(!$remember)
	    {
		$manage['manage']['expire'] = time()+$this->session_expire;
	    }
	    else
	    {
		$manage['manage']['expire'] = false;
	    }

	    $this->CI->session->set_userdata($manage);
	    return true;
	}
	else
	{
	    return false;
	}
    }

    /**
     * Do logout
     */
    function logout()
    {
	$manage = $this->CI->session->userdata('manage');
	$this->CI->session->unset_userdata('manage');
	$this->CI->session->sess_destroy();
    }
}
