<?php
	require_once("./common/agent.php");	
	require_once("./common/functions.php");	
	require_once("./common/class.audit.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
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
	#insert / edit credit limit
	if(!empty($smt) && isset($smt) && ($smt == "save103")){
		$TypeID = FixQuotes($TypeID);
		$CreditID = FixQuotes($CreditID);
		$txtComment = FixQuotes($txtComment);
		$txtCredit = FixQuotes($txtCredit);
		$txtType = FixQuotes($txtType);
		
		$mode = FixQuotes($mode);
		if($mode == 1){ # insert
			$sql = "INSERT INTO tblCustProductCredit(AccID, CredID, CredType, Description) 
							VALUES('".$AccountID."', '".$CreditID."', '".$TypeID."', '".$txtComment."')";
		}else{	# update credit limit
			$sql = "UPDATE tblCustProductCredit SET 
								CredID = '".$CreditID."',
								CredType = '".$TypeID."',
								Description = '".$txtComment."'
							WHERE AccID = '".$AccountID."'";
		}
		$Audit = new Audit();
		$comment = "Modify account credit limit to credit type: $txtType; credit limit: $txtCredit; comment: $txtComment";
		$Audit->AddAudit($CustomerID, $AccountID, "Credit rule limit management", $comment, $user['FullName'], 1, 15);
		$mydb->sql_query($sql);
	}

	# get credit rule
	$sql = "select a.Description, t.CredType as 'Type', t.CredTypeID as 'TypeID', r.CredName as 'Name', r.CredID as 'CreditID'
					from tblCustProductCredit a, tblSysCreditMgtTypeCode t, tblCreditLimitRules r
					where a.CredType = t.CredTypeID and a.CredID = r.CredID and a.AccID = ".$AccountID."
					union
					select a.Description, t.CredType as 'Type', t.CredTypeID as 'TypeID', r.CredName as 'Name', r.CredID as 'CreditID'
					from tblCustProductCredit a, tblSysCreditMgtTypeCode t, tblCreditRuleUnpaidPeriod r
					where a.CredType = t.CredTypeID and a.CredID = r.CredID and a.AccID = ".$AccountID."
					union
					select a.Description, t.CredType as 'Type',  t.CredTypeID as 'TypeID', r.CreditRuleInvoice as 'Name', r.CredID as 'CreditID'
					from tblCustProductCredit a, tblSysCreditMgtTypeCode t, tblCreditRuleInvoice r
					where a.CredType = t.CredTypeID and a.CredID = r.CredID and a.AccID = ".$AccountID;		

	if($que = $mydb->sql_query($sql)){				
		while($result = $mydb->sql_fetchrow($que)){
			
				$Description = $result['Description']; 
				$Type = $result['Type']; 
				$TypeID = $result['TypeID']; 
				$CreditID = $result['CreditID']; 
				$Name = $result['Name']; 
				$modi = '<th align="center">Modify</th>';				
				$edit = "[<a href='javascript:doCom(2, ".$TypeID.", ".$CreditID.", \"".$Name."\", \"".$Description."\");'>Edit</a>]";
				$table = "<tr>
										<td class='row2' align='left'>".$Type."</td>
										<td class='row2' align='left'>".$Name."</td>
										<td class='row2' align='left'>".$Description."</td>
										<td class='row2' align='left'>".$edit."</td>
									</tr>";			
		}		
	}	
	$mydb->sql_freeresult();
	$edit = "[<a href='javascript:doCom(1, \"\", \"\", \"\", \"\", \"\");'>Assign</a>]";	
?>

<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript">
	function doCom(index, tid, rid, tn, cm){
		fcreditrule.mode.value = index;
		if(index == 1)
			document.getElementById("d-creditrule").style.display = "block";
		else{
			cid = fcreditrule.CustomerID.value;
			aid = fcreditrule.AccountID.value;
			
				var loading;
		loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
				document.getElementById("d-creditrule").style.display = "block";
				document.getElementById("d-creditrule").innerHTML = loading;
				url = "./php/ajax_creditrule.php?tid="+tid+"&cid="+cid+"&aid="+aid+"&rid="+rid+"&tn="+tn+"&cm="+cm+"&mt=" + new Date().getTime();
				getTranDetail(url, "d-creditrule");
						
		}
	}
	function getDail(val){
		if(val != "")
			location(5, val, "CreditID");
		//alert(val);
	}
	function Validate(){
		if(fcreditrule.TypeID.options[fcreditrule.TypeID.selectedIndex].value == ""){
			alert("Please select credit type");
			fcreditrule.TypeID.focus();
			return;
		}else if((fcreditrule.CreditID.options[fcreditrule.CreditID.selectedIndex].value == "") || (fcreditrule.CreditID.options[fcreditrule.CreditID.selectedIndex].text == "Unknown")){
			alert("Please select credit limit");
			fcreditrule.CreditID.focus();
			return;
		}
		fcreditrule.txtCredit.value = fcreditrule.CreditID.options[fcreditrule.CreditID.selectedIndex].text;
		fcreditrule.txtType.value = fcreditrule.TypeID.options[fcreditrule.TypeID.selectedIndex].text;
		fcreditrule.btnSubmit.disabled = true;
		fcreditrule.smt.value = "save103";
		fcreditrule.submit();
	}
	
</script>
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
							<td valign="top" width="180" rowspan="2">
								<?php include("content.php"); ?>
							</td>
							<td valign="top" align="left">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="60%">
									<tr>
										<td align="left" class="formtitle"><b>MODIFY RULE LIMIT
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>										
										<td align="right"><?php print $edit; ?></td>
									</tr> 				
									<tr>
										<td colspan="2">
											
												<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="103" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">																																	
													<thead>
														<th align="center">Credit type</th>
														<th align="center">Credit rule</th>
														<th align="center">Description</th> 
														<?php print $modi; ?>
													</thead>
													<?php print $table; ?>
												</table>
												
										</td>
									</tr>			
								</table>
							</td>
						</tr>
						<tr>
							<td align="left" valign="top">
								<div id="d-creditrule" style="display:none">
									<form name="fcreditrule" method="post" action="./">
										<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="60%">
											<tr>
												<td align="left" class="formtitle"><b>CREDIT RULE LIMIT</b></td>
												<td align="right"></td>
											</tr> 				
											<tr>
												<td colspan="2">
													<table border="0" cellpadding="3" cellspacing="0" align="left" bgcolor="#feeac2">
														<tr>
															<td align="left" nowrap="nowrap">Credit type:</td>
															<td>
																<select name="TypeID" class="boxenabled" onChange="getDail(this.options[this.selectedIndex].value);">														
																	<option value="">select credit type</option>
																<?php
																	$sql = "SELECT CredTypeID, CredType 
																					from tblSysCreditMgtTypeCode 
																					where CredTypeID not in(
																						select CredType FROM tblCustProductCredit where AccID= ".$AccountID.")
																					order by CredType";
																	// sql 2005
																	
																	$que = $mydb->sql_query($sql);									
																	if($que){
																		while($rst = $mydb->sql_fetchrow($que)){	
																			$CredTypeID = $rst['CredTypeID'];
																			$CredType = $rst['CredType'];																			
																			print "<option value='".$CredTypeID."'>".$CredType."</option>";
																		}
																	}
																	$mydb->sql_freeresult();
																?>
															</select>
															</td>
														</tr>
														<tr>
															<td align="left">Credit limit:</td>
															<td align="left">
																<select name="CreditID" class="boxenabled" tabindex="25" style="width:200px">																	
																</select>
															</td>
														</tr>
														<tr>
															<td align="left">Comment:</td>
															<td align="left">
																<textarea name="txtComment" cols="40" rows="5" class="boxenabled"></textarea>																
															</td>
														</tr>
														<tr>
															<td colspan="2" align="center">
																<input type="button" name="btnSubmit" value="Submit" class="button" onclick="Validate();" />
														</tr>
													</table>
												</td>
											</tr>	
										</table>	
									<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
									<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />											
									<input type="hidden" name="pg" value="103" />
									<input type="hidden" name="smt" value="" />
									<input type="hidden" name="txtType" value="" />
									<input type="hidden" name="txtCredit" value="" />
									<input type="hidden" name="mode" value="" />
									</form>
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
