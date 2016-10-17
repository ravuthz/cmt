<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$ct=$_GET['ct'];	
	$cid=$_GET['cid'];	
	$where = $_GET['where'];
	
	/*//Get Count Customer-----------------------------------------------------------
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
	
//	print $retOuthead;
	$mydb->sql_freeresult();*/
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>DAILY INVOICE ISSUE:</b><br />
						Cycle date: <b>'.$ct.'</b><br />
						Print on: '.date("Y M d H:i:s").'
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="center" style="border:1px solid">Invoice</th>								
								<th align="center" style="border:1px solid">Acc ID</th>
								<th align="center" style="border:1px solid">Account name</th>
								<th align="center" style="border:1px solid">Subscription</th>
								<th align="center" style="border:1px solid">Package name</th>
								<th align="center" style="border:1px solid">Issue date</th>	
								<th align="center" style="border:1px solid">Net amount</th>
								<th align="center" style="border:1px solid">VAT amount</th>
								<th align="center" style="border:1px solid">Invoice amount</th>								
							</thead>
							<tbody>';
	$sql = "SELECT ci.InvoiceID, cp.AccID, cp.CustID, cp.UserName, cp.SubscriptionName, ci.InvoiceAmount, 
								ci.VATAmount, ci.NetAmount, tp.TarName, ci.IssueDate 
					FROM tblCustProduct cp(nolock), tblCustomerInvoice ci(nolock), tblTarPackage tp(nolock), tblSysBillRunCycleInfo inf(nolock)			
					WHERE cp.AccID = ci.AccID 
						AND inf.PackageID = tp.PackageID			
						AND inf.CycleID = ci.BillingCycleID
						AND convert(varchar, inf.BillEndDate, 112) = '".$cid."' 
						AND tp.PackageID IN(".$where.")
						AND	ci.InvoiceAmount != 0 		
					ORDER BY ci.IssueDate, tp.TarName DESC;";
			
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0;
		$totalvat = 0;
		$totalnet = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceNo = $result['InvoiceID'];					
			$Custid = $result['Custid'];										
			$SubscriptionName = $result['SubscriptionName'];
			$AccID = $result['AccID'];
			$UserName = $result['UserName'];
			$PackageName = $result['TarName'];
			$NetAmount = $result['NetAmount'];
			$Amount = $result['InvoiceAmount'];
			$VAT = $result['VATAmount'];
			$IssueDate = $result['IssueDate'];
			
			$linkCust = "<a href='../?CustomerID=".$Custid."&pg=10'>".$CustName."</a>";
			$linkAcc = "<a href='../?CustomerID=".$Custid."&AccountID=".$AccID."&pg=90'>".$UserName."</a>";		
			$totalamount+= $Amount;
			$totalvat+= $VAT;		
			$totalnet += $NetAmount;
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;">'.$n.'</td>';	
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px dotted; border-top:1px dotted;">'.$InvoiceNo.'</td>';								
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$AccID.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$PackageName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.FormatDate($IssueDate, 3).'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NetAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($VAT).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.FormatCurrency($Amount).'</td>';	
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="7" align="right" style="border:1px solid">Total:&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalnet).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalvat).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalamount).'</td></tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>