//GET_FLUXES_OWNED_BY
/// START DEPRECATED CODE::::::::::::::::::::::::::

var get_fluxes_owned_by_callback = function(array) {alert("get_fluxes_owned_by_callback");};
var bObj;

function get_fluxes_owned_by(callback,user_id) {

	//remember the callback globally:
	get_fluxes_owned_by_callback = callback;

	address = 'http://localhost/API/get_fluxes_owned_by.php?user_id=1&callback=get_fluxes_owned_by_CB';

	// Create a new request object
	bObj = new JSONscriptRequest(address); 

	//TODO: this two functions must be condensed in a single one:
	bObj.buildScriptTag(); 	bObj.addScriptTag();

}

function get_fluxes_owned_by_CB(json_array) {
	bObj.removeScriptTag();
	get_fluxes_owned_by_callback(json_array);
}
///END DEPRECATED CODE:::::::::::::::::::::::::::::

//GENERAL API WRAPPER

//globals:
var global_id = 0;
var temp_scripts = new Array();

function flux_api_call(callback_function, api_url, optional_object) {
	scriptID = "scriptID"+global_id++;
	address = api_url+"&callback=flux_api_callback(\""+scriptID+"\",%s);";
	temp_scripts[scriptID] = new Array();
	temp_scripts[scriptID]["callback"] = callback_function;
	temp_scripts[scriptID]["object"] = optional_object;

	bObj = new JSONscriptRequest(address);
	bObj.buildScriptTag(scriptID); bObj.addScriptTag();
}

function flux_api_callback(id,jsonArray) {
	//remove the temporary <script> tag that called us:
	head = document.getElementsByTagName("head").item(0)
	head.removeChild(document.getElementById(id));
	if (temp_scripts[id]["object"]==undefined) {
		//it's a global callback, just call the function
		temp_scripts[id]["callback"](jsonArray);
	} else {
		//it's an object's method:
		temp_scripts[id]["callback"].call(temp_scripts[id]["object"],jsonArray);
	}
	delete temp_scripts[id];
}

//#######################################################################################
//INCLUDING THE JSONscriptREQUEST

// JSONscriptRequest -- a simple class for making HTTP requests
// using dynamically generated script tags and JSON
//
// Author: Jason Levitt
// Date: December 7th, 2005
//
// Sample Usage:
//
// <script type="text/javascript" src="jsr_class.js"></script>
// 
// function callbackfunc(jsonData) {
//      alert('Latitude = ' + jsonData.ResultSet.Result[0].Latitude + 
//            '  Longitude = ' + jsonData.ResultSet.Result[0].Longitude);
//      aObj.removeScriptTag();
// }
//
// request = 'http://api.local.yahoo.com/MapsService/V1/geocode?appid=YahooDemo&
//            output=json&callback=callbackfunc&location=78704';
// aObj = new JSONscriptRequest(request);
// aObj.buildScriptTag();
// aObj.addScriptTag();
//
//


// Constructor -- pass a REST request URL to the constructor
//
function JSONscriptRequest(fullUrl) {
    // REST request path
    this.fullUrl = fullUrl; 
    // Keep IE from caching requests
    this.noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    // Get the DOM location to put the script tag
    this.headLoc = document.getElementsByTagName("head").item(0);
    // Generate a unique script tag id
    this.scriptId = 'JscriptId' + JSONscriptRequest.scriptCounter++;
}

// Static script ID counter
JSONscriptRequest.scriptCounter = 1;

// buildScriptTag method
//
JSONscriptRequest.prototype.buildScriptTag = function (id) {

    // Create the script tag
    this.scriptObj = document.createElement("script");
    
    // Add script object attributes
    this.scriptObj.setAttribute("type", "text/javascript");
    this.scriptObj.setAttribute("charset", "utf-8");
    this.scriptObj.setAttribute("src", this.fullUrl + this.noCacheIE);
    this.scriptObj.setAttribute("id", id);
}
 
// removeScriptTag method
// 
JSONscriptRequest.prototype.removeScriptTag = function () {
    // Destroy the script tag
    this.headLoc.removeChild(this.scriptObj);  
}

// addScriptTag method
//
JSONscriptRequest.prototype.addScriptTag = function () {
    // Create the script tag
    this.headLoc.appendChild(this.scriptObj);
}
//#######################################################################################
//End include: JSON script request
