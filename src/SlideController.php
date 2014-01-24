<?php
namespace Ralphie\Sunshine;
use \stdClass;

class SlideController {

	public function __construct(
		\Monolog\Logger $logger
	
	) {
		$this->log = $logger;
		$this->log->debug('SlideController Constructed');
	
	
	//var_dump($this->p['ms']);
	//$this->p['ms']->registerAction('slides.getSlideTree',array($this,'getSlideTree'));	
	}
	
	public function getSlideTree($conn, $msg) {
		//this one is always gonna be a gnarly SOB
		$slides = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('exampleslides'),\RecursiveIteratorIterator::SELF_FIRST);
		
		foreach($slides as $slide) {
			echo $slide."\n";
		
		}
	}
}