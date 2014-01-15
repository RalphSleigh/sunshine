//this module just makes a simple page to debug the JSON websockets. 
app.bounce = (function() {
	
	var module = {};

	module.onMessage = function(msg) {
	$('#messagebox').html(app.system.prettyJSON(msg));
	}

	module.send = function(){
		
		var data = $('#input').val();
		app.ts.send($.parseJSON(data));
		return false;
		}
	
	module.init = function(){
		//this is bad
		var markup = $('<pre id="messagebox"></pre><form><textarea style="width:600px;height:10em;" id="input" name="input" method="POST" /><button style="display:block" id="submitButton">GO</button>');
		$('body').append(markup);
		app.ts.addMsgCallback(module.onMessage);
		$('#submitButton').click(module.send);
	}
	return module;
}());