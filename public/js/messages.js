app.messages = (function(){
	var module = {}, displays = {}, message;
	
	function displayMessageonDisplay(i,c) {
	//do the shizzle;
	c.messageBox.hide();
	c.messageInner.html(message.contents);
	c.messageBox.toggle( "slide",{"direction":"up"}, 400).delay(message.duration*1000).toggle( "slide",{"direction":"up"}, 400);
	}
	
	module.displayMessage = function(msg) {
		message = msg;
		$.each(displays, displayMessageonDisplay);
	}

	module.registerDisplay = function(div,context) {
		var c = displays[context] = {};
		c.container = div;
		c.messageBox = $('<div class="messageBox"></div>');
		c.messageInner = $('<div class="messageInner"></div>');
		c.container.prepend(c.messageBox);
		c.messageBox.append(c.messageInner);
		

	}
	
	module.init = function(){};
	

return module;	
}());