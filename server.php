#!/php -q
<?php 
namespace Ralphie\Sunshine;
use \stdClass;
require 'vendor/autoload.php';
require "config.php";

//config

//core websocket stuff
//include "user.class.php";
//include "websocket.class.php";
//include "class.PHPWebSocket.php";
//client handlers
//include "display.class.php";
//include "control.class.php";
//content handlers
//include "slidehandler.class.php";
//include "messagehandler.class.php";
//include "twitterhandler.class.php";
//include "videohandler.class.php";
//include "chathandler.class.php";

Abstract class Messageserver{

	

	public $slidehandler;
	public $videohandler;
	//client handlers stored seperatly so functionality can be reused by ajax backend
	public $clienthandlers;
	
	function __construct($address,$port) {
	
		$this->debug = true;
		$this->slidehandler = new SlideHandler();
		$this->videohandler = new VideoHandler();
		$this->messagehandler = new MessageHandler();
		$this->chathandler = new ChatHandler();
		$this->twitterhandler = new TwitterHandler();
		
	}
	
	function     say($msg=""){ echo $msg."\n"; }
	
	function process($user,$msg){ //called on every message
  
		$this->say("< ".$msg);
	
		if(empty($user->roles)){ //first read from a new connection, establish what it is.
			$this->upgradeuser($user,$msg);//read type from message, create the right client handlers.
			return;
		}
		
		//message should be json object, 2 properties. msgfor->handler and data is passed through.
		
		$unencode = json_decode($msg);		
		$returnmsgs = null;
		
		echo $msg;
		
		if($unencode && isset($unencode->msgfor)) {
			switch($unencode->msgfor) {
				case 'server' : $returnmsgs = $this->servermessage($unencode->data,$user);  break; //handle messages for the server.
				case 'slidehandler' : $returnmsgs = $this->slidehandler->processmessage($unencode->data,$user);  break;
				case 'videohandler' : $returnmsgs = $this->videohandler->processmessage($unencode->data,$user);  break;
				case 'messagehandler' : $returnmsgs = $this->messagehandler->processmessage($unencode->data,$user);  break;
				case 'chathandler' : $returnmsgs = $this->chathandler->processmessage($unencode->data,$user);  break;
				case 'twitterhandler' : $returnmsgs = $this->twitterhandler->processmessage($unencode->data,$user);  break;
			}
		}
		
		//ALMIGHTY HACK FOR TRANSITION TO NEW CLIENT CODE:
		
		else if($unencode && isset($unencode->bounce)) {
			foreach($this->users as $user) {
			$this->send($user->socket,json_encode($unencode));
			}
		}
		
		else echo "bad JSON or no msgfor \n";
		
		if($returnmsgs) { //Loop over list of return messages and send them to any client with matching id/role.
			//print_r($returnmsgs);
			foreach($returnmsgs as $for => $returnmsg) { 			
				 /*
				$returnuser = $this->getuserbyid($for);
				if($returnuser)$this->send($returnuser->socket,json_encode($returnmsg));
				foreach($this->getusershaverole($for) as $returnuser)$this->send($returnuser->socket,json_encode($returnmsg));
				*/
				//lets make this only send each message to each user once
				
				
				
				foreach($this->users as $user) {
					if($user->id == $for){
						$this->send($user->socket,json_encode($returnmsg));
						continue;
						}
					if(in_array($for,$user->roles))$this->send($user->socket,json_encode($returnmsg));
				}
			}
		}
	}
   
   function upgradeuser($user, $msg) {
		$info = json_decode($msg);
		
		if(isset($info->id) && is_array($info->roles)) {
		
			foreach($info->roles as $role) {
			
				switch($role) {
					case 'display' : $this->clienthandlers[$info->id][$role] = new Control($info->id);  break;
					case 'control' : $this->clienthandlers[$info->id][$role] = new Display($info->id);  break;
					}
				}
				
			$user->roles = $info->roles;
			$user->id = $info->id;
		}
		else {
				$this->send($user->socket,"bad registration, disconnecting \n");
				$this->disconnect($user->socket);
		}
	}
	
	function getuserbyid($id){ //get user who has an id.
		$found=null;
		foreach($this->users as $user){
			if($user->id==$id){ $found=$user; break; }
			}
		return $found;
		}
		
	function getuserbysocket($socket){ //get user who has an id.
		$found=null;
		foreach($this->users as $user){
			if($user->socket==$socket){ $found=$user; break; }
			}
		return $found;
		}
	
	function getusershaverole($role){ //get an array of all users who have a role 
		$found=array();
		foreach($this->users as $user){
			if(in_array($role,$user->roles))$found[] = $user;
			}
		return $found;
		}
		
	
	function servermessage($data,$user) {
	
		if($data == "restart") {
			echo "\nServer Restarting\n\n";
		 /*
		$WshShell = new COM("WScript.Shell");
		$oExec = $WshShell->Exec("php server.php");		
		while($input = $oExec->StdOut->ReadLine())echo $input;
		//$oExec = $WshShell->Run("php server.php", 1, false);
		*/
		
			exit(2);
		}
		else if($data == "exit") {
			echo "\nGoodbye cruel world!!\n\n";
			exit(0);
		}
	}
}

class WebSocket extends Messageserver{

public $server;
public $users;

//abstract function process($user,$msg);


function __construct($address,$port) {

$this->server = new PHPWebSocket();
$this->server->parobj = $this;
$this->server->bind('message', 'convertmessage');
$this->server->bind('open', 'convertopen');
$this->server->bind('close', 'convertclose');

$users = Array();
parent::__construct($address,$port);
//print_r($this);
//exit;
$this->server->wsStartServer($address,$port);
}

function convertopen($clientID) {
//$this->server->wsClients[$clientID][2]
$this->users[$clientID] = new User();
$this->users[$clientID]->id = uniqid();
$this->users[$clientID]->clientid = $clientID;
$this->users[$clientID]->socket = $this->server->wsClients[$clientID][0];
	}

function convertclose($clientID, $status) {

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
$master = new WebSocket(WEBSOCKET_HOST,WEBSOCKET_PORT);
