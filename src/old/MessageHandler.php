<?php
namespace Ralphie\Sunshine;
/*
This class contains functionality related to displaying marquee messages.
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections.
*/
class MessageHandler{

	function processmessage($msg,$fromuser) { //return an array of messages to send id => message
		
		$tocall = $msg->call;
		if(method_exists($this,$tocall))return $this->$tocall($msg->data,$fromuser);
		else echo "method not found";//this makes perfect sense, extract the call value and call it with the next layer of data
		
		}
	
	function displaymessage($msg,$fromuser) {
		
		echo "displaymessage called";
		$obj = new stdClass();
		$obj->clienthandler = "messageClient";

		$obj->message = $msg->message;

		$returnmsg['display'] = $obj;
		
		$obj1 = new stdClass();
		$obj1->clienthandler = "chatClient";
		$obj1->data->call = "updateChatContent";
		
		
		
		$obj1->data->data = '<p><b>Infobox:</b> '.$msg->message.'</p>';
		file_put_contents('chat.txt','<p><b>Infobox:</b> '.$msg->message.'</p>',FILE_APPEND);

		
		$returnmsg['chat'] = $obj1;
		
		return $returnmsg;
	}


}

?>