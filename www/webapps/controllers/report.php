<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller {

    public function index()
    {
	$this->load->view('dashboard');
    }

    function test()
    {
	echo 'aaa';
    }
}

