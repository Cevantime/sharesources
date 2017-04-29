<?php

if( ! function_exists('route_matches')) {
	function route_is($string, $start = true) {
		$CI =& get_instance();
		
		global $my_uri;
		
		if(!$my_uri) {
			$uri = $CI->uri->uri_string();
		}
		
		if($start) {
			return preg_match('#^'.preg_quote($string).'#', $uri) === 1;
		} else {
			return preg_match('#'.preg_quote($string).'$#', $uri) === 1;
		}
	}
}

