<?php
	require_once("./common/functions.php");
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
	
	function showReport(){
	
		if(usage.selservice.selectedIndex < 1 || usage.selPeak.selectedIndex < 1)
			alert("Please select information in combo box");
		else{
			
			w = usage.selPeak.options[usage.selPeak.selectedIndex].value;
			service = usage.selservice.options[usage.selservice.selectedIndex].value;
			
			if(usage.selservice.selectedIndex>1)
			{			
				where = "0 ";
				for(i=0; i< usage.package.length; i++){
					if(usage.package[i].checked == true)
						where += ", " + usage.pack[i].value;
				}
				url = "./php/Extra_Online_Usage_View.php?&w="+w+"&where="+where+"&service="+service;
			}
			else
			{
				url = "./php/Extra_Online_Usage_Limit_View.php?&w="+w+"&service="+service;
			}
			
			
			window.open(url);	
		}
	}
	
	
	function getpackage(check,fi){
		service = usage.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			usage.selservice.focus();
			return;
		}else{
			url = "./php/ajax_usage_package.php?serviceid="+service+"&ck="+check+"&fi="+fi+"&mt="+new Date().getTime();
			getTranDetail(url, "d-package");
		}
	}
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>ONLINE USAGE </b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="usage">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="center">Percent to be Peak:</td>		
								<td align="center">Service Type:</td>		
							</tr>
							<tr>
								<td align="left">
										<select name="selPeak" style="width:200px">
											<option value='Select Peak'>Select Peak</option>
												<?php
													$sql = "SELECT DISTINCT peak FROM tblpeak  ORDER BY peak DESC";
													if($que = $mydb->sql_query($sql)){
														while($result = $mydb->sql_fetchrow($que)){
															$peak = $result['peak'];
															print "<option value='".$peak."'>".$peak."</option>";
														}
													}
													$mydb->sql_freeresult($que);
												?>
										</select>
									</td>							
									<td align="left">
										<select name="selservice" style="width:200px" onChange="getpackage(1,1);" id="selservice">
											<option value='0'>Select service</option>
												<option value="11">CreditLimit</option>
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
								<td align="center" colspan="2">
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