<?php

use mikehaertl\wkhtmlto\Pdf;

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
class SessionsController extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('teachsession');
	}
	
	public function add() {
		$this->layout->title('Ajouter une session');
		$sessionModel = 'teachsession';
		$datas = $this->save(null,$sessionModel);
		$this->layout->view('sessions/save', array('popSaveSession'=> $datas));
	}
	
	public function edit($id) {
		$this->layout->title('Modifier une session');
		$datas = $this->save($id);
		$this->layout->view('sessions/save', array('popSaveSession'=> $datas, 'isEditSession'=>true));
	}
	
	public function all(){
		$this->layout->title('Toutes les sessions');
		$this->load->library('mypagination');
		$this->checkIfUserCan('see','teachsession','*');
		$this->load->model('teachsession');
		$id_pagination = 'teachsessions-list';
		$this->teachsession->prepareShares();
		$sessions = $this->teachsession->getList();
		$this->layout->assign('sessions', $sessions);
		$this->layout->assign('id_pagination_sessions_list', $id_pagination);
		$this->layout->view('sessions/all');
	}
	
	public function setCurrent($teachSessionId) {
		$this->load->library('teachSessionManager');
		if(user_can('set_current', 'teachsession', $teachSessionId)){
			$this->teachsessionmanager->setCurrentTeachSession($teachSessionId);
			if(user_is('teacher')) {
				$user = user();
				$user->current_teachsession = $teachSessionId;
				$user->save();
			}
			add_success('Votre session a bien été changée');
			
		}
		
		if($this->input->get('redirect')) {
			redirect($this->input->get('redirect'));
		}
		
		redirect('home');
	}
	
	public function delete($id) {
		$userModel = 'teachsession';
		$this->load->helper('memberspace/authorization');
		if(user_can('delete',$userModel,$id)){
			$this->load->model($userModel);
			$this->user->deleteId($id);
			add_success(translate('La session a bien été supprimée'));
		} else {
			add_error(translate('Vous n\'avez pas le droit de supprimer cette session'));
		}
		redirect('sessions/all');
	}
	
	public function mines() {
		$this->layout->title('Mes sessions');
		$this->load->model('teachsession');
		$this->checkIfUserCan('see','teachsession','*');
		$sessions = $this->teachsession->getUserTeachSessions();
		$this->layout->assign('sessions', $sessions);
		$this->layout->view('sessions/mines');
	}
	
	public function mySessions() {
		$this->load->model('teachsession');
		$sessions = $this->teachsession->getUserTeachSessions();
		$this->load->view('sessions/includes/select-session', array('sessions' => $sessions));
	}
	
	public function save($id = null) {
		$sessionModel = 'teachsession';
		$model = $sessionModel;
		$this->load->helper('memberspace/authorization');
		$this->load->helper('flashmessages/flashmessages');
		$this->load->model($sessionModel);
		$this->load->helper('form');
		$datas = $this->$model->getId($id,'array');
        $datas = $datas ?: [];
		if(isset($_POST) && isset($_POST['save-session'])) {
			$datas = array_merge($datas,$_POST);
			unset($_POST['save-session']);
			$is_update = false;
			if(isset($_POST['id']) && $_POST['id']) {
				$is_update = true;
				if(!user_can('update',$sessionModel, $_POST['id'])){
					add_error(translate('Vous ne pouvez pas modifier cette session'));
					redirect('sessions/mines');
				}
			} else {
				if(!user_can('add',$sessionModel)) {
					add_error(translate('Vous ne pouvez pas ajouter cette session'));
					redirect('sessions/mines');
				}
			}
			if($this->$model->fromPost()) {
				add_success(translate('La session a bien été ').($is_update ? translate('mise à jour') : translate('ajoutée')));
				redirect('sessions/mines');
			} else {
				add_error($this->form_validation->error_string());
			}
			
		}
		return $datas;
	}

	private function getLang() {
		$lang = $this->input->post_get('lang');
		if ($lang)
			return $lang;
		$this->load->helper('locale');
		return locale();
	}

	public function requestShare($sessionId) {
		$this->processShare($sessionId);
	}
	
	
	public function requestUnshare($sessionId) {
		$this->processShare($sessionId, false);
	}
	
	private function processShare($sessionId, $share = true) {
		if( ! $this->input->is_ajax_request()
			|| ! user_can('grab', 'teachsession', $sessionId)) {
			show_404();
			return;
		}
		
		$this->load->model('teachsession');
		
		$session = $this->teachsession->getId($sessionId);
		
		if(! $session) {
			$status = 'failed';
			$html = translate('Erreur');
		} else {
			if($share) {
				$this->teachsession->shareWith(user_id(), $sessionId);
			} else {
				$this->teachsession->unshare(user_id(), $sessionId);
			}
			$status = 'ok';
			$html = $this->load->view('sessions/includes/sessions-actions',
				array(
					'session' => $session,
					'teachsession' => teachsession()
				),
				true
			);
			
		}
		
		
		die(json_encode(array('status'=>$status, 'html'=> $html)));
	}
	

}
