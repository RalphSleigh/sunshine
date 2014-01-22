//module that controls the dashboard screen. This is quite complicated.

app.dash = (function(){
	var module = {};

	function clientRefreshButton(e) {
		var id = $(e.target).data('clientid');
		app.ts.send({"action":"system.refreshClient","clientId":id});
	}
	
	function serverRestartButton(e) {
		app.ts.send({"action":"system.restartServer"});
	}
	
	function serverShutdownButton(e) {
		if(confirm("Are you sure you wish to restart the server? It won't come back"))app.ts.send({"action":"system.shutdownServer"});
	}
	
	//function client
	
	module.init = function(){
		//oh lordie
		var request = {};
		request.action = "system.getHTMLTemplate";
		request.template = "dashboard";
		request.call = "dash.installHTML";
		app.ts.send(request);
	}
	
	module.installHTML = function(msg){
	
		//this is called once we have the HTML template from the server.
	
		$('body').html(msg.templateHTML);
		$('body').addClass('dashboard');
		
		app.system.addMode('dashboard');
		app.ts.send({"action":"system.getClientInfo"});//update the client info
	}
	
	module.displayClientInfo = function(msg){
		
		$('#system-clients').html('');
		$.each(msg.clients, function( key, client ) { //this is ugly, but w/ever
			var line = '<div class="row">';
			line += '<div class="col-md-2"><p>{0}</p></div><div class="col-md-2"></p>{1}</p></div>'.format(client.remoteAddress, client.resourceId);
			line += '<div class="col-md-4"><p>{0}</p></div>'.format($.each(client.modes, function( key, mode ) { return mode }));
			line += '<div class="col-md-4"><button type="button" data-clientid="{0}" class="btn btn-default clientRefreshButton">Refresh</button></div>'.format(client.resourceId);
			$('#system-clients').append(line);
			});
		
		$('.clientRefreshButton').click(clientRefreshButton);	
		$('#system-clients-connected').html('  {0} connected'.format(msg.clients.length));
		
		$('#system-server-restart').click(serverRestartButton);
		$('#system-server-shutdown').click(serverShutdownButton);
		
		}
	
	
	
	return module;
}());