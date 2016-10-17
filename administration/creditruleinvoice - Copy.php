
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
	
	if(!isset($numrecord)){
		$numrecord=1;
	}
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 1101) && isset($allowsubmit) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtRuleName=stripslashes(FixQuotes($txtRuleName));
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
			$DiscDestID=GetNextID("CreditRuleInvoice");
			if(CheckExistingCreditRuleInvoice($txtRuleName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "INSERT INTO tblCreditRuleInvoice(CredID, CreditRuleInvoice,Description,Status) ".
						"VALUES('".$DiscDestID."','".$txtRuleName."','".$txtDescription."','1');";
		
					$i=0;
					
				
					while($i<$numrecord){
						$FromInvoice=intval($_POST["txtFromInvoice".$i]);
						$ToInvoice=intval($_POST["txtToInvoice".$i]);
						$Action=intval($_POST["cmbAction".$i]);
						$Description=stripslashes(FixQuotes($_POST["txtDescription".$i]));
						$SubAction=intval($_POST["cmbSubAction".$i]);
						$Threshold=doubleval($_POST["txtThreshold".$i]);
						$sql.="INSERT INTO tlkpCreditRuleInvoice (CredID,FromInvoice,ToInvoice,Action,Description,Threshold,Suplementary) ".
						    "VALUES ('$DiscDestID','$FromInvoice','$ToInvoice','$Action','$Description','$Threshold','$SubAction'); ";
						
						$i++;
						}
				
				//print $sql;
				if($result=$mydb->sql_query($sql)){	
						$retOut = $myinfo->info("Successfully add new credit rule invoice.");
						$Audit->AddAudit(0,0,"Add Credit Rule Invoice","Add credit rule invoice - $Description",$user["FullName"],1,17);
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new new credit rule invoice.", $error['message']);
					$Audit->AddAudit(0,0,"Add Credit Rule Invoice","Add credit rule invoice - $Description",$user["FullName"],0,17);
				}
				$op="add";
				$txtRuleName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
			}	
			
		}elseif($op=="edit"){
			if(CheckExistingCreditRuleInvoice($txtRuleName,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "UPDATE tblCreditRuleInvoice SET CreditRuleInvoice='$txtRuleName', Description='$txtDescription' Where CredID='".intval($hid)."'";
				
					$mydb->sql_freeresult($result);
					$DiscDestID=intval($hid);
					$i=0;
					
					$sql.="Delete from tlkpCreditRuleInvoice where CredID='".$DiscDestID."';";
					$i=0;
					
				
					while($i<$numrecord){
						$FromInvoice=intval($_POST["txtFromInvoice".$i]);
						$ToInvoice=intval($_POST["txtToInvoice".$i]);
						$Action=intval($_POST["cmbAction".$i]);
						$Description=stripslashes(FixQuotes($_POST["txtDescription".$i]));
						$SubAction=intval($_POST["cmbSubAction".$i]);
						$Threshold=doubleval($_POST["txtThreshold".$i]);
						$sql.="INSERT INTO tlkpCreditRuleInvoice (CredID,FromInvoice,ToInvoice,Action,Description,Threshold,Suplementary) ".
						    "VALUES ('$DiscDestID','$FromInvoice','$ToInvoice','$Action','$Description','$Threshold','$SubAction'); ";
						
						$i++;
					}
				
				//echo $sql;
				if($result=$mydb->sql_query($sql)){			
						$retOut = $myinfo->info("Successfully update credit rule invoice.");
							$Audit->AddAudit(0,0,"Update Credit Rule Invoice","Update credit rule invoice - $Description",$user["FullName"],1,17);
				}else{
					$error = $mydb->error();
					$retOut = $myinfo->error("Failed to update credit rule invoice.", $error['message']);
					$Audit->AddAudit(0,0,"Update Credit Rule Invoice","Update credit rule invoice - $Description",$user["FullName"],1,17);
				}
				$op="add";
				$txtRuleName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
			}
				
		}
	}elseif(isset($hid) && isset($op) && !isset($operate)){
	
		if($op=="edit"){
			$sql="select * from tblCreditRuleInvoice WHERE CredID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtRuleName=stripslashes($row['CreditRuleInvoice']);
				$txtDescription=stripslashes($row['Description']);
				
				
				$sql2="select * from tlkpCreditRuleInvoice
						Where CredID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["txtFromInvoice".$j]=intval($row2["FromInvoice"]);
						$_POST["txtToInvoice".$j]=intval($row2["ToInvoice"]);
						$_POST["cmbAction".$j]=intval($row2["Action"]);
						$_POST["txtDescription".$j]=stripslashes($row2["Description"]);
						$_POST["txtThreshold".$j]=doubleval($row2["Threshold"]);
						$_POST["cmbSubAction".$j]=intval($row2["Suplementary"]);			
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operate))
				$numrecord=$j;
			}
			
		}elseif($op=="activate"){
			$sql="update tblCreditRuleInvoice set Status='1' WHERE CredID='".$hid."'";
				if($mydb->sql_query($sql)){					
					$retOut = $myinfo->info("Successfully activate credit rule invoice.");
						$Audit->AddAudit(0,0,"Activate Credit Rule Invoice","Activate credit rule invoice - ID: $hid",$user["FullName"],1,17);
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to activate credit rule invoice.", $error['message']);
					$Audit->AddAudit(0,0,"Activate Credit Rule Invoice","Activate credit rule invoice - ID: $hid",$user["FullName"],0,17);
				}
				$op="add";
				$txtRuleName="";
				$txtDescription="";
				$allowsubmit=false;
				$numrecord=1;
		}elseif($op=="deactivate"){
			$sql="update tblCreditRuleInvoice set Status='0' WHERE CredID='".$hid."'";
				if($mydb->sql_query($sql)){					
					$retOut = $myinfo->info("Successfully deactivate credit rule invoice.");
						$Audit->AddAudit(0,0,"Deactivate Credit Rule Invoice","Deactivate credit rule invoice - ID: $hid",$user["FullName"],1,17);
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to deactivate credit rule invoice.", $error['message']);
					$Audit->AddAudit(0,0,"Deactivate Credit Rule Invoice","Deactivate credit rule invoice - ID: $hid",$user["FullName"],0,17);
				}
				$op="add";
				$txtRuleName="";
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
		//if(confirm("Do you want to deactivate credit rule: " + code + "?"))
		//	window.location = "./?pg=1101&op=deactivate&hid=" + id;
		alert("S");
	}
	//ActionConfirmationActivate
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate credit rule: " + code + "?"))
			window.location = "./?pg=1101&op=activate&hid=" + id;
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
		document.location.href="./?pg=1101";
	}
	
	function CheckValidateInput(){
	
		var test=true;
		for(var i=0;i<frml.numrecord.value;i++){
			eval("mFromInvoice=frml.txtFromInvoice"+i);
			eval("mToInvoice=frml.txtToInvoice"+i);
				eval("mAction=frml.cmbAction"+i);
			eval("mSubAction=frml.cmbSubAction"+i);

			

			
			
			if(mFromInvoice.value==""){
				alert("Please fill the From Invoice value.");
				mFromInvoice.focus();
				return false;
			}
			if(Number(mFromInvoice.value)<0){
				alert("Invalue From Invoice value.");
				mFromInvoice.focus();
				return false;
			}
			if(mToInvoice.value==""){
				alert("Please fill To Invoice value.");
				mToInvoice.focus();
				return false;
			}
			if(Number(mToInvoice.value)<0){
				alert("Invalid To Invoice value.");
				mToInvoice.focus();
				return false;
			}
			if(Number(mToInvoice.value)<Number(mFromInvoice.value) && Number(mToInvoice.value)!=0){
				alert("To Invoice should be equal or bigger than From Invoice.");
				mToInvoice.focus();
				return false;
			}
				if(mAction.value==0){
				alert("Please select any action.");
				mAction.focus();
				return false;
			}
			
			if(mSubAction.value==0){
				alert("Please select any suplementary action.");
				mSubAction.focus();
				return false;
			}
		}
		return test;
	}
	
	function CheckValidate(){
		var i=0;
		var test=true;
		for(var i=0;i<frml.numrecord.value-1;i++){
			eval("mFromInvoice=frml.txtFromInvoice"+i);
			eval("mToInvoice=frml.txtToInvoice"+i);

			for(var j=i+1;j<frml.numrecord.value;j++){

				eval("mFromInvoice2=frml.txtFromInvoice"+j);
				eval("mToInvoice2=frml.txtToInvoice"+j);

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
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="160">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="611" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="611">
			   <tr>
				 <td width="645" height="18" align="left" class="formtitle"><strong>Credit Rule Invoice </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr >
					 	<td width="118" align="left"  style="padding-left:10">Rule Name:</td>
						<td align="left" colspan="5"><label></label>
						 <input type="text" name="txtRuleName" value="<?php echo $txtRuleName?>" /> <img src="./images/required.gif" border="0" /></td>
					 </tr>	
					<tr>
						<td align="left" style="padding-left:10" >Description:</td>
				 	  <td align="left" colspan="5"><input type="text" name="txtDescription" value="<?php echo $txtDescription;?>" size="60" />
			 	      <img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
					  <td colspan="6" align="left" style="padding-left:10"><strong>Credit Rule Setup  :</strong></td>
				     </tr>
					 <tr><td colspan="6" >
					 <table width="100%" border="0" bgcolor="#CCCCCC" cellspacing="2">
					 <tr><td>
					  <table width="100%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td width="5%" align="left">&nbsp;</td>
					  <td width="21%" align="center"><strong>From Invoice </strong></td>
					  <td width="23%" align="center"><strong>To Invoice </strong></td>
					  <td width="18%" align="center"><strong>Threshold (Cent) </strong></td>
					  <td width="18%" align="center"><strong>Action</strong></td>
					  <td width="33%" align="center"><strong>Sub Action </strong></td>
					  <td width="33%" align="center"><strong>Description</strong></td>
					  </tr>
					<?php 
					//check wether the new row is add or not
					
					
					//$numrecord=3;
					$k=0;
					$newindex=0;
					while ($k<$numrecord) {
		
						$txtFromInvoice=$_POST["txtFromInvoice".$k];
						$txtToInvoice=$_POST["txtToInvoice".$k];
						$cmbAction=$_POST["cmbAction".$k];
						$txtDescription=$_POST["txtDescription".$k];
						$txtThreshold=$_POST["txtThreshold".$k];
						$cmbSubAction=$_POST["cmbSubAction".$k];
						
						
						//Check if the operate is equal add
						if(!isset($operate) || $operate=="add"){
							$newindex=$k;		
						?>
					<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					    <input type="text" name="txtFromInvoice<?php echo $newindex?>" value="<?php echo $txtFromInvoice;?>"  size="6" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtToInvoice<?php echo $newindex?>" value="<?php echo $txtToInvoice;?>" size="6"  />				      </td>
					  <td  align="center"><input type="text" name="txtThreshold<?php echo $newindex?>" value="<?php echo $txtThreshold;?>" size="6" /></td>
					  <td  align="center">
					  <select name="cmbAction<?php echo $newindex?>">
					  
					  <option value="0">--Select Action--</option>
					  <?php 
//					 $action=Array(1=>"Active",2=>"Bar",3=>"Suspend",4=>"Terminate",5=>"Demand");
					  foreach($gl_action as $key=>$value){
					  		$select="";
							if($key==$cmbAction)$select=" selected";
					  ?>
					  <option value="<?php echo $key?>" <?php echo $select?>><?php echo $value?></option>
					  <?php }?>
					  </select></td>
					  <td  align="center"><select name="cmbSubAction<?php echo $newindex?>">
                        <option value="0">--Select Action--</option>
                        <?php 
					  //$action=Array(1=>"Income",2=>"Outgoing(Loc+Int)",3=>"Outgoing(Int)");
					  foreach($gl_action_sub as $key=>$value){
					  		$select="";
							if($key==$cmbSubAction)$select=" selected";
					  ?>
                        <option value="<?php echo $key?>" <?php echo $select?>><?php echo $value?></option>
                        <?php }?>
                      </select></td>
					  <td  align="center"><input type="text" name="txtDescription<?php echo $newindex?>" value="<?php echo $txtDescription?>" size="15"  /></td>
					  </tr>
				     <?php
				     		$newindex++;
							}elseif($operate=="delete" && !isset($_POST["chSel".$k])){
								
						?>
						<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					    <input type="text" name="txtFromInvoice<?php echo $newindex?>" value="<?php echo $txtFromInvoice;?>"  size="6" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtToInvoice<?php echo $newindex?>" value="<?php echo $txtToInvoice;?>" size="6"  />				      </td>
					  <td  align="center"><input type="text" name="txtThreshold<?php echo $newindex?>" value="<?php echo $txtThreshold;?>" size="6" /></td>
					  <td  align="center"><select name="cmbAction<?php echo $newindex?>">
					  
					  <option value="0">--Select Action--</option>
					  <?php 
					  $action=Array(1=>"Active",2=>"Bar",3=>"Suspend",4=>"Terminate",5=>"Demand");
					  foreach($gl_action as $key=>$value){
					  		$select="";
							if($key==$cmbAction)$select=" selected";
					  ?>
					  <option value="<?php echo $key?>" <?php echo $select?>><?php echo $value?></option>
					  <?php }?>
					  </select></td>
					  <td  align="center"><select name="cmbSubAction<?php echo $newindex?>">
                        <option value="0">--Select Action--</option>
                        <?php 
					  $action=Array(1=>"Income",2=>"Outgoing(Loc+Int)",3=>"Outgoing(Int)");
					  foreach($gl_action_sub as $key=>$value){
					  		$select="";
							if($key==$cmbSubAction)$select=" selected";
					  ?>
                        <option value="<?php echo $key?>" <?php echo $select?>><?php echo $value?></option>
                        <?php }?>
                      </select></td>
					  <td  align="center"><input type="text" name="txtDescription<?php echo $newindex?>" value="<?php echo $txtDescription?>" size="15"></td>
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
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td colspan="7" align="left" style="padding-left:20"><a href="javascript:addRecord();">Add New Row</a> - <a href="javascript:deleteRecord();">Delete Selected </a></td>
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
			  <input type="hidden" name="pg" id="pg" value="1101">
			  <input type="hidden" name="numrecord"  value="<?php if(isset($numrecord)) echo $numrecord; else echo '1';?>">
			  <input type="hidden" name="operate" value="<?php if(isset($operate)) echo $operate; else echo 'add';?>">
			  <input type="hidden" name="allowsubmit" value="<?php if(isset($allowsubmit)) echo $allowsubmit; else '0';?>">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="desdiscid" value="<?php echo $hid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes"	>
</form>
	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="630">
									<tr>
									 	<td align="left" class="formtitle"><strong>Credit Rule Management </strong></td>
										<td align="right">[<a href="./?pg=1101">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
													<th width="1">&nbsp;</th>
													<th width="294">Credit Rule Name </th>
													<th width="285">Description</th>
													<th width="26">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select * from tblCreditRuleInvoice
													
													  ORDER BY Status Desc
														";
												//print $sql;
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$ID=intval($row["CredID"]);
													$RuleName=stripslashes($row["CreditRuleInvoice"]);
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
														if(HasCreditRuleInvoiceDetail($ID)){
															echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
															echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$ID."\" name=\"img" .$ID. "\" onClick=\"SwitchMenu('block" .$ID. "', 'img".$ID. "');\">";
															echo "</a>";
														}
														echo "</td>";
														echo "
															<td  align=\"left\"><font color=\"#aaaaaa\">".$RuleName."</font></td>
															<td  align=\"left\"><font color=\"#aaaaaa\">".$Description."</font></td>";
								echo "<td align=\"center\"><a href=\"javascript:ActionConfirmationActivate(".$ID.",'".$RuleName."')\"><img src=\"./images/icon/admin.gif\" border=\"0\"></a></td>";
							echo "								</tr>";
													}else{
														$rowdeactiveclass="";
														echo "<tr class=\"$rowdeactiveclass\">";
														echo "<td class=\"".$rowclass."\">";
														if(HasCreditRuleInvoiceDetail($ID)){
														echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
										
														echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$ID."\" name=\"img" .$ID. "\" onClick=\"SwitchMenu('block" .$ID. "', 'img".$ID. "');\">";
														echo "</a>";
													}
													
echo '		
														<td class="'.$rowclass.'" align="left">'.$RuleName.'</td>
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>';
														
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1101&amp;op=edit&amp;hid=".$ID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$ID.",'".$RuleName."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasCreditRuleInvoiceDetail($ID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='3' >";
													echo GetCreditRuleInvoiceDetail($ID);
												
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