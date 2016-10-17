<?php
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
//error_reporting(E_ALL);	
	require_once("./common/agent.php");	
	require_once("function.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
    $arrmonth=array(1=>"Jan",
    		   2=>"Feb",
    		   3=>"Mar",
    		   4=>"Apr",
    		   5=>"May",
    		   6=>"Jun",
    		   7=>"Jul",
    		   8=>"Aug",
    		   9=>"Sept",
    		   10=>"Oct",
    		   11=>"Nov",
    		   12=>"Dec");
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 1009)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$txtName=stripslashes(FixQuotes($txtName));
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
			
			if(CheckExistingBillingCycle($txtName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
			//$=myidGetNextID("FreeCallAllowDiscount");
				$sql = "INSERT INTO tlkpBillingCycle(Name, Description,Status) VALUES('".$txtName."','".$txtDescription."','1');";
				//print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					 $myid=$mydb->sql_nextid();
					 
					// $sql.="delete from tlkpBillingDate WHERE CycleID='".$myid."'; ";
					   # commit transaction
						#$mydb->mssql_commit();
					    $sql="delete from tlkpBillingDate WHERE CycleID='".intval($myid)."'";
					    if($mydb->sql_query($sql)){
							$retOut = $myinfo->info("Successfully update billing cycle.");
							
					    }else{
					    	$error = $mdybd->sql_error();
								$retOut = $myinfo->error("failed can not update billing cycle.", $error['message']);
					    }
						
					    foreach($lsDay as $key=>$value){
							 $arr=explode("-",$value);
							 if((isset($arr[0])  || $arr[0]!=0) && isset($arr[1])){
					    	 	$sqlupdate="insert into tlkpBillingDate (CycleID,Day,Month) VALUES('".intval($myid)."','".intval($arr[1])."','".$arr[0]."')";
							 }else{
							 	$sqlupdate="insert into tlkpBillingDate (CycleID,Day) VALUES('".intval($myid)."','".intval($value)."')";
							 }
					    	//print($sqlupdate);
					    	 $mydb->sql_query($sqlupdate);
					    	 
					    }
					 
					$lsDay="";
					$retOut = $myinfo->info("Successfully add new billing cycle.");
					
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new billing cycle", $error['message']);
				}
			}	
			
			
		}elseif($op=="edit"){
			if(CheckExistingBillingCycle($txtName,true,$cycleid)){
			 	
			 			$retOut=$myinfo->warning("The information already existed.");
			}else{
			 
					$sql = "UPDATE tlkpBillingCycle SET Name='".$txtName."', Description='".$txtDescription."' WHERE CycleID='".intval($cycleid)."';";
					//print $sql;
					if($mydb->sql_query($sql)){
						# commit transaction
						#$mydb->mssql_commit();
					    $sql="delete from tlkpBillingDate WHERE CycleID='".intval($cycleid)."'";
					    if($mydb->sql_query($sql)){
							$retOut = $myinfo->info("Successfully update billing cycle.");
					    }else{
					    	$error = $mdybd->sql_error();
								$retOut = $myinfo->error("failed can not update billing cycle.", $error['message']);
					    }
					    foreach($lsDay as $key=>$value){
							 $arr=explode("-",$value);
							 if((isset($arr[0])  || $arr[0]!=0) && isset($arr[1])){
					    	 	$sqlupdate="insert into tlkpBillingDate (CycleID,Day,Month) VALUES('".intval($cycleid)."','".intval($arr[1])."','".$arr[0]."')";
							 }else{
							 	$sqlupdate="insert into tlkpBillingDate (CycleID,Day) VALUES('".intval($cycleid)."','".intval($value)."')";
							 }
					    	  //print $sqlupdate;
					    	 $mydb->sql_query($sqlupdate);
					    	 
					    }
					    $lsDay="";
					 
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to update billing cycle", $error['message']);
					}
					$op="add";
					$txtName="";
					$txtDescription="";
			}
		}
		//echo "second edit";
	}elseif(isset($cycleid) && isset($op)){
	
		if($op=="edit"){
			$sql="Select * from tlkpBillingCycle WHERE CycleID='".$cycleid."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtName=stripslashes($row['Name']);
				$txtDescription=stripslashes($row['Description']);
				$sql2="select * from tlkpBillingDate WHERE CycleID='".$cycleid."'";
				//print $sql2;
				$lsDay=null;
				if(HasBillingDate($cycleid)){
					$result2=$mydb->sql_query($sql2);
					while($row2=$mydb->sql_fetchrow($result2)){
						if(trim($row2["Month"])=="" || $row2["Month"]==0){
							$lsDay[]=intval($row2["Day"]);
						}else{
							$lsDay[]=stripslashes(intval($row2['Month']))."-".intval($row2["Day"]);
						}
						//print_r($row2);
					}
				}
			}
			
		}elseif($op=="deactivate"){
			$sql="update tlkpBillingCycle set status='0' WHERE CycleID='".$cycleid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					
					$retOut = $myinfo->info("Successfully deactivate billing cycle.");
				}else{					
					$err=$mydb->sql_error();
					$retOut = $myinfo->error("Failed to deactivate billing cycle",$err["message"]);
				}
				$op="add";
				$txtName="";
				$txtDescription="";
		}elseif($op=="activate"){
			$sql="update tlkpBillingCycle set status='1' WHERE CycleID='".$cycleid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					
					$retOut = $myinfo->info("Successfully activate billing cycle.");
				}else{					
					$err=$mydb->sql_error();
					$retOut = $myinfo->error("Failed to activate billing cycle",$err["message"]);
				}
				$op="add";
				$txtName="";
				$txtDescription="";
		}
		//echo "first edit";
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate billing cycle: " + code + "?"))
			window.location = "./?pg=1009&op=deactivate&cycleid=" + id;
	}
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to Activate billing cycle: " + code + "?"))
			window.location = "./?pg=1009&op=activate&cycleid=" + id;
	}
	function ValidateForm(){

		mName = frml.txtName;
		
		if(Trim(mName.value) == ""){
			alert("Please enter Billing Cycle Name.");
			nName.focus();
			return;
		}
		
		frml.btnSubmit.disabled = true;
		var selVals = new Array( );
	
		for (var i = 0; i < document.frml.lsDay.length; i++) {
		    document.frml.lsDay.options[i].selected=true;
		}
		if(i==0){
			alert("Please add cycle date.");
			frml.lsDay.focus();
			return;	
		}
		frml.submit();
	}
	function Reset(){
		document.location.href="./?pg=1009";
		
	}
	function AddDay(){
		var selmonth=document.frml.cmbMonth[document.frml.cmbMonth.selectedIndex].text
		var sel="";
		//alert("err: "+sel);
		if(selmonth==""){
			sel=document.frml.txtDay.value;
		}else{
			sel=selmonth+"-"+document.frml.txtDay.value;
		}
	    
		var newOpt = new Option(sel, document.frml.cmbMonth.value+"-"+document.frml.txtDay.value, false, false);
		
		var checkExist=true;
		
		var selVals = new Array( );
		
		if(Trim(document.frml.txtDay.value)==""){
			alert("Please input the day value.");
		    return checkExist;			
		}
		if(!isInteger(document.frml.txtDay.value)){
			alert("Please input the day value.");
		    return checkExist;
		}else if(Number(document.frml.txtDay.value)<=0){
			alert("Day must be positive number.");
			document.frml.txtDay.focus();
			return;
		}
		if(document.frml.txtDay.value>28){
			alert("Please input valid day value.");
		    return checkExist;
		}				
		for (var i = 0; i < document.frml.lsDay.length; i++) {
		    if (document.frml.lsDay.options[i].value==newOpt.value) {
		        checkExist=false;
		        alert("The day is already existed.");
		        return checkExist;
		    }
		}
		var ismonthday=false;
		//check is month/day cycle
		for (var i = 0; i < document.frml.lsDay.length; i++) {
		    if (document.frml.lsDay.options[i].text.length > 2) {
		        ismonthday=true;
		 		break;
		    }
		}
		
		if(ismonthday==true && document.frml.lsDay.length >= 1 && selmonth==""){
				checkExist=false;
		        alert("The day is invalid billing cycle value.");
		        return checkExist;
		}
		else if(ismonthday==false &&  document.frml.lsDay.length >= 1 && selmonth!=""){
				checkExist=false;
		        alert("The day is invalid billing cycle value.");
		        return checkExist;
		}
		
		document.frml.lsDay.add(newOpt);
		document.frml.txtDay.value="";
		document.frml.cmbMonth.value="0";
		return checkExist;
	}
	function DeleteDay(){
		var selVals = new Array( );
		var test=false;
		for (var i = 0; i < document.frml.lsDay.length; i++) {
		    if (document.frml.lsDay.options[i].selected) {
		        test=true;
		        document.frml.lsDay.remove(document.frml.lsDay.options[i].index);
		        return test;
		    }
		}
		if(test==false){
			alert("Please select any day.");
			return test;
		}
	}
</script>
<table width="724" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td align="left" width="164" valign="top"><?php include("left.php"); ?></td>	
		<td width="705"  align="left" valign="top">	
			<table border="0" align="left" cellpadding="0" cellspacing="5">
				<form name="frml" method="post" action="./">
					<tr>
						<td valign="top" align="left" width="348"> 
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="346">
							 <tr>
								 <td align="left" class="formtitle" height="18"><b>Billing Cycle</b></td>
							 </tr>
							 <tr>
							 	<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">			
									 <tr>
										<td width="75" align="left">Name:</td>
										<td align="left" width="318" colspan="3"><label>
											<input type="text" name="txtName"  class="boxenabled" value="<?php print($txtName);?>" />
										 </label><img src="./images/required.gif" border="0"  title="Billing Cycle Name"/></td>
									</tr>	
									<tr>
									<td colspan="4">
									
					</td>
					</tr>
					<tr>
						<td align="left" valign="top">Description:</td>
					 	<td align="left" colspan="3">
					 	<textarea name="txtDescription"  class="boxenabled" rows="3" cols="30"><?php print($txtDescription);?></textarea></td>
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
							<input type="button" onclick="Reset()"  name="reset" value="Reset" class="button" />
							<input type="button"  name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
					 </tr>	
					 <tr>	
					 <td colspan="4">
					 <?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"4\" align=\"left\">$retOut</td></tr>";
							}
						?>	
					   </td>
					</tr>		
				   </table>
				 </td>
			   </tr>	
			   <input type="hidden" name="pg" id="pg" value="1009">
			   <input type="hidden" name="subop" id="subop" value="1">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="cycleid" id="cycleid" value="<?php echo $cycleid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">		   
  </table>
</td>

<td width="428" align="left" valign="top"  style="padding-top:5px;">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="256">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>Billing Cycle</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="54" align="left">Day:</td>
						<td align="left" valign="middle" width="216"><label>
						  <input type="text" size="4" name="txtDay" class="boxenabled"  />
					   </label>							
						  <img src="./images/required.gif" border="0" /> (Start Billing Day) </td>
				     </tr>			
					 <tr>
					   <td align="left">Month:</td>
					   <td align="left">
					   <select name="cmbMonth">
					   <option value="0" selected="selected"></option>
					   <option value="1">Jan</option>
					   <option value="2">Feb</option>
					   <option value="3">Mar</option>
					   <option value="4">Apr</option>
					   <option value="5">May</option>
					   <option value="6">Jun</option>
					   <option value="7">Jul</option>
					   <option value="8">Aug</option>
					   <option value="9">Sep</option>
					   <option value="10">Oct</option>
					   <option value="11">Nov</option>
					   <option value="12">Dec</option>
					   
					   </select></td>
				     </tr>
					 <tr>
					 	<td width="54" align="left"></td>
						<td align="left">
<select name="lsDay[]" multiple="MULTIPLE" id="lsDay" style="width:150px">
<?php
if(isset($lsDay)){
	foreach ($lsDay as $key=>$value){
		$arr=explode("-",$value);
		if((isset($arr[0]) || $arr[0]!=0) && isset($arr[1])){
			echo "<option value='".$value."'>".$arrmonth[intval($arr[0])]."-".intval($arr[1])."</option>";
		}else{
			echo "<option value='".$value."'>".$value."</option>";
		}
	}
}
?>
</select>				</td>
				     </tr>	 				 
					 <tr> 				  
					  <td align="center" colspan="2">
					
							<input type="button"  name="btnDelete" value="Delete" class="button" onClick="DeleteDay();" />		
								<input type="button"  name="btnAdd"value="Add" class="button" onClick="AddDay();" />					   </td>
					 </tr>		
				   </table>
				 </td>
			   </tr>	
			  		   
  </table>
  
</td>

</tr>
 </form>
<tr><td colspan="2">
	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="617">
									<tr>
									 	<td align="left" class="formtitle"><strong>Billing Cycle Information</strong></td>
										<td align="right">[<a href="./?pg=1009">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" style="border-collapse:collapse" cellspacing="0"   width="100%" id="1" class="" bgcolor="#EFEFEF" bordercolor="#aaaaaa">
												<tr>
													<th width="42">ID</th>
													<th width="141">Cycle Name</th>
													<th width="200">Cycle Date</th>
													<th width="161">Description</th>
													<th width="27">Edit</th>
												
												<tr>
												
												
												<?php
												$sql="select * from tlkpBillingCycle ORDER BY Status Desc, Name ASC";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$CycleID=intval($row["CycleID"]);
													$Name=stripslashes($row['Name']);
													$Description=stripslashes($row['Description']);
												//	echo "<div id='cont'>";													
												//	echo "<tr class=\"$rowclass\">";

													$BillingDate="";
													if(HasBillingDate($CycleID)){
														$BillingDate=GetBillingDate($CycleID);
													}else{
														$BillingDate="N/A";
													}
													
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==false){
														$rowdeactiveclass="  style='background-color:#EFEFEF' ";
														echo '<tr '.$rowdeactiveclass.'>
														<td  align="center"><font color="#aaaaaa">'.$CycleID.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$Name.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$BillingDate.'</font></td>		
														<td  align="left"><font color="#aaaaaa">'.$Description.'</font></td>		';
														echo "<td  align='center'><a href=\"javascript:ActionConfirmationActivate(".$row['CycleID'].",'".$row['Name']."')\"><img src=\"./images/icon/admin.gif\" border=\"0\"></a></td>";
																						
													echo '</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr '.$rowdeactiveclass.'>
														<td class="'.$rowclass.'" align="center">'.$CycleID.'</td>
														<td class="'.$rowclass.'" align="left">'.$Name.'</td>
														<td class="'.$rowclass.'" align="left">'.$BillingDate.'</td>
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>';
													echo "<td  class='".$rowclass."'><a href=\"?pg=1009&amp;subop=2&amp;op=edit&amp;cycleid=".$CycleID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$row['CycleID'].",'".$row['Name']."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';
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