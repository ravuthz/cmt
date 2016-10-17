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
?>	

<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
	function showReport(){
		cid = frevenue.selCycle.options[frevenue.selCycle.selectedIndex].value;
		ct = frevenue.selCycle.options[frevenue.selCycle.selectedIndex].text;
		service = frevenue.selservice.options[frevenue.selservice.selectedIndex].value;
		sname = frevenue.selservice.options[frevenue.selservice.selectedIndex].text;
		
		where = "0 ";
			for(i=0; i< frevenue.package.length; i++){
				if(frevenue.package[i].checked == true)
					where += ", " + frevenue.pack[i].value;
			}
			
		
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//document.getElementById("d-result").innerHTML = loading;
			url = "./report/revenue.php?cid="+cid+"&ct="+ct+"&where="+where+"&sname="+sname+"&mt=" + new Date().getTime();

			window.open(url);
			//getTranDetail(url, "d-result");
		
	}
	
	function getpackage(check){
		service = frevenue.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			frevenue.selservice.focus();
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
					<td width="244" align="left" class="formtitle"><b>REVENUE REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="frevenue" onSubmit="showReport(); return false;">
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
								<td align="left"><input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport();" /></td>
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