<?php

class Category extends DATA_Model {
	
	const TABLE_NAME = 'categories';
	
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	public function validationRulesForInsert($datas) {
		return array(
			array(
				'rules' => 'required|min_length[2]|max_length[250]',
				'label' => 'Nom',
				'field' => 'name'
			),
			array(
				'rules' => 'required',
				'label' => 'Description',
				'field' => 'description'
			),
			'image' => array(
				'field' => 'image',
				'label' => translate('Image'),
				'rules' => 'file_required|file_image_mindim[100,100]|file_size_max[500KB]|file_allowed_type[image]'
			),
			'color' => array(
				'field' => 'color',
				'label' => translate('Couleur'),
				'rules' => 'required|regex_match[/^([A-Fa-f0-9]{6})$/i]'
			)
		);
	}
	
	public function validationRulesForUpdate($datas) {
		return array(
			array(
				'rules' => 'min_length[2]|max_length[250]',
				'label' => 'Nom',
				'field' => 'name'
			),
			array(
				'rules' => '',
				'label' => 'Description',
				'field' => 'description'
			),
			'image' => array(
				'field' => 'image',
				'label' => translate('Image'),
				'rules' => 'file_image_mindim[300,300]|file_size_max[500KB]|file_allowed_type[image]'
			),
			'color' => array(
				'field' => 'color',
				'label' => translate('Couleur'),
				'rules' => 'required|regex_match[/^([A-Fa-f0-9]{6})$/i]'
			)
		);
	}
	
	protected function beforeInsert(&$to_insert = null) {
		$to_insert['alias'] = $this->createAliasFrom($to_insert['name']);
	}
	
	protected function beforeUpdate(&$datas = null, $where = null) {
		if(isset($datas['name'])){
			$datas['alias'] = $this->createAliasFrom($datas['name'], true);
		}
	}
	
	public function uploadPaths() {
		return array('image' => 'uploads/categories');
	}
}

