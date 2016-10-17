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
<script language="javascript" type="text/javascript">
	
	function viewInfo(ID)
	{
		window.open("RequestView.php?&ID="+ID);

	}
	
	function checkradio(f)
	{
		if(f.service[0].checked)
		{
			alert("Telephone");
		}
		else if(f.service[1].checked)
		{
			alert("Internet");
		}
		else
		{
			alert("Lease Line");
		}
	}
	
	function checkusername(event)
	{
		if(event.keyCode==13)
		{
			window.location="requestform.php?&opt=edit&username="+frmDisplay.txtusername.value;
		}
	}

	function checkuseraction()
	{
			re=0;		
			d=0;
			dl=0;
			frmDisplay.stD.value=trim(frmDisplay.stD.value);
			red=0;
			didd=0;
			dndd=0;
			dinter=0; 
			ridd=0;
			rndd=0;
			rinter=0;
			// Change and Move
				loca=0;
				nm=0;
				number=0;
				sys=0;
				monthlyfee=0;
				to=0;
				fee=0;
				de=0;
			//
			
			
			if(frmDisplay.stD.value.length==0)
			{
				alert("Please fill request date.");
				red=1;
				return;
			}			
			if(frmDisplay.chmovelo.checked==true)
			{
				d=1;
				loca=1;
			}
			if(frmDisplay.chmovena.checked==true)
			{  
				d=1;
				nm=1;
			}
			if(frmDisplay.chmovenu.checked==true)
			{
				d=1;
				number=1;
			}
			if(frmDisplay.chmovesys.checked==true)
			{
				d=1;
				sys=1;
			}
			if(frmDisplay.chmovemo.checked==true)
			{
				d=1;
				monthlyfee=1;
			}
			
			
				
			dl=0;
			if(d==1)
			{
					frmDisplay.txtto.value=trim(frmDisplay.txtto.value);
					frmDisplay.txtfee.value=trim(frmDisplay.txtfee.value);
					if(frmDisplay.txtto.value.length==0 && frmDisplay.txtfee.value.length==0)
					{
						alert("Please fill the information in Text Box To or Fee.");
						dl=0;
						return;
					}
					else 
					{
						dl=1;
					}
			}
			distype=0;
			drr=1;
			tt=1;
			if(frmDisplay.dis[0].checked)
			{
				drr=0;
				frmDisplay.stdisfrom.value=trim(frmDisplay.stdisfrom.value);
				frmDisplay.stdisto.value=trim(frmDisplay.stdisto.value);
				if(frmDisplay.stdisfrom.value.length==0 || frmDisplay.stdisto.value.length==0)
				{
					tt=0;
					alert("Please fill start and from date to stop temporary");
					return;
				}
				else
					distype=1;
			}
			else if(frmDisplay.dis[1].checked)
			{
				drr=0;
				frmDisplay.strecfrom.value=trim(frmDisplay.strecfrom.value)
				if(frmDisplay.strecfrom.value.length==0)
				{
					tt=0;
					alert("Please fill reconnection date to terminate.");
					return;
				}
				else
					distype=2;
			}
			
			frmDisplay.txtamt.value=trim(frmDisplay.txtamt.value);
			if(frmDisplay.deidd.checked==true)
			{
				didd=1;
				de=1;
			}
			if(frmDisplay.dendd.checked==true)
			{
				dndd=1;
				de=1;
			}
			if(frmDisplay.deinter.checked==true)
			{
				dinter=1;
				de=1;
			}
		
		
			re=0;
			if(frmDisplay.reidd.checked==true)
			{
				ridd=1;
				re=1;
			}
			if(frmDisplay.rendd.checked==true)
			{
				rndd=1;
				re=1;
			}
			if(frmDisplay.reinter.checked==true)
			{
				rinter=1;
				re=1;
			}
			
			if(frmDisplay.txtamt.value.length==0)
			{
				amt=0;
			}
			else
			{
			
				amt=1;
			}
			
			dr=1;
			if((de==1 || re==1) && amt==0)
			{
				alert("Please fill ammount.");
				dr=0;
				return;
			}
			else if(de==0 && re==0 && amt==1)
			{
				alert("Please select deposit or refund only.");
				dr=0;
				return;
			}
			sgn=0;
			frmDisplay.txtaname.value=trim(frmDisplay.txtaname.value);
			frmDisplay.txtacname.value=trim(frmDisplay.txtacname.value);
			frmDisplay.txtafrom.value=trim(frmDisplay.txtafrom.value);
			frmDisplay.txtacfrom.value=trim(frmDisplay.txtacfrom.value);
			if(frmDisplay.txtaname.value.length==0 || frmDisplay.txtacname.value.length==0 || frmDisplay.txtafrom.value.length==0 || frmDisplay.txtacfrom.value.length==0)
			{
				sgn=1;
				alert("Please fill authorized person information.");
				return;
			}
			
			if((dl==1 && d==1) || (dr==1 && (de==1 || re==1)) || (tt==1 && drr==0) || sgn==0)
			{
				cm="requestform.php?&opt=add&txtusername="+frmDisplay.txtusername.value+"&PaccID="+frmDisplay.PaccID.value+"&CustID="+frmDisplay.CustID.value+"&stD="+frmDisplay.stD.value;
				cm+="&loca="+loca+"&nm="+nm+"&number="+number+"&sys="+sys+"&monthlyfee="+monthlyfee+"&to="+frmDisplay.txtto.value+"&fee="+frmDisplay.txtfee.value;
				cm+="&distype="+distype+"&stdisfrom="+frmDisplay.stdisfrom.value+"&stdisto="+frmDisplay.stdisto.value+"&strecfrom="+frmDisplay.strecfrom.value;
				if(re==1)
				{
					dre=1;
					ndd=rndd;
					idd=ridd;
					inter=rinter;
				}
				else if(de==1)
				{
					dre=2;
					ndd=dndd;
					idd=didd;
					inter=dinter;
				}
				else
				{
					dre=0;
					ndd=0;
					idd=0;
					inter=0;
				}
				
				cm+="&dre="+dre+"&idd="+idd+"&ndd="+ndd+"&inter="+inter+"&amt="+frmDisplay.txtamt.value;
				cm+="&txtaname="+frmDisplay.txtaname.value+"&txtafrom="+frmDisplay.txtafrom.value+"&txtato="+frmDisplay.txtato.value+"&txtacname="+frmDisplay.txtacname.value+"&txtacfrom="+frmDisplay.txtacfrom.value+"&txtacto="+frmDisplay.txtacto.value;
				cm+="&other="+frmDisplay.txtother.value+"&reason="+frmDisplay.txtreason.value;
				window.location=cm;
				return;
			}
			else
			{ 
				alert("Please, fill information requested.");
			}
			
			dl=0;
			d=0;
			dr=0;
			de=0;
			re=0;
			tt=0;
			didd=0;
			dndd=0;
			dinter=0;
			ridd=0;
			rndd=0;
			rinter=0;
			amt=0;
	}

	function LTrim( value ) {
		
		var re = /\s*((\S+\s*)*)/;
		return value.replace(re, "$1");
		
	}
	
	// Removes ending whitespaces
	function RTrim( value ) {
		
		var re = /((\s*\S+)*)\s*/;
		return value.replace(re, "$1");
		
	}
	
	// Removes leading and ending whitespaces
	function trim( value ) {
		
		return LTrim(RTrim(value));
		
	}

	
</script>
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

	#============ Customer billing information ===========================#%
	$stD=date('Y-m-d');
	if($opt=='edit')
	{
		$opt="";
		
		$sql = "select top 1 P.CustID,P.AccID PaccID,username,SubscriptionName,case when T.ServiceID=2 then 'PHONE' when T.serviceID=4 then 'Lease' else 'ISP'
				 end TarName ,Ct.Address + ' ,' + Sangkat.Name + ' ,' + KhanName.Name + ' ,' + CityName.Name + ' ,' + CountryName.Name Address,CustTypeID,CycleFee,f.*
				from tblCustomer C Inner Join tblCustProduct P on C.CustID=P.CustID Inner Join tblTarPackage T on P.packageID=T.packageID Inner Join tblCustAddress 
				Ct on P.accID=Ct.AccID Left Join tlkpLocation KhanName on KhanName.ID=Ct.KhanID Left Join tlkpLocation CityName on CityName.ID=Ct.AccID Left Join
				 tlkpLocation Sangkat on Sangkat.ID=Ct.SangkatID Left Join tlkpLocation CountryName on CountryName.ID=Ct.CountryID  Left Join tblformrequest f on
				  p.accID=f.accID where statusID<>4 and IsBillingAddress=1 and username='".$username."'";
				   
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
					
					$dFrom = $result['dFrom'];		
					$dTo = $result['dTo'];	
					$rFrom = $result['rFrom'];		
					$Specify = $result['Specify'];		
					$Reason = $result['Reason'];
					$drType = $result['drType'];
					$IDD = $result['IDD'];
					$NDD = $result['NDD'];
					
					$Internet = $result['Internet'];		
					$Amount = $result['Amount'];	
					$Location = $result['Location'];		
					$Name = $result['Name'];		
					$Number = $result['Number'];
					$System = $result['System'];
					$MonthlyFee = $result['MonthlyFee'];
					$Fee = $result['Fee'];
					$To = $result['To'];	
					
					$NameAuth = $result['NameAuth'];		
					$DateAuth = $result['DateAuth'];	
					$TimeAuth = $result['TimeAuth'];
					$NameAcknow = $result['NameAcknow'];		
					$DateAcknow = $result['DateAcknow'];		
					$TimeAcknow = $result['TimeAcknow'];
					$stM = $result['CycleFee'];
				}
				$mydb->sql_freeresult();
	}
	else if($opt=='add')
	{

		$now = date("Y/M/d H:i:s");
		$sql="insert into tblformrequest(AccID,Date,distype,dFrom,dTo,rFrom,Specify,Reason,drType,IDD,NDD,Internet,Amount,Location,[Name],Number,[System],MonthlyFee,Fee,[To],NameAuth ,DateAuth,TimeAuth,NameAcknow,DateAcknow,TimeAcknow) values(".doubleval($PaccID).",'$stD',$distype,'$stdisfrom','$stdisto','$strecfrom','$specify','$other',$dre,$idd,$ndd,$inter,".doubleval($amt).",$loca,$nm,$number,$sys,$monthlyfee,'$fee','$to','$txtaname','$txtafrom','$txtato','$txtacname','$txtacfrom','$txtacto')";

		 if($mydb->sql_query($sql))
		 {
			$retOut = $myinfo->info("Success to add requested information", $error["message"]);
			$mydb->sql_close();
			
			
				if($distype<>0)
				{
					$sql="Insert into tblJobAcctStatus(AccID,CurrentStatusID,NewStatusID,SubmitDate,EffectiveDate,Comment,IsDone,Incoming,
		Outgoing,International,IsConfirm,IncomingLoc,IncomingNat,OutgoingLoc,OutgoingNat,Other,DoneDate) values(".doubleval($PaccID).",1,3,'$stD','$stD','Request to terminate',0,1,1,1,0,0,0,0,0,0,'$stD')";
		
						
					 if($mydb->sql_query($sql))
					 {
						// do nothing.....
					 }
					 else
					 {
						$retOut = $myinfo->info("Failed to terminate.", $error["message"]);
					 }
				}
				
				if($strecfrom<>'')
				{
					$sql="Insert into tblJobAcctStatus(AccID,CurrentStatusID,NewStatusID,SubmitDate,EffectiveDate,Comment,IsDone,Incoming,
		Outgoing,International,IsConfirm,IncomingLoc,IncomingNat,OutgoingLoc,OutgoingNat,Other,DoneDate) values(".doubleval($PaccID).",0,1,'$stD','$stD','Request to Reconnect for $txtusername',0,1,1,1,0,0,0,0,0,0,'$stD')";
		
						
					 if($mydb->sql_query($sql))
					 {
						// do nothing.....
					 }
					 else
					 {
						$retOut = $myinfo->info("Failed to terminate.", $error["message"]);
					 }
				}
				
				///--------------------------------------
				
				if($sys==1)
				{
					 $sql="Insert into tblJobAcctStatus(AccID,CurrentStatusID,NewStatusID,SubmitDate,EffectiveDate,Comment,IsDone,Incoming,
		Outgoing,International,IsConfirm,IncomingLoc,IncomingNat,OutgoingLoc,OutgoingNat,Other,DoneDate) values(".doubleval($PaccID).",1,3,'$stD','$stD','Request to change System to $to',0,1,1,1,0,0,0,0,0,0,'$stD')";
								

					 if($mydb->sql_query($sql))
					 {
						// do nothing.....
					 }
					 else
					 {
						$retOut = $myinfo->info("Failed to change System.", $error["message"]);
					 }
				}
				
				if($number==1)
				{
					 $sql="Insert into tblJobAcctStatus(AccID,CurrentStatusID,NewStatusID,SubmitDate,EffectiveDate,Comment,IsDone,Incoming,
		Outgoing,International,IsConfirm,IncomingLoc,IncomingNat,OutgoingLoc,OutgoingNat,Other,DoneDate) values(".doubleval($PaccID).",1,3,'$stD','$stD','Request to change Number to $to',0,1,1,1,0,0,0,0,0,0,'$stD')";
		
					 if($mydb->sql_query($sql))
					 {
						// do nothing.....
					 }
					 else
					 {
						$retOut = $myinfo->info("Failed to change Number.", $error["message"]);
					 }
				}
				//---------------------------------------
				

					$cscomment='';
					if($nm==1)
					{
						
							$cscomment='Name';
							
					}
					if($loca==1)
					{
							if($cscomment!='')
							{
								$cscomment.=' & Location';
							}
							else
							{
								$cscomment='Location';
							}
					}
					if($monthlyfee==1)
					{
							if($cscomment!='')
							{
								$cscomment.=' & Monthly Fee';
							}
							else
							{
								$cscomment='Monthly Fee';
							}
							
					}
					
					/*if($number==1)
					{
							if($cscomment!=''))
							{
								$cscomment.=' & Number';
							}
							else
							{
								$cscomment='Number';
							}
							
					}*/
					
				
						$drcomment='';
						$rdcomment='';
						if($idd==1)
						{
							$drcomment='IDD';
						}
						if($ndd==1)
						{
							if($drcomment<>'')
							{
								$drcomment.=' & NDD';
							}
							else
							{
								$drcomment='NDD';
							}
						}	
						if($inter==1)
						{
							if($drcomment<>'')
							{
								$drcomment.=' & Internet';
							}
							else
							{
								$drcomment='Internet';
							}
						}	
						
						if($dre==2)
						{					
							$rdcomment='Deposit '.$drcomment.' of '.$amt.'';
						}
						else if($dre==1)
						{
							$rdcomment='Refund '.$drcomment.' of '.$amt.'';
						}
	
						if($cscomment<>'' && $rdcomment<>'')
						{
							$cscomment='change '.$cscomment.' to '.$to.' & '.$rdcomment;
						}
						else if($cscomment<>'' && $rdcomment=='')
						{
							$cscomment='change '.$cscomment.' to '.$to;
						}
						else if($cscomment=='' && $rdcomment<>'')
						{
							$cscomment=$rdcomment;
						}
	
	
					
					/*if($strecfrom!='')
					{
						if($cscomment!='')
						{
							$cscomment.=' & Reconnect for $txtusername from $strecfrom onward.';
						}
						else
						{
							$cscomment='Reconnect for $txtusername from $strecfrom onward.';
						}
					}*/
					
					if($cscomment<>'')
					{
							$sql="Insert into tblJobRequestStatus(AccID,CustID,SubmitDate,RequestComment) values($PaccID,$CustID,'$now','$cscomment')";
		
							
							 if($mydb->sql_query($sql))
							 {
								// do nothing.....						
							 }
							 else
							 {
								$retOut = $myinfo->info("Failed to change $cscomment.", $error["message"]);
							 }
					}
		

				$sql="select Top 1 ID from tblformrequest where accID=$PaccID order by ID Desc";

				$mydb->sql_query($sql);
				if($result = $mydb->sql_fetchrow())
				{
					$ID=$result['ID'];
				}

		 }
		 else
		 {
		 	$retOut = $myinfo->info("Failed to add requested information", $error["message"]);
		 }
		$opt='';
	}//add
	
/*# =============== Get account information =====================
	$sql = "select	Comment,
					SWComment,
					ISPComment,
					MDFComment,
					OPComment 
			from tblJobAcctStatus
			WHERE AccID = $AccountID ";
	if($que = $mydb->sql_query($sql)){
ha
			$Comment = $rst['Comment'];
			$SWComment = $rst['SWComment'];
			$ISPComment = $rst['ISPComment'];
			$MDFComment = $rst['MDFComment'];
			$OPComment = $rst['OPComment'];	
		}
	}
	$mydb->sql_freeresult();
*/
?>

<form name="frmDisplay">
<table border="0" cellpadding="3" cellspacing="0" width="800 px" bordercolor="#000000" align="center" >
	<tr> 						<!-- ROW BLOCK:::: Camintel Logo -->
		<td colspan="2" align="left">
			<img src="../images/Log-CMT.gif" width="170px" height="50px" border="0"/>
		</td>
	</tr>
	<tr> 						<!-- ROW BLOCK:::: Form Title -->
		<td colspan="2" align="center">
			<font size="+2" color="#000000"><b>Customer Request Form<br /></b></font>
		</td>
	</tr>
	<tr>						<!-- ROW BLOCK:::: Service Type and Account -->
		<td colspan="2" align="right">
			<table border="0">
				<tr>
					<td><font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Services: </font></td>
					<td align="right"><input type="radio" name="service" value="tel" tabindex="1" onclick="checkradio(this.form);" <?php if($type=='PHONE') print 'checked=checked'; ?>/><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Telephone  </font></td> 					<td align="right"><input type="radio" name="service" value="inter" tabindex="2" onclick="checkradio(this.form);" <?php if($type=='ISP') print 'checked=checked';?> /><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Internet  </font></td>
					<td align="right"><input type="radio" name="service" value="lease" tabindex="3" onclick="checkradio(this.form);" <?php if($type=='Lease') print 'checked=checked';?> /><font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Lease Line</font></td>
				</tr>
				<tr>
					<td><font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Tel. No/ Acc, No/ User Name: </font></td>
					<td class="tdCell" colspan="3" align="left"><input type="text" name="txtusername" class="txt" align="left" tabindex="4" onkeydown="checkusername(event);" value="<?php print $txtusername;?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left" colspan="2">
			<table border="0" width="100%">
				<tr>
					<td align="left">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Date:</font>
					</td>
					<td class="tdCell" width="28%" align="left">
						 <input type="text" tabindex="5" name="stD" class="txt" value="<?php print $stD; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
					</td>
					<td width="30%"></td>
					<td align="right" width="10%">
						<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF">Monthly fee:</font>
					</td>
					<td class="tdCell" align="right" width="30%">
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
			<font face="Arial, Helvetica, sans-serif" size="-1" color="#3300FF"><strong> Nature of request:</strong></font>
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
												<input type="checkbox" tabindex="14" name="chmovelo" value="<?php print $chmovelo; ?>"/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Location</font>
											</td>
											<td>
												<input type="checkbox" tabindex="15" name="chmovena" value="<?php print $chmovena; ?>"/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Name</font>
											</td>
											<td>
												<input type="checkbox" tabindex="16" name="chmovenu" value="<?php print $chmovenu; ?>"/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Number</font>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" tabindex="17" name="chmovesys" value="<?php print $chmovesys; ?>"/>
											</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">System</font>
											</td>
											<td>
												<input type="checkbox" tabindex="18" name="chmovemo" value="<?php print $chmovemo; ?>"/>
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
												<textarea name="txtto" class="txt" rows="2" tabindex="19"><?php print $txtto;?></textarea>
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
												<textarea name="txtfee" class="txt" rows="2" style="border-bottom-style:solid;" tabindex="20"><?php print $txtfee;?></textarea>
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
												<input type="radio" tabindex="21" name="dis" />
										  	</td>
											<td width="32%">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Temporary</font>
											</td>
											<td width="4%">
												<input type="radio" tabindex="22" name="dis" />
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
															<input type="text" tabindex="23" name="stdisfrom" class="txt" size="17" maxlength="10" value="<?php print $stdisfrom; ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')" />
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
															<input type="text" tabindex="24" name="stdisto" class="txt" value="<?php print $stdisto;?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>												
														</td>
													</tr>
												</table>
										  </td>	
										</tr>
									</table>
								</td>
							 </tr>
							 <tr>
							 	<td colspan="2">
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
												 <input type="text" tabindex="25" name="strecfrom" class="txt" style="font-size:12px" value="<?php print $strecfrom;?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
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
		<td width="50%"> <!-- ROW BLOCK:::: Deposit anad Refund -->
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
									<input type="checkbox" tabindex="26" name="deidd" value="<?php print $deidd; ?>"/>
							    </td>
								<td width="18%" align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">IDD</font>								
								</td>
								<td width="4%">
									<input type="checkbox" tabindex="27" name="dendd" value="<?php print $dendd; ?>"/>
							    </td>
								<td width="20%" align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">NDD</font>								
								</td>
								<td width="4%">
									<input type="checkbox" tabindex="28" name="deinter" value="<?php print $deinter; ?>"/>
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
									<input type="checkbox" tabindex="29" name="reidd" value="<?php print $reidd; ?>"/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">IDD</font>
								</td>
								<td>
									<input type="checkbox" tabindex="30" name="rendd" value="<?php print $rendd; ?>"/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">NDD</font>
								</td>
								<td>
									<input type="checkbox" tabindex="31" name="reinter" value="<?php print $reinter; ?>"/>
								</td>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Internet</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr >
					<td width="100%" align="left">
						<table width="100%" border="0">
							<tr>
								<td align="left">
									<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Amount:</font>
								</td>
								<td align="left" rowspan="2" class="tdCell" width="90%">
									<input type="text" name="txtamt" class="txt" rows="1" tabindex="32" value="<?php print $txtamt;?>" />
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
					<td width="100%" align="left">
						<table width="100%" border="0">
							<tr valign="top">
								<td align="left" rowspan="2" class="tdCell" width="100%" colspan="2" valign="top">
									<textarea name="txtother" class="txt" rows="4" tabindex="33"><?php print $txtother;?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
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
									<textarea name="txtreason" class="txt" rows="7" tabindex="34"></textarea>
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
											<td width="34%" height="36" align="left" style="vertical-align:top;">
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Authorized Signature</font>											</td>	
											<td width="66%" style="vertical-align:top;">
												<table border="0" width="100%">								
													<tr>
														<td align="left" class="tdCell" width="100%">
																<span style="width:100%">:</span>
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
																			:<input type="text" name="txtaname" class="txt" tabindex="35" value="" />
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
														<input type="text" tabindex="36" name="txtafrom" class="txt" value="<?php print date('Y/m/d'); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
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
															<input type="text" tabindex="37" name="txtato" class="txt" value="<?php print date('H:s'); ?>" />												
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
												<font face="Arial, Helvetica, sans-serif" size="-1" color="#000000">Acknowledgement</font>											</td>	
											<td width="67%">
												<table border="0" width="100%">								
													<tr>
														<td align="left" class="tdCell" width="100%">
																<span style="width:100%">:</span>
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
																			:<input type="text" name="txtacname" class="txt" value="" tabindex="38"/>
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
														<input type="text" tabindex="39" name="txtacfrom" class="txt" value="<?php print date('Y/m/d'); ?>" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')"/>
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
															<input type="text" tabindex="40" name="txtacto" class="txt" value="<?php print date('H:s'); ?>" />												
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
	<tr>
		<td colspan="2" align="center" style="border-top-style:solid;border-top-width:1px">
			<br /><input type="button" name="cmdsave" value="Save" onclick="checkuseraction();" /> &nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="cmdprint" value="Print" onclick="viewInfo('<?php print $ID;?>')"/> &nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" name="cmdreset" value="Reset"  /><br />
			<input type="hidden" name="PaccID" value="<?php print $PaccID;?>"  />
			<input type="hidden" name="CustID" value="<?php print $CustID;?>"  />
			<input type="hidden" name="ID" value="<?php print $ID;?>"  />
		</td>
	</tr>
	<?php
							if(isset($retOut) && (!empty($retOut))){
								print "<tr><td colspan=\"2\" align=\"left\">$retOut</td></tr>";
							}
							$ID='';
						?>		
</table>
</form>
</body>
</html>
<?php
# Close connection
$mydb->sql_close();
?>