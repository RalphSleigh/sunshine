<?php namespace \Ralphie\Sunshine;
/*
This class contains functionality related to display clients
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections, constructor contains id of client it serves.
May at some point need some way of persisting instances across ajax requests
*/
class Display {

	public $id;
	
	function __construct($userid) {
		$id = $userid;
	}
	
	function gettype() { return 'Display';}
	
}