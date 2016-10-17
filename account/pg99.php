<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/sorttable.js"></script>
<script language="JavaScript" type="text/javascript">
	function changeImage(imgCode, imgSource){
		//alert(imgSource);
			 if(imgCode == 1)
				document.customer.src = imgSource;
		else if(imgCode ==2)
				document.product.src = imgSource;
		else if(imgCode ==3)
				document.finance.src = imgSource;
		else if(imgCode ==4)
				document.usage.src = imgSource;
		else if(imgCode ==5)
				document.audit.src = imgSource;	
	}

	function disableIt(RecID, RecItem){
		if(confirm("Do you wish to delete recurring charge " + RecItem + "?")){
			fdisrec.RecurringID.value = RecID;
			fdisrec.remove.value = "yes";
			fdisrec.ItemName.value = RecItem;
			fdisrec.submit();
		}
	}
</script>
<?php
		
	# =============== Get customer header =====================
		
		$sql = "select c.CustName, sum(d.NationalDeposit) as ncDeposit, sum(d.InternationDeposit) as 'icDeposit',
									sum(d.MonthlyDeposit) as mfDeposit, sum(b.Credit) as Credit, sum(b.Outstanding) as Outstanding 
						from tblCustomer c, tblCustProduct a, tblAccountBalance b, tblAccDeposit d
						where c.CustID = a.CustID and a.AccID = b.AccID and a.AccID = d.AccID and c.CustID=$CustomerID
						group by c.CustName";

		if($que = $mydb->sql_query($sql)){
			if($rst = $mydb->sql_fetchrow($que)){
				$CustName = $rst['CustName'];
				$ncDeposit = $rst['ncDeposit'];
				$icDeposit = $rst['icDeposit'];
				$mfDeposit = $rst['mfDeposit'];
				$Deposit = $ncDeposit + $icDeposit + $mfDeposit;
				$Deposit = FormatCurrency($Deposit);
				$Credit = FormatCurrency($rst['Credit']);
				$Outstanding = FormatCurrency($rst['Outstanding']);
			}
		}
		
		$mydb->sql_freeresult();	
		
		if(($remove == "yes") && ($pg==99)){
			$sql = "UPDATE tblAccRecurringCharge SET StatusID = 1, CloseDate = getDate() WHERE RecurringID = $RecurringID";

			if($mydb->sql_query($sql)){
				$Audit = new Audit();
				$Description = "Delete recurring charge item: $ItemName";
				$Audit->AddAudit($CustomerID, $AccountID, "Delete recurring charge", $Description, $user['FullName'], 1, 3);
			}
		}								
?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#aaaaaa" style="border-collapse:collapse">
		<tr>

			<td valign="top"  height="50">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<tr>
							<td align="left">ID: <b><?php print $CustomerID ?></b></td>						
							<td align="left" colspan="2">Name:<b><?php print $CustName ?></b></td>						
						</tr>
						<tr>
							<td align="left">Deposit: <b><?php print $Deposit; ?></b></td>						
							<td align="left">Balance: <b><?php print $Credit; ?></b></td>						
							<td align="left">Invoice: <b><?php print $Outstanding; ?></b></td>
						</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<!-- Individual customer tab menu -->
				<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bordercolor="#ffffff" align="center">
					 <tr>
						<td align="left" width="15" background="./images/tab_null.gif">&nbsp;</td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onMouseOver="changeImage(1, './images/tab/customer_over.gif');" onMouseOut="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><img src="./images/tab/product_active.gif" name="product" border="0" id="product" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onMouseOver="changeImage(4, './images/tab/usage_over.gif');" onMouseOut="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onMouseOver="changeImage(5, './images/tab/audit_over.gif');" onMouseOut="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>		
					</tr>
				</table>
					<!-- end customer table menu -->			
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->				
					<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
						<tr>
							<td valign="top" width="180">
								<?php include("content.php"); ?>
							</td>
							<td valign="top" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
										<td align="left" class="formtitle"><b>RECURRING CHARGE SUMMARY
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right"></td>
									</tr> 
									<tr>
										<td colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
												<thead>																	
													<th>Item</th>
													<th>Description</th>
													<th>Amount</th>
													<th>Status</th>	
													<th>Close date</th>
													<th>Todo</th>																						
												</thead>
												<tbody>
													<?php
														$sql = "select r.RecurringID, r.RecChargeDes, r.RecChargeFee, i.ItemName, r.StatusID, r.CloseDate
																		from tblAccRecurringCharge r, tlkpInvoiceItem i
																		where r.ItemID = i.ItemID and	r.AccID = $AccountID";

														if($que = $mydb->sql_query($sql)){
															while($result = $mydb->sql_fetchrow()){																
																$RecurringID = $result['RecurringID'];																
																$RecChargeDes = $result['RecChargeDes'];
																$RecChargeFee = $result['RecChargeFee'];
																$ItemName = $result['ItemName'];
																$StatusID = $result['StatusID'];
																$CloseDate = $result['CloseDate'];																
																if($StatusID){
																	$sStatus = "<font color=red><b>Deleted</b></font>";
																	$sEdit = "";														
																}else{
																	$sStatus = "<font color=green><b>Open</b></font>";
																	$sEdit = "<a href=\"javascript:disableIt(".$RecurringID.", '".$ItemName."');\"><img src='./images/Delete.gif' border=0 alt='Delete recurring charge ".$ItemName."' /></a>";
																  $sEdit .= " <a href=\"./?CustomerID=".$CustomerID."&AccountID=".$AccountID."&RecurringID=".$RecurringID."&pg=100\"><img src='./images/Edit.gif' border=0 alt='Edit recurring charge ".$ItemName."' /></a>";
																}
																$iLoop++;															
																if(($iLoop % 2) == 0)
																	$style = "row1";
																else
																	$style = "row2";
																print '<tr>';	
																print '<td class="'.$style.'" align="left">'.$ItemName.'</td>';
																print '<td class="'.$style.'" align="left">'.$RecChargeDes.'</td>';
																print '<td class="'.$style.'" align="right">'.FormatCurrency($RecChargeFee).'</td>';							
																print '<td class="'.$style.'" align="left">'.$sStatus.'</td>';
																print '<td class="'.$style.'" align="left">'.formatDate($CloseDate, 3).'</td>';
																print '<td class="'.$style.'" align="left">'.$sEdit.'</td>';
																print '</tr>';
															}
														}
														$mydb->sql_freeresult();	
													?>
												</tbody>												
											</table>
											<form name="fdisrec" method="post" action="./">
												<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
												<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
												<input type="hidden" name="RecurringID" value="" />
												<input type="hidden" name="ItemName" value="" />
												<input type="hidden" name="remove" value="" />
												<input type="hidden" name="pg" value="99" />
											</form>
										</td>
									</tr>
								</table>
							</td>
						</tr>						
					</table>	
				</td>
			</tr>			
</table>
<?php
# Close connection
$mydb->sql_close();
?>
