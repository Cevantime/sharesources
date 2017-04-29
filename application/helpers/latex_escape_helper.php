<?php

if(!function_exists('latex_special_chars')) {
	
	function latex_special_chars( $string )
	{
		$map = array( 
				"#"=>"\\#",
				"$"=>"\\$",
				"%"=>"\\%",
				"&"=>"\\&",
				"~"=>"\\~{}",
				"_"=>"\\_",
				"^"=>"\\^{}",
				"\\"=>"\\textbackslash",
				"{"=>"\\{",
				"}"=>"\\}",
		);
		
		return preg_replace_callback( "/([\^\%~\\\\#\$%&_\{\}])/", function($matches) use($map) {
			return $map[$matches[1]];
		}, $string );
	}
	

}
if(!function_exists('latex_decode')) {
	
	function latex_decode( $string )
	{
		$map = array( 
				"\\#"=>"#",
				"\\$"=>"$",
				"\\%"=>"%",
				"\\&"=>"&",
				"\\~{}"=>"~",
				"\\_"=>"_",
				"\\^{}"=>"^",
				"\\textbackslash"=>"\\",
				"\\{"=>"{",
				"\\}"=>"}",
		);
		
		return preg_replace_callback( "/((\\\\\\^\{\})|(\\\\\\%)|(\\\\~\{\})|(\\\\textbackslash)|(\\\\#)|(\\\\\\$)|(\\\\%)|(\\\\&)|(\\\\_)|(\\\\\\{)|(\\\\\\}))/", function($matches) use($map) {
			return $map[$matches[1]];
		}, $string );
	}
	

}
