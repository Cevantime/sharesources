<?php

namespace JBBCode\codedefinitions;

require_once APPPATH . DIRECTORY_SEPARATOR . 'vendor/jbbcode/jbbcode/JBBCode/CodeDefinition.php';

class FileCodeDefinition extends \JBBCode\CodeDefinition {
	public function __construct() {
		$this->tagName = 'file';
		$this->useOption = true;
		parent::__construct();
	}
	
	public function asHtml(\JBBCode\ElementNode $el) {
		$content = '';
		foreach ($el->getChildren() as $child) {
			$content .= $child->getAsBBCode();
		}
		if($el->getAttribute()['file'] == 'image') {
			return '<img src="'.base_url($content).'"/>';
		}
	}
}

