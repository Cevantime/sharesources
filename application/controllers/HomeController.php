<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class HomeController extends MY_Controller {
	
	public function index() {
		$this->layout->title('Accueil');
//		$this->layout->view('home');
		redirect('courses');
	}
	
}
