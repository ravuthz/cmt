<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");
	$DrawerID = $_GET['drawer'];
	$TransactionID = $_GET['tranid'];
	$TransactionName = $_GET['tranname'];	
	$cashier = $_GET['cashier'];	
	$pm = $_GET['pm'];			
	$d = $_GET['d'];			
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle">
						<b>Tranaction name: [<font color=white>'.$TransactionName.'</font>]; Cashier [<font color=white>'.$cashier.'</font>]</b>
					</td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">Payment ID</th>								
								<th align="center">Account</th>	
								<th align="center">Date</th>
								<th align="center">Description</th>																				
								<th align="center">Amount</th>																												
							</thead>
							<tbody>';
	
	$sql = "select a.AccID, a.CustID, a.UserName, d.PaymentID, d.PaymentDate, d.PaymentAmount, d.Description
					from tblCustProduct a, tblCustCashDrawer d
					where a.AccID = d.AcctID and d.DrawerID = $DrawerID 
							and d.TransactionModeID = $TransactionID 
							and d.PaymentModelID = $pm
							and convert(varchar, d.PaymentDate, 112) = '".$d."'
							and d.DrawerID= $DrawerID";	
	
	if($que = $mydb->sql_query($sql)){
		$totalAmount = 0.00;
		$iLoop = 0;
		while($result = $mydb->sql_fetchrow()){																															
			$AccID = $result['AccID'];										
			$CustID = $result['CustID'];										
			$UserName = $result['UserName'];																
			$PaymentID = $result['PaymentID'];											
			$PaymentDate = $result['PaymentDate'];
			$PaymentAmount = $result['PaymentAmount'];
			$Description = $result['Description'];											
			
			$LinkAccount = "<a href=\"./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91\">".$UserName."</a>";										
			$totalAmount += floatval($PaymentAmount);											
			$iLoop++;															
			
			if(($iLoop % 2) == 0)											
				$style = "row1";
			else
			$style = "row2";
			$retOut .= '<tr>';																			
			$retOut .= '<td class="'.$style.'" align="left">'.$PaymentID.'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$LinkAccount.'</td>';																								
			$retOut .= '<td class="'.$style.'" align="left">'.formatDate($PaymentDate, 7).'</td>';
			$retOut .= '<td class="'.$style.'" align="left">'.$Description.'</td>';
			$retOut .= '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';
			$retOut .= '</tr>';
		}		
	}
	$mydb->sql_freeresult();
		$retOut .= '</tbody>
									<tfoot class="sortbottom">
										<tr>
											<td align="right" colspan="4">Total</td>
											<td align="right">'.FormatCurrency($totalAmount).'</td>
										</tr>
									</tfoot>												
								</table>						
							</td>
						</tr>
					</table>';
		
	print $retOut;	
?>