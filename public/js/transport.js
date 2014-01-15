//This will attempt to use websockets to handle transport, but will fallback to XMLHTTPRequest if it cant.
//todo: AJAX fallback.



app.ts = (function(){

	var module = {}, ws, recList = $.Callbacks();
	
	function wsMessage(msg) {
		recList.fire($.parseJSON(msg.data));
		}
		
	function wsOpen() {
		console.log('socket opened');
		var reg = {id:"hi",roles:["display"]};
		ws.send(JSON.stringify(reg));
	}
	
	module.init = function(host, port){
		ws = new WebSocket('ws://'+host+':'+port);
		
		ws.onopen = wsOpen;
		ws.onmessage = wsMessage;
		
		}
			
	module.send = function(payload) {
		ws.send(JSON.stringify(payload));
		}
		
	module.addMsgCallback = function(callback) { //things to do when we get a message
		recList.add(callback);
	}
	
	
	return module;
	}());