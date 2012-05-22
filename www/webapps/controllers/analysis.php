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
	$data['manager'] = $this->session->userdata('manage');
	$this->_template('agent/list', $data);
    }

    function view_agent($id)
    {
	$data['agent'] = $this->manage_model->get_user_by_id($id);#get_agent_by_id($id);
	$this->_template('agent/view', $data);
    }

    function trader()
    {
	$this->_template('trader/list');
    }
    
    function view_trader($id)
    {
	$data['trader']	= $this->manage_model->get_user_by_id($id);
	$this->_template('trader/view', $data);
    }

    function region()
    {
	$this->_template('region');
    }
}

?>
