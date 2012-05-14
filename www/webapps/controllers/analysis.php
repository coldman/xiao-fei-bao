<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Data analysis
 * 数据分析与查询
 * 
 * @author  zhangxk <zxk0610@gmail.com>
 * @link 
 */
 
 
class Analysis extends MY_Controller 
{
    function __construct()
    {
	parent::__construct();

    }

    function turnover()
    {
	$user = $this->session->userdata('manage');
	$data = $this->manage_model->get_users_by_manager_id($user['id'], 10, 2);
	print_r($data);
    }

    function agent()
    {
	echo '代理商列表';
    }

    function trader()
    {
	echo '商家数据';
    }

}

?>
