host = "ws://192.168.1.151:12345/websocket/server.php";
document.observe("dom:loaded", displaySetup);


function displaySetup() {

	//transport = new AJAXTransportClass(host,displayid,["display"]);
	//slideControl = new slideControlClass($('slidecontrol'));
	slideClientLive = new slideClientClass($('displaycontainer'),true);
	//slideClientPreview = new slideClientClass($('previewwindow'));

	Event.observe(window,"resize", slideClientLive.callResize.bind(slideClientLive));
	//Event.observe(window,"resize", slideClientPreview.callResize.bind(slideClientPreview));
	
	//$('golivebutton').observe('click',slideControl.previewToLive.bind(slideControl));
	new PeriodicalExecuter(updateDisplay, 10);
};

function updateDisplay() {
	log("doing the ajax thing");
	new Ajax.Request('ajax.php', {
	method: 'get',
	onSuccess: function(transport) {
		applyupdate(transport.responseText);
		//var messagestring = transport.responseText;
		//var msgdata = messagestring.evalJSON();
		//slideClientLive.processmessage(msgdata);
		}
	});
};

function applyupdate(msgstring) {
		var msgdata = msgstring.evalJSON();
		slideClientLive.processmessage(msgdata);
		
	}


function resizeevent() {
	slideclient.resizecontent();
	};

function log(msg){
	if(DEBUG) console.log(msg);
	};
	
