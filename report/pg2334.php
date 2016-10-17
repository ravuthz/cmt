<?php
	require_once("./common/functions.php");
	
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<!--<script language="JavaScript" src="./javascript/ajax_getreport.js"></script>-->
<script language="javascript" type="text/javascript">
	
	function showReport(){
		
			cid = debtor.selCycle.options[debtor.selCycle.selectedIndex].value;			
			url = "./php/Count_Monthly.php?&cid="+cid;
			window.open(url);
	}
	
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>COUNT MONTHLY</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="debtor">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="center">Cycle date:</td>	
								
							</tr>
							
							<tr>
								<td align="center">
									<select name="selCycle" style="width:120px">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".FormatDate($BillEndDate, 5)."'>".FormatDate($BillEndDate, 5)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>
								
								
							</tr>
							<tr>								
								<td align="center" >
									<input type="button" name="btnSubmit" value="View report" onClick="showReport();" />
									
								</td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td align="left" valign="top">
		</td>
	</tr>		
	<tr><td colspan="2">&nbsp;</td></tr>				
</table>	
</body>