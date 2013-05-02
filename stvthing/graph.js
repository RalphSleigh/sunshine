function STVGraphClass(domID,config, graphData) {

this.id = domID;
this.d = graphData;
this.c = config;
this.p = Raphael("container", this.c.areaWidth, this.c.areaHeight);

this.init = function() {

	this.title = this.p.text(this.c.areaWidth/2, this.c.margin/2, this.d.title);
	this.title.attr({"font-size":25,"font-weight":"bold"});
	
	this.floor = this.c.areaHeight - this.c.margin;
	this.drawAxis();
	this.drawQuota();
	this.drawBars();
	this.stage = 0;
	
	

	//rect.attr({fill: "green"});
	//circle.attr({fill: "blue"});
	}

this.drawAxis = function() {
	
	var axisPath = "M {0} {0} {0} {1} {2} {1} {2}".f(this.c.margin,this.c.areaHeight - this.c.margin, this.c.areaWidth - this.c.margin);
	this.axis = this.p.path(axisPath);
	}

this.getHeightFromVotes = function(votes) {
	return votes*(this.c.areaHeight - (2 * this.c.margin))/this.d.maxVotes;
	}

this.getBarX = function(candidate) {
	var gap = ((this.c.areaWidth - (2 * this.c.margin)) - (this.d.candidates.length * this.c.barWidth))/(this.d.candidates.length+1);
	return this.c.margin + ((candidate)*this.c.barWidth) + ((candidate+1) * gap);
	}	
	
this.drawQuota = function() {
	var quotaPath = "M {0} {1} {2} {1}".f(this.c.margin,this.floor - this.getHeightFromVotes(this.d.quota), this.c.areaWidth - this.c.margin);
	this.quotaLine = this.p.path(quotaPath);
	this.quotaLine.attr({"stroke-dasharray":"- ","stroke":"#777777"});
	
	this.quotaText = this.p.text(this.c.margin - 15, this.floor - this.getHeightFromVotes(this.d.quota), this.d.quota);
	this.quotaText.attr({"font-size":12,"font-weight":"bold"});
	
	}
	
this.drawBars = function(){
	
	this.bars =  [];
	this.barTitles =  [];
	var graph=this;
	$.each(this.d.candidates, function(i){
		graph.bars[i] = graph.p.rect(graph.getBarX(i),graph.floor - graph.getHeightFromVotes(this.initial),graph.c.barWidth,graph.getHeightFromVotes(this.initial));
		graph.bars[i].attr({fill:graph.c.barColours[i].p});
		
		graph.barTitles[i] = graph.p.text(graph.getBarX(i) + graph.c.barWidth/2, graph.floor + 15, this.name);
		graph.barTitles[i].attr({"font-size":12,"font-weight":"bold"});
		});
	}

this.next = function(){
	switch(this.d.stage[this.stage].action) {
		case "showNext":
			this.animShowNext();
			break;
		}
	this.stage++;
	}
	
this.animShowNext = function() {
	var graph=this;
	var bottom = this.floor;
	$.each(this.d.stage[this.stage].to, function(i){
		
		//graph.nextPrefBar[i] =  graph.p.rect(graph.getBarX(i),
		
		//graph.bars[i] = graph.p.rect(graph.getBarX(i),graph.floor - graph.getHeightFromVotes(this.initial),graph.c.barWidth,graph.getHeightFromVotes(this.initial));
		//graph.bars[i].attr({fill:graph.c.barColours[i].p});
		alert(this);
		//graph.barTitles[i] = graph.p.text(graph.getBarX(i) + graph.c.barWidth/2, graph.floor + 15, this.name);
		//graph.barTitles[i].attr({"font-size":12,"font-weight":"bold"});
		});
	}
	
	
return this;
}
	
/*
$('#next').click(rect, function(e) {
var anim = Raphael.animation({width: 10, height: 20}, 2e3);

e.data.animate(anim);
});
*/



graphData = {
"title":"Test Election Results",
"maxVotes":80,
"quota":30,
"candidates":[
	{"name":"Alice","initial":60},
	{"name":"Bob","initial":50},
	{"name":"Carol","initial":28},
	{"name":"Dave","initial":24},
	{"name":"Eve","initial":10}],
"stage":[{"action":"showNext",
		  "candidate":0,
		  "to":[5,0,26,15,14]}]
};

graphConfig = {
"areaWidth":700,
"areaHeight":500,
"margin":50,
"barWidth":60,
"barColours":[
	{"p":"#FF4444","s":"#FF8C8C"},
	{"p":"#4444FF","s":"#8C8CFF"},
	{"p":"#44FF44","s":"#8CFF8C"},
	{"p":"#FFFF44","s":"#FFFF8C"},
	{"p":"#44FFFF","s":"#8CFFFF"}],
"animTime":2000,
};

start = function(){
	graph =  new STVGraphClass("container", graphConfig, graphData);
	graph.init();
	
	$('#next').click($.proxy(graph.next, graph));
}

$(start);

String.prototype.format = String.prototype.f = function() {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};