<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('filefaclass')) {

	function filefaclass($file) {
		if(strpos($file->file_type, 'image/') !== FALSE) {
			return 'fa-picture-o';
		} 
		if($file->file_type == 'application/zip') {
			return 'fa-file-archive-o';
		}
		if($file->file_type == 'application/pdf') {
			return 'fa-file-pdf-o';
		}
		
		return 'fa-file';
	}

}