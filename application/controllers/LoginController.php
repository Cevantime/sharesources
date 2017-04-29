<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class LoginController extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('memberspace/loginManager', 'webforceuser');
		if(is_connected()) {
			redirect('home');
		}
		$this->layout->setLayout('layout/login_creative');
		
	}
	
	public function index() {
		
		$this->load->model('teachsession');
		
		$this->layout->view('login');
	}
}