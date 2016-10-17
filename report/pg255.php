<?php
	require_once("./common/functions.php");
	/*
		+ ************************************************************************************** +	
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/		
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<!--<script language="JavaScript" src="./javascript/ajax_getreport.js"></script>-->
<script language="javascript" type="text/javascript">
	
	function showReport(){
		if(revenuesummary.selservice.selectedIndex < 1)
			alert("Please select service");
		else{
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			//pid = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].value;
		//	pt = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			ct = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].text;
			
			where = "0 ";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true)
					where += ", " + revenuesummary.pack[i].value;
			}
			
			tid = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tid += ", " + revenuesummary.lid[i].value;
			}
			
			tt = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tt += ", " + revenuesummary.lname[i].value;
			}
			
		
			url = "./php/ajax_revenue_summary_Group_Package.php?&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st+"&w="+where;
			window.open(url);	
		}
	}
	
	function showGroupReport(){
		if(revenuesummary.selservice.selectedIndex < 1)
			alert("Please select service");
		else{
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			//pid = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].value;
		//	pt = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			ct = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].text;
				
			where = "0 ";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true)
					where += ", " + revenuesummary.pack[i].value;
			}
			
			tid = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tid += ", " + revenuesummary.lid[i].value;
			}
			
			tt = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tt += ", " + revenuesummary.lname[i].value;
			}
			
			url = "./php/ajax_Location_revenue_summary_InOne.php?&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st+"&w="+where;
			window.open(url);	
		}
	}


	function showLocationRevenue(){
		if(revenuesummary.selservice.selectedIndex < 1)
			alert("Please select service");
		else{
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			//pid = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].value;
		//	pt = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			ct = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].text;
			
				
			where = "0 ";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true)
					where += ", " + revenuesummary.pack[i].value;
			}
			
			tid = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tid += ", " + revenuesummary.lid[i].value;
			}
			
			tt = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tt += ", " + revenuesummary.lname[i].value;
			}
			
			
			url = "./php/ajax_Location_revenue_summary.php?&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st+"&w="+where;
			window.open(url);	
		}
	}

	
	
	function getpackage(check){
		service = revenuesummary.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			revenuesummary.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");		
		}
	}
	
	function getcity(check){	
	
			urlCity = "./php/ajax_location_city.php?ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(urlCity, "d-location");		
	}
	
</script>

<body onLoad="getcity(1);">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>MONTHLY REVENUE SUMMARY REPORT</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="revenuesummary">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Cycle date:</td>	
								<td align="left">Service:</td>		
																						
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
												<option value="1">ISP</option>
												
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
												
							</tr>					
							<tr>
								<td colspan="2">
									<div id="d-location"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-package"></div>
								</td>
							</tr>							
							<tr>								
								<td align="center" colspan="3">
									<input type="button" name="btnSubmit" value="Group Package" onClick="showReport();" />
									<input type="button" name="btnSubmit" value="Group Revenue" onClick="showGroupReport();" />
									<input type="button" name="btnReLoc" value="Location Revenue" onClick="showLocationRevenue();" />
								</td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td align="left" valign="top">
			<div id="d-invoicesummary">			
		</div>
		</td>
	</tr>		
	<tr><td colspan="2">&nbsp;</td></tr>				
	<tr><td colspan="2">
		<div id="d-invoicereport">
		</div>
	</td></tr>
</table>	
</body>