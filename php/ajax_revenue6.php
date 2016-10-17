<?php

	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	$st = $_GET['st'];	
	$et = $_GET['et'];
	$service = $_GET['service'];
	$retOut = '
			<table border="0" cellpadding="1" cellspacing="0" width="100%" class="formbg">
				<tr>
					<td align="left" class="formtitle"><b>Deposit Pre-Payment Refund Transfer and Settlement Billing Event</b></td>
				</tr>
				<tr>
					<td>
	<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">PayDate</th>								
								<th align="center">AccID</th>
								<th align="center">Username</th>
								<th align="center">CustomerName</th>
								<th align="center">BookNC</th>
								<th align="center">BookIDD</th>	
								<th align="center">BookMF</th>	
								<th align="center">AdvPay</th>
								<th align="center">OverINV</th>
								<th align="center">RefNC</th>			
								<th align="center">RefIDD</th>
								<th align="center">RefMF</th>
								<th align="center">RefCr</th>
								<th align="center">NCtoCr</th>
								<th align="center">IDDtoCr</th>
								<th align="center">MFtoCr</th>
								<th align="center">CrNote</th>
								<th align="center">WriteOff</th>
								<th align="center">Settle</th>																											
							</thead>
							<tbody>';
$sql = "	
			select	PaymentID,
					convert(varchar(10),PaymentDate,111) PaymentDate,
					Custid,
					AccID,
					Username,
					SubscriptionName,
					null InvoiceID,
					0 OverINV,
					BookNC,
					BookIDD,
					BookMF,
					IncreaseCredit,
					RefundNC,
					RefundIDD,
					RefundMF,
					RefundCredit,
					NCtoCredit,
					IDDtoCredit,
					MFtoCredit,
					CreditNote,
					WriteOff,
					Settle
			from vwOtherPayment
			where invoiceid = 0 and serviceid =" .$service; 
$sql .= "and convert(varchar, PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
$sql .= " Union
			select	PaymentID,
					'".FormatDate($et, 9)."' PaymentDate,
					cp.Custid,
					ca.AcctID,
					cp.Username,
					cp.SubscriptionName,
					ca.invoiceid,
					sum(paymentAmount) - ci.OriginalUnpaidAmount 'OverINV',
					0 BookNC,
					0 BookIDD,
					0 BookMF,
					0 IncreaseCredit,
					0 RefundNC,
					0 RefundIDD,
					0 RefundMF,
					0 RefundCredit,
					0 NCtoCredit,
					0 IDDtoCredit,
					0 MFtoCredit,
					0 CreditNote,
					0 WriteOff,
					0 Settle	   
		 	from tblCustCashDrawer ca
		 	join tblCustomerInvoice ci on ca.invoiceid = ci.invoiceid
			join tblCustProduct cp on cp.accid = ca.acctid
			join tblTarPackage tp on tp.packageid = cp.packageid 
			where IsRollBack IS NULL and serviceid =" .$service; 
$sql .= " and convert(varchar, ca.PaymentDate, 112) between ".FormatDate($st, 4)." and ".FormatDate($et, 4);
$sql .= " group by PaymentID, cp.CustID,ca.acctid, cp.username,cp.subscriptionName, ca.invoiceid, ci.OriginalUnpaidAmount  
		  having sum(paymentAmount) - ci.OriginalUnpaidAmount > 0 
 		  Order by  PaymentDate asc,
					BookNC desc,
					BookIDD desc,
					BookMF desc,
					IncreaseCredit desc,
					RefundNC desc,
					RefundIDD desc,
					RefundMF desc,
					RefundCredit desc,
					NCtoCredit desc,
					IDDtoCredit desc,
					MFtoCredit desc,
					CreditNote desc,
					WriteOff desc,
					Settle desc,
					username asc
		"; 					

	if($que = $mydb->sql_query($sql)){				
		$ToiLoop = 0;
		$TotalBookNC = 0.00;
		$TotalBookIDD = 0.00;
		$TotalBookMF = 0.00;
		$TotalIncreaseCredit = 0.00;
		$TotalOverINV = 0.00;
		$TotalRefundNC = 0.00;
		$TotalRefundIDD = 0.00;
		$TotalRefundMF = 0.00;
		$TotalRefundCredit = 0.00;
		$TotalNCtoCredit = 0.00;
		$TotalIDDtoCredit = 0.00;
		$TotalMFtoCredit = 0.00;
		$TotalCreditNote = 0.00;
		$TotalWriteOff = 0.00;
		$TotalSettle = 0.00;		
			
		while($result = $mydb->sql_fetchrow($que)){																																
			$PaymentDate = $result['PaymentDate'];
			$Custid = $result['Custid'];
			$AccID = $result['AccID'];
			$Username = $result['Username'];										
			$SubscriptionName = $result['SubscriptionName'];
			$BookNC = $result['BookNC'];
			$BookIDD = $result['BookIDD'];										
			$BookMF = $result['BookMF'];																
			$IncreaseCredit = $result['IncreaseCredit'];											
			$OverINV = $result['OverINV'];											
			$RefundNC = $result['RefundNC'];											
			$RefundIDD = $result['RefundIDD'];
			$RefundMF = $result['RefundMF'];
			$RefundCredit = $result['RefundCredit'];
			$NCtoCredit = $result['NCtoCredit'];
			$IDDtoCredit = $result['IDDtoCredit'];											
			$MFtoCredit = $result['MFtoCredit'];											
			$CreditNote = $result['CreditNote'];
			$WriteOff = $result['WriteOff'];
			$Settle = $result['Settle'];			
					
					
			$linkAcct = "<a href='../?CustomerID=".$Custid."&AccountID=".$Accid."&pg=91' target='_blank'>".$Username."</a>";		
			
			$TotalBookNC += floatval($BookNC);
			$TotalBookIDD += floatval($BookIDD);
			$TotalBookMF += floatval($BookMF);
			$TotalIncreaseCredit += floatval($IncreaseCredit);
			$TotalOverINV += floatval($OverINV);
			$TotalRefundNC += floatval($RefundNC);
			$TotalRefundIDD += floatval($RefundIDD);
			$TotalRefundMF += floatval($RefundMF);
			$TotalRefundCredit += floatval($RefundCredit);
			$TotalNCtoCredit += floatval($NCtoCredit);
			$TotalIDDtoCredit += floatval($IDDtoCredit);
			$TotalMFtoCredit += floatval($MFtoCredit);
			$TotalCreditNote += floatval($CreditNote);
			$TotalWriteOff += floatval($WriteOff);
			$TotalSettle += floatval($Settle);
			
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
				$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PaymentDate.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$AccID.'</td>';																									
			$retOut .= '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($BookNC).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($BookIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($BookMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($IncreaseCredit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($OverINV).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RefundNC).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RefundIDD).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RefundMF).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($RefundCredit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($NCtoCredit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($IDDtoCredit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($MFtoCredit).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($CreditNote).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($WriteOff).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($Settle).'</td>';
			$retOut .= '</tr>';
		}		
	}else{
	//	$error = $mydb->sql_error();
	//	$ya= $error['message']; 
	}
	$mydb->sql_freeresult();
	$mydb->sql_close();
		$retOut .= '</tbody>
								<tfoot>
									<tr>
										<td colspan=5 align=right>Total</td>
										<td align="right">'.FormatCurrency($TotalBookNC).'</td>
										<td align="right">'.FormatCurrency($TotalBookIDD).'</td>
										<td align="right">'.FormatCurrency($TotalBookMF).'</td>
										<td align="right">'.FormatCurrency($TotalIncreaseCredit).'</td>
										<td align="right">'.FormatCurrency($TotalOverINV).'</td>
										<td align="right">'.FormatCurrency($TotalRefundNC).'</td>
										<td align="right">'.FormatCurrency($TotalRefundIDD).'</td>
										<td align="right">'.FormatCurrency($TotalRefundMF).'</td>
										<td align="right">'.FormatCurrency($TotalRefundCredit).'</td>
										<td align="right">'.FormatCurrency($TotalNCtoCredit).'</td>
										<td align="right">'.FormatCurrency($TotalIDDtoCredit).'</td>
										<td align="right">'.FormatCurrency($TotalMFtoCredit).'</td>
										<td align="right">'.FormatCurrency($TotalCreditNote).'</td>
										<td align="right">'.FormatCurrency($TotalWriteOff).'</td>
										<td align="right">'.FormatCurrency($TotalSettle).'</td>
									</tr>
								</tfoot>																				
							</table>
						</td>
					</tr>
				</table>';
		print $retOut;
?>