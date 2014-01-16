<?php
namespace Ralphie\Sunshine;
use \stdClass;
//This is abstract because needed to change backend, should tidy this up and refactor at some point.

Abstract class MessageServer{

	

	public $slidehandler;
	public $videohandler;
	//client handlers stored seperatly so functionality can be reused by ajax backend
	public $clienthandlers;
	
	function __construct($p, $address,$port) {
		
		$p['log']->debug('Creating Handlers');
		$this->p = $p;
		$this->debug = true;
		$this->slidehandler = new SlideHandler();
		$this->videohandler = new VideoHandler();
		$this->messagehandler = new MessageHandler();
		$this->chathandler = new ChatHandler();
		$this->twitterhandler = new TwitterHandler();
		$p['log']->debug('Done');
	}
	
	function     say($msg=""){ echo $msg."\n"; }
	
	function process($user,$msg){ //called on every message
  
		//$this->say("< ".$msg);
	
		if(empty($user->roles)){ //first read from a new connection, establish what it is.
			$this->upgradeuser($user,$msg);//read type from message, create the right client handlers.
			return;
		}
		
		//message should be json object, 2 properties. msgfor->handler and data is passed through.
		
		$unencode = json_decode($msg);		
		$returnmsgs = null;
		
		$this->p['log']->info('Incoming Message: '.substr($msg,0,20));
		
		//echo $msg;
		
		//ALMIGHTY HACK FOR TRANSITION TO NEW CLIENT CODE:
		
		if($unencode && isset($unencode->action)) {//new style router? should probably get something awesome here with callables an stuff.
			switch($unencode->action) {
				case 'getHTMLTemplate' : $returnmsgs = $this->getHTMLTemplate($user, $unencode); break; // some default system class really here.
			}
		}
		
		else if($unencode && isset($unencode->bounce)) {
			foreach($this->users as $user) {
			$this->send($user->socket,json_encode($unencode));
			}
		}
		
		//ORIGINAL SERVER CODE
		
		 else if($unencode && isset($unencode->msgfor)) {
			switch($unencode->msgfor) {
				case 'server' : $returnmsgs = $this->servermessage($unencode->data,$user);  break; //handle messages for the server.
				case 'slidehandler' : $returnmsgs = $this->slidehandler->processmessage($unencode->data,$user);  break;
				case 'videohandler' : $returnmsgs = $this->videohandler->processmessage($unencode->data,$user);  break;
				case 'messagehandler' : $returnmsgs = $this->messagehandler->processmessage($unencode->data,$user);  break;
				case 'chathandler' : $returnmsgs = $this->chathandler->processmessage($unencode->data,$user);  break;
				case 'twitterhandler' : $returnmsgs = $this->twitterhandler->processmessage($unencode->data,$user);  break;
			}
		}
		
		
		
		else $this->p['log']->error('Malformed Message: '.substr($msg,0,20));
		
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
						//$this->say("> ".json_encode($returnmsg));
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
			//echo var_dump($user->clientid);
			$this->p['log']->notice('New Connection id: '.$user->clientid);
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
		
	function getHTMLTemplate($user, $message) { 
	
		$obj = new stdClass();
		if (preg_match('/^[A-Z0-9]+$/i', $message->template)) {
			# Have alphanumeric request
			$file = new \SplFileObject('templates/'.$message->template.'.html');
				if($file->isFile())
					{
					$obj->templateHTML = file_get_contents($file->getPathname());
					$obj->action = 'system.template';
					} else {
					$this->p['log']->error('Template not found '.$file->getPathname());
					}
			} else {
			$this->p['log']->error('Invalid template requested '.$message->template);
		}
		
		
		
		$returnmsg[$user->id] = $obj;
		
		return $returnmsg;
	}
	
	function servermessage($data,$user) {
	
		if($data == "restart") {
			$this->p['log']->warning('SERVER RESTARTING');
		 /*
		$WshShell = new COM("WScript.Shell");
		$oExec = $WshShell->Exec("php server.php");		
		while($input = $oExec->StdOut->ReadLine())echo $input;
		//$oExec = $WshShell->Run("php server.php", 1, false);
		*/
		
			exit(2);
		}
		else if($data == "exit") {
			$this->p['log']->warning('SERVER SHUTTING DOWN');
			exit(0);
		}
	}
}