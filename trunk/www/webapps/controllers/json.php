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
        
        $limit = $this->input->get("pagesize");
        
        if (!$limit){
            $data = $this->input->post();
            $limit = isset($data["pagesize"])?$data["pagesize"]:20;
            $page = isset($data["page"])?$data["page"]:1;
            $sortname = isset($data["sortname"])?$data["sortname"]:"user_name";
            $sortorder = isset($data["sortorder"])?$data["sortorder"]:"desc";
            $offset = $limit*($page-1);
        }
        $page = isset($data["page"])?$data["page"]:1;
        $sortname = isset($data["sortname"])?$data["sortname"]:"user_name";
        $offset = $limit*($page-1);
        
        
        $result = $this->manage_model->get_agents_by_manager_id($manage_id["id"],$limit,$offset,$sortname,$sortorder);
        
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
