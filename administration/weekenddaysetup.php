
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
	$day=array(1=>"Monday",
			   2=>"Tuesday",
			   3=>"Wednesday",
			   4=>"Thursday",
			   5=>"Friday",
			   6=>"Saturday",
			   7=>"Sunday");
	
	//echo $op.$weekendid;
	if(isset($smt) && (!empty($smt)) && ($pg == 1013)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbWeekendDay=intval($cmbWeekendDay);
		
		if($op=="add"){
		
			if(CheckExistsWeekendDay($cmbWeekendDay)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tlkpTarWeekendDays(DayNo) VALUES('".$cmbWeekendDay."');";
				//print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					
					$retOut = $myinfo->info("Successfully add new weekend day.");
				}else{
					#rollback transaction
					#$mydb->mssql_rollback();
					$err=$mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new weekend day", $err["message"]);
				}
			}	
			
		}elseif($op=="edit"){
			if(CheckExistsWeekendDay($cmbWeekendDay,true,$weekendid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
		 		
				$sql = "UPDATE tlkpTarWeekendDays SET DayNo='".$cmbWeekendDay."' WHERE WeekDayID='".$weekendid."';";

				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
				
					$retOut = $myinfo->info("Successfully update weekend day.");
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to update weekend day", $error["message"]);
				}
				$op="add";
				$cmbWeekendDay="";
			}
				
		}
	}elseif(isset($weekendid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tlkpTarWeekendDays WHERE WeekDayID='".intval($weekendid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$cmbWeekendDay=stripslashes($row['DayNo']);
			
			}
			
		}elseif($op=="deactivate"){
			$sql="Delete from tlkpTarWeekendDays WHERE WeekDayID='".$weekendid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){									
					$retOut = $myinfo->info("Successfully delete weekend day.");
				}else{					
					$err= $mydb->sql_error();
					$retOut = $myinfo->error("Failed to delete weekend day.", $err["message"]);
				}
				
				$op="add";
				$cmbWeekendDay="";	
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to delete weekend day: " + code + "?"))
			window.location = "./?pg=1013&op=deactivate&weekendid=" + id;
	}
	
	function ValidateForm(){
		mWeekendDay = frml.cmbWeekendDay;
		
		if(Trim(mWeekendDay.value) == ""){
			alert("Please enter weekend day.");
			mWeekendDay.focus();
			return;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1013";
	}
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>
		</td>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="339">
			   <tr>
				 <td width="340" height="18" align="left" class="formtitle"><b>Weekend Day Setup</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="75" align="left">Day:</td>
						<td align="left" width="491" colspan="3"><label>
						   <select name="cmbWeekendDay" style="width:150;">
							<?php
								
								$selected="";
								foreach ($day as $key=>$value){
									if($cmbWeekendDay==$key)
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".$key."' $selected>".$value."</option>";
								}
							?>
						</select>
					   </label><img src="./images/required.gif" border="0" /></td>
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
			  <input type="hidden" name="pg" id="pg" value="1013">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="weekendid" id="weekendid" value="<?php echo $weekendid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="341">
									<tr>
									 	<td width="372" align="left" class="formtitle"><strong>Weekend Day Information</strong></td>
										<td width="43" align="right">[<a href=".?pg=1013">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
									  		  <th width="34">ID</th>
													<th width="242">Weekend Day</th>
													<th width="27">Edit</th>
												
												</tr>
												
												<?php
												$sql2="SELECT * FROM tlkpTarWeekendDays";
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
													
														$rowdeactiveclass="";
													echo '<tr '.$rowdeactiveclass.'>
														<td class="'.$rowclass.'" align="center">'.$row['WeekDayID'].'</td>
														<td class="'.$rowclass.'" align="left">'.$day[$row['DayNo']].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1013&amp;op=edit&amp;weekendid='.$row['WeekDayID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$row['WeekDayID'].',\''.$day[$row['DayNo']].'\')"><img src="./images/Delete.gif" border="0"></a></td>
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

<?php
# Close connection
$mydb->sql_close();
?>