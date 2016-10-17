<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript">

	function doReq(ci,where){
	
				url = "lately_paid_delay.php?&cid="+ci+"&w="+where+"&mt=" + new Date().getTime();
				DoRequest(url,"d-load-delay");	
				setTimeout("doReq('"+ci+"','"+where+"')", 300000);
				
	}
	
	function doIt(invID,ph,Inv,inID){
		var m= new Date().getMonth()+1;
		var dd= new Date().getDate() + "-" + m + "-" + new Date().getYear() + " " + new Date().getHours()+ ":" + new Date().getMinutes();
		if(comm = prompt("Are you sure that you would like to do this "+ph+" ?\n Please enter your comment.","De. " + dd)){
			url = "Ins_switch_comment.php?invoiceID="+invID+"&phone="+ph+"&remark="+comm+"&mt=" + new Date().getTime();
			di="";
			DoAction(url);
			document.getElementById(inID).innerHTML=comm;
			document.getElementById(Inv).disabled=true;
		}
	}

</script>
<link href="../style/mystyle.css" type="text/css" rel="stylesheet" />
<style>
	td, th{
		font-family:"Courier New", Courier, monospace;
		font-size:11px;
	}
</style>
<?php

	$cid = $_GET['cid'];
	$where = $_GET['w'];
	$Print=date('Y-m-d H:i:s');
	
	print "
	
	<html><head><title>List of Account Request</title></head>
	<body onLoad=\"doReq('$cid','$where');\" >
	<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td><div id=\"d-load-delay\">
									</div>
								</td>
							</tr></table></body></html>";

	
	
?>