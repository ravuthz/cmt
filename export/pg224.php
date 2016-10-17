<?php
	
	$filename = "debtor_invoice_report";
	
	if ($type == 'csv') {
			$filename  .= '.xls';
			//$mime_type = 'text/comma-separated-values';		
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xls') {
			$filename  .= '.xls';
			$mime_type = 'application/vnd.ms-excel';
	} elseif ($type == 'xml') {
			$filename  .= '.xml';
			$mime_type = 'text/xml';	
	} elseif ($type == 'word') {
			$filename  .= '.doc';
			$mime_type = 'application/vnd.ms-word';		
	} elseif ($type == 'pdf') {
			$filename  .= '.pdf';
			$mime_type = 'application/pdf';
	}
	
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $filename . '"');		
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$et = FixQuotes($et);
	$st = FixQuotes($st);		
	print "DEBTOR INVOICE REPORT";
	
	print "\r\n";
	print "\r\n";
	print "No\t";
	print "Invoice\t";
	print "Account\t";
	print "Subscription\t";
	print "Status\t";
	print "Date\t";
	print "Net\t";
	print "VAT\t";
	print "Total\t";
	print "Paid\t";
	print "Unpaid\t";	
	print "\r\n";
	
	$sql = " select i.InvoiceID, i.InvoiceAmount, i.UnpaidAmount, i.VATAmount, i.NetAmount, i.IssueDate, 
								 a.AccID, a.UserName, a.StatusID, a.SubscriptionName																	
					from tblCustomerInvoice i(nolock), tblCustProduct a(nolock), 
							tblTarPackage t(nolock), tlkpService s(nolock), tblSysBillRunCycleInfo inf(nolock)																	
					where a.PackageID = t.PackageID 
								and t.ServiceID = s.ServiceID
								and i.BillingCycleID = inf.CycleID
								and i.AccID = a.AccID 
								and i.UnpaidAmount > 0 ";																				
	if($sid == 2){
		$sql .= " and s.ServiceID = 2 ";
	}elseif($sid == 4){
		$sql .= " and s.ServiceID = 4 ";
	}elseif($sid == 5){	
		$sql .= " and s.ServiceID in(1, 3, 8) ";
	}
	switch($id){
		case 1: # 0 - 30 days
				 $sql .= " and convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) ";																	
				break;
		case 2: # 30 - 60 days
				$sql .= " and convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112)
									and convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112) ";
				break;
		case 3: # 60 - 90 days
				$sql .= " and convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112)
									and convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112) ";
				break;
		case 4: # 90 - 120 days
				$sql .= " and convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -90, getdate()), 112)
									and convert(varchar, inf.BillEndDate, 112) > convert(varchar, dateadd(day, -120, getdate()), 112) ";
				break;
		case 5: # > 120 days
				$sql .= " and convert(varchar, inf.BillEndDate, 112) <= convert(varchar, dateadd(day, -120, getdate()), 112) ";
				break;
	}
	$sql .= " order by i.IssueDate, i.UnpaidAmount desc";			
	if($que = $mydb->sql_query($sql)){
		$totalAmount = 0.00;
		$totalUnpaidAmount = 0.00;
		$totalVAT = 0.00;
		$totalNet = 0.00;
		$totalPaid = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																
			$VATAmount = $result['VATAmount'];																
			$NetAmount = $result['NetAmount'];
			$AccID = $result['AccID'];											
			$UserName = $result['UserName'];
			$SubscriptionName = $result['SubscriptionName'];																
			$StatusID = intval($result['StatusID']);											
			$InvoiceID = $result['InvoiceID'];
			$IssueDate = $result['IssueDate'];
			$TotalAmount = $result['InvoiceAmount'];
			$UnpaidAmount = $result['UnpaidAmount'];
			$VATAmount = $result['VATAmount'];
			$NetAmount = $result['NetAmount'];
			$PaidAmount = floatval($TotalAmount) - floatval($UnpaidAmount); 
			switch($StatusID){
				case 0:
					$stbg = $bgUnactivate;
					$stfg = $foreUnactivate;
					$stwd = "Inactive";
					break;
				case 1:
					$stbg = $bgActivate;
					$stfg = $foreActivate;
					$stwd = "Active";
					break;
				case 2:
					$stbg = $bgLock;
					$stfg = $foreLock;
					$stwd = "Barred";
					break;
				case 3:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
				case 4:
					$stbg = $bgClose;
					$stfg = $foreClose;
					$stwd = "Closed";
					break;
			}						
			$totalAmount += floatval($TotalAmount);
			$totalUnpaidAmount += floatval($UnpaidAmount);
			$totalNet += floatval($NetAmount);
			$totalVAT += floatval($VATAmount);
			$totalPaid += ($PaidAmount);
			$iLoop++;															
						
			print $iLoop."\t";								
			print $InvoiceID."\t";
			print $UserName."\t";	
			print substr($SubscriptionName, 0, 30)."\t";													
			print $stwd."\t";
			print formatDate($IssueDate, 3)."\t";						
			print FormatCurrency($NetAmount)."\t";
			print FormatCurrency($VAT)."\t";
			print FormatCurrency($Amount)."\t";
			print FormatCurrency($PaidAmount)."\t";
			print FormatCurrency($UnpaidAmount)."\t";
			print "\r\n";
		}
	}
	print "\t\t\t\t\tTotal\t";
	print FormatCurrency($totalNet)."\t";
	print FormatCurrency($totalVAT)."\t";
	print FormatCurrency($totalAmount)."\t";
	print FormatCurrency($totalPaid)."\t";
	print FormatCurrency($totalUnpaidAmount)."\t";
	
	$mydb->sql_freeresult();
	
?>