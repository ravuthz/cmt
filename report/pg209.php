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
<script language="javascript" type="text/javascript">
	function showReport(){
		st = fNewdAccount.st.value;
		et = fNewdAccount.et.value;
		c = fNewdAccount.Salesman.options[fNewdAccount.Salesman.selectedIndex].value;
		if(fNewdAccount.inactive.checked)
			ina = 1;
		else
			ina = 0;
		if(fNewdAccount.active.checked)
			ac = 1;
		else
			ac = 0;
		if(fNewdAccount.bar.checked)
			ba = 1;
		else
			ba = 0;
		if(fNewdAccount.closed.checked)
			cl = 1;
		else
			cl = 0;
		where = "0 ";
			for(i=0; i< fNewdAccount.package.length; i++){
				if(fNewdAccount.package[i].checked == true)
					where += ", " + fNewdAccount.pack[i].value;
			}
			
		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			fNewdAccount.st.focus();
			return;
		}else{ 
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-newaccount").innerHTML = loading;
			url = "./php/ajax_newaccount.php?st="+st+"&et="+et+"&c="+c+"&ina="+ina+"&ac="+ac+"&ba="+ba+"&cl="+cl+"&where="+where+"&mt="+ new Date().getDate();
			getTranDetail(url, "d-newaccount");
		}
	}
	function getpackage(check){
		service = fNewdAccount.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			fNewdAccount.selservice.focus();
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
					<td align="left" class="formtitle"><b>NEW ACCOUNTS REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fNewdAccount" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="1" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fBardAccount|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="2" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fBardAccount|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>	
							<tr>
								<td align="left">Salesman:</td>
								<td align="left">
									<select name="Salesman" class="boxenabled" tabindex="3">
										<option value="0" selected="selected">All</option>	
										<?php
											$sql = "SELECT SalesmanID, Salutation, Name from tlkpSalesMan order by Name";
											// sql 2005
											
											$que = $mydb->sql_query($sql);									
											if($que){
												while($rst = $mydb->sql_fetchrow($que)){	
													$SalesmanID = $rst['SalesmanID'];
													$Salutation = $rst['Salutation'];
													$Name = $rst['Name'];
													$salesman = $Salutation." ".$Name;
													print "<option value='".$SalesmanID."'>".$salesman."</option>";
												}
											}
											$mydb->sql_freeresult();
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
								<td colspan="2" align="left">
									<input type="checkbox" name="inactive" value="1" checked="checked" />Inactive&nbsp;
									<input type="checkbox" name="active" value="1" checked="checked" />Active&nbsp;
									<input type="checkbox" name="bar" value="1" checked="checked" />Barred&nbsp;
									<input type="checkbox" name="closed" value="1" checked="checked" />Closed&nbsp;
								</td>
							</tr>						
							<tr>
								<td>&nbsp;</td>
								<td align="left"><input type="button" name="btnSubmit" value="View report" tabindex="4" onclick="showReport();" /></td>
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
		<div id="d-newaccount">
		</div>
	</td></tr>
</table>