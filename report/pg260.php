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
			sn = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			//pid = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].value;
		//	pt = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			ct = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].text;
			tid = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].value;
			tt = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].text;
				
			
			url = "./php/ajax_revenue_customer.php?&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st+"&sn="+sn;
			window.open(url);	
		}
	}
	function exportReport(){
		if(revenuesummary.selservice.selectedIndex < 1)
			alert("Please select service");
		else{
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			sn = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;
			//pid = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].value;
		//	pt = revenuesummary.selpackage.options[revenuesummary.selpackage.selectedIndex].text;
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			ct = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].text;
			tid = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].value;
			tt = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].text;
			
			url = "./export/revenue_summary_bycustomer.php?&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt+"&sid="+sid+"&st="+st+"&sn="+sn;
			window.location = url;	
		}
	}
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td align="left" class="formtitle"><b>MONTHLY REVENUE SUMMARY BY CUSTOMERS REPORT</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="revenuesummary">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Cycle date:</td>	
								<td align="left">Service:</td>		
								<td align="left">Invoice type</td>														
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
										<select name="selservice" style="width:200px" id="selservice">
											<option value='0'>Select service</option>
											
											<option value="4">LeaseLine</option>
											
										</select>
							  </td>
								<td align="left">
									<select name="invoicetype"  style="width:200px">
										<option value="0">All</option>
										<option value="1">Cycle bill</option>
										<option value="2">Demand bill</option>
										<option value="3">Other bill</option>
									</select>
								</td>					
							</tr>									
							<tr>								
								<td align="center" colspan="3">
									<input type="button" name="btnSubmit" value="View report" onClick="showReport();" />
									<input type="button" name="download" value="Download" onClick="exportReport();" />
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