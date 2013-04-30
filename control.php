<!DOCTYPE html>
<html>
<head>
<title><?php echo $_GET['id'];?></title>
<link rel="stylesheet" type="text/css" href="css/control.css" />
<link rel="stylesheet" type="text/css" href="css/display.css" />
<script type="text/javascript" charset="utf-8" src="js/config.js.php"></script>
<script type="text/javascript" charset="utf-8">
var DEBUG = true;
</script>
<script type="text/javascript" charset="utf-8" src="js/prototype/prototype.js" ></script>
<script type="text/javascript" src="js/prototype/scriptaculous.js"></script>
<script type="text/javascript" src="js/websockets.js"></script>
<script type="text/javascript" src="js/slideControl.js"></script>
<script type="text/javascript" charset="utf-8" src="js/slideClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/messageClient.js" ></script>
<script type="text/javascript" charset="utf-8" src="js/control.js" ></script>

</head>
<body>
<table class="layouttable">

<tr><td columnspan="3">

</td></tr>
<a href="#" id="slidelistlink">Slides</a> | <a href="#" id="slidelistlink">Messages</a>
<tr>
<td>
<div id="slidecontrol"></div>
	<div id="messagecontrol">
	<form>
		<textarea id="messageinput" >The quick brown fox jumped over the lazy dog.</textarea>
	</form>
	<button id="playmessagebutton">Play message</button>
	</div>

<div id="twittercontrol">
	<form>
		<textarea id="twitterinput" >The quick brown fox jumped over the lazy dog.</textarea>
	</form>
	<button id="playtweetbutton">Play message</button>
	</div>

</td>

<td class="windowcell">
<div>
	<div id="previewwindow" class="displaywindow"></div>
	<button id="golivebutton">Go live</button>
</div>
</td>

<td class="windowcell">
<div>
	<div id="livewindow" class="displaywindow"></div>
</div>
</td>

</tr>
</table>

</body>
</html>
