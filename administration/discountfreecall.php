
<?php
	require_once("./common/agent.php");	
	require_once("function.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright � 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
	$Audit=new Audit();
	if(!isset($numrecord)){
		$numrecord=1;
	}
	if(!isset($numrecorddes)){
		$numrecorddes=1;
	}
	if(isset($smt) && (!empty($smt)) && ($pg == 1018) && isset($allowsubmit) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbRecChargeID=intval($cmbRecChargeID);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
	
			$DiscFreeID=GetNextID("FreeCallAllowDiscount");
			if(CheckFreeCallDiscount($cmbRecChargeID,$txtDescription)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "INSERT INTO tblEventDiscFreeCallAllowance(DiscFreeAllowID, RecChargeID,Description,IsActive) ".
						"VALUES('".$DiscFreeID."','".$cmbRecChargeID."','".$txtDescription."','1');";
						$i=0;
						while($i<$numrecord){
						
						$FreeDurDescription=stripslashes(FixQuotes($_POST["txtFreeDurDescription".$i]));
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);

						$sql.="INSERT INTO tlkpEventDiscFreeCallAllowanceDuration (DiscFreeAllowID,Description,FromDur,ToDur) ".
						    "VALUES ('$DiscFreeID','$FreeDurDescription','$FromDur','$ToDur'); ";
						$i++;
						}
						$i=0;
						while($i<$numrecorddes){
						
						$cmbDistanceID=intval($_POST["cmbDistanceID".$i]);
						$cmbTimeID=intval($_POST["cmbTimeID".$i]);

						$sql.="INSERT INTO tlkpEventDiscFreeCallAllowance (DiscFreeAllowID,DistanceID, TimeID) ".
						    "VALUES ('$DiscFreeID','$cmbDistanceID','$cmbTimeID'); ";
						$i++;
						}
				//print $sql;
				if($result=$mydb->sql_query($sql)){	
						$Audit->AddAudit(0,0,"Add Free Call Allowance Discount","Add free call allowance discount description $txtDescription",$user["FullName"],1,16);
						$retOut = $myinfo->info("Successfully add new free call allowance discount setup.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Free Call Allowance Discount","Add free call allowance discount description $txtDescription",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to add new free call allowance discountsetup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$allowsubmit=false;
				$numrecord=1;
			}	
			
		}elseif($op=="edit"){
			if(CheckFreeCallDiscount($cmbRecChargeID,$txtDescription,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "UPDATE tblEventDiscFreeCallAllowance SET RecChargeID='$cmbRecChargeID', Description='$txtDescription' Where DiscFreeAllowID='".intval($hid)."';";
//				print $sql;
				
					$mydb->sql_freeresult($result);
					$DiscFreeID=intval($hid);
					$i=0;
					
					$sql.="Delete from tlkpEventDiscFreeCallAllowanceDuration where DiscFreeAllowID='".$DiscFreeID."';";
					$sql.="Delete from tlkpEventDiscFreeCallAllowance where DiscFreeAllowID='".$DiscFreeID."';";
						$i=0;
						while($i<$numrecord){
						
						$FreeDurDescription=stripslashes(FixQuotes($_POST["txtFreeDurDescription".$i]));
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);

						$sql.="INSERT INTO tlkpEventDiscFreeCallAllowanceDuration (DiscFreeAllowID,Description,FromDur,ToDur) ".
						    "VALUES ('$DiscFreeID','$FreeDurDescription','$FromDur','$ToDur'); ";
						$i++;
						}
						$i=0;
						while($i<$numrecorddes){
						
						$cmbDistanceID=intval($_POST["cmbDistanceID".$i]);
						$cmbTimeID=intval($_POST["cmbTimeID".$i]);

						$sql.="INSERT INTO tlkpEventDiscFreeCallAllowance (DiscFreeAllowID,DistanceID, TimeID) ".
						    "VALUES ('$DiscFreeID','$cmbDistanceID','$cmbTimeID'); ";
						$i++;
						}
		
				if($result=$mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Update Free Call Allowance","Update free call allowance id: $DiscFreeID",$user["FullName"],1,16);
						$retOut = $myinfo->info("Successfully update free call allowance discount setup.");
				}else{
					$error = $mydb->error();
							$Audit->AddAudit(0,0,"Update Free Call Allowance","Update free call allowance id: $DiscFreeID",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update free call allowance discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$allowsubmit=false;
				$numrecord=1;
				$numrecorddes=1;
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblEventDiscFreeCallAllowance WHERE DiscFreeAllowID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtDescription=stripslashes($row["Description"]);
				$cmbRecChargeID=intval($row["RecChargeID"]);
				
				$sql2="Select * from tlkpEventDiscFreeCallAllowanceDuration Where DiscFreeAllowID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["txtFreeDurDescription".$j]=stripslashes($row2["Description"]);
						$_POST["txtFromDuration".$j]=intval($row2["FromDur"]);
						$_POST["txtToDuration".$j]=intval($row2["ToDur"]);
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operate))
				$numrecord=$j;
				
				$sql2="Select dfc.DistanceID,dfc.TimeID,tb.PackageID 
						from tlkpEventDiscFreeCallAllowance dfc 
								left join
								tblTarTimeBand tb
								on dfc.TimeID=tb.TimeID 
								 Where DiscFreeAllowID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["cmbDistanceID".$j]=intval($row2["DistanceID"]);
						$_POST["cmbTimeID".$j]=intval($row2["TimeID"]);
						$_POST["cmbPackageID".$j]=intval($row2["PackageID"]);
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operatedes))
				$numrecorddes=$j;
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblEventDiscFreeCallAllowance set IsActive='0' WHERE DiscFreeAllowID='".$hid."'";
				if($mydb->sql_query($sql)){					
					$Audit->AddAudit(0,0,"Deactivate free call allowance ID: $hid",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully deactivate free call allowance discount.");
				}else{
					$error = $mydb->sql_error();
						$Audit->AddAudit(0,0,"Deactivate free call allowance ID: $hid",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to deactivate free call allowane discuont.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$allowsubmit=false;
				$numrecord=1;
		}
	}
	
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate destination specific discount setup: " + code + "?"))
			window.location = "./?pg=1018&op=deactivate&desdiscid=" + id;
	}
	
	function ValidateForm(){
		mRecChargeID=frml.cmbRecChargeID;
		mDescription=frml.txtDescription;
		if(Number(mRecChargeID.value)==0){
			alert("Please select recuring charge information.");
			mRecChargeID.focus();
			return false;
		}
		
		if(Trim(mDescription.value)==""){
			alert("Please enter description.");
			mDescription.focus();
			return false;
		}
		
		if(!CheckValidateInput()){
			return false;
		}
		
		if(!CheckValidate()){
			return false;
		}
		
		if(!CheckValidateInputDestination()){
			return false;
		}
		
		if(!CheckValidateDestination()){
			return false;
		}
		
		frml.allowsubmit.value=true;
		frml.btnSubmit.disabled=true;
		frml.btnReset.disabled=true;
		frml.submit();
	}
	function Reset(){
		document.location.href="./?pg=1018";
	}
	
	function CheckValidateInputDestination(){
	
		var test=true;
		for(var i=0;i<frml.numrecorddes.value;i++){
			eval("mDistanceID=frml.cmbDistanceID"+i);
			eval("mTimeID=frml.cmbTimeID"+i);
					
			
			if(Number(mDistanceID.value)==0){
				alert("Please select any distance band information.");
				mDistanceID.focus();
				return false;
			}
			if(Number(mTimeID.value)==0){
				alert("Please select any time band inforamtion.");
				mTimeID.focus();
				return false;
			}
		}
		return test;
	}
	
	function CheckValidateDestination(){
		var i=0;

		for(var i=0;i<frml.numrecorddes.value-1;i++){
			eval("mDistanceID=frml.cmbDistanceID"+i);
			eval("mTimeID=frml.cmbTimeID"+i);
			
			for(var j=i+1;j<frml.numrecorddes.value;j++){
				eval("mDistanceID2=frml.cmbDistanceID"+j);
				eval("mTimeID2=frml.cmbTimeID"+j);
				
			  if(mDistanceID.value==mDistanceID2.value && mTimeID.value==mTimeID2.value){
				    alert("Duplicate destination.");
					return false;
				}
			}
		
		}
		return true;
	}
	
	function CheckValidateInput(){
	
		for(var i=0;i<frml.numrecord.value;i++){
		
			eval("mFromDuration=frml.txtFromDuration"+i);
			eval("mToDuration=frml.txtToDuration"+i);
			eval("mFreeDurDescription=frml.txtFreeDurDescription"+i);
			
			if(Trim(mFreeDurDescription.value)==""){
				alert("Please fill free duration description");
				mFreeDurDescription.focus();
				return false;
			}
			
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
			if(Number(mToDuration.value)<Number(mFromDuration.value) && Number(mToDuration.value)>0){
				alert("To Duration should be equal or bigger than From Duration.");
				mToDuration.focus();
				return false;
			}			
		}
		return true;
	}
	
	function CheckValidate(){
		var i=0;
			for(var i=0;i<frml.numrecord.value-1;i++){
			eval("mFromDuration=frml.txtFromDuration"+i);
			eval("mToDuration=frml.txtToDuration"+i);		
			for(var j=i+1;j<frml.numrecord.value;j++){
				eval("mFromDuration2=frml.txtFromDuration"+j);
				eval("mToDuration2=frml.txtToDuration"+j);
				
			  if(((mFromDuration.value >= mFromDuration.value && mToDuration.value <= mToDuration2.value) || (mFromDuration.value <= mFromDuration2.value && mToDuration.value <= mToDuration2.value && mToDuration.value >= mFromDuration2.value) || (mFromDuration.value >= mFromDuration2.value && mFromDuration.value <= mToDuration2.value && mToDuration.value >= mToDuration2.value))){
					alert("Overlape duration.");
					return false;

				}/*
				if(mDistanceID.value==mDistanceID2.value && mTimeID.value==mTimeID2.value){
					test=false;
				}*/
			}
		
		}
		return true;
	}
	
	function addRecordDes(){
	if(!CheckValidateInputDestination()){
	//		return false;
	}
	else if(!CheckValidateDestination()){
		
	}else{
  	newValue = parseInt(frml.numrecorddes.value);
	newValue += 1;
	frml.numrecorddes.value=newValue;
	frml.allowsubmit.value = "false";
	frml.operatedes.value="add";
	frml.submit();
	}
  }
  
  function deleteRecordDes(){
 	frml.allowsubmit.value = "false";
	frml.operatedes.value="delete";
	frml.submit();
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
<table border="0" cellpadding="0" cellspacing="5" align="left" width="42%">
	<tr>
		<td width="557" valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="646" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="643">
			   <tr>
				 <td width="603" height="18" align="left" class="formtitle"><strong> Free Call Allowance Discount Setup </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr >
					 	<td width="118" align="left"  style="padding-left:10">Recuring Charge:</td>
						<td align="left" colspan="5"><label></label>
						<select name="cmbRecChargeID">
							<option value="0" selected>Select Recuring Charge</option>
						<?php 
						$sql="SELECT * FROM tblRecuringCharges";
						$result=$mydb->sql_query($sql);
						while($row=$mydb->sql_fetchrow($result)){
							$select="";
							$recChargeID=intval($row["RecChargeID"]);
							$recRate=doubleval($row['RecRate']);
							if($recChargeID==$cmbRecChargeID){
								$select=" selected ";
							}
							//$cmbRecChargeID=$recChargeID;
							
						?>
						<option value="<?php echo $recChargeID?>" <?php echo $select?>><?php echo  $recRate;?></option>
						<?php }?>
						</select>
						<img src="./images/required.gif" border="0" />(Cent)</td>
					</tr>	
					<tr>
						<td align="left" style="padding-left:10" >Description:</td>
				 	  <td align="left" colspan="5"><input type="text" name="txtDescription" value="<?php echo $txtDescription;?>" size="60" />
			 	      <img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
					  <td colspan="6" align="center"><strong>Free Call Duration</strong></td>
				     </tr>
					 <tr><td colspan="6" >
					 <table width="100%" border="0" cellspacing="2">
					 <tr><td align="center">
					  <table width="99%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td width="5%" align="left">&nbsp;</td>
					  <td width="55%" align="center" ><strong>Free Duration Description</strong></td>
					  <td width="20%" align="center"><strong>From (Second) </strong></td>
					  <td width="20%" align="center"><strong>To (Second) </strong></td>
					  </tr>
					<?php 
					//check wether the new row is add or not
					
					
					//$numrecord=3;
					$k=0;
					$newindex=0;
					while ($k<$numrecord) {
						$txtFreeDurDescription=$_POST["txtFreeDurDescription".$k];
						$txtFromDuration=$_POST["txtFromDuration".$k];
						$txtToDuration=$_POST["txtToDuration".$k];
						
						//Check if the operate is equal add
						if(!isset($operate) || $operate=="add"){
							$newindex=$k;		
						?>
					<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center" ><input type="text" name="txtFreeDurDescription<?php echo $newindex;?>" value="<?php echo $txtFreeDurDescription?>" size="40" /></td>
					  <td align="center"><input type="text" name="txtFromDuration<?php echo $newindex?>" value="<?php echo $txtFromDuration;?>"  size="6" /></td>
					  <td  align="center">
					    <input type="text" name="txtToDuration<?php echo $newindex?>" value="<?php echo $txtToDuration;?>" size="6"  />				      </td>
					  </tr>
				     <?php
				     		$newindex++;
							}elseif($operate=="delete" && !isset($_POST["chSel".$k])){
								
						?>
						<tr>
					  <td align="center"><input type="checkbox" name="chSel<?php echo $newindex?>" value="1"></td>
					  <td align="center" ><input type="text" name="txtFreeDurDescription<?php echo $newindex;?>" value="<?php echo $txtFreeDurDescription?>" size="40"/></td>
					  <td align="center">
					    <input type="text" name="txtFromDuration<?php echo $newindex?>" value="<?php echo $txtFromDuration;?>"  size="6" />				      </td>
					  <td  align="center">
					    <input type="text" name="txtToDuration<?php echo $newindex?>" value="<?php echo $txtToDuration;?>" size="6"  />				      </td>
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
					  <td align="center" >&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td colspan="4" align="left" style="padding-left:20"><a href="javascript:addRecord();">Add New Row</a> - <a href="javascript:deleteRecord();">Delete Selected </a></td>
					  </tr>		
					 </table>
					 </td>
					 </tr>			 	
					 </table>
					 </td></tr>		
					 <tr>
					  <td colspan="6" align="center" ><strong>Destination</strong></td>
				     </tr>
					 <tr><td colspan="6" >
					 <table width="100%" border="0" cellspacing="2">
					 <tr><td align="center">
					  <table width="99%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td align="left">&nbsp;</td>
					  <td align="left"><div align="center"><strong>Destance Band </strong></div></td>
					  <td align="left" ><div align="center"><strong>Package</strong></div></td>
					  <td align="left" ><div align="center"><strong>Time Band </strong></div></td>
					  </tr>
					<?php 
					//check wether the new row is add or not
					
					
					//$numrecord=3;
					$k=0;
					$newindex=0;
					while ($k<$numrecorddes) {
						$cmbDistanceID=$_POST["cmbDistanceID".$k];
						$cmbTimeID=$_POST["cmbTimeID".$k];	
						$cmbPackageID=$_POST["cmbPackageID".$k];
						//Check if the operate is equal add
						if(!isset($operatedes) || $operatedes=="add"){
							$newindex=$k;		
						?>
					<tr>
					  <td align="center"><input type="checkbox" name="chSelDes<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					  <select name="cmbDistanceID<?php echo $newindex?>"  style="width:150px">	
					  <option value="0" selected>Select Distance Band</option> 
					  <?php
					  	$sql="SELECT * FROM tlkpTarChargingBand WHERE Status='1'";
					    $result=$mydb->sql_query($sql);
					    while($row=$mydb->sql_fetchrow($result)){
					    	$select="";
					    	if($cmbDistanceID==intval($row['DistanceID'])){
					    		$select=" selected ";
					    	}
					  	?>
                        <option value="<?php echo $row['DistanceID']?>" <?php echo $select?>><?php echo $row['BandName'];?></option>
                       <?php }?>
                      </select></td>
					  <td align="center" ><select  name="cmbPackageID<?php echo $newindex?>" onchange="GetTimeBandByPackage(this.value,'cmbTimeID<?php echo $newindex?>');"  style="width:150px">
                        <option value="0">Select Package</option>
                        <?php 
					    $sql="select * from tblTarPackage where Status='1'  and serviceid not in (1,3,8,4)";
					    $result=$mydb->sql_query($sql);
					    while ($row=$mydb->sql_fetchrow($result)) {
					    	$select="";
					    	if($cmbPackageID==intval($row['PackageID'])){
					    		$select=" selected ";
					    	}
					    	
					    ?>
                        <option value="<?php echo $row['PackageID']?>" <?php echo $select?>><?php echo $row['TarName'];?></option>
                        <?php }?>
                      </select></td>
					  <td align="center" >
					    <select name="cmbTimeID<?php echo $newindex?>" id="cmbTimeID<?php echo $newindex?>" style="width:150px">
					    <option value="0">Select Time Band</option>
					    <?php 
					    $sql="select * from tblTarTimeBand where Status='1' and packageid='$cmbPackageID'";
					    $result=$mydb->sql_query($sql);
					    while ($row=$mydb->sql_fetchrow($result)) {
					    	$select="";
					    	if($cmbTimeID==intval($row['TimeID'])){
					    		$select=" selected ";
					    	}
					    	
					    ?>
                          <option value="<?php echo $row['TimeID']?>" <?php echo $select?>><?php echo $row['TimeBandName'];?></option>
                          <?php }?>
                        </select>					  </td>
					  </tr>
				     <?php
				     		$newindex++;
							}elseif($operatedes=="delete" && !isset($_POST["chSelDes".$k])){
								
						?>
						<tr>
					  <td align="center"><input type="checkbox" name="chSelDes<?php echo $newindex?>" value="1"></td>
					  <td align="center">
					  <select name="cmbDistanceID<?php echo $newindex?>"  style="width:150px">	
					  <option value="0" selected>Select Distance Band</option> 
					  <?php
					  	$sql="SELECT * FROM tlkpTarChargingBand WHERE Status='1'";
					    $result=$mydb->sql_query($sql);
					    while($row=$mydb->sql_fetchrow($result)){
					    	$select="";
					    	if($cmbDistanceID==intval($row['DistanceID'])){
					    		$select=" selected ";
					    	}
					  	?>
                        <option value="<?php echo $row['DistanceID']?>" <?php echo $select?>><?php echo $row['BandName'];?></option>
                       <?php }?>
                      </select></td>
					  <td align="center" ><select   name="cmbPackageID<?php echo $newindex?>" onchange="GetTimeBandByPackage(this.value,'cmbTimeID<?php echo $newindex?>');"  style="width:150px">
                        <option value="0">Select Package</option>
                        <?php 
					    $sql="select * from tblTarPackage where Status='1'  and serviceid not in (1,3,8,4)";
					    $result=$mydb->sql_query($sql);
					    while ($row=$mydb->sql_fetchrow($result)) {
					    	$select="";
					    	if($cmbPackageID==intval($row['PackageID'])){
					    		$select=" selected ";
					    	}
					    	
					    ?>
                        <option value="<?php echo $row['PackageID']?>" <?php echo $select?>><?php echo $row['TarName'];?></option>
                        <?php }?>
                      </select></td>
					  <td align="center" >
					    <select name="cmbTimeID<?php echo $newindex?>" id="cmbTimeID<?php echo $newindex?>"  style="width:150px">
					    <option value="0">Select Time Band</option>
					    <?php 
					    $sql="select * from tblTarTimeBand where Status='1' and packageid='$cmbPackageID'";
					    $result=$mydb->sql_query($sql);
					    while ($row=$mydb->sql_fetchrow($result)) {
					    	$select="";
					    	if($cmbTimeID==intval($row['TimeID'])){
					    		$select=" selected ";
					    	}
					    	
					    ?>
                          <option value="<?php echo $row['TimeID']?>" <?php echo $select?>><?php echo $row['TimeBandName'];?></option>
                          <?php }?>
                        </select>					  </td>
					  </tr>
						
						<?php
							$newindex++;
							}
				     		$k++;
						}
						if($operatedes=="delete"){
							$numrecorddes=$newindex;
						}
					?>
					<tr>
					  <td align="center" style="padding-left:10">&nbsp;</td>
					  <td align="center" style="padding-left:10">&nbsp;</td>
					  <td align="center" >&nbsp;</td>
					  <td align="center" >&nbsp;</td>
					  </tr>
					<tr>
					  <td colspan="4" align="left" style="padding-left:20"><a href="javascript:addRecordDes();">Add New Row</a> - <a href="javascript:deleteRecordDes();">Delete Selected </a></td>
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
			  <input type="hidden" name="pg" id="pg" value="1018">
			  <input type="hidden" name="numrecord"  value="<?php if(isset($numrecord)) echo $numrecord; else echo '1';?>" />
			  <input type="hidden" name="numrecorddes" value="<?php if(isset($numrecorddes)) echo $numrecorddes; else echo '1';?>" />
			  <input type="hidden" name="operate" value="<?php if(isset($operate)) echo $operate; else echo 'add';?>">
			  <input type="hidden" name="operatedes" value="<?php if(isset($operatedes)) echo $operatedes; else echo 'add';?>">
			  <input type="hidden" name="allowsubmit" value="<?php if(isset($allowsubmit)) echo $allowsubmit; else '0';?>">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes"	>
</form>
	  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="644">
									<tr>
									 	<td align="left" class="formtitle"><strong>Free Call Allowance Discount</strong></td>
										<td align="right">[<a href="./?pg=1018">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1"  class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
													<th width="1">&nbsp;</th>
													<th width="294">Discount Name</th>
													<th width="285">Recuring Charge</th>
													<th width="26">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select tedds.DiscFreeAllowID, tedds.Description,trc.RecRate, tedds.IsActive
													from tblEventDiscFreeCallAllowance tedds 
														 left join tblRecuringCharges trc
														 on tedds.RecChargeID=trc.RecChargeID
													 ORDER BY IsActive Desc";
												
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result)){
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													$DiscFreeID=intval($row["DiscFreeAllowID"]);
													$Description=stripslashes($row["Description"]);
													$RecRate=doubleval($row["RecRate"]);
													
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
													if(HasDiscFreeCallDetail($DiscFreeID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$DiscFreeID."\" name=\"img" .$DiscFreeID. "\" onClick=\"SwitchMenu('dest" .$DiscFreeID. "', 'img".$DiscFreeID. "');\">";
													echo "</a>";
													}
														echo "</td>";
														echo "

														<td  align=\"left\"><font color=\"#aaaaaa\">".$Description."</font></td>
														<td  align=\"center\"><font color=\"#aaaaaa\">".$RecRate."</font></td>														
														</tr>";
													}else{
													$rowdeactiveclass="";
													echo "<tr class=\"$rowdeactiveclass\">";
													echo "<td class=\"".$rowclass."\">";
													if(HasDiscFreeCallDetail($DiscFreeID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$DiscFreeID."\" name=\"img" .$DiscFreeID. "\" onClick=\"SwitchMenu('dest" .$DiscFreeID. "', 'img".$DiscFreeID. "');\">";
													echo "</a>";
													}
													
echo '
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>
														<td class="'.$rowclass.'" align="center">'.$RecRate.'</td>';
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1018&amp;op=edit&amp;hid=".$DiscFreeID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$DiscFreeID.",'".$Description."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasDiscFreeCallDetail($DiscFreeID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='3' >";
													echo GetFreeCallDiscountDetail($DiscFreeID);
												
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