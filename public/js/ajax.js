//contains the axaj client backend

function websocketTransportClass(host,id,roles,openCallback) {

	this.socket = new WebSocket(host);
	this.openCallback = openCallback;
 
	this.socket.onerror = function(err){
		};
	
	this.socket.onmessage = function(msg){
		log('Recieved:  '+msg.data);
		msgdata = msg.data.evalJSON();
	
		if(window[msgdata.clienthandler])window[msgdata.clienthandler].processmessage(msgdata);
	
		//$('content').innerHTML = jsonstring; 	
		};
	
    this.socket.onopen = function(){
		log('socket opened');
		info = {id:id,roles:roles};
		this.send(Object.toJSON(info));
		if(openCallback)setTimeout(openCallback,1000)//HACK!!!: need to fix server to accept more than one msg/send event.
		//openCallback();
		};
    
    this.socket.onclose   = function(){ 
		setTimeout(controlSetup,1000);
	};
	
	this.send = function(data) {
		this.socket.send(Object.toJSON(data));
		log('Sent: '+Object.toJSON(data)); 
	};
	
return this;	
}