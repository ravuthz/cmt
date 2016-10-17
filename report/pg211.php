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
		st = writeoffbaddebt.st.value;
		et = writeoffbaddebt.et.value;
		where = 0;
		if(writeoffbaddebt.selservice.selectedIndex <1){
			alert("Please select service");
		}else{
				if((st == "") || (et == "")){
				alert("Please enter from date and to date to view report");
				writeoffbaddebt.st.focus();
				return;
				}else{ 
					for(i=0; i< writeoffbaddebt.package.length; i++){
						if(writeoffbaddebt.package[i].checked == true)
							where += ", " + writeoffbaddebt.pack[i].value;
					}
					var loading;
			loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
					document.getElementById("d-writeoffbaddebt").innerHTML = loading;
					url = "./php/ajax_writeoffbaddebt.php?st="+st+"&et="+et+"&where="+where+"&mt="+new Date().getTime();
					getTranDetail(url, "d-writeoffbaddebt");
				}			
		}	
	}
	function getpackage(check){
		service = writeoffbaddebt.selservice.value;		
		
		if(service == ""){
			alert("Please select Service");
			writeoffbaddebt.selservice.focus();
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
					<td align="left" class="formtitle"><b>&nbsp;WRITE OFF BAD DEBT REPORT</b></td>
					<td align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="writeoffbaddebt" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left" nowrap="nowrap">From date:</td>
								<td align="left">
									<input type="text" tabindex="1" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?writeoffbaddebt|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0"></button>
											 &nbsp;(yyyy-mm-dd) 
								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="2" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?writeoffbaddebt|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0"></button>
											 &nbsp;(yyyy-mm-dd) 
								</td>
							</tr>	
							<tr>
								<td align="left" nowrap="nowrap">Service:</td>
								<td align="left" width="90%">
										<select name="selservice" style="width:200px" onChange="getpackage(1);">
											<option value='0'>Select service</option>
											<option value="2">Telephone</option>
											<option value="4">LeaseLine</option>
											<option value="5">ISP</option>
										</select>
							  </td>
							</tr>
							<tr>								
								<td align="left" colspan="2">
									<div id="d-package">
									</div>
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
		<div id="d-writeoffbaddebt">
		</div>
	</td></tr>
</table>