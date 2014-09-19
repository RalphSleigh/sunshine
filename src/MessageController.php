<?php
namespace Ralphie\Sunshine;
use \stdClass;

class MessageController {

	public function __construct(
	\Monolog\Logger $logger){
		$this->log = $logger;
		$this->log->debug('MessageController Constructed');
	}

	public function displayMessage($conn, $msg) {
		
		$this->log->info('Message: '.$msg->message);
		//broadcast message and log in chat, TODO: log in chat.
		$obj = new stdClass;
		$obj->action = 'messages.displayMessage';
		$obj->contents = $msg->message;
		$obj->duration = $msg->duration;
	
		foreach($conn->ms->clients as $client)$client->send(json_encode($obj));	
	}
}