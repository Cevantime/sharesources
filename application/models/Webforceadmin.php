<?php

require_once APPPATH.'models/Webforceteacher.php';

class WebforceAdmin extends Webforceteacher {
	
	const TABLE_NAME = 'webforce_admins';
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function afterInsert($insert_id, &$to_insert = null) {
		parent::afterInsert($insert_id, $to_insert);
		$this->addToGroup('administrators', $insert_id);
	}
	
}

