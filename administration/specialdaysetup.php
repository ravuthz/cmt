
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
	$arraymonth=array(1=>"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	if(isset($smt) && (!empty($smt)) && ($pg == 1008)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		$fromMonth="";
		$toMonth="";
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		//if(strlen($cmbFromMonth)==1){
			//$cmbFromMonth.="0".$cmbFromMonth;
			//$cmbFromMonth.=$arraymonth[$cmbFromMonth];
	     $fromMonth=$arraymonth[intval($cmbFromMonth)];
		//}
		if(strlen($cmbFromDay)==1){
			$cmbFromDay="0".$cmbFromDay;
		}
		//if(strlen($cmbToMonth)==1){
			//$cmbToMonth.="0".$cmbToMonth;
			$toMonth.=$arraymonth[intval($cmbToMonth)];
			
		
		//}
		if(strlen($cmbToDay)==1){
			$cmbToDay="0".$cmbToDay;
		}
		$cmbSpecialDay=stripslashes($fromMonth)."-".stripslashes($cmbFromDay);
		$cmbLastEffect=stripslashes($toMonth)."-".stripslashes($cmbToDay);
		print $cmbSpecialDay." ".$cmbLastEffect;
		if($op=="add"){
		
			if(CheckExistsSpecialDay($cmbSpecialDay,$cmbLastEffect)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tlkpTarSpecialDay(SpecialDay,ToDay, Description,Status) ".
					   "VALUES('".$cmbSpecialDay."','".$cmbLastEffect."','".$txtDescription."','1');";
				//print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Add Special Day","Add special day $txtDescription",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new special day information.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Special Day","Add special day $txtDescription",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new special day information.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			
			if(CheckExistsSpecialDay($cmbSpecialDay,$cmbLastEffect,true,$specialdayid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE tlkpTarSpecialDay SET SpecialDay='".$cmbSpecialDay."', ToDay='".$cmbLastEffect."', Description='".$txtDescription."' WHERE SpecialDayID='".intval($specialdayid)."';";
			//	print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Update Special Day","Update Special day ID: $specialdayid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully update special day information.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Update Special Day","Update Special day ID: $specialdayid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update special day information.", $error['message']);
				}
				$op="add";
				$cmbFromDay="01";
				$cmbFromMonth="Jan";
				$cmbToDay="01";
				$cmbToMonth="Jan";
				$txtDescription="";
			}			
		}
	}elseif(isset($specialdayid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tlkpTarSpecialDay WHERE SpecialDayID='".intval($specialdayid)."'";
			//echo $sql;
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$cmbSpecialDay=stripslashes($row['SpecialDay']);
				$cmbLastEffect=stripslashes($row['ToDay']);
				$txtDescription=stripslashes($row['Description']);
				$arrspecialday=explode("-",$cmbSpecialDay);
				$arrlasteffectday=explode("-",$cmbLastEffect);
				$cmbFromDay=intval($arrspecialday[1]);
				$cmbFromMonth=array_search($arrspecialday[0],$arraymonth,false);
				$cmbToDay=intval($arrlasteffectday[1]);
				$cmbToMonth=array_search($arrlasteffectday[0],$arraymonth,false);
				//print_r($row);
				//print $cmbToMonth. " ".$cmbFromMonth." ".$cmbSpecialDay." ".$cmbLastEffect;
			}
			
		}elseif($op=="deactivate"){
			$sql="update tlkpTarSpecialDay set status='0' WHERE SpecialDayID='".$specialdayid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){		
					$Audit->AddAudit(0,0,"Deactivate Special Day","Deactivate special day ID $specialdayid",$user["FullName"],1,15);			
					$retOut = $myinfo->info("Successfully deactivate special day information.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Special Day","Deactivate special day ID $specialdayid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate special day information.", $error['message']);
				}
				
				$op="add";
				$cmbFromDay="01";
				$cmbFromMonth="Jan";
				$cmbToDay="01";
				$cmbToMonth="Jan";
				$txtDescription="";
		}elseif($op=="activate"){
				//$sql="select * from tlkpTarSpecialDay where ";
				
				$sql="update tlkpTarSpecialDay set status='1' WHERE SpecialDayID='".$specialdayid."'";
				
				if($mydb->sql_query($sql)){		
					$Audit->AddAudit(0,0,"Activate Special Day","Activate special day ID $specialdayid",$user["FullName"],1,15);			
					$retOut = $myinfo->info("Successfully Activate special day information.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Activate Special Day","Activate special day ID $specialdayid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to Activate special day information.", $error['message']);
				}
				
				$op="add";
				$cmbFromDay="01";
				$cmbFromMonth="Jan";
				$cmbToDay="01";
				$cmbToMonth="Jan";
				$txtDescription="";
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code, code1){
		text = "Do you want to deactivate special day from " +code + " to " + code1 + "?";
		if(confirm(text))
			window.location = "./?pg=1008&op=deactivate&specialdayid=" + id;
	}
	function ActionConfirmationActivate(id, code, code1){
		text = "Do you want to activate special day from " +code + " to " + code1 + "?";
		if(confirm(text))
			window.location = "./?pg=1008&op=activate&specialdayid=" + id;
	}
	function ValidateForm(){

		mFromDay = frml.cmbFromDay;
		mFromMonth=frml.cmbFromMonth;
		mToDay=frml.cmbToDay;
		mToMonth=frml.cmbToMonth;
		var arrMonth = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

		if(Trim(mFromDay.value) == ""){
			alert("Please select day field in From Day:.");
			mFromDay.focus();
			return;
		}
		if(Trim(mFromMonth.value) == ""){
			alert("Please select month field in From Day:.");
			mFromMonth.focus();
			return;
		}
		if(Trim(mToDay.value) == ""){
			alert("Please select day field in Last Effect Day:.");
			mToDay.focus();
			return;
		}
		if(Trim(mToMonth.value) == ""){
			alert("Please select month field in Last Effect Day:.");
			mToMonth.focus();
			return;
		}
		
		if(arrMonth[Number(mFromMonth.selectedIndex)] < Number(mFromDay.value)){
			alert("Please select valid day in field From Day.");
			mFromDay.focus();
			return;
		}
		if(arrMonth[Number(mToMonth.selectedIndex)] < Number(mToDay.value)){
			alert("Please select valid day in field To Day.");
			mToDay.focus();
			return;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1008";
	}
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" >
		<?php include_once("left.php");?>
		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="574">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>Special Day Informatin.</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					<tr>
					 	<td align="left">From Day:</td>
						<td align="left"><label>
						<select name="cmbFromDay">
							<?php
								$day=1;
								$dayshow="";
//							    print $cmbFromDay."test";
								while($day<=31){
									if($cmbFromDay==$day)
										$selected = " selected";
									else
										$selected = "";
									if($day<10){
										$dayshow="0".$day;
									}else{
										$dayshow="".$day;
									}
										
									echo "<option value='".$day."' $selected>".$dayshow."</option>";
									$day++;
								}
							?>
						</select> / <select name="cmbFromMonth" >
						<?php
								$month=1;
								$monthshow="";
								
								while($month<=12){
									if($cmbFromMonth==$month)
										$selected = " selected";
									else
										$selected = "";	
									if($month<10){
										$monthshow="0".$month;
									}else{
										$monthshow="".$month;
									}
									echo "<option value='".$month."' $selected>".$arraymonth[$month]."</option>";
									$month++;
								}
							?>
						</select>
					   </label>	DAY / MONTH</td>
					</tr>	
					<tr>
					 	<td align="left">To Day:</td>
						<td align="left"><label>
						<select name="cmbToDay">
							<?php
								$day=1;
								$dayshow="";
								while($day<=31){
									if($cmbToDay==$day)
										$selected = " selected";
									else
										$selected = "";
									if($day<10){
										$dayshow="0".$day;
									}else{
										$dayshow="".$day;
									}
										
									echo "<option value='".$day."' $selected>".$dayshow."</option>";
									$day++;
								}
							?>
						</select> / <select name="cmbToMonth">
							<?php
								$month=1;
								$monthshow="";
								while($month<=12){
									if($cmbToMonth==$month)
										$selected = " selected";
									else
										$selected = "";	
									if($month<10){
										$monthshow="0".$month;
									}else{
										$monthshow="".$month;
									}
									echo "<option value='".$month."' $selected>".$arraymonth[$month]."</option>";
									$month++;
								}
							?>
						</select>
					   </label>	DAY / MONTH</td>
					</tr>	
					<tr>
						<td align="left">Description:</td>
					 	<td align="left" colspan="3"><input type="text" tabindex="2" name="txtDescription" class="boxenabled" size="70" maxlength="50" value="<?php print($txtDescription);?>" /></td>
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
							<input type="button" onclick="Reset()" tabindex="10" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="11" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
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
			  <input type="hidden" name="pg" id="pg" value="1008">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="specialdayid" id="specaildayid" value="<?php echo $specialdayid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Special Day Information.</strong></td>
										<td align="right">[<a href="./?pg=1008">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											  <th width="116">No.</th>
													<th width="285">From Day</th>
													<th width="285">To Day</th>
													<th width="497">Description</th>
													<th width="25">Edit</th>
												
												</tr>
												
												<?php
												$sql2="SELECT * FROM tlkpTarSpecialDay ORDER BY Status DESC";
												$query2=$mydb->sql_query($sql2);
												$rowcount=0;
												$rowclass="";
												$rowdeactiveclass="";
												while($row=$mydb->sql_fetchrow($query2))																	{
												    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$arrspecialday=explode("-",$row['SpecialDay']);
													$arrlasteffectday=explode("-",$row['ToDay']);
//													if($arrspecialday[1]<10){
//														$specialday="0".$arrspecialday[1];
//													}else{
														$specialday="".$arrspecialday[1];
//													}
//													if($arrlasteffectday[1]<10){
//														$lasteffectday="0".$arrlasteffectday[1];
//													}else{
														$lasteffectday="".$arrlasteffectday[1];
//													}
													if($row['Status']==false){
														$rowdeactiveclass=" style='background-color:#CCCCCC';";
														echo '<tr $rowdeactiveclass>
														<td align="center"><font color="#aaaaaa">'.$row['SpecialDayID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$specialday." ".$arrspecialday[0].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$lasteffectday." ".$arrlasteffectday[0].'</font</td>
														<td  align="left"><font color="#aaaaaa">'.$row['Description'].'</font></td>
														';
													echo '<td  align="center"><a href="javascript:ActionConfirmationActivate('.$row['SpecialDayID'].',\''.$row['SpecialDay'].'\', \''.$row['ToDay'].'\')"><img src="./images/icon/admin.gif" border="0"></a></td>';
													echo '</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr $rowdeactiveclass>
														<td class="'.$rowclass.'" align="center">'.$row['SpecialDayID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$specialday." ".$arrspecialday[0].'</td>
														<td class="'.$rowclass.'" align="left">'.$lasteffectday." ".$arrlasteffectday[0].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['Description'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1008&amp;op=edit&amp;specialdayid='.$row['SpecialDayID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['SpecialDayID'].',\''.$row['SpecialDay'].'\', \''.$row['ToDay'].'\')"><img src="./images/Delete.gif" border="0"></a></td>
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