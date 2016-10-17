// JavaScript Document
var xmlHttp;
var theDiv;
var aID;
function checkUserNameAcc(url, div, AccID){
	theDiv = div;
 	aID = AccID;
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
			document.getElementById(theDiv).innerHTML=" Account name already exists";
			document.getElementById(theDiv).style.display = "inline";
			document.getElementById("Recon_"+aID).disabled = true;
		}else{
			//alert(xmlHttp.responseText);	
			document.getElementById(theDiv).innerHTML="";			
			document.getElementById("Recon_"+aID).disabled = false;
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