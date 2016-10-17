// JavaScript Document
var xmlHttp;
var theDiv;
function checkUserNameAcc(url, div){
	theDiv = div;
 
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request");
		return
	} 	
	xmlHttp.onreadystatechange=stateChangedUsername 
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
// End function

function stateChangedUsername(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		// Username exists		

		if(xmlHttp.responseText == 1){			
			document.getElementById("btnSubmit").disabled = true;
			document.getElementById(theDiv).innerHTML=" Account name already exists";
			document.getElementById(theDiv).style.display = "inline";
		}else{
			//alert(xmlHttp.responseText);	
			document.getElementById("btnSubmit").disabled = false;	
			document.getElementById(theDiv).innerHTML="";			
			
		}
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