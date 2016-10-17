<?php
	require_once("./common/functions.php");
?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/ajax_gettransaction.js"></script>
<!--<script language="JavaScript" src="./javascript/ajax_getreport.js"></script>-->
<script language="javascript" type="text/javascript">
	
	function showRequest(){

			t = request.selType.options[request.selType.selectedIndex].value;		
			url = "./report/RequestForm.php?&type="+t;
			window.open(url);	
	}
	
</script>
<body>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="287" align="left" class="formtitle"><b>Request Form</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="request">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="center">Customer Type:</td>	
							</tr>
							<tr>
								 <td align="center">
									<select name="selType" style="width:100px">
										<option value="PHONE">PHONE</option>
										<option value="ISP">ISP</option>																																			
										<option value="Lease">Lease Line</option>
									</select>
								</td>
							</tr>
							<tr>								
								<td align="center" colspan="3">
									<input type="button" name="btnSubmit" value="View" onClick="showRequest();" />      
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