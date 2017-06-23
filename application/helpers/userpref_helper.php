<?php

if( ! function_exists('pref_is')) {
	function pref_is($key, $value) {
		if(user_is('teacher')){
			$prefs = user('preferences');
			if(isset($prefs[$key]) && $prefs[$key] == $value){
				return true;
			}
		} 
		return false;
	}
}
