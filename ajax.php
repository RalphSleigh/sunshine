<?php
include('config.php');

//When requested, this opens socket to server, gets the slide and shoves down the pipe, very simples..


/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    //echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    //echo "socket successfully created.\n";
}

$result = socket_connect($socket, SERVER_ADDRESS, 12346);
if ($result === false) {
    //echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    //echo "successfully connected to $address.\n";
}

$msg = chr(0x00).'{"id": "AJAX", "roles":["display"]}'.chr(0xff);
socket_write($socket, $msg, strlen($msg));
$msg1 = chr(0x00).'{"msgfor": "slidehandler", "data":
   {"call":"getcurrentslide","data":
      {"lol":"lol"}}}'.chr(0xff);
 
 

sleep(1);
socket_write($socket, $msg1, strlen($msg1));
   
    $input = socket_read($socket, 4096);
	
	$input1 = str_replace(array(chr(0x00),chr(0xff)),"",$input);
    echo $input1;





?> 
