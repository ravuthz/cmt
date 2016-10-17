<?php

	//error_reporting(E_ALL & E_ERROR & E_NOTICE);
	require_once("./common/agent.php");	
    require_once("./administration/function.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 2803)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();

		
		$UserName=stripslashes(FixQuotes($UserName));
		$Description=stripslashes(FixQuotes($Description));
		$LimitAmount=stripslashes(FixQuotes($LimitAmount));
		
		if($op=="add"){
		
			if(CheckCreditExisting($AccID) || CheckAccIDExisting($AccID)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tblCreditLimit(AccID, LimitAmount, Description) VALUES(".$AccID.",'".$LimitAmount."','".$Description."');";
				//print $sql;
				if($mydb->sql_query($sql)){						
					$Audit->AddAudit(0,0,"Add Credit Limit",$UserName,$user["FullName"],1,15);			
					$retOut = $myinfo->info("Successfully add new Credit Limit.");
					$UserName="";
					$Description="";
					$LimitAmount="";
					$AccID="";
					$ID="";
				}else{
				$Audit->AddAudit(0,0,"Add Credit Limit",$UserName,$user["FullName"],0,15);	
					$error = $mydb->sql-error();
					$retOut = $myinfo->error("Failed to add new Credit Limit", $error['message']);
				}
			}				
		}elseif($op=="edit"){
			if(!CheckAccIDExisting($AccID)){
			 	
			 			$retOut=$myinfo->warning("The information doesn't existed.");
			}else{
			       
					$sql = "UPDATE tblCreditLimit SET AccID=".$AccID.", LimitAmount=".$LimitAmount.", Description='".$Description."' WHERE ID=".intval($ID).";";
//				/	print $sql;
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Update Credit Limit","Update Credit Limit ID=$ID and AccID=$AccID",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update Credit Limit.");
					}else{
						$error=$mydb->sql_error();
						$Audit->AddAudit(0,0,"Update Credit Limit","Update Credit Limit ID=$ID and AccID=$AccID",$user["FullName"],0,15);
						$retOut = $myinfo->error("Failed to update Credit Limit", $error["message"]);
					}
					$op="add";
					$UserName="";
					$Description="";
					$LimitAmount="";
					$AccID="";
					$ID="";
			}
		}
		//echo "second edit";
		
	}elseif((isset($ID)) && isset($op)){
	
		if($op=="edit"){
			$sql="select cl.AccID,LimitAmount,Description,UserName from tblCreditLimit cl Inner Join tblCustProduct P on cl.accID=p.accID WHERE ID=".intval($ID)."";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$AccID=stripslashes($row['AccID']);
				$UserName=stripslashes($row['UserName']);
				$LimitAmount=stripslashes($row['LimitAmount']);
				$Description=stripslashes($row['Description']);
			}	
		}elseif($op=="delete"){
			

				$sql="delete from tblCreditLimit WHERE ID=".$ID;

			//$retOut = $myinfo->info($sql);
			
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Delete Credit Limit","Delete Credit Limit ID=$ID and AccID=$AccID",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully delete Credit Limit.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Delete Credit Limit","Delete Credit Limit ID=$ID and AccID=$AccID",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to delete Credit Limit", $error["message"]);
				}
				$op="add";		
		}

	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" src="./javascript/ajax_checkaccname.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">

	function ActionConfirmationDelete(id, lamount){
		if(confirm("Do you want to delete this record ?"))
			window.location = "./?pg=2803&op=delete&ID=" + id +"&limitamount="+lamount;
	}
	
	function ValidateForm(){
		musername = document.frml.UserName;
		mlamount = document.frml.LimitAmount;
		if(Trim(musername.value) == "" || Trim(mlamount.value) == ""){
			alert("Please enter user name or limit amount.");
			musername.focus();
			return;
		}
		
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	
	function Reset(){
	
		document.location.href="./?pg=2803";
		
	}
		
	function ValidAccount(retDiv,acid){

		uname = frml.UserName.value;
		url = "./php/ajax_checkaccname.php?username=" + uname +"&mt=" + new Date().getTime();
		checkUserName(url, retDiv,acid);
	}
	
</script><table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		
		<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
	
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
			   <tr>
				 <td height="18" align="left" class="formtitle"><strong>Credit Limit </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="98" align="left">User Name :</td>
						<td align="left" width="464" colspan="3">
						  <input type="text" name="UserName" tabindex="1" style="width:150;" class="boxenabled" value="<?php  print($UserName);?>" size="30" onblur="ValidAccount('dUserName','accid');"/><img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName" class="error"></span>
						</td>
					</tr>	
							
					<tr>
						<td align="left">Limit Amount :</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="2" name="LimitAmount" class="boxenabled" size="30" maxlength="25" value="<?php print($LimitAmount);?>" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dlAmount" class="error"></span></td>
					</tr>
					<tr>
						<td align="left">Description :</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="2" name="Description" class="boxenabled" size="72" maxlength="50" value="<?php print($Description);?>" /></td>
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
			  <input type="hidden" name="pg" id="pg" value="2803">
			   <input type="hidden" name="subop" id="subop" value="1">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="AccID" id="AccID" value="<?php echo $AccID?>" />
			  <input type="hidden" name="ID" id="ID" value="<?php echo $ID?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>

				  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="573">
									<tr>
									 	<td align="left" class="formtitle"><strong>Credit Limit Information.</strong></td>
										<td align="right">[<a href="./?pg=2803">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												
													<th width="49">ID</th>
													<th width="100">AccID</th>
													<th width="229">User Name</th>
													<th width="130">Limit Amount</th>
													<th width="230">Description</th>
													<th width="10">&nbsp;</th>												
												</tr>
												
												
												<?php
												$sql="select ID,cl.AccID,UserName, LimitAmount, Description ".
												     "from tblCreditLimit cl Inner Join tblCustProduct p on cl.accID=p.accID ".
												     "ORDER BY cl.AccID";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result))
												{
												
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													
													$ID=intval($row["ID"]);
													$AccID=intval($row["AccID"]);
													$UserName=stripslashes($row["UserName"]);
													$LimitAmount=$row['LimitAmount'];
													$Description=stripslashes($row['Description']);
												
													 echo "<tr >";


													echo '
														<td class="'.$rowclass.'" align="center">'.$ID.'</td>
														<td class="'.$rowclass.'" align="center">'.$AccID.'</td>
														<td class="'.$rowclass.'" align="center">'.$UserName.'</td>
														<td class="'.$rowclass.'" align="right">'.$LimitAmount.'</td>
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=2803&amp;op=edit&amp;ID='.$row['ID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmationDelete('.$row['ID'].',\''.$row['LimitAmount'].'\')"><img src="./images/Delete.gif" border="0"></a></td>
													</tr>';											
													
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

</td>
</tr>
</table>	
<?php
# Close connection
$mydb->sql_close();
?>