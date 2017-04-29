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
class See extends BLOG_Controller {
	
	private function _seeAction($view, $id = null,$postModel = 'blog/blogpost') {
		if(!$id || !user_can('see', $postModel, $id)) {
			$this->addError(translate('Vous ne pouvez pas voir ce post.'));
		}
		$this->load->view('blog/see/'.$view,array('blogpost'=> $this->getId($id, $postModel)));
	}
	
	public function basic($id = null,$postModel = 'blog/blogpost') {
		$this->_seeAction('basic', $id, $postModel);
	}
	
	public function bo($id,$postModel = 'blog/blogpost') {
		$this->_seeAction('bo', $id, $postModel);
	}
	
	public function front($id = null,$postModel = 'blog/blogpost') {
		$this->_seeAction('front', $id, $postModel);
	}

	public function delete($id = null,$postModel = 'blog/blogpost'){
		if(!$id){
			show_404();
		}
		$this->load->model($postModel);
		$modelName = pathinfo($postModel)['filename'];
		$this->$modelName->deleteId($id);
		$this->load->view('blog/see/delete');
	}
	
	public function getId($id = null,$postModel = 'blog/blogpost'){
		$this->load->model($postModel);
		$modelName = pathinfo($postModel)['filename'];
		$blog_post =  $this->$modelName->getByIdWithAuthor($id);
		if(!$blog_post){
			show_404();
		}
		return $blog_post;
	}

}
