// send http requests
      function sendHttpRequest(url,callbackFunc,target,respXml){
         var xmlobj=null;
         try{
           xmlobj=new XMLHttpRequest();
         }
         catch(e){
           try{
             xmlobj=new ActiveXObject("Microsoft.XMLHTTP");
           }
           catch(e){
             alert('AJAX is not supported by your browser!');
             return false;
           }
         }
         xmlobj.onreadystatechange=function(){
           if(xmlobj.readyState==4){
             if(xmlobj.status==200){
               respXml?eval
(callbackFunc+'(xmlobj.responseXML,target,'+respXml+')'):eval
(callbackFunc+'(xmlobj.responseText,target,'+respXml+')');
             }
           }
         }
         // open socket connection
         xmlobj.open('GET',url,true);
         // send http header
         xmlobj.setRequestHeader('Content-Type','text/html; charset=UTF-8');
         // send http request
         xmlobj.send(null);
      }
	  