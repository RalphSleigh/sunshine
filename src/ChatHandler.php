<?php
namespace Ralphie\Sunshine;
/*
This class contains functionality related to displaying marquee messages.
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections.
*/
class ChatHandler{

	function processmessage($msg,$fromuser) { //return an array of messages to send id => message
		
		$tocall = $msg->call;
		if(method_exists($this,$tocall))return $this->$tocall($msg->data,$fromuser);
		else echo "method not found";//this makes perfect sense, extract the call value and call it with the next layer of data
		
		}
	
	function getexistingchat($msg,$fromuser) {
		
		echo "get existing chat called \n";
		$obj = new stdClass();
		$obj->clienthandler = "chatClient";
		$obj->data->call = "replaceChatContent";		
		
		$chat = file_get_contents('chat.txt');
		
		$obj->data->data = $chat;

		$returnmsg[$fromuser->id] = $obj;
		return $returnmsg;
	}
	
	function newchatmessage($msg,$fromuser) {
	
		$chatmesssage = '<p><b>'.date('G:i').' '.$msg->user.':</b> '.$msg->chat.'</p>';
		file_put_contents('chat.txt',$chatmesssage,FILE_APPEND);
	
		$obj = new stdClass();
		$obj->clienthandler = "chatClient";
		$obj->data->call = "updateChatContent";		
		
		
		$obj->data->data = $chatmesssage;

		$returnmsg['chat'] = $obj;
		return $returnmsg;
	
	}
}

?>