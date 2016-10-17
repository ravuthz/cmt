<html>
	<head>
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/loading.js"></script>
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>
		
		<script language="javascript" type="text/javascript">
			function showReport(st, et, service){
									
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//		document.getElementById("d-invoice").innerHTML = loading;	
					document.getElementById("d-deposit").innerHTML = loading;																								
																				
					// Invoice summary ===================					
					//url2 = "../php/ajax_revenue4.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					
					url2 = "../php/ajax_audit.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					
					getTranDetail(url2, "d-deposit");
					
					

					
					
				//	setTimeout('getTranDetail(url1, "d-invoice")', 10);					
					//setTimeout('showReport1("st", "et")', 1000);
													
			}
			
		</script>	
	</head>
	<body onLoad="showReport('<?php print $st; ?>', '<?php print $et; ?>', '<?php print $service; ?>');">
			<?php

				require_once("../common/agent.php");
				require_once("../common/functions.php");	
				$st = $_GET['st'];	
				$et = $_GET['et'];
				$service = $_GET['service'];
				$sname = $_GET['sname'];
				
				
				$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle">
									<b>Audit REPORT</b><br>
									User Name: '.$sname.'<br> 
									From: '.formatDate($st, 6).' To '.formatDate($et, 6).'</b><br>
								</td>
								<td align="right" class="formtitle">
									<br><br>Printed on: '.date("Y-m-d H:i:s").'
								</td>							
							</tr>
							<tr>
								<td align="left" width="50%">
																				
								</td>
								<td align="right" width="50%">
									
								</td>
							</tr> 
							<tr>
								<td colspan="2">
									<div id="d-revenuedetail"></div>						
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-deposit"></div>						
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-demand"></div>						
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-other"></div>						
								</td>
							</tr>
							

						</table>';
					
				print $retOut;	
				
				$mydb->sql_close();
			?>
		
	</body>
</html>