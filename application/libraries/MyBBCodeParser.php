<?php

/**
 * Description of BBCodeParser
 *
 * @author thibault
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once APPPATH.'modules/wysibb/libraries/BBCodeParser.php';

class MyBBCodeParser extends BBCodeParser {

	public function __construct() {
		parent::__construct();
		
		$baseUrl = base_url();
		
		$builder = new JBBCode\CodeDefinitionBuilder('keynotion', '<div class="info info-keynotion"><i class="fa fa-key main"></i>{param}</div>');
		$this->addCodeDefinition($builder->build());
		
		$builder = new JBBCode\CodeDefinitionBuilder('warning', '<div class="info info-warning"><i class="fa fa-exclamation-triangle main"></i>{param}</div>');
		$this->addCodeDefinition($builder->build());
		
		$builder = new JBBCode\CodeDefinitionBuilder('course', '<a href="'.$baseUrl.'courses/see/{option}">{param}</a>');
		$builder->setUseOption(true);
		$this->addCodeDefinition($builder->build());
	}

	

}

?>
