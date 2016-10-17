
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
	if(isset($smt) && (!empty($smt)) && ($pg == 1024) && $allowsubmit=="true"){
	# Begin transaction sign up messenger
		#$mydb->mssql_begin_transaction();
		
		$cmbPackageID=intval($cmbPackageID);
		$cmbDiscountID=intval($cmbDiscountID);
	
		
		if($op=="add"){
		
			if(CheckExistMinuteBaseTariffDiscount($cmbPackageID,$cmbDiscountID)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "INSERT INTO trelCycleDiscMinuteBase(PackageID,DiscID) 
				 		VALUES('".$cmbPackageID."','".$cmbDiscountID."');";
				if($mydb->sql_query($sql)){
					$Audit->AddAudit(0,0,"Add Minute Base Tariff Discount","Add tariff discount with Package ID: $cmbPackageID, Discount Type: $cmbDiscountType,  Discount ID: $cmbDiscountID",$user["FullName"],1,16);
					$retOut = $myinfo->info("Successfully add new tariff discount.");
				}else{
					$Audit->AddAudit(0,0,"Add Minute Base Tariff Discount","Add tariff discount with Package ID: $cmbPackageID, Discount Type: $cmbDiscountType,  Discount ID: $cmbDiscountID",$user["FullName"],0,16);
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add new tariff discount.", $error['message']);
				}
			}	
			
		}elseif($op=="edit"){
		 
			if(CheckExistMinuteBaseTariffDiscount($cmbPackageID,$cmbDiscountID,true,$hid)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
				$sql = "UPDATE trelCycleDiscMinuteBase SET PackageID='".$cmbPackageID."', DiscID='".$cmbDiscountID."'  WHERE TarID='".intval($hid)."';";
		//		print $sql;
				if($mydb->sql_query($sql)){			
					$Audit->AddAudit(0,0,"Update Minute Base Tariff Discount","Update tariff discount ID: $hid",$user["FullName"],1,16);		
					$retOut = $myinfo->info("Successfully update tariff discount.");
				}else{
	//				$error = $mydb->error();
					$Audit->AddAudit(0,0,"Update Minute Base Tariff Discount","Update tariff discount ID: $hid",$user["FullName"],0,16);
					$retOut = $myinfo->error("Failed to update tariff discount.", $error['message']);
				}
				$op="add";
				$cmbPackageID=0;
				$cmbDiscountID=0;
			}
				
		}
	}elseif(isset($hid) && isset($op)){
	
		if($op=="edit"){
			$sql="select * from trelCycleDiscMinuteBase WHERE TarID='".intval($hid)."'";
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$cmbPackageID=intval($row["PackageID"]);
				$cmbDiscountID=intval($row["DiscID"]);
			
			//	print_r($row);
			}
		}
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script language="javascript">

	
	
	function ValidateForm(){
		mPackageID=frml.cmbPackageID;
		mDiscountID=frml.cmbDiscountID;
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
	
</script><table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="157">
		<?php include_once("left.php");?>		</td>
		<td valign="top" width="634" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg"  width="469">
			   <tr>
				 <td width="465" height="18" align="left" class="formtitle"><strong>Tariff Minute Base Discount </strong></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="155" align="left">Package:</td>
					   <td align="left" width="302" colspan="3">
					   <select name="cmbPackageID" style="width: 150px;">
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
					   <td align="left">Discount Name: </td>
					   <td align="left" colspan="3"><select name="cmbDiscountID" style="width: 150px">
					   <option value="0">Select Discount Name</option>
					   <?php
										$sql="select * from tblCycleDiscMinuteBase where isactive='1'";
										$result=$mydb->sql_query($sql);
										$selected="";
										print $sql;
										while($row=$mydb->sql_fetchrow($result)){
											if($cmbDiscountID==intval($row['DiscID']))
												$selected = " selected";
											else
												$selected = "";
											
												
											echo "<option value='".intval($row['DiscID'])."' $selected>".stripslashes($row['Description'])."</option>";
										}					   			
							?>
					   </select></td>
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
			  <input type="hidden" name="pg" id="pg" value="1024">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="hid" id="hid" value="<?php echo $hid?>" />
			  <input type="hidden" name="allowsubmit" id="allowsubmit" value="<?php echo $allowsubmit?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>
<div >
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
									<tr>
									 	<td align="left" class="formtitle"><strong>Tariff Discount  Information </strong></td>
										<td align="right">[<a href=".?pg=1024">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="" bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
											 		 <th width="116">ID</th>
													 <th width="285">Package</th>
													<th width="285">Discount Name</th>
													<th width="25">Edit</th>
												
												</tr>
											
												<?php
												$sql2="select cdm.TarID,ttp.TarName, ttd.Description
from trelCycleDiscMinuteBase cdm
		left join tblTarPackage ttp
		on cdm.PackageID=ttp.PackageID
		left join tblCycleDiscMinuteBase ttd
		on cdm.DiscID=ttd.DiscID 
		order by ttp.TarName
		";
												$query2=$mydb->sql_query($sql2);
												$rowcount=0;
												$rowclass="";
												$rowdeactiveclass="";
												while($row=$mydb->sql_fetchrow($query2))																	                                                {
												    if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
														$rowdeactiveclass="";
echo '<tr >
														<td class="'.$rowclass.'" align="center">'.$row['TarID'].'</td>
														<td  class="'.$rowclass.'" align="left">'.$row['TarName'].'</td>
														<td class="'.$rowclass.'"  align="left">'.$row['Description'].'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=1024&amp;op=edit&amp;hid='.$row['TarID'].'"><img src="./images/Edit.gif" border="0"></a></td>
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