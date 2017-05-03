<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of configuration
 *
 * @author thibault
 */

require_once APPPATH.'models/Webforceuser.php';

class Teachsession extends Webforceuser {
	
	const TABLE_NAME = 'teach_sessions';
	
	private $_userShares = array();
	
	public function getTableName() {
		return self::TABLE_NAME;
	}

	public function getByName($name) {
		return $this->getRow(array('name'=>$name));
	}
	
	public function checkDate($date) {
		return DateTime::createFromFormat('d/m/Y', $date) !== null;
		
	}
	
	public function prepareShares() {
		$this->load->model('linkusersteachsessions');
		$tableLink = $this->linkusersteachsessions->getTableName();
		
		$table = $this->getTableName();
		
		$this->join($tableLink, 
		$tableLink.'.user_id='.user_id().' AND '.$tableLink.'.teach_session_id='.$table.'.id', 'left');
		
		$this->select($tableLink.'.user_id as shared');
	}
	
	public function validationRulesForInsert($datas) {
		$rules = parent::validationRulesForInsert($datas);
		
		return array_merge(
			$rules,
				
			array(
				'name' => array(
					'field' => 'name',
					'label' => translate('Nom complet'),
					'rules' => 'required|trim|is_unique[teach_sessions.name]|min_length[3]|max_length[100]'
				),
				'date_start' => array(
					'field' => 'date_start',
					'label' => translate('Date de début'),
					'rules' => array(
						'required',
						array('valid_session_date', array($this, 'checkDate'))
					)
				),
				'date_end' => array(
					'field' => 'date_end',
					'label'=> translate('Date de fin'),
					'rules' => array(
						'required',
						array('valid_session_date', array($this, 'checkDate')),
						array(
							'date_end_superior_date_start',
							function($r) use ($datas) {
								$datetimeStart = 
									DateTime::createFromFormat('d/m/Y', $datas['date_start']);
								$datetimeEnd = 
									DateTime::createFromFormat('d/m/Y', $datas['date_end']);
								
								return $datetimeStart->format('U') < $datetimeEnd->format('U');
							
							}
						)
					)
				)
			)	
		);
	}
	public function validationRulesForUpdate($datas) {
		$rules = parent::validationRulesForUpdate($datas);
		$self = $this;
		return array_merge(
			$rules,
				
			array(
				array(
					'field' => 'name',
					'label' => translate('Nom complet'),
					'rules' => 'trim|min_length[3]|max_length[100]'
				),
				array(
					'field' => 'date_start',
					'label' => translate('Date de début'),
					'rules' => array(
						array('valid_session_date', array($this, 'checkDate'))
					)
				),
				array(
					'field' => 'date_end',
					'label'=> translate('Date de fin'),
					'rules' => array(
						array('valid_session_date', array($this, 'checkDate')),
						array(
							'date_end_superior_date_start',
							function($r) use ($datas, $self) {
								if(isset($datas['date_start'])) {
									$datetimeStart = 
										DateTime::createFromFormat('d/m/Y', $datas['date_start']);
								} else {
									$row = $self->getId($datas['id']);
									$datetimeStart = 
										DateTime::createFromFormat('d/m/Y', $row->date_start);
								}
								
								$datetimeEnd = 
									DateTime::createFromFormat('d/m/Y', $datas['date_end']);
								
								return $datetimeStart->format('U') < $datetimeEnd->format('U');
							
							}
						)
					)
				)
			)	
		);
	}
	
	public function beforeInsert(&$to_insert = null) {
		parent::beforeInsert($to_insert);
		$to_insert['date_start'] = DateTime::createFromFormat('d/m/Y', $to_insert['date_start'])->format('U');
		$to_insert['date_end'] = DateTime::createFromFormat('d/m/Y', $to_insert['date_end'])->format('U');
	}
	
	public function afterInsert($insert_id, &$to_insert = null) {
		$this->load->helper('memberspace/connection');
		parent::afterInsert($insert_id, $to_insert);
		$this->load->model('linkusersteachsessions');
		$this->linkusersteachsessions->link(user_id(),$insert_id);
		$this->load->model('memberspace/group');
		$users = $this->group->getByName('users');
		$this->addToGroup($users->id, $insert_id);
	}

	protected function beforeUpdate(&$datas = null, $where = null) {
		parent::beforeUpdate($datas, $where);
		$datas['date_start'] = DateTime::createFromFormat('d/m/Y', $datas['date_start'])->format('U');
		$datas['date_end'] = DateTime::createFromFormat('d/m/Y', $datas['date_end'])->format('U');
	}
	
	
	public function getUserTeachSessions($limit = null, $offset = null, $type = 'object', $columns = null) {
		$this->load->model('linkusersteachsessions');
		$this->load->model('memberspace/user');
		$this->load->helper('memberspace/connection');
		$table = $this->getTableName();
		$linkTable = $this->linkusersteachsessions->getTableName();
		$userTable = $this->user->getTableName();
		$this->join($linkTable, $linkTable.'.teach_session_id='.$table.'.id', 'inner');
		$this->join("$userTable as u", 'u.id='.$linkTable.'.user_id', 'inner');
		$this->where($linkTable.'.user_id', user('id'));
		$this->group_by($table.'.id');
		
		return $this->getList($limit, $offset, $type, $columns);
	}
	
	public function connect($id = null) {
		parent::normalConnect($id);
	}
	
	protected function getUserShares($userId = null) {
		if(! $userId) {
			$userId = $this->getData('user_id');
		} 
		
		if(!isset($this->_userShares[$userId])) {
			$this->load->model('linkusersteachsessions');
			$this->_userShares[$userId] = $this->linkusersteachsessions->get(
				array(
					'user_id' => $userId
				)
			);
		}
		return $this->_userShares[$userId];
	}
	
	public function isSharedTo($teachSessionId, $user) {
		$shares = $this->getUserShares($user->id);
		if( ! $shares) {
			return false;
		}
		foreach ($shares as $share) {
			if($share->user_id == $user->id 
				&& $share->teach_session_id == $teachSessionId) {
				return true;
			}
		}
		
		return false;
	}
	
	public function shareWith($userId, $sessionId = null) {
		if(!$sessionId) {
			$sessionId = $this->getData('id');
		}
		$this->load->model('linkusersteachsessions');
		$this->linkusersteachsessions->link($userId, $sessionId);
	}
	
	public function unshare($userId, $sessionId = null) {
		if(!$sessionId) {
			$sessionId = $this->getData('id');
		}
		$this->load->model('linkusersteachsessions');
		$this->linkusersteachsessions->delete(array(
			'user_id' => $userId,
			'teach_session_id' => $sessionId
		));
	}
}

?>
