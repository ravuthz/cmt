
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1002)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtGateCode=stripslashes(FixQuotes($txtGateCode));
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
		
			if(CheckExistsGateCode($txtGateCode)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tlkpTarGateWay(GateCode, Description,Status) VALUES('".$txtGateCode."','".$txtDescription."','1');";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add New Gate Way","Add new gate way $txtGateCode",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully add new gate way.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add New Gate Way","Add new gate way $txtGateCode",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to add new gate way", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistsGateCode($txtGateCode,true,$gateid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE tlkpTarGateWay SET GateCode='".$txtGateCode."', Description='".$txtDescription."' WHERE gateid='".intval($gateid)."';";
				//print $sql;
				if($mydb->sql_query($sql)){				
					$Audit->AddAudit(0,0,"Update Gate Way","Update gate way gate id: $gateid",$user["FullName"],1,16); 
					$retOut = $myinfo->info("Successfully update gate way.");
				}else{
					$Audit->AddAudit(0,0,"Update Gate Way","Update gate way gate id: $gateid",$user["FullName"],0,16);
					$error = $mydb->error();
					$retOut = $myinfo->error("Failed to update gate way", $error['message']);
				}
				$op="add";
				$txtGateCode="";
				$txtDescription="";
			}
				
		}
	}elseif(isset($gateid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tlkpTarGateWay WHERE GateID='".intval($gateid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtGateCode=stripslashes($row['GateCode']);
				$txtDescription=stripslashes($row['Description']);
			}
			
		}elseif($op=="deactivate"){
			$sql="update tlkpTarGateWay set status='0' WHERE GateID='".$gateid."'";
				if($mydb->sql_query($sql)){	
					$Audit->AddAudit(0,0,"Deactivate Gate Way","Deactivate gate way gate id: $gateid",$user["FullName"],1,16);				
					$retOut = $myinfo->info("Successfully deactivate gate way.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Gate Way","Deactivate gate way gate id: $gateid",$user["FullName"],0,16);	
					$retOut = $myinfo->error("Failed to deactivate gate way", $error['message']);
				}
				
				$op="add";
				$txtGateCode="";
				$txtDescription="";
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate gateway: " + code + "?"))
			window.location = "./?pg=1002&op=deactivate&gateid=" + id;
	}
	
	function ValidateForm(){
		mGateCode = frml.txtGateCode;		
		if(Trim(mGateCode.value) == ""){
			alert("Please enter gate way code.");
			mGateCode.focus();
			return;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1002";
	}
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>
		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="574">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>GATE WAY</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="110" align="left">Gateway Code:</td>
						<td align="left" width="452" colspan="3"><label>
						  <input type="text" name="txtGateCode" tabindex="1" class="boxenabled" value="<?php print($txtGateCode);?>" />
					   </label><img src="./images/required.gif" border="0" /></td>
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
			  <input type="hidden" name="pg" id="pg" value="1002">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="gateid" id="gateid" value="<?php echo $gateid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Gate Way Information </strong></td>
										<td align="right">[<a href=".?pg=1002">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											  <th width="116">No.</th>
													<th width="285">Gate Way Code</th>
													<th width="497">Description</th>
													<th width="25">Edit</th>
												
												</tr>
											
												<?php
												$sql2="SELECT * FROM tlkpTarGateWay ORDER BY Status Desc";
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
													if($row['Status']==false){
														$rowdeactiveclass="";
														echo '<tr '.$rowdeactiveclass.'>
														<td " align="center"><font color="#aaaaaa">'.$row['GateID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['GateCode'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['Description'].'</font></td>														
													</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['GateID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['GateCode'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['Description'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1002&amp;op=edit&amp;gateid='.$row['GateID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['GateID'].',\''.$row['GateCode'].'\');"><img src="./images/Delete.gif" border="0"></a></td>
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