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
	//print_r($_REQUEST);
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 1012)){

		$cmbPackageID=intval($cmbPackageID);
		$cmbTimeID=intval($cmbTimeID);
		$cmbBandID=intval($cmbBandID);
		$cmbGateID=intval($cmbGateID);
		$cmbBlockID=intval($cmbBlockID);
	
		$txtEndDate=stripslashes(FixQuotes($txtEndDate));
		$txtEffectiveDate=stripslashes(FixQuotes($txtEffectiveDate));
		$txtRate=floatval($txtRate);
		$cmbInvItemID=intval($cmbInvItemID);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		if($op=="add"){
			
			if(CheckExistingTariff($cmbBandID,$cmbGateID,$cmbTimeID,$cmbPackageID,$cmbSubService)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				if(trim($txtEndDate)==""){
				
				$sql = "INSERT INTO tblTariff(MinimumCharge,ConnectionCharge,DistanceID,GateID,TimeID,Rate,BlockID, ".
					   "EffectiveDate, Description,InvItemID) ".
					   "VALUES (0,0,'$cmbBandID','$cmbGateID','$cmbTimeID','$txtRate','$cmbBlockID', ".
					   "'$txtEffectiveDate','$txtDescription','$cmbInvItemID');";
				}else{
				$sql = "INSERT INTO tblTariff(MinimumCharge,ConnectionCharge,DistanceID,GateID,TimeID,Rate,BlockID, EndDate, ".
					   "EffectiveDate, Description,InvItemID) ".
					   "VALUES (0,0,'$cmbBandID','$cmbGateID','$cmbTimeID','$txtRate','$cmbBlockID', '$txtEndDate',".
					   "'$txtEffectiveDate','$txtDescription','$cmbInvItemID');";
				}
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add new tariff","Add new tariff GateID: $cmbGateID, TimeID: $cmbTimeID, DistanceID: $cmbBandID, BlockID:  $cmbBlockID, $txtDescription" ,$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new tariff.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add new tariff","Add new tariff GateID: $cmbGateID, TimeID: $cmbTimeID, DistanceID: $cmbBandID, BlockID:  $cmbBlockID, $txtDescription" ,$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new tariff.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
		 	$Audit=new Audit();
			if(CheckExistingTariff($cmbBandID,$cmbGateID,$cmbTimeID,$cmbPackageID,$SubServiceID,true,$tarid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				if(trim($txtEndDate)!=""){
				$sql = "UPDATE tblTariff SET DistanceID='$cmbBandID', ".
					   "GateID='$cmbGateID',TimeID='$cmbTimeID', BlockID='$cmbBlockID',".
					   "Rate='$txtRate', EffectiveDate='$txtEffectiveDate', EndDate='$txtEndDate', invitemid='$cmbInvItemID',".
					   " Description='$txtDescription' ".
					   "WHERE tarid='".intval($tarid)."';";
				}else{
				$sql="UPDATE tblTariff SET DistanceID='$cmbBandID', ".
					   "GateID='$cmbGateID',TimeID='$cmbTimeID', BlockID='$cmbBlockID',".
					   "Rate='$txtRate', EffectiveDate='$txtEffectiveDate',  invitemid='$cmbInvItemID',".
					   " Description='$txtDescription' ".
					   "WHERE tarid='".intval($tarid)."';";
				}
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Update tariff","Update tariff Tariff ID: $tarid. "+$txtDescription,$user["FullName"],1,15);
					
					$retOut = $myinfo->info("Successfully update tariff.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Update tariff","Update tariff Tariff ID: $tarid. "+$txtDescription,$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update tariff.", $error['message']);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbTimeID=0;
				$cmbBandID=0;
				$cmbGateID=0;
				$cmbBlockID=0;
				$cmbInvItemID=0;
				$cmbSubServiceID=0;
				$txtEffectiveDate="";
				$txtRate=0.0;
				$txtDescription="";
			}
				
				
		}
	}elseif(isset($tarid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblTariff WHERE tarid='".intval($tarid)."' and (enddate is null or enddate >= getdate())";
			//echo $sql;
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
			
				$cmbTimeID=intval($row['TimeID']);
				$cmbBandID=intval($row['DistanceID']);
				$cmbGateID=intval($row['GateID']);
				$mysql="select * from tblTarTimeBand where TimeID='$cmbTimeID'";
				$myresult=$mydb->sql_query($mysql);
				while ($myrow=$mydb->sql_fetchrow($myresult)) {
					$cmbPackageID=intval($myrow['PackageID']);
				}
				$cmbBlockID=intval($row['BlockID']);
				$cmbInvItemID=intval($row['InvItemID']);
				$cmbSubService=intval($row['Teleservice']);
				$txtEffectiveDate=stripslashes($row['EffectiveDate']);
				$txtRate=floatval($row['Rate']);
				$txtDescription=stripslashes(FixQuotes($row['Description']));
			}
			
		}elseif($op=="deactivate"){
			$Audit=new Audit();
			
			$sql="update tblTariff set EndDate=GetDate() WHERE tarid='".$tarid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
				
					$retOut = $myinfo->info("Successfully deactivate tariff.");
					$Audit->AddAudit(0,0,"Deactivate Tariff","Deactivate tariff id: $tarid",$user["FullName"],1,15);
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Tariff","Deactivate tariff id: $tarid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate tariff.", $error['message']);
				}
				
				$op="add";
				
				$cmbTimeID=0;
				$cmbBandID=0;
				$cmbGateID=0;
				$cmbBlockID=0;
				$cmbInvItemID=0;
				$cmbSubService=0;
				$txtEffectiveDate="";
				$txtRate=0.0;
				$txtDescription="";
		}
		elseif($op=="activatetariff"){
			$Audit=new Audit();
			
			$sql="update tblTariff set EndDate=null WHERE tarid='".$tarid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
				
					$retOut = $myinfo->info("Successfully activate tariff.");
					$Audit->AddAudit(0,0,"Activate Tariff","Activate tariff id: $tarid",$user["FullName"],1,15);
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Activate Tariff","Activate tariff id: $tarid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to activate tariff.", $error['message']);
				}
				
				$op="add";
				
				$cmbTimeID=0;
				$cmbBandID=0;
				$cmbGateID=0;
				$cmbBlockID=0;
				$cmbInvItemID=0;
				$cmbSubService=0;
				$txtEffectiveDate="";
				$txtRate=0.0;
				$txtDescription="";
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script type="text/javascript" src="./javascript/datetimepicker.js"></script>
<script type="text/javascript" src="./javascript/date.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate tariff: " + code + "?"))
			window.location = "./?pg=1012&op=deactivate&tarid=" + id;
	}
	function ActionConfirmationActivate(id, code){
		if(confirm("Do you want to activate tariff: " + code + "?"))
			window.location = "./?pg=1012&op=activatetariff&tarid=" + id;
	}
	function ValidateForm(){

	
			mTimeID=frml.cmbTimeID;
			mbBandID=frml.cmbBandID;
			mGateID=frml.cmbGateID;
			mBlockID=frml.cmbBlockID;
			
			mEffectiveDate=frml.txtEffectiveDate;
			mRate=frml.txtRate;
			mDescription=frml.txtDescription;
			
		
		if(mTimeID.value==-1 || mTimeID.value==""){
			alert("Please enter Please select time band");
			mTimeID.focus();
			return;
		}
		if(mbBandID.value==-1 || mbBandID.value==""){
			alert("Please enter Please select charging band.");
			mbBandID.focus();
			return;
		}

		if(Trim(mRate.value)==""){
			alert("Please enter rate.");
			mRate.focus();
			return false;
		}
		if(!isNumber(mRate.value)){
			alert("Please enter numeric value in rate.");
			mRate.focus();
			return;
		}
		if(Number(mRate.value)<0){
			alert("Rate must be positive number.");
			mRate.focus();
			return;
		}
		
		if(mBlockID.value=="0"){
			alert("Please select charge block");
			mBlockID.focus();
			return;
		}
		
		if(mEffectiveDate.value == ""){
			alert("Please enter effective date.");
			mEffectiveDate.focus();
			return;
		}
	
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1012";
		//frml.op.value="add";
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
var contentname="tariff";
var blockname="block";
var url="./administration/ajax_getcontent.php";


function getContent(id,orderby,ordertype){
	 try{
	 	
	 	  var url2=url+"?contentname="+contentname+"&id="+id+"&orderby="+orderby+"&ordertype="+ordertype+"&mt=" + new Date().getTime();
		 blockid=id;
		// alert(url);
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
				// alert(response);
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
	
	//Get time
	  function GetTimeBandByPackage(packageid,target){
		 var myurl="./administration/ajax_infomation.php?id="+packageid+"&mt=" + new Date().getTime() ;
		//alert(myurl);
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
				for(var loopindex=0;loopindex<option.length;loopindex++){
						
						selectControl.options[loopindex]=new Option(option[loopindex].firstChild.data);
						selectControl.options[loopindex].value=option[loopindex].getAttribute("value");		
						//alert(option[loopindex].getAttribute("value"));
				}
			}else{
				// 	do nothing
			}
	  }
	  
</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top"><?php include("left.php"); ?></td>	
		<td align="left" valign="top">	
<table border="0" cellpadding="0" cellspacing="5" align="center" width="99%">
	<tr>
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="639">
			   <tr>
				 <td width="644" height="18" align="left" class="formtitle"><b>TARIFF</b></td>
			   </tr>
			   <tr>
				 <td valign="top" align="left">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
						<tr>
					 	
					   <td width="16%" align="left" nowrap="nowrap">PackageID:</td>
						<td width="38%" align="left"><label>
						<select name="cmbPackageID" style="width:200px;" onchange="GetTimeBandByPackage(this.value,'cmbTimeID');">
						<option value="0">Select Package</option>
							<?php
								$sql="select PackageID, TarName from tblTarPackage where status='1' and serviceid not in (1,3,8,4)";
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
					   </label>	<img src="./images/required.gif" border="0" /></td>
					   <td width="19%" align="left" nowrap="nowrap">Time Band:</td>
						<td width="27%" align="left"><label>
						<select name="cmbTimeID" id="cmbTimeID" style="width:150px;">
							<?php
								$sql="select ttb.TimeID, ttb.PackageID, ttb.DayType, ttb.FromTime, 
										ttb.ToTime, ttb.TimeBandName,ttb.Status  
											from tblTarTimeBand ttb
												inner join tblTarPackage ttp
												on ttb.PackageID=ttp.PackageID
											where ttb.status=1 and ttp.status=1 and ttb.PackageID='$cmbPackageID'";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbTimeID==intval($row['TimeID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['TimeID'])."' $selected>".stripslashes($row['TimeBandName'])."</option>";
								}
							?>
						</select>
					   </label></td>
					</tr>				
					
					<tr>
					 	<td align="left" nowrap="nowrap">Charging Band:</td>
					  <td align="left"><label>
						<select name="cmbBandID" style="width:200px;">
							<?php
								$sql="select * from tlkpTarChargingBand 
								where status = 1";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbBandID==intval($row['DistanceID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['DistanceID'])."' $selected>".stripslashes($row['BandName'])."</option>";
								}
							?>
						</select>
					   </label></td>
					   	<td align="left" nowrap="nowrap">Gate Way:</td>
					  <td align="left"><label>
						<select name="cmbGateID" style="width:150px;" tabindex="4">
							<option>&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<?php
								$sql="select * from tlkpTarGateWay WHERE status='1'";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbGateID==intval($row['GateID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['GateID'])."' $selected>".stripslashes($row['GateCode'])."</option>";
								}
							?>
						</select>
					   </label></td>
					</tr>			
					  <tr>
					    <td align="left" nowrap="nowrap">Effective Date:</td>
					    <td align="left"><input id="txtEffectiveDate" style="width:170px;" name="txtEffectiveDate" class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEffectiveDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEffectiveDate));
						?>" type="text" size="25">
				        <a href="javascript:NewCal('txtEffectiveDate','yyyymmdd',true,12)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
					    <td colspan="2" align="left"> (YYYY-MM-DD HH:MM:SS)</td>
				      </tr>
					  
					  <tr>
					 	<td align="left" nowrap="nowrap">End Date:</td>
						<td align="left"><a href="javascript:NewCal('txtEndDate','yyyymmdd',true,12)">
						  <input id="txtEndDate" name="txtEndDate"  style="width:170px;" class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEndDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEndDate));
						?>"   type="text" size="25" />
					    <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
						<td colspan="2" align="left">(YYYY-MM-DD HH:MM:SS)	   				</td>
					  </tr>		
					 
					  <tr>
					    <td align="left" >Invoice Item: </td>
					    <td colspan="2" align="left"><select name="cmbInvItemID"  style="width:180px;">
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
					    <td align="left" colspan="3">&nbsp;</td>
				      </tr>
					  <tr>
					    <td align="left" >Rate:</td>
					    <td align="left"><label>
						  <input type="text" name="txtRate" class="boxenabled" tabindex="6" size="14" value="<?php print($txtRate);?>" />
					  </label>(Cent) <img src="./images/required.gif" border="0" /></td>
					   	<td align="left">Charging Block:</td>
					  <td align="left" colspan="3"><label>
						<select name="cmbBlockID" style="width:150px;" >
							<?php
								$sql="select * from tlkpTarChargeBlock where Status = 1";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbBlockID==intval($row['BlockID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['BlockID'])."' $selected>".stripslashes($row['BlockName'])."</option>";
								}
							?>
						</select>
					   </label></td>
				     </tr>
				     <tr>
					 	<td align="left" >Description:</td>
						<td align="left" colspan="3"><label>
						  <input type="text" size="81" tabindex="8" name="txtDescription" class="boxenabled" value="<?php print($txtDescription);?>" />
					   </label>						</td>		
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
			  <input type="hidden" name="pg" id="pg" value="1012">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="tarid" id="tarid" value="<?php echo $tarid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>	
		  	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="638">
									<tr>
									 	<td width="399" align="left" class="formtitle"><strong>Tariff Information</strong></td>
										<td width="231" align="right">[<a href="./?pg=1012">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th>&nbsp;</th>
													<th width="200">Package ID</th>
													<th width="200">Package Name</th>
													<th width="200">Cycle Name</th>
													<th width="200">Cycle Fee</th>
													<th width="497">Threshold</th>
												
												
												</tr>
												
												
												<?php
												
												$sql="select ttp.PackageID, ttp.TarName,ttp.Threshold,tbc.Name,
														ttp.CycleFee, ttp.Status
													from tblTarPackage ttp 
													left join tlkpBillingCycle tbc 
													on ttp.CycleID=tbc.CycleID 
													where ttp.serviceid not in (1,3,8,4)
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
													$isExist=HasTariffDetail($PackageID);
													
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
													if($isExist){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "'); getContent(".$PackageID.",'','');\">";
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
													if($isExist){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "'); getContent(".$PackageID.",'','');\">";
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
													if($isExist){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='5' >";
													echo "<div id='block".$PackageID."' style='display:none;'>";
													echo "</div>";
													//echo GetTariffDetail($PackageID);
												
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
</td>
</tr>
</table>
<?php
# Close connection
$mydb->sql_close();
?>