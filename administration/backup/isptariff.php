
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1022)){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		$cmbPackageID=intval($cmbPackageID);
		$cmbOverChargeBlockID=intval($cmbOverChargeBlockID);
		$cmbTimeID =intval($cmbTimeID);
		$txtEndDate=stripslashes(FixQuotes($txtEndDate));
		$txtEffectiveDate=stripslashes(FixQuotes($txtEffectiveDate));
		$txtRate=floatval($txtRate);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		$cmbInvItemID = intval($cmbInvItemID);
		$cmbTimeInvItemID = intval($cmbTimeInvItemID);
		$cmbGroupNum = intval($cmbGroupNum);
		if($op=="add"){
		
			if(CheckExistingIspTariff($cmbPackageID, $cmbTimeID, $cmbOverChargeBlockID)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				if(trim($txtEndDate)==""){
				
				$sql = "INSERT INTO tblIspTariff (InvItemID,TimeInvItemID, GroupNum, OverChargeBlockID,PackageID,FreeUsage, FreeMailBox , Description,EffectiveDate, TimeID)".
					   "VALUES ('$cmbInvItemID','$cmbTimeInvItemID', '$cmbGroupNum','$cmbOverChargeBlockID','$cmbPackageID','$txtFreeUsage','$txtFreeMailBox','$Description',".
					   "'$txtEffectiveDate', '$cmbTimeID');";
				}else{
				$sql = "INSERT INTO tblIspTariff (InvItemID,TimeInvItemID, GroupNum, OverChargeBlockID,PackageID,FreeUsage, FreeMailBox,Description,EffectiveDate,EndDate, TimeID)".
					   "VALUES ('$cmbInvItemID' ,'$cmbTimeInvItemID', '$cmbGroupNum','$cmbOverChargeBlockID','$cmbPackageID','$txtFreeUsage','$txtFreeMailBox,'$Description',".
					   "'$txtEffectiveDate', '$txtEndDate','$cmbTimeID');";
				}
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add New ISP Tariff","Add new ISP tariff name $txtDescription",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully add new tariff.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add New ISP Tariff","Add new ISP tariff name $txtDescription",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to add new tariff.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 //print "test";
			if(CheckExistingIspTariff($cmbPackageID,$cmbTimeID, $cmbOverChargeBlockID, true,$tarid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
	
				if(trim($txtEndDate)!=""){
				$sql = "UPDATE tblIspTariff SET PackageID='$cmbPackageID',OverChargeBlockID='$cmbOverChargeBlockID',".
					   "FreeUsage='$txtFreeUsage',FreeMailBox = '$txtFreeMailBox', EffectiveDate='$txtEffectiveDate', EndDate='$txtEndDate', ".
					   " Description='$txtDescription' , TimeID = '$cmbTimeID', InvItemID = '$cmbInvItemID', TimeInvItemID = '$cmbTimeInvItemID', ".
					   " GroupNum = '$cmbGroupNum' ".
					   "WHERE tarid='".intval($tarid)."';";
				}else{
					  $sql="UPDATE tblIspTariff SET PackageID='$cmbPackageID',OverChargeBlockID='$cmbOverChargeBlockID', FreeMailBox = '$txtFreeMailBox', ".
					   "FreeUsage='$txtFreeUsage', EffectiveDate='$txtEffectiveDate',  ".
					   " Description='$txtDescription' , TimeID = '$cmbTimeID', InvItemID = '$cmbInvItemID', TimeInvItemID = '$cmbTimeInvItemID', ".
					   " GroupNum = '$cmbGroupNum' ".

					   "WHERE tarid='".intval($tarid)."';";
				}
				//print $sql;
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Update ISP Tariff","Update ISP tariff name $txtDescription, ID: $tarid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully update tariff.");
				}else{
					$error = $mydb->sql_error();
						$Audit->AddAudit(0,0,"Update ISP Tariff","Update ISP tariff name $txtDescription, ID: $tarid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to update tariff.", $error['message']);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbOverChargeBlockID=0;
				$txtEndDate="";
				$txtEffectiveDate="";
				$txtFreeUsage="";
				$txtFreeMailBox = "";
				$cmbTimeID= 0;
				$txtDescription="";
				$cmbInvItemID = 0;
				$cmbTimeInvItemID = 0;
				$cmbGroupNum = 0;
	
			}
				
				
		}
	}elseif(isset($tarid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblIspTariff WHERE tarid='".intval($tarid)."'";
			//echo $sql;
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtFreeUsage=doubleval($row['FreeUsage']);
				$cmbPackageID=intval($row['PackageID']);
				$txtEndDate=stripslashes($row['EndDate']);
				$txtFreeMailBox=intval($row["FreeMailBox"]);
				$cmbOverChargeBlockID=intval($row['OverChargeBlockID']);
				$cmbTimeID=intval($row['TimeID']);
				$txtEffectiveDate=stripslashes($row['EffectiveDate']);
				$cmbTimeInvItemID=stripslashes($row['TimeInvItemID']);
				$cmbInvItemID=stripslashes($row['InvItemID']);
				$cmbGroupNum=stripslashes($row['GroupNum']);
				$txtDescription=stripslashes(FixQuotes($row['Description']));
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblIspTariff set status = 0 WHERE tarid='".$tarid."'";
			//echo $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Deactivate ISP tariff","Deactivate ISP tariff ID: $tarid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully deactivate tariff.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate ISP tariff","Deactivate ISP tariff ID: $tarid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to deactivate tariff.", $error['message']);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbOverChargeBlockID=0;
				$txtEndDate="";
				$txtEffectiveDate="";
				$txtFreeMailBox = "";
				$txtFreeUsage="";
				$cmbTimeID= 0;
				$txtDescription="";
				$cmbInvItemID = 0;
				$cmbTimeInvItemID = 0;
				$cmbGroupNum = 0;
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script type="text/javascript" src="./javascript/date.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate tariff: " + code + "?"))
			window.location = "./?pg=1022&op=deactivate&tarid=" + id;
	}
	function ValidateForm(){	
			mEffectiveDate=frml.txtEffectiveDate;
			mFreeUsage=frml.txtFreeUsage;
			mDescription=frml.txtDescription;
			mPackageID=frml.cmbPackageID;
			mOverChargeBlockID=frml.cmbOverChargeBlockID;
		if(mPackageID.value==0){
			alert("Please select package.");
			mPackageID.focus();
			return false;
		}
		if(mOverChargeBlockID.value==0){
			alert("Please select Charge Block.");
			mOverChargeBlockID.focus();
			return false;
		}
		if(Trim(mFreeUsage.value)==""){
			alert("Please fill free usage amount.");
			mFreeUsage.focus();
			return false;
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
		document.location.href="./?pg=1022";
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
var contentname="isptariff";
var blockname="block";
var url="./administration/ajax_getcontent.php";


function getContent(id){
	 try{
	 	
	 	 var url2=url+"?contentname="+contentname+"&id="+id+"";
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

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="217">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="991" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="634">
			   <tr>
				 <td width="644" height="18" align="left" class="formtitle"><b>ISP TARIFF</b></td>
			   </tr>
			   <tr>
				 <td valign="top" align="left">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
						<tr>
					 	
					   <td width="27%" align="left" nowrap="nowrap">PackageID:</td>
						<td width="73%" align="left"><label>
						<select name="cmbPackageID" style="width:200px;" onchange="GetTimeBandByPackage(this.value,'cmbTimeID');">
						<option value="0">-- Select Package --</option>
							<?php
								$sql="select PackageID, TarName from tblTarPackage where status='1' and serviceid in (1,3,8)";
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
			         </tr>	
			        <tr>
			         	<td width="19%" align="left" nowrap="nowrap">Time Band:</td>
						<td width="27%" align="left"><label>
						<select name="cmbTimeID" id="cmbTimeID" style="width:200px;">
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
					    <td align="left" >Charge Block:</td>
					    <td align="left"><select name="cmbOverChargeBlockID" style="width:200px;">
                          <option value="0">-- Select Charge Block --</option>
                          <?php
								$sql="select OverChargeBlockName, OverChargeBlockID from tblIspOverChargeBlock where status='1' order by OverChargeBlockName";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbOverChargeBlockID==intval($row['OverChargeBlockID']))
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".intval($row['OverChargeBlockID'])."' $selected>".stripslashes($row['OverChargeBlockName'])."</option>";
								}
							?>
                        </select>
                          <img src="./images/required.gif" border="0" /></td>
				</tr>
				<tr>
					    <td align="left" >Tariff Invoice Item:</td>
					    <td align="left"><select name="cmbInvItemID" style="width:200px;">
                          <option value="0">-- Select Charge Block --</option>
                          <?php
								$sql="SELECT     ItemID, ItemGroupID, ItemName, InvoiceSequence, IsVAT, 
									IsNegative, IsManual, IsRecurring, ItemKh
									FROM tlkpInvoiceItem
									order by ItemName";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbInvItemID == intval($row['ItemID']))
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".intval($row['ItemID'])."' $selected>".stripslashes($row['ItemName'])."</option>";
								}
							?>
                        </select>
                          <img src="./images/required.gif" border="0" /></td>
				     </tr>
				<tr>
				<td align="left" >Time Invoice Item:</td>
			       <td align="left"><select name="cmbTimeInvItemID" style="width:200px;">
                          <option value="0">-- Select Charge Block --</option>
                          <?php
								$sql="SELECT     ItemID, ItemGroupID, ItemName, InvoiceSequence, IsVAT, 
									IsNegative, IsManual, IsRecurring, ItemKh
									FROM tlkpInvoiceItem
									order by ItemName";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbTimeInvItemID==intval($row['ItemID']))
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".intval($row['ItemID'])."' $selected>".stripslashes($row['ItemName'])."</option>";
								}
							?>
                        </select>
                          <img src="./images/required.gif" border="0" /></td>
				     </tr>

				<tr>
				<td align="left" >Group:</td>
			       <td align="left"><select name="cmbGroupNum" style="width:200px;">
                          <option value="0">-- Select Charge Block --</option>
                          <?php
								$sql="";
								$arrgroup=array(1,2,3,4,5,6,7);
								$result=$mydb->sql_query($sql);
								$selected="";
								foreach($arrgroup as $key=>$val){
									if($cmbGroupNum==$key)
										$selected = " selected";
									else
										$selected = "";										
									echo "<option value='".$key."' $selected>".$val."</option>";
								}
							?>
                        </select>
                          <img src="./images/required.gif" border="0" /></td>
				     </tr>

				     <tr>
					    <td align="left" >Free Usage: </td>
					    <td align="left"><input type="text" name="txtFreeUsage" value="<?php echo $txtFreeUsage;?>" class="boxenabled" /></td>
			   	     </tr>
					  <tr>
					    <td align="left" >Free Mail Box:</td>
					    <td align="left"><input type="text" name="txtFreeMailBox" value="<?php echo $txtFreeMailBox;?>" class="boxenabled" /></td>
				     </tr>
					<!--  <tr>
					    <td align="left" >Effective Date:</td>
					    <td align="left"> <input type="text" name="txtEffectiveDate" size="21" class="boxenabled" value="<?php print(formatDate($txtEffectiveDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEffectiveDate', '', 'width=200,height=220,top=250,left=350');">
									<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button>		   			   				</td>
				     </tr>
					  
				      <tr>
				        <td align="left" >End Date: </td>
				        <td align="left"> <input type="text" name="txtEndDate"  size="21" class="boxenabled" value="<?php print(formatDate($txtEndDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEffectiveDate', '', 'width=200,height=220,top=250,left=350');">
									<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button>	</td>
			         </tr>-->
			         <tr>
			           <td align="left" >Effectived Date: </td>
			           <td align="left"><input type="text" width="150" name="txtEffectiveDate" size="21" class="boxenabled" value="<?php print(formatDate($txtEffectiveDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEffectiveDate', '', 'width=200,height=220,top=250,left=350');">
						<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button></td>
		             </tr>
			         <tr>
			           <td align="left" >End Date: </td>
			           <td align="left"><input type="text" width="150" name="txtEndDate" size="21" class="boxenabled" value="<?php print(formatDate($txtEndDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEndDate', '', 'width=200,height=220,top=250,left=350');">
						<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button></td>
		             </tr>
			         <tr>
					 	<td align="left" >Description:</td>
						<td align="left"><label>
						  <input type="text" size="70" name="txtDescription" class="boxenabled" value="<?php print($txtDescription);?>" />
					   </label>						</td>		
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
							<input type="button" onclick="Reset()" name="reset" value="Reset" class="button" />
							<input type="button"  name="btnSubmit" value="<?php echo $subvalue;?>" class="button" onClick="ValidateForm();" />						</td>
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
			  <input type="hidden" name="pg" id="pg" value="1022">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="tarid" id="tarid" value="<?php echo $tarid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>	
		  	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="638">
									<tr>
									 	<td width="399" align="left" class="formtitle"><strong>Isp Tariff Information</strong></td>
										<td width="231" align="right">[<a href="./?pg=1012">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th width="1">&nbsp;</th>
													<th width="37">TID</th>
													<th width="37">PID</th>
													<th width="201">Package Name</th>
													<th width="201">TimeBand</th>
													<th width="111">Charge Block</th>
													<th width="83">Cycle Name</th>
													<th width="55">Cycle Fee</th>
													<th width="41">Free Usage</th>
													<th width="39">Mail Box</th>
													
													<th width="111">Effectived Date</th>
													<th width="111">End Date</th>												
												</tr>
												
												
												<?php
												
									$sql="select it.TarID, t.PackageID, t.TarName , bc.Name 'CycleName', t.cyclefee, it.FreeUsage, 	ttb.TimeBandName,		it.FreeMailBox,  cb.OverChargeBlockName, it.EffectiveDate, it.EndDate
											from (tblTarpackage t 
													left join tlkpBillingCycle bc
														on t.CycleID = bc.CycleID
													left join (tblIspTariff it 
																  left join tblIspOverChargeBlock cb
																	on it.OverChargeBlockID = cb.OverChargeBlockID)
															on t.PackageID = it.PackageID)
													left join tblTarTimeBand ttb
															on it.TimeID = ttb.TimeID
													
											where 
											t.serviceid in (1,3,8) and t.status = 1";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$TID = intval($row["TarID"]);
													$PackageID=intval($row["PackageID"]);
													$TarName=$row["TarName"];
													
													$CycleName=$row["CycleName"];
													$CycleFee=doubleval($row["cyclefee"]);
													$FreeUsage=doubleval($row["FreeUsage"]);
													$FreeMail = intval($row["FreeMailBox"]);												
													$isExist=HasTariffDetail($PackageID);
													$EffectiveDate = formatDate($row["EffectiveDate"],5);
													$EndDate = formatDate($row["EndDate"], 5);
													$ChargeBlockName = stripslashes($row["OverChargeBlockName"]);
													$TimeBandName = stripslashes($row["TimeBandName"]);
													
													echo "<div id='cont'>";																															
													echo "</td>";
													
													    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													if($row['Status']==1){
													$rowdeactiveclass=" ";
														echo "<tr $rowdeactiveclass>";
													echo "<td>";
													if($isExist){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "'); getContent(".$PackageID.");\">";
													echo "</a>";
													}		
														echo '
														<td  align="left"><font color="#aaaaaa">'.$TID.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$PackageID.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$TarName.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$TimeBandName.'</font></td>														
														<td  align="left"><font color="#aaaaaa">'.$ChargeBlockName.'</font></td>					
														<td  align="left"><font color="#aaaaaa">'.$CycleName.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$CycleFee.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$FreeUsage.'</font></td>	
														<td  align="left"><font color="#aaaaaa">'.$FreeMail.'</font></td>														
														<td  align="left"><font color="#aaaaaa">'.$EffectiveDate.'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$EndDate.'</font></td>								
													</tr>
													
														';
													}else{
													$rowdeactiveclass="";
													echo "<tr class=\"$rowdeactiveclass\">";
													echo "<td class=\"".$rowclass."\">";
													if($isExist){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$PackageID."\" name=\"img" .$PackageID. "\" onClick=\"SwitchMenu('block" .$PackageID. "', 'img".$PackageID. "'); getContent(".$PackageID.");\">";
													echo "</a>";
													}
													
echo '													<td class="'.$rowclass.'" align="left">'.$TID.'</td>
														<td class="'.$rowclass.'" align="left">'.$PackageID.'</td>
														<td class="'.$rowclass.'" align="left">'.$TarName.'</td>
														<td class="'.$rowclass.'" align="left">'.$TimeBandName.'</td>
														<td class="'.$rowclass.'" align="left">'.$ChargeBlockName.'</td>
														<td class="'.$rowclass.'" align="left">'.$CycleName.'</td>
														<td class="'.$rowclass.'" align="left">'.$CycleFee.'</td>
														<td class="'.$rowclass.'" align="left">'.$FreeUsage.'</td>
														<td class="'.$rowclass.'" align="left">'.$FreeMail.'</td>
														
														<td class="'.$rowclass.'" align="left">'.$EffectiveDate.'</font></td>
														<td class="'.$rowclass.'" align="left">'.$EndDate.'</font></td>								
														<td class="'.$rowclass.'" align="left"><a href="?pg=1022&amp;op=edit&amp;tarid='.$TID.'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmation('.$TID.',\''.$OverChargeBlockName.'\')\"><img src="./images/Delete.gif" border="0"></a></td>';
												 
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

<?php
# Close connection
$mydb->sql_close();
?>