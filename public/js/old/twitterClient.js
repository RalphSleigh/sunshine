function twitterClientClass(container,usewindow) {
	this.container = container;
	if(usewindow == true)this.usewindow = true;
	else this.usewindow = false;
  
  
	this.processmessage = function(msgdata) {
		log('Displying a tweet');
		
		if(this.usewindow)var windowdim = document.viewport.getDimensions();
		else var windowdim = container.getDimensions();
		
		if($('twitterDisplayDiv'))$('twitterDisplayDiv').remove();
		
		//this.messagecontainer = new Element('div', {'id':'messageDisplayDiv', 'class': 'messageContainer', 'style':'display:none;height:'+windowdim.height*3/5+'px;width:'+windowdim.width+'px'});
		this.twittercontainer = new Element('div', {'id':'twitterDisplayDiv', 'class': 'twitterContainer', 'style':'display:none'});
		this.twitterdiv = new Element('div',{'id':'twitterDisplayInner'}).update('<img src="images/twitter.png" style="float:left" />'+msgdata.message);
		this.container.insert({'top':this.twittercontainer});
		this.twittercontainer.insert(this.twitterdiv);
		var size = windowdim.height/15;
		this.twitterdiv.setStyle({"font-size":size+'px'});


		var dim = this.twittercontainer.getDimensions()
		this.twittercontainer.setStyle({'height':dim.height+'px'});
		
		

		Effect.SlideDown(this.twittercontainer,{'duration':1.5});
		Effect.SlideUp.delay(12,this.twittercontainer,{'duration':1.5});
		


		
		
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
