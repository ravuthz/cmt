// JavaScript Document
var xmlHttp;
var theDiv;
var theDivacid;
function checkUserName(url, div, acid){
	theDiv = div;
 	theDivacid = acid;
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 	
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function stateChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		// Username exists		

		if(xmlHttp.responseText != 0){			
			document.getElementById(theDiv).innerHTML="";
			document.getElementById(theDiv).style.display = "inline";
			document.getElementById(theDivacid).value=xmlHttp.responseText;
		}else{
			document.getElementById(theDiv).innerHTML="Account Already Exists or Invalid";			
			document.getElementById(theDiv).style.display = "inline";
		}
	} 
} 


function checkUserNameNot(url, div, acid){
	theDiv = div;
 	theDivacid = acid;
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 	
	xmlHttp.onreadystatechange=stateChangedNot 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function stateChangedNot(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		// Username exists		

		if(xmlHttp.responseText != 0){			
			document.getElementById(theDiv).innerHTML="";
			document.getElementById(theDiv).style.display = "inline";
			document.getElementById(theDivacid).value=xmlHttp.responseText;
		}else{
			document.getElementById(theDiv).innerHTML="Account Doesn't Exists or Invalid";			
			document.getElementById(theDiv).style.display = "inline";
		}
		
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