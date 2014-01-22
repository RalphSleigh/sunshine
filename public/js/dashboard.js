//module that controls the dashboard screen. This is quite complicated.

app.dash = (function(){
	var module = {};

	
	module.init = function(){
		//oh lordie
		var request = {};
		request.action = "system.getHTMLTemplate";
		request.template = "dashboard";
		request.call = "dash.installHTML";
		app.ts.send(request);
	}
	
	module.installHTML = function(msg){
		$('body').html(msg.templateHTML);
	}
	
	return module;
}());