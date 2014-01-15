//this handles client side slide display

app.slide = (function() {
	var module = {}, container;

 	function displaySlide(slide) {
	
	alert(data);
	}
	
	module.init = function(domNode) {
		container = domNode;
		
		}

	module.onMessage = function(msg) {
	
		data = $.parseJSON(msg);
		if(data.action == 'slide.display') displaySlide(data);
	
	}
		
	return module;
})();