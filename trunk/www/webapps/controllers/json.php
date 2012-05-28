<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * controller: users
 * 对业务员管理
 * 
 * @author  zhangxk <zxk0610@gmail.com>
 * @link 
 */
 
 
class Json extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("manage_model");
    }

    function change_pwd()
    {
        echo '修改密码';
    }

    function info()
    {
        echo '个人信息';
    }

    function g_agents()
    {
        $params  = $this->_params();
        $manager = $this->session->userdata("manage");
        if (!isset($manager)) {
            echo json_encode(array());
            return false;
        }
	if ($manager['role_type'] == 0)
	{
	    $params['manage_id'] = $manager['id'];  
	}
        $params['sortname']  = 'user_name';
        
        $result = $this->manage_model->get_agent_grid_data($params);
        
        echo json_encode($result);
    }
    
    function agents()
    {
        $params  = $this->_params();
        $params['sortname']  = 'user_name';
        $params['select'] = "user_name,real_name,email,sex,qq,msn,comp_phone,comp_name";
        $params['is_agent']  = 1;
        unset($params['limit']);
        $result = $this->manage_model->get_agent_grid_data($params);
        echo json_encode($result);
    }

    function managers()
    {
        $params = $this->_params();
        $result = $this->manage_model->get_manage_grid_data($params);
        echo json_encode($result);
    }
    
    function g_traders()
    {
        $params = $this->_params();
        $manager = $this->session->userdata("manage");
        if (!isset($manager)) {
            echo json_encode(array());
            return false;
        }
	if ($manager['role_type'] == 0)
	{
	    $params['manage_id'] = $manager['id'];
	}
	$params['sortname'] = 'user_name';
        
        $result = $this->manage_model->get_traders_grid_data($params);
	echo json_encode($result);
    }
    
    function undispatch_agents()
    {
        $result = $this->manage_model->get_undispatch_agents();
        echo json_encode($result);
    }
    
    function del_manager($id)
    {
        if ($this->manage_model->del_manager($id)){
            echo json_encode(array("result"=>true));
        }
        else{
            echo json_encode(array("result"=>false));
        }
    }
    
    // $flag   1-省 2-市 3-区
    function regions()
    {
	$fid = $this->input->get("fid");
	if (!$fid) 
	{
	    $fid = 1;
	}
        //$ret = $this->manage_model->get_areas($parent_id);
	$ret = $this->manage_model->get_jstree_region_data($fid);
	echo json_encode($ret);
    }
    
    function selected_agents($id)
    {
        echo json_encode($this->manage_model->get_agent_by_id($id));
    }
    
    function agent_rates()
    {
	$params = $this->_params();
	$result = $this->manage_model->get_agent_rates($params);
	echo json_encode($result);
    }
    
    function del_agent_rate($id)
    {
	$this->manage_model->del_agent_rate($id);
	echo json_encode(array('result'=>true));
    }
}
    


?>
