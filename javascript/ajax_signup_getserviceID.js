// JavaScript Document
var xmlHttp;
var theDiv;
function getServiceID(url, div){
	theDiv = div;
 
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request");
		return
	} 	
	xmlHttp.onreadystatechange=stateChangedServiceID 
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
// End function

function stateChangedServiceID(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		// Username exists	
		var vs = xmlHttp.responseText;
		theDiv.value = vs;
	} 
} 
// End function

function GetXmlHttpObject(handler){
  var objXMLHttp=null
  // Firefox, Opera 8.0+, Safari
  if (window.XMLHttpRequest){
    objXMLHttp=new XMLHttpRequest();
    }
  // Internet Explorer
  else if (window.ActiveXObject){
    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  return objXMLHttp
}
// End function