<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/**
 * Description of home
 *
 * @author thibault
 */
class SearchController extends MY_Controller {
	
	
	public function index() {
		$search = $this->input->get_post('search');
		
		$this->layout->title(translate('Votre recherche'));
		
		if(strlen($search)<= 2){
			add_error(translate('Votre recherche doit contenir plus de 2 caractères'));
			return $this->layout->view('search/index');
		} 
		
		$this->load->model('course');
		$courses = $this->course->keySearch(null, null, $search);
		$this->layout->assign('courses', $courses);
		
		if(user_can('see', 'teacher')) {
			$this->load->model('webforceteacher');
			$teachers = $this->webforceteacher->search(null, null,$search, array('login', 'forname', 'name'));
			$this->layout->assign('teachers', $teachers);
		}
		
		if(user_can('see', 'teachsession')) {
			$this->load->model('teachsession');
			$this->teachsession->prepareShares();
			$sessions = $this->teachsession->search(null, null,$search, array('login', 'name'));
			$this->layout->assign('sessions', $sessions);
		}
		
		$this->layout->view('search/index');
		
	}
}
