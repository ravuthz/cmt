// JavaScript Document

var _xmlHttp,_elementName;

// creates an XMLHttpRequest instance
function createXmlHttpRequestObject()
{
	// will store the reference to the XMLHttpRequest object
	var xmlHttp;
	// this should work for all browsers except IE6 and older
	try
	{
		// try to create XMLHttpRequest object
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		// assume IE6 or older
		var XmlHttpVersions = new Array('MSXML2.XMLHTTP.6.0',
										'MSXML2.XMLHTTP.5.0',
										'MSXML2.XMLHTTP.4.0',
										'MSXML2.XMLHTTP.3.0',
										'MSXML2.XMLHTTP',
										'Microsoft.XMLHTTP');
		// try every prog id until one works
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
		{
			try
			{
				// try to create XMLHttpRequest object
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}
			catch (e) {} // ignore potential error
		}
	}
	// return the created object or display an error message
	if (!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}
 

//process
function process(url, elementName)
{
	_xmlHttp = createXmlHttpRequestObject();
	_elementName=elementName;
	// only continue if xmlHttp isn't void
	if (_xmlHttp)
	{
		// try to connect to the server
		try
		{
			url +='&id='+ Date();
			// initiate reading the a file from the server
			_xmlHttp.open("GET", url, true);
			_xmlHttp.onreadystatechange = handleRequestStateChange;
			_xmlHttp.send(null);
		}
		// display the error in case of failure
		catch (e)
		{
			alert("Can't connect to server:\n" + e.toString());
		}	
	}
}

// function executed when the state of the request changes
function handleRequestStateChange()
{	
	
	// obtain a reference to the <div> element on the page
	myDiv = document.getElementById(_elementName);
		
	if (_xmlHttp.readyState == 1)
	{
		myDiv.innerHTML = LoadingContent();						
	}
	else if (_xmlHttp.readyState == 2)
	{
		//myDiv.innerHTML += "Request status: 2 (loaded) <br/>";
	}
	else if (_xmlHttp.readyState == 3)
	{
		//myDiv.innerHTML += "Request status: 3 (interactive) <br/>";
	}
	// when readyState is 4, we also read the server response
	// continue if the process is completed
	if (_xmlHttp.readyState == 4)
	{			
		// continue only if HTTP status is "OK"
		if (_xmlHttp.status == 200)
		{							
			// read the message from the server
			response = _xmlHttp.responseText;								
			myDiv.innerHTML = response;									
		}
	}
}

function LoadingContent()
{
	content = '<div id="wait">'+
					'<table width=100% border=0 cellspacing=0 cellpadding=100>'+
						'<tr>'+
							'<td align=center width="100%">'+
								'<p><img src="../images/loading.gif" border=0><br><b>Please wait a moment...</b></p>'+
							'</td>'+
						'</tr>'+
					'</table>'+
				'</div>';
	return content;
}