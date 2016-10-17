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
	function getpackage(){
		service = finvoicereport.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			finvoicereport.selservice.focus();
			return;
		}else{
			url = "./php/ajax_getpackage.php?serviceid="+service;
			getTranDetail(url, "d-package");
		}
	}
	
	function showReport(){		
			
			cid = finvoicereport.selCycle.options[finvoicereport.selCycle.selectedIndex].value;
			ct = finvoicereport.selCycle.options[finvoicereport.selCycle.selectedIndex].text;
			
			where = "0 ";
			for(i=0; i< finvoicereport.package.length; i++){
				if(finvoicereport.package[i].checked == true)
					where += ", " + finvoicereport.pack[i].value;
			}
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		//	document.getElementById("d-invoicereport").innerHTML = loading;
		//	document.getElementById("d-invoicesummary").innerHTML = loading;						
						
			// Invoice summary ===================
			url1 = "./php/ajax_invoicereport.php?cid="+cid+"&ct="+ct+"&where="+where+"&mt="+new Date().getTime();
			window.open(url1);
		//	getTranDetail(url1, "d-invoicesummary");
			
			// Invoice detail ====================
		//	url = "./php/ajax_invoicereport.php?cid="+cid+"&where="+where;
		//	setTimeout('getTranDetail(url, "d-invoicereport");', 1000);			
			
		
	}
	function exportReport(){
		st = finvoicereport.st.value;
		et = finvoicereport.et.value;
		url = "./export/invoice_report.php?st="+st+"&et="+et+"&type=csv";
		window.open(url);
	}
	
	function getpackage(check){
		service = finvoicereport.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			finvoicereport.selservice.focus();
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
					<td width="287" align="left" class="formtitle"><b>DAILY INVOICE REPORT</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="finvoicereport" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Bill cycle</td>
								<td align="left">
									<select name="selCycle" style="width:200px">
										<?php
											$sql = "
																SELECT 
																DISTINCT BillEndDate 
																FROM tblSysBillRUnCycleInfo(nolock) 
																WHERE BillProcessed = 1 
																UNION
																SELECT MIN(BillEndDate)
																FROM tblSysBillRUnCycleInfo(nolock)
																WHERE BillProcessed = 0
																ORDER BY 1 DESC";
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
							</tr>	
							<tr>
								<td align="left">Service:</td>								
									<td align="left">
										<select name="selservice" style="width:200px" onChange="getpackage(1);" id="selservice">
											<option value='0'>Select service</option>
												<option value="2">Telephone</option>
												<option value="4">LeaseLine</option>
												<option value="5">ISP</option>																															
										</select>							 
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-package"></div>
								</td>
							</tr>	
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<input type="button" name="btnSubmit" value="View report" onclick="showReport();" />
									<input type="button" name="download" value="Download" onclick="exportReport();" />
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
