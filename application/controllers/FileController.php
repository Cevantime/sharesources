<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class FileController extends MY_Controller {
	
	public function download($fileId) {
		$this->load->model('filebrowser/file');
		
		$file = $this->file->getId($fileId);
		
		if(!$file) {
			die('Unable to find the file !');
		}
		
		$this->load->helper('download');
		
		force_download($file->file);
		
	}
}
