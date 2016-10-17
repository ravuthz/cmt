<html>
	<head>
		<title>..:: Wise Biller ::..</title>
		<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
		<script language="JavaScript" src="../javascript/loading.js"></script>
		<script language="JavaScript" src="../javascript/ajax_gettransaction.js"></script>
		
		<script language="javascript" type="text/javascript">
			function showReport(cid, ct, service){
									
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//		document.getElementById("d-invoice").innerHTML = loading;	
					document.getElementById("d-other").innerHTML = loading;																								
																				
					// Invoice summary ===================					
					url2 = "../php/ajax_revenue8.php?cid="+cid+"&ct="+ct+"&where="+service+"&mt="+ new Date().getTime();
					url3 = "../php/ajax_revenue3.php?cid="+cid+"&ct="+ct+"&where="+service+"&mt="+ new Date().getTime();
					//url1 = "../php/ajax_revenue1.php?st="+st+"&et="+et+"&mt="+ new Date().getTime();
					
					setTimeout('getTranDetail(url2, "d-deposit")',10);	
					setTimeout('getTranDetail(url3, "d-other")',1000);					
				//	setTimeout('getTranDetail(url1, "d-invoice")', 10);					
					//setTimeout('showReport1("st", "et")', 1000);
													
			}
			
		</script>	
	</head>
	<body onLoad="showReport('<?php print $cid; ?>', '<?php print $ct; ?>', '<?php print $where; ?>');">
			<?php

				require_once("../common/agent.php");
				require_once("../common/functions.php");	
				$cid = $_GET['cid'];	
				$ct = $_GET['ct'];
				$where = $_GET['where'];
				$sname = $_GET['sname'];
				// Get invoice issue
				$sql1 = "SELECT COUNT(i.CustID) 'Customer', COUNT(i.InvoiceID) 'Invoice', SUM(i.UnpaidAmount) 'Unpaid',
											SUM(i.NetAmount) 'NetValue', SUM(i.VATAmount) 'VAT', SUM(i.InvoiceAmount) 'Amount'
								FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE i.AccID = a.AccID
									AND i.BillingCycleID = inf.CycleID
									AND a.PackageID IN( ".$where.")
									AND i.invoiceamount != 0
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";

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
				$sql = "
							Declare @dt datetime
							Set @dt = '".$cid."'				
				SELECT SUM(PaymentAmount) as 'FeePayment'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), 
										tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									and p.TransactionModeID = 1
									and a.PackageID IN (".$where.")
									and convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				$sql .= " and PaymentDate between DATEADD(mm, DATEDIFF(mm,0,@dt), 0) and dateadd(ms,-1000,DATEADD(mm, DATEDIFF(m,0,@dt)+1, 0))";
		
									
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$FeePayment = $result['FeePayment'];
					}
				}

				$mydb->sql_freeresult($que);
				
				#=====================================[Add Deposit]
				$sql = "	Declare @dt datetime
							Set @dt = '".$cid."'
		
				SELECT SUM(PaymentAmount) as 'AddDeposit'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									and TransactionModeID in (4, 5, 6)
									and a.PackageID IN (".$where.")
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				$sql .= " and PaymentDate between DATEADD(mm, DATEDIFF(mm,0,@dt), 0) and dateadd(ms,-1000,DATEADD(mm, DATEDIFF(m,0,@dt)+1, 0))";
				
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$AddDeposit = $result['AddDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Refund Deposit]
				$sql = "SELECT SUM(PaymentAmount) as 'RefundDeposit'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									andTransactionModeID in(7, 8, 9)
									and a.PackageID IN (".$where.")
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$RefundDeposit = $result['RefundDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Transfer Deposit]
				$sql = "SELECT SUM(PaymentAmount) as 'TransferDeposit'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									and TransactionModeID in(11, 12, 13)
									and a.PackageID IN (".$where.")
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$TransferDeposit = $result['TransferDeposit'];
					}
				}
				$mydb->sql_freeresult($que);
				
				#=====================================[Add balance]
				$sql = "SELECT SUM(PaymentAmount) as 'AddBalance'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									and TransactionModeID =3
									and a.PackageID IN (".$where.")
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$AddBalance = $result['AddBalance'];
					}
				}
				$mydb->sql_freeresult($que);
				
				
				
				#=====================================[tranfer balance]
				$sql = "SELECT SUM(PaymentAmount) as 'TransferBalance'
								FROM tblCashPayment p(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
								WHERE p.AcctID = a.AccID
									and a.PackageID = inf.PackageID
									and TransactionModeID = 10
									and a.PackageiD IN (".$where.")
									AND convert(varchar, inf.BillEndDate, 112) = '".$cid."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$TransferBalance = $result['TransferBalance'];
					}
				}
				$mydb->sql_freeresult($que);
				
				
				$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle">
								<b>TOTAL MONTHLY OTHER REVENUE REPORT<br>
									<b>Service: '.$sname.'</b><br>
									For cycle: '.formatDate($cid, 6).'<br>
									Printed on: '.date("Y-m-d H:i:s").'</b>
								</td>
								<td align="right"><!--[<a href="../export/revenue_period.php?st='.$st.'&et='.$et.'&service='.$service.'&type=csv">Download</a>]--></td>
							</tr>
							<tr>
								<td align="left" width="50%">
									<table border="0" cellpadding="1" cellspacing="0" align="left" class="formbg">		
										<tr>
											<td align="left" class="formtitle"><b>Invoices</b></td>
										</tr>					
										<tr>
											<td> 
												<table border="1" cellpadding="3" cellspacing="0" align="left" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
													<tr>
														<td align="left">Customer number: </td>
														<td align="right">'.$tCust.'</td>
													</tr>
													<tr>
														<td align="left">Invoice Issued Number:</td>
														<td align="right">'.$tInvoice.'</td>
													</tr>
													<tr>
														<td align="left">Net Value:</td>
														<td align="right">'.FormatCurrency($tNetValue).'</td>
													</tr>
													<tr>
														<td align="left">VAT Charges:</td>
														<td align="right">'.FormatCurrency($tVAT).'</td>
													</tr>
													<tr>
														<td align="left">Total Invoice Value:</td>
														<td align="right">'.FormatCurrency($tAmount).'</td>
													</tr>
													<tr>
														<td align="left">Unpaid Value:</td>
														<td align="right">'.FormatCurrency($tUnpaid).'</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>											
								</td>
								<td align="right" width="50%">
									<table border="0" cellpadding="1" cellspacing="0" align="right" class="formbg">		
										<tr>
											<td align="left" class="formtitle"><b>Transactions</b></td>
										</tr>					
										<tr>
											<td> 
												<table border="1" cellpadding="3" cellspacing="0" align="left" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
													<tr>
														<td align="left">Fee Payment:</td>
														<td align="right">'.FormatCurrency($FeePayment).'</td>
													</tr>
													<tr>
														<td align="left">Deposit Added:</td>
														<td align="right">'.FormatCurrency($AddDeposit).'</td>
													</tr>
													<tr>
														<td align="left">Deposit Refunded:</td>
														<td align="right">'.FormatCurrency($RefundDeposit).'</td>
													</tr>
													<tr>
														<td align="left">Deposit Transferred:</td>
														<td align="right">'.FormatCurrency($TransferDeposit).'</td>
													</tr>
													<tr>
														<td align="left">PrePayment Added:</td>
														<td align="right">'.FormatCurrency($AddBalance).'</td>
													</tr>
													<tr>
														<td align="left">PrePayment Transferred:</td>
														<td align="right">'.FormatCurrency($TransferBalance).'</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr> 
							<tr>
								<td colspan="2">
									<div id="d-deposit"></div>						
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