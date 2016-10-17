<?php
	require_once("./common/agent.php");	
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
	function showReport(index){
		st = foutgoing.st.value;
		et = foutgoing.et.value;
		// Package
		pwhere = "0 ";
		for(i=0; i< foutgoing.package.length; i++){
			if(foutgoing.package[i].checked == true)
				pwhere += ", " + foutgoing.pack[i].value;
		}
		
		// band
		bwhere = "0 ";
		for(i=0; i< foutgoing.bandid.length; i++){
			if(foutgoing.bandid[i].checked == true)
				bwhere += ", " + foutgoing.band[i].value;
		}
		if((st == "") || (et == "")){
			alert("Please enter from date and to date to view report");
			foutgoing.st.focus();
			return;
		}else{ 
			if(index == 1){
				var loading;
		loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
				document.getElementById("d-result").innerHTML = loading;
				url = "./php/ajax_outgoingcall.php?st="+st+"&et="+et+"&pwhere="+pwhere+"&bwhere="+bwhere+"&mt="+new Date().getTime();			
				getTranDetail(url, "d-result");
			}else if(index == 2){
				url = "./report/outgoingcall.php?st="+st+"&et="+et+"&pwhere="+pwhere+"&bwhere="+bwhere+"&mt="+new Date().getTime();
				window.open(url);
			}else if(index == 3){
				url = "./export/outgoingreport.php?st="+st+"&et="+et+"&pwhere="+pwhere+"&bwhere="+bwhere+"&mt="+new Date().getTime();
				window.open(url);
			}
		}
	}
		
	function getpackage(check){
		url = "./php/ajax_package.php?serviceid=2&ck="+check;	
		getTranDetail(url, "d-package");
	}
	function getband(check){
		url = "./php/get_band.php?ck="+check;	
		getTranDetail(url, "d-band");
	}
	
</script>

<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left">
				<tr>
					<td width="244" align="left" class="formtitle"><b>OUTGOING MINUTE CALLS REPORT DAILY</b></td>
				</tr> 
				<tr>
					<td colspan="2" valign="top">
						<form name="foutgoing" onSubmit="showReport(); return false;">
						<table border="0" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<tr>
								<td align="left">From date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="st" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?foutgoing|st', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>
								<td align="left">To date:</td>
								<td align="left">
									<input type="text" tabindex="3" name="et" class="boxenabled" size="27" maxlength="30" value="<?php print date("Y-m-d"); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?foutgoing|et', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>
								</td>														
							</tr>		
							<tr>
								<td colspan="4" align="left">Package:</td>
							</tr>					
							<tr>								
								<td align="left" colspan="4">
									<div id="d-package">
										<?php
											$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
											$retOut .= "<tr><td colspan=4><a href='javascript:getpackage(1)'>Check all</a> | <a href='javascript:getpackage(0)'>Uncheck all</a></td></tr>";
											$retOut .= "<tr>";			
											$sql = "SELECT PackageID, TarName FROM tblTarPackage WHERE ServiceID=2 ORDER BY TarName";								
											
											if($que = $mydb->sql_query($sql)){				
												$iLoop = 0;
												
												while($result = $mydb->sql_fetchrow($que)){																															
													$PackageID = $result['PackageID'];
													$TarName = $result['TarName'];										
													$iLoop++;
													if(($iLoop % 4) == 0)											
														$retOut .= "</tr><tr>";																			
													$retOut .= '<td><font size="-2">
																				<input type="checkbox" name="package" value="1" checked />'.$TarName.'
																				<input type="hidden" name="pack" value="'.$PackageID.'" />
																			</font></td>';									
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
								<td align="left" colspan="4">Band name:</td>
							</tr>
							<tr>
								<td align="left" colspan="4">
									<div id="d-band">
										<?php
											$retOut = '<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">';				
											$retOut .= "<tr><td colspan=4><a href='javascript:getband(1)'>Check all</a> | <a href='javascript:getband(0)'>Uncheck all</a></td></tr>";
											$retOut .= "<tr>";			
											$sql = "SELECT DistanceID, BandName FROM tlkptarChargingBand ORDER BY BandName";								
											
											if($que = $mydb->sql_query($sql)){				
												$iLoop = 0;
												
												while($result = $mydb->sql_fetchrow($que)){																															
													$BandID = $result['DistanceID'];
													$BandName = $result['BandName'];										
													$iLoop++;
													if(($iLoop % 4) == 0)											
														$retOut .= "</tr><tr>";																			
													$retOut .= '<td><font size="-2">
																				<input type="checkbox" name="bandid" value="1" checked />'.$BandName.'
																				<input type="hidden" name="band" value="'.$BandID.'" />'.'</td>
																			</font></td>';									
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
								<td align="center" colspan="4">
									<input type="button" name="btnSubmit" value="View report" tabindex="4" onClick="showReport(1);" />
									<input type="button" name="btnpreview" value="Preview" tabindex="5" onClick="showReport(2);" />
									<input type="button" name="btnexport" value="Export" tabindex="6" onClick="showReport(3);" />
								</td>
							</tr>
						</table>						
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<div id="d-result"></div>
		</td>
	</tr>
</table>