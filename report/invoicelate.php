<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	
?>	
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>INVOICE LATE PAYMENT REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">AccID</th>
								<th align="center">Account</th>
								<th align="center">Status</th>
								<th align="center">Subscription</th>
								<th align="center">Invoice</th>
								<th align="center">Issue Date</th>
								<th align="center">Net Amount</th>
								<th align="center">VAT</th>
								<th align="center">Total Amount</th>
								<th align="center">Unpaid</th>																			
							</thead>
							<tbody>
								<?php
										$sql = "select a.AccID, a.CustID, a.UserName, a.SubscriptionName, a.StatusID, i.InvoiceAmount, 
																		i.VATAmount, i.NetAmount,	i.UnpaidAmount, i.IssueDate, i.InvoiceID
														from tblCustProduct a, tblCustomerInvoice i
														where a.AccID = i.AccID 
															and convert(varchar, i.DueDate, 112) <= convert(varchar, getDate(), 112)
															and i.UnpaidAmount > 0 ORDER BY i.IssueDate ";

									if($que = $mydb->sql_query($sql)){										
										$iLoop = 0;
										while($result = $mydb->sql_fetchrow()){																
											$AccID = $result['AccID'];
											$CustID = $result['CustID'];																
											$UserName = $result['UserName'];
											$SubscriptionName = $result['SubscriptionName'];											
											$StatusID = $result['StatusID'];																
											$InvoiceAmount = $result['InvoiceAmount'];
											$VATAmount = $result['VATAmount'];
											$NetAmount = $result['NetAmount'];
											$UnpaidAmount = $result['UnpaidAmount'];
											$IssueDate = $result['IssueDate'];
											$InvoiceID = $result['InvoiceID'];
											switch($StatusID){
												case 0:
													$stbg = $bgUnactivate;
													$stfg = $foreUnactivate;
													$stwd = "Inactive";
													break;
												case 1:
													$stbg = $bgActivate;
													$stfg = $foreActivate;
													$stwd = "Active";
													$link = "<a href='./?CustomerID=".$CustID."&AccountID=".AccID."&md=2&cst=1&uname=".$UserName."&pg=249'>Close</a>";													
													break;
												case 2:
													$stbg = $bgLock;
													$stfg = $foreLock;
													$stwd = "Barred";
													break;
												case 3:
													$stbg = $bgClose;
													$stfg = $foreClose;
													$stwd = "Closed";
													break;
												case 4:
													$stbg = $bgClose;
													$stfg = $foreClose;
													$stwd = "Closed";
													break;
											}
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											$linkAcct = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";	
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';								
											print '<td class="'.$style.'" align="left">'.$AccID.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';														
											print '<td class="'.$style.'" align="left">'.$SubscriptionName.'</td>';		
											print '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';						
											print '<td class="'.$style.'" align="left">'.$InvoiceID.'</td>';											
											print '<td class="'.$style.'" align="left">'.FormatDate($IssueDate, 3).'</td>';
											print '<td class="'.$style.'" align="left">'.FormatCurrency($NetAmount).'</td>';
											print '<td class="'.$style.'" align="left">'.FormatCurrency($VATAmount).'</td>';
											print '<td class="'.$style.'" align="left">'.FormatCurrency($InvoiceAmount).'</td>';
											print '<td class="'.$style.'" align="left">'.FormatCurrency($UnpaidAmount).'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>																	
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>