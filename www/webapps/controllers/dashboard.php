<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller 
{
    function __construct()
    {
	parent::__construct();

	$this->auth->is_logged_in(uri_string());
    }

    public function index()
    {
	$data['manager'] = $this->session->userdata('manage');
	$this->_template('dashboard', $data);
    }

    function test()
    {
	echo 'aaa';
    }
}

