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
class Sharecourseteachsession extends DATA_Model {
	
	public static $TABLE_NAME = 'shares_courses_teachsessions';
	
	public static $PRIMARY_COLUMNS = array('course_id','teach_session_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($courseId, $teachSessionId, $date = null){
		if($date === null) {
			$date = time();
		}
		$link = $this->getRow(array('course_id'=>$courseId,'teach_session_id'=>$teachSessionId));
		if(!$link) {
			$this->load->model('notification');
			$this->notification->create(Notification::TYPE_NEW_COURSE_SHARE_SESSION, 
					Notification::VISIBILITY_PRIVATE,
					array('courseId' => $courseId),
					array($teachSessionId));
			$this->insert(array('course_id'=>$courseId,'teach_session_id'=>$teachSessionId, 'date'=>$date));
		}
	}
	
	
}

