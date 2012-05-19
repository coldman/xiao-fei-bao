<?php
/**
 * Manage controller
 * @Author	RobinHuang
 * @Version	1.0
 */
class MY_Controller extends CI_Controller
{
    function __construct()
    {
	parent::__construct();
	$this->load->model(array('manage_model'));
    }

    function _template($template, $data=array())
    {
	$data['media_root'] = site_url('static').'/';
	$this->load->view($template, $data);
    }

    function _params()
    {
	$params = array();
	$data	= $this->input->post();
	$page   = isset($data['page'])?$data['page']:1;
	$params['limit']    = isset($data['pagesize'])?$data['pagesize']:20;
	$params['offset']   = ($page-1) * $params['limit'];
	$params['sortname'] = isset($data['sortname'])?$data['sortname']:'id';
	$params['sortorder']= isset($data['sortorder'])?$data['sortorder']:'desc';
	return $params;
    }
}

?>
