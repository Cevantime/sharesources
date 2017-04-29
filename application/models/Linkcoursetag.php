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
class Linkcoursetag extends DATA_Model {
	
	public static $TABLE_NAME = 'links_courses_tags';
	
	public static $PRIMARY_COLUMNS = array('course_id','tag_id');

	public function getTableName() {
		return self::$TABLE_NAME;
	}
	
	public function getPrimaryColumns() {
		return self::$PRIMARY_COLUMNS;
	}
	
	public function link($postId, $tagId){
		$link = $this->getRow(array('course_id'=>$postId,'tag_id'=>$tagId));
		if(!$link) {
			$this->insert(array('course_id'=>$postId,'tag_id'=>$tagId));
		}
	}
	

}

