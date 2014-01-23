function chatClientClass(container) {
	this.container = container;
	this.username = prompt("Enter Your Username:", "");
	
	this.processmessage = function(msgdata) {
		log('chatClient processing message');
		this[msgdata.data.call](msgdata.data.data);
		}
		
	this.setup = function() { //once we has a websocket get the existing chat data
		
		var data = 	{"msgfor": "chathandler", "data":
					{"call":"getexistingchat","data":""
						}};
		transport.send(data);

	};
	
	this.replaceChatContent = function(data) {
	$('chatcontent').innerHTML = data;
	$('chatcontent').scrollTop = $('chatcontent').scrollHeight;
	};
	
	this.updateChatContent = function(data) {
	$('chatcontent').innerHTML += data;
	$('chatcontent').scrollTop = $('chatcontent').scrollHeight;
	};
	
	
	this.sendChat = function() {
	
		var chatelement = $('chatinput')
		
		var chat = chatelement.getValue();
		
		var data = 	{"msgfor": "chathandler", "data":
					{"call":"newchatmessage","data":{"user":this.username,"chat":chat}
						}};
		transport.send(data);
		chatelement.clear();
		chatelement.activate();
		}
		
	
	this.buttonHandler = function(e) {
	this.sendChat();
	Event.stop(e);
	}
	
	this.keyHandler = function(e) {
		if (e.keyCode == Event.KEY_RETURN) {
		this.sendChat();
		Event.stop(e);
		}
	}
	
	$('chatsendbutton').observe('click',this.buttonHandler.bind(this));
    $('chatinput').observe('keypress',this.keyHandler.bind(this));
  return this;
}