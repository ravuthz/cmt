<?php
	require_once("./common/agent.php");	
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
	$month = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>	

<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">		
	function showReport(){
		
		loc = f1.location.options[f1.location.selectedIndex].value;
		locn = f1.location.options[f1.location.selectedIndex].text;		
		cycle = f1.selCycle.options[f1.selCycle.selectedIndex].value;		
		where = "0 ";
		for(i=0; i< f1.package.length; i++){
			if(f1.package[i].checked == true)
				where += ", " + f1.pack[i].value;
		}
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;
			url = "./php/ajax_billtype.php?cycle="+cycle+"&w="+where+"&mt="+ new Date().getTime() + "&loc=" +loc+"&locn=" + locn;			
			getTranDetail(url, "d-result");
		
	}
	function showReport1(){

		where = "0 ";
		for(i=0; i< f1.package.length; i++){
			if(f1.package[i].checked == true)
				where += ", " + f1.pack[i].value;
		}
		cycle = f1.selCycle.options[f1.selCycle.selectedIndex].value;	
		loc = f1.location.options[f1.location.selectedIndex].value;
		locn = f1.location.options[f1.location.selectedIndex].text;			
						
			url = "./report/billsummary.php?cycle="+cycle+"&w="+where+"&mt="+ new Date().getTime() + "&loc=" +loc+"&locn=" + locn;			
			window.open(url);
			//getTranDetail(url, "d-result");
		
	}
	
	function showReport2(){

		where = "0 ";
		for(i=0; i< f1.package.length; i++){
			if(f1.package[i].checked == true)
				where += ", " + f1.pack[i].value;
		}
		cycle = f1.selCycle.options[f1.selCycle.selectedIndex].value;
		loc = f1.location.options[f1.location.selectedIndex].value;
		locn = f1.location.options[f1.location.selectedIndex].text;		
						
			url = "./report/grouptraffic.php?cycle="+cycle+"&w="+where+"&mt="+ new Date().getTime() + "&loc=" +loc+"&locn=" + locn;			
			window.open(url);
			//getTranDetail(url, "d-result");
		
	}

	
	
	function download(){

		where = "0 ";
		for(i=0; i< f1.package.length; i++){
			if(f1.package[i].checked == true)
				where += ", " + f1.pack[i].value;
		}
		cycle = f1.selCycle.options[f1.selCycle.selectedIndex].value;			
						
			url = "./export/statistic_bill_process.php?cycle="+cycle+"&w="+where+"&mt="+ new Date().getTime();			
			window.location = url;

		
	}
	function getpackage(check){
		service = f1.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			f1.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>									
		<td align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>STATISTIC OF BILL PROCESSING</b></td>
				</tr> 
				<tr>
					<td colspan="3">
						<form name="f1" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
						<tr>
					<td align="left">Select month:</td>
					<td align="left">Select service:</td>
					<td align="left">Select Location:</td>
				</tr>
							<tr>
								<td align="left">
									<select name="selCycle" style="width:200px">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".FormatDate($BillEndDate, 4)."'>".FormatDate($BillEndDate, 2)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>									
								</td>
								<td align="left">
										<select name="selservice" style="width:200px" onChange="getpackage(1);" id="selservice">
											<option value='0'>Select service</option>
											<option value="2">Telephone</option>
												<option value="4">LeaseLine</option>
												<option value="5">ISP</option>
											<?php
									//$sql = "select ServiceID, ServiceName from tlkpService where ServiceTypeID = 1;";
//									// sql 2005
//									
//									$que = $mydb->sql_query($sql);									
//									if($que){
//										while($rst = $mydb->sql_fetchrow($que)){	
//											$ServiceID = $rst['ServiceID'];
//											$ServiceName = $rst['ServiceName'];
//											
//											print "<option value='".$ServiceID."'>".$ServiceName."</option>";
//										}
//									}
//									$mydb->sql_freeresult();
								?>								
										</select>
							  </td>
							  <td align="left">
									<select name="location"  style="width:200px">
										<option value='All'>All</option>
										<?php
											$sql = "select ID LocID, Name LocName  from tlkpLocation where type = 2 order by Name";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$LocID = $result['LocID'];
													$LocName = $result['LocName'];
													print "<option value='".$LocID."'>".$LocName."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>	
							</tr>
							<tr>
								<td colspan="3">
									<div id="d-package"></div>
								</td>
							</tr>				
							<tr>
								<td colspan="3">
																	
									<input type="button" name="btnSubmit1"  value="View report" <?php print $dis; ?> onClick="showReport();" />
									<input type="button" name="btnSubmit1"  value="Print preview" <?php print $dis; ?> onClick="showReport1();" />
									<input type="button" name="btnSubmit"  value="Group Traffic" <?php print $dis; ?> onClick="showReport2();" />
								</td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>		
	<tr><td colspan="2">&nbsp;</td></tr>				
	<tr><td colspan="2">
		<div id="d-result">
		</div>
	</td></tr>
</table>