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
<script language="javascript" type="text/javascript" src="./javascript/ajax_gettransaction.js"></script>
<script language="javascript" type="text/javascript">
function showlevel2(div, pid, pname){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById(div).innerHTML = loading;
		url = "./php/ajax_tariff_1.php?div="+div+"&pid="+pid+"&pname="+pname+"&mt=" + new Date().getTime();
		getTranDetail(url, div);
	}
	function hide(div){
		document.getElementById(div).innerHTML = "";
}

function showlevel3(div, bid, bname){
		var loading;
loading = "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='./images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>";
		document.getElementById(div).innerHTML = loading;
		url = "./php/ajax_tariff_2.php?div="+div+"&bid="+bid+"&bname="+bname+"&mt=" + new Date().getTime();
		getTranDetail(url, div);
	}
	function hide(div){
		document.getElementById(div).innerHTML = "";
	}	
</script>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>		
		<td valign="top" align="left">
			<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
				<tr>
					<td align="left" class="formtitle"><b>PACKAGE LIST REPORT</b></td>
					<td align="right">
						<form name="fpackage" action="./" method="post">
							<table border="0" cellpadding="0" cellspacing="0" align="right">
									<tr>
										<td>Select service:
											<select name="serviceid" onchange="fpackage.submit();">
											<?php
												$sql = "SELECT ServiceID, ServiceName FROM tlkpService WHERE ServiceTypeID = 1 ORDER BY 2";
												if($que = $mydb->sql_query($sql)){
													while($result = $mydb->sql_fetchrow($que)){
														$dbserviceid = $result['ServiceID'];
														$ServiceName = $result['ServiceName'];
														if($dbserviceid == $serviceid)
															$sel = "selected";
														else
															$sel = "";
														print "<option value='".$dbserviceid."' $sel>".$ServiceName."</option>";
													}
												}
											?>
											</select>
											<input type="hidden" name="pg" value="202" />
											<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />	
										</td>										
									</tr>
								</table>
						</form>
					</td>
				</tr> 
				<tr>
					<td colspan="2">
						<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
							<thead>	
								<th align="center">No</th>															
								<th align="center">Package</th>
								<th align="center">Service</th>
								<th align="center">Monthly</th>
								<th align="center">Registration</th>
								<th align="center">Installation</th>	
								<th align="center">CPE cost</th>
								<th align="center">ISDN cost</th>
								<th align="center">SP cost</th>																						
							</thead>
							<tbody>
								<?php
										$sql = "select t.PackageID, t.TarName, s.ServiceName, t.RegistrationFee, t.ConfigurationFee, 
																	t.CPEFee, t.CycleFee, t.ISDNFee, t.SpecialNumber
														from tblTarPackage t, tlkpService s
														where t.Status = 1 and t.ServiceID = s.ServiceID ";
										if(isset($serviceid))
											$sql .= " and s.ServiceID = ".$serviceid;
										$sql .= " order by s.ServiceName, t.TarName";	
									if($que = $mydb->sql_query($sql)){
										while($result = $mydb->sql_fetchrow()){																
											$PackageID = $result['PackageID'];			
											$TarName = $result['TarName'];			
											$linkTar = "<a href='javascript:showlevel2(\"d1\", ".$PackageID.", \"".$TarName."\");'>".$TarName."</a>";													
											$ServiceName = $result['ServiceName'];
											$RegistrationFee = $result['RegistrationFee'];											
											$ConfigurationFee = $result['ConfigurationFee'];																
											$CPEFee = $result['CPEFee'];
											$CycleFee = $result['CycleFee'];
											$ISDNFee = $result['ISDNFee'];
											$SpecialNumber = $result['SpecialNumber'];
											$iLoop++;															
											if(($iLoop % 2) == 0)
												$style = "row1";
											else
												$style = "row2";
											print '<tr>';	
											print '<td class="'.$style.'" align="left">'.$iLoop.'</td>';
											print '<td class="'.$style.'" align="left">'.$linkTar.'</td>';
											print '<td class="'.$style.'" align="left">'.$ServiceName.'</td>';			
											print '<td class="'.$style.'" align="right">'.FormatCurrency($CycleFee).'</td>';								
											print '<td class="'.$style.'" align="right">'.FormatCurrency($RegistrationFee).'</td>';											
											print '<td class="'.$style.'" align="right">'.FormatCurrency($ConfigurationFee).'</td>';
											print '<td class="'.$style.'" align="right">'.FormatCurrency($CPEFee).'</td>';
											print '<td class="'.$style.'" align="right">'.FormatCurrency($ISDNFee).'</td>';
											print '<td class="'.$style.'" align="right">'.FormatCurrency($SpecialNumber).'</td>';
											print '</tr>';
										}
									}
									$mydb->sql_freeresult();	
								?>
							</tbody>												
						</table>						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="d1">
			</div>
		</td>
	</tr>						
</table>