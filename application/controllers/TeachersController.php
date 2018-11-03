<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class TeachersController extends MY_Controller {
	
	public function search() {
		if(!$this->input->is_ajax_request()) {
			show_404();
		}
		$this->output->enable_profiler(false);
		$search = $this->input->get('q');
		$this->load->model('webforceteacher');
		$this->webforceteacher->where('users.id != ', user_id());
		if(!$search) {
			$foundTeachers = $this->webforceteacher->getList();
		} else {
			$foundTeachers = $this->webforceteacher->search(null, null,$search, array('name', 'forname'));
		}
		if(!$foundTeachers) {
			$foundTeachers = array();
		}
		die( json_encode(array('datas' => $foundTeachers)) );
	}
	
	public function profile() {
		$this->load->library('bo/userManager');
		$this->usermanager->setUserModel('webforceteacher');
		$datas = $this->usermanager->save(user_id(),'teacher/profile');
		$this->layout->view('teachers/profile', array('datas' => $datas));
	}
}
