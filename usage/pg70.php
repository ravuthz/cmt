<?php
	require_once("./common/agent.php");	
	require_once("./common/class.invoice.php");
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
<script language="JavaScript" type="text/javascript" src="./javascript/ajax_location.js"></script>
<script language="JavaScript" type="text/javascript" src="./javascript/ajax_gettransaction.js"></script>
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
	
	function getCycle(index){
		if(index == 0){
			faccount.CycleID.options[0] = new Option("Current cycle id");
			faccount.CycleID.options[0].value = "0";			
		}else{
			location(6, faccount.AccountID.options[index].value, "CycleID");
		}
	}
	
	function viewreport(){
		aid = faccount.AccountID.value;
		cid = faccount.CustomerID.value;
		yid = faccount.CycleID.value;
		
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-usage").innerHTML = loading;
			url = "./php/ajax_usage_summary.php?aid="+aid+"&cid="+cid+"&yid="+yid+"&tm="+ new Date().getTime();			
			getTranDetail(url, "d-usage");
		
	}
	
	function downloadreport(){
		aid = faccount.AccountID.value;
		cid = faccount.CustomerID.value;
		yid = faccount.CycleID.value;
		
		url = "./export/usagesummary.php?aid="+aid+"&cid="+cid+"&yid="+yid+"&type=csv";
		window.open(url, "new", "scrollbars = 1");
//		getTranDetail(url, "d-usage");
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
		
		# function build usage for each account
		
						
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
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onMouseOver="changeImage(2, './images/tab/product_over.gif');" onMouseOut="changeImage(2, './images/tab/product.gif');" /></a></td>
						
						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onMouseOver="changeImage(3, './images/tab/finance_over.gif');" onMouseOut="changeImage(3, './images/tab/finance.gif');" /></a></td>
						
						<td align="left" width="85"><img src="./images/tab/usage_active.gif" name="usage" border="0" id="usage" /></td>
						
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
						<td width="200" align="left" valign="top">
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
								<tr>
									<td align="left" class="formtitle"><b>VIEWING OPTION</b></td>																		
								</tr>
								<tr>
									<td>
										<form name="faccount" method="post" action="./">
											<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%" bgcolor="#feeac2">
												<tr>
													<td align="left" nowrap="nowrap">Select account:</td>
													<td align="left">
														<select name="AccountID" onchange="getCycle(this.selectedIndex);">
															<option value="0">All accounts</option>
															<?php
																$sql = "SELECT AccID, UserName = Case
																										When StatusID = 1 then UserName + '_' + convert(varchar,accid) + ' ' + '(Active)'
																										When StatusID = 4 then UserName + '_' + convert(varchar,accid) + ' ' + '(Close)'
																										Else UserName + '_' + convert(varchar,accid) + ' ' + '(InActive)'
																										End
																										
																										 from tblCustProduct where CustID = $CustomerID
																										 order by StatusID, tblCustProduct.UserName, AccID desc";
																if($que = $mydb->sql_query($sql)){
																	while($result = $mydb->sql_fetchrow($que)){
																		$dbAccountID = $result['AccID'];
																		$UserName = $result['UserName'];
																		if(intval(dbAccountID) == intval($AccountID))
																			$sel = "selected";
																		else
																			$sel = "";
																		print "<option value='".$dbAccountID."' $sel>".$UserName."</option>";
																	}
																}
															?>
														</select>
													</td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Select cycle:</td>
													<td align="left">
														<select name="CycleID">
															<option value="0">Current cycle</option>
															
														</select>
													</td>
												</tr>
												<tr>													
													<td align="left" colspan="2">
														<input type="button" name="btnView" value="View" onclick="viewreport();" class="button" />
														<input type="button" name="btnView" value="Download" onclick="downloadreport();" class="button" />
													</td>
												</tr>
											</table>
										<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
										</form>
									</td>
								</tr>
							</table>	
						</td>
						<td valign="top" align="left">
							<div id="d-usage">
							</div>						
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
