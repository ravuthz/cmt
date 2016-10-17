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
		st = fcashier.st.value;
		et = fcashier.et.value;
		service = fcashier.selservice.options[fcashier.selservice.selectedIndex].text;		
	
		tid = "0 ";
			for(i=0; i< fcashier.LocName.length; i++){
				if(fcashier.LocName[i].checked == true)
					tid += ", " + fcashier.lid[i].value;
			}
			
			
		where = "0 ";
			for(i=0; i< fcashier.package.length; i++){
				if(fcashier.package[i].checked == true)
					where += ", " + fcashier.pack[i].value;
			}
			
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			//document.getElementById("d-result").innerHTML = loading;
			url = "./report/cashier.php?&tid="+tid+"&st="+st+"&et="+et+"&service="+service+"&where="+where+"&mt=" + new Date().getTime();

			window.open(url);
			//getTranDetail(url, "d-result");
		
	}
	function getpackage(check){
		service = fcashier.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			fcashier.selservice.focus();
			return;
		}else{
			url = "./php/ajax_package.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(url, "d-package");
		}
	}
	
	function getcity(check){	
	
			urlCity = "./php/ajax_location_city.php?ck="+check+"&mt="+new Date().getTime();;
			getTranDetail(urlCity, "d-location");		
	}
	
</script>
<body onLoad="getcity(0);">
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>CASHIER REPORT</b></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fcashier" onSubmit="return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frevenue|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
							</tr>
							<tr>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?frevenue|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
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
                            	<td colspan="2">
                                	<div id="d-location"></div>
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
</body>