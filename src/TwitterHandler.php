<?php
namespace Ralphie\Sunshine;
/*
This class contains functionality related to displaying twitter messages.
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections.
*/
class TwitterHandler{

	function processmessage($msg,$fromuser) { //return an array of messages to send id => message
		
		$tocall = $msg->call;
		if(method_exists($this,$tocall))return $this->$tocall($msg->data,$fromuser);
		else echo "method not found";//this makes perfect sense, extract the call value and call it with the next layer of data
		
		}
	
	function displaymessage($msg,$fromuser) {
		
		echo "displaymessage called";
		$obj = new stdClass();
		$obj->clienthandler = "twitterClient";

		$obj->message = $msg->message;

		$returnmsg['display'] = $obj;
		return $returnmsg;
	}


}

?>
