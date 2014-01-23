function messageClientClass(container,usewindow) {
	this.container = container;
	if(usewindow == true)this.usewindow = true;
	else this.usewindow = false;
  
  
	this.processmessage = function(msgdata) {
		log('Displying some a message');
		
		if(this.usewindow)var windowdim = document.viewport.getDimensions();
		else var windowdim = container.getDimensions();
		
		if($('messageDisplayDiv'))$('messageDisplayDiv').remove();
		
		//this.messagecontainer = new Element('div', {'id':'messageDisplayDiv', 'class': 'messageContainer', 'style':'display:none;height:'+windowdim.height*3/5+'px;width:'+windowdim.width+'px'});
		this.messagecontainer = new Element('div', {'id':'messageDisplayDiv', 'class': 'messageContainer', 'style':'display:none'});
		this.messagediv = new Element('div',{'id':'messageDisplayInner'}).update(msgdata.message);
		this.container.insert({'top':this.messagecontainer});
		this.messagecontainer.insert(this.messagediv);
		var size = windowdim.height/10;
		this.messagediv.setStyle({"font-size":size+'px'});


		var dim = this.messagecontainer.getDimensions()
		this.messagecontainer.setStyle({'height':dim.height+'px'});
		
		

		Effect.SlideDown(this.messagecontainer,{'duration':1.5});
		Effect.SlideUp.delay(8,this.messagecontainer,{'duration':1.5});
		


		
		
		/*
		this.settings = {"background":"#006600","resizeFunction":"resizeContent"} //default settings
		
		this.container.setStyle({fontSize:'100%'});
		
		this.container.childElements().each(function(s){s.remove()});
		this.margindiv = new Element('div', { 'class': 'margin'});
		this.contentdiv = new Element('div', { 'class': 'content'});
		
		this.container.insert(this.margindiv);
		this.margindiv.insert(this.contentdiv);
		
		var extraReg = new RegExp("<!--(.*)-->");
		var extraJSON = extraReg.exec(msgdata.html);
		if(extraJSON){
			var extraData = extraJSON[1].evalJSON();
		
			if(extraData.background)this.settings.background = extraData.background;
			if(extraData.resizeFunction)this.settings.resizeFunction = extraData.resizeFunction;			
		}	
		
		this.contentdiv.innerHTML = msgdata.html;
		
		this.margindiv.setStyle({"background":this.settings.background});
		
		
		if(!this.resizelock){
			this.resizelock = true;
			this[this.settings.resizeFunction]();
			this.resizelock = false;
			}
	*/
	};

  return this;
}
