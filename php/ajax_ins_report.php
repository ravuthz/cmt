<link rel="stylesheet" type="text/css" href="../style/mystyle.css" />
<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$st=$_GET['st'];
	$et=$_GET['et'];	
	$tid=$_GET['tid'];
	$tt=$_GET['tt'];	
	$where=$_GET['where'];	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>INSTALLATION FEE REPORT:</b><br />
						Print on: '.date("Y M d H:i:s").'
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center" style="border:1px solid">No.</th>
								<th align="center" style="border:1px solid">Acc ID</th>					
								<th align="center" style="border:1px solid">Account name</th>			
								<th align="center" style="border:1px solid">Subscription name</th>								
								<th align="center" style="border:1px solid">NDeposit</th>
								<th align="center" style="border:1px solid">IDeposit</th>
								<th align="center" style="border:1px solid">MDeposit</th>
								<th align="center" style="border:1px solid">Credit</th>
								<th align="center" style="border:1px solid">Invoice</th>
								<th align="center" style="border:1px solid">Invoice amount</th>
								<th align="center" style="border:1px solid">Paid amount</th>
								<th align="center" style="border:1px solid">Unpaid amount</th>
							</thead>
							<tbody>';
	$sql = "SELECT i.InvoiceID, a.AccID, a.CustID, a.SubscriptionName, a.UserName, i.NetAmount, 
								i.VATAmount, i.InvoiceAmount, i.UnpaidAmount, 
					adp.NationalDeposit,adp.InternationDeposit,adp.MonthlyDeposit , Credit
					FROM tblCustomerInvoice i(nolock), tblCustProduct a(nolock), tblSysBillRunCycleInfo inf(nolock)
					,tblAccDeposit adp, tblAccountBalance ab, tblCustAddress cad
					WHERE i.AccID = a.AccID
						AND i.BillingCycleID = inf.CycleID
						AND a.accID = adp.accID
						AND a.accID = ab.accID
						AND a.accID = cad.accID AND cad.IsBillingAddress=0
						AND a.PackageID in(".$where.")
						AND cad.cityID in(".$tid.")
						AND i.InvoiceAmount>0
		";					
			
		$sql .= " AND i.IssueDate>='".$st."' AND i.IssueDate<='".$et."'  
				 AND Exists (select * from tblCustomerInvoiceDetail cId where cId.InvoiceID=I.InvoiceID and cId.BillItemID=7)
				 ORDER BY i.UnpaidAmount Desc"; 
	
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
			
			$NationalDeposit = $result['NationalDeposit'];
			$InternationDeposit = $result['InternationDeposit'];
			$MonthlyDeposit = $result['MonthlyDeposit'];
			$Credit = $result['Credit'];
			$InvoiceAmount = $result['InvoiceAmount'];
			$UnpaidAmount = $result['UnpaidAmount'];	
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
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$AccID.'</td>';																																		
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($NationalDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($InternationDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($MonthlyDeposit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($Credit).'</td>';
			$retOut .= '<td class="'.$style.'" align="left" style="border-left:1px dotted; border-top:1px dotted;">'.$InvoiceNo.'</td>';
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($InvoiceAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted;">'.FormatCurrency($PaidAmount).'</td>';	
			$retOut .= '<td class="'.$style.'" align="right" style="border-left:1px dotted; border-top:1px dotted; border-right:1px solid">'.FormatCurrency($UnpaidAmount).'</td>';	
			$retOut .= '</tr>';						
		}
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>																					
								<tr height=20>
										<td colspan="9" align="right" style="border:1px solid">Total:&nbsp;&nbsp;&nbsp;&nbsp;</td>
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