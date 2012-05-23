<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * controller: users
 * 对业务员管理
 * 
 * @author  zhangxk <zxk0610@gmail.com>
 * @link 
 */
 
 
class User extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();

    }

    function change_pwd()
    {
	$info	    = $this->session->userdata('manage');
	$manager    = $this->manage_model->get_manage_by_id($info['id']);
	$submitted  = $this->input->post('submitted');
	if ($submitted) 
	{
	    $old_pwd = $this->input->post('old_pwd');
	    $new_pwd = $this->input->post('new_pwd');
	    $cfm_pwd = $this->input->post('cfm_pwd');
	    if (sha1($old_pwd) != $manager['password']) 
	    {
		$this->session->set_flashdata('error', '原密码不正确，请重新输入！');
	    }
	    else
	    {
		if ($new_pwd != $cfm_pwd)
		{
		    $this->session->set_flashdata('error', '确认密码不匹配，请重新输入！');
		}
		else
		{
		    $this->manage_model->save_manage(array('id'=>$manager['id'], 'password'=>sha1($new_pwd)));
		    $this->session->set_flashdata('msg', '密码修改成功！');
		}
	    }
	    redirect('user/change_pwd');
	}
	$this->_template('pwd');
    }

    function info()
    {
	$manager = $this->session->userdata('manage');
	$data['manager'] = $this->manage_model->get_manage_by_id($manager['id']);
	$this->_template('info', $data);
    }

    function managers()
    {
	$this->_template('manager/list');
    }

    function add_manager()
    {
	$submitted = $this->input->post('submitted');
	if ($submitted) 
	{
	    $save = array(
		'username'  => $this->input->post('username'), 
		'realname'  => $this->input->post('realname'),
		'password'  => sha1($this->input->post('password')),
		'email'	    => $this->input->post('email'), 
		'sex'	    => $this->input->post('sex'), 
		'phone'	    => $this->input->post('phone')
	    );
	    $agents = $this->input->post('agents');
	    if (sha1($this->input->post('cfm_pwd')) != $save['password'])
	    {
		$this->session->set_flashdata('error', '确认密码不匹配！');
	    }
	    else 
	    {
		$manager_id = $this->manage_model->save_manage($save);
		if ($agents) 
		{
		    $agent_ids = explode(',', $agents);
		    $this->manage_model->bind_agents_to_manager($manager_id, $agent_ids);
		}
		$this->session->set_flashdata('msg', '业务员添加成功！');
	    }

	    redirect('user/add_manager');
	}
	$this->_template('manager/add');
    }

    function edit_manager($id)
    {
	$data['manager'] = $this->manage_model->get_manage_by_id($id);
	$submitted = $this->input->post('submitted');
	if ($submitted)
	{
	    $save = array(
		'id'	    => $this->input->post('id'), 
		'username'  => $this->input->post('username'), 
		'realname'  => $this->input->post('realname'),
		'email'	    => $this->input->post('email'), 
		'sex'	    => $this->input->post('sex'), 
		'phone'	    => $this->input->post('phone')
	    );
	    $agents = $this->input->post('agents');
	    if ($this->input->post('password'))
	    {
		if ($this->input->post('password') != $this->input->post('cfm_pwd'))
		{
		    $this->session->set_flashdata('error', '确认密码不匹配！');
		    redirect('user/edit_manager/'.$save['id']);
		}
		else 
		{
		    $save['password'] = sha1($this->input->post('password'));
		}
	    }
	    $manager_id = $this->manage_model->save_manage($save);
	    if ($agents)
	    {
		$agent_ids = explode(',', $agents);
		$this->manage_model->bind_agents_to_manager($manager_id, $agent_ids);
	    }
	    $this->session->set_flashdata('msg', '业务员更新成功！');
	    redirect('user/edit_manager/'.$save['id']);
	}
	$this->_template('manager/edit', $data);
    }

    function agent_rate_temp()
    {
	$this->_template('agent/rate_temp_list');
    }

    function agent_rate_temp_add()
    {
	echo 'add';
    }

    function agent_rate_temp_edit()
    {
	echo 'edit';
    }

    function agent_rate_temp_del()
    {
	echo 'del';
    }

}

?>
