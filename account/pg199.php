<?php
	require_once("./common/agent.php");	
	require_once("./common/class.audit.php");	
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
<script language="javascript" type="text/javascript" src="./javascript/date.js"></script>
<script type="text/javascript" src="./javascript/datetimepicker.js"></script>
<script type="text/javascript" src="./javascript/function.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
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
		//alert("test");
		if(foneadjust.txtMailBox.value == ""){
			alert("Please enter number of mailbox");
			foneadjust.txtMailBox.focus();
			return;
		}else if(!isValidMail(foneadjust.txtMailBox.value)){
			alert("Please enter mailbox address");
			foneadjust.txtMailBox.focus();
			return;
		}
		else if(foneadjust.txtPerMailBoxCharge.value == ""){
			alert("Please enter charge per mailbox rate");
			foneadjust.txtPerMailBoxCharge.focus();
			return;
		}else if(!isNumber(foneadjust.txtPerMailBoxCharge.value)){
			alert("Please enter valid per mailbox rate");
			foneadjust.txtPerMailBoxCharge.focus();
			return;
		}
		else if(foneadjust.txtEffectiveDate.value == ""){
			alert("Please enter effective date");
			foneadjust.txtEffectiveDate.focus();
			return;
		}else{
			foneadjust.btnSubmit.disabled = true;
			foneadjust.submit();
		}
	}
	//Get time
	  function GetCredit(id,target){
		 var myurl="./administration/ajax_infomation.php?id="+id+"&choice=credit"+"&ms="+new Date().getTime();;
		// alert(myurl);
		 
		sendHttpRequest(myurl,'ApplyInfo',target,true);  
	  }
	  
	  //Call By sendHttpRequest(url,'TimeBandInfo',true);
	  function ApplyInfo(documentdata,target,respXml){
			var option;
			var mydocument=documentdata;
			//alert(documentdata);
			
			if(respXml==true){
				option=mydocument.getElementsByTagName("option");
				//alert(option.length);
				var selectControl=document.getElementById(target);
				//alert(target);
				selectControl.options.length=0;	
				selectControl.options[0]=new Option("-- Select --");
			    selectControl.options[0].value=0;
				for(var loopindex=0;loopindex<option.length;loopindex++){
						
						selectControl.options[loopindex+1]=new Option(option[loopindex].firstChild.data);
						selectControl.options[loopindex+1].value=option[loopindex].getAttribute("value");		
						//alert(option[loopindex].getAttribute("value"));
				}
			}else{
				// 	do nothing
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
		if($chIsPercentage!='1'){
			$chIsPercentage=0;
		}		
		
		if($chIsExtraMail!='1'){
			$chIsExtraMail=0;
		}			
		if(!empty($smt) && isset($smt) && ($smt == "save199")){		
		
			$Audit = new Audit();
			$txtMailBox = stripslashes(FixQuotes($txtMailBox));
			$txtPerMailBoxCharge = doubleval($txtPerMailBoxCharge);
			$txtEndDate=stripslashes(FixQuotes($txtEndDate));
		    $chIsExtraMail=intval($chIsExtraMail);
			$txtEffectiveDate=stripslashes(FixQuotes($txtEffectiveDate));
			
			
			if($op == "add"){
				$error = false;			
				if(!$error){
					# Get current adjustment				
							if(trim($txtEndDate)==""){
									$sql = "INSERT INTO tblIspAccAdditionalMailList
	(AccID, MailBox, EffectiveDate, EndDate, PerMailBoxCharge,IsExtraMail)
	
	VALUES ($AccountID, '$txtMailBox', '$txtEffectiveDate', NULL, $txtPerMailBoxCharge,$chIsExtraMail)";
								}else{
									$sql = "INSERT INTO tblIspAccAdditionalMailList
	(AccID, MailBox, EffectiveDate, EndDate, PerMailBoxCharge,IsExtraMail)
	
	VALUES ($AccountID, '$txtMailBox', '$txtEffectiveDate', '$txtEndDate', $txtPerMailBoxCharge,$chIsExtraMail)";
								}						
		
							if($mydb->sql_query($sql)){
								$Audit->AddAudit($CustomerID, $AccountID, "Additional mail box", $Description, $user['FullName'], 1, 12);
								redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=199');
								
							}else{	
								$error = $mydb->sql_error();
								$retOut = $myinfo->error("Failed to add account mail box.", $error['message']);				
							}									
				}		
			}elseif($op=="edit"){
				$error = false;			
				if(!$error){
					# Get current adjustment				
							if(trim($txtEndDate)==""){
$sql = "UPDATE tblIspAccAdditionalMailList
SET AccID = $AccountID, MailBox = '$txtMailBox', EffectiveDate = '$txtEffectiveDate', EndDate = NULL, IsExtraMail = $chIsExtraMail,		PerMailBoxCharge = $txtPerMailBoxCharge
WHERE MailBoxID = $mailboxid";
							}else{
								$sql = "UPDATE tblIspAccAdditionalMailList
SET AccID = $AccountID, MailBox = '$txtMailBox', EffectiveDate = '$txtEffectiveDate', EndDate = '$txtEndDate',  IsExtraMail = $chIsExtraMail, PerMailBoxCharge = $txtPerMailBoxCharge
WHERE MailBoxID = $mailboxid";
							}						
		
							if($mydb->sql_query($sql)){
								$Audit->AddAudit($CustomerID, $AccountID, "account mail box", "Add new additional mail box for : account $AccountID", $user['FullName'], 1, 12);
								redirect('./?CustomerID='.$CustomerID.'&AccountID='.$AccountID.'&pg=199');
								
							}else{	
								$error = $mydb->sql_error();
								$retOut = $myinfo->error("Failed to update account mail box.", $error['message']);				
							}									
					}		
				}									
		}		
		elseif (isset($op) && $op == "edit" && isset($mailboxid)){
			$sql = "select * from tblIspAccAdditionalMailList 
			where AccID = $AccountID and MailBoxID = $mailboxid";
				$query=$mydb->sql_query($sql);
				while($row=$mydb->sql_fetchrow($query)){
					$txtMailBoxID = intval($row["MailBoxID"]);
					$txtMailBox = stripslashes($row["MailBox"]);
					$txtPerMailBoxCharge = FormatLength(doubleval($row["PerMailBoxCharge"]),2);
					$txtEffectiveDate = formatDate(stripslashes($row["EffectiveDate"]), 6);
					$txtEndDate = formatDate(stripslashes($row["EndDate"]), 6);	
					$chIsExtraMail=intval($row["IsExtraMail"]);																							
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
									 	<td align="left" class="formtitle"><b>Account Mail Box - <?php print $aSubscriptionName." (".$aUserName.")"; ?></b>										</td>
										<td align="right"></td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<form name="foneadjust" method="post" action="./" onSubmit="return false;">
											<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa">																								
												<tr>
													<td width="19%" align="left" nowrap="nowrap">Mail Box:</td>
													<td width="81%" align="left"><input type="text" size="27" name="txtMailBox" class="boxenabled" value="<?php print($txtMailBox);?>" />												     
												  &nbsp;&nbsp;&nbsp;&nbsp;Is Additional mail box: <input type="checkbox" name="chIsExtraMail" value="1" <?php if($chIsExtraMail==1) print " checked='CHECKED'";?> /></td>
												</tr>
												
												<tr>
												  <td align="left" nowrap="nowrap">Charge/Mail: </td>
												  <td align="left"><input type="text" size="27" name="txtPerMailBoxCharge" class="boxenabled" value="<?php print($txtPerMailBoxCharge);?>" /></td>
											  </tr>
												<tr>
												  <td align="left" nowrap="nowrap">Effective Date:</td>
												  <td align="left"><input id="txtEffectiveDate" style="width:170px;" name="txtEffectiveDate" class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEffectiveDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEffectiveDate));
						?>" type="text" size="25"><a href="javascript:NewCal('txtEffectiveDate','yyyymmdd',true,12)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
											  </tr>
												<tr>
													<td align="left" nowrap="nowrap">End Date: </td>
													<td align="left"><input id="txtEndDate" name="txtEndDate"  style="width:170px;" class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEndDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEndDate));
						?>"   type="text" size="25" /><a href="javascript:NewCal('txtEndDate','yyyymmdd',true,12)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
												</tr>
												<tr>
												  <td align="left" nowrap="nowrap">&nbsp;</td>
												  <td align="left">&nbsp;</td>
											  </tr>
																				
													<tr> 				  
													<td align="center" colspan="2">
														<input type="reset" tabindex="4" name="reset" value="Reset" class="button" />
														<input type="submit" tabindex="5" name="btnSubmit" value="Submit" class="button" onClick="submitForm();" />													</td>
												 </tr>
												 <?php
														if(isset($retOut) && (!empty($retOut))){
															print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
														}
													?>									
											</table>
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
											<input type="hidden" name="AccountID" value="<?php print $AccountID; ?>" />
											<input type="hidden" name="pg" value="199" />
											
											<input type="hidden" name="op" value="<?php 
											if(!isset($op)){echo 'add';}else{echo $op;}?>" />
											<input type="hidden" name="mailboxid" value="<?php echo $mailboxid; ?>" />
											<input type="hidden" name="smt" value="save199" />
											</form>										</td>
									</tr>		
									<tr>
									 	<td align="left" class="formtitle"><b>Account Mail Box Information</b>										</td>
										<td align="right"></td>
									</tr>
									<tr>
									  <td colspan="2" align="left" class="formtitle">
									  <table width="100%" border="1"  bordercolor="#666666" style="border-collapse:collapse" bgcolor="#FFFFFF">
                                        <tr >
                                          <td width="6%"  class="formtitle"><div align="center"><strong>ID</strong></div></td>
                                          <td width="14%"  class="formtitle"><div align="center"><strong>Mail Box</strong></div></td>
                                          <td width="20%"  class="formtitle"><div align="center"><strong>Charge/mail </strong></div></td>
                                          <td width="27%"  class="formtitle"><div align="center"><strong>Effective Date </strong></div></td>
                                          <td width="28%"  class="formtitle"><div align="center"><strong>End Date</strong></div></td>
										  
                                          <td width="28%"  class="formtitle"><div align="center"><strong>Is Extra Mail </strong></div></td>
                                          <td width="5%"  class="formtitle"><div align="center"><strong>Edit</strong></div></td>
                                        </tr>
										<?php
											$sql = "select * from tblIspAccAdditionalMailList where AccID = $AccountID";
											$query=$mydb->sql_query($sql);
											while($row=$mydb->sql_fetchrow($query)){
												$MailBoxID = intval($row["MailBoxID"]);
												$MailBox = stripslashes($row["MailBox"]);
												$PerMailBoxCharge = FormatLength(doubleval($row["PerMailBoxCharge"]),2);
												$EffectiveDate = formatDate(stripslashes($row["EffectiveDate"]), 6);
												$EndDate = formatDate(stripslashes($row["EndDate"]), 6);
												
												
										?>
                                        <tr>
                                          <td><div align="center"><?php echo $MailBoxID?></div></td>
                                          <td><div align="center"><?php echo $MailBox?></div></td>
                                          <td><div align="center"><?php echo $PerMailBoxCharge?></div></td>
                                          <td><div align="center"><?php echo $EffectiveDate?></div></td>
                                          <td><div align="center"><?php echo $EndDate?></div></td>
										  
									      <td><div align="center"><?php
										  	if($row['IsExtraMail']==0){
														echo "No";	
														}else{
														echo "Yes";
														}
														?>
														</div></td>
									      <td><?php echo "<a href='?CustomerID=".$CustomerID."&amp;AccountID=".$AccountID."&amp;pg=199&amp;op=edit&amp;mailboxid=".$MailBoxID."'><img src='./images/Edit.gif' border='0'></a>";?></td>
                                        </tr>
										  <?php 
										  
											 }
										  ?>
									  </table>									  </td>
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
