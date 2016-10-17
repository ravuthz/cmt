<?php
	/**
	 ** Function for convert html tag
	 **
	 ** Created by: Chea vey
	 ** Created on: 2006 december 27
	 **/	
	 
		function xmlEncode($str){
			$str=str_replace("&","&amp;",$str);
			$str=str_replace("<","&lt;",$str);
			$str=str_replace(">","&gt;",$str);
			return $str;
		}
			
	/**
	 ** Function redirect page
	 **
	 ** Created by: Chea vey
	 ** Created on: 2007 January 10
	 ** 
	 **/
	 	function redirect($url){
			print "<script>window.location='".$url."';</script>";
		}
	 
	/**
	 ** Function for convert Convert datetime
	 **
	 ** Created by: Chea vey
	 ** Created on: 2007 January 10
	 ** 
	 **/	
		function formatDate($strdate = "", $mode){
			
			$date = strtotime($strdate);			
			if(empty($date)) return;
			$sep = "/";
			$com = ",";
			
			# ============ AM/PM ===========
			# am/pm
			$a = date("a", $date);
			# AM/PM
			$A = date("A", $date);
			
			# ============ Second ==========
			# ss (00 - 59)
			$s = date("s", $date);
			
			# ============ Minute ==========
			# mn (00 - 59)
			$i = date("i", $date);
			
			# ============ Hour ============
			# hh (1 - 12)
			$g = date("g", $date);
			# hh (01 - 12)
			$h = date("h", $date);
			# hh (0 - 23)
			$G = date("G", $date);
			# hh (00 - 23)
			$H = date("H", $date);
			
			# ============ Date ============
			# dd (1 - 31)
			$j = date("j", $date);
			# dd (01 - 31)			
			$d = date("d", $date);
			# ddd (mon-sun)
			$D = date("D", $date);
			# dddd (monday - sunday)
			$l = date("l", $date);
			
			# ============ Month ============
			# mm (1 - 12)
			$n = date("n", $date);
			# mm (01 - 12)
			$m = date("m", $date);
			# mmm (jan - dec)
			$M = date("M", $date);
			# mmmm (january - december)
			$F = date("F", $date); 
			
			# ============ year =============
			# yy
			$y = date("y", $date);
			# yyyy
			$Y = date("Y", $date);
			
			switch($mode){
				case 1: # dd/mm/yy (01/01/07)
					$retDate = $d.$sep.$m.$sep.$y;
					break;
				case 2: # dd/mm/yyyy (01/01/2007)
					$retDate = $d.$sep.$m.$sep.$Y;
					break;
				case 3: # dd/mmm/yy (01/jan/07)
					$retDate = $d.$sep.$M.$sep.$y; 
					break;
				case 4: # yyyymmdd (20070101)
					$retDate = $Y.$m.$d;
					break;
				case 5: #YYYY-mm-dd
					$retDate = $Y."-".$m."-".$d;
					break;
				case 6: #dd mmmm yyyy
					$retDate = $d." ".$F." ".$Y;
					break;
				case 7: #dd mmm yyyy hh:mm:ss
					$retDate = $d." ".$F." ".$Y." ".$H.":".$i.":".$s;
					break;
			} 
			
			return $retDate;
		}
		
		/*
		 * Format currency
		 */
		 
		function FormatCurrency($Amount, $currency = "\$", $type = 1){
			# 100 cents
			if($type == 1)
				$Amount = $Amount;
			# 10 cents
			elseif($type == 2)
				$Amount = $Amount / 10;
			# 1 cent
			elseif($type == 3)
				$Amount = $Amount / 100;
			return $currency.number_format($Amount, 2);
		}
		
		/*
		 * Format length of number
		 */
		 function FormatLength($Number, $Len){
		 	$retOut = "";
			if($Len <= 0)
				$retOut = $Number;
			else{
				if(strlen($Number) > $Len)
					$retOut = $Number;
				else{
					for ($i=1; $i<=($Len - strlen($Number)); $i++)
						$retOut .= "0";
					$retOut .= $Number;
				}
			}
			return $retOut;
		 }
		 
		 /*
		 	* Format hour
			* hh:mm:ss
		 */
		 function FormatHour($second){
				$hour = intval($second / 3600);
				$second %= 3600;
				$minute = intval($second / 60);
				$second %= 60;
				return FormatLength($hour, 2).":".FormatLength($minute, 2).":".FormatLength($second, 2);
			}
		 
		/*
		 * Fix the quotes of string for database update
		 */
		function FixQuotes ($what = "") {
			$what = ereg_replace("'","''",$what);
			while (eregi("\\\\'", $what)) {
			$what = ereg_replace("\\\\'","'",$what);
			}
			return $what;
		}
		
		/**
		 ** Function get id
		 **
		 ** Created by: Chea vey
		 ** Created on: 2006 January 10
		 **/
		 
		 function getID($field){
		 		global $mydb;
				$sql = "select NextValue from tlkpNext where NextName='".$field."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						$ID = $result['NextValue'];
						# update next value
						$sql1 = "update tlkpNext set NextValue=".($ID + 1)." where NextName='".$field."'";
						if($mydb->sql_query($sql1))
							return $ID;
						else
							return false;
					}						
				}else
					return false;
		 }
		 
		 
		 /**
		 ** Function get configue value
		 **
		 ** Created by: Chea vey
		 ** Created on: 2006 January 10
		 **/
		 function getConfigue($Name){
		 		global $mydb;
				$sql = "select ConfigueValue from globalConfigs where ConfigueName='".$Name."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que))
						return $result['ConfigueValue'];						
					else
						return false;											
				}else
					return false;
		 }
		 
		 /**
		 ** Function get Invoice Item ID
		 **
		 ** Created by: Chea vey
		 ** Created on: 2006 January 10
		 **/
		 function getInvoiceItem($ItemName){
		 		global $mydb;
				$sql = "select ItemID from tlkpInvoiceItem where ItemName='".$ItemName."'";
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que))
						return $result['ItemID'];						
					else
						return false;											
				}else
					return false;
		 }
		 
		 /**
		 ** Function get cashier cash drawer
		 **
		 ** Created by: Chea vey
		 ** Created on: 2006 January 26
		 **/
		 function GetDrawerID($uerid){
		 	global $mydb;
			$sql = "SELECT DrawerID FROM tblDrawer WHERE UserID = $uerid and Status = 0";
			if($que = $mydb->sql_query($sql)){
				if($mydb->sql_numrows() > 0){
					if($result = $mydb->sql_fetchrow($que)){
						return $result['DrawerID'];
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
		
	//
	//	Dateadd function
	//	$v: number of date to be added
	//	$d: giving date // if null than default is today
	//	$f: format date out put
	//
	
	function DateAdd($v, $d=null, $f="d/M/Y"){ 
		$d=($d?$d:date("Y-m-d")); 
		return date($f,strtotime($v." days",strtotime($d))); 
	}
	
	//
	//	Datediff function
	//
	function datediff($start_date,$end_date="now",$unit="D")
		{
			$unit = strtoupper($unit);
			$start=strtotime($start_date);
			if ($start === -1) {
				$retOut = "invalid start date";
			}
			
			$end=strtotime($end_date);			
			if ($end === -1) {
				$retOut = "invalid end date";
			}
			
			//if ($start > $end) {
//				$temp = $start;
//				$start = $end;
//				$end = $temp;
//			}
			
			$diff = $end-$start;
			
			$day1 = date("j", $start);
			$mon1 = date("n", $start);
			$year1 = date("Y", $start);
			$day2 = date("j", $end);
			$mon2 = date("n", $end);
			$year2 = date("Y", $end);
			
			switch($unit) {
				case "D":
					$retOut = intval($diff/(24*60*60));
					break;
				case "M":
					if($day1>$day2) {
						$mdiff = (($year2-$year1)*12)+($mon2-$mon1-1);
					} else {
						$mdiff = (($year2-$year1)*12)+($mon2-$mon1);
					}
					$retOut = $mdiff;
					break;
				case "Y":
					if(($mon1>$mon2) || (($mon1==$mon2) && ($day1>$day2))){
						$ydiff = $year2-$year1-1;
					} else {
						$ydiff = $year2-$year1;
					}
					$retOut = $ydiff;
					break;
				case "YM":
					if($day1>$day2) {
						if($mon1>=$mon2) {
							$ymdiff = 12+($mon2-$mon1-1);
						} else {
							$ymdiff = $mon2-$mon1-1;
						}
					} else {
						if($mon1>$mon2) {
							$ymdiff = 12+($mon2-$mon1);
						} else {
							$ymdiff = $mon2-$mon1;
						}
					}
					$retOut = $ymdiff;
					break;
				case "YD":
					if(($mon1>$mon2) || (($mon1==$mon2) &&($day1>$day2))) {
						$yddiff = intval(($end - mktime(0, 0, 0, $mon1, $day1, $year2-1))/(24*60*60));						
					} else {
						$yddiff = intval(($end - mktime(0, 0, 0, $mon1, $day1, $year2))/(24*60*60));
					}
					$retOut = $yddiff;
					break;
				case "MD":
					if($day1>$day2) {
						$mddiff = intval(($end - mktime(0, 0, 0, $mon2-1, $day1, $year2))/(24*60*60));						
					} else {
						$mddiff = intval(($end - mktime(0, 0, 0, $mon2, $day1, $year2))/(24*60*60));
					}
					$retOut =$mddiff ;
					break;
				default:
     			print("{Datedif Error: Unrecognized \$unit parameter. Valid values are 'Y', 'M', 'D', 'YM'. Default is 'D'.}");
				
			}
			return $retOut;
		}
?>