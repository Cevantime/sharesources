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

class Tag extends DATA_Model {
	
	const TABLE_NAME = 'tags';
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function insert($datas = null) {
		if(!$datas){
			$datas = $this->toArray();
		}
		
		if(!isset($datas['label']) || !$datas['label']) return 0;
		
		$datas['label'] = trim($datas['label']);
		$datas['alias'] = alias($datas['label']);
		
		$existing = $this->getRow(array('alias' => $datas['alias']));
		
		if($existing) return $existing->id;
		
		return parent::insert($datas);
	}
	
	public function getSome($limit) {
		$this->order_by('RAND()');
		$this->limit($limit);
		return $this->get();
	}
}
