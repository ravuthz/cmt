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
	$month = array(1=>"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
//php print date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
	
	function PreviewReport(){
		frm = fcashcollection.frm.options[fcashcollection.frm.selectedIndex].value;
		tom = fcashcollection.tom.options[fcashcollection.tom.selectedIndex].value;
		
		ua = fcashcollection.ua.value;
		nu = fcashcollection.nu.value;
		
		serviceid = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].value;
		servicename = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].text;
		
		
		where = "0 ";
		for(i=0; i< fcashcollection.package.length; i++){
			if(fcashcollection.package[i].checked == true){
				where += ", " + fcashcollection.pack[i].value;
				}
		}
		
		cu = "'0' ";
		for(i=0; i< fcashcollection.vip.length; i++){
			if(fcashcollection.vip[i].checked == true){
				cu += ", " + fcashcollection.vip[i].value;
				}
		}
		
			
			// Invoice summary ===================
			url1 = "./report/DebtStatus.php?frm="+frm+"&tom="+tom+"&ua="+ua+"&nu="+nu+"&cu="+cu+"&servicename="+servicename+"&serviceid="+serviceid+"&where="+where+"&mt="+ new Date().getTime();
			window.open(url1);
	}
	
	function getpackage(check){
		service = fcashcollection.serviceid.value;		
		if(service == ""){
			alert("Please select Service");
			fcashcollection.serviceid.focus();
			return;
		}else{
			url = "./php/ajax_city.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>Debt Status...</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fcashcollection" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
						<tr>
								<td align="left">Service:</td>
								<td align="left">
									<select name="serviceid" onChange="getpackage(0);">
										<option value="0">All services</option>
										<option value="2">Telephone</option>
										<option value="4">Leased line</option>
										<option value="5">ISP </option>
									</select>
								</td>
								<td align="left">Amount Unpaid >= </td>
								<td align="left"><input type="text" name="ua" class="boxenabled" size="15" value="0" /></td>
								<td align="left">Number INV >=</td>
								<td align="left"><input type="text" name="nu" class="boxenabled" size="15" value="0" /></td>
							</tr>
							<tr>
								<td align="left">From month:</td>
								<td align="left">
									<select name="frm" style="font-family:'Courier New', Courier, monospace">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".FormatDate($BillEndDate, 10)."'>".FormatDate($BillEndDate, 10)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>
								<td align="left">To month:</td>
								<td align="left">
									<select name="tom" style="font-family:'Courier New', Courier, monospace">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".FormatDate($BillEndDate, 10)."'>".FormatDate($BillEndDate, 10)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>
								
								<td>Customer Type :</td>
								<td>
									<input type="checkbox" name="vip" value="'CUS'"  />Normal
									<input type="checkbox" name="vip" value="'VIP'"  />VIP
								</td>
							</tr>	
							
							<tr>
								<td colspan="6">
									<div id="d-package"></div>
								</td>
							</tr>	
							<tr>							
								<td colspan="6" align="center">
									
									<input type="button" name="btnPreview" value="Preview Report" onclick="PreviewReport()" />
								</td>
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