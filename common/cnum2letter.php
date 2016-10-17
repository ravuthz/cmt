<?php
	/**
	 * Project name: BMS
	 *
	 * Created by: Chea Vey
	 * Created on: 23 May 2006
	 * 
	 * Copy right by BRC Technology.
	 *
	 *
	 * Convert a currency value into an (American) English string
	 *
	 */ 
	 
	 /**
	  *	This function use to convert from number into string
		* Input 	@number decimal
		* return 	@string
		*/
	 function CNum2Letter($number){
	 //	if(ereg("^([0-9]+)\.([0-9]+)", $number, $myArr)){
 		/*if($myArr = split("\.", $number)){
			EnglishDigitGroup($myArr[0]);
			print(" point "); 
			print(EnglishDigitGroup(substr($myArr[1], 0, 2)));
		}*/
			$negative = "";
			$point = " point ";
			$cents = " cents";
			$dollar		= " US dollar";
			$retOut = "";
	
		if($number == 0){
				$retOut = "Zero US dollar";
				return $retOut;
			}
			if($number < 0){
				$negative = "negative ";
				$number = abs($number);	
			}
			 
			$retOut .= $negative;
			$myArr = split("\.", $number, 2);				
			$beforPoint = $myArr[0];
			$afterPoint	=	0;
			if(sizeof($myArr)>1)
				$afterPoint = $myArr[1];											
			if($afterPoint < 10)
					$afterPoint *= 10;
					
			if($beforPoint == 0){
				$retOut .= "zero".$dollar;
			}else{
				$retOut .= EnglishBiggerGroup($beforPoint);
				$retOut .= $dollar;
			}
			$retOut .= $point;
			if($afterPoint == 0){
				$retOut .= "zero cent";	
			}else{
				$retOut .= EnglishBiggerGroup($afterPoint);
				$retOut .= $cents;
			}
			$retOut .= " only";
		
		return $retOut;
	 }
	 
	 /**
	 	*	This function uses to convert from number to string
		*	Input 	@number netween 1000 to unlimited
		*	Return 	@string
		*/
		
		function EnglishBiggerGroup($number){
			$retOut = "";
			$Thousand = 1000;
			$Million 	= $Thousand * $Thousand;
			$Billion 	= $Thousand * $Million;
			$Trillion = $Thousand * $Billion;
							
			# If number given more than trillion
			if($number >= $Trillion){			
				$tmpnumber  = (int) ($number / $Trillion);
				$retOut .= EnglishDigitGroup($tmpnumber);
				$retOut .= " trillion ";
				$number  = (int) ($number % $Trillion);
			}
			#if number given more than billion
			if($number >= $Billion){			
				$tmpnumber  = (int) ($number / $Billion);
				$retOut .= EnglishDigitGroup($tmpnumber);
				$retOut .= " billion ";
				$number  = ($number % $Billion);
			}
			#if number given more than million
			if($number >= $Million){			
				$tmpnumber  = (int) ($number / $Million);
				$retOut .= EnglishDigitGroup($tmpnumber);
				$retOut .= " million ";
				$number  = ($number % $Million);
			}
			#if number given more than Thousand
			if($number >= $Thousand){			
				$tmpnumber  = (int) ($number / $Thousand);
				$retOut .= EnglishDigitGroup($tmpnumber);
				$retOut .= " thousand ";
				$number  = ($number % $Thousand);
			}
			#if number given less than Thousand
			if($number < $Thousand){			
				$retOut .= EnglishDigitGroup($number);
			}
			return $retOut;
		} 
			
	 /**
	  *	This function uses to convert from number into string
		* Input 	@number between 1 to 999
		* return 	@string
		*/
		
	 function EnglishDigitGroup($number){	 				
		$thoudsand= 1000;
		$million	=	$thoudsand * $thoudsand;		
		$retOut		= "";
		$flag			= false;
		$numTem		=	0;
		
		# check if number more than 100
		$numTem	=	(int)($number / 100);
	
		switch ($numTem){
			case 0:
							$retOut .= "";
							$flag = false;
							break;
			case 1:
							$retOut .= "one";
							$flag = true;
							break;
			case 2:
							$retOut .= "two";
							$flag = true;
							break;
			case 3:
							$retOut .= "three";
							$flag = true;
							break;
			case 4:
							$retOut .= "four";
							$flag = true;
							break;
			case 5:
							$retOut .= "five";
							$flag = true;
							break;
			case 6:
							$retOut .= "six"; 
							$flag = true;
							break;
			case 7:
							$retOut .= "seven";
							$flag = true;
							break;
			case 8:
							$retOut .= "eight";
							$flag = true;
							break;
			case 9:
							$retOut .= "nine";
							$flag = true;
							break;
		}
		# number given > 100
		if($flag){
			$retOut	.= " hundred";
			$retOut	.= " ";			
			$flag = false;
		}
		$number = ($number % 100);
		#check if number given is more than 10 but less than 100		
		$numTem	=	(int)($number / 10);		
		
		switch ($numTem){
			case 0:
							$retOut .= "";
							$flag = false;
							break;
			case 1:
							$retOut .= "";
							$flag = false;							
							break;
			case 2:
							$retOut .= "twenty";
							$flag = true;
							break;
			case 3:
							$retOut .= "thirty";
							$flag = true;
							break;
			case 4:
							$retOut .= "forty";
							$flag = true;
							break;
			case 5:
							$retOut .= "fifty";
							$flag = true;
							break;
			case 6:
							$retOut .= "sixty"; 
							$flag = true;
							break;
			case 7:
							$retOut .= "seventy";
							$flag = true;
							break;
			case 8:
							$retOut .= "eighty";
							$flag = true;
							break;
			case 9:
							$retOut .= "ninety";
							$flag = true;
							break;
		}
		
		if($flag){						
			$retOut	.= " ";
			$number = ($number % 10);
			$flag = false;
		}
		# check if given number less than 20		
		switch ($number){
			case 0:
							$retOut .= "";
							$flag = false;
							break;
			case 1:
							$retOut .= "one";
							$flag = false;
							break;
			case 2:
							$retOut .= "two";
							$flag = true;
							break;
			case 3:
							$retOut .= "three";
							$flag = true;
							break;
			case 4:
							$retOut .= "four";
							break;
			case 5:
							$retOut .= "five";
							break;
			case 6:
							$retOut .= "six"; 
							break;
			case 7:
							$retOut .= "seven";
							break;
			case 8:
							$retOut .= "eight";
							break;
			case 9:
							$retOut .= "nine";
							break;
			case 10:
							$retOut .= "ten";
							break;
			case 11:
							$retOut .= "eleven";
							break;
			case 12:
							$retOut .= "twele";
							break;
			case 13:
							$retOut .= "thirteen";
							break;
			case 14:
							$retOut .= "forteen";
							break;
			case 15:
							$retOut .= "fifteen";
							break;
			case 16:
							$retOut .= "sixteen";
							break;
			case 17:
							$retOut .= "seventeen";
							break;
			case 18:
							$retOut .= "eighteen";
							break;
			case 19:
							$retOut .= "nineteen";
							break;
		}
		return $retOut;
	}	
?>