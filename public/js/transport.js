//This will attempt to use websockets to handle transport, but will fallback to XMLHTTPRequest if it cant.
//todo: AJAX fallback.

app.ts = (function(){

	var module = {}, ws, wsAddress, recList = $.Callbacks(), sendQueue = [];
	
	function wsMessage(msg) {
		recList.fire($.parseJSON(msg.data));
		}
		
	function wsOpen() {
		console.log('socket opened');
		//var reg = {id:"hi",roles:["display"]};
		//.send(JSON.stringify(reg));
		app.system.updateModes();
		app.ts.processQueue();
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
		if(ws.readyState == ws.OPEN) {
			console.log('Sending: ');
			console.log(payload);
			ws.send(JSON.stringify(payload));
		}
		else sendQueue.push(payload);
	}
		
	module.addMsgCallback = function(callback) { //things to do when we get a message
		recList.add(callback);
	}
	
	module.processQueue = function() {
		var item;
		while(item = sendQueue.pop()) {
			this.send(item);
		}
	}

	return module;
	}());