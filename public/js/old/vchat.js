document.observe("dom:loaded", controlSetup);
var init = false

function controlSetup() {

	transport = new websocketTransportClass(host,chatid,["chat","display"],openCallback);
	if(!init) {
	slideClientLive = new slideClientClass($('livewindow'));
	chatClient = new chatClientClass($('chatcontainer'));
	messageClient = new messageClientClass($('livewindow'));
	twitterClient = new twitterClientClass($('livewindow'));
	
	var windowdim = document.viewport.getDimensions();
	//$('livewindow').setStyle({"height":windowdim.height- 20+"px"});
	$('chatcontent').setStyle({"height":windowdim.height - 390+"px"});
	
	Event.observe(window,"resize", chatResize);
	Event.observe(window,"resize", slideClientLive.callResize.bind(slideClientLive));
	init = true;
	}
	//$('golivebutton').observe('click',slideControl.previewToLive.bind(slideControl));
	//$('playmessagebutton').observe('click',sendMessage);
	
};

function chatResize() {

	var windowdim = document.viewport.getDimensions();
	//$('livewindow').setStyle({"height":windowdim.height- 20+"px","width":(windowdim.width - 40)/2+"px"});
	$('chatcontent').setStyle({"height":windowdim.height - 390+"px"});

}

function openCallback() {
log("connection open, setting up stuff");
chatClient.setup();
};

function log(msg){
	if(DEBUG) console.log(msg);
	};

/*	
function sendMessage() {
	var message = $('messageinput').getValue();
	var data = 	{"msgfor": "messagehandler", "data":
					{"call":"displaymessage","data":
						{"message":message}}};
	transport.send(data);
	}
  
  */
