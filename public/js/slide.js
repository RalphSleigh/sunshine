//this handles client side slide display, yup.
   
app.slide = (function(){
	var module = {}, container,margin,content;

	module.init = function() {
		console.log('slide init');
		container = $('body');//hack for now
		
		//make some DIVs
		margin = $('<div class="margin"></div>');
		content = $('<div class="content"></div>');
		container.append(margin);
		margin.append(content);
		container.addClass('displaywindow');
		
		app.system.addMode('slides');
		
		}

	module.displaySlide = function(msg) {
		content.html(msg.slideHTML);
	}
	/*	
	module.onMessage = function(msg) {
	
		if(msg.action == 'slide.display') displaySlide(msg);
	
	}
	*/
	return module;
})();