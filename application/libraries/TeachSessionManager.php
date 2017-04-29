<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SessionManager
 *
 * @author cevantime
 */
class TeachSessionManager {
	
	private $_ci;
	
	public function __construct() {
		$this->_ci =& get_instance();
		$this->_ci->load->library('session');
	}
	
	public function createSessionFromUserId($userId) {
		$this->_ci->load->model('webforceuser');
		
		// we look for the user first
		$wuser = $this->_ci->webforceuser->getId($userId);
		
		if( ! $wuser ) {
			// no user, no session !
			return false;
		}
		// if there is a user, it let's check if it is not a session
		$this->_ci->load->model('teachsession');
		
		$session = $this->_ci->teachsession->getId($wuser->id);
		
		if($session) {
			unset($session->password);
			$this->_ci->session->set_userdata('current_teachsession', $session);
			return $session;
		}
		
		return false;
	}
	
	public function setCurrentTeachSession($idTeachSession) {
		$this->_ci->load->model('teachsession');
		
		$session = $this->_ci->teachsession->getId($idTeachSession);
		
		if($session) {
			unset($session->password);
			$this->_ci->session->set_userdata('current_teachsession', $session);
			return true;
		}
	}
	
	public function getCurrentTeachSession() {
		return $this->_ci->session->userdata('current_teachsession');
	}
}
