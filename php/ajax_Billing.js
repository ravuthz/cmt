// JavaScript Document
var xmlHttp;
var theDiv;
var acID;
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

function DoAction(url){


	if (url.length==0){ 
		//document.getElementById(theDiv).innerHTML=""
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	xmlHttp.onreadystatechange=DoChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

//alert(url);
}

function Dopco(url,retDiv,ac){
	theDiv=retDiv;
	acID=ac;
	if (url.length==0){ 
		//document.getElementById(theDiv).innerHTML=""
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	xmlHttp.onreadystatechange=Dopc 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

//alert(url);
}
// End function

function DoChanged(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		//do nothing

	} 
} 


function Dopc(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById(theDiv).innerHTML=xmlHttp.responseText;
		document.getElementById(theDiv).style.display="inline";
		document.getElementById(acID).value="Close";
		//do nothing

	} 
} 


function DoRequest(url, div){
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
	
	xmlHttp.onreadystatechange=stateRequest 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function stateRequest(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById(theDiv).innerHTML=xmlHttp.responseText
	} 
} 


// End function