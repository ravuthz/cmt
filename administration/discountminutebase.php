
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
	if(!isset($numrecord)){
		$numrecord=1;
	}
	if(isset($smt) && (!empty($smt)) && ($pg == 1021) && isset($allowsubmit) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbInvItemID=intval($cmbInvItemID);
		$cmbMode=intval($cmbMode);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
	
			$DiscFreeID=GetNextID("CallAllowanceMinuteBase");
			if(CheckCallMinuteDiscountDiscount($txtDescription)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				if ($cmbMode==1)
					$Mode='Money';
				elseif ($cmbMode==2)
					$Mode='Minute';
				$sql.= "INSERT INTO tblCycleDiscMinuteBase(DiscID,Description,InvItemID,IsActive,Mode) ".
						"VALUES('".$DiscFreeID."','".$txtDescription."','$cmbInvItemID','1','$Mode');";
						$i=0;
						
						while($i<$numrecord){
						
						$MinDuration=stripslashes(FixQuotes($_POST["txtMinDuration".$i]));
						$Rate=doubleval($_POST["txtRate".$i]);

						$sql.="INSERT INTO tblCycleDiscMinuteBaseSub (DiscID,MinDuration,DiscAmount) ".
						    "VALUES ('$DiscFreeID','$MinDuration','$Rate'); ";
						$i++;
						}
						$i=0;
				//print $sql;
				if($result=$mydb->sql_query($sql)){	
						$Audit->AddAudit(0,0,"Add Call Minute Base Discount","Add Call Minute Base Discount Name: $txtDescription",$user["FullName"],1,16);
						$retOut = $myinfo->info("Successfully add new call minute base discount setup.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Call Minute Base Discount","Add Call minute base Discount Name: $txtDescription",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to add new call minute base discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbInvItemID=0;
				$cmbMode=0;
				
				$allowsubmit=false;
				$numrecord=1;
			}	
			
		}elseif($op=="edit"){
			if(CheckCallMinuteDiscountDiscount($txtDescription,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				if ($cmbMode==1)
					$Mode='Money';
				elseif ($cmbMode==2)
					$Mode='Minute';
				$sql.= "UPDATE tblCycleDiscMinuteBase SET Description='$txtDescription', InvItemID='$cmbInvItemID',Mode='$Mode' Where DiscID='".intval($hid)."';";
//				print $sql;
				
					$mydb->sql_freeresult($result);
					$DiscFreeID=intval($hid);
					$i=0;
					
					$sql.="Delete from tblCycleDiscMinuteBaseSub where DiscID='".$DiscFreeID."';";
					$i=0;
						while($i<$numrecord){
						
					$MinDuration=stripslashes(FixQuotes($_POST["txtMinDuration".$i]));
						$Rate=doubleval($_POST["txtRate".$i]);

						$sql.="INSERT INTO tblCycleDiscMinuteBaseSub (DiscID,MinDuration,DiscAmount) ".
						    "VALUES ('$DiscFreeID','$MinDuration','$Rate'); ";
							$i++;
						}
		
				if($result=$mydb->sql_query($sql)){		
				$Audit->AddAudit(0,0,"Update Call Minute Base Discount","Update Call Minute Base Discount Name: $txtDescription, ID: $DiscFreeID",$user["FullName"],1,16);	
						$retOut = $myinfo->info("Successfully update call minute base discount setup.");
				}else{
					$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update Call Minute Base Discount","Update Call Minute Base Discount Name: $txtDescription, ID: $DiscFreeID",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update call minute base discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbInvItemID=0;
				$cmbMode=0;
				$allowsubmit=false;
				$numrecord=1;
				
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblCycleDiscMinuteBase WHERE DiscID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtDescription=stripslashes($row["Description"]);
				$cmbInvItemID=intval($row["InvItemID"]);
				
				if ($row["Mode"]=='Money')
					$cmbMode=1;
				elseif ($row["Mode"]=='Minute')
					$cmbMode=2;
				else 
					$cmbMode=0;
				
				$sql2="Select * from tblCycleDiscMinuteBaseSub Where DiscID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["txtMinDuration".$j]=intval($row2["MinDuration"]);
						$_POST["txtRate".$j]=intval($row2["DiscAmount"]);
						
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operate))
				$numrecord=$j;
			
			}
			
		}elseif($op=="activate"){
			$sql="update tblCycleDiscMinuteBase set IsActive='1' WHERE DiscID='".$hid."'";
				if($mydb->sql_query($sql)){		
					$Audit->AddAudit(0,0,"Activate Call Minute Base Discount","Deactivate Minute Base Allowance Discount ID: $hid",$user["FullName"],1,16);			
					$retOut = $myinfo->info("Successfully activate call minute base discount.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Activate Call Minute Base Discount","Activate Call Minute Base Discount ID: $hid",$user["FullName"],0,16);	
					$retOut = $myinfo->error("Failed to activate call minute base discount.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbInvItemID=0;
				$cmbMode=0;
				$allowsubmit=false;
				$numrecord=1;
		}elseif($op=="deactivate"){
			$sql="update tblCycleDiscMinuteBase set IsActive='0' WHERE DiscID='".$hid."'";
				if($mydb->sql_query($sql)){		
					$Audit->AddAudit(0,0,"Deactivate Call Minute Base Discount","Deactivate Minute Base Allowance Discount ID: $hid",$user["FullName"],1,16);			
					$retOut = $myinfo->info("Successfully deactivate call minute base discount.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Call Minute Base Discount","Deactivate Call Minute Base Discount ID: $hid",$user["FullName"],0,16);	
					$retOut = $myinfo->error("Failed to deactivate call minute base discount.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbInvItemID=0;
				$cmbMode=0;
				$allowsubmit=false;
				$numrecord=1;
		}
	}
	
?>
<link type="text/css" rel="stylesheet" href="./style/mystyle.css" />
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate call minute base discount setup: " + code + "?"))
			window.location = "./?pg=1021&op=deactivate&hid=" + id;
	}
	
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate call minute base discount setup: " + code + "?"))
			window.location = "./?pg=1021&op=activate&hid=" + id;
	}
	function ValidateForm(){
		mDescription=frml.txtDescription;
		
		if(Trim(mDescription.value)==""){
			alert("Please enter Discount Name.");
			mDescription.focus();
			return false;
		}
		
		if(!CheckValidateInput()){
			return false;
		}
		
		if(!CheckValidate()){
			return false;
		}
		
		
		frml.allowsubmit.value=true;
		frml.btnSubmit.disabled=true;
		frml.btnReset.disabled=true;
		frml.submit();
	}
	function Reset(){
		document.location.href="./?pg=1021";
	}
	
		
	function CheckValidateInput(){
	
		for(var i=0;i<frml.numrecord.value;i++){
		
			eval("mMinDuration=frml.txtMinDuration"+i);
		
			eval("mRate=frml.txtRate"+i);
			eval("mIsPercentage=frml.chIsPercentage"+i);
			
			if(mMinDuration.value==""){
				alert("Please fill the Min Duration value.");
				mMinDuration.focus();
				return false;
			}
			if(Number(mMinDuration.value)<0){
				alert("Invalue Minimum Duration value.");
				mMinDuration.focus();
				return false;
			}
			
			if(!Number(mRate.value)){
				alert("Please insert value discount value (should be number).");
				mRate.focus();
				return false;
			}
			if(mRate.value<=0){
				alert("Discount value should be bigger than 0.");
				mRate.focus();
				return false;
			}					
		}
		return true;
	}
	
	function CheckValidate(){
		var i=0;
			for(var i=0;i<frml.numrecord.value-1;i++){
			eval("mMin1=frml.txtMinDuration"+i);
			
			for(var j=i+1;j<frml.numrecord.value;j++){
				eval("mMin2=frml.txtMinDuration"+j);
			
		/*		m1 < m2*/
			  if(mMin1 > mMin2){
					alert("Overlape duration.");
					mMin2.focus();
					return false;
				}
			}
		
		}
		return true;
	}
	
  function addRecord(){
  
	 if(!CheckValidateInput()){
	//		return false;
	}
	else if(!CheckValidate()){
		
	}else{
  	newValue = parseInt(frml.numrecord.value);
	newValue += 1;

	frml.numrecord.value=newValue;
	frml.allowsubmit.value = "false";
	frml.operate.value="add";
	frml.submit();}
  }
  
  function deleteRecord(){
	frml.allowsubmit.value = "false";
	frml.operate.value="delete";
	frml.submit();
	
  }
	//Get time
	  function GetTimeBandByPackage(packageid,target){
		 var myurl="./administration/ajax_infomation.php?id="+packageid+"&ms="+new Date().getTime();
		sendHttpRequest(myurl,'TimeBandInfo',target,true);  
	  }
	  
	  //Call By sendHttpRequest(url,'TimeBandInfo',true);
	  function TimeBandInfo(documentdata,target,respXml){
			var option;
			var mydocument=documentdata;
			//alert(documentdata);
			
			if(respXml==true){
				option=mydocument.getElementsByTagName("option");
				//alert(option.length);
				var selectControl=document.getElementById(target);
				//alert(target);
				selectControl.options.length=0;	
				selectControl.options[0]=new Option("Select Time Band");
				for(var loopindex=0;loopindex<=option.length;loopindex++){
						
						selectControl.options[loopindex+1]=new Option(option[loopindex].firstChild.data);
						selectControl.options[loopindex+1].value=option[loopindex].getAttribute("value");		
						
				}
			}else{
				// 	do nothing
			}
	  }
	  
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="25%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="612" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="457">
			   <tr>
				 <td width="603" height="18" align="left" class="formtitle"><strong>  Call Minute Base Discount Setup </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					<tr>
						<td width="174" align="left" style="padding-left:10" >Discount Name:</td>
				 	  <td width="456" align="left"><input type="text" name="txtDescription" value="<?php echo $txtDescription;?>" size="40" />
			 	      <img src="./images/required.gif" border="0" />
							</td>
					</tr>
					<tr>
					  <td align="left"  style="padding-left:10">Mode:</td>
				      <td align="left"><select name="cmbMode" style="width:284px;">
					  	  <?php 
						  		$money='selected';
								$minute='';
						  		if ($cmbMode==1) 
									$money='selected'; 
								elseif ($cmbMode==2)
								{ 
									$minute='selected'; 
									$money='';
								}
						 
                          echo '<option value=\'1\' '.$money.' >Money</option>';
						  echo '<option value=\'2\' '.$minute.' >Minute</option>';
						 ?>
                      </select></td>
					  
			         </tr>
					 
					<tr>
					  <td align="left"  style="padding-left:10">Invoice Item:</td>
				      <td align="left"><select name="cmbInvItemID" style="width:284px;">
                          <?php
								$sql="select * from tlkpInvoiceItem";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbInvItemID==intval($row['ItemID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['ItemID'])."' $selected>".stripslashes($row['ItemName'])."</option>";
								}
							?>
                      </select></td>
					  
			         </tr>
					<tr>
					  <td colspan="2" align="center">&nbsp;</td>
				     </tr>
					 <tr><td colspan="2" >
					 <table width="100%" border="0" cellspacing="2">
					 <tr><td align="center">
					  <table width="98%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td width="7%" align="left">&nbsp;</td>
					  <td width="61%" align="center"><p><strong>Is Bigger than<br />
					  (Minutes)</strong></p>					    </td>
					  <td width="32%" align="center"><strong>Discount Amount ($) </strong></td>
					  </tr>
					<?php 
					//check wether the new row is add or not
					
					
					//$numrecord=3;
					$k=0;
					$newindex=0;
					while ($k<$numrecord) {
						
						$txtMinDuration=$_POST["txtMinDuration".$k];
						$txtRate=$_POST["txtRate".$k];
						//Check if the operate is equal add
						if(!isset($operate) || $operate=="add"){
							$newindex=$k;		
						?>
					<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center"><input type="text" name="txtMinDuration<?php echo $newindex?>" value="<?php echo $txtMinDuration;?>"  size="9" /></td>
					  <td  align="center"><input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo $txtRate;?>" size="6"></td>
					  </tr>
				     <?php
				     		$newindex++;
							}elseif($operate=="delete" && !isset($_POST["chSel".$k])){
								
						?>
						<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					    <input type="text" name="txtMinDuration<?php echo $newindex?>" value="<?php echo $txtMinDuration;?>"  size="9" />				      </td>
					  <td  align="center"><input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo $txtRate;?>" size="6"></td>
						</tr>
						
						<?php
							$newindex++;
							}
				     		$k++;
						}
						if($operate=="delete"){
							$numrecord=$newindex;
						}
					?>
					<tr>
					  <td align="center" style="padding-left:10">&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td colspan="3" align="left" style="padding-left:20"><a href="javascript:addRecord();">Add New Row</a> - <a href="javascript:deleteRecord();">Delete Selected </a></td>
					  </tr>		
					 </table>
					 </td>
					 </tr>			 	
					 </table>
					 </td></tr>		
					 
					 <tr><td colspan="2" >
					 
					 </td></tr>		 	
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
							<input type="button" onclick="Reset()" name="btnReset" value="Reset" class="button" />
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
			  <input type="hidden" name="pg" id="pg" value="1021">
			  <input type="hidden" name="numrecord"  value="<?php if(isset($numrecord)) echo $numrecord; else echo '1';?>" />
			
			  <input type="hidden" name="operate" value="<?php if(isset($operate)) echo $operate; else echo 'add';?>">
			 
			  <input type="hidden" name="allowsubmit" value="<?php if(isset($allowsubmit)) echo $allowsubmit; else '0';?>">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes"	>
</form>
	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="459">
									<tr>
									 	<td align="left" class="formtitle"><strong> Call Minute Base Discount</strong></td>
										<td align="right">[<a href="./?pg=1021">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
													<th width="1">&nbsp;</th>
													<th width="289">Discount Name</th>
													<th width="288">Invoice Item Name </th>
													<th width="80">Mode </th>
													<th width="27">Edit</th>
												</tr>
												
												
												<?php
												$sql="select mb.DiscID, mb.Description, mb.IsActive, 
													ii.ItemName,mb.Mode
												from
													tblCycleDiscMinuteBase mb 
														left join tlkpInvoiceItem ii
														on mb.invitemid=ii.itemid
													 ORDER BY IsActive, Description";
												
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$DiscFreeID=intval($row["DiscID"]);
													$Description=stripslashes($row["Description"]);
													$InvoiceItemName=stripslashes($row["ItemName"]);
													$Mode=stripslashes($row["Mode"]);
													
													
													
													echo "<div id='cont'>";																		
													
													
													
													
													echo "</td>";
													
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['IsActive']==0){
													$rowdeactiveclass=" ";
														echo "<tr $rowdeactiveclass>";
													echo "<td>";
													if(HasDiscCallMinuteBaseDetail($DiscFreeID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$DiscFreeID."\" name=\"img" .$DiscFreeID. "\" onClick=\"SwitchMenu('block" .$DiscFreeID. "', 'img".$DiscFreeID. "');\">";
													echo "</a>";
													}
														echo "</td>";
														echo "

														<td  align=\"left\"><font color=\"#aaaaaa\">".$Description."</font></td>
														<td  align=\"center\"><font color=\"#aaaaaa\">".$InvoiceItemName."</font></td>
														<td  align=\"center\"><font color=\"#aaaaaa\">".$Mode."</font></td>											";
														echo "<td align=\"center\"><a href=\"javascript:ActionConfirmationActivate(".$DiscFreeID.",'".$Description."')\"><img src=\"./images/icon/admin.gif\" border=\"0\"></a></td>";
														echo "</tr>";
													}else{
													$rowdeactiveclass="";
													echo "<tr class=\"$rowdeactiveclass\">";
													echo "<td class=\"".$rowclass."\">";
													if(HasDiscCallMinuteBaseDetail($DiscFreeID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$DiscFreeID."\" name=\"img" .$DiscFreeID. "\" onClick=\"SwitchMenu('block" .$DiscFreeID. "', 'img".$DiscFreeID. "');\">";
													echo "</a>";
													}
													
echo '
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>
														<td class="'.$rowclass.'" align="center">'.$InvoiceItemName.'</td>
														<td class="'.$rowclass.'" align="center">'.$Mode.'</td>';
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1021&amp;op=edit&amp;hid=".$DiscFreeID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$DiscFreeID.",'".$Description."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasDiscCallMinuteBaseDetail($DiscFreeID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='4' >";
													echo GetCallMinuteBaseDiscountDetail($DiscFreeID);
												
													echo "</td>";				
													echo "</tr>";	
																	
													}
													$rowcount++;			
												}

												?>
												
												</tbody>
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