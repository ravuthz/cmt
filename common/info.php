
<?php
	/**
	 *	@Project: Project Management	
	 *	@package: pm/functions/
	 *	@File:		database.php	
	 *	
	 *	Class for connect, manipulate data from MySQL database	 
	 *
	 */
 include_once("configs.php");
 class info_box{ 		
 		//
 		//	Create schema for other information alert mode.
		//		
		function schema($imageURL, $strMessage, $strDetail = "", $color= ""){
		global $SERVERROOT;
		$imageRoot = $SERVERROOT."images/icon";
			
			$strOut = "
									<script language=\"Javascript\">
										function showDetail(){
											
											dvDetail = document.getElementById(\"dvdetail\");
											//alert(dvDetail.style.display);
											if(dvDetail.style.display == \"none\")
												dvDetail.style.display = \"block\";
											else
												dvDetail.style.display = \"none\";
										}
									</script>
									<table cellpadding=5 cellspacing=0 bgColor=\"#ffffff\" width=\"98%\">
									<tr>
										<td style=\"border:1px dotted #000000\">
											<table border=0 cellpadding=3 cellspacing=0>
												<tr>
													<td valign=top align=left>
														<img src=\"".$imageRoot."/$imageURL\" border=0 alt=''>
													</td>
													<td valign=top align=left>
														<font color='$color'>$strMessage</font>
													</td>
												</tr>";
									if($strDetail != ""){
										$strOut .= "<tr><td>&nbsp;</td><td align=\"left\">[<a href=\"javascript:showDetail()\">Show detail</a>]</td></tr>
																<tr><td colspan=\"2\" align=\"left\">	
																	<div id=\"dvdetail\" style=\"display:none\">
																		$strDetail;
																	</div>
																</td></tr>
															 "; 
									}
						$strOut .= "</table>
										</td>
									</tr>
								</table>";
			return $strOut;
		}
		//
		//	Alert warning to screen
		//
		function warning($strMessage){
			return $this->schema("warning.gif", $strMessage);
		}
		
		//
		//	Alert information to screen
		//
		function info($strMessage){
			return $this->schema("info.gif", $strMessage);
		}
		
		//
		//	Alert Error to screen
		//
		function error($strMessage, $detail=""){
			return $this->schema("error.gif", $strMessage, $detail);
		}		
 }
?>
