
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$st=$_GET['st'];
	$et=$_GET['et'];
	
//Get Count Customer-----------------------------------------------------------
			$sql = "SELECT COUNT(CustID) AS TotalCust 
							FROM tblCustomer 
							WHERE Convert(varchar, RegisteredDate, 112) BETWEEN ".formatDate($st, 4)." AND ".formatDate($et, 4);
	
			if($que = $mydb->sql_query($sql)){
				$result = $mydb->sql_fetchrow($que);
				$TotalCust = $result['TotalCust'];
			}
			$mydb->sql_freeresult($que);
	// get total invoice
			$sql = "SELECT
										 COUNT(InvoiceID) AS TotalInvoice,
										 SUM(InvoiceAmount) AS TotalAmount,
										 SUM(NetAmount) AS TotalNetAmount,
										 SUM(VATAmount) AS TotalVAT,
										 SUM(UnpaidAmount) AS TotalUnpaid										 
							FROM tblCustomerInvoice 
							WHERE Convert(varchar, IssueDate, 112) BETWEEN ".formatDate($st, 4)." AND ".formatDate($et, 4);
							
			if($que = $mydb->sql_query($sql)){		
				if($result = $mydb->sql_fetchrow()){																															
				//	$TotalCust = $result['TotalCust'];
					$TotalInvoice = $result['TotalInvoice'];
					$TotalAmount = $result['TotalAmount'];
					$TotalNetAmount = $result['TotalNetAmount'];
					$TotalVAT = $result['TotalVAT'];
					$TotalUnpaid = $result['TotalUnpaid'];
								
				}
			}
			$retOuthead .= '<table border="1" cellpadding="3" cellspacing="0" align="left" width="100%" height="100%" id="audit3" style="border-collapse:collapse" bordercolor="#aaaaaa">
				<tr class="row2"><td align="left" width="160"> Customer Number</td><td align="right">'.$TotalCust.'</td></tr>
				<tr class="row2"><td align="left"> Invoice Issued Number</td><td align="right">'.$TotalInvoice.'</td></tr>
				<tr class="row2"><td align="left"> Net Value</td><td align="right">'.FormatCurrency($TotalNetAmount).'</td></tr>
				<tr class="row2"><td align="left"> VAT Charges</td><td align="right">'.FormatCurrency($TotalVAT).'</td></tr>
				<tr class="row2"><td align="left"> Total Invoice Value</td><td align="right">'.FormatCurrency($TotalAmount).'</td></tr>
				<tr class="row2"><td align="left"> Unpaid Value</td><td align="right">'.FormatCurrency($TotalUnpaid).'</td></tr>
				</table>';
	
	print $retOuthead;
	$mydb->sql_freeresult();
?>