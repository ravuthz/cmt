<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$cid=$_GET['cid'];
	$ct=$_GET['ct'];	
	$where=$_GET['where'];	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>OPEN INVOICE REPORT:</b><br />
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
								<th align="center" style="border:1px solid">Subscription name</th>								
								<th align="center" style="border:1px solid">Net amount</th>
								<th align="center" style="border:1px solid">VAT amount</th>
								<th align="center" style="border:1px solid">Invoice amount</th>
								<th align="center" style="border:1px solid">Paid amount</th>
								<th align="center" style="border:1px solid">Unpaid amount</th>
							</thead>
							<tbody>';
	$sql = "SELECT i.InvoiceID, a.AccID, a.CustID, a.SubscriptionName, a.UserName, i.NetAmount, 
								i.VATAmount, i.InvoiceAmount, i.UnpaidAmount
					FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
					WHERE i.AccID = a.AccID
						AND i.BillingCycleID = inf.CycleID
						AND a.PackageID in(".$where.")";					
			
		$sql .= "	AND convert(varchar, inf.BillEndDate, 112) = '".$cid."' 
							AND i.UnpaidAmount > 0
							ORDER BY InvoiceID"; 
	
	if($que = $mydb->sql_query($sql)){		
		$n = 0;
		$totalamount = 0;
		$totalvat = 0;
		$totalnet = 0;
		$totalpaid = 0;
		$totalunpaid = 0;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$InvoiceNo = $result['InvoiceID'];					
			$AccID = $result['AccID'];
			$CustID = $result['CustID'];
			$SubscriptionName = $result['SubscriptionName'];
			$UserName = $result['UserName'];
			$NetAmount = $result['NetAmount'];
			$VATAmount = $result['VATAmount'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$UnpaidAmount = $result['UnpaidAmount'];
			$linkInv = "<a href='./finance/screeninvoice.php?CustomerID=".$CustID."&AccountID=".$AccID."&InvoiceID=".$InvoiceNo."' target='_blank'>".$InvoiceNo."</a>";					
			$linkAcc = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91' target='_blank'>".$UserName."</a>";		
			
			$PaidAmount = floatval($InvoiceAmount) - floatval($UnpaidAmount);
			
			$totalamount+= $InvoiceAmount;
			$totalvat+= $VATAmount;		
			$totalnet += $NetAmount;
			$totalpaid += $PaidAmount;
			$totalunpaid += $UnpaidAmount;
			$iLoop++;															
			$n++;
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			
			$retOut .= '<td class="'.$style.'" align="center" style="border-left:1px solid; border-top:1px dotted;">'.$n.'</td>';	
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$InvoiceNo.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$AccID.'</td>';																																		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$SubscriptionName.'</td>';
			
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NetAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($VATAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($InvoiceAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($PaidAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.FormatCurrency($UnpaidAmount).'</td>';	
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="5" align="right" style="border:1px solid">Total:&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalnet).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalvat).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalamount).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalpaid).'</td>
										<td align="right" style="border:1px solid">'.FormatCurrency($totalunpaid).'</td>
									</tr>
								</table>						
							</td>
						</tr>
					</table>';
     print $retOut;
?>