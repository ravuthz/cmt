
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1103) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbPackageID=intval($cmbPackageID);
		$cmbCredID=intval($cmbCredID);
		$cmbCredType=intval($cmbCredType);
		$txtEffectiveDate=stripslashes(FixQuotes($txtEffectiveDate));
     	$txtEndDate=stripslashes(FixQuotes($txtEndDate));

		$txtPriority=intval($txtPriority);
	
		
		if($op=="add"){
		
			if(CheckExistCreditRuleTariff($cmbPackageID,$cmbCredID,$cmbCredType)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO trelTarCredit(PackageID,CredID,CredType,EffectivedDate,Priority) 
				 		VALUES('".$cmbPackageID."','".$cmbCredID."','".$cmbCredType."','".$txtEffectiveDate."','".$txtPriority."');";
				if($mydb->sql_query($sql)){
					$retOut = $myinfo->info("Successfully add new credit rule tariff.");
					$Audit->AddAudit(0,0,"Add Package's Credit","Add package's credit with PackageID: $cmbPackage, Credit Type ID: $cmbCredType, Credit ID: $cmbCredID",$user["FullName"],1,17);
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new credit rule tariff.", $error['message']);
					$Audit->AddAudit(0,0,"Add Package's Credit","Add package's credit with PackageID: $cmbPackage, Credit Type ID: $cmbCredType, Credit ID: $cmbCredID",$user["FullName"],0,17);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbCredID=0;
				$cmbCredType=0;
				$txtEffectiveDate="";
				$txtPriority="";
				$txtDescription="";
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistCreditRuleTariff($cmbPackageID,$cmbCredID,$cmbCredType,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE trelTarCredit SET PackageID='".$cmbPackageID."',CredID='".$cmbCredID."',Priority='$txtPriority', CredType='".$cmbCredType."', 
						EffectivedDate='".$txtEffectiveDate."'  WHERE ID='".intval($hid)."';";
				//print $sql;
				if($mydb->sql_query($sql)){				
					$retOut = $myinfo->info("Successfully update tariff credit rule.");
					$Audit->AddAudit(0,0,"Update Package's Credit","Update package's credit with PackageID: $cmbPackage, Credit Type ID: $cmbCredType, Credit ID: $cmbCredID",$user["FullName"],1,17);
				}else{
					$error = $mydb->error();
					$retOut = $myinfo->error("Failed to update tariff credit rule.", $error['message']);
					$Audit->AddAudit(0,0,"Update Package's Credit","Update package's credit with PackageID: $cmbPackage, Credit Type ID: $cmbCredType, Credit ID: $cmbCredID",$user["FullName"],0,17);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbCredID=0;
				$cmbCredType=0;
				$txtEffectiveDate="";
				$txtPriority="";
				$txtDescription="";
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from trelTarCredit WHERE ID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$cmbPackageID=intval($row["PackageID"]);
				$cmbCredID=intval($row["CredID"]);
				$cmbCredType=intval($row["CredType"]);
				$txtEffectiveDate=stripslashes($row["EffectivedDate"]);
				$txtEndDate=stripslashes($row["EndDate"]);
				$txtPriority=stripslashes($row["Priority"]);
			//	print_r($row);
			}
		}
	}
?>
<link type="text/css" rel="stylesheet" href="./style/mystyle.css" />
<script type="text/javascript" src="./javascript/datetimepicker.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/sorttable.js"></script>
<script language="javascript">

	
	
	function ValidateForm(){
		mPackageID=frml.cmbPackageID;
		mCredID=frml.cmbCredID;
		mCredType=frml.cmbCredType;
		mDescription=frml.txtDescription;
		mEffectiveDate=frml.txtEffectiveDate;
		mPriority=frml.txtPriority;
		frml.btnSubmit.disabled = true;
		frml.allowsubmit.value="true";
		frml.submit();
	}
	
	function Reset(){
		document.location.href="./?pg=1103";
	}
	
	//Get time
	  function GetCredit(id,target){
		 var myurl="./administration/ajax_infomation.php?id="+id+"&choice=credit"+"&ms="+new Date().getTime();;
		// alert(myurl);
		 
		sendHttpRequest(myurl,'ApplyInfo',target,true);  
	  }
	  
	  //Call By sendHttpRequest(url,'TimeBandInfo',true);
	  function ApplyInfo(documentdata,target,respXml){
			var option;
			var mydocument=documentdata;
			//alert(documentdata);
			
			if(respXml==true){
				option=mydocument.getElementsByTagName("option");
				//alert(option.length);
				var selectControl=document.getElementById(target);
				//alert(target);
				selectControl.options.length=0;	
				selectControl.options[0]=new Option("-- Select --");
			    selectControl.options[0].value=0;
				for(var loopindex=0;loopindex<option.length;loopindex++){
						
						selectControl.options[loopindex+1]=new Option(option[loopindex].firstChild.data);
						selectControl.options[loopindex+1].value=option[loopindex].getAttribute("value");		
						//alert(option[loopindex].getAttribute("value"));
				}
			}else{
				// 	do nothing
			}
	  }
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="26%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="610" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="606">
			   <tr>
				 <td width="465" height="18" align="left" class="formtitle"><strong>Tariff Credit Management </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="155" align="left">Package:</td>
					   <td align="left" width="302" colspan="3">
					   <select name="cmbPackageID" style="width: 240px;">
					   <option value="0">Select Package</option>
					    <?php
								$sql="select * from tblTarPackage where status='1'";
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
					   </select></td>
					</tr>	
					 <tr>
					   <td align="left">Credit Type: </td>
					   <td align="left" colspan="3"><select name="cmbCredType"  style="width: 240px;" onchange="GetCredit(this.value,'cmbCredID');">
                         <option value="0">Select Discount Type</option>
                         <?php
								$sql="select * from tblSysCreditMgtTypeCode";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbCredType==intval($row['CredTypeID']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['CredTypeID'])."' $selected>".stripslashes($row['CredType'])."</option>";
								}
							?>
                       </select></td>
					 </tr>
					 <tr>
					   <td align="left">Credit Name: </td>
					   <td align="left" colspan="3">
					   <select name="cmbCredID" id="cmbCredID"  style="width: 240px;" >
					      <option value="0">-- Select --</option>
					   <?php
					   if($cmbCredType==6000){
					   		$sql="select * from tblCreditLimitRules where status=1";
					   }
					   if($cmbCredType==6001){
					   		$sql="select * from tblCreditRuleInvoice where status=1";
							
					   }
					   if($cmbCredType==6002){
					   		$sql="select * from tblCreditRuleUnpaidPeriod where status=1";
					   }
					   $result2=$mydb->sql_query($sql);
					   while($row=$mydb->sql_fetchrow($result2)){
					   		$select="";
							if($cmbCredID==intval($row["0"])){
								$select= " selected";
							}
							echo "<option value=\"".intval($row[0])."\" $select>".$row[1]."</option>";
					   }
					   ?>
					   </select></td>
				     </tr>
					 <tr>
					   <td align="left">Effective Date: </td>
					   <td align="left" colspan="3"><input id="txtEffectiveDate"  style="width: 240px;" name="txtEffectiveDate" class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEffectiveDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEffectiveDate));
						?>"   type="text" size="25"><a href="javascript:NewCal('txtEffectiveDate','yyyymmdd',true,12)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>(YYYY-MM-DD HH:MM:SS)	</td>
				     </tr>
					  <tr>
						<td align="left">End Date :</td>
					 	<td align="left" colspan="3"><input id="txtEndDate" name="txtEndDate"   style="width: 240px;"class="boxenabled" value="<?php if(date('Y-m-d h:i:s A',strtotime($txtEndDate))!="1970-01-01 07:00:00 AM")
						echo date('Y-m-d h:i:s A',strtotime($txtEndDate));
						?>"   type="text" size="25"><a href="javascript:NewCal('txtEffectiveDate','yyyymmdd',true,12)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>(YYYY-MM-DD HH:MM:SS)	</td>
					</tr>
					 <tr>
					   <td align="left">Priority:</td>
					   <td align="left" colspan="3"><input type="text" name="txtPriority" value="<?php echo $txtPriority?>" class="boxenabled" width="150"/></td>
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
							<input type="button" onclick="Reset()" tabindex="3" name="reset" value="Reset" class="button" />
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
			  <input type="hidden" name="pg" id="pg" value="1103">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="allowsubmit" id="allowsubmit" value="<?php echo $allowsubmit?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="610">
									<tr>
									 	<td align="left" class="formtitle"><strong>Tariff Credit Management</strong><strong> Information </strong></td>
										<td align="right">[<a href=".?pg=1103">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											 		 <th width="116">No.</th>
													<th width="285">Package</th>
													<th width="285">Credit Type</th>
													<th width="285">Credit Name</th>
													<th width="285">Effective Date</th>
													<th width="497">End Date</th>
													<th width="497">Priority</th>
													<th width="25">Edit</th>
												
												</tr>
											
												<?php
												$sql2="select tc.ID,cmtc.CredType, 
														case when tc.CredType=6000 then
															clr.CredName
															when tc.CredType=6001 then
															cri.CreditRuleInvoice
															when tc.CredType=6002 then
															crup.CredName
														else ''
														end 'CredRuleName',
														tp.TarName,  tc.EffectivedDate,tc.EndDate,tc.Priority
														
														from 
															trelTarCredit tc 
															left join tblTarPackage tp
																on tc.PackageID=tp.PackageID
															left join tblSysCreditMgtTypeCode cmtc
																on tc.CredType=cmtc.CredTypeID
															left join tblCreditLimitRules clr
																on tc.CredID=clr.CredID
															left join tblCreditRuleInvoice cri
																on tc.CredID=cri.CredID
															left join tblCreditRuleUnpaidPeriod crup
																on tc.CredID=crup.CredID";
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
													if($row['EndDate']!=""){
														$rowdeactiveclass="";
														echo '<tr '.$rowdeactiveclass.'>
														<td align="center"><font color="#aaaaaa">'.$row['ID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['TarName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['CredType'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['CredRuleName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['EffectivedDate'].'</font></td>												
														<td  align="left"><font color="#aaaaaa">'.$row['EndDate'].'</font></td>	
														<td  align="left"><font color="#aaaaaa">'.$row['Priority'].'</font></td>														
													</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['ID'].'</td>
														<td  class="'.$rowclass.'" align="left">'.$row['TarName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['CredType'].'</td>
														<td class="'.$rowclass.'"  align="left">'.$row['CredRuleName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['EffectivedDate'].'</td>												
														<td class="'.$rowclass.'" align="left">'.$row['EndDate'].'</td>	
														<td class="'.$rowclass.'" align="left">'.$row['Priority'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1103&amp;op=edit&amp;hid='.$row['ID'].'"><img src="./images/Edit.gif" border="0"></a></td>
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
