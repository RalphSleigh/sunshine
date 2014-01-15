//Stuff

var app = {};

app.system = (function(){
	var module = {};
	
	module.init = function(){
	
		app.ts.init(window.location.hostname,12345);
		
		if(window.location.pathname.match('test'))app.bounce.init();
		if(window.location.pathname.match('slide'))app.slide.init($('body'));
		if(window.location.pathname.match('dashboard'))app.dash.init();
			
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
