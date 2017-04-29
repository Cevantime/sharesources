<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

$CI =& get_instance();
$post = $CI->input->post();

$is_update = isset($post['id'], $post['save-blogpost']);

$config = array(
	'blog/blogpost' => array(
		array(
			'field' => 'title',
			'label' => translate('Titre'),
			'rules' => $is_update ? 'required' : ''
		),
		array(
			'field' => 'description',
			'label' => translate('Description'),
			'rules' => (!$is_update ? 'required|' : '').'min_length[20]',
		),
		array(
			'field' => 'content',
			'label' => translate('Contenu'),
			'rules' => (!$is_update ? 'required|' : '').'min_length[20]',
		),
		array(
			'field' => 'image',
			'label' => translate('Image'),
			'rules' => (!$is_update ? 'file_required|' : '').'file_image_maxdim[2000,1500]|file_allowed_type[image]'
		)
		
	)
);

if($is_update){
	$CI->load->model('blog/blogpost');
	$config['blog/blogpost'][] = array(
			'field' => 'id',
			'label' => 'Id',
			'rules' => array(
				'required',
				'numeric',
				array($CI->blogpost, 'exists')
			)
	);
}

