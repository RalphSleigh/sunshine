<?php
namespace Ralphie\Sunshine;
/*
This class contains functionality related to displaying video.
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections.
*/
class VideoHandler{
	 
	function processmessage($msg,$fromuser) { //return an array of messages to send id => message
		
		
		$obj = new stdClass();
		$obj->clienthandler = 'videoclient';
		

		$obj->html  = '<video src="video/The_end_is_nigh-2.mp4">Ohdear</video>';

		$returnmsg['main'] = $obj;
		return $returnmsg;
		}

}

?>