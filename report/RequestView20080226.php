<?php

	require_once("../common/functions.php");    
	require_once("../common/agent.php");	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="CAMINTEL SA" />
<meta name="reply-to" content="sovandy@camintel.com.kh" />
<title>..:: Wise Biller ::..</title>
<script language="JavaScript" src="../javascript/date.js"></script>
</head>
<body leftmargin="30px" >
<style TYPE="text/css">

td.tdCell {
	border-bottom-width : 1px;
	border-left-style : none;
	border-bottom-style : solid;
	border-right-style : none;
	border-top-style : none;
	border-bottom-color : #000000;
}

.txt {
	border: none;
	border-color: #FFFFFF;
	width: 98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}

</style>

<?php

	#============ Customer billing information ===========================#

	if($ID!='')
	{
		$opt="";
		
		$sql = "select top 1 P.CustID,P.AccID PaccID,username,SubscriptionName,case when T.ServiceID=2 then 'PHONE' when T.serviceID=4 then 'Lease' else 'ISP'
				 end TarName ,Ct.Address + ' ,' + Sangkat.Name + ' ,' + KhanName.Name + ' ,' + CityName.Name + ' ,' + CountryName.Name Address,CustTypeID,CycleFee,f.*
				from tblCustomer C Inner Join tblCustProduct P on C.CustID=P.CustID Inner Join tblTarPackage T on P.packageID=T.packageID Inner Join tblCustAddress 
				Ct on P.accID=Ct.AccID Left Join tlkpLocation KhanName on KhanName.ID=Ct.KhanID Left Join tlkpLocation CityName on CityName.ID=Ct.AccID Left Join
				 tlkpLocation Sangkat on Sangkat.ID=Ct.SangkatID Left Join tlkpLocation CountryName on CountryName.ID=Ct.CountryID  Left Join tblformrequest f on
				  p.accID=f.accID where statusID<>4 and IsBillingAddress=1 and f.ID=$ID";
				$que = $mydb->sql_query($sql);
				if($result = $mydb->sql_fetchrow()){
					$CustID = $result['CustID'];		
					$PaccID = $result['PaccID'];	
					$stName = $result['SubscriptionName'];
					$txtusername = $result['username'];		
					$txtacname = $result['SubscriptionName'];
					$TarName = $result['TarName'];		
					$staddress = $result['Address'];
					// 1 personal
					// 2 business
					
					$type = $result['TarName'];
					if($result['CustTypeID']==1)
					{
						$sttyper="checked";
						$sttypeb="";					
					}
					else
					{
						$sttyper="";
						$sttypeb="checked";
					}
					
					$ID = $result['ID'];
					$distype = $result['distype'];
					if($distype==1)
					{
						$distem='checked=checked';
						$dister='';
					}
					if($distype==2)
					{
						$dister='checked=checked';
						$distem='';
					}
					else
					{
						$distem='';
						$dister='';
					}
					
					
					if($result['dFrom']!='1900-01-01' || $result['dFrom']<>'')
					{
						$dFrom = formatdate($result['dFrom'],5);
					}		
					
					if($result['dTo']!='1900-01-01' || $result['dTo']<>'')
					{
						$dTo = formatdate($result['dTo'],5);
					}		
					
					if($result['rFrom']!='1900-01-01' || $result['rFrom']<>'')
					{
						$rFrom = formatdate($result['rFrom'],5);
					}		
						
					$Specify = $result['Specify'];		
					$Reason = $result['Reason'];
					$drType = $result['drType'];
					
					$IDD = $result['IDD'];
					$NDD = $result['NDD'];
					$Internet = $result['Internet'];		
					
					if($drType==1)
					{
						if($IDD==1)
						{
							$chdidd='checked=checked';
							$chridd='';
						}
						if($NDD==1)
						{
							$chdndd='checked=checked';
							$chrndd='';
						}	
						if($Internet==1)
						{
							$chdinter='checked=checked';
							$chrinter='';
						}	
					}
					else if($drType==2)
					{
						if($IDD==1)
						{
							$chridd='checked=checked';
							$chdidd='';
						}
						if($NDD==1)
						{
							$chrndd='checked=checked';
							$chdndd='';
						}	
						if($Internet==1)
						{
							$chrinter='checked=checked';
							$chdinter='';
						}	
					}
					else
					{
						$chridd='';
						$chdidd='';
						$chdndd='';
						$chrndd='';
						$chdinter='';
						$chrinter='';
					}
					
					$Amount = $result['Amount'];	
					$Location = $result['Location'];		
					$Name = $result['Name'];		
					$Number = $result['Number'];
					$System = $result['System'];
					$MonthlyFee = $result['MonthlyFee'];
					$Fee = $result['Fee'];
					$To = $result['To'];	
					
					$NameAuth = $result['NameAuth'];		
					$DateAuth = formatdate($result['DateAuth'],5);
					$TimeAuth = $result['TimeAuth'];
					$NameAcknow = $result['NameAcknow'];		
					$DateAcknow = formatdate($result['DateAcknow'],5);		
					$TimeAcknow = $result['TimeAcknow'];
					$stM = $result['CycleFee'];
					$stD = formatdate($result['Date'],5);
					$Location = $result['Location'];
					$Name = $result['Name'];
					$System = $result['System'];
					$Number = $result['Number'];
					$MonthlyFee = $result['MonthlyFee'];
					if($Location==1)
					{
						$chlocation='checked=checked';
					}
					else
						$chlocation='';
						
					if($Name==1)
					{
						$chname='checked=checked';
					}
					else
						$chname='';
						
					if($Number==1)
					{
						$chnumber='checked=checked';
					}
					else
						$chnumber='';
						
					if($System==1)
					{
						$chsystem='checked=checked';
					}
					else
						$chsystem='';
						
					if($MonthlyFee==1)
					{
						$chmonthlyfee='checked=checked';
					}
					else
						$chmonthlyfee='';
				}
				$mydb->sql_freeresult();
	}
	
?>

<form name="frmDisplay">
<table border="0" cellpadding="3" cellspacing="0" width="800 px" bordercolor="#000000" align="center" >
	<tr> 						<!-- ROW BLOCK:::: Camintel Logo -->
		<td colspan="2" align="left">
			<img src="../images/Log-CMT.gif" width="150px" height="60px" border="0"/>
		</td>
	</tr>
	<tr> 						<!-- ROW BLOCK:::: Form Title -->
		<td height="50" colspan="2" align="center">
			<font size="+2" color="#000000"><b><u>CUSTOMER REQUEST FORM</u> <br />
		  </b></font>
	  </td>
	</tr>
	<tr>						<!-- ROW BLOCK:::: Service Type and Account -->
		<td colspan="2" align="right">
			<table border="0">
				<tr>
					<td align="left"><font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Services: </font></td>
					<td align="right"><input type="radio" name="service" value="tel" tabindex="1" onclick="checkradio(this.form);" <?php if($type=='PHONE') print 'checked=checked'; ?>/><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Telephone  </font></td> 					<td align="right"><input type="radio" name="service" value="inter" tabindex="2" onclick="checkradio(this.form);" <?php if($type=='ISP') print 'checked=checked';?> /><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Internet  </font></td>
					<td align="right"><input type="radio" name="service" value="lease" tabindex="3" onclick="checkradio(this.form);" <?php if($type=='Lease') print 'checked=checked';?> /><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Lease Line</font></td>
				</tr>
				<tr>
					<td align="left"><font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Tel. No/ Acc, No/ User Name: </font></td>
					<td class="tdCell" colspan="3" align="left"><input type="text" name="txtusername" class="txt" align="left" tabindex="4" onkeydown="checkusername(event);" value="<?php print $txtusername;?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left" colspan="2">
			<table border="0" width="100%">
				<tr>
					<td width="4%" align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Date:</font>					</td>
					<td class="tdCell" width="20%" align="left">
						 <input type="text" tabindex="5" name="stD" class="txt" value="<?php print $stD; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
				  </td>
					<td width="22%"></td>
					<td align="left" width="10%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Monthly fee:</font>					</td>
					<td class="tdCell" align="right" width="44%">
						 <input type="text" tabindex="6" name="stM" class="txt" value="<?php print $stM; ?>" readonly="readonly" />
				  </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>                 <!-- ROW BLOCK:::: Customer Name -->
		<td colspan="2" align="left">		
			<table border="0" width="100%">		
				<tr>				
					<td width="15%" align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Customer's Name:</font>					</td>
					<td class="tdCell" align="left" width="85%">
						 <input type="text" tabindex="7" name="stName" class="txt" value="<?php print $stName; ?>" readonly="readonly"/>
				  </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>            <!-- ROW BLOCK:::: Customer Address -->
		<td colspan="2" align="left">	
			<table border="0" width="100%">			
				<tr>
					<td width="17%" align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Customer's Address:</font></td>
					<td class="tdCell" align="left" width="83%">
						 <input type="text" tabindex="8" name="staddress" class="txt" value="<?php print $staddress; ?>" readonly="readonly" />
				  </td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>            <!-- ROW BLOCK:::: Customer Type -->
		<td colspan="2" align="left">	
			<table border="0" width="100%">			
				<tr>
					<td align="left" width="20%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Customer Type:</font>
					</td>
					<td width="2%" align="left">					
						<input type="radio" tabindex="10" name="sttype" <?php if($sttyper=='checked') print 'checked=checked'; ?> disabled="disabled" />
				  </td>
					<td align="left" width="18%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Residence</font>					</td>
					<td width="2%" align="left">
						<input type="radio" tabindex="10" name="sttype" <?php if($sttypeb=='checked') print 'checked=checked'; ?> disabled="disabled" />
				  </td>
					<td align="left" width="18%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Business</font>					</td>
					<td width="2%" align="left">
						<input type="radio" tabindex="10" name="sttype" disabled="disabled" />
				  </td>
					<td align="left" width="18%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Government</font>					</td>
					<td width="2%" align="left">
						<input type="radio" tabindex="10" name="sttype" disabled="disabled" />
				  </td>
					<td align="left" width="18%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">NGO</font>					</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>            <!-- ROW BLOCK:::: Nature of request -->
		<td colspan="2" align="left">	
			<font face="Arial, Helvetica, sans-serif" size="+0" color="#3300FF"><strong> Nature of request:</strong></font>
		</td>
	</tr>	
	<tr>            <!-- ROW BLOCK:::: Nature of request -->
	  <td colspan="2" align="left">	
			
				<tr>
					<td align="left" width="50%" style="vertical-align:top;">
						<table style="border-collapse:collapse;border-style:double;border:double;border-color:#000000" width="100%">
							<tr bordercolor="#FFFFFF">
								<td height="25">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong><u>Change / Move:</u></strong></font>										
								</td>
							</tr>
							<tr  bordercolor="#FFFFFF">
								<td width="100%" bordercolor="#FFFFFF">
									<table width="100%" border="0">
										<tr>
											<td>
												<input type="checkbox" tabindex="14" name="chmovelo" <?php print $chlocation; ?>/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Location</font>
											</td>
											<td>
												<input type="checkbox" tabindex="15" name="chmovena" <?php print $chname; ?>/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Name</font>
											</td>
											<td>
												<input type="checkbox" tabindex="16" name="chmovenu" <?php print $chnumber; ?>/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Number</font>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" tabindex="17" name="chmovesys" <?php print $chsystem; ?>/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">System</font>
											</td>
											<td>
												<input type="checkbox" tabindex="18" name="chmovemo" <?php print $chmonthlyfee; ?>/>
											</td>
											<td colspan="3">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Monthly fee</font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr >
								<td width="100%" align="left">
									<table width="100%" border="0">
										<tr>
											<td align="left" width="100%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">To:</font>
											</td>
										</tr>
										<tr>
											<td align="left" rowspan="2" class="tdCell">
												<textarea name="txtto" class="txt" rows="2" tabindex="19"><?php print $To;?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr >
								<td width="100%" align="left">
									<table width="100%" border="0">
										<tr>
											<td align="left" width="100%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Fee:</font>
											</td>
										</tr>
										<tr>
											<td align="left" rowspan="2" class="tdCell">
												<textarea name="txtfee" class="txt" rows="2" style="border-bottom-style:solid;" tabindex="20"><?php print $Fee;?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td align="left" width="50%" style="vertical-align:top">
						 <table width="100%" height="225" style="border-collapse:collapse;border-style:double;border:double;border-color:#000000">
							 <tr bordercolor="#FFFFFF">
								<td colspan="2">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong><u>Disconnect:</u></strong></font>
								</td>
							 </tr>
							 <tr bordercolor="#FFFFFF">
								<td colspan="2">
									<table border="0" width="100%">
										<tr>
											<td width="5%">
												<input type="radio" tabindex="21" name="dis" <?php print $distem; ?> />
										  	</td>
											<td width="32%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Temporary</font>
											</td>
											<td width="4%">
												<input type="radio" tabindex="22" name="dis" <?php print $dister; ?>/>
										  	</td>
											<td width="59%">
													<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Termination</font>												
											</td>	
										</tr>
									</table>
								</td>
							 </tr>
							 <tr bordercolor="#FFFFFF">
								<td colspan="2">
									<table border="0" width="100%">
										<tr>
											<td width="7%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">From:</font>																
										  	</td>
											<td width="38%">
												<table border="0" width="100%">												
													<tr>
														<td class="tdCell">
															<input type="text" tabindex="23" name="stdisfrom" class="txt" size="17" maxlength="10" value="<?php print $dFrom; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
														</td>
													</tr>
												</table>
										    </td>
											<td width="5%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">To:</font>										  	
											</td>
											<td width="50%">
												<table border="0" width="100%">
													<tr>
														<td class="tdCell">																								
															<input type="text" tabindex="24" name="stdisto" class="txt" value="<?php print $dTo;?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>												
														</td>
													</tr>
												</table>
										  </td>	
										</tr>
									</table>
								</td>
							 </tr>
							 <tr>
							 	<td colspan="2" align="center">
									<font face="Arial, Helvetica, sans-serif" size="-2" color="#000000">(Maximum temporary disconnection is two-month period only)</font>
								</td>
							 </tr>
							 <tr bordercolor="#FFFFFF">
								<td height="19" colspan="2">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong><u>Reconnection:</u></strong></font>								
								</td>
							 </tr>
							 <tr bordercolor="#FFFFFF" height="45">
								<td width="7%" height="30">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">From:</font>								
								</td>
								<td class="tdCell" align="left">
									<table border="0" width="50%">
										<tr>
											<td class="tdCell" width="100%">
												 <input type="text" tabindex="25" name="strecfrom" class="txt" style="font-size:12px" value="<?php print $rFrom;?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
											</td>
										</tr>
									</table>
							    </td>
							 </tr>
					  </table>
					</td>
				</tr>
			
		</td>
	</tr>	
	<tr>
		<td width="50%" height="208"> <!-- ROW BLOCK:::: Deposit anad Refund -->
			<table style="border-collapse:collapse;border-style:double;border:double;border-color:#000000" width="100%">
				<tr bordercolor="#FFFFFF">
					<td height="25" align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong><u>Deposit/Refund:</u></strong></font>										
					</td>
				</tr>
				<tr  bordercolor="#FFFFFF">
					<td width="100%" bordercolor="#FFFFFF">
						<table width="100%" border="0">
							<tr>
								<td width="22%">
									<span style="font-family:Arial, Helvetica, sans-serif;font-size:12px"><strong>Deposit:</strong></span>								</td>
								<td width="4%">
									<input type="checkbox" tabindex="26" name="deidd" <?php print $chdidd; ?>/>
							    </td>
								<td width="18%" align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">IDD</font>								
								</td>
								<td width="4%">
									<input type="checkbox" tabindex="27" name="dendd" <?php print $chdndd; ?>/>
							    </td>
								<td width="20%" align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">NDD</font>								
								</td>
								<td width="4%">
									<input type="checkbox" tabindex="28" name="deinter" <?php print $chdinter; ?>/>
							    </td>
								<td width="28%" align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Internet</font>								
								</td>
							</tr>
							<tr>
								<td>
									<span style="font-family:Arial, Helvetica, sans-serif;font-size:12px"><strong>Refund:</strong></span>
								</td>
								<td>
									<input type="checkbox" tabindex="29" name="reidd" <?php print $chridd; ?>/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">IDD</font>
								</td>
								<td>
									<input type="checkbox" tabindex="30" name="rendd" <?php print $chrndd; ?>/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">NDD</font>
								</td>
								<td>
									<input type="checkbox" tabindex="31" name="reinter" <?php print $chrinter; ?>/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Internet</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr >
					<td width="100%" height="66" align="left">
						<table width="100%" border="0">
							<tr>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Amount:</font>
								</td>
								<td align="left" rowspan="2" class="tdCell" width="90%">
									<input type="text" name="txtamt" class="txt" rows="1" tabindex="32" value="<?php print $Amount;?>" />
								</td>
							</tr>
						</table>
				  </td>
				</tr>
			</table>
	  </td>
		<td width="50%">
			<table style="border-collapse:collapse;border-style:double;border:double;border-color:#000000" width="100%">
				<tr bordercolor="#FFFFFF">
					<td height="28" align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong>Other</strong>(Specify):</font>					
					</td>
				</tr>
				<tr valign="top" >
					<td width="100%" height="114" align="left">
						<table width="100%" border="0">
							<tr valign="top">
								<td align="left" rowspan="2" class="tdCell" width="100%" colspan="2" valign="top">
									<textarea name="txtother" class="txt" rows="6" tabindex="33"><?php print $Specify;?></textarea>
								</td>
							</tr>
						</table>
				  </td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td width="100%" height="174" colspan="2" valign="top">
			<table style="border-collapse:collapse;border-style:double;border:double;border-color:#000000" width="100%">
				<tr>
					<td align="left" width="100%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong>Reason:</strong></font>
					</td>
				</tr>
				<tr>
					<td width="100%">					
						<table border="0" width="100%"> 	
							<tr>					
								<td class="tdCell" width="100%">
									<textarea name="txtreason" class="txt" rows="7" tabindex="34"><?php print $Reason; ?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
	  </td>
	</tr>
	<tr>
		<td colspan="2"> <!-- ROW BLOCK:::: SIGNATURE -->
			
			<table border="0" width="100%">			
				<tr>
					<td align="left" width="50%" style="vertical-align:top;">
						<table border="0" width="100%">
							<tr >
								<td width="100%" height="55" align="left">
									<table width="100%" border="0">
										<tr>
											<td width="36%" height="36" align="left" style="vertical-align:top;">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Authorized Signature:</font>											</td>	
											<td width="64%" style="vertical-align:top;">
												<table border="0" width="100%">								
													<tr>
														<td align="left" class="tdCell" width="100%">
																<span style="width:100%"><input type="text" name="txtspan" value="" class="txt" disabled="disabled" /></span>
														</td>
													</tr>
												</table>
										  </td>
										</tr>
									</table>
							  </td>
							</tr>
							<tr >
								<td width="100%" align="left">

												<table border="0" width="100%">										
													<tr>
														<td width="9%" align="left">
															<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Name</font>														</td>	
														<td width="91%">
															<table border="0" width="100%">								
																<tr>
																  <td align="left" class="tdCell" width="100%">
																			:<input type="text" name="txtaname" class="txt" tabindex="35" value="<?php print $NameAuth;?>" />
																	</td>
																</tr>
															</table>
													  </td>
													</tr>
												</table>
								</td>
							</tr>
							<tr>
								<td width="100%">
									<table border="0" width="100%">
										<tr>
											<td width="7%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Date:</font>										  	</td>
											<td width="40%">
												<table border="0" width="100%">												
													<tr>
														<td class="tdCell">
														<input type="text" tabindex="36" name="txtafrom" class="txt" value="<?php print $DateAuth; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
														</td>
													</tr>
											  </table>
										  </td>
											<td width="5%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Time:</font>											</td>
											<td width="48%">
												<table border="0" width="100%">
													<tr>
														<td class="tdCell">																								
															<input type="text" tabindex="37" name="txtato" class="txt" value="<?php print $TimeAuth; ?>" />												
														</td>
													</tr>
												</table>
										  </td>	
										</tr>
									</table>
								</td>
							</tr>
							
						</table>
					</td>
					<td align="left" width="50%" style="vertical-align:top">
						 
						 <table border="0" width="100%">
							<tr >
								<td width="100%" align="left">
									<table width="100%" border="0">
										<tr>
											<td width="33%" align="left">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Acknowledgement:</font>											</td>	
											<td width="67%">
												<table border="0" width="100%">								
													<tr>
														<td align="left" class="tdCell" width="100%">
																<span style="width:100%"><input name="txtspan1" type="text" class="txt"  /></span>
														</td>
													</tr>
												</table>
										  </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<font face="Arial, Helvetica, sans-serif" size="-2" color="#000000">(Customer or his/her representative's signature)</font>
								</td>
							</tr>
							<tr >
								<td width="100%" align="left">
												<table border="0" width="100%">										
													<tr>
														<td width="9%" align="left">
															<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Name</font>														</td>	
														<td width="91%">
															<table border="0" width="100%">								
																<tr>
																	<td align="left" class="tdCell" width="100%">
																			:<input type="text" name="txtacname" class="txt" value="<?php print $NameAcknow; ?>" tabindex="38"/>
																	</td>
																</tr>
															</table>
													  </td>
													</tr>
												</table>
								</td>
							</tr>
							<tr>
								<td width="100%">
									<table border="0" width="100%">
										<tr>
											<td width="7%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Date:</font>										  	</td>
											<td width="40%">
												<table border="0" width="100%">												
													<tr>
														<td class="tdCell">
														<input type="text" tabindex="39" name="txtacfrom" class="txt" value="<?php print $DateAcknow; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
														</td>
													</tr>
											  </table>
										  </td>
											<td width="5%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Time:</font>											</td>
											<td width="48%">
												<table border="0" width="100%">
													<tr>
														<td class="tdCell">																								
															<input type="text" tabindex="40" name="txtacto" class="txt" value="<?php print $TimeAcknow; ?>" />												
														</td>
													</tr>
												</table>
										  </td>	
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="background-color:#003366;border:none" align="center">
			<font size="-2" face="Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>Copies: White -File / Pink -Customer / Green -Operation Department / Yellow - Billing Section</strong></font>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1"  width="100%" style="border-color:#000000;border-bottom-style:double;border-bottom-color:#000000;border-left-style:none;border-right-style:none">
				<tr>
					<td >
						<span>   </span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-family:Arial, Helvetica, sans-serif;font-size:10px" align="center">#1, Corner of Terak Vithei Sisowath & Vithei Phsar Dek, Phnom Pen, Cambodia <br/> Tel: 023-986 789 Fax : 023-986 277 E-mail: sales@camintel.com</p>
		</td>
	</tr>
	
</table>
</form>
</body>
</html>
<?php
# Close connection
$mydb->sql_close();
?>