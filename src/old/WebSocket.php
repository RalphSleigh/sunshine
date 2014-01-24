<?php
namespace Ralphie\Sunshine;

//this class glues messageserver to PHPWebsocket, needs fixing.
class WebSocket extends MessageServer{

public $server;
public $users;

//abstract function process($user,$msg);


function __construct($p, $address, $port) {

$this->p = $p;
$this->server = new PHPWebSocket();
$this->server->p = $this->p;
$this->server->parobj = $this;
$this->server->bind('message', 'convertmessage');
$this->server->bind('open', 'convertopen');
$this->server->bind('close', 'convertclose');

$users = Array();
parent::__construct($this->p,$address,$port);
//print_r($this);
//exit;
$this->p['log']->info('Starting websocket server');
$this->server->wsStartServer($address, $port);
}

function convertopen($clientID) {
//$this->server->wsClients[$clientID][2]
$this->users[$clientID] = new User();
$this->users[$clientID]->id = uniqid();
$this->users[$clientID]->clientid = $clientID;
$this->users[$clientID]->socket = $this->server->wsClients[$clientID][0];
	}

function convertclose($clientID, $status) {

$this->p['log']->notice('Disconnected: '.$clientID);
unset($this->users[$clientID]);
/*
$found=null;
    $n=count($this->users);
    for($i=0;$i<$n;$i++){
      if($this->users[$i]->id==$clientID->id){ $found=$i; break; }
    }
    if(!is_null($found)){ array_splice($this->users,$found,1); }
    $index=array_search($clientID->id,$this->id);
    echo $clientID->id." DISCONNECTED!";
    if($index>=0){ array_splice($this->id,$index,1);
	}
	*/
}

function convertmessage($clientID, $message, $messageLength, $binary) {
	//print_r($this);
	$this->process($this->users[$clientID],$message);
}

function send($socket,$msg) {
	//echo $this->users[$socket]->socket;
	$this->server->wsSend(parent::getuserbysocket($socket)->clientid, $msg);
	}
}