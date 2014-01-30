//this handles client side slide display, yup.
   
app.slide = (function(){
	var module = {}, displays = {};

	/*
	module.init = function() {
		console.log('slide init');
		container = $('#root');//hack for now
		
		//make some DIVs
		margin = $('<div class="margin"></div>');
		content = $('<div class="content"></div>');
		container.append(margin);
		margin.append(content);
		container.addClass('displaywindow');
		
		app.system.addMode('slides');
		
		}
	*/
	
	function resizeContent(index,c) {
	
		//reset
		var scale = 0.4;
		c.container.css('font-size',scale+'em');
	
		var currentHeight = c.content.height();
		var targetHeight = c.margin.height();
		var i = 0;
		var pH;
		while(currentHeight < targetHeight && i < 100) {
			var ratio = targetHeight/currentHeight;
			pH = scale;
			scale = scale * ((ratio - 1) * 0.2 + 1);
			c.container.css('font-size',scale+'em');
			currentHeight = c.content.height();
			i++;
		}
		c.container.css('font-size',pH+'em');
	}
	
	module.resizeDisplays = function() {
	
		$.each(displays, resizeContent);
	
	}
	
	
	module.registerDisplay = function(div,context) {
		var c = displays[context] = {};
		c.container = div;
		c.margin = $('<div class="margin"></div>');
		c.content = $('<div class="content"></div>');
		c.container.append(c.margin);
		c.margin.append(c.content);
		c.container.addClass('displaywindow');
	}
		
	module.displaySlide = function(msg) {
	
		var c = displays[msg.context];
		c.content.html(msg.slideHTML);
		resizeContent(0,c);
	}
	/*	
	module.onMessage = function(msg) {
	
		if(msg.action == 'slide.display') displaySlide(msg);
	
	}
	*/
	return module;
})();