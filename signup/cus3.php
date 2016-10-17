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
?>
<script language="javascript">
	function checkIt(index){
		fsignupcust2.where.value = index;
		fsignupcust2.btnNext.disabled = false;		
	}
	
	function submitIt(){
		if(fsignupcust2.where.value == 2){
			fsignupcust2.pg.value = 5;			
		}else{
			fsignupcust2.pg.value = 8;
		}
		fsignupcust2.btnNext.disabled = true;
		fsignupcust2.submit();
	}
</script>

<br>
<table border="0" cellpadding="0" cellspacing="5" align="left" width="99%">
	<tr>
		<td valign="top" width="180">
			<fieldset>
				<legend align="center">Signup Customer Process</legend>
					<table border="0" cellpadding="5" cellspacing="0" align="left" width="100%" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
						<tr><td>&nbsp;</td></tr>			
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Customer profile
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Contact information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left" bgcolor="#ffffff">
								<b>Product information</b>
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Account information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Address information
						 	</td>		 
						</tr>
						<tr>
						 	<td style="border-top:1px solid" align="left">
								Information summary
						 	</td>		 
						</tr>		
					 </table>
			</fieldset>		
		</td>
		<td valign="top" width="600" align="left"> 
			<form name="fsignupcust2" method="post" action="./" onsubmit="submitIt(); return false;">
			 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="left" class="formtitle" height="18"><b>PRODUCT INFORMATION</b></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2" align="left">
										<tr>
											<td align="left">
												<?php
													$sql = "SELECT ServiceID, ServiceName from tlkpService where ServiceTypeID = 1 order by 1";									
													$que = $mydb->sql_query($sql);									
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){	
															$ServiceID = $rst['ServiceID'];
															$ServiceName = $rst['ServiceName'];											
															print "<input type='radio' name='ServiceID' onClick='checkIt(".$ServiceID.");' value='".$ServiceID."'>".$ServiceName."<br />";																						
														}
													}
													$mydb->sql_freeresult();
												?>
											</td>												
										</tr>
									</table>										
							 </td>
							 </tr>
						</table>
					</td>
				</tr>				
				<tr><td>&nbsp;</td></tr>
					<tr> 				  
					<td align="center">						
						<input type="button" tabindex="16" name="btnNext" value="Next >>" class="button" onclick="submitIt();" disabled="disabled" />						
						<input type="hidden" name="where" value="" />
					</td>
				 </tr>		   
			 </table>
					<input type="hidden" name="pg" id="pg" value="">
					<!--
					//
					//	Hidden fields
					//
					-->
					<input type="hidden" name="selCusSalutation" value="<?php print $selCusSalutation; ?>" />
					<input type="hidden" name="txtCustomerName" value="<?php print $txtCustomerName; ?>" />
					<input type="hidden" name="txtCusDOB" value="<?php print $txtCusDOB; ?>" />
					<input type="hidden" name="txtCusBus" value="<?php print $txtCusBus; ?>" />
					<input type="hidden" name="selCusDuplicateID" value="<?php print $selCusDuplicateID; ?>" />
					<input type="hidden" name="txtCusDuplicate" value="<?php print $txtCusDuplicate; ?>" />
					<input type="hidden" name="radExemption" value="<?php print $radExemption; ?>" />
					<input type="hidden" name="txtVATNumber" value="<?php print $txtVATNumber; ?>" />
					<input type="hidden" name="selCusNationality" value="<?php print $selCusNationality; ?>" />
					<input type="hidden" name="selCusOccupation" value="<?php print $selCusOccupation; ?>" />
					<input type="hidden" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" />
					<input type="hidden" name="txtCusEmail" value="<?php print $txtCusEmail; ?>" />
					<input type="hidden" name="txtCusAddress" value="<?php print $txtCusAddress; ?>" />
					<input type="hidden" name="selCusCountry" value="<?php print $selCusCountry; ?>" />
					<input type="hidden" name="selCusCity" value="<?php print $selCusCity; ?>" />
					<input type="hidden" name="selCusKhan" value="<?php print $selCusKhan; ?>" />
					<input type="hidden" name="selCusSangkat" value="<?php print $selCusSangkat; ?>" />
					
					<input type="hidden" name="selConSalutation" value="<?php print $selConSalutation; ?>" />
					<input type="hidden" name="txtContactName" value="<?php print $txtContactName; ?>" />
					<input type="hidden" name="txtConDOB" value="<?php print $txtConDOB; ?>" />
					<input type="hidden" name="selConDuplicateID" value="<?php print $selConDuplicateID; ?>" />
					<input type="hidden" name="selConNationality" value="<?php print $selConNationality; ?>" />
					<input type="hidden" name="selConOccupation" value="<?php print $selConOccupation; ?>" />
					<input type="hidden" name="txtConPhone" value="<?php print $txtConPhone; ?>" />
					<input type="hidden" name="txtConEmail" value="<?php print $txtConEmail; ?>" />
					<input type="hidden" name="txtConAddress" value="<?php print $txtConAddress; ?>" />
					<input type="hidden" name="selConCountry" value="<?php print $selConCountry; ?>" />
					<input type="hidden" name="selConCity" value="<?php print $selConCity; ?>" />
					<input type="hidden" name="selConKhan" value="<?php print $selConKhan; ?>" />
					<input type="hidden" name="selConSangkat" value="<?php print $selConSangkat; ?>" />
					
					<input type="hidden" name="selDesSalutation" value="<?php print $selDesSalutation; ?>" />
					<input type="hidden" name="txtDesignateName" value="<?php print $txtDesignateName; ?>" />
					<input type="hidden" name="txtDesDOB" value="<?php print $txtDesDOB; ?>" />
					<input type="hidden" name="selDesDuplicateID" value="<?php print $selDesDuplicateID; ?>" />
					<input type="hidden" name="selDesNationality" value="<?php print $selDesNationality; ?>" />
					<input type="hidden" name="selDesOccupation" value="<?php print $selDesOccupation; ?>" />
					<input type="hidden" name="txtDesPhone" value="<?php print $txtDesPhone; ?>" />
					<input type="hidden" name="txtDesEmail" value="<?php print $txtDesEmail; ?>" />
					<input type="hidden" name="txtDesAddress" value="<?php print $txtDesAddress; ?>" />
					<input type="hidden" name="selDesCountry" value="<?php print $selDesCountry; ?>" />
					<input type="hidden" name="selDesCity" value="<?php print $selDesCity; ?>" />
					<input type="hidden" name="selDesKhan" value="<?php print $selDesKhan; ?>" />
					<input type="hidden" name="selDesSangkat" value="<?php print $selDesSangkat; ?>" />
					
					<input type="hidden" name="selGuaSalutation" value="<?php print $selGuaSalutation; ?>" />
					<input type="hidden" name="txtGarrentorName" value="<?php print $txtGarrentorName; ?>" />
					<input type="hidden" name="txtGuaDOB" value="<?php print $txtGuaDOB; ?>" />
					<input type="hidden" name="selGuaDuplicateID" value="<?php print $selGuaDuplicateID; ?>" />
					<input type="hidden" name="selGuaNationality" value="<?php print $selGuaNationality; ?>" />
					<input type="hidden" name="selGuaOccupation" value="<?php print $selGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaPhone" value="<?php print $txtGuaPhone; ?>" />
					<input type="hidden" name="txtGuaEmail" value="<?php print $txtGuaEmail; ?>" />
					<input type="hidden" name="txtGuaAddress" value="<?php print $txtGuaAddress; ?>" />
					<input type="hidden" name="selGuaCountry" value="<?php print $selGuaCountry; ?>" />
					<input type="hidden" name="selGuaCity" value="<?php print $selGuaCity; ?>" />
					<input type="hidden" name="selGuaKhan" value="<?php print $selGuaKhan; ?>" />
					<input type="hidden" name="selGuaSangkat" value="<?php print $selGuaSangkat; ?>" />					
					
					<input type="hidden" name="txtCusNationality" value="<?php print $txtCusNationality; ?>" />
					<input type="hidden" name="txtCusOccupation" value="<?php print $txtCusOccupation; ?>" />
					<input type="hidden" name="txtCusSangkat" value="<?php print $txtCusSangkat; ?>" />
					<input type="hidden" name="txtCusKhan" value="<?php print $txtCusKhan; ?>" />
					<input type="hidden" name="txtCusCity" value="<?php print $txtCusCity; ?>" />
					<input type="hidden" name="txtCusCountry" value="<?php print $txtCusCountry; ?>" />
					
					<input type="hidden" name="txtConNationality" value="<?php print $txtConNationality; ?>" />
					<input type="hidden" name="txtConOccupation" value="<?php print $txtConOccupation; ?>" />
					<input type="hidden" name="txtConDuplicate" value="<?php print $txtConDuplicate; ?>" />
					<input type="hidden" name="txtConSangkat" value="<?php print $txtConSangkat; ?>" />
					<input type="hidden" name="txtConKhan" value="<?php print $txtConKhan; ?>" />
					<input type="hidden" name="txtConCity" value="<?php print $txtConCity; ?>" />
					<input type="hidden" name="txtConCountry" value="<?php print $txtConCountry; ?>" />
					
					<input type="hidden" name="txtGuaNationality" value="<?php print $txtGuaNationality; ?>" />
					<input type="hidden" name="txtGuaOccupation" value="<?php print $txtGuaOccupation; ?>" />
					<input type="hidden" name="txtGuaDuplicate" value="<?php print $txtGuaDuplicate; ?>" />
					<input type="hidden" name="txtGuaSangkat" value="<?php print $txtGuaSangkat; ?>" />
					<input type="hidden" name="txtGuaKhan" value="<?php print $txtGuaKhan; ?>" />
					<input type="hidden" name="txtGuaCity" value="<?php print $txtGuaCity; ?>" />
					<input type="hidden" name="txtGuaCountry" value="<?php print $txtGuaCountry; ?>" />
					
					<input type="hidden" name="txtDesNationality" value="<?php print $txtDesNationality; ?>" />
					<input type="hidden" name="txtDesOccupation" value="<?php print $txtDesOccupation; ?>" />
					<input type="hidden" name="txtDesDuplicate" value="<?php print $txtDesDuplicate; ?>" />
					<input type="hidden" name="txtDesSangkat" value="<?php print $txtDesSangkat; ?>" />
					<input type="hidden" name="txtDesKhan" value="<?php print $txtDesKhan; ?>" />
					<input type="hidden" name="txtDesCity" value="<?php print $txtDesCity; ?>" />
					<input type="hidden" name="txtDesCountry" value="<?php print $txtDesCountry; ?>" />
					
					<input type="hidden" name="radCustType" value="<?php print $radCustType; ?>" />
					<input type="hidden" name="selBusinessType" value="<?php print $selBusinessType; ?>" />
					<input type="hidden" name="txtBusinessType" value="<?php print $txtBusinessType; ?>" />
					
					<input type="hidden" name="ext" value="<?php print $ext; ?>" />
					<input type="hidden" name="CustomerID" value="<?php print $CustomerID; ?>" />
				</form>
			</td>
		</tr>
	</table>	
<br>&nbsp;
<?php
# Close connection
$mydb->sql_close();
?>