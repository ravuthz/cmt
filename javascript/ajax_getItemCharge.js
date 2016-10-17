<!--// java script 
var locoptions;
var theDiv;

function getItemCharge(div){
	theDiv = div;
	//url = "./php/ajax_location.php?t=2&q=1&d=selDesCity";

	url = "./php/ajax_getItemCharge.php";
	
	var XMLHttpRequestObject = false;
	try{
			XMLHttpRequestObject = new ActiveXObject("MSXML2.XMLHTTP");
	}catch(exception1){
			try{
					XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(exception2){
					XMLHttpRequestObject = false;
			}
	}
	if(XMLHttpRequestObject){
			XMLHttpRequestObject.open("GET", url, true);
			XMLHttpRequestObject.onreadystatechange = function(){
					if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200){
							var xmlDocument = XMLHttpRequestObject.responseXML;
							locoptions = xmlDocument.getElementsByTagName("option");							
							listOption();
					}
			}
			XMLHttpRequestObject.send(null);
	}
}

function listOption(){
		var loopIndex;
		var selectControl = theDiv;
		
		for(loopIndex =0; loopIndex < locoptions.length; loopIndex++){			
				fullLocation = locoptions[loopIndex].firstChild.data;
				arrLocation = fullLocation.split("|");
				textLocation = arrLocation[0];
				valueLocation = arrLocation[1];
				selectControl.options[loopIndex] = new Option(textLocation);
				selectControl.options[loopIndex].value=valueLocation;

		}
}
