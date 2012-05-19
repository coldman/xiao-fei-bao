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
	$this->_template('pwd');
    }

    function info()
    {
	$this->_template('info');
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
