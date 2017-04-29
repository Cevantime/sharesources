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
class Blogadmin extends BO_Controller {

	public function index($modelName = 'blog/blogpost') {
		$modelName = $this->filterModel($modelName);
		$start = $this->input->get('page_start');
		$this->checkIfUserCan('see', $modelName);
		$this->load->model($modelName);
		$this->load->library('mypagination');
		$id_pagination = 'blogadmin_pagination';
		$model = pathinfo($modelName)['filename'];
		$blogposts = $this->mypagination->paginate($id_pagination, $this->$model,$start,5,'getListWithAuthors');
		$this->layout->view('blog/admin/index', array('model'=>'blog/blogpost','blogposts'=>$blogposts));
	}
	
	public function edit($id,$modelName = 'blog/blogpost') {
		$this->checkIfUserCan('update', 'admin', $id);
		$modelName = $this->filterModel($modelName);
		$this->layout->view('blog/admin/edit', array('id'=>$id, 'modelName'=>$modelName));
	}
	
	public function delete($id,$modelName = 'blog/blogpost'){
		$this->checkIfUserCan('delete', 'admin', $id);
		$modelName = $this->filterModel($modelName);
		Modules::run('blog/see/delete',$id,$modelName);
	}
	
	private function filterModel($model) {
		$explode = explode('-', $model);
		return implode('/', $explode);
	}

}
