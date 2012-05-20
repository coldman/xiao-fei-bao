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
	$this->_template('agent/list');
    }

    function view_agent($id)
    {
	$data['agent'] = $this->manage_model->get_agent_by_id($id);
	$this->_template('agent/view', $data);
    }

    function trader()
    {
	$this->_template('trader/list');
    }

}

?>
