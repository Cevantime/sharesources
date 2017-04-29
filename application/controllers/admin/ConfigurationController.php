<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ConfigurationController extends MY_Controller{
	
	public function index() {
		$this->checkIfUserCan('*', 'configuration');
		$this->load->model('configuration');
		$this->layout->title(translate('Configuration'));
		$post = $_POST;
		$pop = $this->configuration->getValues();
		
		if($post) {
			$pop = array_merge($pop,$post);
			$this->configuration->fromPost();
			if($this->configuration->getLastErrors()){
				add_error($this->configuration->getLastErrorsString());
			} else {
				add_success(translate('Les changements ont bien été appliqués'));
				redirect('admin/configuration');
			}
		}
		
		$this->layout->view('admin/configuration/index', array('pop'=> $pop));
		
	}
}