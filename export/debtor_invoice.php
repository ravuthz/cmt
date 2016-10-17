<?php
	
	$filename = "debtor_invoice_";
	$id = $_GET['id'];
	// Query
	$sql = " select c.CustID, c.CustName, i.InvoiceID, i.InvoiceAmount, i.UnpaidAmount, i.DueDate, 
											 a.AccID, a.UserName, a.StatusID																	
								from tblCustomerInvoice i, tblCustomer c, tblCustProduct a, tblTarPackage t, tlkpService s																	
								where c.CustID = a.CustID and a.PackageID = t.PackageID and t.ServiceID = s.ServiceID
											and i.AccID = a.AccID and i.UnpaidAmount > 0 ";																				
	
	switch($id){
		case 1: # 0 - 30 days
				 $sql .= " and convert(varchar, DueDate, 112) > convert(varchar, dateadd(day, -30, getdate()), 112) ";
				 $filename .= "0_30days";
				break;
		case 2: # 30 - 60 days
				$sql .= " and convert(varchar, DueDate, 112) <= convert(varchar, dateadd(day, -30, getdate()), 112)
									and convert(varchar, DueDate, 112) > convert(varchar, dateadd(day, -60, getdate()), 112) ";
				$filename .= "30_60days";									
				break;
		case 3: # 60 - 90 days
				$sql .= " and convert(varchar, DueDate, 112) <= convert(varchar, dateadd(day, -60, getdate()), 112)
									and convert(varchar, DueDate, 112) > convert(varchar, dateadd(day, -90, getdate()), 112) ";
				$filename .= "60_90days";
				break;
		case 4: # 90 - 120 days
				$sql .= " and convert(varchar, DueDate, 112) <= convert(varchar, dateadd(day, -90, getdate()), 112)
									and convert(varchar, DueDate, 112) > convert(varchar, dateadd(day, -120, getdate()), 112) ";
				$filename .= "90_120days";
				break;
		case 5: # > 120 days
				$sql .= " and convert(varchar, DueDate, 112) <= convert(varchar, dateadd(day, -120, getdate()), 112) ";
				$filename .= "over_120days";
				break;
		default:
				$filename .= "all";
				break;
	}
	$sql .= " order by i.DueDate, i.UnpaidAmount desc";
										
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
	

	print "\r\n\r\n";
	print "No\t";
	print "Invoice\t";
	print "Customer\t";
	print "Account\t";
	print "Status\t";
	print "Due date\t";
	print "Total Amount\t";
	print "Unpaid\t";
	print "\r\n";
	
	if($que = $mydb->sql_query($sql)){
										$totalAmount = 0.00;
										$totalUnpaidAmount = 0.00;
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$CustID = $result['CustID'];																
											$CustName = $result['CustName'];
											$AccID = $result['AccID'];											
											$UserName = $result['UserName'];																
											$StatusID = intval($result['StatusID']);											
											$InvoiceID = $result['InvoiceID'];
											$DueDate = $result['DueDate'];
											$TotalAmount = $result['InvoiceAmount'];
											$UnpaidAmount = $result['UnpaidAmount'];
											
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
											$totalAmount += floatval($TotalAmount);
											$totalUnpaidAmount += floatval($UnpaidAmount);
											$iLoop++;															
																						
											print $iLoop."\t";								
											print $InvoiceID."\t";
											print $CustName."\t";
											print $UserName."\t";
											print $stwd."\t";
											print FormatDate($DueDate, 3)."\t";
											print FormatCurrency($TotalAmount)."\t";
											print FormatCurrency($UnpaidAmount)."\t";
											print "\r\n";
										}
									}
									$mydb->sql_freeresult();
									print "Total\t\t\t\t\t\t";
									print FormatCurrency($totalAmount)."\t";
									print FormatCurrency($totalUnpaidAmount)."\t";									
	

	$mydb->sql_close();
?>