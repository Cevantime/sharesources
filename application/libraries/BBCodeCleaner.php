<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BBCodeCleaner
 *
 * @author cevantime
 */
class BBCodeCleaner {

	/**
	 * 
	 * @param type $str
	 */
	public function clean($str) {
		$str = str_replace("\t", "    ", $str);
		$regex = "#(.*?)(\r)?\n#";
		$regex2 = "#\[p\](.*?)(\[/?(h1|h2|h3|h4|h5|h6|li|ul|div|pre|code|sectioncode|legend|quote|becareful|info|left|leftedcode|center|justify|section2|section3|p|ol|list|\*|youtube|video|table|td|tr|th)(=.*?)?\])(.*?)\[/p\](\r)?\n#";
		$str = preg_replace($regex, "[p]$1[/p]\r\n", $str . "\r\n");
		$str = preg_replace($regex2, "$1$2$5\r\n", $str);
		
		$newstr = '';
		$min = 0;
		$max = 0;
		while (($min = strpos($str, '[code', $min)) !== FALSE) {
			$newstr .= substr($str, $max, $min - $max);
			$max = strpos($str, '[/code]', $min);
			if ($max === FALSE) {
				break;
			}
			$newstr .= str_replace('[p]', '', str_replace('[/p]', '', substr($str, $min, $max - $min)));
			$min = $max;
		}
		$newstr .= substr($str, $max, strlen($str) - $max - 1);
		$newstr = str_replace('[p][/p]', '', $newstr);
	}

}
