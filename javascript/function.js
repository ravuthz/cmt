// JavaScript Document
// Global variabl
// Check Phone Number
var digits = "0123456789";
//Non digit phone number but allowed.
var phoneNumberDelimiters = "()- /";
var validWorldPhoneChars = phoneNumberDelimiters + "+";
var minDigitsInIPhoneNumber = 9;
// Check if valid telephone format
// Check for integer
function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    return true;
}
function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    
    for (i = 0; i < s.length; i++)
    {      
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}
function checkInternationalPhone(strPhone){
s=stripCharsInBag(strPhone,validWorldPhoneChars);
return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

// Check if valid email
function isValidMail(email){
	var Temp = email;
	if ((Temp==null)||(Temp=="")){				
		return false;
	}
	else{
		var AtSym    = Temp.indexOf('@');
		var Period   = Temp.lastIndexOf('.');
		var Space    = Temp.indexOf(' ');
		var Length   = Temp.length - 1 ;  // Array is from 0 to length-1
			if ((AtSym < 1) ||                     // '@' cannot be in first position
	    		(Period <= AtSym+1) ||             // Must be atleast one valid char btwn '@' and '.'
		    	(Period == Length ) ||             // Must be atleast one valid char after '.'
			    (Space  != -1))                    // No empty spaces permitted
		   {  
				 return false;

	   		}
		return true;
		}		
}

function isNumber(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        var c = s.charAt(i);
        if ((((c < "0") || (c > "9")) && (c != ".")) && (c != "-")) return false;
    }
    return true;
}

function SwitchMenu(obj, img){  
	if(document.getElementById){
		var imgSrc = document.getElementById(img);
		var el = document.getElementById(obj);
		var ar = document.getElementById("cont").getElementsByTagName("DIV");
			if(el.style.display == "none"){
				for (var i=0; i<ar.length; i++){
					ar[i].style.display = "none";
				}
				imgSrc.src = "images/leaf1.gif";			
				el.style.display = "block";
			}else{
				el.style.display = "none";
				imgSrc.src = "images/plus1.gif";
			}
		}
	}