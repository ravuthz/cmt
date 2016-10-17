<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
	require_once("./common/functions.php");
	
?>

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
	
	function submitForm(fl){
		
				fchangesubname.btnSubmit.disabled = true;
				fchangesubname.submit();
		
	}
	
	function trim(str)
	{    if(!str || typeof str != 'string')        
			return str;
			return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
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
		$flag = FixQuotes($flag);
		$radaccExemption = FixQuotes($radaccExemption);
		if(!empty($smt) && isset($smt) && ($smt == "save301") && isset($flag) && $flag==1){		
			$Audit = new Audit();
			$txtVATnumber = FixQuotes($txtVATnumber);
			$sql = "UPDATE tblCustProduct SET VatNumber = '".$txtVATnumber."',IsVatException=".$radaccExemption." WHERE AccID=	$AccountID";
			if($mydb->sql_query($sql)){
					$Description = "Change subscription name of account $AccountID to $txtSubname";
					$Audit->AddAudit($CustomerID, $AccountID, "Change VATnumber ", $Description, $user['FullName'], 1, 11);
					redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=91&TrackID='.$TrackID);
			}else{	
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to change subscription name.", $error['message']);				
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
									 	<td align="left" class="formtitle"><b>CHANGE VAT
										- <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>
										</td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<form name="fchangesubname" method="post" action="./">																				
											<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">			
												<tr>
													<td align="left" nowrap="nowrap">VAT Charge:</td>
													<td align="left">
														<input type="radio" name="radaccExemption" tabindex="78" value="0" <?php if($radaccExemption == 0) print("checked"); ?> checked />YES&nbsp;&nbsp;&nbsp;
                                                    	<input type="radio" name="radaccExemption" tabindex="79" value="1" <?php if($radaccExemption == 1) print("checked"); ?> />NO
													</td>
												</tr>
												<tr>
                                                	<td align="left" nowrap="nowrap">VAT Number:</td>
                                                    <td>
                                                    	<input type="text" name="txtVATnumber" tabindex="1" class="boxenabled" size="70" maxlength="100" />
                                                    </td>
                                                </tr>								
												<tr> 				  
													<td align="center" colspan="2">
													<input type="reset" tabindex="2" name="reset" value="Reset" class="button" />
													<input type="button" tabindex="3" name="btnSubmit" value="Submit" class="button" onClick="submitForm(1);" />						
													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>									
											</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
											<input type="hidden" name="flag" value="1" />
											<input type="hidden" name="pg" value="301" />
											<input type="hidden" name="smt" value="save301" />
                                            <input type="hidden" name="TrackID" value="<?php print $TrackID;?>" />
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
