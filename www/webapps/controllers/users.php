<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * controller: users
 * 对业务员管理
 * 
 * @author  zhangxk <zxk0610@gmail.com>
 * @link 
 */
 
 
class Users extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();

    }

    public function report($username)
    {
        $this->load->model("manage_model");
        $result = $this->manage_model->get_user_by_username($username);
        print_r($result);
        
        //$data["result"] = $result;  //add to gloab var
        //$this->_template('login', $data);
    }

    function test()
    {
        $this->load->model("manage_model");
        #$result = $this->manage_model->get_agent_amt(21417,1124106441,1424106441);
        #result = $this->manage_model->get_agent_amt(14011);
        $result = $this->manage_model->get_marker_district(393);
        // foreach ($result as $item)
        // {
            // print_r($item);
            // #echo $item;
        // }
        
        print_r($result);
    }
}
    
 

?>