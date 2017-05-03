<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeachersController
 *
 * @author cevantime
 */

class UsersController extends MY_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->load->library('bo/userManager');
	}
	
	public function all($userModel = 'memberspace-user'){
		$userModelFormated = $this->filterModel($userModel);
		$this->checkIfUserCan('see',$userModelFormated,'*');
		$this->load->model($userModelFormated);
		$model = pathinfo($userModelFormated)['filename'];
		$users = $this->$model->getList();
		$this->layout->assign('users', $users);
		$this->layout->view('admin/'.$userModel.'s/all', array('modelName'=>$userModelFormated));
	}
	
	public function add($userModel = 'memberspace-user') {
		$this->layout->title(translate('Ajout d\'un formateur'));
		$userModelFormated = $this->filterModel($userModel);
		$this->checkIfUserCan('add',$userModelFormated);
		$this->usermanager->setUserModel($userModelFormated);
		$datas = $this->usermanager->save(null, $this->redirectAction($userModelFormated));
		$this->layout->view('admin/'.$userModel.'s/save', array('popSaveUser'=> $datas,'modelName'=>$userModelFormated));
	}
	
	public function edit($userModel = 'memberspace-user', $id = null) {
		$this->layout->title(translate('Modification d\'un formateur'));
		$userModelFormated = $this->filterModel($userModel);
		$this->checkIfUserCan('edit', $userModelFormated, $id);
		$this->usermanager->setUserModel($userModelFormated);
		$datas = $this->usermanager->save($id, $this->redirectAction($userModelFormated));
		$this->layout->view('admin/'.$userModel.'s/save', array('popSaveUser'=> $datas, 'isEditUser'=>true,'modelName'=>$userModelFormated));
	}
	
	public function delete($userModel = 'memberspace-user', $id = null) {
		$userModelFormated = $this->filterModel($userModel);
		$this->checkIfUserCan('delete', $userModelFormated, $id);
		$this->usermanager->setUserModel($userModelFormated);
		$this->usermanager->delete($id, $this->redirectAction($userModelFormated));
	}
	
	protected function filterModel($model) {
		return str_replace('-', '/', $model);
	}
	
	private function redirectAction($userModel){
		switch ($userModel){
			case 'webforceteacher':
				return 'teachers';
			case 'webforceadmin':
				return 'administrators';
				
			default :
				return 'home';
		}
	}
 }
