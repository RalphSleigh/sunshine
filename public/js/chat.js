//chat related functions

app.chat = (function(){
	var module = {},user;
	
	function sendChatButton() {
	
		var data = $('#chat-input-box').val();
		
		app.ts.send({"action":"chat.newChatMessage","message":data,"user":user});
		
	
	}
	
	function chatBoxObserver(e) {
		if (e.keyCode == 13) {
		sendChatButton();
		
		e.stopPropagation();
		$('#chat-input-box').val('');
		$('#chat-input-box').focus();
		return false;
		}
	}

	
	module.init = function(){
	
		//do we listen to chat? 
		if(!$('#chat-box').length)return;
		//set up chat
		var request = {};
		request.action = "chat.getExistingChat";
		app.ts.send(request);
		
		user = $.cookie('username') || prompt('Username:');
		$.cookie('username', user, { expires: 7 });
	}
	
	module.insertChatText = function(msg) {
	
		$('#chat-box').html(msg.data);
		$('#chat-send-button').click(sendChatButton);
		$('#chat-input-box').keypress(chatBoxObserver);
		
		$('#chat-box').animate({"scrollTop": $('#chat-box')[0].scrollHeight}, "slow");
	
	}
	
	module.newChatMessage = function(msg) {
	
		$('#chat-box').append(msg.data);
		$('#chat-box').animate({"scrollTop": $('#chat-box')[0].scrollHeight}, "slow");
	}
	
	return module;
}());