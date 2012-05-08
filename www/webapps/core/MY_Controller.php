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
    }

    function _template($template, $data=array())
    {
	$this->load->view($template, $data);
    }
}

?>
