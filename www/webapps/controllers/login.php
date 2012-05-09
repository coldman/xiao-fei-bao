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
            //$captcha	    = $this->input->post('captcha');
            //$remember	    = $this->input->post('remember');
	    
	    $login	    = $this->auth->login($username, $password);
	    if ($login)
	    {
		$redirect   = 'dashboard';
		redirect($redirect);
	    }
	    else 
	    {
		$this->session->set_flashdata('redirect', $redirect);
		$this->session->set_flashdata('msg', '登录认证失败');
		return;
		redirect('login');
	    }
        }
        
        $this->_template('login', $data);
    }

    function logout()
    {
	$this->auth->logout();
	$this->session->set_flashdata('msg', '您已经登出.');
	redirect('login');
    }
}

?>
