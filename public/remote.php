<!DOCTYPE html>
<html>
<head>
<title><?php echo $_GET['id'];?></title>
<script type="text/javascript" charset="utf-8" src="js/config.js.php"></script>
<script type="text/javascript" charset="utf-8">
var DEBUG = true;
</script>
<script type="text/javascript" charset="utf-8" src="js/prototype/prototype.js" ></script>
<script type="text/javascript" src="js/prototype/scriptaculous.js"></script>
<script type="text/javascript" src="js/websockets.js"></script>
</head>
<body>

<button id="prev" style="width:100%;height:200px;font-size:10em">Prev</button>
<button id="next" style="width:100%;height:200px;font-size:10em">Next</button>
<script type="text/javascript">
document.observe("dom:loaded", remoteSetup);


function remoteSetup() {
	
	
	log("opening connection");
	transport = new websocketTransportClass(host,"remote",["control"],null);
	
	$('next').observe('click',next);
	$('prev').observe('click',prev);

	}
	
function next() {
data = {"msgfor": "slidehandler", "data":
   {"call":"displaynextslide","data":
      {"slidetoload":"msg"}}};
transport.send(data);
	
};

function prev() {
data = {"msgfor": "slidehandler", "data":
   {"call":"displayprevslide","data":
      {"slidetoload":"msg"}}};
transport.send(data);
return false;
	
};

function log(msg){
	if(DEBUG) console.log(msg);
	};

</script>
</body>
</html>
