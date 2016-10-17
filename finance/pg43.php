<?php
	require_once("./common/agent.php");	
	require_once("./common/class.payment.php");
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
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
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
	
	function doPrint(InvoiceID, CustomerID){
		filename = "printinvoice.php?CustomerID="+CustomerID+"&InvoiceID="+InvoiceID;
		var docprint=window.open(filename);		
		docprint.focus();
	}
	function getReceipt(){
		cid = fpayment.CustomerID.value;
		acc = fpayment.tAcc.value;
		
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-payment").innerHTML = loading;
			url = "./php/ajax_payment.php?cid="+cid+"&acc="+acc+"&mt="+new Date().getTime();
			getTranDetail(url, "d-payment");
		
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=10"><img src="./images/tab/customer.gif" name="customer" border="0" id="customer" onmouseover="changeImage(1, './images/tab/customer_over.gif');" onmouseout="changeImage(1, './images/tab/customer.gif');"/></a></td>						
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><img src="./images/tab/finance_active.gif" name="finance" border="0" id="finance" /></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');" /></a></td>						
						
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
										<td align="left" class="formtitle"><b>PAYMENT HISTORY</b></td>
										<td align="right">
											<form name="fpayment" onsubmit="return false;">
												<table border="0" cellpadding="0" cellspacing="0" align="right">
													<tr>
														<td>
															<input type="text" name="tAcc" size="20" class="boxsearch" value="<?php print $tAcc; ?>" />
															<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />	
														</td>
														<td>
															<button name="btnSearch" onClick="getReceipt();" class="invisibleButtons" style="width:79; height:22">
																<img src="./images/go.gif" border="0" alt="Search" class="invisibleButtons" width="79" height="22">
															</button>
														</td>
													</tr>
												</table>																																															
											</form>
										</td>
									</tr> 
									<tr>
										<td colspan="2">
										<div id="d-payment">
											<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
												<thead>	
													<th>Rec#</th>				
													<th>Inv#</th>
													<th>AccID</th>													
													<th>Username</th>													
													<th nowrap="nowrap">IssueDate</th>													
													<th>Amount</th>
													<th nowrap="nowrap">Mode</th>
													<th>Cashier</th>										
													<th>PaidDate</th>			
												</thead>
												<tbody>
													<?php
														$Finance = new Payment();
														$que = $Finance->GetPaymentHistory($CustomerID);
														$iLoop = 1;
														$Total = 0;
														while($result = $mydb->sql_fetchrow()){
															$InvoiceID = $result['InvoiceID'];
															$AccID = $result['AcctID'];
															$PaymentID = $result['PaymentID'];
															$PaymentAmount = $result['PaymentAmount'];
															$PaymentDate = $result['paiddate'];
															$IssueDate = $result['IssueDate'];
															$Cashier = $result['Cashier'];
															$PaymentMode = $result['PaymentMode'];
															$TransactionName = $result['TransactionName'];
															$UserName = $result['UserName'];
															$Invoice = "" ;
															if($InvoiceID != 0)
															$Invoice = "<a href='./finance/screeninvoice.php?CustomerID=".$CustomerID."&InvoiceID=".$InvoiceID."' target='_blank'>".$InvoiceID."</a>";
															$Receipt = "<a href='./finance/receipt.php?CustomerID=".$CustomerID."&PaymentID=".$PaymentID."' target='_blank'>".$PaymentID."</a>";																
															$Type = "DN";
															$iLoop++;
															$Total += floatval($PaymentAmount); 
															if(($iLoop % 2) == 0)
																$style = "row1";
															else
																$style = "row2";
															print '<tr>';	
															print '<td class="'.$style.'" align="left">'.$Receipt.'</td>';
															print '<td class="'.$style.'" align="left">'.$Invoice.'</td>';
															print '<td class="'.$style.'" align="left">'.$AccID.'</td>';
															print '<td class="'.$style.'" align="left">'.$UserName.'</td>';
															print '<td class="'.$style.'" align="right">'.formatDate($IssueDate, 1).'</td>';																														
															print '<td class="'.$style.'" align="right">'.FormatCurrency($PaymentAmount).'</td>';
															print '<td class="'.$style.'" align="left">'.$PaymentMode.'</td>';	
															print '<td class="'.$style.'" align="left">'.$Cashier.'</td>';									
															print '<td class="'.$style.'" align="left">'.formatDate($PaymentDate, 7).'</td>';								
															print '</tr>';
														}
														$mydb->sql_freeresult();	
													?>
												</tbody>
												<tfoot>
													<tr class="sortbottom">
													<td colspan="5" align="right">Total</td>
													<td align="right"><?php print FormatCurrency($Total);?></td>
													<td align="right" colspan="3">&nbsp;</td>
													</tr>
												</tfoot>	
											</table>
										</div>	
											
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
