<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<link rel="stylesheet" type="text/css" href="css/chat.css" />
<link rel="stylesheet" type="text/css" href="css/display.css" />
<script type="text/javascript" charset="utf-8" src="js/config.js.php"></script>
<script type="text/javascript" charset="utf-8">
var chatid = "<?php echo $_SERVER['REMOTE_ADDR']; ?>";
var DEBUG = true;
</script>
<script type="text/javascript" charset="utf-8" src="js/prototype/prototype.js" ></script>
<script type="text/javascript" src="js/prototype/scriptaculous.js"></script>
<script type="text/javascript" src="js/websockets.js"></script>
<script type="text/javascript" charset="utf-8" src="js/slideClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/chatClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/messageClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/twitterClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/vchat.js" ></script>

</head>
<body>

<div id="chatcontainer"> 
<div id="chatcontent"></div>
<form>
		<button id="chatsendbutton">Send</button>
		<div id="chatinputcontainer">
		<textarea id="chatinput" resize="none" ></textarea></div>
		
	</form>


</div>

<div>
	<div id="livewindow" class="displaywindow" style="height:300px"></div>
</div>

</body>
</html>
