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

    function agents()
    {
        
        $manage_id = $this->session->userdata("manage");
        if (!isset($manage_id)) {
            echo("no login!");
            return false;
        }
        
        $limit = $this->input->get("limit");
        if (!$limit){
            $limit = $this->input->post("limit");
            $offset = $this->input->post("offset");
        }
        $offset = $this->input->get("offset");
        
        $result = $this->manage_model->get_agents_by_manager_id($manage_id["id"],$limit,$offset);
        
        echo json_encode($result);
    }
}
    


?>
