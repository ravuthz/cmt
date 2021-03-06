<?php

	//error_reporting(E_ALL & E_ERROR & E_NOTICE);
	require_once("./common/agent.php");	
    require_once("./administration/function.php");
	require_once("./common/class.audit.php");
	require_once("./common/functions.php");
	
	$Audit=new Audit();
	if(isset($smt) && (!empty($smt)) && ($pg == 2223)){

		
		$txtusername = stripslashes(FixQuotes($txtusername));
		$selType = stripslashes(FixQuotes($selType));
		if($op=="add"){
		
			if(CheckUserNameExistingDetail($txtusername)){
				$retOut=$myinfo->warning("The information already existed.");
			}else{
			
				$sql = "INSERT INTO tblExceptionDetail(AccID, Remark, Dedate,Expire,CycleID) select $accid,'$selType','$txtre 23:59:59.000','$txted',(select cycleid from tblSysBillRunCycleInfo where  BillEndDate='$selCycle'+' 23:59:59.000' and packageID=(select packageid from tblCustProduct where accid=$accid));";
					
				//print $sql;
				if($mydb->sql_query($sql)){						
					$Audit->AddAudit(0,0,"Add Exception",$txtCustName,$user["FullName"],1,15);			
					$retOut = $myinfo->info("Successfully add new excepted phone.");
					$txtusername="";
					$txtCustName="";
				}else{
				$Audit->AddAudit(0,0,"Add Exception",$txtCustName,$user["FullName"],0,15);	
					$error = $mydb->sql-error();
					$retOut = $myinfo->error("Failed to add new excepted phone", $error['message']);
				}
			}	
			
			
		}elseif($op=="edit"){
			if(!CheckUserNameExisting($txtusername)){
			 	
			 			$retOut=$myinfo->warning("The information doesn't existed.");
			}else{
			       
					$sql = "UPDATE tblExceptionDetail SET Remark='".$selType."', Dedate='".$txtre." 23:59:59.000', Expire='$txted 23:59:59.000',CycleID=(select cycleid from tblSysBillRunCycleInfo where  BillEndDate='$selCycle'+' 23:59:59.000' and packageID=(select packageid from tblCustProduct where accid=$accid)) WHERE accID=".intval($accid).";";
//				/	print $sql;
					if($mydb->sql_query($sql)){
						$Audit->AddAudit(0,0,"Update Exception Detail","Update Exception Detail id $accid cycleid $cycleid",$user["FullName"],1,15);
						$retOut = $myinfo->info("Successfully update exception detail.");
					}else{
						$error=$mydb->sql_error();
						$Audit->AddAudit(0,0,"Update exception","Update exception detail id: $accid cycleid: $cycleid",$user["FullName"],0,15);
						$retOut = $myinfo->error("Failed to update exception detail", $error["message"]);
					}
					$op="add";
					$txtusername="";
					$txtCustName="";
			}
		}
		//echo "second edit";
		
	}elseif((isset($accid)) && isset($op)){
	
		if($op=="edit"){
			
			if(strval($cycleid)!='0')
			{		
					$sql="select (select username from tblCustProduct where accid=E.accid) username,remark,dedate,expire,(select BillEndDate from tblSysBillRuncycleInfo where cycleid=E.cycleid and packageID=(select packageID from tblCustProduct where accid=E.accid)) billdate from tblExceptionDetail E WHERE accID=".intval($accid)." and cycleid='$cycleid'";
			}
			else{		
					$sql="select (select username from tblCustProduct where accid=E.accid) username,remark,dedate,expire,(select BillEndDate from tblSysBillRuncycleInfo where cycleid=E.cycleid and packageID=(select packageID from tblCustProduct where accid=E.accid)) billdate from tblExceptionDetail E WHERE accID=".intval($accid)." and cycleid is null";
			}
			
			$query=$mydb->sql_query($sql);
			while($row=$mydb->sql_fetchrow($query)){
				$txtusername =$row['username'];
				$selType = $row['remark'];
				$txtre = FormatDate($row['dedate'],5);
				$txted = FormatDate($row['expire'],5);
				$selCycle = FormatDate($row['billdate'],5);
			}
		}elseif($op=="delete"){
			$sql="delete tblException WHERE accid='$accid'";
			  // print $sql;
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Delete exception","Delete exception detail id: $accid cycleid : $cycleid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully delete exception detail.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Delete exception","Delete exception id: $accid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to delete exception", $error["message"]);
				}
				
				$op="add";		
		}elseif($op=="deletedetail"){
			
			if($re=="Exception")
			{
				$sql="delete from tblExceptionDetail WHERE accid=$accid";
			}
			else
			{
				$sql="delete from tblExceptionDetail WHERE accid=$accid and cycleid='$cycleid'";
			}
			
			//$retOut = $myinfo->info($sql);
				if($mydb->sql_query($sql)){
					# commit transaction
					#$mydb->mssql_commit();
					$Audit->AddAudit(0,0,"Delete exception detail","Delete exception detail id: $accid cycleid : $cycleid",$user["FullName"],1,15);
					$retOut = $myinfo->info("Successfully delete exception detail.");
				}else{
					$error=$mydb->sql_error();
					$Audit->AddAudit(0,0,"Delete exception detail","Delete exception detail id: $accid cycleid: $cycleid",$user["FullName"],0,15);
					$retOut = $myinfo->error("Failed to delete exception detail", $error["message"]);
				}
				
				$op="add";		
		}
		//echo "first edit";
	}
?>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="javascript" src="./javascript/ajax_checkaccname.js"></script>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/sorttable.js"></script>
<script type="text/javascript" src="./javascript/ajax_getcontent.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/treegrid.js"></script>
<script language="javascript">

	function ActionConfirmationDeleteExceptionDetail(id, cycleid, Type){
		if(confirm("Do you want to delete detail : " + cycleid + "?"))
			window.location = "./?pg=2223&op=deletedetail&accid=" + id +"&cycleid=" + cycleid+"&re=" + Type;
	}
	
	
	function ActionConfirmationDeleteException(id, code){
		if(confirm("Do you want to delete detail : " + code + "?"))
			window.location = "./?pg=2223&op=delete&accid=" + id;
	}
	
	
	function ValidateForm(){
		musername = document.frml.txtusername;
		
		if(Trim(musername.value) == ""){
			alert("Please enter user name.");
			musername.focus();
			return;
		}
		
		frml.btnSubmit.disabled = true;
		frml.submit();
	}
	
	
	function Reset(){
	
		document.location.href="./?pg=2223";
		
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
var contentname="exception";
var blockname="exception";
var url="./administration/ajax_getcontent.php";

function getContent(id,orderby,ordertype){
	 try{
	 	
	 	 var url2=url+"?contentname="+contentname+"&id="+id+"&orderby="+orderby+"&ordertype="+ordertype+"&mt=" + new Date().getTime();
		 blockid=id;
		//alert(url2);
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
		
	function ValidAccount(retDiv,acid){

		username = frml.txtusername.value;
		url = "./php/ajax_checkaccnamedetail.php?username=" + username +"&mt=" + new Date().getTime();
		checkUserNameNot(url, retDiv, acid);
	}
	
</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top">
		<?php include("left.php"); ?></td>	
		<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
	
		<td valign="top" width="650" align="left"> 
<form name="frml" method="post" action="./">
<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="574">
			   <tr>
				 <td align="left" class="formtitle" height="18"><b>Exception </b></td>
			   </tr>
			   <tr>
				 <td valign="top">
				   <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
					 
					 <tr>
					 	<td width="98" align="left">User Name :</td>
						<td align="left" width="464" colspan="3">
						  <input type="text" name="txtusername" tabindex="1" style="width:150;" class="boxenabled" value="<?php  print($txtusername);?>" size="30" onblur="ValidAccount('dUserName','accid');"/><img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName" class="error"></span>
						</td>
					</tr>	
							
					<tr>
						<td align="left">Type :</td>
					 	<td align="left" colspan="3">
									<select name="selType" style="width:120px">
										<?php 
												if ($selType=='Exception')
												{
													print "<option value='Exception' selected>Exception</option>";
													print "<option value='Delay' >Delay</option>";
												}
												else
												{
													print "<option value='Delay' selected>Delay</option>";
													print "<option value='Exception'>Exception</option>";
												}
											?>
									</select>
						</td>
					</tr>
					
					<tr>
						<td align="left">Request Date :</td>
					 	<td align="left" colspan="3">
									<input type="text" name="txtre" class="boxenabled" size="17" maxlength="15" value="<?php print FormatDate($txtre,5); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />						
						</td>
					</tr>
					<tr>
						<td align="left">Expire Date :</td>
					 	<td align="left" colspan="3">
									<input type="text" name="txted" class="boxenabled" size="17" maxlength="15" value="<?php print FormatDate($txted,5); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />												</td>
					</tr>
					
					<tr>
						<td align="left">Bill Date :</td>
					 	<td align="left" colspan="3">
									<select name="selCycle" style="width:120px" onchange="">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													if($selCycle==FormatDate($BillEndDate, 5))
														$selected = " selected";
													else
														$selected = "";
													
													print "<option value='".FormatDate($BillEndDate, 5)."' $selected>".FormatDate($BillEndDate, 5)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
											
										?>
									</select>
									</td>
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
			  <input type="hidden" name="pg" id="pg" value="2223">
			   <input type="hidden" name="subop" id="subop" value="1">
			  <input type="hidden" name="op" id="op" value="<?php echo $op?>" />
			  <input type="hidden" name="accid" id="accid" value="<?php echo $accid?>" />
			  <input type="hidden" name="smt" id="smt" value="yes">
</form>

				  <div align="left">
					<table border="0" cellpadding="2" cellspacing="0" class="formbg" width="573">
									<tr>
									 	<td align="left" class="formtitle"><strong>Exception Detail Information.</strong></td>
										<td align="right">[<a href="./?pg=2223">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class=""  bordercolor="#aaaaaa"  bgcolor="#EFEFEF"  style="border-collapse:collapse">
												<tr>
												<th width="1">&nbsp;</th>
													<th width="49">ID</th>
													<th width="229">User Name</th>
													<th width="221">Customer Name</th>
													<th width="27">Edit</th>
												
												</tr>
												
												
												<?php
												$sql="select AccID, AccName, CustName ".
												     "from tblException ".
												     "ORDER BY AccName";
												$result=$mydb->sql_query($sql);
												$rowcount=0;
												$rowclass="";
												while($row=$mydb->sql_fetchrow($result))
												{
												
													if($rowcount%2==0){
														$rowclass="row1";
													}else{
														$rowclass="row2";
													}
													
													$AccID=intval($row["AccID"]);
													$AccName=stripslashes($row['AccName']);
													$CustName=stripslashes($row['CustName']);
													echo "<div id='cont'>";		
												
												
													 echo "<tr >";

													echo "<td  class='".$rowclass."'>";
								    				if(HasExceptionDetail($AccID))
													{
													
									echo "<a onMouseOver=\"this.style.cursor='hand'\">";	
																		
									echo "<img src=\"./images/plus1.gif\" border=\"0\" id=\"img".$AccID."\" name=\"img" .$AccID. "\" onClick=\"SwitchMenu('exception" .$AccID. "', 'img".$AccID. "');  getContent(".$AccID.",'','');\">";
									echo "</a>";
													
													}
													echo "</td>";
echo '
	<td class="'.$rowclass.'" align="center">'.$AccID.'</td>
														<td class="'.$rowclass.'" align="left">'.$AccName.'</td>
													
														<td class="'.$rowclass.'" align="left">'.$CustName.'</td>
														<td class="'.$rowclass.'" align="right"><a href="./?pg=2222&amp;op=edit&amp;accid='.$row['AccID'].'"><img src="./images/Edit.gif" border="0"></a>&nbsp;<a href="javascript:ActionConfirmationDeleteException('.$row['AccID'].',\''.$row['AccName'].'\')"><img src="./images/Delete.gif" border="0"></a></td>
													</tr>';											
													
														if(HasExceptionDetail($AccID)){
														
															echo "<tr  bgcolor='#ffffff'>";
															echo "<td></td>";						
															echo "<td colspan='4' >";
															echo "<div id='exception".$AccID."'  style='display:none;'>";
															echo "</div>";
														
															echo "</td>";				
															echo "</tr>";	
																		
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

</td>
</tr>
</table>	
<?php
# Close connection
$mydb->sql_close();
?>