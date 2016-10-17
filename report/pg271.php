<?php
	require_once("./common/functions.php");
?>


<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<!--<script language="JavaScript" src="./javascript/ajax_getreport.js"></script>-->
<script language="javascript" type="text/javascript">


	function showLocationRevenue(btn){
		
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			cid2 = revenuesummary.selCycle2.options[revenuesummary.selCycle2.selectedIndex].value;
			
				
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
			
			if(btn == 1)
				url = "./php/ajax_Percentage_Debt.php?&cid="+cid+"&cid2="+cid2+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st;
			else
				url = "./php/ajax_Percentage_Debt_BillEndDate.php?&cid="+cid+"&cid2="+cid2+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st;
			window.open(url);	
		
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
					<td width="287" align="left" class="formtitle"><b>MONTHLY DEBT SUMMARY REPORT</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="revenuesummary">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
							  <td align="left">From  Date:</td>
								<td align="left">To Date:</td>	
								<td align="left">Service:</td>		
																						
							</tr>
							
							<tr>
							  <td align="left"><select name="selCycle2" style="width:200px">
							    <?php
											$sql = "SELECT DISTINCT Convert(varchar,BillEndDate,102) BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".$BillEndDate."'>".$BillEndDate."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
						      </select></td>
								<td align="left">
									<select name="selCycle" style="width:200px">
										<?php
											$sql = "SELECT DISTINCT Convert(varchar,BillEndDate,102) BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".$BillEndDate."'>".$BillEndDate."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>
								
								<td align="left">
										<select name="selservice" style="width:200px" id="selservice">
											<option value='0'>All</option>
												<option value="2">Telephone</option>
												<option value="4">LeaseLine</option>
												<option value="1">ISP</option>
										</select>
							  </td>
												
							</tr>					
							<tr>
								<td colspan="3">
									<div id="d-location"></div>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<div id="d-package"></div>
								</td>
							</tr>							
							<tr>								
								<td align="center" colspan="4">
									<input type="button" name="btnReLoc" value="View Report Group by Location" onClick="showLocationRevenue(1);" />
                                    <input type="button" name="btnReLocB" value="View Report Group by Cycle" onClick="showLocationRevenue(2);" />
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