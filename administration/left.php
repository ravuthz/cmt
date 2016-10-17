<?php
global $pg;

$bgformat=' bgcolor="#ffffff"; font-weight:bolder';
switch ($pg){
	case 1001: $bg1=$bgformat; break;
	case 1002: $bg2=$bgformat;	break;
	case 1003: $bg3=$bgformat; break;
	case 1004: $bg4=$bgformat; break;
	case 1005: $bg5=$bgformat; break;
	case 1006: $bg6=$bgformat; break;
	case 1007: $bg7=$bgformat; break;
	case 1008: $bg8=$bgformat; break;
	case 1009: $bg9=$bgformat; break;
	case 1010: $bg10=$bgformat; break;
	case 1011: $bg11=$bgformat; break;
	case 1012: $bg12=$bgformat; break;
	case 1013: $bg13=$bgformat; break;
    case 1015: $bg15=$bgformat; break;
    case 1016: $bg16=$bgformat; break;
    case 1017: $bg17=$bgformat; break;
    case 1018: $bg18=$bgformat; break;
    case 1019: $bg19=$bgformat; break;
    case 1020: $bg20=$bgformat; break;
    case 1021: $bg21=$bgformat; break;
    case 1022: $bg22=$bgformat; break;
    case 1023: $bg23=$bgformat; break;
    case 1100: $bg100=$bgformat; break;
    case 1101: $bg101=$bgformat;break;
    case 1102: $bg102=$bgformat;break;
    case 1103: $bg103=$bgformat;break;
	case 1024: $bg124=$bgformat;break;
	case 1025: $bg125=$bgformat;break;
}
?>
<fieldset>
				<legend align="center">Tariff Setup</legend>
					<table border="0" cellpadding="5" cellspacing="0" align="center" width="150" bordercolor="#aaaaaa" bgcolor="#feeac2" id="submenu">			
					<?php if($pg <1014) { ?>
					 <tr>
						  <td style="border-top:1px solid; text-align:left" <?php echo $bg9?>><a href="./?pg=1009">Billing Cycle</a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg11?>><a href="./?pg=1011">Package Setup</a></td>
					  </tr>
					    <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg13?>><a href="./?pg=1013">Weekend Day Setup</a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg8?>><a href="./?pg=1008">Special Day </a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg7?>><a href="./?pg=1007">Time Band </a></td>
					  </tr>
					  <tr>
						 	<td style="border-top:1px solid; text-align:left"  <?php echo $bg3?>><a href="./?pg=1003">Charge Block</a>						 	</td>		 
						</tr>
						<tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg4?>><a href="./?pg=1004">Charging Block Detail</a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg5?>><a href="./?pg=1005">Charging Band </a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg6?>><a href="./?pg=1006">Charging Code </a></td>
					  </tr>
					<tr>
						 	<td style="border-top:1px solid; text-align:left"  <?php echo $bg2?>><a href="./?pg=1002">Gate Way</a></td>		 
						</tr>
					<tr>
					<td style="border-top:1px solid; text-align:left"  <?php echo $bg12?>><a href="./?pg=1012">Tariff Setup</a></td>
					  </tr>				   
					  <?php } ?>
					  <?php if(($pg>1013 and $pg<1022) or $pg==1024){?>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg17?>><a href="./?pg=1017">Recuring Charge</a></td>
					  </tr>
					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg15?>><a href="./?pg=1015">Friend and Family Discount Setup</a></td>
					  </tr>
					   <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg20?>><a href="./?pg=1020">Call Allowance Discount</a></td>
					  </tr>
	 				  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg21?>><a href="./?pg=1021">Call MinuteBase Discount</a></td>
					  </tr>
	
					   <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg16?>><a href="./?pg=1016">Destination Specific Discount</a></td>
					  </tr>

					  <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg18?>><a href="./?pg=1018">Free Call Allowance</a></td>
					  </tr>
					 <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg19?>><a href="./?pg=1019">Tariff Discount</a></td>
					  </tr>
					  <tr>
					  	  <td style="border-top:1px solid; text-align:left"  <?php echo $bg124?>><a href="./?pg=1024">Tariff Minute Base Discount</a></td>
						  </tr>
					  <?php } else if($pg==1022 || $pg==1023 || $pg=1025){ ?>
					    <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg23?>><a href="./?pg=1023">Isp Overage Charge</a></td>
					  </tr>
					  	<tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg22?>><a href="./?pg=1022">Isp Tariff</a></td>
					  </tr>
					<tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg25?>><a href="./?pg=1025">ISP Time Band</a></td>
					  </tr>
					 <?php } else if($pg==1100 || $pg==1101 || $pg==1102 || $pg==1103){ ?>
					 
					  	<tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg100?>><a href="./?pg=1100">Credit Rule Limit</a></td>
					  </tr>
					   	<tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg101?>><a href="./?pg=1101">Credit Rule Invoice</a></td>
					  </tr>
					 <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg102?>><a href="./?pg=1102">Credit Rule Periodic</a></td>
					  </tr>
					   <tr>
						  <td style="border-top:1px solid; text-align:left"  <?php echo $bg103?>><a href="./?pg=1103">Apply Credit Rule to Package</a></td>
					  </tr>
					
					<?php  }?>
			
					  </tr>
					 </table>
</fieldset>