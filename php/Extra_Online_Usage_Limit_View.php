
<script language="javascript" type="text/javascript" src="ajax_Billing.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>
<script language="javascript" type="text/javascript">

	function doReq(w){
	
				url = "Load_Credit_Limit_Usage_Online.php?&w="+w+"&mt=" + new Date().getTime();
				DoRequest(url,"d-load");		
				//setTimeout("doReq('"+w+"')", 300000);
				
	}
	
	
	function doIt(accID,Status,Amount,CycleID,Existing,Description,Inv,curbook){
			
			if(confirm("Are you sure to do this action?"))	
			{
					url = "ins_switch_comment_usage_credit_limit.php?accID="+accID+"&Status="+Status+"&Amount="+Amount+"&CycleID="+CycleID+"&Existing="+Existing+"&curbook="+curbook+"&dp="+new Date().getDate()+"&Description="+Description+"&mt=" + new Date().getTime();
					DoAction(url);
					//document.getElementById(inID).innerHTML=comm;
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

	$Print=date('Y-m-d H:i:s',strtotime("previous hours"));
	//
	
	$service = $_GET['service'];
	$w = $_GET['w'];
	if(intval($service)==2)
	{
		$sname='Telephone';
	}
	else if(intval($service)==5)
	{
		$sname='ISP';
	}
	else
	{
		$sname='Lease Line';
	}
	print "
	
	<html><head><title>ONLINE USAGE </title></head>
	<body onLoad=\"doReq(".$w.");\" >
	<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<tr>
								<td align=left>ONLINE USAGE<br>
								Printed on: <b>".$Print."
							</td></tr>
							<tr>
							<tr>
								<td align=left>
								Service Type: <b>".$sname."</b></b><br><br>&nbsp;
							</td></tr>
							<tr>
								<td><div id=\"d-load\">
									</div>
								</td>
							</tr></table></body></html>";

	
	
?>