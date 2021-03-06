
<?php
	//error_reporting(E_ALL & E_ERROR & E_NOTICE);
	require_once("./common/agent.php");	
	require_once("function.php");
	require_once("./common/functions.php");
	require_once("./common/class.audit.php");
	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright � 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 1003)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtBlockName=stripslashes(FixQuotes($txtBlockName));
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
		
			if(CheckExistsBlockName($txtBlockName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
			
				$sql = "INSERT INTO tlkpTarChargeBlock(BlockName, Description,Status) VALUES('".$txtBlockName."','".$txtDescription."','1');";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add Charge Block",$txtDescription,$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new charge block.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Charge Block",$txtDescription,$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new charge block", $error["message"]);
				}
			}	
			
			
		}elseif($op=="edit"){
			if(CheckExistsBlockName($txtBlockName,true,$blockid)){
			 	
			 			$retOut=$myinfo->warning("The information already existed.");
			}else{
			 
					$sql = "UPDATE tlkpTarChargeBlock SET BlockName='".$txtBlockName."', Description='".$txtDescription."' WHERE BlockId='".intval($blockid)."';";
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Update Charge Block",$txtBlockName,$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update block name.");
					}else{
						$Audit->AddAudit(0,0,"Update Charge Block",$txtBlockName,$user["FullName"],0,15);
						$error=$mydb->sql_error();
						$retOut = $myinfo->error("Failed to update block name", $error["message"]);
					}
					$op="add";
					$txtGateCode="";
					$txtDescription="";
			}
		}
		//echo "second edit";
	}elseif(isset($blockid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tlkpTarChargeBlock WHERE BlockID='".intval($blockid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtBlockName=stripslashes($row['BlockName']);
				$txtDescription=stripslashes($row['Description']);
			}
			
		}elseif($op=="deactivate"){
		
			$sql="update tlkpTarChargeBlock set status='0' WHERE BlockID='".$blockid."'";
		
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Deactivate Charge Block","Deactivate block id: $blockid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully deactivate charge block.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Deativate Charge Block","Deactivate block id: $blockid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate charge block", $error["message"]);
				}
				
				$op="add";
				$txtGateCode="";
				$txtDescription="";
		}
		if($op=="activate"){
			$sql="update tlkpTarChargeBlock set status='1' WHERE BlockID='".$blockid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
				$Audit->AddAudit(0,0,"Active Charge Block","Active charge block id: $cmbBlockID",$user["FullName"],1,15);	
					$retOut = $myinfo->info("Successfully activate charge block.");
				}else{
					#rollback transaction
					#$mydb->mssql_rollback();
					$err=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Aactive Charge Block","Active charge block id: $cmbBlockID",$user["FullName"],0,15);	
					$retOut = $myinfo->error("Failed to activate charge block", $err["message"]);
				}
				
	}
		//echo "first edit";
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate charging block: " + code + "?"))
			window.location = "./?pg=1003&op=deactivate&blockid=" + id;
	}
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate charging block: " + code + "?"))
			window.location = "./?pg=1003&op=activate&blockid=" + id;
	}
	function ValidateForm(){
		mBlockName = frml.txtBlockName;		
		if(Trim(mBlockName.value) == ""){
			alert("Please enter charging block code.");
			mBlockName.focus();
			return;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	function Reset(){
		document.location.href="/?pg=1003";
	}
	
	
</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top"><?php include("left.php"); ?></td>	
		<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
	<td valign="top">
		<?php include_once("left.php");?>
	</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>CHARGING BLOCK</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="110" align="left">Block Name:</td>
						<td align="left" width="452" colspan="3"><label>
						  <input type="text" name="txtBlockName" tabindex="1" class="boxenabled" value="<?php print($txtBlockName);?>" />
					   </label>							<img src="./images/required.gif" border="0" /></td>
					</tr>	
					<tr>
						<td align="left">Description:</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="2" name="txtDescription" class="boxenabled" size="72" maxlength="50" value="<?php print($txtDescription);?>" /></td>
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
			  <input type="hidden" name="pg" id="pg" value="1003">
			   <input type="hidden" name="subop" id="subop" value="1">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="blockid" id="blockid" value="<?php echo $blockid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>

		  	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Charging Block Information.</strong></td>
										<td align="right">[<a href="./?pg=1003">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th>&nbsp;</th>
											
													<th width="200">Block Name</th>
													<th width="497">Description</th>
													<th width="25">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select * from tlkpTarChargeBlock ORDER BY Status Desc";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$BlockID=intval($row["BlockID"]);
													$BlockName=stripslashes($row['BlockName']);
													$Description=stripslashes($row['Description']);
													echo "<div id='cont'>";																		
													echo "</td>";
													
													    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==false){
													$rowdeactiveclass=" ";
														echo "<tr $rowdeactiveclass>";
													echo "<td>";
													if(HasChargeBlockDetail($BlockID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$BlockID."\" name=\"img" .$BlockID. "\" onClick=\"SwitchMenu('block" .$BlockID. "', 'img".$BlockID. "');\">";
													echo "</a>";
													}
														
														echo '

														<td  align="left"><font color="#aaaaaa">'.$BlockName.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$Description.'</font></td>';
													echo "<td align='center' ><a href=\"javascript:ActionConfirmationActivate(".$row['BlockID'].",'".$row["BlockName"]."')\"><img src=\"./images/icon/admin.gif\" border=\"0\"></a></td>";														
													echo '</tr>';
													}else{
													$rowdeactiveclass="";
													echo "<tr class=\"$rowdeactiveclass\">";
													echo "<td class=\"".$rowclass."\">";
													if(HasChargeBlockDetail($BlockID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$BlockID."\" name=\"img" .$BlockID. "\" onClick=\"SwitchMenu('block" .$BlockID. "', 'img".$BlockID. "');\">";
													echo "</a>";
													}
													
													echo '
														<td class="'.$rowclass.'" align="left">'.$BlockName.'</td>
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>';
												  	echo "<td class='".$rowclass."' ><a href=\"?pg=1003&amp;subop=2&amp;op=edit&amp;blockid=".$BlockID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$row['BlockID'].",'".$row["BlockName"]."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasChargeBlockDetail($BlockID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='3' >";
													echo GetChargeBlockDetail($BlockID);
												
													echo "</td>";				
													echo "</tr>";	
																	
													}	
													$rowcount++;			
													echo "</div>";
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
</td>
</tr>
</table>
<?php
# Close connection
$mydb->sql_close();
?>