<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author thibault
 */
require_once APPPATH . '/modules/memberspace/models/Post.php';

class Blogpost extends Post {

	public static $TABLE_NAME = 'posts_blog';

	public function getTableName() {
		return self::$TABLE_NAME;
	}

	public function update($datas = null, $where = null) {
		$this->unlink('image', $datas);
		parent::update($datas, $where);
	}

	public function delete($where = null) {
		if ($where === null) {
			$where = array();
			foreach ($this->getPrimaryColumns() as $col) {
				$where[$col] = $this->{$col};
			}
			$this->clear();
		}
		$rows = $this->get($where);
		if ($rows) {
			foreach ($rows as $row) {
				$this->unlink('image', (array) $row);
			}
		}
		return parent::delete($where);
	}

	public function validationRulesForInsert($datas) {
		$rules = parent::validationRulesForInsert($datas);
		$newRules = array(
			'title' => array(
				'field' => 'title',
				'label' => translate('Titre'),
				'rules' => 'required|trim|min_length[2]|max_length[255]'
			),
			'description' => array(
				'field' => 'description',
				'label' => translate('Description'),
				'rules' => 'required|trim|min_length[20]|max_length[10000]',
			),
			'image' => array(
				'field' => 'image',
				'label' => translate('Image'),
				'rules' => 'file_required|file_image_maxdim[2000,1500]|file_allowed_type[image]'
			)
		);
		return array_merge($rules,$newRules);
	}
	
	public function validationRulesForUpdate($datas) {
		$rules = parent::validationRulesForUpdate($datas);
		$newRules = array(
			'title' => array(
				'field' => 'title',
				'label' => translate('Titre'),
				'rules' => 'trim|max_length[255]'
			),
			'description' => array(
				'field' => 'description',
				'label' => translate('Description'),
				'rules' => 'trim|min_length[20]|max_length[10000]',
			),
			'image' => array(
				'field' => 'image',
				'label' => translate('Image'),
				'rules' => 'file_image_maxdim[2000,1500]|file_allowed_type[image]'
			)
		);
		return array_merge($rules,$newRules);
	}

	public function uploadPaths() {
		return array('image' => 'uploads/posts');
	}

	public function getListWithAuthors($limit = 0, $offset = 10) {
		$this->load->model('memberspace/user');
		$usersTableName = $this->user->getTableName();
		$this->join($usersTableName, $usersTableName . '.id=user_id');
		$this->db->select($this->db->dbprefix('users') . '.login as author');
		return $this->getList($limit, $offset);
	}

	public function getByIdWithAuthor($id) {
		$this->load->model('memberspace/user');
		$usersTableName = $this->user->getTableName();
		$this->join($usersTableName, $usersTableName . '.id=user_id');
		$this->db->select($this->db->dbprefix('users').'.login as author');
		return $this->getId($id);
	}

}
