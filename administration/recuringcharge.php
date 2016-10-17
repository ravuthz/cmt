
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1017)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtRecRate=doubleval($txtRecRate);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
		
			if(CheckExistsRecuringCharge($txtRecRate)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tblRecuringCharges (Description,RecRate) ".
						"VALUES ('".$txtDescription."','".$txtRecRate."')";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add Recuring Charge","Add Recuring Charge $txtDescription",$user["FullName"], 1,16);
					$retOut = $myinfo->info("Successfully add new recuring charge.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Recuring Charge","Add Recuring Charge $txtDescription",$user["FullName"], 0,16);
					$retOut = $myinfo->error("Failed to add new recuring charge", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistsRecuringCharge($txtRecRate,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE tblRecuringCharges SET RecRate='".$txtRecRate."', Description='".$txtDescription."' WHERE RecChargeID='".intval($hid)."';";
				//print $sql;
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Update recuring charge","Update Recuring Charge ID: $hid",$user["FullName"],1,16);	
					$retOut = $myinfo->info("Successfully update recuring charge.");
				}else{
					$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update recuring charge","Update Recuring Charge ID: $hid",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update recuring charge.", $error['message']);
				}
				$op="add";
				$txtRecRate="";
				$txtDescription="";
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblRecuringCharges WHERE RecChargeID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtRecRate=doubleval($row['RecRate']);
				$txtDescription=stripslashes($row['Description']);
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblRecuringCharges set status='0' WHERE RecChargeID='".$hid."'";
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Deactivate recuring charge","Deactivate Recuring Charge ID: $hid",$user["FullName"],1,16);		
					$retOut = $myinfo->info("Successfully deactivate recuring charge.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate recuring charge","Deactivate Recuring Charge ID: $hid",$user["FullName"],0,16);	
					$retOut = $myinfo->error("Failed to deactivate recuring charge.", $error['message']);
				}
				
				$op="add";
				$txtRecRate="";
				$txtDescription="";
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate gateway: " + code + "?"))
			window.location = "./?pg=1017&op=deactivate&gateid=" + id;
	}
	
	function ValidateForm(){
		mRecRate=frml.txtRecRate;
		mDescription=frml.txtDescription;
		if(Trim(mRecRate.value)==""){
			alert("Please fill recuring charge rate.");
			mRecRate.focus();
			return false;
		}
		if(isNumber(mRecRate.value)<=0){
			alert("Recuring charge rate value should be bigger than 0.");
			mRecRate.focus();
			return false;
		}
		if(Trim(mDescription.value)==""){
			alert("Please fill the description for recuring charge.");
			mDescription.focus();
			return false;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1017";
	}
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="37%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>
		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="574">
			   <tr>
				 <td height="18" align="left" class="formtitle"><strong>Recuring Charge </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="120" align="left" style="padding-left:10">Recuring Rate: </td>
						<td align="left" width="442" ><input type="text" name="txtRecRate" class="boxenabled" value="<?php echo $txtRecRate;?>"/><img src="./images/required.gif" border="0" />
					   </td>
					</tr>	
					<tr>
						<td align="left" style="padding-left:10">Description:</td>
				 	  <td align="left" ><input type="text" name="txtDescription" class="boxenabled" size="60" maxlength="50" value="<?php print($txtDescription);?>" />
				 	    <img src="./images/required.gif" border="0" /></td>
					</tr>					 				 
					 <tr> 				  
					  <td align="center" colspan="2">
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
							<input type="button" onclick="Reset()" name="reset" value="Reset" class="button" />
							<input type="button" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
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
			  <input type="hidden" name="pg" id="pg" value="1017">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Recuring Charge  Information </strong></td>
										<td align="right">[<a href=".?pg=1017">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											  <th width="116">No.</th>
													<th width="285">Recuring Charge</th>
													<th width="497">Description</th>
													<th width="25">Edit</th>
												
												</tr>
											
												<?php
												$sql2="select * from tblRecuringCharges";
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
//													if($row['Status']==false){
//														$rowdeactiveclass="";
//														echo '<tr '.$rowdeactiveclass.'>
//														<td " align="center"><font color="#aaaaaa">'.$row['RecChargeID'].'</font></td>
//														<td  align="left"><font color="#aaaaaa">'.$row['RecRate'].'</font></td>
//														<td  align="left"><font color="#aaaaaa">'.$row['Description'].'</font></td>														
//													</tr>';
//													}else{
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['RecChargeID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['RecRate'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['Description'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1017&amp;op=edit&amp;hid='.$row['RecChargeID'].'"><img src="./images/Edit.gif" border="0"></a></td>
													</tr>';
													//}
													
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