//Stuff
 
var app = {};
 
app.system = (function(){
	var module = {},modes = [];
	
	function debouncer(func, timeout) {
		var timeoutID , timeout = timeout || 500;
		return function () {
			var scope = this , args = arguments;
			clearTimeout( timeoutID );
			timeoutID = setTimeout( function () {
          func.apply( scope , Array.prototype.slice.call( args ) );
      } , timeout );
   }
}
	
	
	module.init = function(){
	
		app.ts.init(window.location.hostname,12345);
		app.ts.addMsgCallback(module.onMessage); //generic action handler.
		$('#connecting').show();
		$(window).resize(debouncer(app.slide.resizeDisplays,100));
		
		if(window.location.pathname.match('slide'))app.ts.addOpenCallback(app.slide.registerDisplay($('#root'),'live'));
		else if(window.location.pathname.match('dashboard'))app.dash.init();
			
	}	
	
	module.onMessage = function(msg) {
		//lets get gnarly and extract the function out of the window object by MAGIC.
		var part, parts = msg.action.split('.'), methodToCall = app;
		while(part = parts.shift()){
			if(methodToCall[part]) {
				methodToCall = methodToCall[part];
			} else {
				methodToCall = false;
			}
		}
		//now call it if it all worked.
		if(methodToCall) {
			console.log('Incoming message, action: '+msg.action);
			methodToCall(msg);		
		} else {
			console.log('Incoming message, unknown action: '+msg.action);
		}
	}
	
	module.addMode = function(mode) {
		modes.push(mode);
		this.updateModes();
	}
		
	module.updateModes = function() {
	if(modes.length > 0)app.ts.send({"action":"system.registerModes","modes":modes});//let server know what we are.
	}
	
	module.refresh = function() {
		location.reload(true);
	}
	
	module.prettyJSON = function(json) {
		if (typeof json != 'string') {
			json = JSON.stringify(json, "\n", 2);
		}
		json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
			var cls = 'number';
			if (/^"/.test(match)) {
				if (/:$/.test(match)) {
					cls = 'key';
				} else {
					cls = 'string';
				}
			} else if (/true|false/.test(match)) {
				cls = 'boolean';
			} else if (/null/.test(match)) {
				cls = 'null';
			}
			return '<span class="' + cls + '">' + match + '</span>';
		});
	}
	
	return module;
}());

$(app.system.init);

String.prototype.format = function () {
  var args = arguments;
  return this.replace(/\{\{|\}\}|\{(\d+)\}/g, function (m, n) {
    if (m == "{{") { return "{"; }
    if (m == "}}") { return "}"; }
    return args[n];
  });
};
