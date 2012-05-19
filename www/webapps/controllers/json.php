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

    function g_agent()
    {
        $params  = $this->_params();
        $manager = $this->session->userdata("manage");
        if (!isset($manager)) {
            echo json_encode(array());
            return false;
        }
        $params['manage_id'] = $manager['id'];
        $params['sortname']  = 'user_name';
        $result = $this->manage_model->get_agent_grid_data($params);
        echo json_encode($result);
    }
    
    function agents()
    {
        $params  = $this->_params();
        $params['sortname']  = 'user_name';
        $result = $this->manage_model->get_agent_grid_data($params);
        echo json_encode($result);
    }

    function managers()
    {
        $params = $this->_params();
        $result = $this->manage_model->get_manage_grid_data($params);
        echo json_encode($result);
    }
    
    
}
    


?>
