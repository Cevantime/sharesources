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
class Linkcoursefile extends DATA_Model {
	
	public static $TABLE_NAME = 'link_courses_files';
	
	public static $PRIMARY_COLUMNS = array('course_id','file_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($postId, $fileId){
		$link = $this->getRow(array('course_id'=>$postId,'file_id'=>$fileId));
		if(!$link) {
			$this->insert(array('course_id'=>$postId,'file_id'=>$fileId));
		}
	}

}

