//constructor:
function HorizontalFlux(container_id,flux_id,flux_name) {

	this.container = $("#"+container_id);
	this.flux_id = flux_id;

	//TODO: all these properties will be in the css, we will just assign the correct classes
	$("<div style=\"width:100px;height:100px;background-color:gray;float:left;margin:4px\">"+flux_name+"</div>").appendTo(this.container);
	this.t = 0; //t = time, used to animate the bubbles
	this.pipes = new Array();
	//call the api to get the info about the flux
	var obj = this;
	//flux_api_call(function() {obj.gotFluxInfo();},"http://localhost/API/get_flux_info.php?flux_id="+flux_id,this);
	flux_api_call("http://localhost/API/get_flux_info.php?flux_id="+flux_id,function(array) {obj.gotFluxInfo(array);});
}

HorizontalFlux.prototype.gotFluxInfo = function(array) {
	for (var i=0;i<array.length;i++) {
		this.addReceiver(array[i]["flux_to_id"],array[i]["share"]);
	}
//	this.refreshWidths();
}

HorizontalFlux.prototype.addReceiver = function(name, share) {
	subContainer = $("<div style=\"width:100px;height:100px;float:left;margin:4px\" id=\""+this.flux_id+"subflux"+name+"\"></div>").appendTo(this.container);
	pipeContainer = $("<div style=\"width:100px;height:70px\"></div>").appendTo(subContainer);
	pipeBase = $("<div class=\"pipeBase\" style=\"width:100px;height:30px;background-color:gray\">"+name+"</div>").appendTo(subContainer)
	pipe = $("<div class=\"pipe\" style=\"height:70px;width: "+share+"px;background-image:url('include/horizontal_flux/bubbles.jpg');background-repeat:repeat;margin:auto\" id=\""+this.flux_id+"pipe"+name+"\"></div>").appendTo(pipeContainer);
	pipe.attr("flux_to_id",name);
	pipe.attr("flux_from_id",this.flux_id);
	pipe.attr("share",share);
	this.pipes.push(pipe);

	//TODO: only if you're the owner 
	//creating the slider:
	var obj = this;
	var selector = ".pipe[flux_to_id="+name+"][flux_from_id="+obj.flux_id+"]";
	slider = $("<div class=\"pipeslider\" style=\"width:90px;height:5px;\"></div>").slider({value: share},{slide: function(event,ui) { $(selector).css("width",ui.value); } });
	slider.slider({change: function(event,ui) {obj.changeSubflux(name,ui.value);}});
	pipeBase.append(slider);
	
}

HorizontalFlux.prototype.step = function() {
	$(".pipe").css("background-position", "0px "+this.t +"px");
	this.t++;
}

HorizontalFlux.prototype.changeSubflux = function(subflux_id,new_share) {
	flux_api_call("http://localhost/API/change_flux.php?flux_from_id="+this.flux_id+"&flux_to_id="+subflux_id+"&new_share="+new_share,this.changedFluxCB,this);
}

HorizontalFlux.prototype.changedFluxCB = function(jsonArray) {
	//TODO if result !="SUCCESS" show an error to the user
}

