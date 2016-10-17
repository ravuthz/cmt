<?php
	require("intercon_inv_class.php");
	require("util_class.php");
	
	$com_id=10;
	$period="072007";
	
	$DecPlace=2;
	$util=new Utilities();
	
	$IntInv=new InterconInv($com_id,$period);
	$IntInv->GetInvoiceHeader();		
	$result=$IntInv->GetInvoiceBody();
				
?>

<style type="text/css">
<!--
.textsmall {font-family: Arial, Helvetica, sans-serif; font-size: 13px; }
.textbig  {font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight:bold;}
.timebold{font-family: "Times New Roman", Times, serif; text-align: center; font-size: 25px; font-weight:bold; }
.timenormal{font-family: "Times New Roman", Times, serif; text-align: center; font-size: 23px; }
.khmers1 { font-family:"Limon S1"; text-align:center; font-size:38px;}
.khmersmall { font-family:"Limon S1"; font-size:26px;}
.khmersmallest { font-family:"Limon S1"; font-size:25px;}
@font-face {
    font-family: Limon S1;
    font-style:  normal;
    font-weight: normal;
    src: url(LIMONS0.eot);
  }
.style1 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bold; }
.textsmall16 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
}
-->
</style>
<table width="730" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
	  <td colspan="3" class="timebold">&nbsp;</td>
  </tr>
	<tr><td height="34" colspan="3" class="timenormal">&nbsp;</td></tr>
	<tr>
	  <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
  </tr>
	<tr>
	  <td colspan="3" class="khmers1">vik&frac12;&aacute;yb&frac12;RtGakr<br />
      <span class="textbig">TAX INVOICE</span></td>
  </tr>
	<tr>
	  <td colspan="3">&nbsp;</td>
  </tr>
	<tr class="textsmall" valign="top">
	  <td height="26" colspan="2" align="right">&nbsp;&nbsp; </td>
      <td width="47%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Camitel VAT TIN : VT100045049</td>
  </tr>
	<tr>
	  <td colspan="2" class="textsmall16"><b><?=$IntInv->com_name;?></b></td>
      <td rowspan="5" valign="top">
	  <table width="100%" height="214" border="0" cellpadding="0" cellspacing="0" style="background-image:url(../images/invbg.png); background-position:right; background-position:top; background-repeat:no-repeat">
	  <tr valign="top"><td height="214"><table width="100%" border="2" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#000000">
        <tr>
          <td><table width="360" border="0" align="right" cellpadding="0" cellspacing="0">
			<tr>
				<td height="31" colspan="2" class="khmersmall" width="170"><div style="width:170">&nbsp;elxvik&frac12;&aacute;yb&frac12;Rt</div></td>
			    <td width="45" rowspan="2" class="textsmall"><div style="width:45">&nbsp;</div></td>
			    <td width="145" rowspan="2" class="textsmall" align="right"><div style="width:145"><?=$IntInv->inv_no;?>&nbsp;</div></td>
			</tr>
			<tr>
			  <td width="170" class="textsmall"><div style="width:170">&nbsp;Invoice No</div></td>
	          <td width="12" align="right" class="textsmall"><b>:</b></td>
		  </tr>
			</table>
			</td>
        </tr>
        <tr>
         	<td>
				<table width="360" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
			  			<td colspan="2" class="khmersmall" width="170"><div style="width:170">&nbsp;kalbriec&auml;T&eacute;nvik&frac12;&aacute;yb&frac12;Rt</div></td>
			  			<td rowspan="2" width="45" class="textsmall"><div style="width:45">&nbsp;</div></td>
		      			<td rowspan="2" width="145" class="textsmall" align="right"><div style="width:145"><?=$IntInv->issue_date;?>&nbsp;</div></td>
		  			</tr>
					<tr>
			  			<td class="textsmall" width="170"><div style="width:170">&nbsp;Date of Invoice</div></td>
		      			<td class="textsmall" align="right" width="12"><b>:</b></td>
					</tr>
				</table>
			</td>			  
        </tr>
        <tr>
          	<td>
				<table width="360" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						  <td colspan="2" class="khmersmall" width="170"><div style="width:170">&nbsp;putkMNt;karbg;R)ak;</div></td>
						  <td rowspan="2" width="45" class="textsmall"><div style="width:45">&nbsp;</div></td>
						  <td rowspan="2" width="145" class="textsmall" align="right"><div style="width:145"><?=$IntInv->due_date;?>&nbsp;</div></td>
				  	</tr>
					<tr>
					  	<td class="textsmall" width="170"><div style="width:170">&nbsp;Due Date</div></td>
					  	<td class="textsmall" align="right" width="12"><b>:</b></td>
				  	</tr>
				</table>
			</td>
        </tr>
        <tr>
          <td><table width="360" border="0" align="right" cellpadding="0" cellspacing="0"><tr>
			  <td height="31" colspan="2" class="khmersmall" width="170"><div style="width:170">&nbsp;TwkR)ak;RtUvbg;</div></td>
			  <td rowspan="2" width="45" class="textsmall"><div style="width:45">&nbsp;</div></td>
		      <td rowspan="2" width="145" class="textsmall" align="right"><div style="width:145"><b><?=$util->CurrencyFormat($IntInv->inv_amount,$DecPlace);?>&nbsp;</b></div></td>
		  </tr>
			<tr>
			  <td class="textsmall" width="170"><div style="width:170">&nbsp;Amount Due</div></td>
		      <td class="textsmall" align="right" width="12"><b>:</b></td>
		  </tr></table></td>
        </tr>
      </table>
	  	
		</td>
	  </tr></table>	  </td>
  </tr>
	<tr>
	  <td height="72" colspan="2" valign="top" class="textsmall"><?=$IntInv->addr;?></td>
  </tr>
	<tr>
	  <td class="textsmall">VAT TIN&nbsp;:&nbsp;</td>
      <td class="textsmall"><?=$IntInv->com_vatin;?></td>
  </tr>
	<tr>
	  <td height="77" colspan="2">&nbsp;</td>
  </tr>
	
	<tr class="textsmall">
	  <td width="9%" height="35" align="right">Attn:&nbsp;&nbsp; </td>
      <td width="44%"><?=$IntInv->attn;?></td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td class="textsmall"><?=$IntInv->position;?></td>
	  <td>&nbsp;</td>
  </tr>
	<tr>
	  <td height="41" colspan="3">&nbsp;</td>
  </tr>
	<tr class="textsmall">
	  <td height="28" colspan="3" valign="top"><strong>Subject: Invoice of Interconnect Charge for the month of March 2007</strong></td>
  </tr>
	<tr class="textsmall">
	  <td height="23" colspan="3" valign="bottom"><div style="margin-left:100">Incoming Calls into Camitel's Network</div></td>
  </tr>
	<tr>
	  <td colspan="3">
	  		<table width="500" border="0" cellpadding="0" cellspacing="0" align="right">
				<tr class="textsmall" valign="top">
					<td width="146" height="25" align="left">&nbsp;</td>
					<td width="94" align="center"><div align="right">Calls</div></td>
					<td width="89" align="center"><div align="right">Minutes</div></td>
					<td width="79" align="center"><div align="right">US$/Min</div></td>
					<td width="96" align="center"><div align="right">Total</div></td>
				<?
					while($row=mssql_fetch_array($result))
					{		
				?>
				<tr class="textsmall">				  
				  <td><div align="left"><?=$row["Description"];?></div></td>
				  <td><div align="right"><?=number_format($row["NoOfCall"],0);?></div></td>
				  <td><div align="right"><?=number_format($row["Unit"],$DecPlace);?></div></td>
				  <td><div align="right"><?=$util->CurrencyFormat($row["rate"],$DecPlace);?></div></td>
				  <td><div align="right"><?=$util->CurrencyFormat($row["Amount"],$DecPlace);?></div></td>
				  <?
				  	}
				  ?>
			    <!--<tr class="textsmall">
			      <td>Long Distance Calls </td>
			      <td><div align="right">0000</div></td>
			      <td><div align="right">0000</div></td>
			      <td><div align="right">$00.00</div></td>
			      <td><div align="right">$00.00</div></td>-->
		        <tr class="textsmall">
		          <td height="15" colspan="5" v><div style="margin-left:120" ><img src="../images/line.png" width="400" height="2" border="0" style="background-color:#000000" /></div></td>
              <tr class="textsmall">
                  <td colspan="3"><b><div style="margin-left:120">Interconnect Charges</div></b>                    <div align="center"></div></td>
                  <td><strong>:</strong></td>
                  <td><div align="right"><?=$util->CurrencyFormat($IntInv->net_amount,$DecPlace);?></div></td>
              <tr class="textsmall">
                <td colspan="3"><b><div style="margin-left:120">VAT @ 10%</div></b>                  <div align="center"></div></td>
                <td><strong>:</strong></td>
                <td><div align="right"><?=$util->CurrencyFormat($IntInv->vat_amount,$DecPlace);?></div></td>
              <tr class="textsmall">
                <td colspan="5" height="14">
			  		  <div style="margin-left:120"><img src="../images/line.png" width="400" height="2" border="0" style="background-color:#000000"/></div>				  </td>
                <tr class="textsmall">
                  <td colspan="3"><b><div style="margin-left:120">Invoice Total</div></b>                    <div align="center"></div></td>
                  <td><strong>:</strong></td>
                  <td><div align="right"><strong><?=$util->CurrencyFormat($IntInv->inv_amount,$DecPlace);?></strong></div></td>
                <tr class="textsmall">
                  <td colspan="5" height="14">
				  		<div style="margin-left:120"><img src="../images/line.png" width="400" height="3" border="0" style="background-color:#000000"/></div>				  </td>
            </table>	  </td>
  </tr>
	<tr>
	  <td colspan="3">&nbsp;</td>
  </tr>
	<tr class="textsmall">
	  <td colspan="3">Should you have any queries, please feel free to contact our Billing &amp; Treasy Department.<br />
	  Thank you in advance.<br />
	  Yours faithfully</td>
  </tr>
	<tr class="textsmall">
	  <td height="121" colspan="3">&nbsp;</td>
  </tr>
	<tr class="textbig">
	  <td colspan="3">Lay Mariveau </td>
  </tr>
	<tr class="textsmall">
	  <td colspan="3">Managing Director </td>
  </tr>
	<tr class="textsmall">
	  <td height="26" colspan="3">&nbsp;</td>
  </tr>
	<tr class="textsmall">
	  <td colspan="3"><em>Payment to be made by cheque to Camitel SA </em></td>
  </tr>
	<!--<tr class="textsmall">
	  <td height="139" colspan="3">&nbsp;</td>
  </tr>
	<tr class="textsmall">
	  <td height="31" colspan="3" align="center" class="khmersmallest">GaKarelx 01 kac;RCug tirvifI RBHsuIsuvt nig pSarEdk P&ntilde;MeBj km&lt;&uacute;Ca</td>
  </tr>
	<tr>
	  <td colspan="3"><div align="center"><font face="Arial, Helvetica, sans-serif" size="2">No 01 corner of Terak Vithei Preah Sisowath and Phsar Dek, Phnom Penh, Cambodia.<br />
	  Phone : 855-023-987689 Fax : 855-023-986277 Email : sales@camintel.com, www.camintel.com </font></div></td>
  </tr>-->
</table>
