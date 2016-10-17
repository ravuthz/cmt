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

	if(isset($smt) && (!empty($smt)) && ($pg == 1023) && isset($allowsubmit) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtOverChargeBlockName=stripslashes(FixQuotes($txtOverChargeBlockName));
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
			$DiscDestID=GetNextID("OverChargeBlock");
			if(CheckExistOverChargeBlock($txtOverChargeBlockName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "INSERT INTO tblIspOverChargeBlock(OverChargeBlockID, OverChargeBlockName,Description,Status) ".
						"VALUES('".$DiscDestID."','".$txtOverChargeBlockName."','".$txtDescription."','1');";
		
					$i=0;
					
				
					while($i<$numrecord){
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);
						$DiscRate=doubleval($_POST["txtRate".$i]);
						$SequencEnum=intval($_POST["txtSequenceNum".$i]);

						$sql.="INSERT INTO tblIspOverChargeBlockDetail (OverChargeBlockID,FromDuration,ToDuration,Rate,SequencEnum) ".
						    "VALUES ('".$DiscDestID."','".$FromDur."','".$ToDur."','$DiscRate','".$SequencEnum."'); ";
						
						$i++;
						}
				
				
				if($result=$mydb->sql_query($sql)){	
						$Audit->AddAudit(0,0,"Add ISP Overage Charge Block","Add ISP Overage charge block: $txtOverChargeBlockName",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully add new charge block.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add ISP Overage Charge Block","Add ISP Overage charge block: $txtOverChargeBlockName",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new new charge block.", $error['message']);
				}
				$op="add";
				$txtChargeBlockName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
			}	
			
		}elseif($op=="edit"){
			if(CheckExistOverChargeBlock($txtOverChargeBlockName,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
			
				$sql="";
				$sql.= "UPDATE tblIspOverChargeBlock SET OverChargeBlockID='$hid', OverChargeBlockName='$txtOverChargeBlockName'
					, Description='$txtDescription' , Status = '1' 
					 Where OverChargeBlockID='".intval($hid)."'";
				
					$mydb->sql_freeresult($result);
					$DiscDestID=intval($hid);
					$i=0;
					
					$sql.="Delete from tblIspOverChargeBlockDetail where OverChargeBlockID='".$hid."';";
					$i=0;
					
				
					while($i<$numrecord){
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);
						$DiscRate=doubleval($_POST["txtRate".$i]);
						$SequencEnum=intval($_POST["txtSequenceNum".$i]);

						$sql.="INSERT INTO tblIspOverChargeBlockDetail (OverChargeBlockID,FromDuration,ToDuration,Rate,SequencEnum) ".
						    "VALUES ('".$hid."','".$FromDur."','".$ToDur."','$DiscRate','".$SequencEnum."'); ";
						
						$i++;
					}
				//echo $sql;
				if($result=$mydb->sql_query($sql)){			
						$Audit->AddAudit(0,0,"Update Charge Block","Update charge block ID: $DiscDestID",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update charge block setup.");
				}else{
					$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update Charge Block","Update charge block ID: $DiscDestID",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update charge block setup.", $error['message']);
				}
				$op="add";
				$txtChargeBlockName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
			}
				
		}
	}elseif(isset($hid) && isset($op) && !isset($operate)){
	
		if($op=="edit"){
			$sql="select * from tblIspOverChargeBlock WHERE OverChargeBlockID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtOverChargeBlockName=stripslashes($row['OverChargeBlockName']);
				$txtDescription=stripslashes($row['Description']);
				
				
				$sql2="select * from tblIspOverChargeBlockDetail
						Where OverChargeBlockID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["txtFromDuration".$j]=intval($row2["FromDuration"]);
						$_POST["txtToDuration".$j]=intval($row2["ToDuration"]);
						$_POST["txtRate".$j]=doubleval($row2["Rate"]);
						$_POST["txtSequenceNum".$j]=doubleval($row2["SequencEnum"]);
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operate))
				$numrecord=$j;
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblIspOverChargeBlock set Status='0' WHERE OverChargeBlockID='".$hid."'";
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Deactivate ISP Overage Charge Block","Deactivate ISP overage charge block ID: $hid",$user["FullName"],1,15);	
					$retOut = $myinfo->info("Successfully deactivate charge block.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate ISP Overage Charge Block","Deactivate ISP overage charge block ID: $hid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate charge block.", $error['message']);
				}
				$op="add";
				$txtChargeBlockName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
		}
	}
	
?>
<link type="text/css" rel="stylesheet" href="./style/mystyle.css" />
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate destination specific discount setup: " + code + "?"))
			window.location = "./?pg=1023&op=deactivate&hid=" + id;
	}
	
	function ValidateForm(){
	
		mDescription=frml.txtDescription;
		
		if(mDescription.value==""){
			alert("Please enter description.");
			mDescription.focus();
			return false;
		}
		if(!CheckValidateInput()){
			return false;
		}
		if(!CheckValidate()){
			alert("Overlape information.");
			return false;
		}
		
		frml.allowsubmit.value=true;
		frml.btnSubmit.disabled=true;
		frml.btnReset.disabled=true;
		frml.submit();
	}
	function Reset(){
		document.location.href="./?pg=1016";
	}
	
	function CheckValidateInput(){
	
		var test=true;
		for(var i=0;i<frml.numrecord.value;i++){
			eval("mFromDuration=frml.txtFromDuration"+i);
			eval("mToDuration=frml.txtToDuration"+i);
			eval("mRate=frml.txtRate"+i);

			
			
			if(mFromDuration.value==""){
				alert("Please fill the From Duration value.");
				mFromDuration.focus();
				return false;
			}
			if(Number(mFromDuration.value)<0){
				alert("Invalue From Duration value.");
				mFromDuration.focus();
				return false;
			}
			if(mToDuration.value==""){
				alert("Please fill To Duration value.");
				mToDuration.focus();
				return false;
			}
			if(Number(mToDuration.value)<0){
				alert("Invalid To Duration value.");
				mToDuration.focus();
				return false;
			}
			if(Number(mToDuration.value)<Number(mFromDuration.value) && Number(mToDuration.value)!=0){
				alert("To Duration should be equal or bigger than From Duration.");
				mToDuration.focus();
				return false;
			}
			if(mRate.value==""){
				alert("Please fill discount rate.");
				mRate.focus();
				return false;
			}
			if(Number(mRate.value)<0){
				alert("Invalid rate value.");
				mRate.focus();
				return false;
			}
		}
		return test;
	}
	
	function CheckValidate(){
		var i=0;
		var test=true;
		for(var i=0;i<frml.numrecord.value-1;i++){
			eval("mFromDuration=frml.txtFromDuration"+i);
			eval("mToDuration=frml.txtToDuration"+i);

			for(var j=i+1;j<frml.numrecord.value;j++){

				eval("mFromDuration2=frml.txtFromDuration"+j);
				eval("mToDuration2=frml.txtToDuration"+j);

			}
		
		}
		return test;
	}
	
  function addRecord(){
	 if(!CheckValidateInput()){
	//		return false;
	}
	else if(!CheckValidate()){
		alert("Overlape information.");
	}else{
  	newValue = parseInt(frml.numrecord.value);
	newValue += 1;
	//enableFormElements();
	frml.numrecord.value=newValue;
	frml.allowsubmit.value = "false";
	frml.operate.value="add";
	frml.submit();}
  }
  
  function deleteRecord(){
  	/*newValue = parseInt(frml.numrecord.value);
	newValue -= 1;
	enableFormElements();
	frml.numrecord.value=newValue;
	*/
	frml.allowsubmit.value = "false";
	frml.operate.value="delete";
	frml.submit();
	
  }
	
//  function enableFormElements(){
//	  for(var i=0;i<document.frml.elements.length;i++){
//		if(document.frml.elements[i].type=="text"){
//			document.frml.elements[i].disabled=false;
//		}
//	  }
//  }
  
  //Get time
	  function GetTimeBandByPackage(packageid,target){
		 var myurl="./administration/ajax_infomation.php?id="+packageid;
		// alert(myurl);
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
	  
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="96%">
	<tr>
		<td valign="top" width="214">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="974" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="635">
			   <tr>
				 <td width="595" height="18" align="left" class="formtitle"><strong>Over Charge Block Setup </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr >
					 	<td width="118" align="left"  style="padding-left:10">Block Name :</td>
						<td align="left" colspan="5"><label></label>
						 <input type="text" name="txtOverChargeBlockName" value="<?php echo $txtOverChargeBlockName?>" /> <img src="./images/required.gif" border="0" /></td>
					 </tr>	
					<tr>
						<td align="left" style="padding-left:10" >Description:</td>
				 	  <td align="left" colspan="5"><input type="text" name="txtDescription" value="<?php echo $txtDescription;?>" size="60" />
			 	      <img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
					  <td colspan="6" align="left" style="padding-left:10"><strong>Charge Block</strong></td>
				     </tr>
					 <tr><td colspan="6" >
					 <table width="100%" border="0" bgcolor="#CCCCCC" cellspacing="2">
					 <tr><td>
					  <table width="100%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td width="7%" align="left">&nbsp;</td>
					  <td width="33%" align="center"><strong>From(Minute)</strong></td>
					  <td width="32%" align="center"><strong>To(Minute)</strong></td>
					  <td width="28%" align="center"><strong>Rate (Cent) </strong></td>
					  <td width="28%" align="center"><strong>Sequence Num</strong></td>
					  </tr>
					<?php 
					//check wether the new row is add or not
					
					
					//$numrecord=3;
					$k=0;
					$newindex=0;
					while ($k<$numrecord) {
		
						$txtFromDuration=$_POST["txtFromDuration".$k];
						$txtToDuration=$_POST["txtToDuration".$k];
						$txtRate=$_POST["txtRate".$k];
						$txtSequenceNum=$_POST["txtSequenceNum".$k];
		
						
						
						//Check if the operate is equal add
						if(!isset($operate) || $operate=="add"){
							$newindex=$k;		
						?>
					<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					    <input type="text" name="txtFromDuration<?php echo $newindex?>" value="<?php echo $txtFromDuration;?>"  size="10" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtToDuration<?php echo $newindex?>" value="<?php echo $txtToDuration;?>" size="10"  />				      </td>
					  <td  align="center">
					    <input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo  $txtRate;?>"  size="4" /></td> 
					  <td  align="center">
					    <input type="text" name="txtSequenceNum<?php echo $newindex?>" value="<?php echo  $txtSequenceNum;?>"  size="4" /></td>

					  </tr>
				     <?php
				     		$newindex++;
							}elseif($operate=="delete" && !isset($_POST["chSel".$k])){
								
						?>
						<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					    <input type="text" name="txtFromDuration<?php echo $newindex?>" value="<?php echo $txtFromDuration;?>"  size="10" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtToDuration<?php echo $newindex?>" value="<?php echo $txtToDuration;?>" size="10"  />				      </td>
					  <td  align="center">
					    <input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo  $txtRate;?>"  size="4" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtSequenceNum<?php echo $newindex?>" value="<?php echo  $txtSequenceNum;?>"  size="4" />				      </td>
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
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td colspan="5" align="left" style="padding-left:20"><a href="javascript:addRecord();">Add New Row</a> - <a href="javascript:deleteRecord();">Delete Selected </a></td>
					  </tr>		
					 </table>
					 </td>
					 </tr>			 	
					 </table>
					 </td></tr>			 	
					 <tr> 				  
					  <td align="center" colspan="6">
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
							<input type="button" onclick="Reset()" tabindex="3" name="btnReset" value="Reset" class="button" />
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
			  <input type="hidden" name="pg" id="pg" value="1023">
			  <input type="hidden" name="numrecord"  value="<?php if(isset($numrecord)) echo $numrecord; else echo '1';?>">
			  <input type="hidden" name="operate" value="<?php if(isset($operate)) echo $operate; else echo 'add';?>">
			  <input type="hidden" name="allowsubmit" value="<?php if(isset($allowsubmit)) echo $allowsubmit; else '0';?>">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="desdiscid" value="<?php echo $hid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes"	>
</form>
	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="634">
									<tr>
									 	<td align="left" class="formtitle"><strong>Destination Specific Discount</strong></td>
										<td align="right">[<a href="./?pg=1023">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
													<th width="1">&nbsp;</th>
													<th width="294">Over Charge Block </th>
													<th width="285">Description</th>
													<th width="26">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select * from tblIspOverChargeBlock
													  where status='1'
													  ORDER BY Status Desc
														";
												
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$ID=intval($row["OverChargeBlockID"]);
													$OverChargeBlockName=stripslashes($row["OverChargeBlockName"]);
													$Description=stripslashes($row["Description"]);												
													echo "<div id='cont'>";																		
													echo "</td>";
													
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==0){
													$rowdeactiveclass=" ";
														echo "<tr $rowdeactiveclass>";
													echo "<td>";
													if(HasOverChargeBlockDetail($ID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$ID."\" name=\"img" .$ID. "\" onClick=\"SwitchMenu('block" .$ID. "', 'img".$ID. "');\">";
													echo "</a>";
													}
														echo "</td>";
														echo "
														<td  align=\"left\"><font color=\"#aaaaaa\">".$OverChargeBlockName."</font></td>
														<td  align=\"left\"><font color=\"#aaaaaa\">".$Description."</font></td>
														</tr>";
													}else{
													$rowdeactiveclass="";
													echo "<tr class=\"$rowdeactiveclass\">";
													echo "<td class=\"".$rowclass."\">";
													if(HasOverChargeBlockDetail($ID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$ID."\" name=\"img" .$ID. "\" onClick=\"SwitchMenu('block" .$ID. "', 'img".$ID. "');\">";
													echo "</a>";
													}
													
echo '		
														<td class="'.$rowclass.'" align="left">'.$OverChargeBlockName.'</td>
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>';
														
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1023&amp;op=edit&amp;hid=".$ID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$ID.",'".$OverChargeBlockName."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasOverChargeBlockDetail($ID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='3' >";
													echo GetOverChargeBlockDetail($ID);
												
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
		  </div>	
      </td>
	</tr>
</table>	

<?php
# Close connection
$mydb->sql_close();
?>