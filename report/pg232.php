<?php
	require_once("./common/agent.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/		
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
//php print date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
	function showReport(){
		tid = fcategory.tid.options[fcategory.tid.selectedIndex].value;
		oid = fcategory.oid.options[fcategory.oid.selectedIndex].value;
		where = 0;
		if(fcategory.selservice.selectedIndex <1){
			alert("Please select service");
		}else{		
			for(i=0; i< fcategory.package.length; i++){
				if(fcategory.package[i].checked == true)
					where += ", " + fcategory.pack[i].value;
			}
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;					
						
			// Invoice summary ===================
			url1 = "./php/ajax_cust_category.php?tid="+tid+"&oid="+oid+"&where="+where+"&mt="+ new Date().getTime();
			getTranDetail(url1, "d-result");	
		}						
	}
	function getpackage(check){
		service = fcategory.selservice.value;		
		
		if(service == ""){
			alert("Please select Service");
			fcategory.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>Customer report by category</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fcategory" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Category:</td>
								<td align="left">
									<select name="tid">
										<option value="-1" selected="selected">All</option>
										<?php
											$sql = "select TypeID, Name	from tlkpCustBusinessType";
											if($que = $mydb->sql_query($sql)){
												while ($result = $mydb->sql_fetchrow($que)){
													$TypeID = $result['TypeID'];
													$Name = $result['Name'];
													print "<option value='".$TypeID."'>".$Name."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="left">Occupation:</td>
								<td align="left">
									<select name="oid">
										<option value="0" selected="selected">All</option>
										<?php
											$sql = "select CareerID, Career	from tlkpcareer";
											if($que = $mydb->sql_query($sql)){
												while ($result = $mydb->sql_fetchrow($que)){
													$CareerID = $result['CareerID'];
													$Career = $result['Career'];
													print "<option value='".$CareerID."'>".$Career."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>	
							<tr>
								<td align="left" nowrap="nowrap">Service:</td>
								<td align="left" width="90%">
										<select name="selservice" style="width:200px" onChange="getpackage(1);">
											<option value='0'>Select service</option>
											<option value="2">Telephone</option>
											<option value="4">LeaseLine</option>
											<option value="5">ISP</option>
										</select>
							  </td>
							</tr>
							<tr>								
								<td align="left" colspan="2">
									<div id="d-package">
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="button" name="btnSubmit" value="View report" onclick="showReport();" /></td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>		
	</tr>		
	<tr><td>&nbsp;</td></tr>				
	<tr><td>
		<div id="d-result">
		</div>
	</td></tr>
</table>