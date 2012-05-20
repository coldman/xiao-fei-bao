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
	    redirect('user/managers');
	}
	$this->_template('manager/add');
    }

    function edit_manager()
    {
	$submitted = $this->input->post('submitted');
	if ($submitted)
	{
	    redirect('user/managers');
	}
	$this->_template('manager/edit');
    }

    function test()
    {
        $this->load->model("manage_model");
        #$this->load->model("trader_model");
        #$result = $this->manage_model->get_agent_list(10000);
        #result = $this->manage_model->get_agent_amt(14011);
        #$result = $this->manage_model->get_trader_orders(13952,0,2000000000000000);
        
        $result = $this->manage_model->get_traders_grid_data(array('manage_id'=>3,'limit'=>2));
        print_r($result);
    }
}
    
 

?>
