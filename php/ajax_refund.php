<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	$cid = $_GET['cid'];
	$acc = $_GET['acc'];
			
	$retOut = '
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa" bgColor="#ffffff">
							<thead>																																
								<th align="center">Rec#</th>
								<th align="center">Inv#</th>
								<th align="center">Type</th>	
								<th align="center" nowrap="nowrap">Account</th>
								<th align="center">Date</th>																				
								<th align="center">Amount</th>
								<th align="center">Mode</th>
								<th align="center">Cashier</th>																												
							</thead>';
	
	$sql = "select d.PaymentID, d.PaymentAmount, convert(varchar, d.PaymentDate, 120) 'paiddate', d.Cashier, d.InvoiceID, 
							m.PaymentMode, s.TransactionName, ac.UserName
					from tblCustCashDrawer d(nolock), tlkpPaymentMode m(nolock), tlkpTransaction s, tblCustProduct ac(nolock) 
					where d.PaymentModelID = m.PaymentID 
								and d.TransactionModeID = s.TransactionID 
								and d.AcctID = ac.AccID
								and (d.IsRollback is NULL or d.IsRollback = 0) and d.TransactionModeID in(2, 7, 8, 9) 
						and d.CustID = $cid
					";
						
	if(trim($acc != "*"))
		$sql .= "and ac.UserName like '%".$acc."%' ";
	$sql .=	"order by d.PaymentID desc";
	
	
	if($que = $mydb->sql_query($sql)){
		$Total = 0;
		$iLoop = 0;
		$retOut .= "<tbody>";
		while($result = $mydb->sql_fetchrow()){																															
			$PaymentID = $result['PaymentID'];
			$PaymentAmount = $result['PaymentAmount'];
			$paiddate = $result['paiddate'];
			$Cashier = $result['Cashier'];
			$InvoiceID = $result['InvoiceID'];
			$PaymentMode = $result['PaymentMode'];
			$TransactionName = $result['TransactionName'];
			$UserName = $result['UserName'];
			$Type = "DN";
			$Invoice = "" ;
			if($InvoiceID != 0)
			$Invoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
			$Receipt = "<a href='./finance/receipt.php?CustomerID=".$CustomerID."&PaymentID=".$PaymentID."' target='_blank'>".$PaymentID."</a>";										

			$Total += floatval($PaymentAmount);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="left">'.$Receipt.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.$Invoice.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Type.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$UserName.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.formatDate($paiddate, 1).'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$PaymentMode.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Cashier.'</td>';
			$retOut .= '</tr>';
		}		
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="5">Total</td>
											<td align="right">'.FormatCurrency($Total).'</td>
											<td align="right" colspan="2">&nbsp;</td>
										</tr>
										
									</tfoot>';
	}
	$mydb->sql_freeresult();		
	$retOut	.= "</table>";
	//print $sql;
	print $retOut;	
?>
