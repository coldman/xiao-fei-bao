<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Manage login application.
 * @Author	RobinHuang
 * @Version	1.0
 */

class Login extends MY_Controller
{
    function __construct()
    {
	parent::__construct();
    }

    function index()
    {
	$data['redirect']   = $this->session->flashdata('redirect');
	$submitted	    = $this->input->post('submitted');
	if ($submitted)
	{
	    $username	    = $this->input->post('username');
	    $password	    = $this->input->post('password');
	    $captcha	    = $this->input->post('captcha');
	    $remember	    = $this->input->post('remember');
	}
	
	$this->_template('login', $data);
    }

    function logout()
    {
	//
    }
}

?>
