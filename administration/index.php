<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
-->

<link type="text/css" rel="stylesheet" href="../style/mystyle.css" />
<script language="JavaScript" src="../javascript/date.js"></script>
<script language="JavaScript" src="../javascript/trim.js"></script>
<script language="JavaScript" src="../javascript/function.js"></script>

<div id="work">

	<table width="100%">
	<tr><td valign="top">
	<?php
	include_once("left.php");
	?>
	</td>
	<td valign="top">
	<?php
		switch($pg){
			case 1012: 
			include("./administration/tariff.php");
			break;			
			case 1002:
				include("./administration/gateway.php");
				break;
			case 1003: 
				include("./administration/chargeblock.php");
				break;
			case 1004: 
				include("./administration/chargeblockdetail.php");
				break;
			case 1005: 
				include("./administration/chargingband.php");
				break;
			case 1006: 	
				include("./administration/chargingcode.php");
				break;
			case 1007: 
				include("./administration/timeband.php");
				break;
			case 1008: 
				include("./administration/specialdaysetup.php");
				break;
		  case 1009: 
				include("./administration/billingcycle.php");
				break;
			case 1011: 
				include("./administration/package.php");
				break;
			case 1012: 
				include("./administration/tariff.php");
				break;
			case 1013: 
			include("./administration/weekenddaysetup.php");
			break;
			case 1015: 
			include("./administration/discountfnf.php");
			break;
			case 1016: 
			include("./administration/discountdes.php");
			break;
			case 1017: 
			include("./administration/recuringcharge.php");
			break;
			case 1018: 
			include("./administration/discountfreecall.php");
			break;
			case 1019: 
			include("./administration/discounttariff.php");
			break;
			
			case 1020: 
	
			include("./administration/discountcallallowance.php");

			case 1021: 
	
			include("./administration/discountminutedisc.php");
			break;
			
			case 1022: 
	
			include("./administration/isptariff.php");
			break;
			case 1023: 
	
			include("./administration/ispoverchargeblock.php");
			break;
			
			case 1100: 
	
			include("./administration/creditlimitrule.php");
			break;
			case 1101: 
	
			include("./administration/creditruleinvoice.php");
			break;
			case 1102: 
	
			include("./administration/creditlimitunpaidperiod.php");
			break;
			
			case 1103: 
	
			include("./administration/credittariff.php");
			break;
			default: 
				include("./administration/top.php");				
				break;
		
		}
	?>
	</td>
	</tr>
	</table>

</div>
			