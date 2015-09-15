<?php
namespace Ralphie\Sunshine;

class ActionRouter {

	protected $log;
	protected $ms;
	protected $modules = array();
	
	public function __construct(
	\Monolog\Logger $logger,
	\Ralphie\Sunshine\SlideController $s,
	\Ralphie\Sunshine\MessageController $m,
	\Ralphie\Sunshine\TwitterController $t,
	\Ralphie\Sunshine\ChatController $c){
	
	
		$this->log = $logger;
		$this->modules['slides'] = $s;
		$this->modules['messages'] = $m;
		$this->modules['twitter'] = $t;
		$this->modules['chat'] = $c;
	
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
			$this->log->info($conn->resourceId.' Called '.$msg->action);

			try {
				$function($conn, $msg);
			} catch (\Exception $e) {
				$this->log->error('Exception: '.$e->getMessage());
			}

		} else {
			$this->log->error('Invalid route: '.$msg->action);
		}
	}
	
}