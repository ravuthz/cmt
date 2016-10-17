<?php
	require_once("./common/functions.php");
	require_once("./common/agent.php");

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
	
	function Invoice(){
		
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			mininv = revenuesummary.txtmininv.value;
			minunp = revenuesummary.txtminunp.value;

			where = "0 ";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true)
					where += ", " + revenuesummary.pack[i].value;
			}
			
			tid = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tid += ", " + revenuesummary.lid[i].value;
			}
			
			msnv = "0 ";
			for(i=0; i< revenuesummary.msn.length; i++){
				if(revenuesummary.msn[i].checked == true)
					msnv += ", " + revenuesummary.msnID[i].value;
			}
			
			invt = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].value;
			
			url = "http://web.billing.cmt:8080/bigisp/InvoicePDF/PrintingInvoiceMessenger.aspx?MessengerID="+msnv+"&GrpSID=1&ServiceName=Internet&InvoiceTypeID="+invt+"&BillEndDate="+cid+"&MinInvoice="+mininv+"&MinUnpaid="+minunp+"&IsGroupInvoice=1&CallType='LDC','IDD'&Pex="+where+"&LocID="+tid;

			window.open(url);	
		
	}
	
	function Detail(){
			cid = revenuesummary.selCycle.options[revenuesummary.selCycle.selectedIndex].value;
			mininv = revenuesummary.txtmininv.value;
			minunp = revenuesummary.txtminunp.value;

			where = "0 ";
			for(i=0; i< revenuesummary.package.length; i++){
				if(revenuesummary.package[i].checked == true)
					where += ", " + revenuesummary.pack[i].value;
			}
			
			tid = "0 ";
			for(i=0; i< revenuesummary.LocName.length; i++){
				if(revenuesummary.LocName[i].checked == true)
					tid += ", " + revenuesummary.lid[i].value;
			}
			
			msnv = "0 ";
			for(i=0; i< revenuesummary.msn.length; i++){
				if(revenuesummary.msn[i].checked == true)
					msnv += ", " + revenuesummary.msnID[i].value;
			}
			
			invt = revenuesummary.invoicetype.options[revenuesummary.invoicetype.selectedIndex].value;
			
			url = "http://web.billing.cmt:8080/bigisp/InvoicePDF/PrintingDetailByMessenger.aspx?MessengerID="+msnv+"&GrpSID=1&ServiceName=Internet&InvoiceTypeID="+invt+"&BillEndDate="+cid+"&MinInvoice="+mininv+"&MinUnpaid="+minunp+"&IsGroupInvoice=1&CallType='LDC','IDD'&Pex="+where+"&LocID="+tid;

			window.open(url);	
		
	}


	
	function getpackage(check){

			for(i=0; i< revenuesummary.package.length; i++){
				revenuesummary.package[i].checked = check;
			}
			

	}
	
	function getmsn(check){

			for(i=0; i< revenuesummary.msn.length; i++){
				revenuesummary.msn[i].checked = check;
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
					<td width="287" align="left" class="formtitle"><b>Printing Bills</b></td>
					<td width="6" align="right"></td>
				</tr> 
				<tr>
					<td colspan="2">
						<form name="revenuesummary">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">Cycle date:</td>	
								<td align="left">Invoice Type:</td>		
																						
							</tr>
							
							<tr>
								<td align="left">
									<select name="selCycle" style="width:200px">
										<?php
											$sql = "SELECT DISTINCT BillEndDate FROM tblSysBillRUnCycleInfo WHERE BillProcessed = 1 ORDER BY BillEndDate DESC";
											if($que = $mydb->sql_query($sql)){
												while($result = $mydb->sql_fetchrow($que)){
													$BillEndDate = $result['BillEndDate'];
													print "<option value='".FormatDate($BillEndDate, 10)."'>".FormatDate($BillEndDate, 2)."</option>";
												}
											}
											$mydb->sql_freeresult($que);
										?>
									</select>
								</td>
								
								<td align="left">
                                		
										<select name="invoicetype"  style="width:200px">
										<option value="1">Cycle bill</option>
										<option value="2">Demand bill</option>
										<option value="3">Other bill</option>
									</select>
							  </td>
												
							</tr>		
                            			
                            <tr>
								<td align="left">Mininum Invoice Amount:</td>	
								<td align="left">Minimum Unpaid Amount:</td>		
																						
							</tr>
							
							<tr>
								<td align="left">
									<input type="text" name="txtmininv" value="0.01" />
								</td>
								
								<td align="left">
                                	<input type="text" name="txtminunp" value="0.0" />
							  </td>
												
							</tr>					
                            
							<tr>
								<td colspan="2">
									<div id="d-location"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="d-package">
                                    	<?php

											$ck = 0;
											//$cpe = $_GET['cpe'];		
											
											$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
											$retOut .= "<tr><td colspan=5>Package Exception:  <a href='javascript:getpackage(1)'>Check all</a> | <a href='javascript:getpackage(0)'>Uncheck all</a></td></tr>";
											$retOut .= "<tr>";			
											
											$sql = "SELECT PackageID, TarName 
															FROM tblTarPackage WHERE ";
											
											$sql .= "ServiceID in(1, 3, 8) ";
											$sql .= "	ORDER BY ServiceID,TarName";								
											
											if($que = $mydb->sql_query($sql)){				
												$iLoop = 0;
												
												while($result = $mydb->sql_fetchrow($que)){																															
													$PackageID = $result['PackageID'];
													$TarName = $result['TarName'];										
													
													if(($iLoop % 4) == 0)											
														$retOut .= "</tr><tr>";																			
													$retOut .= '<td align="left"><input type="checkbox" name="package" value="1" ';
														if(intval($ck) ==1) $retOut .= "checked"; 
													$retOut .= ' /><font size="-2">'.$TarName;
													$retOut .= '</font><input type="hidden" name="pack" value="'.$PackageID.'" />'.'</td>';						
													$iLoop++;
												}		
											}
											$mydb->sql_freeresult();		
												$retOut .= "</tr>";
												$retOut .= '</table>';
												
											print $retOut;	
										?>
                                    </div>
								</td>
							</tr>							
                            <tr>
								<td colspan="2">
									<div id="d-messenger">
                                    	<?php


											//$cpe = $_GET['cpe'];		

											$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
											$retOut .= "<tr><td colspan=5>Messenger List:<a href='javascript:getmsn(1)'>Check all</a> | <a href='javascript:getmsn(0)'>Uncheck all</a> </td></tr>";
											$retOut .= "<tr>";			
											
											$sql = "select MessengerID, Name
														from tlkpMessenger where statusID=1";							
											
											if($que = $mydb->sql_query($sql)){				
												$iLoop = 0;
												
												while($result = $mydb->sql_fetchrow($que)){																															
													$MessengerID = $result['MessengerID'];
													$MsnName = $result['Name'];										
													
													if(($iLoop % 4) == 0)											
														$retOut .= "</tr><tr>";																			
													$retOut .= '<td align="left"><input type="checkbox" name="msn" value="1" ';
														if($iLoop == 0) $retOut .= "checked"; 
													$retOut .= ' /><font size="-2">'.$MsnName;
													$retOut .= '</font><input type="hidden" name="msnID" value="'.$MessengerID.'" />'.'</td>';						
													$iLoop++;
												}		
											}
											$mydb->sql_freeresult();		
												$retOut .= "</tr>";
												$retOut .= '</table>';
												
											print $retOut;	
										?>
                                    </div>
								</td>
							</tr>							
							<tr>								
								<td align="center" colspan="3">
									<input type="button" name="btnInvoice" value="Show Invoice" onClick="Invoice();" />
									<input type="button" name="btnDetail" value="Show Detail" onClick="Detail();" />
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