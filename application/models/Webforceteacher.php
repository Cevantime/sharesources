<?php

require_once APPPATH . 'models/Webforceuser.php';

class Webforceteacher extends Webforceuser {

	const TABLE_NAME = 'webforce_teachers';

	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function getRow($where = array(), $type = 'object', $columns = null) {
		$row = parent::getRow($where, $type, $columns);
		if(is_array($row)){
			$row['preferences'] = json_decode($row['preferences'], true);
			
		} else if(is_object($row)) {
			$row->preferences = json_decode($row->preferences);
		}
		return $row;
	}

	public function connect($id = null) {
		parent::connect($id);
		$this->load->library('teachSessionManager');
		if ($this->current_teachsession) {
			$this->teachsessionmanager->setCurrentTeachSession($this->current_teachsession);
		} else {
			// if the teacher has only one session, set it as current by default
			$this->load->model('teachsession');
			$sessions = $this->teachsession->getUserTeachSessions();
			if ($sessions && count($sessions) === 1) {
				$this->teachsessionmanager->setCurrentTeachSession($sessions[0]->id);
			}
		}
	}

	public function afterInsert($insert_id, &$to_insert = null) {
		parent::afterInsert($insert_id, $to_insert);
		$this->load->model('memberspace/group');
		$tearchersGroup = $this->group->getByName('teacher');
		$this->addToGroup($tearchersGroup->id, $insert_id);
		$password = $this->input->post('password');
		$subject = $this->load->view('mailing/mail-subject-new-teacher', array(), true);
		$teacher = (object)$to_insert;
		$body = $this->load->view('mailing/mail-body-new-teacher', array(
			'plainPassword' => $password,
			'teacher' => $teacher
		), true);
		$this->sendEmailToTeacher($teacher, $subject, $body);
	}

	public function sendEmailToTeacher($teacher, $subject, $message) {
		$this->load->library('mailManager');
		if($this->mailmanager->sendMail(
			$subject, 
			$message, 
			$teacher->email, 
			array(config('email_for_mailing' , 'admin@noreply.org') => config('name_for_mailing', translate('L\'administration')))

		)) {
			add_success(translate('Un email de notification a été envoyé au formateur.'));
		} else {
			add_error(translate('L\'email de notification n\'a pas pu être envoyé au formateur.'
					. 'Veuillez contacter un administrateur pour résoudre ce problème.'));
		}
//		$this->load->library('email');
//
//		$this->email->from(config('email_for_mailing' , 'admin@noreply.org'), config('name_for_emailing',translate('Administration')));
//		$this->email->to($teacher->email);
//
//		$this->email->subject($subject);
//		$this->email->message($message);
//
//		$this->email->send();
		
	}

	public function validationRulesForInsert($datas) {
		$rules = parent::validationRulesForInsert($datas);

		return array_merge(
			$rules, array(
				'forname' => array(
					'field' => 'forname',
					'label' => translate('Prénom'),
					'rules' => 'required|trim|min_length[1]|max_length[100]'
				),
				'name' => array(
					'field' => 'name',
					'label' => translate('Nom'),
					'rules' => 'required|trim|min_length[1]|max_length[100]'
				)
			)
		);
	}

	public function validationRulesForUpdate($datas) {
		$rules = parent::validationRulesForUpdate($datas);

		return array_merge(
			$rules, array(
				'forname' => array(
					'field' => 'forname',
					'label' => translate('Prénom'),
					'rules' => 'trim|min_length[1]|max_length[100]'
				),
				'name' => array(
					'field' => 'name',
					'label' => translate('Nom'),
					'rules' => 'trim|min_length[1]|max_length[100]'
				)
			)
		);
	}

}
