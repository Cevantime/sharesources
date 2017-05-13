<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('colortext')) {

	function colortext($r, $g = null, $b = null) {
		if( is_null($g) || is_null($b)){
			list($r,$g,$b) = hex2rgb($r);
		}
		$d = 0;
		$a = 1 -((0.299 * $r + 0.587 * $g + 0.114 * $b)/255);
		
		if($a < 0.5) {
			$d = 0;
		} else {
			$d = 255;
		}
		
		return "rgb($d,$d,$d)";
	}

}
if (!function_exists('hex2rgb')) {

	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		
		$rgb = array($r, $g, $b);
		
		return $rgb; // returns an array with the rgb values
	}
	

}