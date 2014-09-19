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
	
	function JSONSendButton() {	
		var data = $('#system-json-send').val();
		app.ts.send($.parseJSON(data));
	}
	
	function goLiveButton() {
		app.ts.send({"action":"slides.goLive"});
	}
	
	function displayLogoButton() {
		app.ts.send({"action":"slides.goLogo"});
	}
	
	function displayMessageButton() {
		var data = $('#slides-message-box').val();
		var duration = $('#slides-message-duration').val();
		app.ts.send({"action":"messages.displayMessage","message":data,"duration":duration});
	}
	//function client
	
	module.init = function(msg){
	
		//this is called once we have the HTML template from the server.
		
		if(!$('.dashboard').length)return;//run this if we are a dashboard
		
		$('#system-server-restart').click(serverRestartButton);
		$('#system-server-shutdown').click(serverShutdownButton);
		$('#system-JSON-send').click(JSONSendButton);
		$('#slides-go-live').click(goLiveButton);
		$('#slides-logo').click(displayLogoButton);
		$('#slides-message').click(displayMessageButton);
		
		//app.slide.registerDisplay($('#slides-preview-window'),'preview');
		//app.slide.registerDisplay($('#slides-live-window'),'live');
		
		app.system.addMode('dashboard');
		app.ts.send({"action":"system.getClientInfo"});//update the client info
		app.ts.send({"action":"slides.getSlideTree"});//draw slide tree
		app.ts.send({"action":"twitter.getTweets"});//draw tweet list
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
		}
	
	module.displaySlideTree = function(msg) {
	
	
		$('#slides-list').on('select_node.jstree',module.selectSlideTree).jstree({"core":{"themes":{"dots":false},"data":msg.data,"multiple":false}});
	}
	
	module.selectSlideTree = function(e, data) {
		//if its a leaf send it
		if(data.node.children.length == 0)app.ts.send({"action":"slides.slideSelected","slideId":data.node.id});
	}
	
	module.displayTweetList = function(msg) {
		$.each(msg.tweets, function( key, tweet ) { //this is ugly, but w/ever
			var line = '<div class="row">';
			line+='<div class="col-md-2"><p class="username"><b>{0}</b><br />{1}</p></div>'.format(tweet.user.screen_name, tweet.shortDate);
			line+='<div class="col-md-8"><p>{0}</p></div>'.format(tweet.HTML);
			line+='<div class="col-md-2"><button type="button" data-tweetid="{0}" class="btn btn-default tweetDisplayButton">Display</button></div></div>'.format(tweet.id);
			$('#twitter-list').append(line);
			});
	
	
	}
	return module;
}());