<?php
	
	$filename = "invoice_report";
	
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
	print "Invoice report";
	print "\r\n";
	print "From: ".FormatDate($st, 3)." to ".FormatDate($et, 3);
	print "\r\n";
	print "\r\n";
	print "\r\n";
	print "No\t";
	print "Invoice\t";
	print "Customer Name\t";
	print "Account Name\t";
	print "Package Name\t";
	print "Net Amount\t";
	print "VAT Amount\t";
	print "Invoice Amount\t";	
	print "\r\n";
	
	$sql = "SELECT ci.InvoiceID, cp.AccID, cp.UserName, ci.InvoiceAmount, ci.VATAmount, ci.NetAmount,
					c.Custid, c.CustName, tp.TarName 
			FROM tblCustomer c, tblCustProduct cp, tblCustomerInvoice ci, tblTarPackage tp			
			WHERE c.CustID = cp.CustID AND cp.AccID = ci.AccID AND cp.PackageID = tp.PackageID			
				AND convert(varchar, ci.IssueDate, 112) BETWEEN ".formatDate($st, 4)." AND ".formatDate($et, 4)."
			ORDER BY tp.TarName DESC;";
				
	if($que = $mydb->sql_query($sql)){
		$totalamount = 0.00;
		$totalvat = 0.00;
		$totalnet = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																
			$InvoiceNo = $result['InvoiceID'];					
			$Custid = $result['Custid'];										
			$CustName = $result['CustName'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$PackageName = $result['TarName'];
			$ServiceName = $result['ServiceName'];
			$Amount = $result['InvoiceAmount'];
			$NetAmount = $result['NetAmount'];
			$VAT = $result['VATAmount'];
			
			$totalamount+= $Amount;
			$totalvat+= $VAT;
			$totalnet += $NetAmount;			
			$iLoop++;															
						
			print $iLoop."\t";								
			print $InvoiceNo."\t";
			print $CustName."\t";	
			print $UserName."\t";													
			print $PackageName."\t";
			print FormatCurrency($NetAmount)."\t";
			print FormatCurrency($VAT)."\t";
			print FormatCurrency($Amount)."\t";
			print "\r\n";
		}
	}
	print "\t\t\t\tTotal";
	print FormatCurrency($totalnet)."\t";
	print FormatCurrency($totalvat)."\t";
	print FormatCurrency($totalamount)."\t";
	
	$mydb->sql_freeresult();
	
?>