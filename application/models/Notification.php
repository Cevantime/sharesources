<?php

/**
 * Description of Users
 *
 * @author thibault
 */
class Notification extends DATA_Model {
	
	public static $TABLE_NAME = 'notifications';
	
	const VISIBILITY_PRIVATE = 0;
	const VISIBILITY_ADMINS = 1;
	const VISIBILITY_TEACHERS = 2;
	const VISIBILITY_SESSIONS = 4;
	const VISIBILITY_PUBLIC = 7;
	
	const TYPE_NEW_COURSE = 'NEW_COURSE';
	const TYPE_NEW_PUBLIC_COURSE = 'NEW_PUBLIC_COURSE';
	const TYPE_NEW_COURSE_SHARE_TEACHER = 'NEW_COURSE_SHARE_TEACHER';
	const TYPE_NEW_COURSE_SHARE_SESSION = 'NEW_COURSE_SHARE_SESSION';
	const TYPE_CUSTOM = 'CUSTOM';
	
	
	public static $VALUE_LABEL_ASSOC;

	public function __construct() {
		parent::__construct();
		self::$VALUE_LABEL_ASSOC = array(
			self::VISIBILITY_PRIVATE => translate('à personne'),
			self::VISIBILITY_PUBLIC => translate('à tout le monde'),
			self::VISIBILITY_TEACHERS => translate('aux formateurs'),
			self::VISIBILITY_SESSIONS => translate('aux apprenants'),
			self::VISIBILITY_ADMINS => translate('aux administrateurs'),
			self::VISIBILITY_SESSIONS + self::VISIBILITY_TEACHERS => translate('formateurs et apprenants'),
			self::VISIBILITY_ADMINS + self::VISIBILITY_TEACHERS => translate('formateurs et administrateurs'),
			self::VISIBILITY_SESSIONS + self::VISIBILITY_ADMINS => translate('administrateurs et apprenants'),

		);
	}
	
	public function getTableName() {
		return self::$TABLE_NAME;
	}

	public function getText($notification) {
		if( ! is_null($notification->infos) && is_string($notification->infos)) {
			$notification->infos = json_decode($notification->infos);
		}
		switch($notification->type) {
			case self::TYPE_NEW_COURSE :
			case self::TYPE_NEW_COURSE_SHARE_SESSION :
			case self::TYPE_NEW_COURSE_SHARE_TEACHER :
			case self::TYPE_NEW_PUBLIC_COURSE :
				$courseId = $notification->infos->courseId;
				$this->load->model('course');
				$course = $this->course->getId($courseId);
				if(!$course) {
					$text = $this->load->view(
						'notifications/obsolete',
						array('course' => $course),
						true
					);
				} else {
					$text = $this->load->view(
						'notifications/'.strtolower(str_replace('_', '-', $notification->type)),
						array('course' => $course),
						true
					);
					
				}
				return $text;
			case self::TYPE_CUSTOM: 
				return $notification->infos->text;
		}
	
	}
	
	public function getLink($notification) {
		if( ! is_null($notification->infos) && is_string($notification->infos)) {
			$notification->infos = json_decode($notification->infos);
		}
		switch($notification->type) {
			case self::TYPE_NEW_COURSE :
			case self::TYPE_NEW_COURSE_SHARE_SESSION :
			case self::TYPE_NEW_COURSE_SHARE_TEACHER :
			case self::TYPE_NEW_PUBLIC_COURSE :
				$courseId = $notification->infos->courseId;
				return 'courses/see/'.$courseId;
			case self::TYPE_CUSTOM: 
				return $notification->infos->url;
		}
	}

	public function getUnreadNotifications($userId = null) {
		if(!$userId) {
			if( $this->getData('userId')) {
				$userId = $this->getData('userId');
			} else {
				$userId = user_id();
			}
		}
		
		$this->load->model('userhasnotification');
		$this->load->model('usersawnotification');
		
		$subqueryVisible = $this->userhasnotification
				->select('notification_id')
				->where('user_id', $userId)
				->get_compiled_select($this->userhasnotification->getTableName());
		
		$subqueryAlreadySeen = $this->usersawnotification
				->select('notification_id')
				->where('user_id', $userId)
				->get_compiled_select($this->usersawnotification->getTableName());
		
		$this->where('id NOT IN ('.$subqueryAlreadySeen.')',NULL, FALSE);
		$this->group_start();
		$this->where('id IN ('.$subqueryVisible.')', NULL, FALSE);
		if(user_is('root')) {
			$this->or_where('visibility & '.self::VISIBILITY_PUBLIC.' > 0', NULL, false);
		}
		if(user_is('administrators')) {
			$this->or_where('visibility & '.self::VISIBILITY_ADMINS.' > 0', NULL, false);
		}
		if(user_is('teacher')) {
			$this->or_where('visibility & '.self::VISIBILITY_TEACHERS.' > 0', NULL, false);
		}
		if(user_is('users')) {
			$this->or_where('visibility & '.self::VISIBILITY_SESSIONS.' > 0', NULL, false);
		}
		$this->group_end();
		
		$this->order_by('created_time DESC');
		
		$notifications = $this->get();
		
		if($notifications) {
			foreach($notifications as $notif) {
				$notif->text = $this->getText($notif);
			}
		}
		return $notifications;
	}
	
	public function create($type, $visibility = null, $infos = null, $userIds = null) {
		
		if(is_null($visibility)) {
			switch ($type) {
				case self::TYPE_NEW_COURSE :
					$visibility = self::VISIBILITY_ADMINS;
					break;
				case self::TYPE_NEW_COURSE_SHARE_SESSION :
					$visibility = self::VISIBILITY_PRIVATE;
					break;
				case self::TYPE_NEW_COURSE_SHARE_TEACHER :
					$visibility = self::VISIBILITY_PRIVATE;
					break;
				case self::TYPE_NEW_PUBLIC_COURSE :
					$visibility = self::VISIBILITY_TEACHERS;
					break;
				case self::TYPE_CUSTOM: 
					$visibility = self::VISIBILITY_PUBLIC;
				}
		}
		
		$id = $this->insert(array(
			'type'=>$type,
			'visibility' => $visibility,
			'infos' => is_array($infos) ? json_encode($infos) : null
		));
		
		if($userIds) {
			$this->load->model('userhasnotification');
			$links =array();
			foreach ($userIds as $userId) {
				$links[] = array('user_id' => $userId,'notification_id' => $id);
			}
			$this->userhasnotification->updateGroup($links);
		}
	}
	
	public function markAsSeen($notificationId = null, $userId = null) {
		
		if( ! $notificationId) {
			if($this->getData('id')){
				$notificationId = $this->getData('id');
			}
		}
		if(!$userId) {
			if($this->getData('userId')) {
				$userId = $this->getData('userId');
			} else if(user_id()){
				$userId = user_id();
			}
		}
		
		$this->load->model('usersawnotification');
		
		if( ! $this->usersawnotification->get(array('user_id'=>$userId, 'notification_id' => $notificationId))) {
			$this->usersawnotification->insert(array('user_id'=>$userId, 'notification_id' => $notificationId));
		}
		
	}
	
	public function validationRulesForInsert($datas) {
		return array(
			'visibility' => array(
				'field' => 'visibility',
				'label' => translate('Partager à'),
				'rules' => 'required|less_than_equal_to['.self::VISIBILITY_PUBLIC.']|greater_than_equal_to['.self::VISIBILITY_PRIVATE.']'
			),
			'url' => array(
				'field' => 'url',
				'label' => translate('URL'),
				'rules' => 'required|valid_url'
			),
			'message' => array(
				'field' => 'message',
				'label' => translate('Message'),
				'rules' => 'required|min_length[2]|max_length[255]'
			) 
		);
	}
	public function validationRulesForUpdate($datas) {
		return array(
			'visibility' => array(
				'field' => 'visibility',
				'label' => translate('Partager à'),
				'rules' => 'less_than_equal_to['.self::VISIBILITY_PUBLIC.']|greater_than_equal_to['.self::VISIBILITY_PRIVATE.']'
			),
			'url' => array(
				'field' => 'url',
				'label' => translate('URL'),
				'rules' => 'valid_url'
			),
			'message' => array(
				'field' => 'message',
				'label' => translate('Message'),
				'rules' => 'min_length[2]|max_length[255]'
			),
			'id' => array(
				'field' => 'id',
				'label' => translate('Id notification'),
				'rules' => array(
					'required',
					array('notif_exists' , array($this,'exists'))
				)
			)
		);
	}
	
	protected function beforeInsert(&$to_insert = null) {
		
		if( empty($to_insert['type'])) {
			$to_insert['type'] = self::TYPE_CUSTOM;
			$to_insert['infos'] = json_encode(
				array(
					'text' => $_POST['message'],
					'url' => $_POST['url']
			));
		}
		$to_insert['created_time'] = time();
	}
	
	protected function beforeUpdate(&$datas = null, $where = null) {
		$infos = array();
		if(isset($_POST['message'])){
			$infos['text'] = $_POST['message'];
		}
		if(isset($_POST['url'])){
			$infos['url'] = $_POST['url'];
		}
		
		$datas['infos'] = $infos;
	}
	
	public function getList($limit = null, $offset = null, $type = 'object', $columns = null) {
		$list = parent::getList($limit, $offset, $type, $columns);
		if(!$list) 
			return $list;
		foreach($list as $notif) {
			$notif->infos = json_decode($notif->infos);
		}
		return $list;
	}
}

