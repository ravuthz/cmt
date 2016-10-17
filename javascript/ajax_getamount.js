// JavaScript Document
var xmlHttp;
var theDiv1;
var theDiv2;
function Amount(url, div1, div2){
	theDiv1 = div1;
	theDiv2 = div2;

	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 	
	xmlHttp.onreadystatechange=amountChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function amountChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 		
			document.getElementById(theDiv1).value = xmlHttp.responseText;
			document.getElementById(theDiv2).value = xmlHttp.responseText;
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