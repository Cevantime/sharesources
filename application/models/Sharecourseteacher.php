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
class Sharecourseteacher extends DATA_Model {
	
	public static $TABLE_NAME = 'shares_courses_teachers';
	
	public static $PRIMARY_COLUMNS = array('course_id','teacher_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($courseId, $teacherId){
		$link = $this->getRow(array('course_id'=>$courseId,'teacher_id'=>$teacherId));
		if(!$link) {
			$this->insert(array('course_id'=>$courseId,'teacher_id'=>$teacherId));
		}
	}
	
}

