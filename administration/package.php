
<?php

	require_once("./common/agent.php");	
	require_once("function.php");
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
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 1011)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		$txtName=stripslashes(FixQuotes($txtName));
		$txtThreshold=floatval($txtThreshold);
		$txtDepositAmount=floatval($txtDepositAmount);
		$cmbServiceID=intval($cmbServiceID);
		$cmbCycleID=intval($cmbCycleID);
		$txtRegistrationFee=doubleval($txtRegistrationFee);
		$txtConfigurationFee=doubleval($txtConfigurationFee);
		$txtCPEFee=doubleval($txtCPEFee);
		$txtCycleFee=doubleval($txtCycleFee);
		$txtISDNFee=doubleval($txtISDNFee);
		$txtSpecialNumber=doubleval($txtSpecialNumber);

		
		
		if($op=="add"){
		
			if(CheckExistingPackage($txtName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tblTarPackage(TarName, Threshold, DepositAmount, ServiceID, CycleID,Status,RegistrationFee,ConfigurationFee, CPEFee,CycleFee,ISDNFee,SpecialNumber, CreatedDate) VALUES('".$txtName."','".$txtThreshold."','".$txtDepositAmount."','".$cmbServiceID."','".$cmbCycleID."',1,'".$txtRegistrationFee."','".$txtConfigurationFee."','".$txtCPEFee."','".$txtCycleFee."','".$txtISDNFee."','".$txtSpecialNumber."',GetDate())";
				//print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Add Package","Add package Packge Name: $txtName",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new package.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Package","Add package Packge Name: $txtName",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new package.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistingPackage($txtName,true,$packageid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE tblTarPackage SET TarName='".$txtName."', Threshold='".$txtThreshold."', DepositAmount='".$txtDepositAmount."', CycleID='".$cmbCycleID."', RegistrationFee='".$txtRegistrationFee."', ConfigurationFee='".$txtConfigurationFee."', CPEFee='".$txtCPEFee."', CycleFee='".$txtCycleFee."', ISDNFee='".$txtISDNFee."', SpecialNumber='".$txtSpecialNumber."' WHERE  packageid='".intval($packageid)."'";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Update Package","Update Package Name: $txtName, ID: $packageid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully update package.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Update Package","Update Package Name: $txtName, ID: $packageid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update package.", $error['message']);
				}
				$op="add";
				$txtName="";
				$txtThreshold="0";
				$txtDepositAmount="0.0";
				$cmbServiceID=1;
				$cmbCycleID=1;
				$txtRegistrationFee="";
				$txtConfigurationFee="";
				$txtCPEFee="";
				$txtCycleFee="";
				$txtISDNFee="";
				$txtSpecialNumberFee="";
				$txtFreeUsgae="";
			}
				
				
		}
	}elseif(isset($packageid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblTarPackage WHERE PackageID='".intval($packageid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtName=stripslashes($row['TarName']);
				$txtThreshold=stripslashes($row['Threshold']);
				$txtDepositAmount=stripslashes($row['DepositAmount']);
				$cmbServiceID=intval($row['ServiceID']);
				$cmbCycleID=intval($row['CycleID']);
				$txtRegistrationFee=doubleval($row['RegistrationFee']);
				$txtConfigurationFee=doubleval($row['ConfigurationFee']);
				$txtCycleFee=doubleval($row['CycleFee']);
				$txtCPEFee=doubleval($row['CPEFee']);
				$txtISDNFee=doubleval($row['ISDNFee']);
				$txtSpecialNumber=doubleval($row['SpecialNumber']);
				
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblTarPackage set status='0' WHERE PackageID='".$packageid."'";
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Deactivate Package","Deactivate package ID: $packageid",$user["FullName"],1,15);		
					$retOut = $myinfo->info("Successfully deactivate package.");
				}else{
					$error = $mydb->sql-error();
					$Audit->AddAudit(0,0,"Deactivate Package","Deactivate package ID: $packageid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate package.", $error['message']);
				}
				
				$op="add";
				$txtName="";
				$txtThreshold="0";
				$txtDepositAmount="0.0";
				$cmbServiceID=1;
				$cmbCycleID=1;
				$txtRegistrationFee="";
				$txtConfigurationFee="";
				$txtCPEFee="";
				$txtCycleFee="";
				$txtISDNFee="";
				$txtSpecialNumberFee="";
			
		}elseif($op=="activate"){
			$sql="update tblTarPackage set status='1' WHERE PackageID='".$packageid."'";
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Activate Package","Activate package ID: $packageid",$user["FullName"],1,15);		
					$retOut = $myinfo->info("Successfully activate package.");
				}else{
					$error = $mydb->sql-error();
					$Audit->AddAudit(0,0,"Activate Package","Activate package ID: $packageid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to activate package.", $error['message']);
				}
				
				$op="add";
				$txtName="";
				$txtThreshold="0";
				$txtDepositAmount="0.0";
				$cmbServiceID=1;
				$cmbCycleID=1;
				$txtRegistrationFee="";
				$txtConfigurationFee="";
				$txtCPEFee="";
				$txtCycleFee="";
				$txtISDNFee="";
				$txtSpecialNumberFee="";
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate package: " + code + "?"))
			window.location = "./?pg=1011&op=deactivate&packageid=" + id;
	}
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate package: " + code + "?"))
			window.location = "./?pg=1011&op=activate&packageid=" + id;
	}
	function ValidateForm(){

		mName = frml.txtName;
		mThreshold=frml.txtThreshold;
		
		mDepositAmount=frml.txtDepositAmount;
		txtISDNFee=frml.txtISDNFee;
		txtSpecialNumber=frml.txtSpecialNumber;
		txtCycleFee=frml.txtCycleFee;
		txtConfigurationFee=frml.txtConfigurationFee;
		txtRegistrationFee=frml.txtRegistrationFee;
		txtCPEFee=frml.txtCPEFee;
		
		mServiceID=frml.cmbServiceID;
		mCycleID=frml.cmbCycleID;
		
		if(Trim(mName.value) == ""){
			alert("Please enter Name.");
			mName.focus();
			return;
		}
		
		if(!isNumber(mThreshold.value)){
			alert("Please enter numeric value in Threshold field");
			mThreshold.focus();
			return;
		}else if(Number(mThreshold.value) < 0){
			alert("Threshold amount must be positive number");
			mThreshold.focus();
			return;
		}
		if(!isNumber(mDepositAmount.value)){
			alert("Please enter numeric value in Deposit Field.");
			mDepositAmount.focus();
			return;
		}else if(Number(mDepositAmount.value) < 0){
			alert("Deposit amount must be positive number");
			mDepositAmount.focus();
			return;
		}
		if(!isNumber(txtISDNFee.value)){
			alert("ISDN fee must be positive number");
			txtISDNFee.focus();
			return;
		}else if(Number(txtISDNFee.value) < 0){
			alert("ISDN fee must be positive number");
			txtISDNFee.focus();
			return;
		}
		if(!isNumber(txtSpecialNumber.value)){
			alert("Special number must be positive number");
			txtSpecialNumber.focus();
			return;
		}else if(Number(txtSpecialNumber.value) < 0){
			alert("Special number must be positive number");
			txtSpecialNumber.focus();
			return;
		}
		if(!isNumber(txtCycleFee.value)){
			alert("Cycle fee must be positive number");
			txtCycleFee.focus();
			return;
		}else if(Number(txtCycleFee.value) < 0){
			alert("Cycle fee must be positive number");
			txtCycleFee.focus();
			return;
		}
		if(!isNumber(txtConfigurationFee.value)){
			alert("Installation fee must be positive number");
			txtConfigurationFee.focus();
			return;
		}else if(Number(txtConfigurationFee.value) < 0){
			alert("Installation fee must be positive number");
			txtConfigurationFee.focus();
			return;
		}
		if(!isNumber(txtRegistrationFee.value)){
			alert("Registration fee must be positive number");
			txtRegistrationFee.focus();
			return;
		}else if(Number(txtRegistrationFee.value) < 0){
			alert("Registration fee must be positive number");
			txtRegistrationFee.focus();
			return;
		}
		if(!isNumber(txtCPEFee.value)){
			alert("CPE fee must be positive number");
			txtCPEFee.focus();
			return;
		}else if(Number(txtCPEFee.value) < 0){
			alert("CPE fee must be positive number");
			txtCPEFee.focus();
			return;
		}
		
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1011";
	}
	function ViewBy(servicevalue){
		
		getContent(servicevalue,'','asc');
	}
	
var blockid=0;
var contentname="package";
var blockname="block";
var url="./administration/ajax_getcontent.php";


function getContent(id,orderby,ordertype){
	 try{
	 	
	 	  var url2=url+"?contentname="+contentname+"&id="+id+"&orderby="+orderby+"&ordertype="+ordertype+"&mt=" + new Date().getTime();
		  blockid=id;
				var div = document.getElementById("block0");
				//div.className="header";
				 div.innerHTML="";
	 	 httpRequest("get",url2,true,respHandle);
	 }catch(err){
		alert("Unconditional Error : "+err.message);
	 }
	// httpRequest("get",url,true,respHandle);
	return false;
 }
 
 //function for XMLHttpRequest onreadystatechange event handler
function respHandle(){
	 try{
		 if(request.readyState == 4){
			 if(request.status == 200){
				 /*All headers received as a single string*/
				 //var headers = request.getAllResponseHeaders();
				 var response= request.responseText;
				// alert(request.status+response);
				 var div = document.getElementById("block0");
				//div.className="header";
				 div.innerHTML=response;
			 } else {
				 //request.status is 503 if the application isn't available; 
				 //500 if the application has a bug
				 alert(request.status);
				 alert("A problem occurred with communicating between "+
				 "the XMLHttpRequest object and the server program.");
			 }
		 }//end outer if
	 } catch (err) {
		 alert("It does not appear that the server is "+
		 "available for this application. Please"+
		 " try again very soon. \nError: "+err.message);
	 }
	}	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" >
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="651">
			   <tr>
				 <td width="647" height="18" align="left" class="formtitle"><b>PACKAGE</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="22%" align="left">Name:</td>
						<td width="30%" align="left"><label>
						  <input type="text" name="txtName"class="boxenabled" value="<?php print($txtName);?>" />
					   </label>							<img src="./images/required.gif" border="0" /></td>
					    <td width="21%" align="left">&nbsp;</td>
					    <td width="27%" align="left"></td>
					 </tr>	
					 <tr>
					 	<td align="left">Threshold:</td>
						<td align="left"><label>
						  <input type="text" name="txtThreshold" class="boxenabled" value="<?php print($txtThreshold);?>" />
						</label>							<img src="./images/required.gif" border="0" /></td>
					    <td align="left">Billing Cycle:</td>
					    <td align="left"><select name="cmbCycleID" style="width:150px;">
                          <?php
								$sql="select * from tlkpBillingCycle where status='1'";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbCycleID==intval($row['CycleID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['CycleID'])."' $selected>".stripslashes($row['Name'])."</option>";
								}
							?>
                        </select></td>
					 </tr>
					<tr>
						<td align="left">Deposit Amount:</td>
					 	<td align="left"><input type="text"  name="txtDepositAmount" class="boxenabled" value="<?php print($txtDepositAmount);?>" /> <img src="./images/required.gif" border="0" /></td>
					    <td align="left">Service:</td>
					    <td align="left"><select name="cmbServiceID" style="width:150px;">
                          <?php
								$sql="select * from tlkpService";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbServiceID==intval($row['ServiceID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['ServiceID'])."' $selected>".stripslashes($row['ServiceName'])."</option>";
								}
							?>
                      </select></td>
					</tr>
					<tr>
					  <td align="left">Registration Fee:</td>
					  <td align="left"><input type="text" name="txtRegistrationFee" value="<?php echo $txtRegistrationFee?>" class="boxenabled" /></td>
					  <td align="left">Installation Fee: </td>
					  <td align="left"><input type="text" name="txtConfigurationFee" value="<?php echo $txtConfigurationFee?>" class="boxenabled" /></td>
				     </tr>
					<tr>
					  <td align="left">CPE Fee:</td>
					  <td align="left"><input type="text" name="txtCPEFee" value="<?php echo $txtCPEFee?>" class="boxenabled" /></td>
					  <td align="left">Cycle Fee: </td>
					  <td align="left"><input type="text" name="txtCycleFee" value="<?php echo $txtCycleFee?>" class="boxenabled" /></td>
				     </tr>
					<tr>
					  <td align="left">ISDN Fee:</td>
					  <td align="left"><input type="text" name="txtISDNFee" value="<?php echo $txtISDNFee?>" class="boxenabled" /></td>
					  <td align="left">Special Number Fee: </td>
					  <td align="left"><input type="text" name="txtSpecialNumber" value="<?php echo $txtSpecialNumber?>" class="boxenabled" /></td>
				     </tr>
										 				 
					 <tr> 				  
					  <td align="center" colspan="4">
					  <?php
					  if(isset($op)){
					  		if($op=="edit"){
								$subvalue="Update";
							}elseif($op=="add"){
								$subvalue="Add";
							}
					  }else{
					  	$subvalue="Add";
						$op="add";
					  }
					  ?>
							<input type="button" onclick="Reset()"  name="reset" value="Reset" class="button" />
							<input type="button" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
					 </tr>		
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
							}
						?>			
			     </table>				 </td>
			   </tr>			   
  </table>
			  <input type="hidden" name="pg" id="pg" value="1011">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="sService" id="sService" value="<?php echo $sService;?>" />
			  <input type="hidden" name="packageid" id="packageid" value="<?php echo $packageid?>" />
              <input type="hidden" name="smt" id="smt" value="yes" />
</form>
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="744">
									
									<tr>
									 	<td width="295" align="left" class="formtitle"><strong>Package Information </strong></td>
										<td width="441" align="right"><span class="formtitle" style="padding-bottom:20px">
										  <span class="formtitle" style="padding-bottom:20px">View By Service:</span>
										  <select name="sService" style="width:150;" tabindex="5" onchange="ViewBy(this.value)">
                                            <option value="0" <?php if($sService==0)echo " selected";?>>All Service</option>
                                            <?php
								$sql="select * from tlkpService where serviceid <> 1000";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($sService==intval($row['ServiceID']))
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".intval($row['ServiceID'])."' $selected>".stripslashes($row['ServiceName'])."</option>";
								}
							?>
                                          </select>
										</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="./?pg=1011">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
										<div id='block0'></div>									  
										</td>
									</tr>
		</table>
	  </td>
  </tr>
	</table>	
<script language="javascript">
	getContent(<?php echo (isset($sService)||$sService!="")?intval($sService):0 ?>,'','asc');
</script>
<?php
# Close connection
$mydb->sql_close();
?>