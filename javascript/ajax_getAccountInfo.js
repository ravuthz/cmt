// JavaScript Document
var xmlHttp;
var theDiv;
function getAcccInfo(url, div){
	theDiv = div;


	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 	
	xmlHttp.onreadystatechange=AccInfo 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function AccInfo(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 		
			document.getElementById(theDiv).innerHTML = xmlHttp.responseText;
	} 
} 
// End function

function GetXmlHttpObject(handler){
  var objXMLHttp=null
  // Firefox, Opera 8.0+, Safari
  if (window.XMLHttpRequest){
    objXMLHttp=new XMLHttpRequest()
    }
  // Internet Explorer
  else if (window.ActiveXObject){
    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
  return objXMLHttp
}
// End function