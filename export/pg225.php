<?php
	
	$filename = "unpaid_invoice_report";
	
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
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	$sid = FixQuotes($sid);
	$code = FixQuotes($code);
	$m = split("-", $code);
		
	print "Unpaid invoice on ".$month[$m[0]]."-".$m[1];
	print "\r\n";
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
	
	$sql = "SELECT i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.NetAmount, i.VATAmount, i.UnpaidAmount, a.CustID, a.AccID,
		 								a.UserName, a.SubscriptionName, a.StatusID
						FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblTarPackage p(nolock), tblSysBillRunCycleInfo inf(nolock)
						WHERE i.AccID = a.AcCID
							AND a.PackageID = p.PackageID
							AND i.BillingCycleID = inf.CycleID							
							AND convert(varchar, datepart(m, inf.BillEndDate)) + '-' + convert(varchar, year(inf.BillEndDate)) = '".$code."'
							AND i.UnpaidAmount > 0 ";
		if($sid == 2){
			$sql .= " and p.ServiceID = 2 ";
		}elseif($sid == 4){
			$sql .= " and p.ServiceID = 4 ";
		}elseif($sid == 5){	
			$sql .= " and p.ServiceID in(1, 3, 8) ";
		}	
		$sql .= " ORDER BY i.IssueDate, i.UnpaidAmount DESC";
					
	if($que = $mydb->sql_query($sql)){
		$totalinvoice = 0.00;
		$totalnetamount= 0.00;
		$totalvat = 0.00;
		$totalunpaid = 0.00;
		$totalpaid = 0.00;
		$iLoop = 0;
		if($que = $mydb->sql_query($sql)){			
			while($result = $mydb->sql_fetchrow()){
																																		
				$InvoiceID = $result['InvoiceID'];										
				$IssueDate =	$result['IssueDate'];
				$InvoiceAmount =	$result['InvoiceAmount'];
				$NetAmount =	$result['NetAmount'];
				$VATAmount =	$result['VATAmount'];
				$UnpaidAmount =	$result['UnpaidAmount'];
				$PaidAmount = floatval($InvoiceAmount) - floatval($UnpaidAmount);
				$CustID =	$result['CustID'];
				$AccID =	$result['AccID'];
				$UserName =	$result['UserName'];
				$SubscriptionName =	$result['SubscriptionName'];
				$StatusID = $result['StatusID'];
				$totalinvoice += floatval($InvoiceAmount);
				$totalnetamount += floatval($NetAmount);
				$totalvat += floatval($VATAmount);
				$totalunpaid += floatval($UnpaidAmount);
				$totalpaid += floatval($PaidAmount);
				
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
	}
	print "\t\t\t\t\tTotal\t";
	print FormatCurrency($totalnetamount)."\t";
	print FormatCurrency($totalvat)."\t";
	print FormatCurrency($totalinvoice)."\t";
	print FormatCurrency($totalpaid)."\t";
	print FormatCurrency($totalunpaid)."\t";
	
	$mydb->sql_freeresult();
	
?>