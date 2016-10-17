var request = null;

/* Wrapper function for constructing a Request object.
Parameters:
reqType: The HTTP request type such as GET or POST.
url: The URL of the server program.
asynch: Whether to send the request asynchronously or not.
respHandle: The name of the function that will handle the response.
Any fifth parameters represented as arguments[4] are the data a
POST request is designed to send. */
function httpRequest(reqType,url,asynch,respHandle){
	//Mozilla-based browsers
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
		//if the reqType parameter is POST, then the
		//5th argument to the function is the POSTed data
		if(reqType.toLowerCase() != "post") {
			initReq(reqType, url,asynch,respHandle);
		} else {
		//the POSTed data
		var args = arguments[4];
			if(args != null && args.length > 0){
			initReq(reqType,url,asynch,respHandle,args);
			}
		}
	} else if (window.ActiveXObject){
		request=new ActiveXObject("Msxml2.XMLHTTP");
	if (! request){
		request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(request){
		//if the reqType parameter is POST, then the
		//4th argument to the function is the POSTed data
			if(reqType.toLowerCase() != "post") {
				initReq(reqType,url,asynch,respHandle);
			} else {
			var args = arguments[4];
				if(args != null && args.length > 0){
					initReq(reqType,url,asynch,respHandle,args);
				}
			}
	} else {
		alert("Your browser does not permit the use of all "+
		"of this application's features!");}
	} else {
		alert("Your browser does not permit the use of all "+
		"of this application's features!");
	}
}

/* Initialize a Request object that is already constructed */
function initReq(reqType,url,bool,respHandle){
	try{
	/* Specify the function that will handle the HTTP response */
	request.onreadystatechange=respHandle;
	request.open(reqType,url,bool);
	//if the reqType parameter is POST, then the
	//5th argument to the function is the POSTed data
		if(reqType.toLowerCase() == "post") {
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(arguments[4]);
		} else {
			request.send(null);
		}
	
	} catch (errv) {
		alert(
		"The application cannot contact "+
		"the server at the moment. "+
		"Please try again in a few seconds.\n"+
		"Error detail: "+errv.message);
	}
}

