<?php
namespace Ralphie\Sunshine;
use \stdClass;

class ChatController {

	public function __construct(
	\Monolog\Logger $logger){
	
		$this->log = $logger;
		$this->chat  = file_get_contents(SUNSHINE_CHAT_FILE);
		
		$this->log->debug('ChatController Constructed');
	}
	
	public function getExistingChat($conn, $msg) {
	//return the chat so far

		$obj = new \StdClass;
		$obj->action = "chat.insertChatText";
		$obj->data = $this->chat;

		$conn->send(json_encode($obj));
	}

	public function newChatMessage($conn, $msg) {
		
		//format the message and store it.
		if(trim($msg->message) == '')return;
		$chatmesssage = '<p><b>'.date('G:i').' '.$msg->user.':</b> '.$msg->message.'</p>';
		file_put_contents(SUNSHINE_CHAT_FILE,$chatmesssage,FILE_APPEND); //should probably use some fancy file stream here but #yolo
		$this->chat .= $chatmesssage;
		
		//send the message to everyone TODO: only clients who want chat.
		$obj = new \StdClass;
		$obj->action = "chat.newChatMessage";
		$obj->data = $chatmesssage;
		foreach($conn->ms->clients as $client)$client->send(json_encode($obj));
	}
	
}
