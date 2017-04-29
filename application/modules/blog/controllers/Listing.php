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
class Listing extends BLOG_Controller {

	public function index($model = 'blog/blogpost') {
		$this->basic($model);
	}
	
	public function basic($model = 'blog/blogpost') {
		$model = $this->filterModel($model);
		$blogposts = $this->pagination('pagination-blogposts-basic-list',$model);
		$this->load->view('blog/listing/basic',array('blogposts'=>$blogposts, 'model'=>$model));
	}
	
	public function bo($model = 'blog/blogpost') {
		$model = $this->filterModel($model);
		$blogposts = $this->pagination('pagination-blogposts-basic-list',$model);
		$this->load->view('blog/listing/bo',array('blogposts'=>$blogposts, 'model'=>$model));
	}
	
	public function front($model = 'blog/blogpost') {
		$model = $this->filterModel($model);
		$blogposts = $this->pagination('pagination-blogposts-front-list',$model);
		$this->load->view('blog/listing/front',array('blogposts'=>$blogposts, 'model'=>$model));
	}

	private function filterModel($model) {
		$explode = explode('-', $model);
		return implode('/', $explode);
	}

	public function pagination($id_pagination, $model){
		$explode = explode('/', $model) ;
		$modelName = end($explode);
		$start = $this->input->get('page_start');
		$offset = 10;
		$this->load->library('mypagination');
		$this->load->helper('pagination');
		$this->load->model($model);
		return $this->mypagination->paginate($id_pagination, $this->$modelName,$start,$offset,'getListWithAuthors');
	}

}
