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
	function showReport(){
		et = fcashcollection.et.options[fcashcollection.et.selectedIndex].value;
		serviceid = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].value;
		servicename = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].text;
			var loading;
	loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
			document.getElementById("d-result").innerHTML = loading;					
			
		where = "0 ";
		ln = " vc";
		for(i=0; i< fcashcollection.package.length; i++){
			if(fcashcollection.package[i].checked == true){
				where += ", " + fcashcollection.pack[i].value;
				}
		}
			
						
			// Invoice summary ===================
			url1 = "./php/ajax_cashcollection.php?code="+et+"&servicename="+servicename+"&serviceid="+serviceid+"&where="+where+"&mt="+ new Date().getTime();
			getTranDetail(url1, "d-result");
			//window.open(url1);
			
			
	
	}
	function PreviewReport(){
		et = fcashcollection.et.options[fcashcollection.et.selectedIndex].value;
		serviceid = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].value;
		servicename = fcashcollection.serviceid.options[fcashcollection.serviceid.selectedIndex].text;
		
		
		where = "0 ";
		ln = " vc";
		for(i=0; i< fcashcollection.package.length; i++){
			if(fcashcollection.package[i].checked == true){
				where += ", " + fcashcollection.pack[i].value;
				}
		}
		
			
			// Invoice summary ===================
			url1 = "./report/cashcollection.php?code="+et+"&servicename="+servicename+"&serviceid="+serviceid+"&where="+where+"&mt="+ new Date().getTime();
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
					<td width="287" align="left" class="formtitle"><b>Cash Collection by month</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="fcashcollection" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Select month:</td>
								<td align="left">
									<select name="et" style="font-family:'Courier New', Courier, monospace">
										<?php
											$sql = "select distinct left(convert(varchar,paymentdate,120),7) Pd
													from tblCashPayment
													where paymentdate is not null
													order by Pd desc";
											if($que = $mydb->sql_query($sql)){
												while ($result = $mydb->sql_fetchrow($que)){
													$Pd = $result['Pd'];
													
													print "<option value='".$Pd."'>".$Pd."</option>";
												}
											}
										?>
									</select>
								</td>
								<td></td>
							</tr>	
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
								<td></td>
							</tr>
							<tr>
								<td colspan="3">
									<div id="d-package"></div>
								</td>
							</tr>	
							<tr>							
								<td colspan="3" align="center">
									<input type="submit" name="btnSubmit" value="Collection Detail" />
									<input type="button" name="btnPreview" value="Collection Summary" onclick="PreviewReport()" />
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