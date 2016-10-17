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

	function submitForm(){
		Amount = faddcharge.Amount;
		if(Trim(Amount.value == "")){
			alert("Please enter charge amount");
			Amount.focus();
			return;
		}else if(!isNumber(Trim(Amount.value))){
			alert("charge amount must be a number");
			Amount.focus();
			return;
		}else if(Number(Trim(Amount.value)) <= 0){
			alert("charge amount must be greater than 0");
			Amount.focus();
			return;		
		}else{
			faddcharge.btnSubmit.disabled = true;
			faddcharge.submit();
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
		
		if(!empty($smt) && isset($smt) && ($smt == "save100")){		
			$Audit = new Audit();
			$Amount = FixQuotes($Amount);
			$Description = FixQuotes($Description);						
			$txtExpire = FixQuotes($txtExpire);
			$error = false;
			if(!empty($txtExpire)){
				if(datediff($txtExpire) > 0){
					$retOut = $myinfo->warning("Expired date must later than today");
					$error = true;
				}
			}
			if(!$error){
				$sql = "UPDATE tblAccRecurringCharge SET
										RecChargeDes = '".$Description."',
										RecChargeFee = '".$Amount."',
										CloseDate = '".$txtExpire."'
								WHERE RecurringID = $RecurringID
								";

				if($mydb->sql_query($sql)){
					if(!empty($txtExpire))
						$expire = "expire on ".formatDate($txtExpire, 6);
					$Description = "Update recurring charge to $txtCharge amount: ".FormatCurrency($Amount)." $expire. Description: $Description";
					$Audit->AddAudit($CustomerID, $AccountID, "Update recurring charge", $Description, $user['FullName'], 1, 3);
					redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=99');
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to update recurring charge.", $error['message']);
				}
			}
		}
		
		$sql = "SELECT r.RecChargeDes, r.RecChargeFee, r.CloseDate, i.ItemName 
						FROM tblAccRecurringCharge r, tlkpInvoiceItem i
						WHERE r.ItemID = i.ItemID and RecurringID = $RecurringID";
		if($que = $mydb->sql_query($sql)){
			if($result = $mydb->sql_fetchrow($que)){
				$ItemName = $result['ItemName'];
				$RecChargeDes = $result['RecChargeDes'];
				$RecChargeFee = $result['RecChargeFee'];
				$CloseDate = $result['CloseDate'];
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
										<td align="left" class="formtitle"><b>EDIT RECURRING CHARGE
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right"></td>
									</tr> 
									<tr>
										<td colspan="2">
											<form name="faddcharge" method="post" action="./" onSubmit="return false;">
												<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" bgcolor="#feeac2">													
													<tr>
														<td align="left" nowrap="nowrap">Charge item:</td>
														<td align="left">
															<input type="text" name="ItemName" class="boxdisabled" readonly="true" value="<?php print $ItemName;?>" />
														</td>					
													</tr>													
													<tr>
														<td align="left" nowrap="nowrap">Amount:</td>
														<td align="left">
															<input type="text" name="Amount" tabindex="2" class="boxenabled" size="20" value="<?php print $RecChargeFee; ?>" />(Exclude VAT)
														</td>					
													</tr>
													<tr>
														<td align="left">Expiry date:</td>
														<td align="left">
															<input type="text" tabindex="3" name="txtExpire" class="boxenabled" size="27" maxlength="30" value="<?php print formatDate($CloseDate, 5); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?faddcharge|txtExpire', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
														</td>
													</tr>																										
													<tr>
														<td align="left" valign="top">Description:</td>
														<td align="left">
															<textarea name="Description" cols="38" rows="5" tabindex="7" class="boxenabled"><?php print trim($RecChargeDes); ?></textarea>
														</td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>								
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="8" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="9" name="btnSubmit" value="Save" class="button" onClick="submitForm();" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>
												
												</table>
											<select name="tmpInvoice" style="display:none">
												<option value="0"></option>
												<?php print $opt2; ?>
											</select>	
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
											<input type="hidden" name="RecurringID" value="<?php print $RecurringID; ?>">
											<input type="hidden" name="pg" value="100" />
											<input type="hidden" name="smt" value="save100" />
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
