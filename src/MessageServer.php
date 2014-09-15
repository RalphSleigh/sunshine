<?php 
namespace Ralphie\Sunshine;
use \stdClass;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class MessageServer implements MessageComponentInterface{
	 
	public $clients = array();
	public $actions = array();
	
	public function __construct(
		\Monolog\Logger $logger,
		\Ralphie\Sunshine\ActionRouter $router
		){
		
		$this->log = $logger;
		$this->router = $router;
		
		$this->router->setMS($this);//OH NOES! Circular dependency! Need to factor out both the clients array and system commands to resolve.
		
		/*
		$this->slidehandler = new SlideHandler();
		$this->videohandler = new VideoHandler();
		$this->messagehandler = new MessageHandler();
		$this->chathandler = new ChatHandler();
		$this->twitterhandler = new TwitterHandler();
		*/

		/*
		$this->registerAction('system.getHTMLTemplate',array($this,'getHTMLTemplate'));
		$this->registerAction('system.registerModes',array($this,'registerModes'));
		$this->registerAction('system.getClientInfo',array($this,'getClientInfo'));
		$this->registerAction('system.refreshClient',array($this,'refreshClient'));
		$this->registerAction('system.restartServer',array($this,'restartServer'));//REFACTORTHIS
		$this->registerAction('system.shutdownServer',array($this,'shutdownServer'));
		*/
				
		$this->log->debug('MessageServer Constructed');
		
	}
	/*
	public function registerAction($action, callable $method) {
	$this->actions[$action] = $method;
	}
	*/
	
	public function onOpen(ConnectionInterface $conn) {
		$this->log->notice('New Connection: '.$conn->remoteAddress.' '.$conn->resourceId);
		$this->clients[$conn->resourceId] = $conn;
		$conn->modes = array();
		$conn->ms = $this;//HACK;
		$conn->lastSeen = time();
		//foreach($this->clients as $client) {
		//	if(in_array('dashboard',$client->modes))$this->getClientInfo($client, null);
		//	}
    }
	
	public function onClose(ConnectionInterface $conn) {
		$this->log->notice('Disconnected: '.$conn->remoteAddress.' '.$conn->resourceId);
		unset($this->clients[$conn->resourceId]);
		$this->updateDashClientLists();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
		throw $e; //this is not a very good idea, exception will end up in the log.
    }
	
	public function onMessage(ConnectionInterface $conn, $msg){ //called on every message
		
		//update lastseen time on client
		
		$conn->lastSeen = time();
		//message should be json object, 2 properties. msgfor->handler and data is passed through.
		
		$message = json_decode($msg);		
		//$returnmsgs = null;
		
		//$this->log->info('Incoming Message: '.substr($msg,0,40));
			
		//New server code:
		
		if($message && isset($message->bounce)) { //debugging send bounce messages to everyone
			foreach($this->clients as $client) {
			$client->send(json_encode($message));
			}
		}
		
		$this->router->route($conn,$message);
		/*
		else if($message && isset($message->action)) {
			if($this->actions[$message->action])$this->actions[$message->action]($conn, $message); // call right handler
			else $this->p['log']->error('Unknown action: '.$message->action);
			}
		*/
		
		
		//ORIGINAL SERVER CODE
		/*
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
		*/
		
		//else $this->p['log']->error('Malformed Message: '.substr($msg,0,20));
		
		//$this->p['log']->info('Finished processing message');
		/*
		if($returnmsgs) { //Loop over list of return messages and send them to any client with matching id/role.
			//print_r($returnmsgs);
			foreach($returnmsgs as $for => $returnmsg) { 			
				 /*
				$returnuser = $this->getuserbyid($for);
				if($returnuser)$this->send($returnuser->socket,json_encode($returnmsg));
				foreach($this->getusershaverole($for) as $returnuser)$this->send($returnuser->socket,json_encode($returnmsg));
				
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
		*/
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
					$this->log->error('Template not found '.$file->getPathname());
					}
			} else {
			$this->log->error('Invalid template requested '.$message->template);
		}
	}
	
	public function registerModes($conn, $message) {
		//$conn->modes[] = $message->mode;  //thios fails becasue of __get/__set redirection apparently.
		$modes = $conn->modes;
		foreach($message->modes as $mode)$modes[] = $mode;
		$conn->modes = $modes;
		
		$this->updateDashClientLists();
		
		}
	
	public function getClientInfo($conn, $message) {
		$return = new stdClass();
		foreach($this->clients as $resource => $client){
			$clientobj = new stdClass();
			$clientobj->resourceId = $resource;
			$clientobj->remoteAddress = $client->remoteAddress;
			$clientobj->modes = $client->modes;
			if($client == $conn)$clientobj->me = true;
			$return->clients[] = $clientobj;		
			}
		$return->action = "dash.displayClientInfo";
		$conn->send(json_encode($return));
	}
	
	public function updateDashClientLists() {
		foreach($this->clients as $client) {
			if(in_array('dashboard',$client->modes))$this->getClientInfo($client, null);
			}
		}
	
	public function refreshClient($conn, $message) {
		if($this->clients[$message->clientId]) {
			$return = new stdClass();
			$return->action = 'system.refresh';
			$this->log->notice('Refreshing: '.$message->clientId);
			$this->clients[$message->clientId]->send(json_encode($return));
		}
	}
		
	public function restartServer($conn, $message) {
		$this->log->warning('SERVER RESTARTING');
		exit(2);
	}
	
	public function shutdownServer($conn, $message) {
		$this->log->warning('SERVER SHUTTING DOWN');
		exit(0);
	}
	
	public function pong($conn, $message) {}
	
}