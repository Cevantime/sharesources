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
class Keyword extends DATA_Model {
	
	const TABLE_NAME = 'keywords';
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function insert($datas = null) {
		if(!$datas){
			$datas = $this->toArray();
		}
		if(!isset($datas['content']) || !$datas['content']) return 0;
		$existing = $this->getRow(array('content' => $datas['content']));
		if($existing) return $existing->id;
		return parent::insert($datas);
	}

}
