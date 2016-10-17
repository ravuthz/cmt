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
					<td align="left" class="formtitle"><b>OPEN INVOICE REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>																	
								<th align="center">No</th>
								<th align="center">Invoice</th>
								<th align="center">Customer</th>
								<th align="center">Account</th>
								<th align="center">Status</th>								
								<th align="center">Due date</th>
								<th align="center">Total Amount</th>
								<th align="center">Unpaid</th>																						
							</thead>
							<tbody>
								<?php
										$sql = " select c.CustID, c.CustName, i.InvoiceID, i.InvoiceAmount, i.UnpaidAmount, i.DueDate, 
																	 a.AccID, a.UserName, a.StatusID																	
														from tblCustomerInvoice i, tblCustomer c, tblCustProduct a, tblTarPackage t, tlkpService s																	
														where c.CustID = a.CustID and a.PackageID = t.PackageID and t.ServiceID = s.ServiceID
																	and i.AccID = a.AccID and i.UnpaidAmount > 0																		
														order by i.DueDate, i.UnpaidAmount desc";

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
													$stbg = $bgUnactivate;
													$stfg = $foreUnactivate;
													$stwd = "Inactive";
													break;
												case 1:
													$stbg = $bgActivate;
													$stfg = $foreActivate;
													$stwd = "Activ";
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
											}
											$linkInv = "<a href='./finance/screeninvoice.php?CustomerID=".$CustID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
											$linkCust = "<a href='./?CustomerID=".$CustID."&pg=10'>".$CustName."</a>";
											$linkAcct = "<a href='./?CustomerID=".$CustID."&AccountID=".$AccID."&pg=91'>".$UserName."</a>";
											$totalAmount += floatval($TotalAmount);
											$totalUnpaidAmount += floatval($UnpaidAmount);
											$iLoop++;															
											
											//if(($iLoop % 2) == 0)
											if(datediff($DueDate)>0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';								
											print '<td class="'.$style.'" align="left">'.$linkInv.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkCust.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkAcct.'</td>';	
											print '<td align="center" bgcolor="'.$stbg.'">
															<font color="'.$stfg.'"><b>'.$stwd.'</b></font>
														 </td>';													
											print '<td class="'.$style.'" align="right">'.formatDate($DueDate, 3).'</td>';
											print '<td class="'.$style.'" align="right">'.FormatCurrency($TotalAmount).'</td>';
											print '<td class="'.$style.'" align="right">'.FormatCurrency($UnpaidAmount).'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>
							<tfoot class="sortbottom">
								<tr>
									<td align="right" colspan="6">Total</td>
									<td align="right"><?php print FormatCurrency($totalAmount); ?></td>
									<td align="right"><?php print FormatCurrency($totalUnpaidAmount); ?></td>
								</tr>
							</tfoot>												
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>						
</table>