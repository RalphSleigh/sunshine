<?php
namespace Ralphie\Sunshine;
use \stdClass;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class PingHandler{

	public function __construct(
		\Monolog\Logger $logger,
		\Ralphie\Sunshine\MessageServer $server
		){
		
		$this->log = $logger;
		$this->server = $server;
		$this->log->debug('PingHandler Constructed');
		}
		
	public function ping() {
	
		$time = time();
	
		foreach($this->server->clients as $client) {
	
			
			//$this->log->debug('client '.$client.' time '.($time - $client->lastSeen));
			
			//if its been too long, close the connection
			if($time - $client->lastSeen > SUNSHINE_PING_DISCONNECT) {
			
				$this->log->debug('Closing '.$client->resourceId);
				$client->close();
				continue;
				
			}
			//if we have not seen it since the last ping interval, send a ping, the reply should update lastSeen
			if($time - $client->lastSeen > SUNSHINE_PING_INTERVAL) {
				$this->log->debug('Sending ping to '.$client->resourceId);
				$return = new stdClass();
				$return->action = 'system.ping';
				$client->send(json_encode($return));
			}
		}
	
	
	//$this->log->Info('PINGING');
	}
		
}



?>