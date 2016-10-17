<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$pid = $_GET['pid'];
	$pname = $_GET['pname'];
	$div = $_GET['div'];	
				
	$sql = " select tt.TarID, tt.Rate,
							ttcb.BandName, ttgw.GateCode, tttb.TimeBandName, 
							tttb.TimeID, cb.BlockID, cb.BlockName
						from (tblTariff tt  
									left join (tblTarTimeBand tttb   
									left join tblTarPackage ttp  
											on tttb.PackageID=ttp.PackageID)  
											on tt.TimeID=tttb.TimeID)  
									left join tlkpTarGateWay ttgw  
											on tt.GateID=ttgw.GateID  
									left join tlkpTarChargingBand ttcb  
											on tt.DistanceID=ttcb.DistanceID 
									left join tlkpTarChargeBlock cb
											on tt.BlockID=cb.BlockID
						where tttb.PackageID=".$pid." and ttcb.Status='1'
						order by ttcb.BandName
					 ";
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td>
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle"><b>'.$pname.'</b></td>
											<td align="right">[<a href="#" onClick="hide(\''.$div.'\');">Hide</a>]</td>
										</tr> 
										<tr>
											<td colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
													<thead>																	
														<th align="center">No</th>								
														<th align="center">Distance </th>
														<th align="center">Getway</th>
														<th align="center">Timeband</th>																							
														<th align="center">Round up</th>																			
														<th align="center">Rate (cent)</th>																												
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){														
														$iLoop = 0;
														while($result1 = $mydb->sql_fetchrow($que)){
															$TarID = $result1['TarID'];
															$Rate = $result1['Rate'];
															$BandName = $result1['BandName'];
															$GateCode = $result1['GateCode'];
															$TimeBandName = $result1['TimeBandName'];
															$TimeID = $result1['TimeID'];
															$BlockID = $result1['BlockID'];
															$BlockName = $result1['BlockName'];
															$linkBlock = "<a href='javascript:showlevel3(\"d1-1-".$pid."\", ".$BlockID.", \"".$BlockName."\");'>".$BlockName."</a>";
															$iLoop ++;
															if(($iLoop % 2) == 0)											
																$style = "row1";
															else
																$style = "row2";
															$retOut .= '<tr>
																						<td align="left" class="'.$style.'">'.$iLoop.'</td>
																						<td align="left" class="'.$style.'">'.$BandName.'</td>
																						<td align="left" class="'.$style.'">'.$GateCode.'</td>
																						<td align="left" class="'.$style.'">'.$TimeBandName.'</td>
																						<td align="left" class="'.$style.'">'.$linkBlock.'</td>																						
																						<td align="right" class="'.$style.'">'.$Rate.'</td>
																					</tr>	
																					';																					
														} 
														$retOut .= '</tbody>																					
																				</table>
																			</td>
																		</tr>																		
																	</table>
																</td>
															</tr>
															<tr>
																<td>
																	<div id="d1-1-'.$pid.'"></div>
																</td>
															</tr>
														</table>
										';
										}
							print $retOut;
										
?>