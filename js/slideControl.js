//client side javascript for controlling slides, will display a list user can select. 

function slideControlClass(div) {

	this.container = div;
	this.currentpreview = null;
	//this.item = null;
	
	this.setup = function() {
	
		data = {msgfor:"slidehandler",data:{call:"getslidetree"}};
		
		transport.send(data);
	
		};
		
	this.processmessage = function(msgdata) {
		log('slideControl processing message');
		this[msgdata.data.call](msgdata.data.data);

	};
	/*	
	This really needs to be a proper recursive function in case of more than one level of nesting.	
	*/
	this.updateList = function(list) {
	log("updating list");
	this.container.childElements().each(function(s){s.remove()});
	list.each(function(s) {
		//alert(s);
		this.item = new Element('div', { 'class': 'slideitem','id':'slidepath-'+s.id}).update(s.txt);
		if(s.items)s.items.each(function(t){
			this.subitem = new Element('div', { 'class': 'subslideitem','id':'slidepath-'+t.id}).update(t.txt);
			this.subitem.observe('click', this.listOnClick.bind(this));
			this.item.insert(this.subitem);
			},this);
		else this.item.observe('click', this.listOnClick.bind(this));
		
		this.container.insert(this.item);	
		},this);
	
	};
	
	this.loadSlide = function(slide,on) {
		data = {"msgfor": "slidehandler", "data":
			  {"call":"displayslide","data":
              {"slidetoload":slide,"on":on,"con":this.container.id}}};
		transport.send(data);
	}
	
	this.listOnClick = function(event){
		item = event.element();
		//alert(item.id);
		
		this.currentpreview = item.id;		
		this.loadSlide(item.id,"slideClientPreview");
	};
	
	this.previewToLive = function(){
	
		this.loadSlide(this.currentpreview,"slideClientLive");
	};
		
	return this;
	}
