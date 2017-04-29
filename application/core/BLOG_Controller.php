<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author thibault
 */
class BLOG_Controller extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
	}
	
	
	private function addMessage($type, $message){
		$this->load->library('flashmessages/flashMessagesManager');
		$this->flashmessagesmanager->pushNewMessage($message,$type);
	}

	protected function addError($message) {
		$this->addMessage('error', $message);
	}

	protected function addSuccess($message) {
		$this->addMessage('success', $message);
	}

	protected function addWarnings($message) {
		$this->addMessage('warning', $message);
	}
}

?>
