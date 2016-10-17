
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1025)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		if(intval($txtFromHour)<10){
			$txtFromHour="0".intval($txtFromHour);
		
		}
		if(intval($txtFromMinute)<10){
			$txtFromMinute="0".intval($txtFromMinute);
		}
		if(intval($txtFromSecond)<10){
			$txtFromSecond="0".intval($txtFromSecond);
		}
		if(intval($txtToHour)<10){
			$txtToHour="0".intval($txtToHour);
		}
		if(intval($txtToMinute)<10){
			$txtToMinute="0".intval($txtToMinute);
		}
		if(intval($txtToSecond)<10){
			$txtToSecond="0".intval($txtToSecond);
		}
		$txtTimeBandName=stripslashes(FixQuotes($txtTimeBandName));
		$txtFromTime=$txtFromHour.":".$txtFromMinute.":".$txtFromSecond;
	    $txtToTime=$txtToHour.":".$txtToMinute.":".$txtToSecond;
		$cmbDayType=stripslashes(FixQuotes($cmbDayType));
		$cmbPackageID=intval($cmbPackageID);
	
		//echo "test";
		if($op=="add"){
		
			if(CheckExistingTimeBand($txtTimeBandName)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO tblTarTimeBand(DayType, FromTime, ToTime, TimeBandName,  Status,PackageID) ".
					   "VALUES('".$cmbDayType."','".$txtFromTime."','".$txtToTime."','".$txtTimeBandName."','1','".$cmbPackageID."')";
				//echo $sql;
				if($mydb->sql_query($sql)){				
					$Audit->AddAudit(0,0,"Add Time Band","Add time band name: $txtTimeBandName",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new time band.");
				}else{
				//	print $sql;
					$error = $mydb->sql_error();
						$Audit->AddAudit(0,0,"Add Time Band","Add time band name: $txtTimeBandName",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new time band.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
				$sql = "UPDATE tblTarTimeBand SET TimeBandName='".$txtTimeBandName."', FromTime='".$txtFromTime."', ".
					   "ToTime='".$txtToTime."', DayType='".$cmbDayType."', PackageID='".$cmbPackageID."' WHERE TimeID='".intval($timeid)."';";
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Update Time Band","Update time band ID: $timeid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully update time band.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Update Time Band","Update time band ID: $timeid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update time band.", $error['message']);
				}
				$op="add";
				$txtFromHour="";
				$txtFromSecond="";
				$txtFromMinute="";
				$txtToHour="";
				$txtToMinute="";
				$txtToSecond="";
				$cmbCallType=-1;
				$cmbPackageID=-1;
				
				$txtDescription="";
				
		}
	}elseif(isset($timeid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblTarTimeBand WHERE TimeID='".intval($timeid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtTimeBandName=stripslashes($row['TimeBandName']);
				$cmbPackageID=intval($row["PackageID"]);
			    $txtFromTime=stripslashes($row['FromTime']);
			    $txtToTime=stripslashes($row['ToTime']);
			    $cmbDayType=intval($row['DayType']);
			    
				$txtDescription=stripslashes($row['Description']);
				$arrfromtime=explode(":",$txtFromTime);
				$arrtotime=explode(":",$txtToTime);
				$txtFromHour=$arrfromtime[0]; // Hour
				$txtFromMinute=$arrfromtime[1]; //Minute
				$txtFromSecond=$arrfromtime[2]; //Second
				$txtToHour=$arrtotime[0]; //Hour
				$txtToMinute=$arrtotime[1]; //Minute
				$txtToSecond=$arrtotime[2]; //Seconds
			}
			
		}elseif($op=="deactivate"){
			$sql="delete from  tblTarTimeBand WHERE TimeID=".$timeid;			
				if($mydb->sql_query($sql)){					
					$Audit->AddAudit(0,0,"Delete time band","Deactivate Time Band ID: $timeid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully deactivate time band.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Delete time band","Deactivate Time Band ID: $timeid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate time band.", $error['message']);
				}
				
				$op="add";
				$txtTimeBandName="";
				$cmbPackageID="";
			    $txtFromTime="";
			    $txtToTime="";
			    $cmbDayType="";
			    
				$txtDescription="";
				$txtFromHour=""; // Hour
				$txtFromMinute=""; //Minute
				$txtFromSecond=""; //Second
				$txtToHour=""; //Hour
				$txtToMinute=""; //Minute
				$txtToSecond=""; //Seconds
				$txtDescription="";
		}
	}else{
		
			$txtFromHour=""; // Hour
				$txtFromMinute=""; //Minute
				$txtFromSecond=""; //Second
				$txtToHour=""; //Hour
				$txtToMinute=""; //Minute
				$txtToSecond=""; //Seconds
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to delete time band: " + code + "?"))
			window.location = "./?pg=1025&op=deactivate&timeid=" + id;
	}
	
	function ValidateForm(){

		mTimeBandName = frml.txtTimeBandName;
		mFromHour=frml.txtFromHour;
		mFromMinute=frml.txtFromMinute;
		mFromSecond=frml.txtFromSecond;
		mToHour=frml.txtToHour;
		mToMinute=frml.txtToMinute;
		mToSecond=frml.txtToSecond;
		mPackageID=frml.cmbPackageID;
		
		mDayType=frml.cmbDayType;
		if(mDayType.value==""){
			alert("Please select Day type.");
			mDayType.focus();
			return;
		}
		if(Trim(mTimeBandName.value) == ""){
			alert("Please enter Time Band.");
			mTimeBandName.focus();
			return;
		}
		if(Trim(mPackageID.value) == ""){
			alert("Please select Package.");
			mPackageID.focus();
			return;
		}
		if(Trim(mFromHour.value) == ""){
			alert("Please enter From Hour.");
			mFromHour.focus();
			return;
		}else if(!isInteger(Trim(mFromHour.value))){
			alert("Invalid data input.");
			mFromHour.focus();
			return;
		}else if(Number(mFromHour.value)<0 || Number(mFromHour.value)>23){
			alert("Please input valid From Hour value.");
			mFromHour.focus();
			return;
		}
		
		if(Trim(mFromMinute.value) == ""){
			alert("Please enter From Minute.");
			mFromMinute.focus();
			return;
		}else if(!isInteger(Trim(mFromMinute.value))){
			alert("Invalid data input.");
			mFromMinute.focus();
			return;
		}else if(Number(mFromMinute.value)<0 || Number(mFromMinute.value)>23){
			alert("Please input valid From Minute value.");
			mFromMinute.focus();
			return;
		}
		
		if(Trim(mFromSecond.value) == ""){
			alert("Please enter From Second.");
			mFromSecond.focus();
			return;
		}else if(!isInteger(Trim(mFromSecond.value))){
			alert("Invalid data input.");
			mFromSecond.focus();
			return;
		}
		else if(Number(mFromSecond.value)<0 || Number(mFromSecond.value)>23){
			alert("Please input valid From Minute value.");
			mFromSecond.focus();
			return;
		}
		
		if(Trim(mToHour.value) == ""){
			alert("Please enter To Hour.");
			mToHour.focus();
			return;
		}else if(!isInteger(Trim(mToHour.value))){
			alert("Invalid data input.");
			mToHour.focus();
			return;
		}else if(Number(mToHour.value)<0 || Number(mToHour.value)>23){
			alert("Please input valid To Hour value.");
			mToHour.focus();
			return;
		}
		if(Trim(mToMinute.value) == ""){
			alert("Please enter To Minute.");
			mToMinute.focus();
			return;
		}else if(!isInteger(Trim(mToMinute.value))){
			alert("Invalid data input.");
			mToMinute.focus();
			return;
		}
		else if(Number(mToMinute.value)<0 || Number(mToMinute.value)>59){
			alert("Please input valid minute value.");
			mToMinute.focus();
			return;
		}
		
		if(Trim(mToSecond.value) == ""){
			alert("Please enter To Second.");
			mToSecond.focus();
			return;
		}else if(!isInteger(Trim(mToSecond.value))){
			alert("Invalid data input.");
			mToSecond.focus();
			return;
		}else if(Number(mToSecond.value)<0 || Number(mToSecond.value)>59){
			alert("Please input valid To Second value.");
			mToSecond.focus();
			return;
		}
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1025";
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
var contentname="timebandisp";
var blockname="block";
var url="./administration/ajax_getcontent.php";

function getContent(id){
	 try{
	 	
	 	 var url2=url+"?contentname="+contentname+"&id="+id+""+"&mt=" + new Date().getTime();
		 blockid=id;
	//	alert(url2);
		
		
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
				 //alert(blockname+blockid);
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
	
function ValidateInputTime(datas, txtname){
		mFromHour=frml.txtFromHour;
		mFromMinute=frml.txtFromMinute;
		mFromSecond=frml.txtFromSecond;
		mToHour=frml.txtToHour;
		mToMinute=frml.txtToMinute;
		mToSecond=frml.txtToSecond;
		mDayType=frml.cmbDayType;
		if(datas.length==2){
			
			if(txtname=="txtFromHour"){
				mFromMinute.focus();
			}
			if(txtname=="txtFromMinute"){
				mFromSecond.focus();	
			}
			if(txtname=="txtFromSecond"){
				mToHour.focus();	
			}
			if(txtname=="txtToHour"){
				mToMinute.focus();
			}
			if(txtname=="txtToMinute"){
				mToSecond.focus();
			}
			if(txtname=="txtToSecond"){
				mDayType.focus();
			}
		}
}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="48%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="736" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="522">
			   <tr>
				 <td width="596" height="18" align="left" class="formtitle"><b>TIME BAND</b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%"  bgcolor="#feeac2">	
					 <tr>
					 	<td width="135"  align="left">Time Band Name:</td>
						<td width="307" align="left" ><label>
						  <input type="text" name="txtTimeBandName" class="boxenabled" style="width:206px;" value="<?php print($txtTimeBandName);?>" />
					   </label>							<img src="./images/required.gif" border="0" /></td>
				     </tr>	 
					<tr>
					 	<td  align="left">From Time:</td>
						<td align="left" ><label>
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtFromHour')" name="txtFromHour" class="boxenabled" value="<?php print($txtFromHour);?>" size="2" maxlength="2" /> : 
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtFromMinute')" name="txtFromMinute"  class="boxenabled" value="<?php print($txtFromMinute);?>" size="2" maxlength="2"  /> : 
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtFromSecond')"  name="txtFromSecond"  class="boxenabled" value="<?php print($txtFromSecond);?>"  size="2" maxlength="2" />
					  	 HH:MM:SS <img src="./images/required.gif" border="0" />  </label>		</td>
				     </tr>	
				    <tr>
					 	<td  align="left">To Time:</td>
						<td align="left" ><label>
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtToHour')" name="txtToHour"  class="boxenabled" value="<?php print($txtToHour);?>" size="2" maxlength="2"  /> : 
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtToMinute')" name="txtToMinute"  class="boxenabled" value="<?php print($txtToMinute);?>"  size="2" maxlength="2" /> : 
						  <input type="text" onKeyUp="ValidateInputTime(this.value, 'txtToSecond')" name="txtToSecond"  class="boxenabled" value="<?php print($txtToSecond);?>"  size="2" maxlength="2" />
					 					 HH:MM:SS <img src="./images/required.gif" border="0" />  </label>		</td>
				     </tr>
				    <tr>
				      <td  align="left">Day type:</td>
				      <td align="left" ><select name="cmbDayType"  style="width:217px;">
                        <?php
								$sql="select * from tlkpTarDayType";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbDayType==intval($row['DayTypeID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['DayTypeID'])."' $selected>".stripslashes($row['DayType'])."</option>";
								}
							?>
                      </select>
			          <img src="./images/required.gif" border="0" /></td>
			         </tr>	
								    <tr>
				      <td  align="left">Package:</td>
				      <td align="left" ><select name="cmbPackageID" style="width:217px;" >
                        <?php
								$sql="select * from tblTarPackage where status='1' and serviceid in (1,3,8)";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbPackageID==intval($row['PackageID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['PackageID'])."' $selected>".stripslashes($row['TarName'])."</option>";
								}
							?>
                      </select>
				        <img src="./images/required.gif" border="0" /></td>
			         </tr>					 				 
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
							<input type="button" onclick="Reset()" tabindex="9" name="reset" value="Reset" class="button" />
							<input type="button" tabindex="10" name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
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
			  <input type="hidden" name="pg" id="pg" value="1025">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="timeid" id="timeid" value="<?php echo $timeid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
  <div>
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><strong>Charging Block Information.</strong></td>
										<td align="right">[<a href="./?pg=1025">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th width="2">&nbsp;</th>
													<th width="50">Pack ID</th>
													<th width="141">Package Name</th>
													<th width="112">Cycle Name</th>
													<th width="111">Cycle Fee</th>
													<th width="90">Threshold</th>
												</tr>
												
												
												<?php
												echo "	</tr>";
											
												//$sql="select * from tbl ORDER BY Status Desc";
												$sql="select ttp.PackageID, ttp.TarName,ttp.Threshold,tbc.Name,
														ttp.CycleFee, ttp.Status
													from tblTarPackage ttp 
													left join tlkpBillingCycle tbc 
													on ttp.CycleID=tbc.CycleID
													where ttp.serviceid in (1,3,8) 
													order by ttp.status desc";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
														
													}
													$PackageID=intval($row["PackageID"]);
													$TarName=$row["TarName"];
													$Threshold=intval($row["Threshold"]);
													$CycleName=$row["Name"];
													$CycleFee=doubleval($row["CycleFee"]);
													echo "<div id='cont'>";																		
													
													
													
									
													
													    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==0){
														$rowdeactiveclass=" ";
														echo "<tr $rowdeactiveclass>";
														echo "<td>";
														if(HasTimeBandDetail($PackageID)){
														echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
										
														echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "');  getContent(".$PackageID.");\">";
														echo "</a>";
														}
														
															
														echo '
														<td  align="left"><font color="#aaaaaa">'.$PackageID.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$TarName.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$CycleName.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$CycleFee.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$Threshold.'</font></td>														
													</tr>';
													}else{
														$rowdeactiveclass="";
														echo "<tr class=\"$rowdeactiveclass\">";
														echo "<td class=\"".$rowclass."\">";
														if(HasTimeBandDetail($PackageID)){
															echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
											
															echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "'); getContent(".$PackageID.");\">";
															echo "</a>";
														}
echo '
														<td class="'.$rowclass.'" align="left">'.$PackageID.'</td>
														<td class="'.$rowclass.'" align="left">'.$TarName.'</td>
														<td class="'.$rowclass.'" align="left">'.$CycleName.'</td>
														<td class="'.$rowclass.'" align="left">'.$CycleFee.'</td>
														<td class="'.$rowclass.'" align="left">'.$Threshold.'</td>';
												 
													echo '</tr>';																	
													}
													if(HasTimeBandDetail($PackageID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='7' >";
													//echo GetTimeBandDetail($PackageID);
													echo "<div id='block".$PackageID."'  style='display:none;'>";
													echo "</div>";
													echo "</td>";				
													echo "</tr>";	
																	
													}													   
														$rowcount++;			
													echo "</div>";
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