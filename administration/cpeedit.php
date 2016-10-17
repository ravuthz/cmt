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
	$CPEID = FixQuotes($CPEID);
	if(isset($smt) && (!empty($smt)) && ($smt == "save801") &&  ($pg == 801)){
		$txtName = FixQuotes($txtName);
		$txtSupplierName = FixQuotes($txtSupplierName);
		$selServiceID = FixQuotes($selServiceID);
		$StatusID = FixQuotes($StatusID);
		$sql = "UPDATE tlkpCPE SET
								CPEName = '".$txtName."',
								ServiceID = ".$selServiceID.",
								Active = ".$StatusID.",
								SupplierName = '".$txtSupplierName."'
						WHERE CPEID = ".$CPEID;		
		if($mydb->sql_query($sql)){
			redirect("./?pg=800");
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to update CPE.", $error['message'].$sql);
		}
	}
		
	$sql = "SELECT * FROM tlkpCPE WHERE CPEID = $CPEID";
	if($que = $mydb->sql_query($sql)){
		if($result = $mydb->sql_fetchrow($que)){
			$CPEName = $result['CPEName'];
			$Service = $result['ServiceID'];
			$Status = $result['Active'];
			$SupplierName = $result['SupplierName'];
		}
	}
?>
<script language="javascript">
	function ValidateForm(){
		if(fCPE.txtName.value == ""){
			alert("Please enter CPE name");
			fCPE.txtName.focus();
			return;
		}else{
			fCPE.btnNext.disabled = true;
			fCPE.smt.value = "save801";
			fCPE.submit();
		}
	}
</script>
<table border="0" align="left" cellpadding="3" cellspacing="5">
	<tr>
		<td>
			<form name="fCPE" method="post" action="./">
			<table border="0" cellpadding="0" cellspacing="5" align="left">
				<tr>		
					<td valign="top" align="left">
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="center" width="100%">
							<tr>
							 <td align="left" class="formtitle" height="18"><b>EDIT CPE</b></td>
							 </tr>
							<tr>
							 <td valign="top">
								 <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">					 					 
								<tr>
									<td align="left">CPE name:</td>
									<td align="left">
										<input type="text" tabindex="1" name="txtName" class="boxenabled" size="50" maxlength="50" value="<?php print $CPEName; ?>" />
											<img src="./images/required.gif" border="0" />
									</td>
								</tr>					 
								<tr>
									<td align="left" valign="top">Supplier name:</td>
									<td align="left">
										<input type="text" tabindex="2" name="txtSupplierName" class="boxenabled" size="50" maxlength="50" value="<?php print $SupplierName; ?>" />								
									</td>
								</tr>
								<tr>
									<td align="left">Service type:</td>
									<td>
										<select name="selServiceID" class="boxenabled" tabindex="3" style="width:190px">								
											<?php
												$sql = "SELECT ServiceID, ServiceName from tlkpService order by ServiceName";
												// sql 2005
												
												$que = $mydb->sql_query($sql);									
												if($que){
													while($rst = $mydb->sql_fetchrow($que)){	
														$ServiceID = $rst['ServiceID'];
														$ServiceName = $rst['ServiceName'];
														if($Service == $ServiceID) 
															$sel = "selected";
														else
															$sel = "";
														print "<option value='".$ServiceID."' ".$sel.">".$ServiceName."</option>";
													}
												}
												$mydb->sql_freeresult();
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td align="left">Status:</td>
									<td align="left">
										<select name="StatusID" tabindex="4">
											<option value="1" <?php if($Status) print "selected"; ?>>Enable</option>
											<option value="0" <?php if(!$Status) print "selected"; ?>>Disable</option>
										</select>
									</td>
								</tr>
								 <tr> 				  
									<td align="center" colspan="2">
										<input type="reset" tabindex="5" name="reset" value="Reset" class="button" />
										<input type="button" tabindex="6" name="btnNext" value="Save" class="button" onClick="ValidateForm();" />						
									</td>
								 </tr>		
								 <?php
										if(isset($retOut) && (!empty($retOut))){
											print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
										}
									?>			
								 </table>
							 </td>
							 </tr>			   
						 </table>
						</td>
					</tr>
				</table>
				<input type="hidden" name="CPEID" value="<?php print $CPEID; ?>" />
				<input type="hidden" name="pg" id="pg" value="801" />
				<input type="hidden" name="smt" id="smt" value="" />
			</form>
		</td>
	</tr>
</table>
<?php
# Close connection
$mydb->sql_close();
?>