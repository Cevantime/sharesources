<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class CategoriesController extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('category');
	}

	public function all() {
		if (!user_can('see', 'category', '*')) {
			redirect('home');
		}
		
//		$this->addDataTableScripts();

		$categories = $this->category->get();
		
		$this->layout->title('Toutes les categories');

		$this->layout->view('categories/all', array('categories' => $categories));
	}

	public function add() {
		$this->layout->title('Ajouter une categorie');
		$datas = $this->save();
		$this->layout->view('categories/add', array('datas' => $datas));
	}

	public function edit($id) {
		$this->layout->title('Modifier une categorie');
		$datas = $this->save($id);
		$this->layout->view('categories/edit', array('datas' => $datas));
	}

	public function delete($id) {
		if (user_can('delete', 'category', $id)) {
			$this->category->deleteId($id);
			add_success('Cette catégorie a bien été supprimée');
		} else {
			add_error('Vous n\'avez pas les droits nécessaires à la suppression de cette catégorie');
		}
		redirect('categories/all');
	}

	public function save($id = null) {
		$this->load->model('category');
		$post = $this->input->post();
		$pop = array();
		$this->load->helper('locale');
		$oldLang = locale();
		$lang = $this->input->post_get('lang');
		if ($lang) {
			locale($lang);
		}
		if (!$post || !isset($post['save-category']) || !$post['save-category']) {
			if ($id) {
				$pop = $this->category->getId($id, 'array');
			}
			locale($oldLang);
			return $pop;
		}
		$pop = $post;
		$isUpdate = isset($post['id']) && $post['id'];
		if (!user_can($isUpdate ? 'update' : 'add', 'category', $isUpdate ? $post['id'] : '*')) {
			add_error(
				translate('Vous n\'avez pas le droit requis pour ') 
				. ($isUpdate 
					? translate('mettre à jour') 
					: translate('ajouter')) 
				. translate(' cette catégorie.')
			);
			redirect('categories/all');
		}
		$success = $this->category->fromPost();
		locale($oldLang);
		if ($success === false) {
			add_error($this->category->getLastErrorsString());
			return $pop;
		}
		if($isUpdate) {
			add_success(translate('La catégorie a bien été mise à jour.'));
		} else {
			add_success(translate('La catégorie a bien été ajoutée'));
		}

		redirect('categories/all');
	}

}
