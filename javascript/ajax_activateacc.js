// JavaScript Document
var xmlHttp;
var theDiv;
function activateAccount(url, div){
	theDiv = div;
	if (url.length==0){ 
		document.getElementById(theDiv).innerHTML=""
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	xmlHttp.onreadystatechange=stateChangedAccount 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function stateChangedAccount(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		//alert(theDiv+" was successfully requested.......");
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