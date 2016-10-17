<?php
	require_once("../common/agent.php");
	require_once("../common/functions.php");		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="BRC Technology" />
<meta name="reply-to" content="supports@brc-tech.com" />
<title>..:: Wise Biller ::..</title>
<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
</head>
<script language="javascript" type="text/javascript" src="../javascript/loading.js"></script>

<body leftmargin="30px">
<script language=javascript>
	var ShowInProcess = new ShowHideProcess("<table width=800 height=460 border=0 cellspacing=0 cellpadding=100><tr><td align=center><p><img src='../images/loading.gif' border=0><br><b>Please wait a moment...</b></p></td></tr></table>");
	ShowInProcess.Show();
</script>
<?php
	#============ Customer billing information ===========================#
	$sql = "select c.Salutation, c.CustName, a.Address, c.CustTypeID, c.BusinessReg,
							l1.name as 'Sangkat', l2.name as 'Khan', l3.name as 'City', l4.name as 'Country' 
					from tblCustomer c (nolock), tblCustAddress a(nolock), tlkpLocation l1(nolock), tlkpLocation l2(nolock), 
								tlkpLocation l3(nolock), tlkpLocation l4(nolock), tblCustProduct cp(nolock)
					where c.CustID = a.CustID and a.SangkatID = l1.id and a.KhanID = l2.id and a.CityID = l3.id 
								and a.CountryID = l4.id and a.IsBillingAddress = 1 and c.CustID = cp.CustID and cp.AccID = $AccountID";

	$que = $mydb->sql_query($sql);
	if($result = $mydb->sql_fetchrow()){
		$CSalutation = $result['Salutation'];		
		$CustName = $result['CustName'];	
		$Address = $result['Address'];		
		$Sangkat = $result['Sangkat'];		
		$Khan = $result['Khan'];
		$City = $result['City'];
		$Country = $result['Country'];
		$CustTypeID = $result['CustTypeID'];
		$BusinessReg = $result['BusinessReg'];		
		$Address = $Address.", ".$Sangkat.", ".$Khan.", ".$City.", ".$Country;	
		$CustomerName = $CSalutation." ".$CustName;	 
	}
	$mydb->sql_freeresult();

	#============ Designate information ===========================#
	$sql = "select d.Salutation, d.DesignateName, d.DOB, na.Country, oc.Career, d.IdentityMode, d.IdentityData
					from tblCustDesignate d(nolock), tlkpCareer oc(nolock), tlkpCountry na(nolock), tblCustProduct cp(nolock)
					where d.OccupationID = oc.CareerID and d.NationalityID = na.CountryID 
						and d.CustID = cp.CustID and cp.AccID = $AccountID ";
	$que = $mydb->sql_query($sql);
	if($result = $mydb->sql_fetchrow($que)){
		$deSalutation = $result['Salutation'];
		$deDesignateName = $result['DesignateName'];
		$deName = $deSalutation." ".$deDesignateName;
		$deDOB = $result['DOB'];
		$deCountry = $result['Country'];
		$deCareer = $result['Career'];
		$deIdentityMode = $result['IdentityMode'];
		$deIdentityData = $result['IdentityData'];
	}
	$mydb->sql_freeresult($que);
	#============ Contact information ===========================#
	$sql = "select c.Salutation, c.ContactName, c.DOB, c.Phone, na.Country, oc.Career, c.IdentityMode, c.IdentityData,
								c.Address, l1.name as 'Sangkat', l2.name as 'Khan', l3.name as 'City', l4.name as 'adCountry'
					from tblCustContact c(nolock), tlkpCareer oc(nolock), tlkpCountry na(nolock), tblCustProduct cp(nolock),
							tlkpLocation l1(nolock), tlkpLocation l2(nolock), tlkpLocation l3(nolock), tlkpLocation l4(nolock)
					where c.SangkatID = l1.id and c.KhanID = l2.id and c.CityID = l3.id and c.CountryID = l4.id 
						and c.OccupationID = oc.CareerID and c.NationalityID = na.CountryID 
						and c.CustID = cp.CustID and cp.AccID = $AccountID ";
	$que = $mydb->sql_query($sql);
	if($result = $mydb->sql_fetchrow($que)){
		$Salutation = $result['Salutation'];
		$ContactName = $result['ContactName'];
		$ContactName = $Salutation." ".$ContactName;
		$DOB = $result['DOB'];
		$Phone = $result['Phone'];
		$Country = $result['Country'];
		$Career = $result['Career'];
		$IdentityMode = $result['IdentityMode'];
		$IdentityData = $result['IdentityData'];
		$conAddress = $result['Address'];
		$conSangkat = $result['Sangkat'];
		$conKhan = $result['Khan'];
		$conCity = $result['City'];
		$conCountry = $result['adCountry'];
		$conAddress = $conAddress.", ".$conSangkat.", ".$conKhan.", ".$conCity.", ".$conCountry;
	}
	$mydb->sql_freeresult($que);
	if(intval($CustTypeID) == 1){
		$company = "";
		$BusinessReg = "";
		$Designate = "";
		$deDOB = "";
		$deCareer = "";
		$deCountry = "";
		$deIdentityMode = "";
		$deIdentityData = "";
		
		$conName = strval($Salutation)." ".$ContactName;
		$conDOB = $DOB;
		$conCountry = $Country;
		$conCareer = $Career;
		$conIdentityMode = $IdentityMode;
		$conIdentityData = $IdentityData;
	}else{

		$company = strtoupper($CustName);
		$BusinessReg = $BusinessReg;
		$Designate = $deSalutation." ".$deDesignateName;
		$deDOB = $deDOB;
		$deCareer = $deCareer;
		$deIdentityMode = $deIdentityMode;
		$deIdentityData = $deIdentityData;
		$conName = "";
		$conDOB = "";
		$conCountry = "";
		$conCareer = "";
		$conIdentityMode = "";
		$conIdentityData = "";
	}
	#============ Gurarrantor information ===========================#
	$sql = "select c.Salutation, c.GuarrantorName, c.Address, c.DOB, c.IdentityMode, c.IdentityData,
							l1.name as 'Sangkat', l2.name as 'Khan', l3.name as 'City', l4.name as 'Country' 
					from tblCustGuarrantor c(nolock), tlkpLocation l1(nolock), tlkpLocation l2(nolock), tlkpLocation l3(nolock), 
							tlkpLocation l4(nolock), tblCustProduct cp(nolock)
					where c.SangkatID = l1.id and c.KhanID = l2.id and c.CityID = l3.id and c.CountryID = l4.id 
						and c.CustID = cp.CustID and cp.AccID = $AccountID ";
	$que = $mydb->sql_query($sql);
	if($result = $mydb->sql_fetchrow($que)){
		$guaSalutation = $result['Salutation'];
		$GuarrantorName = $result['GuarrantorName'];
		$gName = strval($guaSalutation)." ".$GuarrantorName;
		$guaAddress = $result['Address'];
		$guaDOB = $result['DOB'];
		$guaIdentityMode = $result['IdentityMode'];
		$guaIdentityData = $result['IdentityData'];
		$guaSangkat = $result['Sangkat'];
		$guaKhan = $result['Khan'];
		$guaCity = $result['City'];
		$guaCountry = $result['Country'];
		$gAddress = $guaAddress.", ".$guaSangkat.", ".$guaKhan.", ".$guaCountry;
	}
	
	$mydb->sql_freeresult($que);
	
	# =============== Get installation information =====================	
	$sql = "select l1.[name] as Country, l2.[name] as City, l3.[name] as Khan, l4.[name] as Sangkat, ad.AddressID, ad.Address,
							ad.SangkatID, ad.KhanID, ad.CityID, ad.CountryID
					from tblCustAddress ad left join tlkpLocation l1 on ad.CountryID = l1.id
																 left join tlkpLocation l2 on ad.CityID = l2.id
																 left join tlkpLocation l3 on ad.KhanID = l3.id
																 left join tlkpLocation l4 on ad.SangkatID = l4.id						
					where ad.IsBillingAddress = 0 and ad.AccID=$AccountID";
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$intAddress = $rst['Address'];
			$intAddressID = $rst['AddressID'];
			$intCountryName = $rst['Country'];
			$intKhanName = $rst['Khan'];
			$intCityName = $rst['City'];
			$intSangkatName = $rst['Sangkat'];	
			$intSangkatID = $rst['SangkatID'];
			$intKhanID = $rst['KhanID'];
			$intCityID = $rst['CityID'];
			$intCountryID = $rst['CountryID'];				
			$installAddress = $intAddress.", ".$intSangkatName.", ".$intKhanName.", ".$intCityName.", ".$intCountryName;				
		}
	}
	$mydb->sql_freeresult();
	# =============== Get account information =====================
	$sql = "SELECT a.UserName, ta.CycleFee, ca.Credit, Incoming, Outgoing, International, IncomingLoc, IncomingNat, OutgoingLoc,
								OutgoingNat, Other
					FROM tblTarPackage ta(nolock), tblProductStatus ps(nolock), 
							tblCustProduct a(nolock) left join tblAccCreditAllowance ca(nolock) 
							on a.AccID = ca.AccID
					WHERE a.PackageID = ta.PackageID and a.AccID = ps.AccID and a.AccID = $AccountID ";
	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$AccountName = $rst['UserName'];
			$CycleFee = $rst['CycleFee'];
			$Incoming = $rst['Incoming'];
			$Outgoing = $rst['Outgoing'];
			$International = $rst['International'];
			$IncomingLoc = $rst['IncomingLoc'];
			$IncomingNat = $rst['IncomingNat'];
			$OutgoingLoc = $rst['OutgoingLoc'];
			$OutgoingNat = $rst['OutgoingNat'];
			$Other = $rst['Other'];
			
		}
	}
	$mydb->sql_freeresult();
?>
<table border="1" cellpadding="3" cellspacing="0" width="100%" bordercolor="#000000" style="border-collapse:collapse">
	<tr>
		<td colspan="2" bgcolor="#999999" align="left">
			<font color="#FFFFFF"><b>Customer Information</b></font>
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center" colspan="4" height="30"><b>Firm or Company Application</b></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Firm / Company's Name:</td>
					<td align="left" colspan="3" width="90%"><?php print $company; ?></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Business Registration:</td>
					<td align="left" width="40%" colspan="3"><?php print $BusinessReg; ?></td>										
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Authorized Officer:</td>
					<td align="left" width="40%"><?php print $deName; ?></td>
					<td align="left" nowrap="nowrap">Nationality:</td>
					<td align="left" width="40%"><?php print $deCountry; ?></td>										
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Occupation:</td>
					<td align="left" width="40%"><?php print $deCareer; ?></td>
					<td align="left" nowrap="nowrap">Date of birth:</td>
					<td align="left" width="40%"><?php print FormatDate($deDOB, 3); ?></td>										
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Duplicate of:</td>
					<td align="left" width="40%"><?php print $deIdentityMode; ?></td>
					<td align="left" nowrap="nowrap">Number:</td>
					<td align="left" width="40%"><?php print $deIdentityData; ?></td>					
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td align="center" colspan="4" height="30"><b>Personal Application</b></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Customer Name:</td>
					<td align="left" colspan="3" width="90%"><b><?php print $conName; ?></b></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Date of birth:</td>
					<td align="left" width="40%"><?php print FormatDate($conDOB, 3); ?></td>
					<td align="left" nowrap="nowrap">Nationality:</td>
					<td align="left" width="40%"><?php print $conCountry; ?></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Occupation:</td>
					<td align="left" colspan="3" width="90%"><?php print $conCareer; ?></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Duplicate of:</td>
					<td align="left" width="40%"><?php print $conIdentityMode; ?></td>
					<td align="left" nowrap="nowrap">Number:</td>
					<td align="left" width="40%"><?php print $conIdentityData; ?></td>					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="3" cellspacing="0" align="left">
				<tr>
					<td align="left" nowrap="nowrap">Guarantor's Name:</td>
					<td align="left" width="40%"><?php print $gName;?></td>
					<td align="left" nowrap="nowrap">Date of birth:</td>
					<td align="left" width="40%"><?php print FormatDate($guaDOB, 3);?></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Duplicate of:</td>
					<td align="left" width="40%"><?php print $guaguaIdentityMode;?></td>
					<td align="left" nowrap="nowrap">Number:</td>
					<td align="left" width="40%"><?php print $guaguaIdentityData;?></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Address:</td>
					<td align="left" width="40%"><?php print $gAddress;?></td>
					<td align="left" colspan="2">Guarantor's Signature:_________________ Date ____ /_____ /_______</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="#999999" align="left">
			<font color="#FFFFFF"><b>Connection Status</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="3" cellspacing="0" align="left">
				<tr>
					<td align="left" nowrap="nowrap">Installation address:</td>
					<td align="left" colspan="3"><?php print $installAddress;?></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Billing address:</td>
					<td align="left" colspan="3"><?php print $Address;?></td>					
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Contact name:</td>
					<td align="left" width="40%"><?php print $ContactName;?></td>
					<td align="left" nowrap="nowrap">Telephone:</td>
					<td align="left" width="40%"><?php print $Phone;?></td>
				</tr>	
				<tr>
					<td align="left" nowrap="nowrap">Address:</td>
					<td align="left" colspan="3"><?php print $conAddress;?></td>					
				</tr>			
				<tr>
					<td align="left" nowrap="nowrap">Telephone number:</td>
					<td align="left" width="40%"><?php print $AccountName;?></td>
					<td align="left" nowrap="nowrap">Credit limit per month:</td>
					<td align="left" width="40%"><?php print $Credit;?></td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">Account number:</td>
					<td align="left" width="40%"><?php print $AccountID;?></td>
					<td align="left" nowrap="nowrap">Monthly Fee:</td>
					<td align="left" width="40%"><?php print FormatCurrency($CycleFee);?></td>
				</tr>	
			</table>
		</td>
	</tr>	
	<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="0" width="100%" cellspacing="0">	
				<tr>
					<td colspan="2" bgcolor="#999999" align="left" height="20">
						<font color="#FFFFFF"><b>Facilities and Service Requested</b></font>
					</td>
				</tr>
				<tr>				
					<td align="left">
						<input type="checkbox" name="lc" <?php if($Outgoing) print 'checked="checked"';?> />Local call
					</td>
					<td align="left">
						<input type="checkbox" name="ldd" <?php if($OutgoingNat) print 'checked="checked"';?> />Long distance call
					</td>
				</tr>
				<tr>
					<td align="left">
						<input type="checkbox" name="int" <?php if($International) print 'checked="checked"';?> />International call
					</td>
					<td align="left">
						<input type="checkbox" name="fax" />Connect to fax machine
					</td>
				</tr>
				<tr>
					<td align="left">
						<input type="checkbox" name="int" />Connect to E-mail/Internet
					</td>
					<td align="left">
						<input type="checkbox" name="fax" <?php if($Other) print 'checked="checked"';?>/>Other
					</td>
				</tr>
			</table>
		</td>
		<td align="left" valign="top">
			<table border="0" cellpadding="0" width="100%" cellspacing="0">	
				<tr>
					<td colspan="2" bgcolor="#999999" align="left" height="20">
						<font color="#FFFFFF"><b>For Technician</b></font>
					</td>
				</tr>
				<tr>				
					<td align="center">
						OSP (Out Side Plan)
					</td>
					<td align="center">
						O/P (Operation)
					</td>
				</tr>
				<tr>
					<td align="left">
						MDF:________________________
					</td>
					<td align="left">
						___________________________
					</td>
				</tr>
				<tr>
					<td align="left">
						Cabinet:_____________________
					</td>
					<td align="left">
						___________________________
					</td>
				</tr>
				<tr>
					<td align="left">
						DP:_________________________
					</td>
					<td align="left">
						___________________________
					</td>
				</tr>
				<tr>
					<td align="left">
						____________________________
					</td>
					<td align="left">
						___________________________
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="0" width="100%" cellspacing="0">	
				<tr>
					<td colspan="2" bgcolor="#999999" align="left" height="20">
						<font color="#FFFFFF"><b>Payment Terms</b></font>
					</td>
				</tr>
				<!-- PAYMENT TERM IS AROUND HERE -->
				<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
						 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
			</table>
		</td>
		<td>
			<table border="0" cellpadding="0" width="100%" cellspacing="0">	
				<tr>
					<td colspan="2" align="left" height="20">
						<font color="#FFFFFF"><b></b></font>
					</td>
				</tr>									
				<tr>
					<td align="left" colspan="2">Customer's name:<?php print $CustomerName; ?></td>
				</tr>
				<tr>
					<td align="left" valign="bottom" height="50">Singature: _____________________________</td>
					<td align="left" valign="bottom">Date: _____/______/_______</td>
				</tr>
				<tr>
					<td align="left" colspan="2">Camintel officer:_________________________</td>
				</tr>
				<tr>
					<td align="left" height="50" valign="bottom">Authorized Singature: ____________________</td>
					<td align="left" valign="bottom">Date: _____/______/_______</td>
				</tr>
				<tr>
					<td align="left" height="50" valign="baseline">Comment:</td>
					<td align="left">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
		
	</div>
<script language=javascript>
		ShowInProcess.Hide();
	</script>	
</body>
</html>
<?php
# Close connection
$mydb->sql_close();
?>