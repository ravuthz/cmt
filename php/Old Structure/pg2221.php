<?php
	require_once("./common/functions.php");
	
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<!--<script language="JavaScript" src="./javascript/ajax_getreport.js"></script>-->
<script language="javascript" type="text/javascript">
	
	function showReport(){
		
			cid = debtor.selCycle.options[debtor.selCycle.selectedIndex].value;
			where = debtor.stl.value;		
			if (debtor.selService.options[debtor.selService.selectedIndex].value=="PHONE")
			{			
				url = "./php/paid_request.php?&cid="+cid+"&w="+where;
				window.open(url);	
			}
			else if (debtor.selService.options[debtor.selService.selectedIndex].value=="ISP")
			{			
				url = "./php/paid_request_isp.php?&cid="+cid+"&w="+where;
				window.open(url);	
			}
	}
	
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>LIST OF ACCOUNT REQUEST</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="debtor">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Cycle date:</td>	
								<td align="left">Service:</td>																
								<td align="left">Expire Date:</td>		
							</tr>
							
							<tr>
							
								<td align="left">
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
								
								 <td align="left">
									<select name="selService" style="width:100px">
										<option value="PHONE">PHONE</option>
										<option value="ISP">ISP</option>																																			
									</select>
								</td>
									
								<td align="left">
							<input type="text" tabindex="3" name="stl" class="boxenabled" size="17" maxlength="15" value="<?php print $st1; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
							<!--<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?f1|st', '', 'width=200,height=220,top=250,left=350');">
								<img src='../../../../wamp/www/wisebiller/report/./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
							</button>-->
							  </td>		
							  
							</tr>
							<tr>								
								<td align="center" colspan="3">
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