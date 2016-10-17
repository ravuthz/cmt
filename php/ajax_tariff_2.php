<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");	
	
	$bid = $_GET['bid'];
	$bname = $_GET['bname'];
	$div = $_GET['div'];	
				
	$sql = "  SELECT ttcbd.FromDuration, ttcbd.ToDuration, ttcbd.Unit 
						FROM tblTarChargeBlockDetail ttcbd 
						WHERE ttcbd.BlockID =".$bid."
						ORDER BY 1
					 ";
	
	$retOut = '<table border="0" cellpadding="2" cellspacing="0" align="left" width="100%">
							<tr>
								<td>
									<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
										<tr>
											<td align="left" class="formtitle"><b>'.$bname.'</b></td>
											<td align="right">[<a href="#" onClick="hide(\''.$div.'\');">Hide</a>]</td>
										</tr> 
										<tr>
											<td colspan="2">
												<table border="1" cellpadding="3" cellspacing="0" align="center" width="100%" height="100%" id="audit3" class="sortable" style="border-collapse:collapse" bordercolor="#aaaaaa">
													<thead>																	
														<th align="center">No</th>								
														<th align="center">From duration</th>
														<th align="center">To duration</th>
														<th align="center">Unit</th>																																							
													</thead>
													<tbody>';
													
													if($que = $mydb->sql_query($sql)){														
														$iLoop = 0;
														while($result1 = $mydb->sql_fetchrow($que)){
															$FromDuration = $result1['FromDuration'];
															$ToDuration = $result1['ToDuration'];
															if(floatval($ToDuration) == 0)
																$stToDuration = "unlimited";
															else
																$stToDuration = $ToDuration;
															$Unit = $result1['Unit'];
															
															$iLoop ++;
															if(($iLoop % 2) == 0)											
																$style = "row1";
															else
																$style = "row2";
															$retOut .= '<tr>
																						<td align="left" class="'.$style.'">'.$iLoop.'</td>
																						<td align="left" class="'.$style.'">'.$FromDuration.'</td>
																						<td align="left" class="'.$style.'">'.$stToDuration.'</td>
																						<td align="left" class="'.$style.'">'.$Unit.'</td>																					
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
																	<div id="d1-1-1'.$bid.'"></div>
																</td>
															</tr>
														</table>
										';
										}
							print $retOut;
										
?>