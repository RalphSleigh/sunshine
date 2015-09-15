<?php
namespace Ralphie\Sunshine;
use \stdClass;

class SlideController {

	public function __construct(
	\Monolog\Logger $logger){
		$this->log = $logger;
		$this->log->debug('SlideController Constructed');
	}
	/*
	{
  id          : "string" // will be autogenerated if omitted
  text        : "string" // node text
  icon        : "string" // string for custom
  state       : {
    opened    : boolean  // is the node open
    disabled  : boolean  // is the node disabled
    selected  : boolean  // is the node selected
  },
  children    : []  // array of strings or objects
  li_attr     : {}  // attributes for the generated LI node
  a_attr      : {}  // attributes for the generated A node
}
*/
	public function getSlideTree($conn, $msg) {
	//return an object graph representing the slides folder for display in the slide selection treeview.

		$obj = new \StdClass;
		$obj->action = "dash.displaySlideTree";
		$obj->data = $this->getSubTree('exampleslides');

		$conn->send(json_encode($obj));
	}
	
	public function slideSelected($conn, $msg) {
	//send the slide to the connected dashboard for display in the preview window
	
		$file = file_get_contents($msg->slideId);
		$conn->slideId = $msg->slideId; // store this to display later.
		
		$obj = new \StdClass;
		$obj->action = 'slide.displaySlide';
		$obj->context = 'preview';
		$obj->slideHTML = $file;

		$conn->send(json_encode($obj));
	}
	
	public function goLogo($conn, $msg) {
	
		if($file = file_get_contents('exampleslides/logo.html')) { //hard code = BAD
		
			$obj = new \StdClass;
			$obj->action = 'slide.displaySlide';
			$obj->context = 'live';
			$obj->slideHTML = $file;
			
			foreach($conn->ms->clients as $client)$client->send(json_encode($obj));
		}
	}
	
	public function goLive($conn, $msg) {
	
		if(!$conn->slideId) return; //Nope!
		$file = file_get_contents($conn->slideId);
		
		$obj = new \StdClass;
		$obj->action = 'slide.displaySlide';
		$obj->context = 'live';
		$obj->slideHTML = $file;

		foreach($conn->ms->clients as $client)$client->send(json_encode($obj));
	}
	
	protected function getSubTree($directory) {
		
		$itt = new \FilesystemIterator($directory);
		$array = iterator_to_array($itt);
		
		$result = array();
		
		usort($array, function($a,$b){
			
			if($a->IsDir() && !$b->IsDir())return -1;
			if(!$a->IsDir() && $b->IsDir())return 1;
			return $a->getPathname() > $b->getPathname();
			});
		
		foreach($array as $file) {
			$obj = new \StdClass;
			$obj->id = $file->GetPathname();
			$obj->text = $file->GetFilename();
			$obj->icon = "glyphicon glyphicon-file icon-blue";
			if($file->IsDir()){
				$obj->children = $this->getSubTree($obj->id);
				$obj->icon = "glyphicon glyphicon-folder-open icon-yellow";
			}
			$result[] = $obj;
		}
		return $result;
		
	}
	
}