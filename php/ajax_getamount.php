<?php
	
	require_once("../common/agent.php");
	$t = $_GET['t'];
	$accid = $_GET['accid'];
	switch($t){
		case 1: # Credit
			$sql = "SELECT Credit as 'Amount' FROM tblAccountBalance WHERE AccID=$accid";
			break;
		case 2:	# Out standing
			$sql = "SELECT Outstanding as 'Amount' FROM tblAccountBalance WHERE AccID=$accid";
			break;
		case 7:	# NC Deposit
			$sql = "SELECT NationalDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 8:	# IC Deposit
			$sql = "SELECT InternationDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 9:	# MF Deposit
			$sql = "SELECT MonthlyDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 11:	# NC Deposit
			$sql = "SELECT NationalDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 12:	# IC Deposit
			$sql = "SELECT InternationDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 13:	# MF Deposit
			$sql = "SELECT MonthlyDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 4:	# Unpaid NC Deposit
			$sql = "SELECT UnNationalDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 5:	# Unpaid IC Deposit
			$sql = "SELECT UnInternationDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
		case 6:	# Unpaid MF Deposit
			$sql = "SELECT UnMonthlyDeposit as 'Amount' FROM tblAccDeposit WHERE AccID=$accid";
			break;
	}	
	
		if($que = $mydb->sql_query($sql)){
			if($result = $mydb->sql_fetchrow($que)){
				print $result['Amount'];
			}else{
				print "0";
			}
		}else{
			print "0";
		}
		
		
		//print "hh";
?>