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
class Linkcoursekeyword extends DATA_Model {
	
	public static $TABLE_NAME = 'links_courses_keywords';
	
	public static $PRIMARY_COLUMNS = array('course_id','keyword_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($postId, $keywordId){
		$link = $this->getRow(array('course_id'=>$postId,'keyword_id'=>$keywordId));
		if(!$link) {
			$this->insert(array('course_id'=>$postId,'keyword_id'=>$keywordId));
		}
	}

}

