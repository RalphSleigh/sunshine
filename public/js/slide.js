//this handles client side slide display, yup.
   
app.slide = (function(){
	var module = {}, displays = {};

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
	}
	/*	
	module.onMessage = function(msg) {
	
		if(msg.action == 'slide.display') displaySlide(msg);
	
	}
	*/
	return module;
})();