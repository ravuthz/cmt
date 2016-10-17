
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1019) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbPackageID=intval($cmbPackageID);
		$cmbDiscountID=intval($cmbDiscountID);
		$cmbDiscountType=intval($cmbDiscountType);
		$txtEffectiveDate=stripslashes(FixQuotes($txtEffectiveDate));
     	$txtEndDate=stripslashes(FixQuotes($txtEndDate));

		$txtPriority=intval($txtPriority);
	
		
		if($op=="add"){
		
			if(CheckExistTariffDiscount($cmbPackageID,$cmbDiscountID,$cmbDiscountType)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO trelTarDisc(PackageID,DiscID,DiscType,EffectivedDate,Priority) 
				 		VALUES('".$cmbPackageID."','".$cmbDiscountID."','".$cmbDiscountType."','".$txtEffectiveDate."','".$txtPriority."');";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add Tariff Discount","Add tariff discount with Package ID: $cmbPackageID, Discount Type: $cmbDiscountType,  Discount ID: $cmbDiscountID",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully add new tariff discount.");
				}else{
					$Audit->AddAudit(0,0,"Add Tariff Discount","Add tariff discount with Package ID: $cmbPackageID, Discount Type: $cmbDiscountType,  Discount ID: $cmbDiscountID",$user["FullName"],0,16);
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new tariff discount.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistTariffDiscount($cmbPackageID,$cmbDiscountID,$cmbDiscountType,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				if(trim($txtEndDate)==""){
					$txtEndDate="null";
				}
				$sql = "UPDATE trelTarDisc SET PackageID='".$cmbPackageID."',DiscID='".$cmbDiscountID."',DiscType='".$cmbDiscountType."', 
						EffectivedDate='".$txtEffectiveDate."', EndDate=".$txtEndDate."  WHERE TarDiscID='".intval($hid)."';";
				
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Update Tariff Discount","Update tariff discount ID: $hid",$user["FullName"],1,16);	
					$retOut = $myinfo->info("Successfully update tariff discount.");
				}else{
					$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update Tariff Discount","Update tariff discount ID: $hid",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update tariff discount.", $error['message']);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbDiscountID=0;
				$cmbDiscountType=0;
				$txtEffectiveDate="";
				$txtPriority="";
				$txtDescription="";
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from trelTarDisc WHERE TarDiscID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$cmbPackageID=intval($row["PackageID"]);
				$cmbDiscountID=intval($row["DiscID"]);
				$cmbDiscountType=intval($row["DiscType"]);
				$txtEffectiveDate=stripslashes($row["EffectivedDate"]);
				$txtPriority=stripslashes($row["Priority"]);
			//	print_r($row);
			}
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript" type="text/javascript" src="./javascript/ajax_sendrequest.js"></script>
<script language="javascript">

	
	
	function ValidateForm(){
		mPackageID=frml.cmbPackageID;
		mDiscountID=frml.cmbDiscountID;
		mDiscountType=frml.cmbDiscountType;
		mDescription=frml.txtDescription;
		mEffectiveDate=frml.txtEffectiveDate;
		mPriority=frml.txtPriority;
		frml.btnSubmit.disabled = true;
		frml.allowsubmit.value="true";
		frml.submit();
	}
	function GetDiscount(){
		//alert(frml.cmbDiscountType.options[myvalue.selectedIndex].value);
		frml.allowsubmit.value="false";
		frml.submit();
	}
	function Reset(){
		document.location.href="./?pg=1019";
	}
	  function GetDiscountName(id,target){
		 var myurl="./administration/ajax_infomation.php?id="+id+"&choice=discount"+"&ms="+new Date().getTime();;
	
		
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
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="57%">
	<tr>
		<td valign="top">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="630" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="469">
			   <tr>
				 <td width="465" height="18" align="left" class="formtitle"><strong>Tariff Discount </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="155" align="left">Package:</td>
					   <td align="left" width="302" colspan="3"><select name="cmbPackageID"  style="width: 240px;">
                         <option value="0">Select Package</option>
                         <?php
								$sql="select * from tblTarPackage where status=1 and serviceid not in (1,3,8,4) order by tarname";
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
					   <td align="left">Discount Type: </td>
					   <td align="left" colspan="3"><select name="cmbDiscountType"  style="width: 240px;" onchange="GetDiscountName(this.value,'cmbDiscountID');">
                         <option value="0">Select Discount Type</option>
                          <?php
								$sql="select * from tblSysEventDiscTypeCode where DiscType='5010' or DiscType='5011' or DiscType='5012' order by description";
								$result=$mydb->sql_query($sql);
								$selected="";
								while($row=$mydb->sql_fetchrow($result)){
									if($cmbDiscountType==intval($row['DiscType']))
										$selected = " selected";
									else
										$selected = "";
									
										
									echo "<option value='".intval($row['DiscType'])."' $selected>".stripslashes($row['Description'])."</option>";
								}
							?>
				       </select></td>
				     </tr>
					 <tr>
					   <td align="left">Discount Name: </td>
					   <td align="left" colspan="3"><select name="cmbDiscountID"  style="width: 240px;">
					   <option value="0">Select Discount Name</option>
					   <?php
					   			if(isset($cmbDiscountType)){
					   				
					   				if($cmbDiscountType=='5010'){
										$sql="select * from tblEventDiscDestSpecific where isactive='1' order by Description";
										$result=$mydb->sql_query($sql);
										$selected="";
										print $sql;
										while($row=$mydb->sql_fetchrow($result)){
											if($cmbDiscountID==intval($row['DiscDestID']))
												$selected = " selected";
											else
												$selected = "";
											
												
											echo "<option value='".intval($row['DiscDestID'])."' $selected>".stripslashes($row['Description'])."</option>";
										}
					   				}
					   				
					   				if($cmbDiscountType=='5011')
					   				{
					   					
					   				$sql="select * from tblEventDiscFreeCallAllowance where isactive='1'  order by Description";
										$result=$mydb->sql_query($sql);
										$selected="";
		
										while($row=$mydb->sql_fetchrow($result)){
											if($cmbDiscountID==intval($row['DiscFreeAllowID']))
												$selected = " selected";
											else
												$selected = "";
											
												
											echo "<option value='".intval($row['DiscFreeAllowID'])."' $selected>".stripslashes($row['Description'])."</option>";
										}
					   				}
					   				if($cmbDiscountType=='5012')
					   				{
					   					
					   				$sql="select * from dbo.tblEventDiscCallAllowance where isactive='1'  order by Description";
										$result=$mydb->sql_query($sql);
										$selected="";
		
										while($row=$mydb->sql_fetchrow($result)){
											if($cmbDiscountID==intval($row['DiscAllowID']))
												$selected = " selected";
											else
												$selected = "";
											
												
											echo "<option value='".intval($row['DiscAllowID'])."' $selected>".stripslashes($row['Description'])."</option>";
										}
					   				}
					   			}
							?>
					   </select></td>
				     </tr>
					 <tr>
					   <td align="left">Effective Date: </td>
					   <td align="left" colspan="3"><input type="text" style="width: 240px;" name="txtEffectiveDate" size="21" class="boxenabled" value="<?php print(formatDate($txtEffectiveDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEffectiveDate', '', 'width=200,height=220,top=250,left=350');">
						<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button></td>
				     </tr>
					 <tr>
					   <td align="left">Priority:</td>
					   <td align="left" colspan="3"><input type="text" name="txtPriority" value="<?php echo $txtPriority?>" class="boxenabled"  style="width: 240px;"/></td>
				     </tr>
					 <tr>
						<td align="left">End Date :</td>
					 	<td align="left" colspan="3"><input type="text" style="width: 240px;" name="txtEndDate" size="21" class="boxenabled" value="<?php print(formatDate($txtEndDate, 5));?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frml|txtEndDate', '', 'width=200,height=220,top=250,left=350');">
						<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">						</button></td>
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
			  <input type="hidden" name="pg" id="pg" value="1019">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="allowsubmit" id="allowsubmit" value="<?php echo $allowsubmit?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="626">
									<tr>
									 	<td align="left" class="formtitle"><strong>Tariff Discount  Information </strong></td>
										<td align="right">[<a href=".?pg=1019">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											 		 <th width="116">No.</th>
													<th width="285">Package</th>
													<th width="285">Discount Type</th>
													<th width="285">Discount Name</th>
													<th width="285">Effective Date</th>
													<th width="497">End Date</th>
													<th width="497">Priority</th>
													<th width="25">Edit</th>
												
												</tr>
											
												<?php
												$sql2="select ttd.TarDiscID,ttp.TarName ,ttd.EffectivedDate, ttd.EndDate, ttd.Priority,   
													tsedtc.Description 'DiscountType', 
													CASE when tsedtc.DiscType='5010' then 
															tedds.Description 
													     when tsedtc.DiscType='5011'then 
														tedfca.Description 
														when tsedtc.DiscType='5012' then
															ca.Description
													end 'DiscountName'							
													from trelTarDisc ttd 
															left join tblTarPackage ttp 
															on ttd.PackageID=ttp.PackageID 
															left join tblSysEventDiscTypeCode tsedtc 
															on ttd.DiscType=tsedtc.DiscType 
															left join tblEventDiscDestSpecific tedds 
															on ttd.DiscID=tedds.DiscDestID 
															left join tblEventDiscFreeCallAllowance tedfca 
															on ttd.DiscID=tedfca.DiscFreeAllowID 
															left join tblEventDiscCallAllowance ca
															on ttd.DiscID=ca.DiscAllowID
													order by EndDate";
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
														<td align="center"><font color="#aaaaaa">'.$row['TarDiscID'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['TarName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['DiscountType'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['DiscountName'].'</font></td>
														<td  align="left"><font color="#aaaaaa">'.$row['EffectivedDate'].'</font></td>												
														<td  align="left"><font color="#aaaaaa">'.$row['EndDate'].'</font></td>	
														<td  align="left"><font color="#aaaaaa">'.$row['Priority'].'</font></td>														
													</tr>';
													}else{
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['TarDiscID'].'</td>
														<td  class="'.$rowclass.'" align="left">'.$row['TarName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['DiscountType'].'</td>
														<td class="'.$rowclass.'"  align="left">'.$row['DiscountName'].'</td>
														<td class="'.$rowclass.'" align="left">'.$row['EffectivedDate'].'</td>												
														<td class="'.$rowclass.'" align="left">'.$row['EndDate'].'</td>	
														<td class="'.$rowclass.'" align="left">'.$row['Priority'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1019&amp;op=edit&amp;hid='.$row['TarDiscID'].'"><img src="./images/Edit.gif" border="0"></a></td>
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