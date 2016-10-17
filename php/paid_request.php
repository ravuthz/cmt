
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript">

	function doReq(ci,wh){
	
				url = "paid_load.php?&cid="+ci+"&w="+wh+"&mt=" + new Date().getTime();
				DoRequest(url,"d-load");		
				setTimeout("doReq('"+ci+"','"+wh+"')", 300000);
	}
	
	function doReq_af(ci,wh){	
				url1 = "lately_paid_af_delay.php?&cid="+ci+"&w="+wh+"&mt=1" + new Date().getTime();
				DoRequest(url1,"d-load-af");	
				setTimeout("doReq('"+ci+"','"+wh+"')", 300000);
	}
	
	function doIt(invID,ph,Inv,inID){
		var m= new Date().getMonth()+1;
		var dd= new Date().getFullYear( ) + "/" + m + "/" + new Date().getDate() + " " + new Date().getHours()+ ":" + new Date().getMinutes()+ ":" + new Date().getSeconds() ;
		if(comm = prompt("Are you sure that you would like to do this "+ph+" ?\n Please enter your comment.","Rec. on " + dd)){
			url = "Ins_switch_comment_re.php?invoiceID="+invID+"&phone="+ph+"&remark="+comm+"&mt=" + new Date().getTime();
			di="";
			DoAction(url);
			document.getElementById(inID).innerHTML=comm;
			document.getElementById(Inv).disabled=true;
		}
	}
	
	function doAf(invID,ph,Inv,inID){
		var m= new Date().getMonth()+1;
		var dd= new Date().getFullYear( ) + "/" + m + "/" + new Date().getDate() + " " + new Date().getHours()+ ":" + new Date().getMinutes()+ ":" + new Date().getSeconds() ;
		if(comm = prompt("Are you sure that you would like to do this "+ph+" ?\n Please enter your comment.","Rec. on " + dd)){
			url = "Ins_switch_comment_af.php?invoiceID="+invID+"&phone="+ph+"&remark="+comm+"&mt=" + new Date().getTime();
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
	
	$Print=date('Y-m-d H:i:s',strtotime("previous hours"));
	//
	print "
	
	<html><head><title>List of Account Request</title></head>
	<body onLoad=\"doReq('".$cid."','".$where."');\" >
	<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>LIST OF ACCOUNT REQUEST<br>
								Expired Date: <b>".$where."</b><br>
								Cycle date: <b>".$cid."</b><br>
								Printed on: <b>".$Print."</b><br><br>&nbsp;
							</td></tr>
							<tr>
								<td><div id=\"d-load\">
									</div>
								</td>
							</tr></table></body></html>";

	
	
?>