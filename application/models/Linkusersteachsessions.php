<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author thibault
 */
class Linkusersteachsessions extends DATA_Model {
	
	public static $TABLE_NAME = 'link_users_teach_sessions';
	
	public static $PRIMARY_COLUMNS = array('user_id','teach_session_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($userId, $fileId){
		$link = $this->getRow(array('user_id'=>$userId,'teach_session_id'=>$fileId));
		if(!$link) {
			$this->insert(array('user_id'=>$userId,'teach_session_id'=>$fileId));
		}
	}

}

