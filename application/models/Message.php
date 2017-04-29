<?php

/**
 * Description of Users
 *
 * @author thibault
 */
class Message extends DATA_Model {
	
	public static $TABLE_NAME = 'messages';
	
	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function validationRulesForInsert($datas) {
		$this->load->model('webforceteacher');
		$this->load->model('course');
		return array(
			'content' => array(
				'field' => 'content',
				'label' => translate('Contenu'),
				'rules' => 'required|min_length[5]|max_length[65000]'
			),
			'from_forname' => array(
				'field' => 'from_forname',
				'label' => translate('Prénom'),
				'rules' => ( user_is('users') ? 'required': '' ) .'|min_length[1]|max_length[100]'
			),
			'from_name' => array(
				'field' => 'from_name',
				'label' => translate('Nom'),
				'rules' => ( user_is('users') ? 'required': '' ) .'|min_length[1]|max_length[100]'
			),
			'course_id' => array(
				'field' => 'course_id',
				'label' => translate('Id du cours'),
				'rules' => array(
					'required',
					array('course_exists', array($this->course, 'exists'))
				)
			),
		);
	}
	
	public function validationRulesForUpdate($datas) {
		$this->load->model('webforceteacher');
		$this->load->model('course');
		return array(
			'content' => array(
				'field' => 'content',
				'label' => translate('Contenu'),
				'rules' => 'required|min_length[5]|max_length[65000]'
			),
			'from_forname' => array(
				'field' => 'from_forname',
				'label' => translate('Prénom'),
				'rules' => 'min_length[1]|max_length[100]'
			),
			'from_name' => array(
				'field' => 'from_name',
				'label' => translate('Nom'),
				'rules' => 'min_length[1]|max_length[100]'
			),
			'course_id' => array(
				'field' => 'course_id',
				'label' => translate('Id du cours'),
				'rules' => array(
					array('course_exists', array($this->course, 'exists'))
				)
			),
		);
	}
	
	protected function beforeInsert(&$to_insert = null) {
		$to_insert['from_id'] = user_id();
		$courseId = $_POST['course_id'];
		$this->load->model('course');
		$course = $this->course->getId($courseId);
		$to_insert['to_id'] = $course->user_id;
		return $to_insert;
	}
	
	protected function afterInsert($insert_id, &$to_insert = null) {
		$this->sendMailToTeacher($to_insert);
	}
	
	protected function sendMailToTeacher($message) {
		$this->load->library('mailManager');
		$this->load->model('webforceteacher');
		if(user_is('teacher')) {
			$from = $this->webforceteacher->getId(user_id());
			$forname = $from->forname;
			$name = $from->name;
		} else {
			$from = teachsession();
			$forname = $message['from_forname'];
			$name =  $message['from_name'];
		}
		$this->load->model('course');
		
		$course = $this->course->getId($_POST['course_id']);
		
		$to = $this->webforceteacher->getId($message['to_id']);
		
		$subject = translate('Vous avez reçu un message de '.$forname.' '.$name);
		$mailBody = $this->load->view('messages/mail-template', array(
			'forname' => $forname,
			'name'=>$name,
			'message'=>$message['content'], 
			'from'=>$from,
			'course' => $course
		),true);
		
		if($this->mailmanager->sendMail(
			$subject, 
			$mailBody, 
			$to->email, 
			array($from->email => $forname.' '.$name)

		)) {
			add_success(translate('Votre message a été envoyé par mail au formateur.'));
		} else {
			add_error(translate('L\'email de votre message n\'a pas pu être envoyé au formateur.'
					. 'Veuillez contacter un administrateur pour résoudre ce problème.'));
		}
	}
}

