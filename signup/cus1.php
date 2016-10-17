<?php
	require_once("./common/agent.php");
	require_once("./common/class.audit.php");
	require_once("./common/class.invoice.array.php");
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

<?php

	function getService($ServiceID){
		global $mydb;
		$sql = "select ServiceName from tlkpService where ServiceID=$ServiceID";
		$que = $mydb->sql_query($sql);
		if($que){
			$rst = $mydb->sql_fetchrow($que);
			$ServiceName = $rst['ServiceName'];
		}
		$mydb->sql_freeresult();
		return $ServiceName;
	}

	if($ext == "ap")
	{
		$sql = "select Address,Street,CountryID,CityID,SangkatID,KhanID,l1.name CountryText,l2.name CityText,l4.name SangkatText,l3.name KhanText from tblCustomer
															ad left join tlkpLocation l1 on ad.CountryID = l1.id
																 left join tlkpLocation l2 on ad.CityID = l2.id
																 left join tlkpLocation l3 on ad.KhanID = l3.id
																 left join tlkpLocation l4 on ad.SangkatID = l4.id
				where ad.CustID=".$CustomerID;

		$que = $mydb->sql_query($sql);
		if($que){
			$rst = $mydb->sql_fetchrow($que);
			$aAddress = $rst['Address'];
			$aStreet = $rst['Street'];
			$aCountryID = $rst['CountryID'];
			$aCityID = $rst['CityID'];
			$aSangkatID = $rst['SangkatID'];
			$aKhanID = $rst['KhanID'];
			$aCountryText = $rst['CountryText'];
			$aCityText = $rst['CityText'];
			$aSangkatText = $rst['SangkatText'];
			$aKhanText = $rst['KhanText'];
		}
	}

	# ================= Save =======================
	if(!empty($smt) && isset($smt) && ($smt == "save")){


		# --------------- Audit ----------------------
		$audit = new Audit();

		//
		// Customer profile
		//
		$selCustSalutation = FixQuotes($selCusSalutation);
		$txtCustDOB = FixQuotes($txtCusDOB);
		$txtBilStreet = FixQuotes($txtBilStreet);
		$txtIntStreet = FixQuotes($txtIntStreet);
		$txtLeaStreet = FixQuotes($txtLeaStreet);
		$txtaccVATNumber = FixQuotes($txtaccVATNumber);
		$radaccExemption = FixQuotes($radaccExemption);

		$txtCustBusNo = FixQuotes($txtCusBus);
		$selCustDuplicateID = FixQuotes($selCusDuplicateID);
		$CustDuplicate = FixQuotes($txtCusDuplicate);

		$selCustNationality = FixQuotes($selCusNationality);
		$selCustOccupation = FixQuotes($selCusOccupation);

		$gpInvoice = FixQuotes($gpInvoice);

		if(trim($txtCustBusNo," ") != "")
		{
			$radCustType = 2;
		}
		else
		{
			$radCustType = 1;
		}


		$txtVATNumber = FixQuotes($txtVATNumber);

		$txtCustomerName = FixQuotes($txtCustomerName);
		$selCusCountry = FixQuotes($selCusCountry);
		$selCusCity = FixQuotes($selCusCity);
		$selCusKhan = FixQuotes($selCusKhan);
		$selCusSangkat = FixQuotes($selCusSangkat);
		$txtCusAddress = FixQuotes($txtCusAddress);
		$txtCusEmail = FixQuotes($txtCusEmail);
		$txtCusPhone = FixQuotes($txtCusPhone);
		$txtCusStreet = FixQuotes($txtCusStreet);
		$selBusinessType = FixQuotes($selBusinessType);
		$radExemption = 1;

		//
		//	Designate profile
		//
		$selDesSalutation = FixQuotes($selDesSalutation);
		$txtDesignateName = FixQuotes($txtDesignateName);
		$txtDesDOB = FixQuotes($txtDesDOB);
		$selDesDuplicateID = FixQuotes($selDesDuplicateID);
		$txtDesDuplicate = FixQuotes($txtDesDuplicate);
		$DesPhone = FixQuotes($txtDesPhone);
		$DesEmail = FixQuotes($txtDesEmail);
		$selDesNationality = FixQuotes($selDesNationality);
		$selDesOccupation = FixQuotes($selDesOccupation);
		$txtDesAddress = FixQuotes($txtDesAddress);
		$txtDesStreet = FixQuotes($txtDesStreet);
		$selDesSangkat = FixQuotes($selDesSangkat);
		$selDesKhan = FixQuotes($selDesKhan);
		$selDesCity = FixQuotes($selDesCity);
		$selDesCountry = FixQuotes($selDesCountry);

		//
		//	Guarranter profile
		//
		$selGuaSalutation = FixQuotes($selGuaSalutation);
		$txtGarrentorName = FixQuotes($txtGarrentorName);
		$txtGuaDOB = FixQuotes($txtGuaDOB);
		$selGuaDuplicateID = FixQuotes($selGuaDuplicateID);
		$txtGuaDuplicate = FixQuotes($txtGuaDuplicate);
		$GuaPhone = FixQuotes($txtGuaPhone);
		$GuaEmail = FixQuotes($txtGuaEmail);
		$selGuaNationality = FixQuotes($selGuaNationality);
		$selGuaOccupation = FixQuotes($selGuaOccupation);
		$txtGuaAddress = FixQuotes($txtGuaAddress);
		$txtGuaStreet = FixQuotes($txtGuaStreet);
		$selGuaSangkat = FixQuotes($selGuaSangkat);
		$selGuaKhan = FixQuotes($selGuaKhan);
		$selGuaCity = FixQuotes($selGuaCity);
		$selGuaCountry = FixQuotes($selGuaCountry);

		//
		//	Contact profile
		//
		$selConSalutation = FixQuotes($selConSalutation);
		$txtContactName = FixQuotes($txtContactName);
		$txtConDOB = FixQuotes($txtConDOB);
		$selConDuplicateID = FixQuotes($selConDuplicateID);
		$txtConDuplicate = FixQuotes($txtConDuplicate);
		$ConPhone = FixQuotes($txtConPhone);
		$ConEmail = FixQuotes($txtConEmail);
		$selonNationality = FixQuotes($selConNationality);
		$selConOccupation = FixQuotes($selConOccupation);
		$txtConAddress = FixQuotes($txtConAddress);
		$txtConStreet = FixQuotes($txtConStreet);
		$selConSangkat = FixQuotes($selConSangkat);
		$selConKhan = FixQuotes($selConKhan);
		$selConCity = FixQuotes($selConCity);
		$selConCountry = FixQuotes($selConCountry);

		//
		//	Addrss profile
		//
		$txtIntAddress = FixQuotes($txtIntAddress);
		$selIntSangkat = FixQuotes($selIntSangkat);
		$selIntKhan = FixQuotes($selIntKhan);
		$selIntCity = FixQuotes($selIntCity);
		$selIntCountry = FixQuotes($selIntCountry);

		$txtBilAddress = FixQuotes($txtBilAddress);
		$selBilSangkat = FixQuotes($selBilSangkat);
		$selBilKhan = FixQuotes($selBilKhan);
		$selBilCity = FixQuotes($selBilCity);
		$selBilCountry = FixQuotes($selBilCountry);
		$SelMessengerID = 1;
		$selBilInvoiceType = 2;
		$txtBilEmail = FixQuotes($txtBilEmail);

		//
		//	Service
		//
		$radConnection = FixQuotes($radConnection);

		//
		//	Account profile
		//
		$SubscriptionName = FixQuotes($SubscriptionName);
		// $SubscriptionNameKhmer = encodeUnicode($SubscriptionNameKhmer);
		$PackageID = FixQuotes($PackageID);
		$RegistrationFee = FixQuotes($RegistrationFee);
		$ConfigurationFee = FixQuotes($ConfigurationFee);
		$CPEFee = FixQuotes($CPEFee);
		$ISDNFee = FixQuotes($ISDNFee);
		$SPNFee = FixQuotes($SPNFee);
		$SelStationID = FixQuotes($SelStationID);
		$txtAccountName = FixQuotes($SelPhonePreset.$UserName);
		if($radConnection == 0)
		{
			$txtAccountName = FixQuotes($SelPhonePreset.$UserName);
		}
		else
		{
			$txtAccountName = FixQuotes($UserName);
		}

		$CPETypeID = FixQuotes($CPETypeID);
		$password = FixQuotes($password);
		$selSalesman = FixQuotes($selSalesman);
		$ncDeposit = FixQuotes($ncDeposit);
		if(empty($ncDeposit) || !isset($ncDeposit)) $ncDeposit = 0;
		$chkncDeposit = FixQuotes($chkncDeposit);
		if(empty($chkncDeposit) || !isset($chkncDeposit)) $chkncDeposit = 0;
		$icDeposit = FixQuotes($icDeposit);
		if(empty($icDeposit) || !isset($icDeposit)) $icDeposit = 0;
		$chkicDeposit = FixQuotes($chkicDeposit);
		if(empty($chkicDeposit) || !isset($chkicDeposit)) $chkicDeposit = 0;
		$mfDeposit = FixQuotes($mfDeposit);
		if(empty($mfDeposit) || !isset($mfDeposit)) $mfDeposit = 0;
		$chkmfDeposit = FixQuotes($chkmfDeposit);
		if(empty($chkmfDeposit) || !isset($chkmfDeposit)) $chkmfDeposit = 0;

		$vatRegistrationFee = FixQuotes($vatRegistrationFee);
		if((empty($vatRegistrationFee)) || (!isset($vatRegistrationFee))) $vatRegistrationFee = 0;
		$vatConfigurationFee = FixQuotes($vatConfigurationFee);
		if((empty($vatConfigurationFee)) || (!isset($vatConfigurationFee))) $vatConfigurationFee = 0;
		$vatCPEFee = FixQuotes($vatCPEFee);
		if((empty($vatCPEFee)) || (!isset($vatCPEFee))) $vatCPEFee = 0;
		$vatISDNFee = FixQuotes($vatISDNFee);
		if((empty($vatISDNFee)) || (!isset($vatISDNFee))) $vatISDNFee = 0;
		$vatSPNFee = FixQuotes($vatSPNFee);
		if((empty($vatSPNFee)) || (!isset($vatSPNFee))) $vatSPNFee = 0;

		#Encrypt password using md5
		//$Password = md5($Password);

		$now = date("Y/M/d H:i:s");
		$Operator = $user["FullName"];
		$StatusID = 0; # inactive

		$IsAddProduct = true;
		# --------------- Get station code ----------------
		$StationCode = substr(getConfigue('Billing Center'),0,3);

		$sql = "select ServiceID from tblTarPackage where packageID=".$PackageID;
		$que = $mydb->sql_query($sql);
		if($que){
			$rst = $mydb->sql_fetchrow($que);
			$ServiceID = $rst['ServiceID'];
		}

		if($ServiceID == 4)
		{
			$txtAccountName = $UserName."@leaseline";
		}
		else if($ServiceID == 3)
		{
			$txtAccountName = $UserName."@dialup";
		}
		else if($ServiceID == 8)
		{
			$txtAccountName = $UserName."@isdn";
		}
		else if($ServiceID == 1)
		{
			$txtAccountName = $UserName."@adsl";
		}


	if(isset($ext) && (!empty($ext)) && ($ext != "") && ($ext == "ap")){
	}else{

		//
		//	Get Customer id
		//
		$CustomerID = getID("Customer");
		$CustomerID = $StationCode.$CustomerID;
		//
		//	Add guarranter
		//
		$sql = "INSERT INTO tblCustGuarrantor(CustID, GuarrantorName, GuarrantorNameKhmer, Salutation, DOB, Address, AddressKhmer, KhanID, SangkatID, CityID, CountryID,
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate,Street, StreetKhmer)
						VALUES('".$CustomerID."', '".$txtGarrentorName."', ".encodeUnicode($txtGarrentorNameKhmer).", '".$selGuaSalutation."', '".$txtGuaDOB."',
									 '".$txtGuaAddress."', ".encodeUnicode($txtGuaAddressKhmer).", '".$selGuaKhan."', '".$selGuaSangkat."', '".$selGuaCity."',
									 '".$selGuaCountry."', '".$selGuaNationality."', '".$selGuaOccupation."', '".$GuaPhone."',
									 '".$GuaEmail."', '".$selGuaDuplicateID."', '".$txtGuaDuplicate."', '".$now."','".$txtGuaStreet."', ".encodeUnicode($txtGuaStreetKhmer).")";

		if($mydb->sql_query($sql, true)){
			$title = "Add new customer guarranter";
			$comment = "Add new customer guarranter information when first sign up new customer";
			$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
			//
			//	Add designate
			//
			$sql = "INSERT INTO tblCustDesignate(CustID, DesignateName, DesignateNameKhmer, Salutation, DOB, Address, AddressKhmer, KhanID, SangkatID, CityID, CountryID,
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate,Street, StreetKhmer)
						VALUES('".$CustomerID."', '".$txtDesignateName."', ".encodeUnicode($txtDesignateNameKhmer).", '".$selDesSalutation."', '".$txtDesDOB."',
									 '".$txtDesAddress."', ".encodeUnicode($txtDesAddressKhmer).", '".$selDesKhan."', '".$selDesSangkat."', '".$selDesCity."',
									 '".$selDesCountry."', '".$selDesNationality."', '".$selDesOccupation."', '".$DesPhone."',
									 '".$DesEmail."', '".$selDesDuplicateID."', '".$txtDesDuplicate."', '".$now."','".$txtDesStreet."', ".encodeUnicode($txtDesStreetKhmer).")";

			if($mydb->sql_query($sql, true)){
				$title = "Add new customer designate";
				$comment = "Add new customer designate information when first sign up new customer";
				$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
				//
				//	Add contact
				//
				$sql = "INSERT INTO tblCustContact(CustID, ContactName, ContactNameKhmer, Salutation, DOB, Address, AddressKhmer, KhanID, SangkatID, CityID, CountryID,
								NationalityID, OccupationID, Phone, Email, IdentityMode, IdentityData, SignupDate,Street, StreetKhmer)
						VALUES('".$CustomerID."', '".$txtContactName."', ".encodeUnicode($txtContactNameKhmer).", '".$selConSalutation."', '".$txtConDOB."',
									 '".$txtConAddress."', ".encodeUnicode($txtConAddressKhmer).", '".$selConKhan."', '".$selConSangkat."', '".$selConCity."',
									 '".$selConCountry."', '".$selConNationality."', '".$selConOccupation."', '".$ConPhone."',
									 '".$ConEmail."', '".$selConDuplicateID."', '".$txtConDuplicate."', '".$now."','".$txtConStreet."', ".encodeUnicode($txtConStreetKhmer).")";

				if($mydb->sql_query($sql, true)){
					$title = "Add new customer contact";
					$comment = "Add new customer contact information when first sign up new customer";
					$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
					//
					//	Customer profile
					//
					$sql = "INSERT INTO tblCustomer(CustID, CustName, CustNameKhmer, CustTypeID, VATNumber, IsVATException, RegisteredDate, IdentityData,
										InvoiceTypeID, NationalityID, Telephone, BillingEmail, OccupationID, BusinessReg, MessengerID,
										IdentityMode, DOB, Salutation, Address, AddressKhmer, SangkatID, KhanID, CityID, CountryID, IsAccGroup,
										Category, Email, Street, StreetKhmer, Context)
										VALUES('".$CustomerID."', '".$txtCustomerName."' ,".encodeUnicode($txtCustomerNameKhmer).", '".$radCustType."', '".$txtVATNumber."',
													 '".$radExemption."', '".$now."', '".$CustDuplicate."', '".$selBilInvoiceType."',
													 '".$selCustNationality."', '".$txtCusPhone."', '".$txtBilEmail."', '".$selCustOccupation."',
													 '".$txtCusBus."', '".$SelMessengerID."', '".$selCustDuplicateID."', '".$txtCustDOB."',
													 '".$selCustSalutation."', '".$txtCusAddress."', ".encodeUnicode($txtCusAddressKhmer).", '".$selCusSangkat."', '".$selCusKhan."',
													 '".$selCusCity."', '".$selCusCountry."', '0', '".$selBusinessType."', '".$txtCusEmail."','".$txtCusStreet."', ".encodeUnicode($txtCusStreetKhmer).",'Adding New Customer From system')";

					if($mydb->sql_query($sql, true)){
						$title = "Add new customer information";
						$comment = "Sign up new customer information.";
						$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);

					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to add new customer information.", $error['message']);
						$IsAddProduct = false;

					}	#	end add customer info
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add customer contact.", $error['message']);
					$IsAddProduct = false;
				}	#	end add contact
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to add customer designate.", $error['message']);
				$IsAddProduct = false;
			} # end add designate
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add customer guarranter.", $error['message']);
			$IsAddProduct = false;
		} # end add guarranter
	}	# end add customer
	if($IsAddProduct){
			# --------------- Get account id --------------------------
			$AccountID = getID("Account");
			$AccountID = $StationCode.$AccountID;
			//
			//	Installation address
			//

			$khmerAddress = encodeUnicode($txtIntAddressKhmer . ', ' . $txtIntStreetKhmer);

			$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, AddressKhmer, SangkatID, KhanID, CityID, CountryID, AccID)
							VALUES('".$CustomerID."', 0, '".$txtIntAddress.", ".$txtIntStreet."', ".$khmerAddress.", '".$selIntSangkat."', '".$selIntKhan."', '".$selIntCity."',
										 '".$selIntCountry."', '".$AccountID."')";

			if($mydb->sql_query($sql, true)){
				$title = "Add new installation address";
				$comment = "Add new customer installation address when first sign up new customer";
				$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
				//
				//	Billing address
				//

				$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, AddressKhmer, SangkatID, KhanID, CityID, CountryID, AccID)
								VALUES('".$CustomerID."', 1, '".$txtBilAddress.", ".$txtBilStreet."', ".$khmerAddress.", '".$selBilSangkat."', '".$selBilKhan."', '".$selBilCity."',
											 '".$selBilCountry."', '".$AccountID."')";

				if($mydb->sql_query($sql, true)){
					$title = "Add new billing address";
					$comment = "Add new customer billing address when first sign up new customer";
					$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);

					# lease line
					if($radConnection == 2){
						$sql = "INSERT INTO tblCustAddress(CustID, IsBillingAddress, Address, AddressKhmer, SangkatID, KhanID, CityID, CountryID, AccID)
								VALUES('".$CustomerID."', 2, '".$txtLeaAddress.", ".$txtLeaStreet."', ".$khmerAddress.", '".$selLeaSangkat."', '".$selLeaKhan."', '".$selLeaCity."',
											 '".$selLeaCountry."', '".$AccountID."')";

						if($mydb->sql_query($sql, true)){
							$title = "Add new installation to address for lease line";
							$comment = "Add new customer installation address for lease line account. Lease line from is installation address.";
							$audit->AddAudit($CustomerID, "", $title, $comment, $Operator, 1, 1);
						}
					}
					//
					//	Add new account
					//

					$addressBI = 'bHouseKhmer, bStreetKhmer, iHouse1Khmer, iStreet1Khmer';
					$paramsAddressBI = encodeUnicode($txtBilAddressKhmer) .','. encodeUnicode($txtBilStreetKhmer).','. encodeUnicode($txtIntStreetKhmer) .','. encodeUnicode($txtIntStreetKhmer);

					$addressBII = $addressBI . ', iHouse2Khmer, iStreet2Khmer';
					$paramsAddressBII = $paramsAddressBI . ',' . encodeUnicode($txtLeaAddressKhmer) .','. encodeUnicode($txtLeaStreetKhmer);

					if($radConnection != 2)
					{
						$sql = "INSERT INTO tblTrackAccount(AccID, PackageID, UserName, Password, StatusID, RegDate,
											RegBy, SubscriptionName, SubscriptionNameKhmer, Track,Context,
											bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,$addressBI
											)
											VALUES('".$AccountID."', '".$PackageID."', '".$txtAccountName."', '".$Password."',
														 '".$StatusID."', '".$now."', '".$Operator."', '".$SubscriptionName."', ".encodeUnicode($SubscriptionNameKhmer).",
														 'New','Adding New from Billing System',
														 '".$txtBilAddress."', '".$txtBilStreet."', '".$selBilSangkat."', '".$selBilKhan."', '".$selBilCity."', '".$selBilCountry."',
														 '".$txtIntAddress."', '".$txtIntStreet."', '".$selIntSangkat."', '".$selIntKhan."', '".$selIntCity."',	'".$selIntCountry."',
														 $paramsAddressBI
														 )";

					}
					else
					{
						$sql = "INSERT INTO tblTrackAccount(AccID, PackageID, UserName, Password, StatusID, RegDate,
											RegBy, SubscriptionName, SubscriptionNameKhmer, Track,Context,
											bHouse,bStreet,bSangkatID,bKhanID,bCityID,bCountryID,iHouse1,iStreet1,iSangkatID1,iKhanID1,iCityID1,iCountryID1,
											iHouse2,iStreet2,iSangkatID2,iKhanID2,iCityID2,iCountryID2,$addressBII
											)
											VALUES('".$AccountID."', '".$PackageID."', '".$txtAccountName."', '".$Password."',
														 '".$StatusID."', '".$now."', '".$Operator."', '".$SubscriptionName."', ".encodeUnicode($SubscriptionNameKhmer).",
														 'New','Adding New from Billing System',
														 '".$txtBilAddress."', '".$txtBilStreet."', '".$selBilSangkat."', '".$selBilKhan."', '".$selBilCity."', '".$selBilCountry."',
														 '".$txtIntAddress."', '".$txtIntStreet."', '".$selIntSangkat."', '".$selIntKhan."', '".$selIntCity."',	'".$selIntCountry."',
														 '".$txtLeaAddress."', '".$txtLeaStreet."', '".$selLeaSangkat."', '".$selLeaKhan."', '".$selLeaCity."', '".$selLeaCountry."',
														 $paramsAddressBII
														 )";
					}

					$que = $mydb->sql_query($sql);

					$sql = "select Max(TrackID) TrackID from tblTrackAccount where accID='".$AccountID."'";
					$que = $mydb->sql_query($sql);
					while($rst = $mydb->sql_fetchrow($que))
					{
						$TrackID = $rst['TrackID'];

					}


					$sql = "INSERT INTO tblCustProduct(AccID, CustID, PackageID, UserName, Password, SalePersonID, StatusID, SetupDate,
										CreatedBy, SubscriptionName, SubscriptionNameKhmer, NoBillRun, Score, StationID, IsNCDeposit, IsICDeposit, IsMFDeposit, InvoiceTypeID, MessengerID,
										BillingEmail,Track,MaxTrackID,bHouse,bHouseKhmer,bStreet,bStreetKhmer,bSangkatID,bKhanID,bCityID,bCountryID,VATnumber,IsVATException,GrpInvID)
										VALUES('".$AccountID."', '".$CustomerID."', '".$PackageID."', '".$txtAccountName."', '".$Password."',
													 '".$selSalesman."', '".$StatusID."', '".$now."', '".$Operator."', '".$SubscriptionName."', ".encodeUnicode($SubscriptionNameKhmer).",
													 0, 0, '".$SelStationID."', '".$chkncDeposit."', '".$chkicDeposit."', '".$chkmfDeposit."', '".$selBilInvoiceType.
													 "', '".$SelMessengerID."', '".$txtBilEmail."','New',".$TrackID.",'".$txtBilAddress."',".encodeUnicode($txtBilAddressKhmer).", '".$txtBilStreet."', ".encodeUnicode($txtBilStreetKhmer).", '".
													 $selBilSangkat."', '".$selBilKhan."', '".$selBilCity."', '".$selBilCountry."','".$txtaccVATNumber."',".
													 $radaccExemption.",".$gpInvoice.")";

					if($mydb->sql_query($sql, true)){
						# Insert into product status history as status changed
						$sql = "INSERT INTO tblAccStatusHistory(AccID, StatusID, ChangeDate, OtherID, OtherText)
										VALUES(".$AccountID.", 0, '".$now."', 0, 'New subscribe')";
						$mydb->sql_query($sql);
						# Create account supplement
						$sql = "INSERT INTO tblProductStatus(AccID, Incoming, Outgoing, International, IncomingLoc,
																IncomingNat, OutgoingLoc, OutgoingNat, Other)
													VALUES($AccountID, 0, 0, 0, 0, 0, 0, 0, 0)";
						$mydb->sql_query($sql);

						# set cpe use
						$sql = "INSERT INTO tblCPEUsed(AccID, CPEID, UsedDate) VALUES(".$AccountID.", ".$CPETypeID.", '".$now."')";
						$mydb->sql_query($sql);

						$title = "Add new account";
						$comment = "Signup new account with package: $txtAccPackage";
						$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $Operator, 1, 1);

							//
							//	Create invoice
							//
							$Finance = new Invoice();
							//$InvoiceID = $Finance->GetInvoiceID();
							//
							//	Add required deposit
							//

							$retOut = $Finance->URDeposit($CustomerID, $AccountID, $ncDeposit, $icDeposit, $mfDeposit);

							if($retOut){

								$title = "Add product require deposit";
								$comment = "Set require National Call Deposit: ".FormatCurrency($ncDeposit)."; International Call Deposit:
													 ".FormatCurrency($icDeposit)."; Monthly Fee Deposit: ".FormatCurrency($mfDeposit);
								$audit->AddAudit($CustomerID, $AccountID, $title, $comment, $Operator, 1, 8);

								// redirect('./?CustomerID='.$CustomerID.'&pg=10&TrackID='.$TrackID);

						}	# end add deposit
					}else{
						$error = $mydb->sql_error();
						$retOut = $myinfo->error("Failed to add new account.", $error['message']);
					}	# end add account
				}else{
					$error = $mydb->sql_error();
					$retOut = $myinfo->error("Failed to add customer product.", $error['message'].$sql);
				}
			}else{
				$error = $mydb->sql_error();
				$retOut = $myinfo->error("Failed to add billing address.", $error['message']);
				$IsAddProduct = false;
			}	#	end add billing address
		}else{
			$error = $mydb->sql_error();
			$retOut = $myinfo->error("Failed to add installation address.", $error['message'].$sql);
			$IsAddProduct = false;
		}	#	end add installation address
	}

?>
<script language="JavaScript" src="./javascript/date.js"></script>
<script language="JavaScript" src="./javascript/validphone.js"></script>
<script language="JavaScript" src="./javascript/ajax_location.js"></script>
<script language="JavaScript" src="./javascript/ajax_get_package.js"></script>
<script language="javascript" src="./javascript/ajax_signup_checkusername.js"></script>
<script language="javascript" src="./javascript/ajax_signup_getserviceID.js"></script>
<script language="javascript">

	function storeNameValue(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selCusSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selCusKhan.options[index].text;
			location(4, fsignupcust1.selCusKhan.options[index].value, "selCusSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selCusCity.options[index].text;
			location(3, fsignupcust1.selCusCity.options[index].value, "selCusKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selCusCountry.options[index].text;
			location(2, fsignupcust1.selCusCountry.options[index].value, "selCusCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selDesSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selDesKhan.options[index].text;
			location(4, fsignupcust1.selDesKhan.options[index].value, "selDesSangkat");
		}
		else if(cat == 13){
			location(3, fsignupcust1.selDesCity.options[index].value, "selDesKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selDesCountry.options[index].text;
			location(2, fsignupcust1.selDesCountry.options[index].value, "selDesCity");
		}

	}


	function storeNameValueGua(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selGuaSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selGuaKhan.options[index].text;
			location(4, fsignupcust1.selGuaKhan.options[index].value, "selGuaSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selGuaCity.options[index].text;
			location(3, fsignupcust1.selGuaCity.options[index].value, "selGuaKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selGuaCountry.options[index].text;
			location(2, fsignupcust1.selGuaCountry.options[index].value, "selGuaCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selGuaSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selGuaKhan.options[index].text;
			location(4, fsignupcust1.selGuaKhan.options[index].value, "selGuaSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selGuaCity.options[index].text;
			location(3, fsignupcust1.selGuaCity.options[index].value, "selGuaKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selGuaCountry.options[index].text;
			location(2, fsignupcust1.selGuaCountry.options[index].value, "selGuaCity");
		}

	}

	function storeNameValueCon(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selConSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selConKhan.options[index].text;
			location(4, fsignupcust1.selConKhan.options[index].value, "selConSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selConCity.options[index].text;
			location(3, fsignupcust1.selConCity.options[index].value, "selConKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selConCountry.options[index].text;
			location(2, fsignupcust1.selConCountry.options[index].value, "selConCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selConSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selConKhan.options[index].text;
			location(4, fsignupcust1.selConKhan.options[index].value, "selConSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selConCity.options[index].text;
			location(3, fsignupcust1.selConCity.options[index].value, "selConKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selConCountry.options[index].text;
			location(2, fsignupcust1.selConCountry.options[index].value, "selConCity");
		}

	}


	function storeNameValueIns(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selIntSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selIntKhan.options[index].text;
			location(4, fsignupcust1.selIntKhan.options[index].value, "selIntSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selIntCity.options[index].text;
			location(3, fsignupcust1.selIntCity.options[index].value, "selIntKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selIntCountry.options[index].text;
			location(2, fsignupcust1.selIntCountry.options[index].value, "selIntCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selIntSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selIntKhan.options[index].text;
			location(4, fsignupcust1.selIntKhan.options[index].value, "selIntSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selIntCity.options[index].text;
			location(3, fsignupcust1.selIntCity.options[index].value, "selIntKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selIntCountry.options[index].text;
			location(2, fsignupcust1.selIntCountry.options[index].value, "selIntCity");
		}

	}

	function get_package(s)
	{
		getpackage(s,"PackageID");
		if(s!=0)
		{
			fsignupcust1.SelPhonePreset.disabled=true;
		}
		else
		{
			fsignupcust1.SelPhonePreset.disabled=false;
		}


	}

	function storeNameValueLea(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selLeaSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selLeaKhan.options[index].text;
			location(4, fsignupcust1.selLeaKhan.options[index].value, "selLeaSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selLeaCity.options[index].text;
			location(3, fsignupcust1.selLeaCity.options[index].value, "selLeaKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selLeaCountry.options[index].text;
			location(2, fsignupcust1.selLeaCountry.options[index].value, "selLeaCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selLeaSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selLeaKhan.options[index].text;
			location(4, fsignupcust1.selLeaKhan.options[index].value, "selLeaSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selLeaCity.options[index].text;
			location(3, fsignupcust1.selLeaCity.options[index].value, "selLeaKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selLeaCountry.options[index].text;
			location(2, fsignupcust1.selLeaCountry.options[index].value, "selLeaCity");
		}

	}

	function storeNameValueBil(index, cat){
		if(cat == 5){
			fsignupcust1.txtCusSangkat.value = fsignupcust1.selBilSangkat.options[index].text;
		}
		else if(cat == 6){
			fsignupcust1.txtCusKhan.value = fsignupcust1.selBilKhan.options[index].text;
			location(4, fsignupcust1.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 7){
			fsignupcust1.txtCusCity.value = fsignupcust1.selBilCity.options[index].text;
			location(3, fsignupcust1.selBilCity.options[index].value, "selBilKhan");
		}
		else if(cat == 8){
			fsignupcust1.txtCusCountry.value = fsignupcust1.selBilCountry.options[index].text;
			location(2, fsignupcust1.selBilCountry.options[index].value, "selBilCity");
		}
		else if(cat == 11){
			fsignupcust1.txtDesSangkat.value = fsignupcust1.selBilSangkat.options[index].text;
		}
		else if(cat == 12){
			fsignupcust1.txtDesKhan.value = fsignupcust1.selBilKhan.options[index].text;
			location(4, fsignupcust1.selBilKhan.options[index].value, "selBilSangkat");
		}
		else if(cat == 13){
			fsignupcust1.txtDesCity.value = fsignupcust1.selBilCity.options[index].text;
			location(3, fsignupcust1.selBilCity.options[index].value, "selBilKhan");
		}
		else if(cat == 14){
			fsignupcust1.txtDesCountry.value = fsignupcust1.selBilCountry.options[index].text;
			location(2, fsignupcust1.selBilCountry.options[index].value, "selBilCity");
		}

	}

	function Leaseoption(){
		if(fsignupcust1.radConnection[2].checked)
		{
			document.getElementById("inst2").style.display = "block";
			fsignupcust1.gpInvoice.selectedIndex = 3;
		}
		else
		{
			document.getElementById("inst2").style.display = "none";
			fsignupcust1.gpInvoice.selectedIndex = 0;
		}
	}


	function setTheSameOther(index){

		if(index == 1){
			if(fsignupcust1.thesame1.checked == true){
				fsignupcust1.selConSalutation.options[fsignupcust1.selCusSalutation.selectedIndex].selected = "selected";
				fsignupcust1.selConDuplicateID.options[fsignupcust1.selCusDuplicateID.selectedIndex].selected = "selected";
				fsignupcust1.selConOccupation.options[fsignupcust1.selCusOccupation.selectedIndex].selected = "selected";
				fsignupcust1.selConNationality.options[fsignupcust1.selCusNationality.selectedIndex].selected = "selected";

				fsignupcust1.txtContactName.value = fsignupcust1.txtCustomerName.value;
				fsignupcust1.txtContactNameKhmer.value = fsignupcust1.txtCustomerNameKhmer.value;
				fsignupcust1.txtConDOB.value = fsignupcust1.txtCusDOB.value;

				fsignupcust1.txtConDuplicate.value = fsignupcust1.txtCusDuplicate.value;
				fsignupcust1.txtConPhone.value = fsignupcust1.txtCusPhone.value;
				fsignupcust1.txtConEmail.value = fsignupcust1.txtCusEmail.value;
				fsignupcust1.txtConAddress.value = fsignupcust1.txtCusAddress.value;
				fsignupcust1.txtConAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
				fsignupcust1.txtConStreet.value = fsignupcust1.txtCusStreet.value;
				fsignupcust1.txtConStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;

				fsignupcust1.selConCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
				fsignupcust1.selConCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
				fsignupcust1.selConCountry.options[0].selected = "selected";

				fsignupcust1.selConCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
				fsignupcust1.selConCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
				fsignupcust1.selConCity.options[0].selected = "selected";

				fsignupcust1.selConKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
				fsignupcust1.selConKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
				fsignupcust1.selConKhan.options[0].selected = "selected";

				fsignupcust1.selConSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
				fsignupcust1.selConSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
				fsignupcust1.selConSangkat.options[0].selected = "selected";

			}else{

					fsignupcust1.selConSalutation[0].value = "Mr.";
					fsignupcust1.selConSalutation[0].text = "Mr.";
					fsignupcust1.selConSalutation[0].selected = "selected";

					fsignupcust1.txtContactName.value = "";
					fsignupcust1.txtContactNameKhmer.value = "";
					fsignupcust1.txtConDOB.value = "";

					fsignupcust1.selConDuplicateID[0].value = "ID Card";
					fsignupcust1.selConDuplicateID[0].text = "ID Card";
					fsignupcust1.selConDuplicateID[0].selected = "selected";

					fsignupcust1.txtConDuplicate.value = "";
					fsignupcust1.txtConPhone.value = "";
					fsignupcust1.txtConEmail.value = "";

					fsignupcust1.selConNationality[0].value = 38;
					fsignupcust1.selConNationality[0].text = "Cambodian";
					fsignupcust1.selConNationality[0].selected = "selected";

					fsignupcust1.selConOccupation[0].value = 1;
					fsignupcust1.selConOccupation[0].text = "unknown";
					fsignupcust1.selConOccupation[0].selected = "selected";

					fsignupcust1.txtConAddress.value = "";
					fsignupcust1.txtConStreet.value = "";
					fsignupcust1.txtConStreetKhmer.value = "";

					fsignupcust1.selConSangkat[0].value = 0;
					fsignupcust1.selConSangkat[0].text = "Unknown";
					fsignupcust1.selConSangkat[0].selected = "selected";

					fsignupcust1.selConKhan[0].value = 0;
					fsignupcust1.selConKhan[0].text = "Unknown";
					fsignupcust1.selConKhan[0].selected = "selected";

					fsignupcust1.selConCity[0].value = 0;
					fsignupcust1.selConCity[0].text = "Unknown";
					fsignupcust1.selConCity[0].selected = "selected";

					fsignupcust1.selConCountry[0].value = 0;
					fsignupcust1.selConCountry[0].text = "Unknown";
					fsignupcust1.selConCountry[0].selected = "selected";


			}
		}else{
			if(index == 2){
				if(fsignupcust1.thesame2.checked == true){

					fsignupcust1.txtGarrentorName.value = fsignupcust1.txtCustomerName.value;
					fsignupcust1.txtGarrentorNameKhmer.value = fsignupcust1.txtCustomerNameKhmer.value;
					fsignupcust1.txtGuaDOB.value = fsignupcust1.txtCusDOB.value;

					fsignupcust1.txtGuaDuplicate.value = fsignupcust1.txtCusDuplicate.value;
					fsignupcust1.txtGuaPhone.value = fsignupcust1.txtCusPhone.value;
					fsignupcust1.txtGuaEmail.value = fsignupcust1.txtCusEmail.value;
					fsignupcust1.txtGuaAddress.value = fsignupcust1.txtCusAddress.value;
					fsignupcust1.txtGuaAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
					fsignupcust1.txtGuaStreet.value = fsignupcust1.txtCusStreet.value;
					fsignupcust1.txtGuaStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;

					fsignupcust1.selGuaSalutation.options[fsignupcust1.selCusSalutation.selectedIndex].selected = "selected";
					fsignupcust1.selGuaDuplicateID.options[fsignupcust1.selCusDuplicateID.selectedIndex].selected = "selected";
					fsignupcust1.selGuaOccupation.options[fsignupcust1.selCusOccupation.selectedIndex].selected = "selected";
					fsignupcust1.selGuaNationality.options[fsignupcust1.selCusNationality.selectedIndex].selected = "selected";

					fsignupcust1.selGuaCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
					fsignupcust1.selGuaCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
					fsignupcust1.selGuaCountry.options[0].selected = "selected";

					fsignupcust1.selGuaCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
					fsignupcust1.selGuaCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
					fsignupcust1.selGuaCity.options[0].selected = "selected";

					fsignupcust1.selGuaKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
					fsignupcust1.selGuaKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
					fsignupcust1.selGuaKhan.options[0].selected = "selected";

					fsignupcust1.selGuaSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
					fsignupcust1.selGuaSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
					fsignupcust1.selGuaSangkat.options[0].selected = "selected";

				}else{

						fsignupcust1.selGuaSalutation[0].value = "Mr.";
						fsignupcust1.selGuaSalutation[0].text = "Mr.";
						fsignupcust1.selGuaSalutation[0].selected = "selected";

					fsignupcust1.txtGarrentorName.value = "";
					fsignupcust1.txtGarrentorNameKhmer.value = "";
					fsignupcust1.txtGuaDOB.value = "";

					fsignupcust1.selGuaDuplicateID[0].value = "ID Card";
					fsignupcust1.selGuaDuplicateID[0].text = "ID Card";
					fsignupcust1.selGuaDuplicateID[0].selected = "selected";

					fsignupcust1.txtGuaDuplicate.value = "";
					fsignupcust1.txtGuaPhone.value = "";
					fsignupcust1.txtGuaEmail.value = "";

					fsignupcust1.selGuaNationality[0].value = 38;
					fsignupcust1.selGuaNationality[0].text = "Cambodian";
					fsignupcust1.selGuaNationality[0].selected = "selected";

					fsignupcust1.selGuaOccupation[0].value = 1;
					fsignupcust1.selGuaOccupation[0].text = "Unknown";
					fsignupcust1.selGuaOccupation[0].selected = "selected";

					fsignupcust1.txtGuaAddress.value = "";
					fsignupcust1.txtGuaAddressKhmer.value = "";
					fsignupcust1.txtGuaStreet.value = "";
					fsignupcust1.txtGuaStreetKhmer.value = "";

					fsignupcust1.selGuaSangkat[0].value = 0;
					fsignupcust1.selGuaSangkat[0].text = "Unknown";
					fsignupcust1.selGuaSangkat[0].selected = "selected";

					fsignupcust1.selGuaKhan[0].value = 0;
					fsignupcust1.selGuaKhan[0].text = "Unknown";
					fsignupcust1.selGuaKhan[0].selected = "selected";

					fsignupcust1.selGuaCity[0].value = 0;
					fsignupcust1.selGuaCity[0].text = "Unknown";
					fsignupcust1.selGuaCity[0].selected = "selected";

					fsignupcust1.selGuaCountry[0].value = 0;
					fsignupcust1.selGuaCountry[0].text = "Unknown";
					fsignupcust1.selGuaCountry[0].selected = "selected";
				}
			}
		}
	}


	function setTheSame(){

		if(fsignupcust1.thesame.checked == true){

				fsignupcust1.txtDesignateName.value = fsignupcust1.txtCustomerName.value;
				fsignupcust1.txtDesignateNameKhmer.value = fsignupcust1.txtCustomerNameKhmer.value;
				fsignupcust1.txtDesAddress.value = fsignupcust1.txtCusAddress.value;
				fsignupcust1.txtDesAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
				fsignupcust1.txtDesStreet.value = fsignupcust1.txtCusStreet.value;
				fsignupcust1.txtDesStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;
				fsignupcust1.selDesSalutation.options[fsignupcust1.selCusSalutation.selectedIndex].selected = "selected";
				fsignupcust1.selDesDuplicateID.options[fsignupcust1.selCusDuplicateID.selectedIndex].selected = "selected";
				fsignupcust1.selDesOccupation.options[fsignupcust1.selCusOccupation.selectedIndex].selected = "selected";
				fsignupcust1.selDesNationality.options[fsignupcust1.selCusNationality.selectedIndex].selected = "selected";

				fsignupcust1.selDesCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
				fsignupcust1.selDesCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
				fsignupcust1.selDesCountry.options[0].selected = "selected";

				fsignupcust1.selDesCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
				fsignupcust1.selDesCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
				fsignupcust1.selDesCity.options[0].selected = "selected";

				fsignupcust1.selDesKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
				fsignupcust1.selDesKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
				fsignupcust1.selDesKhan.options[0].selected = "selected";

				fsignupcust1.selDesSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
				fsignupcust1.selDesSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
				fsignupcust1.selDesSangkat.options[0].selected = "selected";

				fsignupcust1.txtDesDOB.value = fsignupcust1.txtCusDOB.value;
				fsignupcust1.txtDesPhone.value = fsignupcust1.txtCusPhone.value;
				fsignupcust1.txtDesEmail.value = fsignupcust1.txtCusEmail.value;
				fsignupcust1.txtDesDuplicate.value = fsignupcust1.txtCusDuplicate.value;
		}else{
			fsignupcust1.txtDesignateName.value = "";
			fsignupcust1.txtDesignateNameKhmer.value = "";
			fsignupcust1.txtDesAddress.value = "";
			fsignupcust1.txtDesAddressKhmer.value = "";
			fsignupcust1.selDesSalutation.options[0].selected = "selected";
			fsignupcust1.selDesDuplicateID.options[0].selected = "selected";
			fsignupcust1.selDesOccupation.options[0].selected = "selected";
			fsignupcust1.selDesNationality.options[0].selected = "selected";

			fsignupcust1.selDesCountry.options[0].text = "Unknown";
			fsignupcust1.selDesCountry.options[0].value = 0;
			fsignupcust1.selDesCountry.options[0].selected = "selected";

			fsignupcust1.selDesCity.options[0].text = "Unknown";
			fsignupcust1.selDesCity.options[0].value = 0;
			fsignupcust1.selDesCity.options[0].selected = "selected";

			fsignupcust1.selDesKhan.options[0].text = "Unknown";
			fsignupcust1.selDesKhan.options[0].value = 0;
			fsignupcust1.selDesKhan.options[0].selected = "selected";

			fsignupcust1.selDesSangkat.options[0].text = "Unknown";
			fsignupcust1.selDesSangkat.options[0].value = 0;
			fsignupcust1.selDesSangkat.options[0].selected = "selected";

			fsignupcust1.txtDesDOB.value = "";
			fsignupcust1.txtDesPhone.value = "";
			fsignupcust1.txtDesEmail.value = "";
			fsignupcust1.txtDesDuplicate.value = "";
		}
	}

	function setTheSameAddress(index){

		if(index == 4){
			if((fsignupcust1.ext.value == "ap") && (fsignupcust1.thesame4.checked == true)){

				fsignupcust1.txtIntAddress.value = fsignupcust1.aAddress.value;
				fsignupcust1.txtIntAddressKhmer.value = fsignupcust1.aAddressKhmer.value;
				fsignupcust1.txtIntStreet.value = fsignupcust1.aStreet.value;
				fsignupcust1.txtIntStreetKhmer.value = fsignupcust1.aStreetKhmer.value;

				fsignupcust1.selIntCountry.options[0].text = fsignupcust1.aCountryText.value;
				fsignupcust1.selIntCountry.options[0].value = fsignupcust1.aCountry.value;
				fsignupcust1.selIntCountry.options[0].selected = "selected";

				fsignupcust1.selIntCity.options[0].text = fsignupcust1.aCityText.value;
				fsignupcust1.selIntCity.options[0].value = fsignupcust1.aCity.value;
				fsignupcust1.selIntCity.options[0].selected = "selected";

				fsignupcust1.selIntKhan.options[0].text = fsignupcust1.aKhanText.value;
				fsignupcust1.selIntKhan.options[0].value = fsignupcust1.aKhan.value;
				fsignupcust1.selIntKhan.options[0].selected = "selected";

				fsignupcust1.selIntSangkat.options[0].text = fsignupcust1.aSangkatText.value;
				fsignupcust1.selIntSangkat.options[0].value = fsignupcust1.aSangkat.value;
				fsignupcust1.selIntSangkat.options[0].selected = "selected";

			}
			else if(fsignupcust1.thesame4.checked == true){

				fsignupcust1.txtIntAddress.value = fsignupcust1.txtCusAddress.value;
				fsignupcust1.txtIntAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
				fsignupcust1.txtIntStreet.value = fsignupcust1.txtCusStreet.value;
				fsignupcust1.txtIntStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;

				fsignupcust1.selIntCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
				fsignupcust1.selIntCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
				fsignupcust1.selIntCountry.options[0].selected = "selected";

				fsignupcust1.selIntCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
				fsignupcust1.selIntCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
				fsignupcust1.selIntCity.options[0].selected = "selected";

				fsignupcust1.selIntKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
				fsignupcust1.selIntKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
				fsignupcust1.selIntKhan.options[0].selected = "selected";

				fsignupcust1.selIntSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
				fsignupcust1.selIntSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
				fsignupcust1.selIntSangkat.options[0].selected = "selected";

			}else{
				fsignupcust1.txtIntAddress.value = "";
				fsignupcust1.txtIntAddressKhmer.value = "";
				fsignupcust1.txtIntStreet.value = "";
				fsignupcust1.txtIntStreetKhmer.value = "";

				fsignupcust1.selIntSangkat[0].value = 0;
				fsignupcust1.selIntSangkat[0].text = "Unknown";
				fsignupcust1.selIntSangkat[0].selected = "selected";

				fsignupcust1.selIntKhan[0].value = 0;
				fsignupcust1.selIntKhan[0].text = "Unknown";
				fsignupcust1.selIntKhan[0].selected = "selected";

				fsignupcust1.selIntCity[0].value = 0;
				fsignupcust1.selIntCity[0].text = "Unknown";
				fsignupcust1.selIntCity[0].selected = "selected";

				fsignupcust1.selIntCountry[0].value = 0;
				fsignupcust1.selIntCountry[0].text = "Unknown";
				fsignupcust1.selIntCountry[0].selected = "selected";
			}
		}else{
			if(index == 3){
				if((fsignupcust1.ext.value == "ap") && (fsignupcust1.thesame3.checked == true)){

					fsignupcust1.txtBilStreet.value = fsignupcust1.aStreet.value;
					fsignupcust1.txtBilStreetKhmer.value = fsignupcust1.aStreetKhmer.value;
					fsignupcust1.txtBilAddress.value = fsignupcust1.aAddress.value;
					fsignupcust1.txtBilAddressKhmer.value = fsignupcust1.aAddressKhmer.value;

					fsignupcust1.selBilCountry.options[0].text = fsignupcust1.aCountryText.value;
					fsignupcust1.selBilCountry.options[0].value = fsignupcust1.aCountry.value;
					fsignupcust1.selBilCountry.options[0].selected = "selected";

					fsignupcust1.selBilCity.options[0].text = fsignupcust1.aCityText.value;
					fsignupcust1.selBilCity.options[0].value = fsignupcust1.aCity.value;
					fsignupcust1.selBilCity.options[0].selected = "selected";

					fsignupcust1.selBilKhan.options[0].text = fsignupcust1.aKhanText.value;
					fsignupcust1.selBilKhan.options[0].value = fsignupcust1.aKhan.value;
					fsignupcust1.selBilKhan.options[0].selected = "selected";

					fsignupcust1.selBilSangkat.options[0].text = fsignupcust1.aSangkatText.value;
					fsignupcust1.selBilSangkat.options[0].value = fsignupcust1.aSangkat.value;
					fsignupcust1.selBilSangkat.options[0].selected = "selected";

				}
				else if(fsignupcust1.thesame3.checked == true){

					fsignupcust1.txtBilAddress.value = fsignupcust1.txtCusAddress.value;
					fsignupcust1.txtBilAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
					fsignupcust1.txtBilStreet.value = fsignupcust1.txtCusStreet.value;
					fsignupcust1.txtBilStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;

					fsignupcust1.selBilCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
					fsignupcust1.selBilCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
					fsignupcust1.selBilCountry.options[0].selected = "selected";

					fsignupcust1.selBilCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
					fsignupcust1.selBilCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
					fsignupcust1.selBilCity.options[0].selected = "selected";

					fsignupcust1.selBilKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
					fsignupcust1.selBilKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
					fsignupcust1.selBilKhan.options[0].selected = "selected";

					fsignupcust1.selBilSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
					fsignupcust1.selBilSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
					fsignupcust1.selBilSangkat.options[0].selected = "selected";

				}else{
					fsignupcust1.txtBilAddress.value = "";
					fsignupcust1.txtBilAddressKhmer.value = "";
					fsignupcust1.txtBilStreet.value = "";
					fsignupcust1.txtBilStreetKhmer.value = "";

					fsignupcust1.selBilSangkat[0].value = 0;
					fsignupcust1.selBilSangkat[0].text = "Unknown";
					fsignupcust1.selBilSangkat[0].selected = "selected";

					fsignupcust1.selBilKhan[0].value = 0;
					fsignupcust1.selBilKhan[0].text = "Unknown";
					fsignupcust1.selBilKhan[0].selected = "selected";

					fsignupcust1.selBilCity[0].value = 0;
					fsignupcust1.selBilCity[0].text = "Uknown";
					fsignupcust1.selBilCity[0].selected = "selected";

					fsignupcust1.selBilCountry[0].value = 0;
					fsignupcust1.selBilCountry[0].text = "Unknown";
					fsignupcust1.selBilCountry[0].selected = "selected";
				}
			}else{
				if(index == 5){
					if((fsignupcust1.ext.value == "ap") && (fsignupcust1.thesame5.checked == true)){

						fsignupcust1.txtLeaAddress.value = fsignupcust1.aAddress.value;
						fsignupcust1.txtLeaAddressKhmer.value = fsignupcust1.aAddressKhmer.value;
						fsignupcust1.txtLeaStreet.value = fsignupcust1.aStreet.value;
						fsignupcust1.txtLeaStreetKhmer.value = fsignupcust1.aStreetKhmer.value;

						fsignupcust1.selLeaCountry.options[0].text = fsignupcust1.aCountryText.value;
						fsignupcust1.selLeaCountry.options[0].value = fsignupcust1.aCountry.value;
						fsignupcust1.selLeaCountry.options[0].selected = "selected";

						fsignupcust1.selLeaCity.options[0].text = fsignupcust1.aCityText.value;
						fsignupcust1.selLeaCity.options[0].value = fsignupcust1.aCity.value;
						fsignupcust1.selLeaCity.options[0].selected = "selected";

						fsignupcust1.selLeaKhan.options[0].text = fsignupcust1.aKhanText.value;
						fsignupcust1.selLeaKhan.options[0].value = fsignupcust1.aKhan.value;
						fsignupcust1.selLeaKhan.options[0].selected = "selected";

						fsignupcust1.selLeaSangkat.options[0].text = fsignupcust1.aSangkatText.value;
						fsignupcust1.selLeaSangkat.options[0].value = fsignupcust1.aSangkat.value;
						fsignupcust1.selLeaSangkat.options[0].selected = "selected";

					}
					else if(fsignupcust1.thesame5.checked == true){

					fsignupcust1.txtLeaAddress.value = fsignupcust1.txtCusAddress.value;
					fsignupcust1.txtIntAddressKhmer.value = fsignupcust1.txtCusAddressKhmer.value;
					fsignupcust1.txtLeaStreet.value = fsignupcust1.txtCusStreet.value;
					fsignupcust1.txtLeaStreetKhmer.value = fsignupcust1.txtCusStreetKhmer.value;

					fsignupcust1.selLeaCountry.options[0].text = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].text;
					fsignupcust1.selLeaCountry.options[0].value = fsignupcust1.selCusCountry.options[fsignupcust1.selCusCountry.selectedIndex].value;
					fsignupcust1.selLeaCountry.options[0].selected = "selected";

					fsignupcust1.selLeaCity.options[0].text = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].text;
					fsignupcust1.selLeaCity.options[0].value = fsignupcust1.selCusCity.options[fsignupcust1.selCusCity.selectedIndex].value;
					fsignupcust1.selLeaCity.options[0].selected = "selected";

					fsignupcust1.selLeaKhan.options[0].text = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].text;
					fsignupcust1.selLeaKhan.options[0].value = fsignupcust1.selCusKhan.options[fsignupcust1.selCusKhan.selectedIndex].value;
					fsignupcust1.selLeaKhan.options[0].selected = "selected";

					fsignupcust1.selLeaSangkat.options[0].text = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].text;
					fsignupcust1.selLeaSangkat.options[0].value = fsignupcust1.selCusSangkat.options[fsignupcust1.selCusSangkat.selectedIndex].value;
					fsignupcust1.selLeaSangkat.options[0].selected = "selected";


					}else{
						fsignupcust1.txtLeaStreet.value = "";
						fsignupcust1.txtLeaStreetKhmer.value = "";
						fsignupcust1.txtLeaAddress.value = "";
						fsignupcust1.txtLeaAddressKhmer.value = "";

						fsignupcust1.selLeaSangkat[0].value = 0;
						fsignupcust1.selLeaSangkat[0].text = "Unknown";
						fsignupcust1.selLeaSangkat[0].selected = "selected";

						fsignupcust1.selLeaKhan[0].value = 0;
						fsignupcust1.selLeaKhan[0].text = "Unknown";
						fsignupcust1.selLeaKhan[0].selected = "selected";

						fsignupcust1.selLeaCity[0].value = 0;
						fsignupcust1.selLeaCity[0].text = "Unknown";
						fsignupcust1.selLeaCity[0].selected = "selected";

						fsignupcust1.selLeaCountry[0].value = 0;
						fsignupcust1.selLeaCountry[0].text = "Unknown";
						fsignupcust1.selLeaCountry[0].selected = "selected";
					}
				}
			}
		}
	}




	function checkPassword(Password, Confirm){
		if(Password == Confirm)
			return true;
		else
			return false;
	}


	function ValidAccountAcc(retDiv){

		phonepre = fsignupcust1.SelPhonePreset.options[fsignupcust1.SelPhonePreset.selectedIndex].value;
		phonenum = fsignupcust1.UserName.value;

		if(fsignupcust1.radConnection[0].checked)
		{
			username = phonepre + phonenum;
			if(username.length < 9){
				document.getElementById("btnSubmit").disabled = true;
				document.getElementById("dUserName").innerHTML=" Invalied account name.";
				document.getElementById("dUserName").style.display = "inline";
				return false;
			}else{
				for (i = 0; i < username.length; i++){
					var c = username.charAt(i);
						if ((c < "0") || (c > "9")){
							document.getElementById("btnSubmit").disabled = true;
							document.getElementById("dUserName").innerHTML=" Invalied account name.";
							document.getElementById("dUserName").style.display = "inline";
							return false;
						}
				}
				document.getElementById("btnSubmit").disabled = false;
			}
		}
		else
		{
			username = phonenum;
			if(username.length == 0){
				document.getElementById("btnSubmit").disabled = true;
				document.getElementById("dUserName").innerHTML=" Invalid account name.";
				document.getElementById("dUserName").style.display = "inline";
				return false;
			}else{
				document.getElementById("btnSubmit").disabled = false;
			}
		}


		if(fsignupcust1.PackageID.selectedIndex < 1)
		{
				alert("Please select package...");
				fsignupcust1.PackageID.focus();
				return false;
		}

		suff = "";
		if(document.getElementById("sidd").value==1)
		{
			suff="@adsl";
		}
		else if(document.getElementById("sidd").value==3)
		{
			suff="@dialup";
		}
		else if(document.getElementById("sidd").value==8)
		{
			suff="@isdn";
		}
		else if(document.getElementById("sidd").value==4)
		{
			suff="@leaseline";
		}

		url = "./php/ajax_checkusername.php?username=" + username+suff +"&mt=" + new Date().getTime();
		checkUserNameAcc(url, retDiv);
	}

	function fn_getvalue()
	{
		if(fsignupcust1.PackageID.selectedIndex < 1)
		{
				alert("Please select package...");
				fsignupcust1.PackageID.focus();
				return false;
		}
		PackageID=fsignupcust1.PackageID.options[fsignupcust1.PackageID.selectedIndex].value;
		url2 = "./php/ajax_getServerID.php?PackageID=" + PackageID +"&mt=" + new Date().getTime();
		getServiceID(url2, document.getElementById("sidd"));
		return true;
	}


	function ValidateForm(){

		if(fsignupcust1.ext.value != "ap")
		{
			if(fsignupcust1.txtCustomerName.value == ""){
				alert("Please enter customer field.");
				fsignupcust1.txtCustomerName.focus();
				return;
			}
		}

		if(Trim(fsignupcust1.SubscriptionName.value) ==""){
			alert("Please enter subscription fee");
			fsignupcust1.SubscriptionName.focus();
			return;
		}else if(fsignupcust1.PackageID.selectedIndex < 1){
			alert("Please select package value");
			fsignupcust1.PackageID.focus();
			return;
		}else if(fsignupcust1.SelStationID.selectedIndex < 1){
			alert("Please select station");
			fsignupcust1.SelStationID.focus();
			return;
		}else if(Trim(fsignupcust1.UserName.value) == ""){
			alert("Please enter account name");
			fsignupcust1.UserName.focus();
			return;
		}else if(Trim(fsignupcust1.Password.value) == ""){
			alert("Please enter account password");
			fsignupcust1.Password.focus();
			return;
		}else if(Trim(fsignupcust1.Password.value).length < 6){
			alert("Password must be between 6 to 20 chararater length");
			fsignupcust1.Password.focus();
			return;
		}else if(!checkPassword(Trim(fsignupcust1.Password.value), Trim(fsignupcust1.ConfirmPassword.value))){
			alert("Password and confirm password must be the same");
			fsignupcust1.Password.focus();
			return;
		}else if(Trim(fsignupcust1.txtBilEmail.value) != ""){
			if(!isValidMail(Trim(fsignupcust1.txtBilEmail.value))){
				alert("Invalid billing email address");
				fsignupcust1.txtBilEmail.focus();
				return;
			}
		}
		// else if(fsignupcust1.selSalesman.selectedIndex < 1){
		// 	alert("Please select saleaman");
		// 	fsignupcust1.selSalesman.focus();
		// 	return;
		// }
		else if(fsignupcust1.selBilCountry.options[fsignupcust1.selBilCountry.selectedIndex].text == "Unknown"){
			alert("Pleas select billing country");
			fsignupcust1.selBilCountry.focus();
			return;
		}else if(fsignupcust1.selBilCity.options[fsignupcust1.selBilCity.selectedIndex].text == "Unknown"){
			alert("Pleas select billing city");
			fsignupcust1.selBilCity.focus();
			return;
		}else if(fsignupcust1.selBilKhan.options[fsignupcust1.selBilKhan.selectedIndex].text == "Unknown"){
			alert("Pleas select billing khan");
			fsignupcust1.selBilKhan.focus();
			return;
		}else if(fsignupcust1.selBilSangkat.options[fsignupcust1.selBilSangkat.selectedIndex].text == "Unknown"){
			alert("Pleas select billing sangkat");
			fsignupcust1.selBilSangkat.focus();
			return;
		}
		else if(Trim(fsignupcust1.txtBilAddress.value) == ""){
			alert("Please enter billing address");
			fsignupcust1.txtBilAddress.focus();
			return;
		}

			fsignupcust1.btnSubmit.disabled = true;
			fsignupcust1.submit();
	}

</script>
<form name="fsignupcust1" method="post" action="./">
<br>
<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
	<tr>

		<td valign="top" width="100%" align="left">

			 	<table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
                	<tr style="padding:2;">
                    	<td>
                        	<table border="0" cellpadding="2" cellspacing="2" align="left" width="100%" class="formbg">
                            	<tr>
                                    <td align="center" class="formtitle" height="18" colspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="+1"><strong>Customer Registration For </strong></font></td>
                                </tr>
                            	<tr bgcolor="#feeac2" style="padding:2;">
                                    <td width="100%" align="center" colspan="2">
                                    	<input type="radio" name="radConnection" tabindex="1" value="0" <?php if($radConnection == 0) print("checked"); ?> onclick="get_package(this.value);Leaseoption();" checked />Telephone&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="radConnection" tabindex="2" value="1" <?php if($radConnection == 1) print("checked"); ?> onclick="get_package(this.value);Leaseoption();" />Internet&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="radConnection" tabindex="3" value="2" <?php if($radConnection == 2) print("checked"); ?> onclick="get_package(this.value);Leaseoption();" />LeaseLine
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                </table>
       		</td>
      </tr>
      <tr height="1%">
		<td valign="top" width="100%" align="left"></td>
      </tr>
      <tr>
		<td valign="top" width="100%" align="left">

                    <div id="mp" style="margin:0; padding:0;">
                <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%" style="margin:0; padding:0;">
					<tr>
						<td>
							<!-- ================================ CUSTOMER PROFILE ================================-->
							<!-- ==================================================================================-->
							<div id="pcustomer">
                                <table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%" style="margin:0; padding:0;">
                                    <tr>
                                        <td align="center" class="formtitle" height="18"><b>PERSONAL CUSTOMER PROFILE</b></td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                                <tr>
                                                    <td align="left">Salutation:</td>
                                                    <td align="left" width="200" colspan="3">
                                                        <select name="selCusSalutation" class="boxenabled" tabindex="4">
                                                            <option value="Mr." <?php if($selCustSalutation == "Mr.") print "selected"?>>Mr.</option>
                                                            <option value="Mrs." <?php if($selCustSalutation == "Mrs.") print "selected"?>>Mrs.</option>
                                                            <option value="Miss." <?php if($selCustSalutation == "Miss.") print "selected"?>>Miss.</option>
                                                            <option value="Dr." <?php if($selCustSalutation == "Dr.") print "selected"?>>Dr.</option>
                                                            <option value="Mr." <?php if($selCustSalutation == "H.E") print "selected"?>>H.E</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" nowrap="nowrap">
                                                        Name:
                                                    </td>
                                                    <td align="left" colspan="3">
                                                        <input type="text" name="txtCustomerName" class="boxenabled" tabindex="5" size="100%" value="" /><img src="./images/required.gif" border="0" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" nowrap="nowrap">
                                                        <span class="khfont">ឈ្មោះ:</span>
                                                    </td>
                                                    <td align="left" colspan="3">
                                                        <input type="text" name="txtCustomerNameKhmer" class="boxenabled​​​​​​​ khfont" tabindex="5" size="100%" value="" /><img src="./images/required.gif" border="0" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" nowrap="nowrap">Date of birth:</td>
                                                    <td align="left"><input type="text" tabindex="6" name="txtCusDOB" class="boxenabled" size="12" maxlength="30" value="" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
                                                        <button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust1|txtCusDOB', '', 'width=200,height=220,top=250,left=350');">
                                                            <img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
                                                        </button>(YYYY-MM-DD)
                                                    </td>
                                                    <td align="left">Duplicate:</td>
                                                    <td align="left">
                                                        <select name="selCusDuplicateID" class="boxenabled" tabindex="7">
                                                            <option value="ID Card" <?php if($selCustDuplicateID == "ID Card") print "selected"?>>ID Card</option>
                                                            <option value="Passport" <?php if($selCustDuplicateID == "Passport") print "selected"?>>Passport</option>
                                                            <option value="Family book" <?php if($selCustDuplicateID == "Family book") print "selected"?>>Family book</option>
                                                        </select>
                                                        <input type="text" name="txtCusDuplicate" class="boxenabled" tabindex="8" size="10" value="<?php print $txtCusDuplicate;?>" />
                                                    </td>
                                                </tr>
                                                                                                <tr>
                                                    <td align="left">Category:</td>
                                                    <td align="left">
                                                        <select name="selBusinessType" class="boxenabled" tabindex="9" style="width:190px">
                                                            <?php
                                                                $sql = "SELECT TypeID, Name from tlkpCustBusinessType where IsShow = 1 order by Name";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $TypeID = $rst['TypeID'];
                                                                        $Name = $rst['Name'];

                                                                        print "<option value='".$TypeID."'>".$Name."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td align="left" nowrap="nowrap">Business No.:</td>
                                                    <td align="left"><input type="text" tabindex="10" name="txtCusBus" class="boxenabled" size="29" maxlength="30" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" nowrap="nowrap">
                                                        VAT Number:
                                                    </td>
                                                    <td align="left">
                                                        <input type="text" name="txtVATNumber" class="boxenabled" tabindex="11" size="29" maxlength="30" value="<?php print $txtVATNumber;?>" />
                                                    </td>
                                                    <td align="left" nowrap="nowrap">&nbsp;

                                                    </td>
                                                    <td align="left">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Nationality:</td>
                                                    <td align="left">
                                                        <select name="selCusNationality" class="boxenabled" tabindex="12" style="width:190px">
                                                            <option value="38" selected="selected">Cambodia</option>
                                                            <?php
                                                                $sql = "SELECT CountryID, Country from tlkpCountry order by Country";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CountryID = $rst['CountryID'];
                                                                        $Country = $rst['Country'];
                                                                        if($selCustNationality == $CountryID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td align="left">
                                                        Occupation:
                                                    </td>
                                                    <td align="left">
                                                        <select name="selCusOccupation" class="boxenabled" tabindex="13" style="width:190px">
                                                            <?php
                                                                $sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CareerID = $rst['CareerID'];
                                                                        $Career = $rst['Career'];
                                                                        if($selCustOccupation == $CareerID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CareerID."' ".$sel.">".$Career."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Contact Phone:</td>
                                                    <td align="left"><input type="text" name="txtCusPhone" value="<?php print $txtCusPhone; ?>" class="boxenabled" tabindex="14" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
                                                    </td>
                                                    <td align="left">Email:</td>
                                                    <td align="left"><input type="text" name="txtCusEmail" value=<?php print $txtCusEmail; ?>"" class="boxenabled" tabindex="15" size="29" /> </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">House:</td>
                                                    <td align="left">
                                                        <input type="text" name="txtCusAddress" class="boxenabled" tabindex="16" size="50" maxlength="60" value="<?php print $txtCusAddress; ?>" />
                                                    </td>
                                                    <td align="left">Street:</td>
                                                    <td align="left">
                                                        <input type="text" name="txtCusStreet" class="boxenabled" tabindex="17" size="29" maxlength="40" value="<?php print $txtCusStreet; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                                    <td align="left">
                                                        <input type="text" name="txtCusAddressKhmer" class="boxenabled khfont" tabindex="16" size="50" maxlength="60" value="<?php print $txtCusAddressKhmer; ?>" />
                                                    </td>
                                                    <td align="left"><span class="khfont">ផ្លូវ:</span></td>
                                                    <td align="left">
                                                        <input type="text" name="txtCusStreetKhmer" class="boxenabled khfont" tabindex="17" size="29" maxlength="40" value="<?php print $txtCusStreetKhmer; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Country:</td>
                                                    <td align="left">
                                                        <div id="divDesCountry">
                                                        <select name="selCusCountry" id="selCusCountry" class="boxenabled" tabindex="18" style="width:200px" onChange="storeNameValue(this.selectedIndex, 8);">
                                                        <option value="0" selected="selected">Unknown</option>
                                                            <?php
                                                                $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CountryID = $rst['id'];
                                                                        $Country = $rst['name'];
                                                                        if($selDesCountry == $CountryID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                        </div>
                                                    </td>
                                                    <td align="left">City:</td>
                                                    <td align="left">

                                                        <select name="selCusCity" id="selCusCity" class="boxenabled" tabindex="19" style="width:200px" onChange="storeNameValue(this.selectedIndex, 7);">

                                                            <option value="0" selected="selected">Unknown</option>
                                                        </select>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Khan:</td>
                                                    <td align="left">
                                                        <select name="selCusKhan" id="selCusKhan" class="boxenabled" tabindex="20" style="width:200px" onChange="storeNameValue(this.selectedIndex, 6);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                    <td align="left">Sangkat:</td>
                                                    <td align="left">
                                                        <select name="selCusSangkat" id="selCusSangkat" class="boxenabled" tabindex="21" style="width:200px" onChange="storeNameValue(this.selectedIndex, 5);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                        	</div>
						</td>
					</tr>
                    <!--================================= End customer =========================== !-->
                    <tr><td>&nbsp;</td></tr>
                    <!--================================= Start Company customer =========================== !-->

					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="center" class="formtitle" height="18"><b>DESIGNATE PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame" onClick="setTheSame();" tabindex="22">The same as customer ]</font></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
									<tr>
										<td align="left">Salutation:</td>
										<td align="left" width="200" colspan="3">
											<select name="selDesSalutation" class="boxenabled" tabindex="23">
												<option value="Mr." <?php if($selDesSalutation == "Mr.") print "selected"?>>Mr.</option>
												<option value="Mrs." <?php if($selDesSalutation == "Mrs.") print "selected"?>>Mrs.</option>
												<option value="Miss." <?php if($selDesSalutation == "Miss.") print "selected"?>>Miss.</option>
												<option value="Dr." <?php if($selDesSalutation == "Dr.") print "selected"?>>Dr.</option>
                                                <option value="Dr." <?php if($selDesSalutation == "H.E") print "selected"?>>H.E</option>
											</select>
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">
											Name:
										</td>
										<td align="left" colspan="3">
											<input type="text" name="txtDesignateName" class="boxenabled" tabindex="24" size="100%" value="<?php print $txtDesignateName;?>" />
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">
											<span class="khfont">ឈ្មោះ:</span>
										</td>
										<td align="left" colspan="3">
											<input type="text" name="txtDesignateNameKhmer" class="boxenabled khfont" tabindex="24" size="100%" value="<?php print $txtDesignateNameKhmer;?>" />
										</td>
									</tr>
									<tr>
										<td align="left" nowrap="nowrap">Date of birth:</td>
										<td align="left"><input type="text" tabindex="25" name="txtDesDOB" class="boxenabled" size="12" maxlength="30" value="<?php print $txtDesDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
											<button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust1|txtDesDOB', '', 'width=200,height=220,top=250,left=350');">
												<img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
											</button>(YYYY-MM-DD)
										</td>
										<td align="left">Duplicate:</td>
										<td align="left">
											<select name="selDesDuplicateID" class="boxenabled" tabindex="26">
												<option value="ID Card" <?php if($selDesDuplicateID == "ID Card") print "selected"?>>ID Card</option>
												<option value="Passport" <?php if($selDesDuplicateID == "Passport") print "selected"?>>Passport</option>
												<option value="Family book" <?php if($selDesDuplicateID == "Miss.") print "selected"?>>Family book</option>
											</select>
											<input type="text" name="txtDesDuplicate" class="boxenabled" tabindex="27" size="11" value="<?php print $txtDesDuplicate;?>" />
										</td>
									</tr>
									<tr>
										<td align="left">Nationality:</td>
										<td align="left">
											<select name="selDesNationality" class="boxenabled" tabindex="28" onChange="storeNameValue(this.selectedIndex, 9);" style="width:200px">
												<option value="38" selected="selected">Cambodia</option>
												<?php
													$sql = "SELECT CountryID, Country from tlkpCountry order by Country";
													// sql 2005

													$que = $mydb->sql_query($sql);
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){
															$CountryID = $rst['CountryID'];
															$Country = $rst['Country'];
															if($selDesNationality == $CountryID)
																$sel = "selected";
															else
																$sel = "";
															print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
														}
													}
													$mydb->sql_freeresult();
												?>
											</select>
										</td>
										<td align="left">
											Occupation:
										</td>
										<td align="left">
											<select name="selDesOccupation" class="boxenabled" tabindex="29" onChange="storeNameValue(this.selectedIndex, 10);" style="width:200px">
												<?php
													$sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
													// sql 2005

													$que = $mydb->sql_query($sql);
													if($que){
														while($rst = $mydb->sql_fetchrow($que)){
															$CareerID = $rst['CareerID'];
															$Career = $rst['Career'];
															if($selDesOccupation == $CareerID)
																$sel = "selected";
															else
																$sel = "";
															print "<option value='".$CareerID."' ".$sel.">".$Career."</option>";
														}
													}
													$mydb->sql_freeresult();
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td align="left">Telephone:</td>
										<td align="left"><input type="text" name="txtDesPhone" value="<?php print $txtDesPhone; ?>" class="boxenabled" tabindex="30" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
										</td>
										<td align="left">Email:</td>
										<td align="left"><input type="text" name="txtDesEmail" value=<?php print $txtDesEmail; ?>"" class="boxenabled" tabindex="31" size="29" /> </td>
									</tr>
									<tr>
										<td align="left">House:</td>
										<td align="left">
											<input type="text" name="txtDesAddress" class="boxenabled" tabindex="32" size="50" maxlength="100" value="<?php print $txtDesAddress; ?>" />
										</td>
                                        <td align="left">Street:</td>
										<td align="left">
											<input type="text" name="txtDesStreet" class="boxenabled" tabindex="33" size="29" maxlength="100" value="<?php print $txtDesStreet; ?>" />
										</td>
									</tr>
									<tr>
										<td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
										<td align="left">
											<input type="text" name="txtDesAddressKhmer" class="boxenabled khfont" tabindex="32" size="50" maxlength="100" value="<?php print $txtDesAddressKhmer; ?>" />
										</td>
                                        <td align="left"><span class="khfont">ផ្លូវ:</span></td>
										<td align="left">
											<input type="text" name="txtDesStreetKhmer" class="boxenabled khfont" tabindex="33" size="29" maxlength="100" value="<?php print $txtDesStreetKhmer; ?>" />
										</td>
									</tr>
										<tr>
											<tr>
												<td align="left">Country:</td>
												<td align="left">
													<select name="selDesCountry" class="boxenabled" tabindex="34" style="width:200px" onchange="storeNameValue(this.selectedIndex, 14);">
													<option value="0" selected="selected">Unknown</option>
														<?php
															$sql = "SELECT id, name from tlkpLocation where type = 1 order by name";

															$que = $mydb->sql_query($sql);
															if($que){
																while($rst = $mydb->sql_fetchrow($que)){
																	$CountryID = $rst['id'];
																	$Country = $rst['name'];
																	if($selDesCountry == $CountryID)
																		$sel = "selected";
																	else
																		$sel = "";
																	print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
																}
															}
															$mydb->sql_freeresult();
														?>
													</select>
												</td>
												<td align="left">City:</td>
												<td align="left">
													<select name="selDesCity" class="boxenabled" tabindex="35" style="width:200px" onchange="storeNameValue(this.selectedIndex, 13);">
														<option value="0">Unknown</option>
													</select>
												</td>
											</tr>
											<td align="left">Khan:</td>
											<td align="left">
												<select name="selDesKhan" class="boxenabled" tabindex="36" style="width:200px" onchange="storeNameValue(this.selectedIndex, 12);">
													<option value="0">Unknown</option>
												</select>
											</td>
											<td align="left">Sangkat:</td>
											<td align="left">
												<select name="selDesSangkat" class="boxenabled" tabindex="37" style="width:200px" onchange="storeNameValue(this.selectedIndex, 11);">
													<option value="0">Unknown</option>
												</select>
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
                    	<td>
                        	<div id="guarantor">
                            <!-- ================================ GUARANTOR PROFILE ================================-->
							<!-- ==================================================================================-->
                        		<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
                                <tr>
                                    <td align="center" class="formtitle" height="18"><b>GUARANTOR PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame2" id="thesame2" onClick="setTheSameOther(2);" tabindex="38">The same as customer profile ]</font></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                        <tr>
                                            <td align="left">Salutation:</td>
                                            <td align="left" width="200" colspan="3">
                                                <select name="selGuaSalutation" class="boxenabled" tabindex="39">
                                                    <option value="Mr." <?php if($selGuaSalutation == "Mr.") print "selected"?>>Mr.</option>
                                                    <option value="Mrs." <?php if($selGuaSalutation == "Mrs.") print "selected"?>>Mrs.</option>
                                                    <option value="Miss." <?php if($selGuaSalutation == "Miss.") print "selected"?>>Miss.</option>
                                                    <option value="Dr." <?php if($selGuaSalutation == "Dr.") print "selected"?>>Dr.</option>
                                                    <option value="Dr." <?php if($selGuaSalutation == "H.E") print "selected"?>>H.E</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" nowrap="nowrap">
                                                Name:
                                            </td>
                                            <td align="left" colspan="3">
                                                <input type="text" name="txtGarrentorName" class="boxenabled" tabindex="40" size="100%" value="<?php print $txtConignateName;?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" nowrap="nowrap">
                                                <span class="khfont">ឈ្មោះ:</span>
                                            </td>
                                            <td align="left" colspan="3">
                                                <input type="text" name="txtGarrentorNameKhmer" class="boxenabled khfont" tabindex="40" size="100%" value="<?php print $txtConignateNameKhmer;?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" nowrap="nowrap">Date of birth:</td>
                                            <td align="left"><input type="text" tabindex="41" name="txtGuaDOB" class="boxenabled" size="12" maxlength="30" value="<?php print $txtGuaDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
                                                <button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust1|txtGuaDOB', '', 'width=200,height=220,top=250,left=350');">
                                                    <img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
                                                </button>(YYYY-MM-DD)
                                            </td>
                                            <td align="left">Duplicate:</td>
                                            <td align="left">
                                                <select name="selGuaDuplicateID" class="boxenabled" tabindex="42">
                                                    <option value="ID Card" <?php if($selGuaDuplicateID == "ID Card") print "selected"?>>ID Card</option>
                                                    <option value="Passport" <?php if($selGuaDuplicateID == "Passport") print "selected"?>>Passport</option>
                                                    <option value="Family book" <?php if($selGuaDuplicateID == "Miss.") print "selected"?>>Family book</option>
                                                </select>
                                                <input type="text" name="txtGuaDuplicate" class="boxenabled" tabindex="43" size="11" value="<?php print $txtGuaDuplicate;?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">Nationality:</td>
                                            <td align="left">
                                                <select name="selGuaNationality" class="boxenabled" tabindex="44" onChange="storeNameValueGua(this.selectedIndex, 9);" style="width:200px">
                                                    <?php
                                                        $sql = "SELECT CountryID, Country from tlkpCountry order by Country";


    													$que = $mydb->sql_query($sql);
    													if($que){
    														while($rst = $mydb->sql_fetchrow($que)){
    															$CountryID = $rst['CountryID'];
    															$Country = $rst['Country'];
    															if($selGuaNationality == $CountryID)
    																$sel = "selected";
    															else
    																$sel = "";
    															print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
    														}
    													}
    													$mydb->sql_freeresult();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="left">
                                                Occupation:
                                            </td>
                                            <td align="left">
                                                <select name="selGuaOccupation" class="boxenabled" tabindex="45" onChange="storeNameValueGua(this.selectedIndex, 10);" style="width:200px">
                                                    <?php
                                                        $sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
                                                        // sql 2005

                                                        $que = $mydb->sql_query($sql);
                                                        if($que){
                                                            while($rst = $mydb->sql_fetchrow($que)){
                                                                $CareerID = $rst['CareerID'];
                                                                $Career = $rst['Career'];
                                                                if($selGuaOccupation == $CareerID)
                                                                    $sel = "selected";
                                                                else
                                                                    $sel = "";
                                                                print "<option value='".$CareerID."' ".$sel.">".$Career."</option>";
                                                            }
                                                        }
                                                        $mydb->sql_freeresult();
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">Telephone:</td>
                                            <td align="left"><input type="text" name="txtGuaPhone" value="" class="boxenabled" tabindex="46" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
                                            </td>
                                            <td align="left">Email:</td>
                                            <td align="left"><input type="text" name="txtGuaEmail" value="" class="boxenabled" tabindex="47" size="28" /> </td>
                                        </tr>
                                        <tr>
                                            <td align="left">House:</td>
                                            <td align="left">
                                                <input type="text" name="txtGuaAddress" class="boxenabled" tabindex="48" size="50" maxlength="100" value="<?php print $txtGuaAddress; ?>" />
                                            </td>
                                            <td align="left">Street:</td>
                                            <td align="left">
                                                <input type="text" name="txtGuaStreet" class="boxenabled" tabindex="49" size="29" maxlength="100" value="<?php print $txtGuaStreet; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                            <td align="left">
                                                <input type="text" name="txtGuaAddressKhmer" class="boxenabled​​ khfont" tabindex="48" size="50" maxlength="100" value="<?php print $txtGuaAddressKhmer; ?>" />
                                            </td>
                                            <td align="left"><span class="khfont">ផ្លូវ:</span></td>
                                            <td align="left">
                                                <input type="text" name="txtGuaStreetKhmer" class="boxenabled khfont" tabindex="49" size="29" maxlength="100" value="<?php print $txtGuaStreetKhmer; ?>" />
                                            </td>
                                        </tr>
                                            <tr>
                                                <td align="left">Country:</td>
                                                <td align="left">
                                                    <select name="selGuaCountry" class="boxenabled" tabindex="50" style="width:200px" onchange="storeNameValueGua(this.selectedIndex, 14);">
                                                    <option value="0" selected="selected">Unknown</option>
                                                        <?php
                                                            $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $CountryID = $rst['id'];
                                                                    $Country = $rst['name'];
                                                                    if($selGuaCountry == $CountryID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                </td>
                                                <td align="left">City:</td>
                                                <td align="left">
                                                    <select name="selGuaCity" class="boxenabled" tabindex="51" style="width:200px" onchange="storeNameValueGua(this.selectedIndex, 13);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td align="left">Khan:</td>
                                                <td align="left">
                                                    <select name="selGuaKhan" class="boxenabled" tabindex="52" style="width:200px" onchange="storeNameValueGua(this.selectedIndex, 12);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>
                                                <td align="left">Sangkat:</td>
                                                <td align="left">
                                                    <select name="selGuaSangkat" class="boxenabled" tabindex="53" style="width:200px" onchange="storeNameValueGua(this.selectedIndex, 11);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>
                                            </tr>
                                         </table>
                                     </td>
                                     </tr>
                                </table>
							</div>
                        </td>
                    </tr>
   					<tr><td>&nbsp;</td></tr>
                    <tr>
                    	<td>
                        	<div id="contactp">
                        	<!-- ================================ CONTACT PROFILE ================================-->
							<!-- ==================================================================================-->
							<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="center" class="formtitle" height="18"><b>CONTACT PROFILE</b>  <font color="#000000">[<input type="checkbox" name="thesame1" id="thesame1" onClick="setTheSameOther(1);" tabindex="54">The same as customer profile ]</font></td>
							</tr>
							<tr>
								<td valign="top">
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                            <tr>
                                                <td align="left">Salutation:</td>
                                                <td align="left" width="200" colspan="3">
                                                    <select name="selConSalutation" class="boxenabled" tabindex="55">
                                                        <option value="Mr." <?php if($selConSalutation == "Mr.") print "selected"?>>Mr.</option>
                                                        <option value="Mrs." <?php if($selConSalutation == "Mrs.") print "selected"?>>Mrs.</option>
                                                        <option value="Miss." <?php if($selConSalutation == "Miss.") print "selected"?>>Miss.</option>
                                                        <option value="Dr." <?php if($selConSalutation == "Dr.") print "selected"?>>Dr.</option>
                                                        <option value="Dr." <?php if($selConSalutation == "H.E") print "selected"?>>H.E</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">
                                                    Name:
                                                </td>
                                                <td align="left" colspan="3">
                                                    <input type="text" name="txtContactName" class="boxenabled" tabindex="56" size="100%" value="<?php print $txtContactName;?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">
                                                    <span class="khfont">ឈ្មោះ:</span>
                                                </td>
                                                <td align="left" colspan="3">
                                                    <input type="text" name="txtContactNameKhmer" class="boxenabled khfont" tabindex="56" size="100%" value="<?php print $txtContactNameKhmer;?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">Date of birth:</td>
                                                <td align="left"><input type="text" tabindex="57" name="txtConDOB" class="boxenabled" size="15" maxlength="30" value="<?php print $txtConDOB; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
                                                    <button class="invisibleButtons" onClick="window.open( './javascript/calendar.html?fsignupcust1|txtConDOB', '', 'width=200,height=220,top=250,left=350');">
                                                        <img src='./images/b_calendar.png' alt='View Calendar' align="middle" border="0">
                                                    </button>(YYYY-MM-DD)
                                                </td>
                                                <td align="left">Duplicate:</td>
                                                <td align="left">
                                                    <select name="selConDuplicateID" class="boxenabled" tabindex="58">
                                                        <option value="ID Card" <?php if($selConDuplicateID == "ID Card") print "selected"?>>ID Card</option>
                                                        <option value="Passport" <?php if($selConDuplicateID == "Passport") print "selected"?>>Passport</option>
                                                        <option value="Family book" <?php if($selConDuplicateID == "Family book") print "selected"?>>Family book</option>
                                                    </select>
                                                    <input type="text" name="txtConDuplicate" class="boxenabled" tabindex="59" size="11" value="<?php print $txtConDuplicate;?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left">Telephone:</td>
                                                <td align="left"><input type="text" name="txtConPhone" value="<?php print $txtConPhone; ?>" class="boxenabled" tabindex="60" size="29" onkeyup="ValidatePhone(this);" onblur="CheckPhone(this);" />
                                                </td>
                                                <td align="left">Email:</td>
                                                <td align="left"><input type="text" name="txtConEmail" value=<?php print $txtConEmail; ?>"" class="boxenabled" tabindex="61" size="28" /> </td>
                                            </tr>
                                            <tr>
                                                <td align="left">Nationality:</td>
                                                <td align="left">
                                                    <select name="selConNationality" class="boxenabled" tabindex="62" onChange="storeNameValue(this.selectedIndex, 3);" style="width:200px">
                                                        <option value="38" selected="selected">Cambodian</option>
                                                        <?php
                                                            $sql = "SELECT CountryID, Country from tlkpCountry order by Country";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $CountryID = $rst['CountryID'];
                                                                    $Country = $rst['Country'];
                                                                    if($selConNationality == $CountryID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                </td>
                                                <td align="left">
                                                    Occupation:
                                                </td>
                                                <td align="left">
                                                    <select name="selConOccupation" class="boxenabled" tabindex="63" onChange="storeNameValue(this.selectedIndex, 4);" style="width:200px">
                                                        <?php
                                                            $sql = "SELECT CareerID, Career from tlkpCareer order by CareerID";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $CareerID = $rst['CareerID'];
                                                                    $Career = $rst['Career'];
                                                                    if($selConOccupation == $CareerID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$CareerID."' ".$sel.">".$Career."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left">House:</td>
                                                <td align="left">
                                                    <input type="text" name="txtConAddress" class="boxenabled" tabindex="64" size="50" maxlength="100" value="<?php print $txtConAddress; ?>" />
                                                </td>
                                                <td align="left">Street:</td>
                                                <td align="left">
                                                    <input type="text" name="txtConStreet" class="boxenabled" tabindex="65" size="29" maxlength="100" value="<?php print $txtConStreet; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtConAddressKhmer" class="boxenabled khfont" tabindex="64" size="50" maxlength="100" value="<?php print $txtConAddressKhmer; ?>" />
                                                </td>
                                                <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtConStreetKhmer" class="boxenabled khfont" tabindex="65" size="29" maxlength="100" value="<?php print $txtConStreetKhmer; ?>" />
                                                </td>
                                            </tr>
                                                <tr>
                                                    <td align="left">Country:</td>
                                                    <td align="left">
                                                        <div id="divConCountry">
                                                        <select name="selConCountry" class="boxenabled" tabindex="66" style="width:200px" onchange="storeNameValueCon(this.selectedIndex, 14);">
                                                        <option value="0" selected="selected">Unknown</option>
                                                            <?php
                                                                $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CountryID = $rst['id'];
                                                                        $Country = $rst['name'];
                                                                        if($selConCountry == $CountryID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                        </div>
                                                    </td>
                                                    <td align="left">City:</td>
                                                    <td align="left">

                                                        <select name="selConCity" id="selConCity" class="boxenabled" tabindex="67" style="width:200px" onchange="storeNameValueCon(this.selectedIndex, 13);">

                                                            <option value="0" selected="selected">Unknown</option>
                                                        </select>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Khan:</td>
                                                    <td align="left">
                                                        <select name="selConKhan" class="boxenabled" tabindex="68" style="width:200px" onchange="storeNameValueCon(this.selectedIndex, 12);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                    <td align="left">Sangkat:</td>
                                                    <td align="left">
                                                        <select name="selConSangkat" class="boxenabled" tabindex="69" style="width:200px" onchange="storeNameValueCon(this.selectedIndex, 11);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                         </table>
                                     </td>
                                     </tr>
                                </table>
                        	</div>
                        </td>
                    </tr>
                 </table>
                 </div>
            </td>
        </tr>
        <tr>
			<td valign="top" width="100%" align="left"></td>
        </tr>
        <tr>
			<td valign="top" width="100%" align="left">
                 <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%">
   					<tr><td>&nbsp;</td></tr>
                    <tr>
                    	<td>
	                        <div id="acinfor">
                        	<table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
							<tr>
								<td align="center" class="formtitle" height="18"><b>ACCOUNT INFORMATION</b></td>
							</tr>
							<tr>
								<td>
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2" align="left">
                                            <tr>
                                                <td align="left" nowrap="nowrap">Subscription name:</td>
                                                <td align="left">
                                                    <input type="text" name="SubscriptionName" value="<?php print $SubscriptionName;?>"  class="boxenabled" tabindex="70" size="100%" /><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap"><span class="khfont">ឈ្មោះ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="SubscriptionNameKhmer" value="<?php print $SubscriptionNameKhmer;?>"  class="boxenabled khfont" tabindex="70" size="100%" /><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left">Package:</td>
                                                <td align="left">
                                                    <?php
                                                        if ($radConnection==0 || empty($radConnection) || !isset($radConnection))
                                                        {
                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                        ISDNFee, SpecialNumber
                                                                            from tblTarPackage where Status = 1 and ServiceID = 2
                                                                            and DepositAmount<>1
                                                                            order by 2";
                                                        }
                                                        else if ($radConnection==1)
                                                        {
                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                        ISDNFee, SpecialNumber
                                                                            from tblTarPackage where Status = 1 and ServiceID in (1,3,8) and DepositAmount<>1 order by 2";
                                                        }
														else if ($radConnection==2)
                                                        {
                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                        ISDNFee, SpecialNumber
                                                                            from tblTarPackage where Status = 1 and ServiceID in (4) and DepositAmount<>1 order by 2";
                                                        }

                                                        $que = $mydb->sql_query($sql);
                                                        if($que){
                                                            $tmppackage = "";
                                                            $tmpdeposit = "";
                                                            while($rst = $mydb->sql_fetchrow($que)){
                                                                $PackageID = $rst['PackageID'];
                                                                $TarName = $rst['TarName'];
                                                                $ISDNFee = $rst['ISDNFee'];
                                                                $SpecialNumber = $rst['SpecialNumber'];
                                                                $CreatedDate = $rst['CreatedDate'];
                                                                $RegistrationFee = $rst['RegistrationFee'];
                                                                $ConfigurationFee = $rst['ConfigurationFee'];
                                                                $CPEFee = $rst['CPEFee'];
                                                                $PackageName = $TarName." (".formatDate($CreatedDate, 4).")";
                                                                $tmppackage .= "<option value='".$PackageID."'>".$TarName."</option><br />";
                                                                $tmpdeposit .= "<option value='".$SpecialNumber."'>".$RegistrationFee."</option><br />";
                                                                $tmpother .= "<option value='".$ConfigurationFee."'>".$CPEFee."</option><br />";
                                                                $tmpother1 .= "<option value='".$ISDNFee."'>".$ISDNFee."</option><br />";
                                                            }
                                                        }
                                                        $mydb->sql_freeresult();

                                                    ?>
                                                    <!--<select name="PackageID" tabindex="2" onchange="autoValue(this.selectedIndex);">-->
                                                    <select name="PackageID" tabindex="71">
                                                    <option value="">Please select package</option>
                                                        <?php print $tmppackage; ?>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="left">Station:</td>
                                                <td align="left">
                                                    <select name="SelStationID" class="boxenabled" tabindex="72" onchange="storeNameValue(this.options[this.selectedIndex].text, 2);">
                                                        <option value="">Select Station</option>
                                                        <?php
                                                            $sql = "SELECT StationID, StationName from tlkpStation order by StationName";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $StationID = $rst['StationID'];
                                                                    $StationName = $rst['StationName'];
                                                                    if($SelStationID == $StationID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$StationID."' ".$sel.">".$StationName."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="left">Product type:</td>
                                                <td align="left">
                                                    <select name="CPETypeID" class="boxenabled" tabindex="73" onchange="storeNameValue(this.options[this.selectedIndex].text, 3);">
                                                        <?php
                                                            $sql = "SELECT CPEID, CPEName, SerialNumber from tlkpCPE WHERE Active = 1 order by CPEName";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $CPEID = $rst['CPEID'];
                                                                    $CPEName = $rst['CPEName'];
                                                                    $SerialNumber = $rst['SerialNumber'];
                                                                    print "<option value='".$CPEID."'>".$CPEName."(".$SerialNumber.")"."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">Account name / Telephone:</td>
                                                <td align="left">
                                                    <select name="SelPhonePreset" class="boxenabled" tabindex="74">
                                                        <?php
                                                            $sql = "SELECT PresetNumber, [Default] from tlkpPhonePreset order by PresetNumber";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $PresetNumber = $rst['PresetNumber'];
                                                                    $Default = $rst['Default'];
                                                                    if($Default)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$PresetNumber."' ".$sel.">".$PresetNumber."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                    <input type="text" name="UserName" id="UserName" value="<?php print $UserName;?>"  class="boxenabled" tabindex="75" size="21" onfocus="fn_getvalue();" onblur="ValidAccountAcc('dUserName');" maxlength="100" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName" class="error"></span><input type="text" id="sidd" name="sidd" style="display:none;"/>
                                                </td>
                                            </tr>
                                            <tr>   <!--===============Group Invoice================-->
                                                <td align="left">Group Invoice:</td>
                                                <td align="left">
                                                    <select name="gpInvoice" class="boxenabled" tabindex="76" onchange="storeNameValue(this.options[this.selectedIndex].text, 3);">
                                                        <?php
                                                            $sql = "SELECT ID,groupinvoice from tblgroupinvoice order by ID";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $ID = $rst['ID'];
                                                                    $groupinvoice = $rst['groupinvoice'];

																	if($radConnection==2)
																	{
																		$gID = 4;
																	}
																	else
																	{
																		$gID = 1;
																	}

																	if($ID==$gID)
																	{
																		print "<option value='".$ID."' selected>".$groupinvoice."</option>";
																	}
																	else
																	{
																		print "<option value='".$ID."'>".$groupinvoice."</option>";
																	}
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td align="left" nowrap="nowrap">
                                                    VAT number:
                                                </td>
                                                <td align="left">
                                                    <input type="text" name="txtaccVATNumber" class="boxenabled" tabindex="77" size="29" maxlength="30" value="<?php print $txtaccVATNumber;?>" />
                                                </td>
                                            </tr>
                                            <tr>
	                                            <td align="left" nowrap="nowrap">
                                                    VAT charge:
                                                </td>
                                                <td align="left">
                                                    <input type="radio" name="radaccExemption" tabindex="78" value="0" <?php if($radaccExemption == 0) print("checked"); ?> checked />YES&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="radaccExemption" tabindex="79" value="1" <?php if($radaccExemption == 1) print("checked"); ?> />NO
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">Password:</td>
                                                <td align="left">
                                                    <input type="password" name="Password" value="<?php print $Password;?>"  class="boxenabled" tabindex="80" size="30" maxlength="20" /><img src="./images/required.gif" border="0" /><span style="display:none" id="dPassword" class="error"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" nowrap="nowrap">Confirm password:</td>
                                                <td align="left">
                                                    <input type="password" name="ConfirmPassword" value="<?php print $ConfirmPassword;?>"  class="boxenabled" tabindex="81" size="30" maxlength="20" /><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left">Salesman:</td>
                                                <td align="left">
                                                    <select name="selSalesman" class="boxenabled" tabindex="82" onchange="storeNameValue(this.options[this.selectedIndex].text, 1);">
                                                        <option value="">Select salesman</option>
                                                        <?php
                                                            $sql = "SELECT SalesmanID, Salutation, Name from tlkpSalesman order by Country";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $SalesmanID = $rst['SalesmanID'];
                                                                    $Salutation = $rst['Salutation'];
                                                                    $Name = $rst['Name'];
                                                                    $salesmanname = $Salutation." ".$Name;
                                                                    if($selSalesman == $SalesmanID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$SalesmanID."' ".$sel.">".$salesmanname."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select><img src="./images/required.gif" border="0" />
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
							</div>
                        </td>
                    </tr>
					<tr><td>&nbsp;</td></tr>
                    <tr>
                    	<td>
                        	<!-- ================================ INSTALLTION 2================================-->
                        	<div id="billingaddress">
                                <table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
                                    <tr>
                                        <td align="center" class="formtitle" height="18"><b>BILLING ADDRESS</b> <font color="#000000">[<input type="checkbox" name="thesame3" onClick="setTheSameAddress(3);" tabindex="83">The same as customer profile ]</font></td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                            <tr>
                                                <td align="left">House:</td>
                                                <td align="left">
                                                    <input type="text" name="txtBilAddress" class="boxenabled" tabindex="84" size="50" maxlength="100" value="<?php print $txtBilAddress; ?>" />
                                                </td>
                                                <td align="left">Street:</td>
                                                <td align="left">
                                                    <input type="text" name="txtBilStreet" class="boxenabled" tabindex="85" size="29" maxlength="100" value="<?php print $txtBilStreet; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtBilAddressKhmer" class="boxenabled khfont" tabindex="84" size="50" maxlength="100" value="<?php print $txtBilAddressKhmer; ?>" />
                                                </td>
                                                <td align="left"><span class="khfont">ផ្លូវ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtBilStreetKhmer" class="boxenabled khfont" tabindex="85" size="29" maxlength="100" value="<?php print $txtBilStreetKhmer; ?>" />
                                                </td>
                                            </tr>
                                                <tr>
                                                    <td align="left">Country:</td>
                                                    <td align="left">
                                                        <select name="selBilCountry" class="boxenabled" tabindex="86" style="width:200px" onChange="storeNameValueBil(this.selectedIndex, 14);">
                                                        <option value="0" selected="selected">Unknown</option>
                                                            <?php
                                                                $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CountryID = $rst['id'];
                                                                        $Country = $rst['name'];
                                                                        if($selIntCountry == $CountryID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td align="left">City:</td>
                                                    <td align="left">
                                                        <select name="selBilCity" class="boxenabled" tabindex="87" style="width:200px" onChange="storeNameValueBil(this.selectedIndex, 13);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td align="left">Khan:</td>
                                                    <td align="left">
                                                        <select name="selBilKhan" class="boxenabled" tabindex="88" style="width:200px" onChange="storeNameValueBil(this.selectedIndex, 12);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                    <td align="left">Sangkat:</td>
                                                    <td align="left">
                                                        <select name="selBilSangkat" class="boxenabled" tabindex="89" style="width:200px" onChange="storeNameValueBil(this.selectedIndex, 11);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" nowrap="nowrap">Billing Email:</td>
                                                    <td colspan="3"><input type="text" name="txtBilEmail" tabindex="90" class="boxenabled" size="77" maxlength="50" value="<?php print $txtBilEmail; ?>" /></td>
                                                </tr>
                                         </table>
                                     </td>
                                     </tr>
                                </table>
                        	</div>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <!-- ================================ INSTALLTION ================================-->
                    <tr>
                    	<td>
                        	<div id="inst1">
                                <table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
                                <tr>
                                    <td align="center" class="formtitle" height="18"><b>INSTALLATION ADDRESS <?php if($radConnection == 1) print "FROM";?></b> <font color="#000000">[<input type="checkbox" name="thesame4" onClick="setTheSameAddress(4);" tabindex="91">The same as customer profile ]</font></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                        <tr>
                                            <td align="left">House:</td>
                                            <td align="left">
                                                <input type="text" name="txtIntAddress" class="boxenabled" tabindex="92" size="50" maxlength="100" value="<?php print $txtIntAddress; ?>" />
                                            </td>
                                            <td align="left">Street:</td>
                                            <td align="left">
                                                <input type="text" name="txtIntStreet" class="boxenabled" tabindex="93" size="29" maxlength="100" value="<?php print $txtIntStreet; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                            <td align="left">
                                                <input type="text" name="txtIntAddressKhmer" class="boxenabled khfont" tabindex="92" size="50" maxlength="100" value="<?php print $txtIntAddressKhmer; ?>" />
                                            </td>
                                            <td align="left"><span class="khfont">ផ្លូវ:</span></td>
                                            <td align="left">
                                                <input type="text" name="txtIntStreetKhmer" class="boxenabled khfont" tabindex="93" size="29" maxlength="100" value="<?php print $txtIntStreetKhmer; ?>" />
                                            </td>
                                        </tr>
                                            <tr>
                                                <td align="left">Country:</td>
                                                <td align="left">
                                                    <select name="selIntCountry" class="boxenabled" tabindex="94" style="width:200px" onChange="storeNameValueIns(this.selectedIndex, 14);">
                                                    <option value="0" selected="selected">Unknown</option>
                                                        <?php
                                                            $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                            // sql 2005

                                                            $que = $mydb->sql_query($sql);
                                                            if($que){
                                                                while($rst = $mydb->sql_fetchrow($que)){
                                                                    $CountryID = $rst['id'];
                                                                    $Country = $rst['name'];
                                                                    if($selIntCountry == $CountryID)
                                                                        $sel = "selected";
                                                                    else
                                                                        $sel = "";
                                                                    print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                }
                                                            }
                                                            $mydb->sql_freeresult();
                                                        ?>
                                                    </select>
                                                </td>
                                                <td align="left">City:</td>
                                                <td align="left">
                                                    <select name="selIntCity" class="boxenabled" tabindex="95" style="width:200px" onChange="storeNameValueIns(this.selectedIndex, 13);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td align="left">Khan:</td>
                                                <td align="left">
                                                    <select name="selIntKhan" class="boxenabled" tabindex="96" style="width:200px" onChange="storeNameValueIns(this.selectedIndex, 12);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>
                                                <td align="left">Sangkat:</td>
                                                <td align="left">
                                                    <select name="selIntSangkat" class="boxenabled" tabindex="97" style="width:200px" onChange="storeNameValueIns(this.selectedIndex, 11);">
                                                        <option value="0">Unknown</option>
                                                    </select>
                                                </td>
                                            </tr>
                                     </table>
                                 </td>
                                 </tr>
                            </table>
                        	</div>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                    	<td>
                        	<!-- ================================ INSTALLTION 2================================-->
                        	<div id="inst2" style="display:none;">
                                <table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
                                <tr>
                                    <td align="center" class="formtitle" height="18"><b>INSTALLATION ADDRESS TO</b> <font color="#000000">[<input type="checkbox" name="thesame5" onClick="setTheSameAddress(5);" tabindex="98">The same as customer profile ]</font></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2">
                                            <tr>
                                                <td align="left">House:</td>
                                                <td align="left">
                                                    <input type="text" name="txtLeaAddress" class="boxenabled" tabindex="99" size="50" maxlength="100" value="<?php print $txtLeaAddress; ?>" />
                                                </td>
                                                <td align="left">Street:</td>
                                                <td align="left">
                                                    <input type="text" name="txtLeaStreet" class="boxenabled" tabindex="100" size="29" maxlength="100" value="<?php print $txtLeaStreet; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left"><span class="khfont">ផ្ទះលេខ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtLeaAddressKhmer" class="boxenabled khfont" tabindex="99" size="50" maxlength="100" value="<?php print $txtLeaAddressKhmer; ?>" />
                                                </td>
                                                <td align="left"><span class="khfont">ផ្លូវ:</span></td>
                                                <td align="left">
                                                    <input type="text" name="txtLeaStreetKhmer" class="boxenabled khfont" tabindex="100" size="29" maxlength="100" value="<?php print $txtLeaStreetKhmer; ?>" />
                                                </td>
                                            </tr>
                                                <tr>
                                                    <td align="left">Country:</td>
                                                    <td align="left">
                                                        <select name="selLeaCountry" class="boxenabled" tabindex="101" style="width:200px" onChange="storeNameValueLea(this.selectedIndex, 14);">
                                                        <option value="0" selected="selected">Unknown</option>
                                                            <?php
                                                                $sql = "SELECT id, name from tlkpLocation where type = 1 order by name";
                                                                // sql 2005

                                                                $que = $mydb->sql_query($sql);
                                                                if($que){
                                                                    while($rst = $mydb->sql_fetchrow($que)){
                                                                        $CountryID = $rst['id'];
                                                                        $Country = $rst['name'];
                                                                        if($selLeaCountry == $CountryID)
                                                                            $sel = "selected";
                                                                        else
                                                                            $sel = "";
                                                                        print "<option value='".$CountryID."' ".$sel.">".$Country."</option>";
                                                                    }
                                                                }
                                                                $mydb->sql_freeresult();
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td align="left">City:</td>
                                                    <td align="left">
                                                        <select name="selLeaCity" class="boxenabled" tabindex="102" style="width:200px" onChange="storeNameValueLea(this.selectedIndex, 13);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td align="left">Khan:</td>
                                                    <td align="left">
                                                        <select name="selLeaKhan" class="boxenabled" tabindex="103" style="width:200px" onChange="storeNameValueLea(this.selectedIndex, 12);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                    <td align="left">Sangkat:</td>
                                                    <td align="left">
                                                        <select name="selLeaSangkat" class="boxenabled" tabindex="104" style="width:200px" onChange="storeNameValueLea(this.selectedIndex, 11);">
                                                            <option value="0">Unknown</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                         </table>
                                     </td>
                                     </tr>
                                </table>
                        	</div>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>

					<tr>
					<td align="center">
						<input type="button" tabindex="105" name="btnSubmit" value="Submit" class="button" onClick="ValidateForm();"/>
					</td>
				 </tr>
				</table>
					<input type="hidden" name="pg" id="pg" value="1">
					<input type="hidden" name="txtCusNationality" value="Cambodia" />
					<input type="hidden" name="txtCusOccupation" value="" />
					<input type="hidden" name="txtCusSangkat" value="" />
					<input type="hidden" name="txtCusKhan" value="" />
					<input type="hidden" name="txtCusCity" value="" />
					<input type="hidden" name="txtCusCountry" value="" />
					<input type="hidden" name="txtDesNationality" value="Cambodia" />
					<input type="hidden" name="txtDesOccupation" value="" />
					<input type="hidden" name="txtDesSangkat" value="" />
					<input type="hidden" name="txtDesKhan" value="" />
					<input type="hidden" name="txtDesCity" value="" />

                    <input type="hidden" name="aCountry" value="<?php print $aCountryID;?>" />
					<input type="hidden" name="aCity" value="<?php print $aCityID;?>" />
                    <input type="hidden" name="aKhan" value="<?php print $aKhanID;?>" />
					<input type="hidden" name="aSangkat" value="<?php print $aSangkatID;?>" />
                    <input type="hidden" name="aCountryText" value="<?php print $aCountryText;?>" />
					<input type="hidden" name="aCityText" value="<?php print $aCityText;?>" />
                    <input type="hidden" name="aKhanText" value="<?php print $aKhanText;?>" />
					<input type="hidden" name="aSangkatText" value="<?php print $aSangkatText;?>" />
					<input type="hidden" name="aAddress" value="<?php print $aAddress;?>" />
					<input type="hidden" name="aStreet" value="<?php print $aStreet;?>" />

					<input type="hidden" name="txtDesCountry" value="" />
                    <input type="hidden" name="CustomerID" value="<?php print $CustomerID;?>" />
					<input type="hidden" name="radCustType" value="<?php print $radCustomer;?>" />
                    <input type="hidden" name="radConnection" value="<?php print $radConnection;?>" />
                    <input type="hidden" name="smt" value="save" />
                    <input type="hidden" name="ext" value="<?php print $ext; ?>" />

		</td>
	</tr>
</table>
</form>

<script language="javascript">
	if(fsignupcust1.ext.value == "ap")
	{
			document.getElementById("mp").style.display = "none";
	}
	else
	{
			document.getElementById("mp").style.display = "block";
	}
</script>
