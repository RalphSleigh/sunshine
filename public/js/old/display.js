document.observe("dom:loaded", displaySetup);


function displaySetup() {

	transport = new websocketTransportClass(host,displayid,["display","message"]);
	//slideControl = new slideControlClass($('slidecontrol'));
	slideClientLive = new slideClientClass($('displaycontainer'),true);
	//slideClientPreview = new slideClientClass($('previewwindow'));
	messageClient = new messageClientClass($('displaycontainer'));
	twitterClient = new twitterClientClass($('displaycontainer'));

	Event.observe(window,"resize", slideClientLive.callResize.bind(slideClientLive));
	//Event.observe(window,"resize", slideClientPreview.callResize.bind(slideClientPreview));
	
	//$('golivebutton').observe('click',slideControl.previewToLive.bind(slideControl));
	
};

function resizeevent() {
	slideclient.resizecontent();
	}


function connectDisplay() {

	slideClient = new slideClientClass($('content'));
	videoClient = new videoClientClass($('content'));
	Event.observe(window,"resize", resizeevent);
	socket = new WebSocket(host);

	socket.onerror = function(err){
		};
	
	socket.onmessage = function(msg){
	
		msgdata = msg.data.evalJSON();
	
		window[msgdata.clienthandler].processmessage(msgdata);
	
		//$('content').innerHTML = jsonstring; 	
		};
	
    socket.onopen    = function(){
		log('socket opened');
		info = {id:displayid,roles:["display"]};
		socket.send(Object.toJSON(info));

										};
    
    socket.onclose   = function(){ 
		setTimeout(connectDisplay,1000);
	};


	
//alert('woot');
};



function videoClientClass(container) {
	this.container = container;
  
	this.processmessage = function(msgdata) {
		log('Displying a video');
		this.container.setStyle({fontSize:'1em'});
		this.container.innerHTML = msgdata.html;
	};
	

	
  return this;
}



function log(msg){
	if(DEBUG) console.log(msg);
	}
  


