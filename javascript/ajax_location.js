<!--// java script 
var locoptions;
var theDiv;

function location(t, q, div){
	theDiv = div;
	//url = "./php/ajax_location.php?t=2&q=1&d=selDesCity";

	url = "./php/ajax_location.php?t=" + t + "&q=" + q + "&d=" + div;
	
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
		var selectControl = document.getElementById(theDiv);
		if(theDiv == "CycleID")
			firstOption = "Current cycle id";
		else
			firstOption = "Unknown";
			
		selectControl.options.length=0;	
		selectControl.options[0] = new Option(firstOption);
		selectControl.options[0].value="0";
		// remove existing
		//for(loopIndex =0; loopIndex < locoptions.length; loopIndex++){			
		//	selectControl.remove(loopIndex+1);
		//}
		
		for(loopIndex =0; loopIndex < locoptions.length; loopIndex++){			
				fullLocation = locoptions[loopIndex].firstChild.data;
				arrLocation = fullLocation.split("|");
				textLocation = arrLocation[0];
				valueLocation = arrLocation[1];
				selectControl.options[loopIndex+1] = new Option(textLocation);
				selectControl.options[loopIndex+1].value=valueLocation;
//				alert(selectControl.options[loopIndex].text);
//				alert(selectControl.options[loopIndex].value);
		}
}
