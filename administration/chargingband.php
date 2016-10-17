
<?php
	//error_reporting(E_ALL & E_ERROR & E_NOTICE);
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1005)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtBandName = stripslashes(FixQuotes($txtBandName));
		
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
		
			if(CheckBandNameExisting($txtBandName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tlkpTarChargingBand(BandName, Description, Status) VALUES('".$txtBandName."','".$txtDescription."',1);";
				//print $sql;
				if($mydb->sql_query($sql)){						
					$Audit->AddAudit(0,0,"Add Distance Band",$txtDescription,$user["FullName"],1,15);			
					$retOut = $myinfo->info("Successfully add new charging band.");
					$txtBandName="";
					$txtDescription="";
				}else{
				$Audit->AddAudit(0,0,"Add Distance Band",$txtDescription,$user["FullName"],0,15);	
					$error = $mydb->sql-error();
					$retOut = $myinfo->error("Failed to add new charging band", $error['message']);
				}
			}	
			
			
		}elseif($op=="edit"){
			if(CheckBandNameExisting($txtBandName,true,$bandid)){
			 	
			 			$retOut=$myinfo->warning("The information already existed.");
			}else{
			       
					$sql = "UPDATE tlkpTarChargingBand SET BandName='".$txtBandName."', Description='".$txtDescription."' WHERE DistanceID='".intval($bandid)."';";
//				/	print $sql;
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Update Distance band","Update distance band id $bandid",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update charing band.");
					}else{
						$error=$mydb->sql_error();
						$Audit->AddAudit(0,0,"Update Distance band","Update distance band id $bandid",$user["FullName"],0,15);
						$retOut = $myinfo->error("Failed to update charging band", $error["message"]);
					}
					$op="add";
					$txtBandName="";
					$txtDescription="";
			}
		}
		//echo "second edit";
	}elseif((isset($areacodeid) || isset($bandid)) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tlkpTarChargingBand WHERE DistanceID='".intval($bandid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtBandName=stripslashes($row['BandName']);
				$cmbBlockID=intval($row['BlockID']);
				$txtDescription=stripslashes($row['Description']);
			}
			
		}elseif($op=="deactivate"){
	
			$sql="update tlkpTarChargingBand set status='0' WHERE DistanceID='".$bandid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Deactivate Distance band","Deactivate distance band id: $bandid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully deactivate charging band.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Distance band","Deactivate distance band id: $bandid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate charging band", $error["message"]);
				}
				
				$op="add";
				$txtBandName="";
				$cmbBlockID="";
				$txtDescription="";
		
		}elseif($op=="activate"){
			if(CheckAreaCodeExisting2($bandid)){
				$retOut=$myinfo->warning("Some area code in the specific distance band is already in used.");
			}else{
			$sql="update tlkpTarChargingBand set status='1' WHERE DistanceID='".$bandid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Activate Distance band","Activate distance band id: $bandid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully activate charging band.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Activate Distance band","Activate distance band id: $bandid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to activate charging band", $error["message"]);
				}
				
				$op="add";
				$txtBandName="";
				$cmbBlockID="";
				$txtDescription="";
			}
		}elseif($op=="deletearea"){
			$sql="delete tblTarAreaCode WHERE areacodeid='$areacodeid'";
			  // print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Delete area code","Delete area code id: $bandid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully delete area code.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Delete area code","Delete area code id: $bandid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to delete area code", $error["message"]);
				}
				
				$op="add";		
		}
		//echo "first edit";
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate charging band: " + code + "?"))
			window.location = "./?pg=1005&op=deactivate&bandid=" + id;
	}
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate charging band: " + code + "?"))
			window.location = "./?pg=1005&op=activate&bandid=" + id;
	}
	
	function ActionConfirmationDeleteAreaCode(id, code){
		if(confirm("Do you want to delete area code: " + code + "?"))
			window.location = "./?pg=1005&op=deletearea&areacodeid=" + id;
	}
	
	function ValidateForm(){
		mBandName = document.frml.txtBandName;
		
		if(Trim(mBandName.value) == ""){
			alert("Please enter band name.");
			mBandName.focus();
			return;
		}
		
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	function Reset(){
	
		document.location.href="./?pg=1005";
		
	}
	

	function SwitchMenu(obj, img){  
	if(document.getElementById){
		var imgSrc = document.getElementById(img);
		var el = document.getElementById(obj);
		var ar = document.getElementById("cont").getElementsByTagName("DIV");
			if(el.style.display == "none"){
				for (var i=0; i<ar.length; i++){
					ar[i].style.display = "none";
				}
				imgSrc.src = "images/leaf1.gif";			
				el.style.display = "block";
			}else{
				el.style.display = "none";
				imgSrc.src = "images/plus1.gif";
			}
		}
	}
	
	
var blockid=0;
var contentname="areacode";
var blockname="block";
var url="./administration/ajax_getcontent.php";

function getContent(id,orderby,ordertype){
	 try{
	 	
	 	 var url2=url+"?contentname="+contentname+"&id="+id+"&orderby="+orderby+"&ordertype="+ordertype+"&mt=" + new Date().getTime();
		 blockid=id;
		//alert(url2);
	 	 httpRequest("get",url2,true,respHandle);
	 }catch(err){
		alert("Unconditional Error : "+err.message);
	 }
	// httpRequest("get",url,true,respHandle);
	return false;
 }
 
 //function for XMLHttpRequest onreadystatechange event handler
function respHandle(){
	 try{
		 if(request.readyState == 4){
			 if(request.status == 200){
				 /*All headers received as a single string*/
				 //var headers = request.getAllResponseHeaders();
				 
				 var response= request.responseText;
				 
				 var div = document.getElementById(blockname+blockid);
				//div.className="header";
				 div.innerHTML=response;
			 } else {
				 //request.status is 503 if the application isn't available; 
				 //500 if the application has a bug
				 alert(request.status);
				 alert("A problem occurred with communicating between "+
				 "the XMLHttpRequest object and the server program.");
			 }
		 }//end outer if
	 } catch (err) {
		 alert("It does not appear that the server is "+
		 "available for this application. Please"+
		 " try again very soon. \nError: "+err.message);
	 }
	}	
</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top">
		<?php include("left.php"); ?></td>	
		<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
	
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>Distance Band </b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="98" align="left">Band Name :</td>
						<td align="left" width="464" colspan="3"><label>
						  <input type="text" name="txtBandName" tabindex="1" style="width:150;" class="boxenabled" value="<?php print($txtBandName);?>" />
						</label>							
						<img src="./images/required.gif" border="0" /></td>
					</tr>	
							
					<tr>
						<td align="left">Description:</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="3" name="txtDescription" class="boxenabled" size="72" maxlength="50" value="<?php print($txtDescription);?>" /></td>
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
							<input type="button" onclick="Reset()" tabindex="4" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="5" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
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
			  <input type="hidden" name="pg" id="pg" value="1005">
			   <input type="hidden" name="subop" id="subop" value="1">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="bandid" id="bandid" value="<?php echo $bandid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>

				  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="573">
									<tr>
									 	<td align="left" class="formtitle"><strong>Distance Band  Information.</strong></td>
										<td align="right">[<a href="./?pg=1005">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th width="1">&nbsp;</th>
													<th width="49">ID</th>
													<th width="229">Band Name</th>
													<th width="221">Description</th>
													<th width="27">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select ttcb.DistanceID, ttcb.Status, ttcb.BandName ".
												     "from tlkpTarChargingBand ttcb ".
												     "ORDER BY ttcb.Status DESC";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$BandID=intval($row["DistanceID"]);
													$BandName=stripslashes($row['BandName']);
													$BlockName=stripslashes($row['BlockName']);
													$Description=stripslashes($row['Description']);	echo "<div id='cont'>";		
													if($row['Status']=="0"){
							$rowdeactiveclass=" ";									 echo "<tr $rowdeactiveclass>";
								//	echo "<td>";
								   	echo "<td  >";
								    				if(HasAreaCode($BandID)){
									echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
																		
									echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$BandID."\" name=\"img" .$BandID. "\" onClick=\"SwitchMenu('block" .$BandID. "', 'img".$BandID. "');  getContent(".$BandID.",'','');\">";
									echo "</a>";
													}
													echo "</td>";
											

														echo '
														<td " align="center"><font color="#aaaaaa">'.$BandID.'</font></td>
														<td " align="left"><font color="#aaaaaa">'.$BandName.'</font></td>
										
														<td  align="left"><font color="#aaaaaa">'.$Description.'</font></td>	';
													echo '	<td align="center"><a href="javascript:ActionConfirmationActivate('.$row['DistanceID'].',\''.$row['BandName'].'\')"><img src="./images/icon/admin.gif" border="0"></a></td>';
																										
													echo '</tr>';
													}else{
														$rowdeactiveclass="";
													 echo "<tr $rowdeactiveclass>";
													echo "<td  class='".$rowclass."'>";
								    				if(HasAreaCode($BandID)){
									echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
																		
									echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$BandID."\" name=\"img" .$BandID. "\" onClick=\"SwitchMenu('block" .$BandID. "', 'img".$BandID. "');  getContent(".$BandID.",'','');\">";
									echo "</a>";
													}
													echo "</td>";
echo '
	<td class="'.$rowclass.'" align="center">'.$BandID.'</td>
														<td class="'.$rowclass.'" align="left">'.$BandName.'</td>
													
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1005&amp;op=edit&amp;bandid='.$row['DistanceID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['DistanceID'].',\''.$row['BandName'].'\')"><img src="./images/Delete.gif" border="0"></a></td>
													</tr>';													
													}		
													if(HasAreaCode($BandID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='4' >";
													echo "<div id='block".$BandID."'  style='display:none;'>";
													echo "</div>";
													//echo GetAreaCode($BandID);
												
													echo "</td>";				
													echo "</tr>";	
																	
													}
														$rowcount++;			
													//echo "</div>";
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