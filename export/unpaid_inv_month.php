<?php
	
	$filename = "unpaid_invoice_on_";
	$code = $_GET['code'];
	
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	

	$m = split("-", $code);
	$filename .= $month[$m[0]]."-".$m[1];
	
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
	
	print "No.\t";
	print "Invoice ID\t";
	print "Account\t";
	print "Due Date\t";
	print "Total Amount\t";
	print "Net Amount\t";
	print "VAT Amount\t";
	print "Unpaid Amount\t";
	print "\r\n";
	
	$sql = "SELECT i.InvoiceID, i.IssueDate, i.InvoiceAmount, i.NetAmount, i.VATAmount, i.UnpaidAmount, a.UserName
						FROM tblCustomerInvoice i, tblCustProduct a
						WHERE i.AccID = a.AcCID
							AND convert(varchar, datepart(m, i.DueDate)) + '-' + convert(varchar, year(i.DueDate)) = '".$code."'
							AND i.UnpaidAmount > 0";
	$totalinvoice = 0.00;
	$totalnetamount= 0.00;
	$totalvat = 0.00;
	$totalunpaid = 0.00;
	$iLoop = 0;
	if($que = $mydb->sql_query($sql)){			
		while($result = $mydb->sql_fetchrow()){
																																	
			$InvoiceID = $result['InvoiceID'];										
			$IssueDate =	$result['IssueDate'];
			$InvoiceAmount =	$result['InvoiceAmount'];
			$NetAmount =	$result['NetAmount'];
			$VATAmount =	$result['VATAmount'];
			$UnpaidAmount =	$result['UnpaidAmount'];
			$UserName =	$result['UserName'];
			$totalinvoice += floatval($InvoiceAmount);
			$totalnetamount += floatval($NetAmount);
			$totalvat += floatval($VATAmount);
			$totalunpaid += floatval($UnpaidAmount);
			$iLoop++;		
			
			print $iLoop."\t";
			print $InvoiceID."\t";
			print $UserName."\t";
			print FormatDate($IssueDate, 3)."\t";
			print FormatCurrency($InvoiceAmount)."\t";
			print FormatCurrency($NetAmount)."\t";
			print FormatCurrency($VATAmount)."\t";
			print FormatCurrency($UnpaidAmount)."\t";
			print "\r\n";											
		}
	}	
	print "Total\t\t\t\t\t\t\t";
	print FormatCurrency($totalinvoice)."\t";
	print FormatCurrency($totalnetamount)."\t";
	print FormatCurrency($totalvat)."\t";
	print FormatCurrency($totalunpaid)."\t";
	
	$mydb->sql_freeresult();
	$mydb->sql_close();
?>