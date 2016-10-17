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
					document.getElementById("d-other").innerHTML = loading;																								
																				
					// Invoice summary ===================					
					//url2 = "../php/ajax_revenue4.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					
					url2 = "../php/ajax_revenue6.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					url3 = "../php/ajax_DemandPayment.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					url4 = "../php/ajax_revenue7.php?st="+st+"&et="+et+"&service="+service+"&mt="+ new Date().getTime();
					
					setTimeout('getTranDetail(url2, "d-deposit")',1000);
					setTimeout('getTranDetail(url3, "d-demand")',10000);
					setTimeout('getTranDetail(url4, "d-other")',3000);
					

					
					
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
				// Get invoice issue
				$sql1 = "SELECT COUNT(i.CustID) 'Customer', COUNT(i.InvoiceID) 'Invoice', SUM(i.UnpaidAmount) 'Unpaid',
											SUM(i.NetAmount) 'NetValue', SUM(i.VATAmount) 'VAT', SUM(i.InvoiceAmount) 'Amount'
								FROM tblCustomerInvoice i, tblCustProduct a, tblTarPackage t
								WHERE i.AccID = a.AccID
									and a.PackageID = t.PackageID
									and i.InvoiceType = 3
									and i.InvoiceAmount != 0
									and t.ServiceID= ".$service."
									and convert(varchar, i.IssueDate, 112) between '".FormatDate($st, 4)."' and '".FormatDate($et, 4)."'";

				if($que1 = $mydb->sql_query($sql1)){
					if($result1 = $mydb->sql_fetchrow($que1)){
						$tCust = $result1['Customer'];
						$tInvoice = $result1['Invoice'];
						$tUnpaid = $result1['Unpaid'];
						$tNetValue = $result1['NetValue'];
						$tVAT = $result1['VAT'];
						$tAmount = $result1['Amount'];
					}
				}
				$mydb->sql_freeresult($que1);
				
				#=====================================[Fee Payment]
				$sql = "SELECT SUM(PaymentAmount) as 'FeePayment'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and p.TransactionModeID = 1
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);

				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$FeePayment = $result['FeePayment'];
					}
				}

				$mydb->sql_freeresult($que);
				
				#=====================================[Add Deposit]
				$sql = "SELECT SUM(PaymentAmount) as 'AddDeposit'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and TransactionModeID in(4, 5, 6)
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$AddDeposit = $result['AddDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Refund Deposit]
				$sql = "SELECT SUM(PaymentAmount) as 'RefundDeposit'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and TransactionModeID in(7, 8, 9)
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$RefundDeposit = $result['RefundDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Transfer Deposit]
				$sql = "SELECT SUM(PaymentAmount) as 'TransferDeposit'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and TransactionModeID in(11, 12, 13)
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$TransferDeposit = $result['TransferDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Add balance]
				$sql = "SELECT SUM(PaymentAmount) as 'AddBalance'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and TransactionModeID =3
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$AddBalance = $result['AddBalance'];
					}
				}
				$mydb->sql_freeresult($que);
				
				
				
				#=====================================[tranfer balance]
				$sql = "SELECT SUM(PaymentAmount) as 'TransferBalance'
								FROM tblCashPayment p, tblCustProduct a(nolock), tblTarPackage t(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = t.PackageID
									and TransactionModeID = 10
									and t.ServiceID= ".$service."
									and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$TransferBalance = $result['TransferBalance'];
					}
				}
				$mydb->sql_freeresult($que);
				
				
				$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle">
									<b>TOTAL DAILY OTHER COLLECTION REPORT</b><br>
									Service: '.$sname.'<br> 
									From: '.formatDate($st, 6).' To '.formatDate($et, 6).'</b><br>
								</td>
								<td align="right" class="formtitle">[<a href="../export/revenue_period.php?st='.$st.'&et='.$et.'&service='.$service.'&type=csv">Download</a>]<BR><BR>
									Printed on: '.date("Y-m-d H:i:s").'
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
									&nbsp;						
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-demand"></div>						
								</td>
							</tr>
							<tr>
								<td colspan="2">
									&nbsp;						
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