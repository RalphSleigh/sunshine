<?php
namespace Ralphie\Sunshine;
use \stdClass;
use \DateTime;

class TwitterController {
	
	const SEARCH_INTERVAL_TIME = 30;
	
	private $lastRequestTime = 0;
	private $tweets = array();
	
	public function __construct(
	\Monolog\Logger $logger,
	\Twitter $t){
		$this->log = $logger;
		$this->t = $t;
		$this->log->debug('TwitterController Constructed');
	}

	private function requestTweets() {
	
		if(time() > $this->lastRequestTime + self::SEARCH_INTERVAL_TIME) {
			$this->log->debug('Requesting Tweets '.$this->lastRequestTime);
			$results = $this->t->search('#woodcraft');
			foreach($results as $tweet) {	
				
				$tweet->HTML = \Twitter::clickable($tweet);
				$date = new DateTime($tweet->created_at);
				$tweet->shortDate = $date->format('d/m G:i');
				$this->tweets[$tweet->id] = $tweet;
			}
			$this->lastRequestTime = time();
	
		} else $this->log->debug('Requesting tweets too quickly');
	}
	
	public function getTweets($conn,$msg) {
	
		
		$this->requestTweets();
		
		uasort($this->tweets, function($a,$b) {
			return new DateTime($a->created_at) < new DateTime($b->created_at);
		}); //Well performance here must suck. 
		
		$obj = new stdClass;
		$obj->tweets = $this->tweets;
		$obj->action = "dash.displayTweetList";
		
		
		$this->log->debug('currently have: '.count($this->tweets));
		$conn->send(json_encode($obj));
	}
	
}