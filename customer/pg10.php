<?php
	require_once("./common/agent.php");
	require_once("./common/functions.php");
	require_once("./common/helper.php");
	/*
		+ ************************************************************************************** +
		*																																												 *
		* This code is not to be distributed without the written permission of BRC Technology.   *
		* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a>  *
		* 																																											 *
		+ ************************************************************************************** +
	*/
?>

<script language="JavaScript" type="text/javascript">
	function changeImage(imgCode, imgSource){
		//alert(imgSource);
			 if(imgCode == 1)
				document.customer.src = imgSource;
		else if(imgCode ==2)
				document.product.src = imgSource;
		else if(imgCode ==3)
				document.finance.src = imgSource;
		else if(imgCode ==4)
				document.usage.src = imgSource;
		else if(imgCode ==5)
				document.audit.src = imgSource;
	}
</script>
<?php

	# =============== Get customer header =====================

		$sql = "select c.CustName, sum(d.NationalDeposit) as ncDeposit, sum(d.InternationDeposit) as 'icDeposit',
									sum(d.MonthlyDeposit) as mfDeposit, sum(b.Credit) as Credit, sum(b.Outstanding) as Outstanding
						from tblCustomer c, tblCustProduct a, tblAccountBalance b, tblAccDeposit d
						where c.CustID = a.CustID and a.AccID = b.AccID and a.AccID = d.AccID and c.CustID=$CustomerID
						group by c.CustName";

		if($que = $mydb->sql_query($sql)){
			if($rst = $mydb->sql_fetchrow($que)){
				$CustName = $rst['CustName'];
				$ncDeposit = $rst['ncDeposit'];
				$icDeposit = $rst['icDeposit'];
				$mfDeposit = $rst['mfDeposit'];
				$Deposit = $ncDeposit + $icDeposit + $mfDeposit;
				$Deposit = FormatCurrency($Deposit);
				$Credit = FormatCurrency($rst['Credit']);
				$Outstanding = FormatCurrency($rst['Outstanding']);
			}
		}

		$mydb->sql_freeresult();

	# =============== Get customer information =====================
	$sql = "select cu.CustName, convert(varbinary(500), cu.CustNameKhmer) AS CustNameKhmer, ct.CustTypeID, ct.CustTypeName, cu.VATNumber, cu.IsVATException, cu.RegisteredDate,
							cu.Telephone, cu.Email, inv.InvoiceType,
							cu.IdentityData, cu.BillingEmail, cu.BusinessReg, cu.IdentityMode, cu.DOB, cu.Salutation, me.Salutation as mS, me.Name,
							l1.[name] as Country, l2.[name] as City, l3.[name] as Khan, l4.[name] as Sangkat, cu.Address, cu.IsAccGroup,
							ty.Name	as BusType,cu.Street
					from tblCustomer cu inner join tlkpCustType ct on cu.CustTypeID = ct.CustTypeID
															inner join tlkpCustInvoiceType inv on cu.InvoiceTypeID = inv.InvoiceTypeID
															inner join tlkpMessenger me on cu.MessengerID = me.MessengerID
															inner join tlkpCustBusinessType ty on cu.Category = ty.TypeID
															left join tlkpLocation l1 on cu.CountryID = l1.id
															left join tlkpLocation l2 on cu.CityID = l2.id
															left join tlkpLocation l3 on cu.KhanID = l3.id
															left join tlkpLocation l4 on cu.SangkatID = l4.id
					where cu.CustID=$CustomerID";

	if($que = $mydb->sql_query($sql)){
		if($rst = $mydb->sql_fetchrow($que)){
			$CustName = $rst['CustName'];
			$CustNameKhmer = $rst['CustNameKhmer'];
			$CustTypeID = $rst['CustTypeID'];
			$Street = $rst['Street'];
			$CustTypeName = $rst['CustTypeName'];
			$VATNumber = $rst['VATNumber'];
			$IsVATException = $rst['IsVATException'];
			$RegisteredDate = $rst['RegisteredDate'];
			$IdentityData = $rst['IdentityData'];
			$BillingEmail = $rst['BillingEmail'];
			$BusinessReg = $rst['BusinessReg'];
			$IdentityMode = $rst['IdentityMode'];
			$DOB = $rst['DOB'];
			$Salutation = $rst['Salutation'];
			$Country = $rst['Country'];
			$City = $rst['City'];
			$Khan = $rst['Khan'];
			$Sangkat = $rst['Sangkat'];
			$Address = $rst['Address'];
			$InvoiceType = $rst['InvoiceType'];
			$mS = $rst['mS'];
			$mName = $rst['Name'];
			$ResidentAddress = $Address.", ".$Street.", ".$Sangkat.", ".$Khan.", ".$City.", ".$Country;
			if($IsVATException)
				$VATCharge = "No";
			else
				$VATCharge = "Yes";
			$Messenger = $mS." ".$mName;
			$CustName = $Salutation." ".$CustName;
			$Duplicate = $IdentityMode."- ".$IdentityData;
			$IsAccGroup = $rst['IsAccGroup'];
			$Telephone = $rst['Telephone'];
			$Email = $rst['Email'];
			$BusType = $rst['BusType'];
		}
	}

	$mydb->sql_freeresult();

?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bordercolor="#aaaaaa" style="border-collapse:collapse">
		<tr>
			<td valign="top"  height="50">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<tr>
							<td align="left">ID: <b><?php print $CustomerID ?></b></td>
							<td align="left">Name:<b><?php print $CustName ?></b></td>
						</tr>
						<tr>
							<td align="left">Deposit: <b><?php print $Deposit; ?></b></td>
							<td align="left">Balance: <b><?php print $Credit; ?></b></td>
							<td align="left">Invoice: <b><?php print $Outstanding; ?></b></td>
						</tr>
					</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<!-- Individual customer tab menu -->
				<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bordercolor="#ffffff" align="center">
					 <tr>
						<td align="left" width="15" background="./images/tab_null.gif">&nbsp;</td>

						<td align="left" width="85"><img src="./images/tab/customer_active.gif" name="customer" border="0" id="customer" /></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=90"><img src="./images/tab/product.gif" name="product" border="0" id="product" onmouseover="changeImage(2, './images/tab/product_over.gif');" onmouseout="changeImage(2, './images/tab/product.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=41"><img src="./images/tab/finance.gif" name="finance" border="0" id="finance" onmouseover="changeImage(3, './images/tab/finance_over.gif');" onmouseout="changeImage(3, './images/tab/finance.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID; ?>&pg=70"><img src="./images/tab/usage.gif" name="usage" border="0" id="usage" onmouseover="changeImage(4, './images/tab/usage_over.gif');" onmouseout="changeImage(4, './images/tab/usage.gif');" /></a></td>

						<td align="left" width="85"><a href="./?CustomerID=<?php print $CustomerID;?>&pg=30"><img src="./images/tab/audit.gif" name="audit" border="0" id="audit" onmouseover="changeImage(5, './images/tab/audit_over.gif');" onmouseout="changeImage(5, './images/tab/audit.gif');"/></a></td>

						<td align="center" width="*" background="./images/tab_null.gif">&nbsp;</td>
					</tr>
				</table>
					<!-- end customer table menu -->
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
					<!-- Individual customer main page -->
					<table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%" align="left">
						<!--<tr>
							<td>
								<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=1">Customer information</a> | <a href="./?CustomerID=<?php print $CustomerID;?>&pg=5">Edit customer</a> | <a href="./?CustomerID=<?php print $CustomerID;?>&pg=7">Edit billing address</a> | <a href="#">Consolidate</a> | <a href="./?CustomerID=<?php print $CustomerID; ?>&pg=14">Edit address</a> | <a href="./?CustomerID=<?php print $CustomerID; ?>&pg=2">Add contact</a>
						</td>
						</tr>-->
						<tr>
							<!-- Customer information -->
							<td align="left" valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>CUSTOMER INFORMATION</b></td>
										<td align="right"><!--[<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=11">Detail</a>] -->[<a href="./?CustomerID=<?php print $CustomerID; ?>&pg=12">Edit</a>]</td>
									</tr>
									<tr>
										<td valign="top" colspan="2">
											<table border="1" cellpadding="4" cellspacing="0" width="100%" height="100%" class="formbody" bordercolor="#aaaaaa" style="border-collapse:collapse">
												<tr>
													<td align="left">Name:</td>
													<td align="left"><b><?php print $CustName; ?></b></td>
													<td align="left"><span class="khfont">ឈ្មោះ:</span></td>
													<td align="left"><span class="khfont"><b><?php print decodeUnicode($CustNameKhmer); ?></b></span></td>
												</tr>
												<tr>
													<td align="left" width="20">Category:</td>
													<td align="left" width="30%"><b><?php print $BusType; ?></b></td>
													<?php
														if($CustTypeID == 1){
															print '<td align="left"">Date of birth:</td>';
															print '<td align="left" width="30%"><b>'.formatDate($DOB, 3).'</b></td>';
														}else{
															print '<td align="left">Business No.:</td>';
															print '<td align="left" width="30%"><b>'.$BusinessReg.'</b></td>';
														}
													?>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Duplicate:</td>
													<td align="left"><b><?php print $Duplicate; ?></b></td>
													<td align="left" nowrap="nowrap">Registered date:</td>
													<td align="left"><b><?php print formatDate($RegisteredDate, 3); ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">VAT number:</td>
													<td align="left"><b><?php print $VATNumber; ?></b></td>
													<td align="left">Type:</td>
													<td align="left"><b><?php print $CustTypeName; ?></b></td>
												</tr>
												<tr>
													<td align="left" nowrap="nowrap">Telephone:</td>
													<td align="left"><b><?php print $Telephone; ?></b></td>
													<td align="left" nowrap="nowrap">Email:</td>
													<td align="left"><b><?php print $Email; ?></b></td>
												</tr>
												<tr>
													<td align="left" valign="top">Address:</td>
													<td align="left" colspan="3"><b><?php print $ResidentAddress; ?></b></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<!-- customer information -->
						<!-- contact information -->
						<tr>
							<td align="left" valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>CONTACT INFORMATION</b></td>
										<td align="right">[<a href="./?CustomerID=<?php print $CustomerID;?>&pg=18">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
												<thead>
													<th>No.</th>
													<th>Name</th>
													<td>Occupation</td>
													<th>Phone</th>
													<th>Email</th>
													<th>Date of birth</th>
													<th width="20">Edit</th>
												</thead>
												<tbody>
													<?php
														# ================== contact ===================
														$sql = "select co.ContactID, co.Salutation, co.ContactName, co.Phone, co.Email, co.DOB, ca.Career
																		from tblCustContact co, tlkpCareer ca
																		where co.OccupationID = ca.CareerID and co.CustID=$CustomerID";
														if($que = $mydb->sql_query($sql)){
															$iLoop = 1;
															while($result = $mydb->sql_fetchrow($que)){
																$ContactID = $result['ContactID'];
																$Salutation = $result['Salutation'];
																$ContactName = $result['ContactName'];
																$Career = $result['Career'];
																$PhoneNumber = $result['Phone'];
																$Email = $result['Email'];
																$DateOfBirth = $result['DOB'];
																$Contact = $Salutation." ".$ContactName;
																$edit = "<a href='./?CustomerID=".$CustomerID."&ContactID=".$ContactID."&pg=15'>Edit</a>";
																if(($iLoop % 2) == 0)
																	$style = "row1";
																else
																	$style = "row2";
																print "<tr>";
																print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
																print '<td class="'.$style.'" align="left">'.$Contact.'</td>';
																print '<td class="'.$style.'" align="left">'.$Career.'</td>';
																print '<td class="'.$style.'" align="left">'.$PhoneNumber.'</td>';
																print '<td class="'.$style.'" align="left">'.$Email.'</td>';
																print '<td class="'.$style.'" align="left">'.formatDate($DateOfBirth, 3).'</td>';
																print '<td class="'.$style.'" align="left">'.$edit.'</td>';
																print "</tr>";
																$iLoop += 1;
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
						<!-- contact information -->
						<!-- guarrenter information -->
						<tr>
							<td align="left" valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>GUARANTOR INFORMATION</b></td>
										<td align="right">[<a href="./?CustomerID=<?php print $CustomerID;?>&pg=19">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
												<thead>
													<th>No.</th>
													<th>Name</th>
													<td>Occupation</td>
													<th>Phone</th>
													<th>Email</th>
													<th>Date of birth</th>
													<th width="20">Edit</th>
												</thead>
												<tbody>
													<?php
														# ================== contact ===================
														$sql = "select gu.GuarrantorID, gu.Salutation, gu.GuarrantorName, gu.Phone, gu.Email, gu.DOB, ca.Career
																		from tblCustGuarrantor gu, tlkpCareer ca
																		where gu.OccupationID = ca.CareerID and gu.CustID=$CustomerID";
														if($que = $mydb->sql_query($sql)){
															$iLoop = 1;
															while($result = $mydb->sql_fetchrow($que)){
																$GuarrantorID = $result['GuarrantorID'];
																$Salutation = $result['Salutation'];
																$GuarrantorName = $result['GuarrantorName'];
																$Career = $result['Career'];
																$PhoneNumber = $result['Phone'];
																$Email = $result['Email'];
																$DateOfBirth = $result['DOB'];
																$Guarranter = $Salutation." ".$GuarrantorName;
																$edit = "<a href='./?CustomerID=".$CustomerID."&GuarrantorID=".$GuarrantorID."&pg=17'>Edit</a>";
																if(($iLoop % 2) == 0)
																	$style = "row1";
																else
																	$style = "row2";
																print "<tr>";
																print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
																print '<td class="'.$style.'" align="left">'.$Guarranter.'</td>';
																print '<td class="'.$style.'" align="left">'.$Career.'</td>';
																print '<td class="'.$style.'" align="left">'.$PhoneNumber.'</td>';
																print '<td class="'.$style.'" align="left">'.$Email.'</td>';
																print '<td class="'.$style.'" align="left">'.formatDate($DateOfBirth, 3).'</td>';
																print '<td class="'.$style.'" align="left">'.$edit.'</td>';
																print "</tr>";
																$iLoop += 1;
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
						<!-- contact information -->
						<!-- guarrenter information -->
						<tr>
							<td align="left" valign="top">
								<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
									<tr>
									 	<td align="left" class="formtitle"><b>DESIGNATE INFORMATION</b></td>
										<td align="right">[<a href="./?CustomerID=<?php print $CustomerID;?>&pg=20">Add</a>]</td>
									</tr>
									<tr>
										<td align="left" colspan="2">
											<table border="1" cellpadding="3" cellspacing="0" width="100%" id="1" class="sortable" bordercolor="#aaaaaa">
												<thead>
													<th>No.</th>
													<th>Name</th>
													<td>Occupation</td>
													<th>Phone</th>
													<th>Email</th>
													<th>Date of birth</th>
													<th width="20">Edit</th>
												</thead>
												<tbody>
													<?php
														# ================== contact ===================
														$sql = "select de.DesignateID, de.Salutation, de.DesignateName, de.Phone, de.Email, de.DOB, ca.Career
																		from tblCustDesignate de, tlkpCareer ca
																		where de.OccupationID = ca.CareerID and de.CustID=$CustomerID";
														if($que = $mydb->sql_query($sql)){
															$iLoop = 1;
															while($result = $mydb->sql_fetchrow($que)){
																$DesignateID = $result['DesignateID'];
																$Salutation = $result['Salutation'];
																$DesignateName = $result['DesignateName'];
																$Career = $result['Career'];
																$PhoneNumber = $result['Phone'];
																$Email = $result['Email'];
																$DateOfBirth = $result['DOB'];
																$Designate = $Salutation." ".$DesignateName;
																$edit = "<a href='./?CustomerID=".$CustomerID."&DesignateID=".$DesignateID."&pg=16'>Edit</a>";
																if(($iLoop % 2) == 0)
																	$style = "row1";
																else
																	$style = "row2";
																print "<tr>";
																print '<td class="'.$style.'" align="right">'.$iLoop.'</td>';
																print '<td class="'.$style.'" align="left">'.$Designate.'</td>';
																print '<td class="'.$style.'" align="left">'.$Career.'</td>';
																print '<td class="'.$style.'" align="left">'.$PhoneNumber.'</td>';
																print '<td class="'.$style.'" align="left">'.$Email.'</td>';
																print '<td class="'.$style.'" align="left">'.formatDate($DateOfBirth, 3).'</td>';
																print '<td class="'.$style.'" align="left">'.$edit.'</td>';
																print "</tr>";
																$iLoop += 1;
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
						<!-- contact information -->
						<tr>
							<td height="100%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>

</table>
<?php
# Close connection
$mydb->sql_close();
?>
