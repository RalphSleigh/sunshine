<?php
namespace Ralphie\Sunshine;
use \stdClass;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class MessageServer implements MessageComponentInterface{
	
	public $slidehandler;
	public $videohandler;
	//client handlers stored seperatly so functionality can be reused by ajax backend
	public $clienthandlers;
	public $clients = array();
	public $actions = array();
	
	public function __construct($p) {
		
		$p['log']->debug('Creating Handlers');
		$this->p = $p;
		$this->debug = true;
		$this->slidehandler = new SlideHandler();
		$this->videohandler = new VideoHandler();
		$this->messagehandler = new MessageHandler();
		$this->chathandler = new ChatHandler();
		$this->twitterhandler = new TwitterHandler();
		$p['log']->debug('Done');
		
		$this->registerAction('system.getHTMLTemplate',array($this,'getHTMLTemplate'));
	}
	
	public function registerAction($action, callable $method) {
	$this->actions[$action] = $method;
	}
	
	public function onOpen(ConnectionInterface $conn) {
		$this->p['log']->notice('New Connection: '.$conn->resourceId);
		$this->clients[$conn->resourceId] = $conn;
    }
	
	public function onClose(ConnectionInterface $conn) {
		$this->p['log']->notice('Disconnected: '.$conn->resourceId);
		unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
		throw $e; //this is not a very good idea, exception will end up in the log.
    }
	
	public function onMessage(ConnectionInterface $conn, $msg){ //called on every message
			
		//message should be json object, 2 properties. msgfor->handler and data is passed through.
		
		$message = json_decode($msg);		
		$returnmsgs = null;
		
		$this->p['log']->info('Incoming Message: '.substr($msg,0,20));
			
		//ALMIGHTY HACK FOR TRANSITION TO NEW CLIENT CODE:
		
		if($message && isset($message->action)) {
			$this->actions[$message->action]($conn, $message); // call right handler
			}

		else if($message && isset($message->bounce)) {
			foreach($this->clients as $client) {
			$client->send(json_encode($message));
			}
		}
		
		//ORIGINAL SERVER CODE
		
		 else if($message && isset($message->msgfor)) {
			switch($message->msgfor) {
				case 'server' : $returnmsgs = $this->servermessage($unencode->data,$user);  break; //handle messages for the server.
				case 'slidehandler' : $returnmsgs = $this->slidehandler->processmessage($unencode->data,$user);  break;
				case 'videohandler' : $returnmsgs = $this->videohandler->processmessage($unencode->data,$user);  break;
				case 'messagehandler' : $returnmsgs = $this->messagehandler->processmessage($unencode->data,$user);  break;
				case 'chathandler' : $returnmsgs = $this->chathandler->processmessage($unencode->data,$user);  break;
				case 'twitterhandler' : $returnmsgs = $this->twitterhandler->processmessage($unencode->data,$user);  break;
			}
		}
		
		
		else $this->p['log']->error('Malformed Message: '.substr($msg,0,20));
		
		//$this->p['log']->info('Finished processing message');
		
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
   
	function getHTMLTemplate($conn, $message) { 
		$obj = new stdClass();
		if (preg_match('/^[A-Z0-9]+$/i', $message->template)) {
			# Have alphanumeric request
			$file = new \SplFileInfo('templates/'.$message->template.'.html');
				if($file->isFile())
					{
					# Is actually a file we can send
					$obj->templateHTML = file_get_contents($file->getPathname());
					$obj->action = $message->call;
					$conn->send(json_encode($obj));
					} else {
					$this->p['log']->error('Template not found '.$file->getPathname());
					}
			} else {
			$this->p['log']->error('Invalid template requested '.$message->template);
		}
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