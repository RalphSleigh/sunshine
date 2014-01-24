<?php
namespace Ralphie\Sunshine;

class ActionRouter {

	protected $log;
	protected $ms;
	protected $modules = array();
	
	public function __construct(
	\Monolog\Logger $logger,
	\Ralphie\Sunshine\SlideController $s){
	
		$this->log = $logger;
		$this->modules['slides'] = $s;
	
		$this->log->debug('ActionRouter Constructed');	
	}
	
	public function setMS($ms) {
		$this->modules['system'] = $ms; //special case
	}
	
	public function route($conn, $msg) {
		//do something awesome with the incoming message.
		$route = explode('.',$msg->action);
		$function = array($this->modules[$route[0]],$route[1]);
		if(is_callable($function, false, $cn)) {
			$this->log->info('Calling '.$cn);
			$function($conn, $msg);
		} else {
			$this->log->error('Invalid route: '.$msg->action);
		}
	}
	
}