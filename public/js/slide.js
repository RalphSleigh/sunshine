//this handles client side slide display

app.slide = (function() {
	var module = {}, container,margin,content;

 	function displaySlide(msg) {
		
		content.html(msg.slideHTML);
		//need to resize here so it fits cool.
	
		//alert(data);
	}
	
	module.init = function(domNode) {
		container = domNode;//store this
		
		//make some DIVs
		margin = $('<div class="margin"></div>');
		content = $('<div class="content"></div>');
		container.append(margin);
		margin.append(content);
		container.addClass('displaywindow');
		
		app.ts.addMsgCallback(app.slide.onMessage);//register for messages
		
		
		}

	module.onMessage = function(msg) {
	
		if(msg.action == 'slide.display') displaySlide(msg);
	
	}
		
	return module;
})();