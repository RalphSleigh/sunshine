<?php
namespace Ralphie\Sunshine;
use \stdClass;

class TwitterController {

	public function __construct(
	\Monolog\Logger $logger){
		$this->log = $logger;
		$this->log->debug('TwitterController Constructed');
	}

	
	
}