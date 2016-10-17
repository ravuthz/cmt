
<?php
	//error_reporting(E_ALL);
	require_once("./common/agent.php");	
	require_once("function.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	$mytext="";
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg ==1004) && isset($allowsubmit) && $allowsubmit=='true'){
	
//		
		
		if($op=="add" || $op=="edit"){
			 
				if($op=="add"){
					$num=NumberOfBlockDetailByBlockID($cmbBlockID);
					if($num>0){
						$retOut=$myinfo->info("Inforamtion is already existed.");
						
					}else{
					$sql="Delete from tblTarChargeBlockDetail WHERE BlockID='$cmbBlockID'";
					 $mydb->sql_query($sql);
			  
			    	$sql="";
			   		$i=0;
			    //echo $sql;
				    while ($i<$numrecord) {
				    	$txtFromDuration=stripslashes(FixQuotes($_POST["txtFromDuration$i"]));
						$txtToDuration=stripslashes(FixQuotes($_POST["txtToDuration$i"]));
						$cmbBlockID=intval($_POST["cmbBlockID"]);
						$txtUnit=floatval($_POST["txtUnit$i"]);
				    	if(trim($txtFromDuration)=="" || trim($txtUnit)=="");
				    	else
						$sql.= "INSERT INTO tblTarChargeBlockDetail(FromDuration, ToDuration,BlockID,Unit) ".
						   "VALUES('".$txtFromDuration."','".$txtToDuration."','".$cmbBlockID."','".$txtUnit."'); ";
				    	$i++;
				    }
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Add Charge Block Detail","Add Charge block detail for block id: $cmbBlockID",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully add new charge block detail.");
						unset($smt);
						$allowsubmit="false";
						unset($numrecord);
						unset($operate);
					}else{
						$myerror=$mydb->sql_error();
						$Audit->AddAudit(0,0,"Add Charge Block Detail","Add Charge Block Detail for block id: $cmbBlockID",$user["FullName"],0,15);
						$retOut = $myinfo->error("Failed to add new charge block detail", $myerror["message"]);
					}
					}
				}elseif($op=="edit"){
					$sql="Delete from tblTarChargeBlockDetail WHERE BlockID='$blockid';";
				 	$mydb->sql_query($sql);
				  
				    $sql="";
				    $i=0;
				    //echo $sql;
				    while ($i<$numrecord) {
				    	
				    	$txtFromDuration=stripslashes(FixQuotes($_POST["txtFromDuration$i"]));
						$txtToDuration=stripslashes(FixQuotes($_POST["txtToDuration$i"]));
						//$blockid=intval($_POST["cmbBlockID"]);
						$txtUnit=floatval($_POST["txtUnit$i"]);
				    	if(trim($txtFromDuration)=="" || trim($txtUnit)=="");
				    	else
						$sql.= "INSERT INTO tblTarChargeBlockDetail(FromDuration, ToDuration,BlockID,Unit) ".
						   "VALUES('".$txtFromDuration."','".$txtToDuration."','".$blockid."','".$txtUnit."'); ";
				    	$i++;
				    }
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Update Charge Block Detail","Update charge block detail from block id: $cmbBlockID",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update charge block detail.");
						unset($smt);
						$allowsubmit="false";
						unset($numrecord);
						unset($operate);
					}else{
						$myerror=$mydb->sql_error();					
						$Audit->AddAudit(0,0,"Update Charge Block Detail","Update charge block detail from block id: $cmbBlockID",$user["FullName"],1,15);
						$retOut = $myinfo->error("Failed to update charge block detail", $myerror["message"]);
					}	
					
				}
		
		    if($op=="edit"){			
						$op="add";
						$txtFromDuration="";
						$txtToDuration="";
						$cmbBlockID=-1;
			}
			
		}
	}
	
	if(isset($op) && $op=="edit"){
		if(!isset($operate)){
			$mytext=ShowBlockDetailRecord($blockid);
			$numrecord=NumberOfBlockDetailByBlockID($blockid);
		}elseif ($operate=="add"){
  			$mytext=GetCurrentData($numrecord);
  		}elseif($operate=="delete"){
  			$mytext=GetCurrentData($numrecord);
  		}
	}else{
	
		if(!isset($operate)){
	    	$i=0;
	    	$numrecord=1;
	    	//while ($i<$numrecord) {
	    	$mytext.=AddNewRecord($i);
	    		//$i++;
	    	//}
	  	}elseif ($operate=="add"){
	  		$mytext=GetCurrentData($numrecord);
	  	}elseif($operate=="delete"){
	  		$mytext=GetCurrentData($numrecord);
	  	}
	}
	if($op=="deactivate"){
			$sql="update tlkpTarChargeBlock set status='0' WHERE BlockID='".$blockid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
				$Audit->AddAudit(0,0,"Deactive Charge Block","Deactive charge block id: $cmbBlockID",$user["FullName"],1,15);	
					$retOut = $myinfo->info("Successfully deactivate charge block.");
				}else{
					#rollback transaction
					#$mydb->mssql_rollback();
					$err=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactive Charge Block","Deactive charge block id: $cmbBlockID",$user["FullName"],0,15);	
					$retOut = $myinfo->error("Failed to deactivate charge block", $err["message"]);
				}
				
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
	
	function GetCurrentData($numrecord){
		global $operate;
			if(isset($numrecord) && $numrecord>0){
			$i=0;
			//echo $numrecord;
			$txtOldFromDuration=0;
			$txtOldToDuration=0;
			$txtOldUnit=0;
			$fromduration=0;
			$toduration=0;
			$unit=0;
			$mytext="";
			while ($i<$numrecord) {
				
				$fromduration=$_POST["txtFromDuration".$i];
				$toduration=$_POST["txtToDuration".$i];
				$unit=$_POST["txtUnit".$i];
				$class="boxenabled";
				$classfrom="boxenabled";
				$disable="";
				$fromdisable="";
				
				if($operate=="add"){
					if($i==$numrecord-1){
						$fromduration=$txtOldToDuration;						
						$fromdisable="disabled";
						$classfrom="boxdisabled";
					}
					else{
						$class="boxdisabled";
						$classfrom="boxdisabled";
						$disable="disabled";
						$fromdisable="disabled";
					}
					
				}
				else if ($operate=="delete")
				{
					if($i==$numrecord-1){			
						$classfrom="boxdisabled";
						$fromdisable="disabled";
					}else{
						$class="boxdisabled";
						$classfrom="boxdisabled";
						$fromdisable="disabled";
						$disable="disabled";
					}
				}
				
				$mytext.= ' 	     <tr>
						   <td align="center" colspan="2">';
				
				$mytext.= '
						   <input name="txtFromDuration'.$i.'" type="text"  class="'.$classfrom.'" '.$fromdisable.'  value="'.$fromduration.'" size="5" />';
				
				$mytext.='		  </td>
						   <td align="center" ><label>
	                         <input name="txtToDuration'.$i.'" type="text" class="'.$class.'" '.$disable.' value="'.$toduration.'" size="5" />
						   </label></td>
						   <td align="center" ><input name="txtUnit'.$i.'" type="text" class="boxenabled" value="'.$unit.'" size="5" /></td>
					     </tr>';
				$txtOldToDuration=$_POST["txtToDuration".$i];
				$txtOldUnit=$_POST["txtUnit".$i];
				$txtOldFromDuration=$_POST["txtFromDuration".$i];
				$i++;
			}	
		}
		return $mytext;
	}
	
	function ShowBlockDetailRecord($blockid)
    {
   	    global $mydb,$myinfo;
   	    
   	    $sql="select ttcb.BlockID 'ttcbBlockID', ttcb.BlockName 'BlockName', ".
	   		 "ttcbd.BlockDetailID, ttcbd.FromDuration, ttcbd.ToDuration, ttcbd.Unit ".
		     "FROM tlkpTarChargeBlock ttcb, tblTarChargeBlockDetail ttcbd ".
		     "WHERE ttcb.BlockID=ttcbd.BlockID AND ttcb.BlockID='$blockid' ".
			 "ORDER BY 1,3 ";
		$result=$mydb->sql_query($sql);
		$i=0;
		$myinformation="";
		$mytext="";
		$class="boxdisabled";
		$classfrom="boxdisabled";
		$disable="disabled";
	 	
		$numrow=$mydb->sql_numrows($result);
		while($row=$mydb->sql_fetchrow($result)){
			$txtFromDuration=stripslashes($row["FromDuration"]);
			$txtToDuration=stripslashes($row["ToDuration"]);
			$txtUnit=stripslashes($row["Unit"]);
			
			if($i==$numrow-1){
				$class="boxenabled";
				
				$disable="";
				
			}
    	$mytext.=' 	     <tr>
					   <td align="center" colspan="2">
                       <input name="txtFromDuration'.$i.'" type="text" disabled class="'.$classfrom.'" value="'.$txtFromDuration.'" size="5" /></td>
					   <td align="center" ><label>
                         <input name="txtToDuration'.$i.'" type="text"  class="'.$class.'" '.$disable.' value="'.$txtToDuration.'" size="5" />
					   </label></td>
					   <td align="center" ><input name="txtUnit'.$i.'" type="text" class="boxenabled" value="'.$txtUnit.'" size="5" /></td>
				     </tr>';
    		$i++;
		}
	
		return $mytext;
    }
    
	function NumberOfBlockDetailByBlockID($blockid)
    {
   	    global $mydb,$myinfo;
   	    
   	    $sql="select ttcb.BlockID 'ttcbBlockID', ttcb.BlockName 'BlockName', ".
	   		 "ttcbd.BlockDetailID, ttcbd.FromDuration, ttcbd.ToDuration, ttcbd.Unit ".
		     "FROM tlkpTarChargeBlock ttcb, tblTarChargeBlockDetail ttcbd ".
		     "WHERE ttcb.BlockID=ttcbd.BlockID AND ttcb.BlockID='$blockid' ".
			 "ORDER BY 1,3 ";
		$result=$mydb->sql_query($sql);
		return intval($mydb->sql_numrows($result));
    }
    
	function AddNewRecord($numrecord){
		
		$mystring= ' 	<tr>
					   <td align="center" colspan="2"><input name="txtFromDuration'.$numrecord.'" type="text" class="boxdisabled" disabled value="0" size="5" /></td>
					   <td align="center" ><label>
                         <input name="txtToDuration'.$numrecord.'" type="text" class="boxenabled" value="0" size="5" />
					   </label></td>
					   <td align="center" ><input name="txtUnit'.$numrecord.'" type="text" class="boxenabled" value="0" size="5" /></td>
				     </tr>';
		return $mystring;
		
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">
	
	function ValidateForm(){

		if(!CheckValidate()){
			return;
		}
		enableFormElements();
		
		frml.allowsubmit.value="true";
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function CheckValidate(){
		var i=0;
		
		while(i<frml.numrecord.value){
			eval("mFromDuration = frml.txtFromDuration"+i);
			eval("mToDuration = frml.txtToDuration"+i);
			
			eval("mUnit = frml.txtUnit"+i);
			mBlockID = frml.cmbBlockID;
			if(mBlockID.value==""){
			    alert("Please select Charging Block.");
				mBlockID.focus();
				return false;
			}
			
			if(mUnit.value==""){
			    alert("Please enter Unit.");
				mUnit.focus();
				return false;
			}
			
			if(Trim(mFromDuration.value) == ""){
				alert("Please enter from duration.");
				mFromDuration.focus();
				return false;
			}
			
			if(!isNumber(mFromDuration.value)){
				alert("Invalid data input format.");
				mFromDuration.focus();
				return false;
			}
			
			if(Trim(mToDuration.value) == ""){
				alert("Please enter to duration.");
				mFromDuration.focus();
				return false;
			}
			
			if(!isNumber(mToDuration.value)){
				alert("Invalid data input format.");
				mToDuration.focus();
				return false;
			}
			
			if(Number(mToDuration.value) < Number(mFromDuration.value) && Number(mToDuration.value) > 0){
				alert("From duration should smaller than to duration.");
				mFromDuration.focus();
				return false;
			}
			if(i==frml.numrecord.value-1){
				if(Number(mToDuration.value) < Number(mFromDuration.value) && Number(mToDuration.value) > 0){
				alert("To duration should be zero for last record.");
				mToDuration.focus();
				return false;
			}
			}
			i++;
		}
		return true;
	}
	function ResetForm(){
	
		document.location.href="./?pg=1004";
	}
	
	
</script>
<script>
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate charging block: " + code + "?"))
			window.location = "./?pg=1004&op=deactivate&blockid=" + id;
	}
		function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate charging block: " + code + "?"))
			window.location = "./?pg=1004&op=activate&blockid=" + id;
	}
  function enableFormElements(){
	  for(var i=0;i<document.frml.elements.length;i++){
		if(document.frml.elements[i].type=="text"){
			document.frml.elements[i].disabled=false;
		}
	  }
  }
  
  function addRecord(){
 		if(!CheckValidate()){
			return;
		}
  	newValue = parseInt(frml.numrecord.value);
	newValue += 1;
	enableFormElements();
	frml.numrecord.value=newValue;
	frml.allowsubmit.value = "false";
	frml.operate.value="add";

	frml.submit();
  }
  
  function deleteRecord(){
  	newValue = parseInt(frml.numrecord.value);
	newValue -= 1;
	enableFormElements();
	frml.numrecord.value=newValue;
	frml.allowsubmit.value = "false";
	frml.operate.value="delete";
	frml.submit();
  }

</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top"><?php include("left.php"); ?></td>	
		<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="573">
			   <tr>
				 <td width="368" height="18" align="left" class="formtitle"><b>CHARGING BLOCK DETAIL</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 <tr>
					   <td align="left">&nbsp;Charging Block:</td>
					 	<td colspan="3" align="left" width="80%"><label>
					 	<?php
						$class="boxenabled";
						$disableclass="";
					 	if(isset($blockid) && isset($op)){

							if($op=="edit"){
								$class="boxdisabled";
								$disableclass="disabled";
								$cmbBlockID=$blockid;
							}
							
						}	
					 	?>
						<select name="cmbBlockID" <?php echo $disableclass; ?> style="width:150px;" class="<?php echo $class?>">
							<?php
								$sql="select * from tlkpTarChargeBlock WHERE Status='1'";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbBlockID==intval($row['BlockID']))
										$selected=" selected";
									else
										$selected="";
									echo "<option value='".intval($row['BlockID'])."' $selected>".stripslashes($row['BlockName'])."</option>";
								}
							?>
						</select>
				       </label>	</td>
					 </tr>
					 <tr class="blue" >
					   <td colspan="4"  align="center" >
					  <table border="1" width="100%" cellpadding="0" cellspacing="1" bordercolor="#aaaaaa" style="border-collapse:collapse">
					   <tr class="blue" >
					   <th colspan="2"  align="center" >From (Second) </th>
					   <th width="194" align="center" >To (Second) </th>
					   <th width="178" align="center" >Unit (Round Up Unit) </th>
				     </tr>
					 
				     <?php
				      echo $mytext;
				    ?>
				   <tr class="blue" >
					   <td colspan="4"  align="left" >&nbsp;<a href="javascript:addRecord();">[Add Row]</a>&nbsp;<a href="javascript:deleteRecord();">[Delete Row]</a></td>
				     </tr>
					   </table></td>
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
							<input type="reset" tabindex="10" name="reset" value="Reset" class="button" onclick="ResetForm();"/>
							<input type="button" tabindex="11" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
					 </tr>		
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
							}
						?>			
			     </table>				 </td>
			   </tr>			   
  </table>
			  <input type="hidden" name="pg" id="pg" value="1004">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="blockid" id="blockid" value="<?php echo $blockid?>" />
			  <input type="hidden" name="numrecord" value="<?php echo $numrecord?>">
			  <input type="hidden" name="operate" value="add">
			  <input type="hidden" name="allowsubmit" value="<?php echo $allowsubmit?>">
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>

		
		  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Charging Block Information.</strong></td>
										<td align="right">[<a href="./?pg=1004">Add</a>]</td>
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
														<td  align="left"><font color="#aaaaaa">'.$Description.'</font></td>		';	
														echo "<td align='center'><a href=\"javascript:ActionConfirmationActivate(".$row['BlockID'].",'".$row["BlockName"]."')\"><img src=\"./images/icon/admin.gif\" border=\"0\"></a></td>";																									
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
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1004&amp;subop=2&amp;op=edit&amp;blockid=".$BlockID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$row['BlockID'].",'".$row["BlockName"]."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
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
													
												}

												?>
												
												</tbody>
											</table>										</td>
									</tr>
		</table>
		  </div>	  </td>
  </tr>
	</table>
</td>
</tr>
</table>	
<?php
# Close connection
$mydb->sql_close();
?>
