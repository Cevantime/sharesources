<?php

class MY_Controller extends MX_Controller {

	// Site global layout

	public function __construct() {
		parent::__construct();
		
		if(($userId = $this->session->userdata('user_id'))){
			if(user_is('administrators', $userId)){
				$this->loginmanager->setUserModel('webforceadmin');
			} else if(user_is('teacher', $userId)){
				$this->loginmanager->setUserModel('webforceteacher');
			} else {
				$this->loginmanager->setUserModel('webforceuser');
			}
		}
		
		if(!is_connected()) {
			$this->session->set_userdata('login_redirect_url', current_url());
			redirect('login');
		}
		
		$this->layout->assign('currentSession', $this->session->userdata('current_session'));
		
		$this->layout->setLayout('layout/creative');
		
		$maintenance = $this->configuration->getValue('maintenance');
		
		if (($this->isEnv('development') || $maintenance) && !$this->input->is_ajax_request()) {
			$this->output->enable_profiler(true);
		}
	}
	
	protected function isEnv($env){
		return ENVIRONMENT === $env;
	}
	
	protected function checkIfUserCan($action,$type,$target='*') {
		if(!user_can($action,$type,$target)) {
			add_error('Vous ne pouvez pas effectuer cette action');
			redirect('home');
		}
	}
	
	public function addSynthaxHighlightning() {
		$this->layout->css('assets/vendor/css/highlightjs/styles/github.css');
		$this->layout->js('assets/vendor/js/highlightjs/highlight.pack.js');
		$this->layout->jscript('hljs.initHighlightingOnLoad();');
	}
	
//	public function addDataTableScripts() {
//		$this->layout->css('assets/vendor/css/datatable.bootstrap.min.css');
//		$this->layout->js('assets/vendor/js/datatable.min.js');
//		$this->layout->js('assets/vendor/js/datatable.bootstrap.min.js');
//	}
}
