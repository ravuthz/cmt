
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
	if(!isset($numrecorddes)){
		$numrecorddes=1;
	}
	if(isset($smt) && (!empty($smt)) && ($pg == 1020) && isset($allowsubmit) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbRecChargeID=intval($cmbRecChargeID);
		$txtDescription=stripslashes(FixQuotes($txtDescription));
		
		if($op=="add"){
	
			$DiscFreeID=GetNextID("CallAllowance");
			if(CheckCallAllowDiscount($cmbRecChargeID,$txtDescription)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "INSERT INTO tblEventDiscCallAllowance(DiscAllowID, RecChargeID,Description,IsActive) ".
						"VALUES('".$DiscFreeID."','".$cmbRecChargeID."','".$txtDescription."','1');";
						$i=0;
						while($i<$numrecord){
						
						$FreeDurDescription=stripslashes(FixQuotes($_POST["txtFreeDurDescription".$i]));
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);
						$Rate=doubleval($_POST["txtRate".$i]);
						$IsPercentage=isset($_POST["chIsPercentage".$i])?1:0;

						$sql.="INSERT INTO tlkpEventDiscCallAllowanceDuration (DiscAllowID,Description,FromDur,ToDur,DiscRate,IsPercentage) ".
						    "VALUES ('$DiscFreeID','$FreeDurDescription','$FromDur','$ToDur','$Rate','$IsPercentage'); ";
						$i++;
						}
						$i=0;
						while($i<$numrecorddes){
						
						$cmbDistanceID=intval($_POST["cmbDistanceID".$i]);
						$cmbTimeID=intval($_POST["cmbTimeID".$i]);

						$sql.="INSERT INTO tlkpEventDiscCallAllowance (DiscAllowID,DistanceID, TimeID) ".
						    "VALUES ('$DiscFreeID','$cmbDistanceID','$cmbTimeID'); ";
						$i++;
						}
				//print $sql;
				if($result=$mydb->sql_query($sql)){	
						$Audit->AddAudit(0,0,"Add Call Allowance Discount","Add Call Allowance Discount Name: $txtDescription",$user["FullName"],1,16);
						$retOut = $myinfo->info("Successfully add new call allowance discount setup.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Add Call Allowance Discount","Add Call Allowance Discount Name: $txtDescription",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to add new call allowance discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$i=0;
						while($i<$numrecord){
						
						$_POST["txtFreeDurDescription".$i]="";
						$_POST["txtFromDuration".$i]="";
						$_POST["txtToDuration".$i]="";
						$_POST["txtRate".$i]="";
						$_POST["chIsPercentage".$i]=0;

						
						$i++;
						}

				$i=0;

						while($i<$numrecorddes){
						
						$_POST["cmbDistanceID".$i]="";
						$_POST["cmbTimeID".$i]="";
						
						$i++;
						}
				$allowsubmit=false;
				$numrecord=1;
			}	
			
		}elseif($op=="edit"){
			if(CheckCallAllowDiscount($cmbRecChargeID,$txtDescription,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql="";
				$sql.= "UPDATE tblEventDiscCallAllowance SET RecChargeID='$cmbRecChargeID', Description='$txtDescription' Where DiscAllowID='".intval($hid)."';";
//				print $sql;
				
					$mydb->sql_freeresult($result);
					$DiscFreeID=intval($hid);
					$i=0;
					
					$sql.="Delete from tlkpEventDiscCallAllowanceDuration where DiscAllowID='".$DiscFreeID."';";
					$sql.="Delete from tlkpEventDiscCallAllowance where DiscAllowID='".$DiscFreeID."';";
						$i=0;
						while($i<$numrecord){
						
						$FreeDurDescription=stripslashes(FixQuotes($_POST["txtFreeDurDescription".$i]));
						$FromDur=intval($_POST["txtFromDuration".$i]);
						$ToDur=intval($_POST["txtToDuration".$i]);
						$Rate=doubleval($_POST["txtRate".$i]);
						$IsPercentage=isset($_POST["chIsPercentage".$i])?1:0;

						$sql.="INSERT INTO tlkpEventDiscCallAllowanceDuration (DiscAllowID,Description,FromDur,ToDur,DiscRate,IsPercentage) ".
						    "VALUES ('$DiscFreeID','$FreeDurDescription','$FromDur','$ToDur','$Rate','$IsPercentage'); ";
						$i++;
						}
						$i=0;
						while($i<$numrecorddes){
						
						$cmbDistanceID=intval($_POST["cmbDistanceID".$i]);
						$cmbTimeID=intval($_POST["cmbTimeID".$i]);

						$sql.="INSERT INTO tlkpEventDiscCallAllowance (DiscAllowID,DistanceID, TimeID) ".
						    "VALUES ('$DiscFreeID','$cmbDistanceID','$cmbTimeID'); ";
						$i++;
						}
		
				if($result=$mydb->sql_query($sql)){		
				$Audit->AddAudit(0,0,"Update Call Allowance Discount","Update Call Allowance Discount Name: $txtDescription, ID: $DiscFreeID",$user["FullName"],1,16);	
						$retOut = $myinfo->info("Successfully update call allowance discount setup.");
				}else{
					$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update Call Allowance Discount","Update Call Allowance Discount Name: $txtDescription, ID: $DiscFreeID",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update call allowance discount setup.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$allowsubmit=false;
				$numrecord=1;
				$i=0;
						while($i<$numrecord){
						
						$_POST["txtFreeDurDescription".$i]="";
						$_POST["txtFromDuration".$i]="";
						$_POST["txtToDuration".$i]="";
						$_POST["txtRate".$i]="";
						$_POST["chIsPercentage".$i]=0;

						
						$i++;
						}

				$i=0;

						while($i<$numrecorddes){
						
						$_POST["cmbDistanceID".$i]="";
						$_POST["cmbTimeID".$i]="";
						
						$i++;
						}
				$numrecorddes=1;
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from tblEventDiscCallAllowance WHERE DiscAllowID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtDescription=stripslashes($row["Description"]);
				$cmbRecChargeID=intval($row["RecChargeID"]);
				
				$sql2="Select * from tlkpEventDiscCallAllowanceDuration Where DiscAllowID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["txtFreeDurDescription".$j]=stripslashes($row2["Description"]);
						$_POST["txtFromDuration".$j]=intval($row2["FromDur"]);
						$_POST["txtToDuration".$j]=intval($row2["ToDur"]);
						$_POST["txtRate".$j]=intval($row2["DiscRate"]);
						$_POST["chIsPercentage".$j]=intval($row2["IsPercentage"]);
						$j++;
						//print "$sql2";
				}
				if($j>0 && !isset($operate))
				$numrecord=$j;
				
				$sql2="Select dca.DistanceID,dca.TimeID,tb.PackageID 
						from tlkpEventDiscCallAllowance dca 
						left join tblTarTimeBand tb
						on dca.TimeID=tb.TimeID
						Where dca.DiscAllowID='".intval($hid)."'";
				$result2=$mydb->sql_query($sql2);
				$j=0;
				while ($row2=$mydb->sql_fetchrow($result2)) {
						$_POST["cmbDistanceID".$j]=intval($row2["DistanceID"]);
						$_POST["cmbTimeID".$j]=intval($row2["TimeID"]);
						$_POST["cmbPackageID".$j]=intval($row2["PackageID"]);
						$j++;
						
				}
				if($j>0 && !isset($operatedes))
				$numrecorddes=$j;
			}
			
		}elseif($op=="deactivate"){
			$sql="update tblEventDiscCallAllowance set IsActive='0' WHERE DiscAllowID='".$hid."'";
				if($mydb->sql_query($sql)){		
					$Audit->AddAudit(0,0,"Deactivate Call Allowance Discount","Deactivate Call Allowance Discount ID: $hid",$user["FullName"],1,16);			
					$retOut = $myinfo->info("Successfully deactivate call allowance discount.");
				}else{
					$error = $mydb->sql_error();
					$Audit->AddAudit(0,0,"Deactivate Call Allowance Discount","Deactivate Call Allowance Discount ID: $hid",$user["FullName"],0,16);	
					$retOut = $myinfo->error("Failed to deactivate call allowane discuont.", $error['message']);
				}
				$op="add";
				$txtDescription="";
				$cmbRecChargeID=0;
				$allowsubmit=false;
				$numrecord=1;
		}
	}
	
?>
<link type="text/css" rel="stylesheet" href="./style/mystyle.css" />
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript">
	function ActionConfirmation(id, code){
		if(confirm("Do you want to deactivate call allowance iscount setup: " + code + "?"))
			window.location = "./?pg=1020&op=deactivate&hid=" + id;
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
		document.location.href="./?pg=1020";
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
			eval("mRate=frml.txtRate"+i);
			eval("mIsPercentage=frml.chIsPercentage"+i);
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
			
			if(!Number(mRate.value)){
				alert("Please insert value rate value (should be number).");
				mRate.focus();
				return false;
			}
			if(mRate.value<=0){
				alert("Rate value should be bigger than 0.");
				mRate.focus();
				return false;
			}					
			if(mIsPercentage.value==1){
				if(mRate.value>100){
					alert("Rate in percent value should be small or equal to 100.");
					mRate.focus();
					return false;
				}
			}		
		}
		return true;
	}
	
	function CheckValidate(){
		var i=0;
			for(var i=0;i<frml.numrecord.value-1;i++){
			eval("mF1=frml.txtFromDuration"+i);
			eval("mT1=frml.txtToDuration"+i);		
			for(var j=i+1;j<frml.numrecord.value;j++){
				eval("mF2=frml.txtFromDuration"+j);
				eval("mT2=frml.txtToDuration"+j);
		/*		f1 t1
				f2 t2
				
				case 1
				f1 < f2 and t1 > f2
				
				
				case 2 
				f1 > f2 and t1 < t2
				
				case 3
				f1 < t2 and t1 > t2*/

/*			  if((mF1.value < mF2.value && mT1.value > mF2.value) || 
			  		(mF1.value > mF2.value && mT1.value < mT2.value) ||
					(mF1.value < mT2.value && mT1.value > mT2.value)){
					alert("Overlape duration.");
					mF2.focus();
					return false;*/

				//}
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
<table border="0" cellpadding="0" cellspacing="5" align="left" width="27%">
	<tr>
		<td width="162" valign="top" >
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="646" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="642">
			   <tr>
				 <td width="603" height="18" align="left" class="formtitle"><strong>  Call Allowance Discount Setup </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr >
					 	<td width="162" align="left"  style="padding-left:10">Recuring Charge:</td>
						<td width="468" colspan="5" align="left"><label></label>
						<select name="cmbRecChargeID" style="width:200px">
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
						<img src="./images/required.gif" border="0" /> (Cent) </td>
					</tr>	
					<tr>
						<td align="left" style="padding-left:10" >Description:</td>
				 	  <td align="left" colspan="5"><input type="text" name="txtDescription" value="<?php echo $txtDescription;?>" size="60" />
			 	      <img src="./images/required.gif" border="0" /></td>
					</tr>
					<tr>
					  <td colspan="6" align="center"><strong>Call Allowance </strong></td>
				     </tr>
					 <tr><td colspan="6" >
					 <table width="100%" border="0" cellspacing="2">
					 <tr><td align="center">
					  <table width="99%" border="1" style="border-collapse:collapse" bgcolor="#FFCC99" cellspacing="2">
					 <tr>
					   <td width="4%" align="left">&nbsp;</td>
					  <td width="40%" align="center" ><strong>Free Duration Description</strong></td>
					  <td width="19%" align="center"><p><strong>From<br />
					  (Second)</strong></p>
					    </td>
					  <td width="15%" align="center"><strong>To <br />
					    (Second) </strong></td>
					  <td width="14%" align="center"><strong>Rate <br />
					    (Cent)</strong></td>
					  <td width="8%" align="center"><strong>%</strong></td>
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
						$txtRate=$_POST["txtRate".$k];
						$chIsPercentage=$_POST["chIsPercentage".$k];
						
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
					  <td  align="center"><input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo $txtRate;?>" size="6"></td>
					  <td  align="center"><input type="checkbox" name="chIsPercentage<?php echo $newindex?>" value="1" <?php if($chIsPercentage==1) echo " checked='CHECKED'";?>></td>
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
					  <td  align="center"><input type="text" name="txtRate<?php echo $newindex?>" value="<?php echo $txtRate;?>" size="6"></td>
						<td  align="center"><input type="checkbox" name="chIsPercentage<?php echo $newindex?>" value="1" <?php if($chIsPercentage==1) echo " checked='CHECKED'";?>></td>
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
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="6" align="left" style="padding-left:20"><a href="javascript:addRecord();">Add New Row</a> - <a href="javascript:deleteRecord();">Delete Selected </a></td>
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
					  <td align="center" ><strong>Package </strong></td>
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
					  	$sql="SELECT * FROM tlkpTarChargingBand";
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
					  <td align="center" >
					  <select  name="cmbPackageID<?php echo $newindex?>" onchange="GetTimeBandByPackage(this.value,'cmbTimeID<?php echo $newindex?>');" style="width:150px">
                        <option value="0">Select Package</option>
                        <?php 
					    $sql="select * from tblTarPackage where Status='1' and serviceid not in (1,3,8,4)";
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
					    <select name="cmbTimeID<?php echo $newindex?>" style="width:150px" id="cmbTimeID<?php echo $newindex?>" >
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
					  <select name="cmbDistanceID<?php echo $newindex?>" style="width:150px">	
					  <option value="0" selected>Select Distance Band</option> 
					  <?php
					  	$sql="SELECT * FROM tlkpTarChargingBand";
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
					  <td align="center" ><select   name="select" style="width:150px">
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
					    <select name="cmbTimeID<?php echo $newindex?>" style="width:150px" id="cmbTimeID<?php echo $newindex?>" >
					    <option value="0">Select Time Band</option>
					    <?php 
					    $sql="select * from tblTarTimeBand where Status='1'  and packageid='$cmbPackageID'";
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
			  <input type="hidden" name="pg" id="pg" value="1020">
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
									 	<td align="left" class="formtitle"><strong> Call Allowance Discount</strong></td>
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
												$sql="select tedds.DiscAllowID, tedds.Description,trc.RecRate, tedds.IsActive
													from tblEventDiscCallAllowance tedds 
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
													$DiscFreeID=intval($row["DiscAllowID"]);
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
													if(HasDiscCallAllowDetail($DiscFreeID)){
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
													if(HasDiscCallAllowDetail($DiscFreeID)){
													echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
									
													echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$DiscFreeID."\" name=\"img" .$DiscFreeID. "\" onClick=\"SwitchMenu('dest" .$DiscFreeID. "', 'img".$DiscFreeID. "');\">";
													echo "</a>";
													}
													
echo '
														<td class="'.$rowclass.'" align="left">'.$Description.'</td>
														<td class="'.$rowclass.'" align="center">'.$RecRate.'</td>';
												  echo "<td class='".$rowclass."' ><a href=\"?pg=1020&amp;op=edit&amp;hid=".$DiscFreeID."\"><img src='./images/Edit.gif' border='0'></a>&nbsp;<a href=\"javascript:ActionConfirmation(".$DiscFreeID.",'".$Description."')\"><img src=\"./images/Delete.gif\" border=\"0\"></a></td>";
													echo '</tr>';																
													}
													if(HasDiscCallAllowDetail($DiscFreeID)){
													echo "<tr  bgcolor='#ffffff'>";
													echo "<td></td>";						
													echo "<td colspan='3' >";
													echo GetCallAllowDiscountDetail($DiscFreeID);
												
													echo "</td>";				
													echo "</tr>";	
																	
													}
													$rowcount++;			
												}

												?>
												
												</tbody>
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