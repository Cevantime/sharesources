<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Description of tags
 *
 * @author cevantime
 */
class TagsController extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('tag');
	}
	
	public function all() {
		if(!$this->input->is_ajax_request()){
			redirect('home');
		}
		$search = $this->input->get('search');
		if($search){
			$this->tag->like('label',$search);
		}
		die( json_encode(array('tags'=>  $this->tag->get())) );
	}
	
	public function courses($tagAlias) {
		$this->load->model('mypost');
		$tag = $this->tag->getRow(array('alias'=>$tagAlias));
		if( ! $tag) {
			show_404();
		}
		$this->mypost->id_tag = $tag->id;
		
		$this->load->library('mypagination');
		
		$page_start = $this->input->get('page_start');
		$page_offset = $this->input->get('page_offset');
		
		if ($page_start) {
			$page_start = intval($page_start);
		} else {
			$page_start = 0;
		}
		if ($page_offset) {
			$page_offset = intval($page_offset);
		} else {
			$page_offset = 10;
		}
		
		$articles = $this->mypagination->paginate('tag_articles',  $this->mypost, $page_start, $page_offset, 'getListByTagWithAuthorAndCategory');
		
		$this->layout->view('blog/tagPosts', array('tag'=>$tag, 'mygPosts'=>$articles));
	}
	
//	public function labelCloud($limit = 6) {
//		$tags = $this->tag->getSome(intval($limit));
//		
//		$this->load->view('tags/includes/label-cloud', array('tags'=>$tags));
//	}
}
