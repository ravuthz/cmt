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
			pid = billprocess.selpackage.options[billprocess.selpackage.selectedIndex].value;
			pt = billprocess.selpackage.options[billprocess.selpackage.selectedIndex].text;
			cid = billprocess.selCycle.options[billprocess.selCycle.selectedIndex].value;
			ct = billprocess.selCycle.options[billprocess.selCycle.selectedIndex].text;
			tid = billprocess.invoicetype.options[billprocess.invoicetype.selectedIndex].value;
			tt = billprocess.invoicetype.options[billprocess.invoicetype.selectedIndex].text;
			url = "./php/ajax_billprocess_detail.php?pid="+pid+"&pt="+pt+"&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt;
			window.open(url);					
	}
	function exportReport(){
		pid = billprocess.selpackage.options[billprocess.selpackage.selectedIndex].value;
		pt = billprocess.selpackage.options[billprocess.selpackage.selectedIndex].text;
		cid = billprocess.selCycle.options[billprocess.selCycle.selectedIndex].value;
		ct = billprocess.selCycle.options[billprocess.selCycle.selectedIndex].text;
		tid = billprocess.invoicetype.options[billprocess.invoicetype.selectedIndex].value;
		tt = billprocess.invoicetype.options[billprocess.invoicetype.selectedIndex].text;
		url = "./export/billprocess_detail.php?pid="+pid+"&pt="+pt+"&cid="+cid+"&ct="+ct+"&tid="+tid+"&tt="+tt;
		window.location = url;	
	}
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>INVOICE REPORT</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="billprocess" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Cycle date:</td>									
								<td align="left">
									<select name="selCycle" style="width:200px">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate";
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
								<td align="left">Package:</td>
								<td align="left">
									<div id="d-package">
										<select name="selpackage"  style="width:200px">
											<option value="0">All packages</option>
											<?php
											$sql = "SELECT PackageID, TarName FROM tblTarPackage WHERE ServiceID = 2 ORDER BY TarName";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$PackageID = $result['PackageID'];
													$TarName = $result['TarName'];
													print "<option value='".$PackageID."'>".$TarName."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
										</select>
									</div>
								</td>
							</tr>	
							<tr>
								<td align="left">Invoice type</td>
								<td align="left">
									<select name="invoicetype"  style="width:200px">
										<option value="1">Cycle bill</option>
										<option value="2">Demand bill</option>
										<option value="3">Other bill</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left">
									<input type="submit" name="btnSubmit" value="View report" />
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
