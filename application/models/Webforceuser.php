<?php

require_once APPPATH.'modules/memberspace/models/User.php';

class Webforceuser extends User {
	
	const TABLE_NAME = 'webforce_users';
	
	protected $_courses = array();
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function delete($where = null) {
		$rows = $this->get($where);
		foreach($rows as $row) {
			unlink($row->avatar);
		}
		parent::delete($where);
	}
	
	protected function beforeUpdate(&$datas = null, $where = null) {
		
		$this->unlink('avatar', $datas);
		
		parent::afterUpdate($datas, $where);
	}
	
	public function connect($id = null) {
		$this->load->library('teachSessionManager');
		
		$this->load->library('memberspace/loginManager');
		
		if( ! $id ) {
			$id = $this->getData('id');
		}
		$this->normalConnect($id);
		
		$session = $this->teachsessionmanager->createSessionFromUserId($id);
		
		if($this->is('teacher', $id)) {
			$this->loginmanager->setUserModel('webforceteacher');
			
		} else if($session && $session->id == $id){
			$this->loginmanager->setUserModel('teachsession');
		}
		
	}
	
	public function normalConnect($id = null) {
		return parent::connect($id);
	}
	
	public function getCourses($userId = null, $refresh = false) {
		if(!$userId) {
			$userId = $this->getData('user_id');
		}
		if( ! isset($this->_courses[$userId]) || $refresh) {
			$this->load->model('course');
			$this->_courses[$userId] = $this->course->get(array('user_id'=> $userId));
		}
		return $this->_courses[$userId];
	}
	
	public function isAuthor($courseId, $user) {
		$courses = $this->getCourses($user->id);
		if(is_object($courseId)) {
			$courseId = $courseId->id;
		}
		if( ! $courses) return false;
		foreach( $courses as $course) {
			if($course->id == $courseId) {
				return true;
			}
		}
		return false;
	}
	
	public function isSelf($userId, $user) {
		return $user->id == $userId;
	}
	
	
	public function uploadPaths() {
		$uploadPath = parent::uploadPaths();
		if($uploadPath === FALSE) $uploadPath = array();
		return array_merge($uploadPath, array('avatar' => 'uploads/avatars'));
	}
	
	protected function prepareSearch($limit = null, $offset = null, $search = null, $columns = null) {
		if ($columns === null && !$this->getData('columns')) {
			$columns = $this->getDataColumns();
		} else if ($columns === null) {
			$columns = $this->getData('columns');
		}

		if ($search === null) {
			$search = $this->search;
		}

		if ($limit !== null) {
			$this->db->limit($offset, $limit);
		}
		
		$this->db->group_start();
		
		if( ! is_array($search)) {
			$search = explode(' ', $search);
		}
		
		foreach ($columns as $col) {
			foreach($search as $s) {
				$this->db->or_like($col, $s);
			}
		}
		
		$this->db->group_end();
	}
	
	public function search($limit = null, $offset = null, $search = null, $columns = null) {
		$this->db->query('SET @matching := 0');
		if( ! is_array($search)) {
			$search = explode(' ', $search);
		}
		
		foreach ($columns as $col) {
			foreach($search as $s) {
				$s = $this->escape_like_str($s);
				$this->select("if($col LIKE '%$s%' ESCAPE '!', @matching := @matching + 1, 'dummy')", FALSE);
			}
			
		}
		$this->select("(@matching) as matching");
		// weird but could not make it working with @matching := 0 
		$this->select("(@matching := @matching - @matching) as 'reset' ");
		$this->order_by('matching DESC');
		
		return parent::search($limit, $offset, $search, $columns);
	}
	
	public function validationRulesForInsert($datas) {
		$rules = parent::validationRulesForInsert($datas);
		
		return array_merge(
			$rules,
			
			array(
				'avatar' => array(
					'field' => 'avatar',
					'label' => translate('Avatar'),
					'rules' => 'file_size_max[100KB]|file_allowed_type[image]'
				)
				
			)
				
		);
	}
	
	public function validationRulesForUpdate($datas) {
		$rules = parent::validationRulesForUpdate($datas);
		
		return array_merge(
			$rules,
			
			array(
				'avatar' => array(
					'field' => 'avatar',
					'label' => translate('Avatar'),
					'rules' => 'file_size_max[100KB]|file_allowed_type[image]'
				)
				
			)
				
		);
	}
}

