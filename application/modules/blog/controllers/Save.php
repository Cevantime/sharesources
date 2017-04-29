<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author thibault
 */
class Save extends BLOG_Controller {

	public function index($id = null, $model = 'blog/blogpost', $redirect = false) {
		$this->basic($id, $model, $redirect);
	}

	public function basic($id = null, $model = 'blog/blogpost', $redirect = false) {
		$datas = $this->doSave($id, $model, $redirect);
		$this->load->view('blog/forms/basic-style', array('blogpost_add_pop' => $datas, 'model_name' => pathinfo($model)['filename'], 'lang' => $this->getLang()));
	}

	public function bootstrap($id = null, $model = 'blog/blogpost', $redirect = false) {
		$datas = $this->doSave($id, $model, $redirect);
		$this->load->view('blog/forms/bo-style', array('blogpost_add_pop' => $datas, 'model_name' => pathinfo($model)['filename'], 'lang' => $this->getLang()));
	}

	private function getLang() {
		$lang = $this->input->post_get('lang');
		if ($lang)
			return $lang;
		$this->load->helper('locale');
		return locale();
	}

	public function doSave($id = null, $model = 'blog/blogpost', $redirect = false) {
		$this->load->model($model);

		$modelName = pathinfo($model)['filename'];

		$modelInst = $this->$modelName;
		$ret = $this->processSave($modelInst, $id, $model, $redirect);
		return $ret;
	}

	private function processSave($modelInst, $id = null, $model = 'blog/blogpost', $redirect = false) {

		$this->load->helper('memberspace/authorization');
		$this->load->helper('memberspace/connection');

		$post = $this->input->post();

		if ($id) {
			$pop = $modelInst->getId($id, 'array');
		} else {
			$pop = array();
		}

		if (!isset($post['save-' . strtolower(get_class($modelInst))])) {
			return $pop;
		}

		$pop = $post;

		if (!$post) {
			return $pop;
		}

		$is_update = isset($post['id']);

		if ($is_update && !user_can('update', $model, $post['id'])) {
			$this->addError(translate('Vous ne pouvez pas modifier ce post.'));
			return $pop;
		} else if (!user_can('add', $model)) {
			$this->addError(translate('Vous ne pouvez pas ajouter de post.'));
			return $pop;
		}

		$datas = $this->input->post();

		$datas['user_id'] = user_id();

		$new_id = $modelInst->fromDatas($datas);

		if ($new_id === false) {
			$this->addError($modelInst->getLastErrorsString());
			return $pop;
		}
		if ($is_update) {
			$new_id = $datas['id'];
		}

		$this->addSuccess(translate('Le post a bien été ') . ($is_update ? translate('mis à jour') : translate('ajouté')));

		if ($redirect) {
			$lastRow = $modelInst->getLastSavedDatas();
			$regex = '/\{row:(.+?)\}/';
			if (preg_match_all($regex, $redirect, $matches)) {
				for ($j = 0; $j < count($matches[0]); $j++) {
					$redirect = str_replace($matches[0][$j], $lastRow[$matches[1][$j]], $redirect);
				}
			}
			redirect($redirect);
		}

		return $datas;
	}

}
