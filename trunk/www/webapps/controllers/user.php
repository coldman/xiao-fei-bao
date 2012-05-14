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
	echo '修改密码';
    }

    function info()
    {
	echo '个人信息';
    }

    function test()
    {
        $this->load->model("manage_model");
        #$result = $this->manage_model->get_agent_list(10000);
        #result = $this->manage_model->get_agent_amt(14011);
        #$result = $this->manage_model->get_trader_orders(13952,0,2000000000000000);
        
        $result = $this->manage_model->get_unmarked_city();
        // foreach ($result as $item)
        // {
            // print_r($item);
            // #echo $item;
        // }
        
        print_r($result);
    }
}
    
 

?>
