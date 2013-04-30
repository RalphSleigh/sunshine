document.observe("dom:loaded", controlSetup);
var init = false

function controlSetup() {
	
	if(!init) {
	log("opening connection");
	transport = new websocketTransportClass(host,"maincontrol",["control","display"],openCallback);
	slideControl = new slideControlClass($('slidecontrol'));
	slideClientLive = new slideClientClass($('livewindow'));
	slideClientPreview = new slideClientClass($('previewwindow'));
	messageClient = new messageClientClass($('livewindow'));

	Event.observe(window,"resize", slideClientLive.callResize.bind(slideClientLive));
	Event.observe(window,"resize", slideClientPreview.callResize.bind(slideClientPreview));
	
	$('golivebutton').observe('click',slideControl.previewToLive.bind(slideControl));
	$('playmessagebutton').observe('click',sendMessage);
$('playtweetbutton').observe('click',sendTweet);
	init = true;
	}
	
};

function openCallback() {
log("connection open, setting up stuff");
slideControl.setup();
};

function log(msg){
	if(DEBUG) console.log(msg);
	};
	
function sendMessage() {
	var message = $('messageinput').getValue();
	var data = 	{"msgfor": "messagehandler", "data":
					{"call":"displaymessage","data":
						{"message":message}}};
	transport.send(data);
	}

function sendTweet() {
	var message = $('twitterinput').getValue();
	var data = 	{"msgfor": "twitterhandler", "data":
					{"call":"displaymessage","data":
						{"message":message}}};
	transport.send(data);
	}
  
