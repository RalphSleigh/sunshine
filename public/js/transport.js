//This will attempt to use websockets to handle transport, but will fallback to XMLHTTPRequest if it cant.
//todo: AJAX fallback.

app.ts = (function(){

	var module = {}, ws, wsAddress, recList = $.Callbacks();
	
	function wsMessage(msg) {
		recList.fire($.parseJSON(msg.data));
		}
		
	function wsOpen() {
		console.log('socket opened');
		var reg = {id:"hi",roles:["display"]};
		ws.send(JSON.stringify(reg));
	}
	
	function wsClose() {
		console.log('socket closed');
		setTimeout(module.init,2000);
	
	}
	
	module.init = function(host, port){
		console.log('Opening');
		if(!wsAddress)wsAddress = 'ws://'+host+':'+port;
		ws = new WebSocket(wsAddress);
		
		ws.onopen = wsOpen;
		ws.onmessage = wsMessage;
		ws.onclose = wsClose;
		
		}
			
	module.send = function(payload) {
		ws.send(JSON.stringify(payload));
		}
		
	module.addMsgCallback = function(callback) { //things to do when we get a message
		recList.add(callback);
	}

	return module;
	}());