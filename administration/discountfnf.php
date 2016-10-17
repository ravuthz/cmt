
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
	if($chIsPercentage!='1'){
		$chIsPercentage=0;
	}

	if(isset($smt) && (!empty($smt)) && ($pg == 1015)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbRecChargeID=intval($cmbRecChargeID);
		$txtRate=doubleval($txtRate);
		$chIsPercentage=intval($chIsPercentage);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
		
			if(CheckFnfDiscount($cmbRecChargeID,$txtRate,$chIsPercentage)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tblEventDiscFnF( RecChargeID,Description,DiscRate,IsPercentage,IsActive) VALUES('".$cmbRecChargeID."','".$txtDescription."','".$txtRate."','".$chIsPercentage."','1');";
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add New Friend and Family Discount","Add new friend and family discount name $txtDescription",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully add new friend and family discount setup.");
				}else{
					$error = $mydb->sql_error();
						$Audit->AddAudit(0,0,"Add New Friend and Family Discount","Add new friend and family discount name $txtDescription",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to add new friend and family discount setup.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckFnfDiscount($cmbRecChargeID,$txtRate,$chIsPercentage,true,$fnfdiscid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE tblEventDiscFnF SET RecChargeID='$cmbRecChargeID', Description='$txtDescription', DiscRate='$txtRate', IsPercentage='$chIsPercentage' Where DiscFnFID='".intval($fnfdiscid)."'";
				//print $sql;
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Update Friend and Family Discount","Update friend and family disocunt id: $fnfdiscid",$user["FullName"],1,16);
						
					$retOut = $myinfo->info("Successfully update friend and family discount setup.");
				}else{
					$error = $mydb->error();
						$Audit->AddAudit(0,0,"Update Friend and Family Discount","Update friend and family disocunt id: $fnfdiscid",$user["FullName"],0,16);
					
					$retOut = $myinfo->error("Failed to update friend and family discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$txtRate="";
				$chIsPercentage="0";
				$cmbRecChargeID=0;
			}
				
		}
	}elseif(isset($fnfdiscid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblEventDiscFnF WHERE DiscFnFID='".intval($fnfdiscid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtDescription=stripslashes($row["Description"]);
				$cmbRecChargeID=intval($row["RecChargeID"]);
				$txtRate=doubleval($row["DiscRate"]);
				$chIsPercentage=intval($row["IsPercentage"]);
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblEventDiscFnF set IsActive='0' WHERE DiscFnfID='".$fnfdiscid."'";
				if($mydb->sql_query($sql)){					
					$Audit->AddAudit(0,0,"Deactivate Friend and Family Discount","Deactivate friend and family discount id: $fnfdiscid",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully deactivate friend and family discount setup.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Friend and Family Discount","Deactivate friend and family discount id: $fnfdiscid",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to deactivate friend and family discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$txtRate="";
				$chIsPercentage="0";
				$cmbRecChargeID=0;
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate friend and family discount setup: " + code + "?"))
			window.location = "./?pg=1015&op=deactivate&fnfdiscid=" + id;
	}
	
	function ValidateForm(){
		mRecChargeID=frml.cmbRecChargeID;
		mDescription=frml.txtDescription;
		mRate=frml.txtRate;
		mIsPercentage=frml.chIsPercentage;
		//alert(mRecChargeID.value);
	    if(mRecChargeID.value==""){
			alert("Please select Recuring Charge Value.");
			mRecChargeID.focus();
			return;
		}
			
		if(Trim(mDescription.value) == ""){
			alert("Please enter description.");
			mDescription.focus();
			return;
		}
			
		if(Trim(mRate.value) == ""){
			alert("Please enter Rate value.");
			mRate.focus();
			return;
		}
		if(!isNumber(Trim(mRate.value))){
			alert("Please enter valid rate.");
			mRate.focus();
			return;
		}
		
		if(mIsPercentage.value==1){
			
			if(mRate.value>100){
				alert("Please enter rate value should be equal or small than 100.");
				mRate.focus();
				return;
			}
		}
		
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1015";
	}
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="44%">
	<tr>
		<td valign="top" >
		<?php include_once("left.php");?>
		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="607">
			   <tr>
				 <td height="18" align="left" class="formtitle"><strong>Friend and Family Discount Rate Setup </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr >
					 	<td width="140" align="left" style="padding-left:10">Recuring Charge </td>
						<td align="left" colspan="3"><label>
						<select name="cmbRecChargeID" class="boxenabled" style="width:230px">
                          <option value="" selected>Select Recuring Charge</option>
                          <?php
						  		$sql="select * from tblRecuringCharges";
								$result=$mydb->sql_query($sql);
						  		$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbRecChargeID==intval($row['RecChargeID']))
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".intval($row['RecChargeID'])."' $selected>".stripslashes($row['RecRate'])."</option>";
								}
						  ?>
                        </select>
						</label>
						<img src="./images/required.gif" border="0" /></td>
					</tr>	
					<tr>
						<td align="left" style="padding-left:10">Description:</td>
					 	<td align="left" colspan="3"><input type="text"  name="txtDescription" class="boxenabled" size="49" maxlength="50" value="<?php print($txtDescription);?>" /><img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
					  <td align="left" style="padding-left:10">Rate (Cent / %):</td>
					  <td width="127" align="left" ><input type="text" size="12" name="txtRate" maxlength="10" class="boxenabled"  value="<?php print($txtRate);?>" /><img src="./images/required.gif" border="0" /></td><td width="152">Is Percentage Discount
					    </td>
					  <td width="135"><input type="checkbox" name="chIsPercentage" value="1" <?php if($chIsPercentage==1) print " checked='CHECKED'";?> /></td>
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
							<input type="button" onclick="Reset()" tabindex="3" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="4" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
					 </tr>		
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
							}
						?>			
				   </table>
				 </td>
			   </tr>			   
  </table>
			  <input type="hidden" name="pg" id="pg" value="1015">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="fnfdiscid" id="fnfdiscid" value="<?php echo $fnfdiscid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="609">
									<tr>
									 	<td width="444" align="left" class="formtitle"><strong>Friend and Family Setup</strong></td>
										<td width="157" align="right">[<a href=".?pg=1015">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											  <th width="45">No.</th>
													<th width="184">Description</th>
													<th width="128">Recuring Charge</th>
													<th width="58">Rate</th>
													<th width="111">Is Percentage</th>
													<th width="29">Edit</th>
												</tr>
											
												<?php
												$sql2="SELECT tedfnf.DiscFnfID, tedfnf.Description, tedfnf.DiscRate, tedfnf.IsActive, ".
												"tedfnf.IsPercentage,  trc.RecRate FROM tblEventDiscFnF tedfnf, tblRecuringCharges trc ".
												"where tedfnf.RecChargeID=trc.RecChargeID ".
												"ORDER BY IsActive Desc";
												$query2=$mydb->sql_query($sql2);
												$rowcount=0;
												$rowclass="";
												$rowdeactiveclass="";
												while($row=$mydb->sql_fetchrow($query2))												{
												    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['IsActive']==false){
														$rowdeactiveclass="";
														echo '<tr '.$rowdeactiveclass.'>
														<td " align="center"><font color="#aaaaaa">'.$row['DiscFnfID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['Description'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['DiscRate'].'</font></td>					
														<td  align="left"><font color="#aaaaaa">'.$row['RecRate'].'</font></td>					
														<td  align="left"><font color="#aaaaaa">';
														if($row['IsPercentage']==0){
														echo "No";	
														}else{
														echo "Yes";
														}
														echo '</font></td>													
													</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['DiscFnfID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['Description'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['DiscRate'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['RecRate'].'</td>
														<td class="'.$rowclass.'" align="left">';
															if($row['IsPercentage']==0){
														echo "No";	
														}else{
														echo "Yes";
														}
														
														echo '</td>
									
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1015&amp;op=edit&amp;fnfdiscid='.$row['DiscFnfID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['DiscFnfID'].',\''.$row['Description'].'\');"><img src="./images/Delete.gif" border="0"></a></td>
													</tr>';
													}
													
													$rowcount++;
												 }
												?>
											</table>
										</td>
									</tr>
		</table>
		  </div>
	  </td>
  </tr>
	</table>	

<?php
# Close connection
$mydb->sql_close();
?>