function slideClientClass(container,usewindow) {
	this.container = container;
	if(usewindow == true)this.usewindow = true;
	else this.usewindow = false;
  
	this.settings = {"background":"#006600","resizeFunction":"resizeContent"}
	this.margindiv = new Element('div', { 'class': 'margin'});
	this.contentdiv = new Element('div', { 'class': 'content'});
		
	this.container.insert(this.margindiv);
	this.container.insert(this.contentdiv);
  
	this.processmessage = function(msgdata) {
		log('Displying some html');
		
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
		this.container.setStyle({"background":this.settings.background});
		if(this.usewindow)$$('body')[0].setStyle({"background":this.settings.background});
		
		if(!this.resizelock){
			this.resizelock = true;
			this[this.settings.resizeFunction]();
			this.resizelock = false;
			}
	};
	
	
	
	this.callResize = function(e) {
		if(!this.resizelock){
			this.resizelock = true;
			this[this.settings.resizeFunction]();
			this.resizelock = false;
			}
		}
	
	this.imageCenter = function() {
		log("Image centre called");
		var imageContainer = this.container.select('.imagecontainer');
		imageContainer[0].select('img')[0].setStyle({"width":"","height":""});
		var imageHeight = imageContainer[0].select('img')[0].getHeight();
		var imageWidth = imageContainer[0].select('img')[0].getWidth();
		
		var newWidth = 0;
		var newHeight = 0;
		
		if(this.usewindow)var windowdim = document.viewport.getDimensions()
		else var windowdim = container.getDimensions();
		
		windowdim.width -= 20;
		windowdim.height -= 20;
		
		var widthratio = imageWidth/windowdim.width;
		var heightratio = imageHeight/windowdim.height;
		
		if(widthratio < heightratio) { 
			
			newWidth = (imageWidth/heightratio);
			newHeight = (windowdim.height);
			imageContainer[0].select('img')[0].setStyle({"width":newWidth+"px","height":newHeight+"px"});
			//topMargin = (windowdim.height - newHeight - 20)/2;
			imageContainer[0].setStyle({"margin-top":"0px"});
			}
		else
			{
			
			newHeight = (imageHeight/widthratio);
			newWidth = (windowdim.width);
			imageContainer[0].select('img')[0].setStyle({"width":newWidth+"px","height":newHeight+"px"});
			topMargin = (windowdim.height - newHeight - 20)/2;
			imageContainer[0].setStyle({"margin-top":topMargin+"px"});
			/*
			imageContainer[0].select('img')[0].setStyle({"width":"80%"});
			imageHeight = imageContainer[0].getHeight();
			topMargin = (windowdim.height - imageHeight - 20)/2;
			imageContainer[0].setStyle({"margin-top":topMargin+"px"});
			*/
			}

		}
	
	this.resizeContent = function() {

		
		if($(this.contentdiv).lastElementChild)$(this.contentdiv.lastElementChild).setStyle({marginBottom:'0em'});//remove bottom margin from last item to prevent margin leaking

		if($(this.contentdiv).firstElementChild)$(this.contentdiv.firstElementChild).setStyle({marginTop:'0em'});

		if(this.usewindow)var windowdim = document.viewport.getDimensions()
		else var windowdim = container.getDimensions();
		var paddingtop = this.margindiv.getStyle('padding-top');
		var paddingbottom = this.margindiv.getStyle('padding-bottom');
		var availableheight = windowdim.height - parseInt(paddingtop) - parseInt(paddingbottom);
		var currentheight = this.contentdiv.getHeight();
		
		log("BEFORE: window is "+windowdim.height+" available "+availableheight+" current: "+currentheight);
		
		var textpercent = 1;
		var i = 0; //lets not loop to infinity
		
		while(currentheight < availableheight && textpercent > 0.2 && textpercent < 2.25 && i < 200) {
				i++;
				textpercent += 0.1;
				this.container.setStyle({fontSize:textpercent+'em'});
				currentheight = this.contentdiv.getHeight();
				log("font size is "+this.container.getStyle('font-size')+" height is now "+currentheight+" have "+availableheight);
			}

			
		while(currentheight > availableheight && textpercent > 0.2 && i < 400) {
				i++;
				textpercent -= 0.01;
				this.container.setStyle({fontSize:textpercent+'em'});
				currentheight = this.contentdiv.getHeight();
				log("font size is "+this.container.getStyle('font-size')+" height is now "+currentheight+" have "+availableheight);
			}
			


			
		while(currentheight < availableheight && textpercent > 0.2 && textpercent < 2.25 && i < 600) {
				i++;
				textpercent += 0.001;
				this.container.setStyle({fontSize:textpercent+'em'});
				currentheight = this.contentdiv.getHeight();
				log("setting height to "+textpercent+" height is now "+currentheight);
			}
			
		while(currentheight > availableheight && textpercent > 0.2 && i < 800) {
				i++;
				textpercent -= 0.0001;
				this.container.setStyle({fontSize:textpercent+'em'});
				currentheight = this.contentdiv.getHeight();
				log("setting height to "+textpercent+" height is now "+currentheight);
			}
		log("AFTER: window is "+windowdim.height+" available "+availableheight+" current: "+currentheight);
		};
	
  return this;
}
