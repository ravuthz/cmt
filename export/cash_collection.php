<?php
	
	$filename = "cash_collection";
	
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
	
	$code = FixQuotes($code);
	$m = FixQuotes($m);		
	
	print "No\t";
	print "Invoice\t";
	print "Account\t";
	print "Status\t";
	print "Issue date\t";
	print "Due date\t";
	print "Total Amount\t";
	print "VAT Amount\t";
	print "Unpaid\t";
	print "\r\n";
	
	$sql = "SELECT i.InvoiceID, i.IssueDate, i.Duedate, i.InvoiceAmount, i.NetAmount, i.VATAmount, i.UnpaidAmount,
					a.CustID, a.AccID, a.StatusID, a.UserName
	FROM tblCustomerInvoice i, tblCashPayment p, tblCustProduct a
	WHERE i.InvoiceID = p.InvoiceID AND i.AccID = a.AccID
				AND convert(varchar, datepart(m, p.PaymentDate)) + '-' + convert(varchar, year(p.PaymentDate)) = '".$code."'																		
				AND convert(varchar, datepart(m, i.IssueDate)) = '".$m."'";
				
	if($que = $mydb->sql_query($sql)){
		$totalAmount = 0.00;
		$totalVATAmount = 0.00;
		$totalUnpaidAmount = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																
			$InvoiceID = $result['InvoiceID'];																
			$IssueDate = $result['IssueDate'];
			$Duedate = $result['Duedate'];											
			$InvoiceAmount = $result['InvoiceAmount'];																
			$StatusID = intval($result['StatusID']);											
			$NetAmount = $result['NetAmount'];
			$VATAmount = $result['VATAmount'];
			$UnpaidAmount = $result['UnpaidAmount'];
			$CustID = $result['CustID'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			
			switch($StatusID){
				case 0:					
					$stwd = "Inactive";
					break;
				case 1:				
					$stwd = "Active";
					break;
				case 2:					
					$stwd = "Barred";
					break;
				case 3:					
					$stwd = "Closed";
					break;
			}		
			$totalAmount += floatval($InvoiceAmount);
			$totalUnpaidAmount += floatval($UnpaidAmount);
			$totalVATAmount += floatval($VATAmount);
			$iLoop++;															
			
			//if(($iLoop % 2) == 0)
			if(datediff($DueDate)>0)
				$style = "row1";
			else
				$style = "row2";
			
			print $iLoop."\t";								
			print $InvoiceID."\t";
			print $UserName."\t";	
			print $stwd."\t";													
			print formatDate($IssueDate, 3)."\t";
			print formatDate($Duedate, 3)."\t";
			print FormatCurrency($InvoiceAmount)."\t";
			print FormatCurrency($VATAmount)."\t";
			print FormatCurrency($UnpaidAmount)."\t";
			print "\r\n";
		}
	}
	print "Total\t\t\t\t\t\t";
	print FormatCurrency($totalAmount)."\t";
	print FormatCurrency($totalVATAmount)."\t";
	print FormatCurrency($totalUnpaidAmount)."\t";
	
	$mydb->sql_freeresult();
	
?>