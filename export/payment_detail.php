<?php
	
	$filename = "drawer";	
	$filename .= "_".eregi_replace(" ", "_", $cashier)."_".$drawerid;
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
	
	$serviceid = $_GET['serviceid'];
	$drawerid = $_GET['did'];
	$t = $_GET['t'];
	$cashier = $_GET['cashier'];
	print "Drawer transaction ".date("Y-M-d H:i:s");
	print "\r\n";
	print "Drawer id: ".$drawerid."\t";
	print "\r\n";
	print "Cashier name: ".$cashier."\t";
	print "\r\n\r\n";
	print "No\t";
	print "Payment id\t";
	print "Payment date\t";
	print "Account\t";
	print "Package\t";
	print "Transaction\t";
	print "Paid as\t";
	print "Cashier\t";
	print "Description\t";
	print "Amount\t";
	print "\r\n";
		
	$sql = "select dr.DrawerID, dr.PaymentID, dr.PaymentDate, dr.PaymentAmount, dr.Description, dr.Cashier,
						ac.UserName, ta.TarName, tr.TranGroupID, tr.TransactionName, pa.PaymentMode
					from tblCustCashDrawer dr, tblCustProduct ac, tblTarPackage ta,
						tlkpTransaction tr, tlkpPaymentMode pa
					where dr.AcctID = ac.AccID
						and ac.PackageID = ta.PackageID
						and dr.TransactionModeID = tr.TransactionID	
						and dr.PaymentModelID = pa.PaymentID
						and (dr.IsRollBack is NULL or dr.IsRollBack = 0)
						and dr.DrawerID = ".$drawerid."
						and tr.TranGroupID = ".$t;
	if($serviceid == 2){
		$sql .= " and ta.ServiceID = 2 ";
	}elseif($serviceid == 4){
		$sql .= " and ta.ServiceID = 4 ";
	}elseif($serviceid == 5){	
		$sql .= " and ta.ServiceID in(1, 3, 8) ";
	}
	$sql .= " and dr.IsSubmitted = 0				
						order by ta.TarName, tr.TransactionName, pa.PaymentMode";						
	if($que = $mydb->sql_query($sql)){		
		$n = 0;		
		$GTotal = 0.00;
		while($result = $mydb->sql_fetchrow()){																															
			$DrawerID = $result['DrawerID'];										
			$PaymentID = $result['PaymentID'];
			$PaymentDate = $result['PaymentDate'];
			$PaymentAmount = $result['PaymentAmount'];
			$Description = $result['Description'];
			$Cashier = $result['Cashier'];
			$UserName = $result['UserName'];
			$TarName = $result['TarName'];
			$TranGroupID = intval($result['TranGroupID']);
			$TransactionName = $result['TransactionName'];
			$PaymentMode = $result['PaymentMode'];
			if($TranGroupID == 1){
				$GTotal += floatval($PaymentAmount);
				$Amount = FormatCurrency($PaymentAmount);
			}elseif($TranGroupID == 2){
				$GTotal -= floatval($PaymentAmount);
				$Amount = FormatCurrency($PaymentAmount * -1);
			}else{
				$Amount = "{".FormatCurrency($PaymentAmount)."}";
			}
			
			
			$n++;
			print $n."\t";
			print $PaymentID."\t";
			print FormatDate($PaymentDate, 7)."\t";
			print $UserName."\t";
			print $TarName."\t";
			print $TransactionName."\t";
			print $PaymentMode."\t";
			print $Cashier."\t";
			print $Description."\t";
			print $Amount."\t";
			print "\r\n";										
		}		
	}
	$mydb->sql_freeresult();
				
		print "\t\t\t\t\t\t\t\t Total: \t";
		print FormatCurrency($GTotal);		
		

?>