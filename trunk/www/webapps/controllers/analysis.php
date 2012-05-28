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

    function assign_amount($id)
    {
	$data['agent_plan'] = $this->manage_model->get_agent_plan($id);
	$data['agent']	    = $this->manage_model->get_agent_user_by_id($id);
	$data['agent_id']   = $id;
	$submitted = $this->input->post('submitted');
	if ($submitted)
	{
	    $agent_id = $this->input->post('id');
	    $rate_template_name = $this->input->post('rate');
	    $update_data = array(
		'agent_id'  => $agent_id, 
		'step1'	    => $this->input->post('step1'), 
		'step2'	    => $this->input->post('step2'), 
		'step3'	    => $this->input->post('step3'), 
		'step4'	    => $this->input->post('step4')
	    );
	    $this->manage_model->set_agent_plan($update_data);
	    if ($rate_template_name)
	    {
		$this->manage_model->save_agent_user(array('user_id'=>$agent_id, 'rate_template_name'=>$rate_template_name));
		echo $agent_id;
	    }
	    redirect('analysis/assign_amount/'.$agent_id);
	}
	$this->_template('agent/assign_amount', $data);
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

    function agent_report()
    {
	echo '代理商结算报表';
    }
}

?>
