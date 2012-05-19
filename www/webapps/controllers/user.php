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
	    if ($old_pwd != $manager['password']) 
	    {
		$this->session->set_flashdata('error', '原密码不正确，请重新输入！');
	    }
	    else
	    {
		$this->manage_model->save_manage(array('id'=>$manager['id'], 'password'=>md5($new_pwd)));
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
	$this->_template('manager_list');
    }

    function test()
    {
        $this->load->model("manage_model");
        #$result = $this->manage_model->get_agent_list(10000);
        #result = $this->manage_model->get_agent_amt(14011);
        #$result = $this->manage_model->get_trader_orders(13952,0,2000000000000000);
        
        $result = $this->manage_model->get_agents_by_manager_id(1);
        // foreach ($result as $item)
        // {
            // print_r($item);
            // #echo $item;
        // }
        //echo $result;
        //print_r($result);
    }
}
    
 

?>
