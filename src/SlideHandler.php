<?php 
namespace Ralphie\Sunshine;
/*
This class contains functionality related to displaying html slides.
Functions should not directly access the transport layer, to allow it to work on both websocket and ajax connections.
*/
class SlideHandler{
	 
	 
	public $messageserver;

	function __construct() {
		
		$this->currentslide = "";
		
		}
	
	function processmessage($msg,$fromuser) { //return an array of messages to send id => message
		
		$tocall = $msg->call;
		if(method_exists($this,$tocall))return $this->$tocall($msg->data,$fromuser);
		else echo "method not found";//this makes perfect sense, extract the call value and call it with the next layer of data
		
		}
	
	function displayslide($msg,$fromuser) {
		
		echo "displayslide called";
		$obj = new stdClass();
		$obj->clienthandler = $msg->on;
		
		$filename = str_replace("slidepath-","",$msg->slidetoload);
		$obj->filename = $filename;


		echo "\nfile is:".$filename."\n";
		if($file=file_get_contents($filename)) {
			$obj->html  = $file;
		}
		else $obj->html ="File not found";

		$returnmsg['display'] = $obj;
		if($msg->on=="slideClientLive")$this->currentslide = $obj;
		return $returnmsg;
	}
	
	function getcurrentslide($msg,$fromuser) { //used for ajax calls
		
		echo "getcurrentslide called \n";
		$returnmsg[$fromuser->id] = $this->currentslide;
		return $returnmsg;
	}
	
	function getslidetree($msg,$fromuser) { //data not important here, but user is.
		echo "getting slide tree";
	
		$obj = new stdClass();
		$obj->clienthandler = 'slideControl';
		
		$tree = $this->recurseslidedirectory(SLIDE_DIRECTORY);

		$obj->data = new stdClass();
		
		$obj->data->call = "updateList";
		$obj->data->data = $tree;
	
		$returnmsg[$fromuser->id] = $obj;
		
		//print_r($returnmsg);
		
		return $returnmsg;
	}
	
	function recurseslidedirectory($dir) {
		
		$items = array();


		
		foreach (new DirectoryIterator($dir) as $fileInfo) {
			
			$file = array();
			
			if($fileInfo->isDot()) continue;
			$file['id'] = $fileInfo->getPathname();
			$file['txt'] = $fileInfo->getFilename();
			if($fileInfo->isDir())$file['items'] = $this->recurseslidedirectory($fileInfo->getPathname());
			$items[] = $file;
			
			//echo $fileInfo->getFilename() . "<br>\n";
		}
		
		usort($items,"cmp");
		print_r($items);
		return $items;
	}

	function displaynextslide($msg,$fromuser) { //for richards remote, this code sucks, yes, I know.
		
		$itt = new RecursiveIteratorIterator( new RecursiveArrayIterator($this->recurseslidedirectory(SLIDE_DIRECTORY)));
		foreach($itt as $item) {
			
		if($this->currentslide->filename == $item) {
			echo $this->currentslide->filename."\n";
			print_r($item);
			echo "\n\n";
			$itt->next();
			echo "\n";
			echo $itt->current();
			$itt->next();
			echo "\n";
			echo $itt->current();
			
			$obj = new stdClass;
			
			$obj->on="slideClientLive";
			$obj->slidetoload = $itt->current();
			
			return $this->displayslide($obj,null);
			
			}
		
		}
	
	
	
	}
	
	function displayprevslide($msg,$fromuser) { //for richards remote, this code sucks, yes, I know.
		
		$prev1 = null;
		$prev2 = null;
		$itt = new RecursiveIteratorIterator( new RecursiveArrayIterator($this->recurseslidedirectory(SLIDE_DIRECTORY)));
		foreach($itt as $item) {
			
		if($this->currentslide->filename == $item) {
			echo $this->currentslide->filename."\n";
			print_r($item);
			echo "\n\n";
			//$itt->next();
			//echo "\n";
			//echo $itt->current();
			//$itt->next();
			//echo "\n";
			//echo $itt->current();
			
			$obj = new stdClass;
			
			$obj->on="slideClientLive";
			$obj->slidetoload = $prev2;
			
			return $this->displayslide($obj,null);
			
			}
		
			$prev2 = $prev1;
			$prev1 = $item;
		
		}
	
	
	
	}
	
}

function cmpSPLFileInfo( $splFileInfo1, $splFileInfo2 )
{
    return strcmp( $splFileInfo1->getFileName(), $splFileInfo2->getFileName() );
}


function cmp($a, $b)
{
    //if ($a->getFilename() == $b->getFilename()) {
     //   return 0;
    //}
    return strcmp($a['txt'],$b['txt']);
}
?>
