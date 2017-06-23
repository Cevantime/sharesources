<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class UserprefsController extends MY_Controller {
	
	public function index($key, $value) {
		
		if( ! user_is('teacher')) {
			die('Error during preference save : current user is no teacher');
		}
		
		if( !in_array($key, array('sidebar_collapsed'))){
			die('Error during preference save : no such property');
		}
		$this->load->model('webforceteacher');
		$user = $this->webforceteacher->getId(user('id'), 'array');
		$user['preferences'][$key] = $value;
		$this->webforceteacher->save($user);
		die('ok');
	}
}
