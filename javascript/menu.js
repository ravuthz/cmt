// JavaScript Document
var xmlHttp;
var theDiv;
var theID;
function process(pag, id){
	theDiv = "menutodo";
	theID = id;
	url = "./php/menu.php?contentid=" + id + "&pag=" + pag + "&t=" + new Date().getTime();
	
		
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null){
		alert ("Browser does not support HTTP Request")
		return
	} 

	xmlHttp.onreadystatechange=getIT 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
// End function

function getIT(){ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 

		document.getElementById(theDiv).innerHTML=xmlHttp.responseText				
		document.getElementById("lcustomerservice").className = "blue";
		document.getElementById("lsales").className = "blue";
		document.getElementById("lcashier").className = "blue";
		document.getElementById("lfinance").className = "blue";
		document.getElementById("ltechnical").className = "blue";
		document.getElementById("ladministrator").className = "blue";
		document.getElementById("lreport").className = "blue";
		document.getElementById("lhelp").className = "blue";	
		document.getElementById("Traffic").className = "blue";
		document.getElementById("Announcement").className = "blue";	
		document.getElementById("menutodo").className = "mainwhite";

		if(theID == 1){
				document.getElementById("lcustomerservice").className = "white";			
		}else if(theID == 2){
				document.getElementById("lsales").className = "white";
		}else if(theID == 3){
				document.getElementById("lcashier").className = "white";
		}else if(theID == 4){
				document.getElementById("lfinance").className = "white";
		}else if(theID == 5){
				document.getElementById("ltechnical").className = "white";
		}else if(theID == 6){
				document.getElementById("ladministrator").className = "white";
		}else if(theID == 7){
				document.getElementById("lreport").className = "white";
		}else if(theID == 8){
				document.getElementById("lhelp").className = "white";
		}else if(theID == 7){
				document.getElementById("Traffic").className = "white";
		}else if(theID == 8){
				document.getElementById("Announcement").className = "white";
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