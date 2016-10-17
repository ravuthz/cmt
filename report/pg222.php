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
	

	function showLocationAging(){
		if(revenuesummary.selservice.selectedIndex < 1)
			alert("Please select service");
		else{
			sid = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].value;
			st = revenuesummary.selservice.options[revenuesummary.selservice.selectedIndex].text;

				
			where = "0 ";
			ln = " vc";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true){
					where += ", " + revenuesummary.pack[i].value;
					
					}
			}
			
			
			url = "./php/ajax_aging_locaion.php?&sid="+sid+"&st="+st+"&w="+where;
			window.open(url);	
		}
	}

	
	
	
	function getpackage(check){
		service = revenuesummary.selservice.value;		
		if(service == ""){
			alert("Please select Service");
			revenuesummary.selservice.focus();
			return;
		}else{
			url = "./php/ajax_city.php?serviceid="+service+"&ck="+check+"&mt="+new Date().getTime();;
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
					<td width="219" align="left" class="formtitle"><b>LOCATION AGING REPORT </b></td>
					<td width="0" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="revenuesummary">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								
								<td align="left">Service:</td>		
																				
							</tr>
							
							<tr>
								
								<td align="left">
										<select name="selservice" style="width:200px" onChange="getpackage(0);" id="selservice">
											<option value='0'>Select service</option>
												<option value="2">Telephone</option>
												<option value="4">LeaseLine</option>
												<option value="1">Internet</option>
												
											<?php
									//$sql = "select ServiceID, ServiceName from tlkpService where ServiceTypeID = 1;";
//									// sql 2005
//									
//									$que = $mydb->sql_query($sql);									
//									if($que){
//										while($rst = $mydb->sql_fetchrow($que)){	
//											$ServiceID = $rst['ServiceID'];
//											$ServiceName = $rst['ServiceName'];
//											
//											print "<option value='".$ServiceID."'>".$ServiceName."</option>";
//										}
//									}
//									$mydb->sql_freeresult();
								?>
								
										</select>
							  </td>
													
							</tr>
							<tr>
								<td colspan="3">
									<div id="d-package"></div>
								</td>
							</tr>			
							<tr>								
								<td align="center" colspan="3">
									<input type="button" name="btnReLoc" value="View" onClick="showLocationAging();" />
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
</body>