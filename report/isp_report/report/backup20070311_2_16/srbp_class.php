<?
	require("conn.php");	
	
	class srbp
	{
		var $DecPlace=2;		
		var $EndBillDate;
		
		//region Public Method
		function getBodyReport($strBillEndDate)
		{			
			$TotalAccount=0;
			$TotalAllUsage=0;
			$TotalChargingUsage=0;
			$TotalMonthlyFee=0;
			$TotalMailBoxFee=0;
			$TotalUsageAmount=0;
			$TotalVAT=0;
			$TotalSubTotal=0;
			$TotalTotal=0;
				
			$strBody="";			
			$srbp=new srbp($strBillEndDate);
			$strQuery=$srbp->GetBodyReportByServiceQS($strBillEndDate);			
			
			$DecPlace=$srbp->DecPlace;
			
			$result = mssql_query($strQuery);
			$i=0;
			while($row=mssql_fetch_array($result))
			{				
				$subTotal=$row["MonthlyFee"]+$row["MailBoxCharging"]+$row["UsageAmount"];
				$Total=$row["MonthlyFee"]+$row["MailBoxCharging"]+$row["UsageAmount"]+$row["VAT"];
				
				if($Total>0)
				{
					$i++;
					$strBody.="<tr class=\"reportbody\">\r\n";
						$strBody.="<td align=\"center\">$i</td>\r\n";
						$strBody.="<td align=\"left\">&nbsp;".$row["Province"]."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($row["NoOfAcc"],$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".$row["ToalUsageHour"]."</td>\r\n";
						$strBody.="<td align=\"right\">".$row["ChargingHour"]."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($row["MonthlyFee"],$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($row["MailBoxCharging"],$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($row["UsageAmount"],$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($subTotal,$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($row["VAT"],$DecPlace)."</td>\r\n";
						$strBody.="<td align=\"right\">".number_format($Total,$DecPlace)."</td>\r\n";
					  $strBody.="</tr>\r\n";
					  
					$TotalAccount+=$row["NoOfAcc"];
					$TotalAllUsage+=$row["Durations"];
					$TotalChargingUsage+=$row["OverChargeDuration"];
					$TotalMonthlyFee+=$row["MonthlyFee"];
					$TotalMailBoxFee+=$row["MailBoxCharging"];
					$TotalUsageAmount+=$row["UsageAmount"];
					$TotalVAT+=$row["VAT"];
					$TotalSubTotal+=$subTotal;
					$TotalTotal+=$Total;
				}
			}
			
			
			//Total
			
					$strBody.="<tr class=\"total\">\r\n";
					$strBody.="<td align=\"center\">&nbsp;</td>\r\n";
					$strBody.="<td align=\"left\">Total</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalAccount,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".$srbp->ConvertSecondToHour($TotalAllUsage)."</td>\r\n";
					$strBody.="<td align=\"right\">".$srbp->ConvertSecondToHour($TotalChargingUsage)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalMonthlyFee,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalMailBoxFee,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalUsageAmount,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalSubTotal,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalVAT,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalTotal,$DecPlace)."</td>\r\n";
				  $strBody.="</tr>\r\n";
			
			return $strBody;
		}
		
		//Get Body Report Page 1
		function getBodyReportPgOne($ProvinceId,$strBillEndDate)
		{			
			$TotalAccount=0;
			$TotalAllUsage=0;
			$TotalChargingUsage=0;
			$TotalMonthlyFee=0;
			$TotalMailBoxFee=0;
			$TotalUsageAmount=0;
			$TotalVAT=0;
			$TotalSubTotal=0;
			$TotalTotal=0;
			$TotalPaid=0;
			$TotalUnpaid=0;
			$TotalOtherAmount=0;
				
			$strBody="";			
			$srbp=new srbp($strBillEndDate);
			if(strpos($ProvinceId,",")>-1)
				$strQuery=$srbp->GetBodyReportByServiceQS($ProvinceId, $strBillEndDate);			
			else
				$strQuery=$srbp->GetBodyReportByProvinceQS($ProvinceId, $strBillEndDate);			
				
			
			$DecPlace=$srbp->DecPlace;
							
			$result = mssql_query($strQuery);
			$i=0;
			while($row=mssql_fetch_array($result))
			{
				$i++;
				$subTotal=$row["MonthlyFee"]+$row["MailBoxCharging"]+$row["UsageAmount"]+$row["CreditAllowance"];
				$Total=$row["MonthlyFee"]+$row["MailBoxCharging"]+$row["UsageAmount"]+$row["VAT"]+$row["CreditAllowance"];
				$strBody.="<tr class=\"servicebody\">\r\n";
					$strBody.="<td align=\"center\">$i</td>\r\n";
					$strBody.="<td align=\"left\" onClick=\"window.open('report_viewer.php?ServiceId=".$row["ServiceID"]."&ProvinceId=".$ProvinceId."&BillEndDate=".$strBillEndDate."&ReportNo=3')\" onmouseover=\"this.style.cursor = 'hand';className='onmouseoverbg'\" onmouseout=\"className='onmouseoutbg'\">&nbsp;".$row["ServiceName"]."</td>\r\n";
					$strBody.="<td align=\"right\">".$row["NoOfAcc"]."</td>\r\n";
					$strBody.="<td align=\"right\">".$row["ToalUsageHour"]."</td>\r\n";
					$strBody.="<td align=\"right\">".$row["ChargingHour"]."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["MonthlyFee"],$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["MailBoxCharging"],$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["UsageAmount"],$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["CreditAllowance"],$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["OtherAmount"],$DecPlace)."</td>\r\n";					
					$strBody.="<td align=\"right\">".number_format($subTotal,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["VAT"],$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($Total,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($row["Paid"],$DecPlace)."</td>\r\n";
        			$strBody.="<td align=\"right\">".number_format($Total-$row["Paid"],$DecPlace)."</td>\r\n";
				  $strBody.="</tr>\r\n";
				  
				$TotalAccount+=$row["NoOfAcc"];
				$TotalAllUsage+=$row["Durations"];
				$TotalChargingUsage+=$row["OverChargeDuration"];
				$TotalMonthlyFee+=$row["MonthlyFee"];
				$TotalMailBoxFee+=$row["MailBoxCharging"];
				$TotalUsageAmount+=$row["UsageAmount"];
				$TotalVAT+=$row["VAT"];
				$TotalSubTotal+=$subTotal;
				$TotalTotal+=$Total;
				$TotalCredit+=$row["CreditAllowance"];
				$TotalPaid+=$row["Paid"];
				$TotalUnpaid+=$Total-$row["Paid"];
				$TotalOtherAmount+=$row["OtherAmount"];
			}
			
			
			//Total
			
					$strBody.="<tr class=\"servicefooter\">\r\n";
					$strBody.="<td align=\"center\">&nbsp;</td>\r\n";
					$strBody.="<td align=\"left\">Total</td>\r\n";
					$strBody.="<td align=\"right\">".$TotalAccount."</td>\r\n";
					$strBody.="<td align=\"right\">".$srbp->ConvertSecondToHour($TotalAllUsage)."</td>\r\n";
					$strBody.="<td align=\"right\">".$srbp->ConvertSecondToHour($TotalChargingUsage)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalMonthlyFee,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalMailBoxFee,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalUsageAmount,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalCredit,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalOtherAmount,$DecPlace)."</td>\r\n";					
					$strBody.="<td align=\"right\">".number_format($TotalSubTotal,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalVAT,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalTotal,$DecPlace)."</td>\r\n";
					$strBody.="<td align=\"right\">".number_format($TotalPaid,$DecPlace)."</td>\r\n";
        			$strBody.="<td align=\"right\">".number_format($TotalUnpaid,$DecPlace)."</td>\r\n";
				  $strBody.="</tr>\r\n";
			
			return $strBody;
		}
		
		
		
		/*
			Convert Second value to hh:mm:ss		
		*/
		private function ConvertSecondToHour($intSecond)
		{
			$strHour=(int)($intSecond/3600)>1?(int)($intSecond/3600):str_pad((int)($intSecond/3600),2,"0",STR_PAD_LEFT);
			$strHour.=":".str_pad((int)(($intSecond % 3600)/60),2,"0",STR_PAD_LEFT);
			$strHour.=":".str_pad((int)($intSecond % 60),2,"0",STR_PAD_LEFT);
			return $strHour;			
		}
		
		//Set Period
		function SetPeriod($strBillEndDate)
		{
			$strQuery=$this->GetPeriodQS($strBillEndDate);			
			$result = mssql_query($strQuery);			
			while($row=mssql_fetch_array($result))
			{
				$this->EndBillDate = $row["BillEndDate"];
			}			
		}
		
		//Select Data From Database
		function SelectDataFromDb($strQuery)
		{
			$result = mssql_query($strQuery);			
			return $result;		
		}
		//endregion
		
		
		
		//region QueryString
		private function GetPeriodQS($strBillEndDate)
		{
			$strQuery=	"Select convert(varchar,min(BillEndDate),103) BillEndDate from tblSysBillRunCycleInfo \r\n".
						"where convert(varchar,BillEndDate,112)='".$strBillEndDate."'";
			return $strQuery;
		}
		
		private function GetBodyReportQS($strBillEndDate)
		{
			$strQuery=	"Select loc.name Province,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(Durations,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(Durations,0)))) % 60), 2) ToalUsageHour,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(OverageChargeUsage,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(OverageChargeUsage,0)))) % 60), 2) ChargingHour,\r\n".
						
						
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"sum(isnull(cid.Amount,0))+sum(isnull(bst.Amount,0)) MonthlyFee,sum(isnull(cid1.Amount,0))+sum(isnull(bst1.Amount,0)) VAT,\r\n".
						"(Select sum(Amount) from tblCustomerInvoiceDetail where InvoiceID=ci.InvoiceID and BillItemID=29) MailBoxCharging\r\n".
						"from tblIspBillableUsage bu\r\n".
						"left outer join tblCustAddress ca on ca.AccID=bu.AccID\r\n".
						"inner join tlkpLocation loc on loc.id=ca.CityID\r\n".
						"left outer join tblCustomerInvoiceDetail cid on cid.AccID=bu.AccID and cid.BillingCycleID=bu.BillCycleID and cid.BillItemId=4\r\n".
						"left outer join tblCustomerInvoiceDetail cid1 on cid1.AccID=bu.AccID and cid1.BillingCycleID=bu.BillCycleID and cid1.BillItemId=5\r\n".
						"left outer join tblBillingSummaryTmp bst on bst.AccID=bu.AccID and bst.BillingCycleID=bu.BillCycleID and bst.BillItemId=4\r\n".
						"left outer join tblBillingSummaryTmp bst1 on bst1.AccID=bu.AccID and bst1.BillingCycleID=bu.BillCycleID and bst1.BillItemId=5\r\n".
						"where bu.BillCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."')\r\n".
						"group by loc.name\r\n".
						
						"Union\r\n".
						
						"Select loc.name Province,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(Durations,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(Durations,0)))) % 60), 2) ToalUsageHour,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(OverageChargeUsage,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(OverageChargeUsage,0)))) % 60), 2) ChargingHour,\r\n".
						
						
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"sum(isnull(cid.Amount,0))+sum(isnull(bst.Amount,0)) MonthlyFee,sum(isnull(cid1.Amount,0))+sum(isnull(bst1.Amount,0)) VAT,\r\n".
						"(Select sum(Amount) from isp_province.dbo.tblBillingSummaryTmp where InvoiceID=ci.InvoiceID and BillItemID=29) MailBoxCharging\r\n".
						"from isp_province.dbo.tblIspBillableUsage bu\r\n".
						"left outer join isp_province.dbo.tblCustAddress ca on ca.AccID=bu.AccID and ca.IsBillingAddress=0\r\n".
						"inner join isp_province.dbo.tlkpLocation loc on loc.id=ca.CityID\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoiceDetail cid on cid.AccID=bu.AccID and cid.BillingCycleID=bu.BillCycleID and cid.BillItemId=4\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoiceDetail cid1 on cid1.AccID=bu.AccID and cid1.BillingCycleID=bu.BillCycleID and cid1.BillItemId=5\r\n".
						"left outer join isp_province.dbo.tblBillingSummaryTmp bst on bst.AccID=bu.AccID and bst.BillingCycleID=bu.BillCycleID and bst.BillItemId=4\r\n".
						"left outer join isp_province.dbo.tblBillingSummaryTmp bst1 on bst1.AccID=bu.AccID and bst1.BillingCycleID=bu.BillCycleID and bst1.BillItemId=5\r\n".
						"where bu.BillCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."')\r\n".
						"group by loc.name";


			return $strQuery;
		}
		
		
		
		
		private function GetBodyReportByProvinceQS($ProvinceId,$strBillEndDate)
		{
			$strQuery=	"Select loc.name Province,s.ServiceName,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(Durations,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(Durations,0)))) % 60), 2) ToalUsageHour,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(OverageChargeUsage,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(OverageChargeUsage,0)))) % 60), 2) ChargingHour,\r\n".
						
						
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"MonthlyFee,\r\n".

						"sum(isnull(ci.VatAmount,0)) VAT,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						" MailBoxCharging,tp.ServiceID,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"CreditAllowance,\r\n".
						"(isnull(sum(isnull(cia.InvoiceAmount,0)-isnull(cia.OriginalUnpaidAmount,0)),0))+(isnull(sum(isnull(cib.InvoiceAmount,0)-isnull(cib.UnpaidAmount,0)),0)) Paid,\r\n".
						"isnull(sum(isnull(cia.OriginalUnpaidAmount,0)),0)+isnull(sum(isnull(cib.UnpaidAmount,0)),0) Unpaid,\r\n".
						
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n". 
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"OtherAmount\r\n".
						
						
						"from tblCustomerInvoice ci\r\n".
						"inner join tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"inner join tlkpService s on s.ServiceID=tp.ServiceID\r\n".
						"inner join tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						"left outer join tblIspBillableUsage bu on bu.AccID=ci.AccID and bu.BillCycleID=ci.BillingCycleID\r\n".
						"left outer join tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join tlkpLocation loc on loc.id=ca.CityID\r\n".		
						"left outer join tblCustomerInvoice cia on cia.InvoiceId=ci.InvoiceId and  cia.InvoiceType=1\r\n".	
						"left outer join tblCustomerInvoice cib on cib.InvoiceId=ci.InvoiceId and  cib.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".		
						"where ci.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and ca.CityID in (".$ProvinceId.")\r\n".
						"and tp.ServiceID in (1,3,8) and ci.InvoiceAmount>0 and tp.PackageID not in (79)\r\n".										
						"group by loc.name,s.ServiceName,tp.ServiceID\r\n".
						
						"Union\r\n".
						
						"Select loc.name Province,s.ServiceName,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(Durations,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(Durations,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(Durations,0)))) % 60), 2) ToalUsageHour,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(OverageChargeUsage,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(OverageChargeUsage,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(OverageChargeUsage,0)))) % 60), 2) ChargingHour,\r\n".
						
						
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"MonthlyFee,\r\n".

						"sum(isnull(ci.VatAmount,0)) VAT,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						" MailBoxCharging,tp.ServiceID,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"CreditAllowance,\r\n".
						"(isnull(sum(isnull(cia.InvoiceAmount,0)-isnull(cia.OriginalUnpaidAmount,0)),0))+(isnull(sum(isnull(cib.InvoiceAmount,0)-isnull(cib.UnpaidAmount,0)),0)) Paid,\r\n".
						"isnull(sum(isnull(cia.OriginalUnpaidAmount,0)),0)+isnull(sum(isnull(cib.UnpaidAmount,0)),0) Unpaid,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n". 
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"OtherAmount\r\n".
						
						"from isp_province.dbo.tblCustomerInvoice ci\r\n".
						"inner join isp_province.dbo.tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join isp_province.dbo.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"inner join isp_province.dbo.tlkpService s on s.ServiceID=tp.ServiceID\r\n".
						"inner join isp_province.dbo.tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						
						"left outer join isp_province.dbo.tblIspBillableUsage bu on bu.AccID=ci.AccID and bu.BillCycleID=ci.BillingCycleID\r\n".
						"left outer join isp_province.dbo.tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join isp_province.dbo.tlkpLocation loc on loc.id=ca.CityID					\r\n".	
						"left outer join isp_province.dbo.tblCustomerInvoice cia on cia.InvoiceId=ci.InvoiceId and cia.InvoiceType=1\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice cib on cib.InvoiceId=ci.InvoiceId and cib.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".
						"where ci.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and ca.CityID in (".$ProvinceId.")\r\n".
						"and tp.ServiceID in (1,3,8) and ci.InvoiceAmount>0 and tp.PackageID not in (79)\r\n".
						"group by loc.name,s.ServiceName,tp.ServiceID\r\n";											


			return $strQuery;
		}
		
		
		
		
		
		private function GetBodyReportByServiceQS($ProvinceId,$strBillEndDate)
		{
			$strQuery=	"if object_id('tempdb..#IspReportByService') is not null
							drop table #IspReportByService\r\n".
						
						
						"Select 0 Province,s.ServiceName,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"sum(isnull(Durations,0)) ToalUsageHour,\r\n".						
						"sum(isnull(OverageChargeUsage,0)) ChargingHour,\r\n".											
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"MonthlyFee,\r\n".

						"sum(isnull(ci.VatAmount,0)) VAT,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						" MailBoxCharging,tp.ServiceID,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"CreditAllowance,\r\n".
						"(isnull(sum(isnull(cia.InvoiceAmount,0)-isnull(cia.OriginalUnpaidAmount,0)),0))+(isnull(sum(isnull(cib.InvoiceAmount,0)-isnull(cib.UnpaidAmount,0)),0)) Paid,\r\n".
						"isnull(sum(isnull(cia.OriginalUnpaidAmount,0)),0)+isnull(sum(isnull(cib.UnpaidAmount,0)),0) Unpaid,\r\n".
						
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n". 
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						"OtherAmount\r\n".
						
						
						"into #IspReportByService from tblCustomerInvoice ci\r\n".
						"inner join tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"inner join tlkpService s on s.ServiceID=tp.ServiceID\r\n".
						"inner join tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						"left outer join tblIspBillableUsage bu on bu.AccID=ci.AccID and bu.BillCycleID=ci.BillingCycleID\r\n".
						"left outer join tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join tlkpLocation loc on loc.id=ca.CityID\r\n".		
						"left outer join tblCustomerInvoice cia on cia.InvoiceId=ci.InvoiceId and  cia.InvoiceType=1\r\n".	
						"left outer join tblCustomerInvoice cib on cib.InvoiceId=ci.InvoiceId and  cib.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".		
						"where ci.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and ca.CityID in (".$ProvinceId.")\r\n".
						"and tp.ServiceID in (1,3,8) and ci.InvoiceAmount>0 and tp.PackageID not in (79)\r\n".										
						"group by s.ServiceName,tp.ServiceID\r\n".
						
						"Union\r\n".
						
						"Select 0 Province,s.ServiceName,count(*) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverageChargeUsage,0)) OverChargeDuration,\r\n".
						
						"sum(isnull(Durations,0)) ToalUsageHour,\r\n".						
						"sum(isnull(OverageChargeUsage,0)) ChargingHour,\r\n".												
						"sum(isnull(OverageChargeAmount,0)) UsageAmount,\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=4 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"MonthlyFee,\r\n".

						"sum(isnull(ci.VatAmount,0)) VAT,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID=29 \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						
						" MailBoxCharging,tp.ServiceID,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n". 
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"CreditAllowance,\r\n".
						"(isnull(sum(isnull(cia.InvoiceAmount,0)-isnull(cia.OriginalUnpaidAmount,0)),0))+(isnull(sum(isnull(cib.InvoiceAmount,0)-isnull(cib.UnpaidAmount,0)),0)) Paid,\r\n".
						"isnull(sum(isnull(cia.OriginalUnpaidAmount,0)),0)+isnull(sum(isnull(cib.UnpaidAmount,0)),0) Unpaid,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n". 
						"+\r\n".
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where tpa.ServiceID=tp.ServiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and cad.CityID in (".$ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)\r\n".
						"OtherAmount\r\n".
						
						"from isp_province.dbo.tblCustomerInvoice ci\r\n".
						"inner join isp_province.dbo.tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join isp_province.dbo.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"inner join isp_province.dbo.tlkpService s on s.ServiceID=tp.ServiceID\r\n".
						"inner join isp_province.dbo.tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						
						"left outer join isp_province.dbo.tblIspBillableUsage bu on bu.AccID=ci.AccID and bu.BillCycleID=ci.BillingCycleID\r\n".
						"left outer join isp_province.dbo.tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join isp_province.dbo.tlkpLocation loc on loc.id=ca.CityID					\r\n".	
						"left outer join isp_province.dbo.tblCustomerInvoice cia on cia.InvoiceId=ci.InvoiceId and cia.InvoiceType=1\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice cib on cib.InvoiceId=ci.InvoiceId and cib.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".
						"where ci.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$strBillEndDate."') and ca.CityID in (".$ProvinceId.")\r\n".
						"and tp.ServiceID in (1,3,8) and ci.InvoiceAmount>0 and tp.PackageID not in (79)\r\n".
						"group by s.ServiceName,tp.ServiceID\r\n".		
						
						
						
						
						
						
						
						"Select 0 Province,ServiceName,sum(isnull(NoOfAcc,0)) NoOfAcc,sum(isnull(Durations,0)) Durations,sum(isnull(OverChargeDuration,0)) OverChargeDuration,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(ToalUsageHour,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(ToalUsageHour,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(ToalUsageHour,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(ToalUsageHour,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(ToalUsageHour,0)))) % 60), 2) ToalUsageHour,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(sum(isnull(ChargingHour,0))))/3600))>1 then CONVERT(varchar, convert(int,(sum(isnull(ChargingHour,0))))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(sum(isnull(ChargingHour,0))))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(sum(isnull(ChargingHour,0)))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(sum(isnull(ChargingHour,0)))) % 60), 2) ChargingHour,\r\n".
						
						
						"sum(isnull(UsageAmount,0)) UsageAmount,\r\n".
						"sum(isnull(MonthlyFee,0))\r\n".
						"MonthlyFee,\r\n".

						"sum(isnull(VAT,0)) VAT,\r\n".
						
						"sum(isnull(MailBoxCharging,0)) \r\n".
						
						"MailBoxCharging,ServiceID,\r\n".
						
						"sum(isnull(CreditAllowance,0)) CreditAllowance,\r\n".
						"sum(isnull(Paid,0)) Paid,\r\n".
						"sum(isnull(Unpaid,0)) Unpaid,\r\n".
						
						
						"sum(isnull(OtherAmount,0)) OtherAmount\r\n".
						"from #IspReportByService\r\n".
						"group by ServiceName,ServiceID\r\n".
						"order by ServiceName,ServiceID\r\n".
						
						"if object_id('tempdb..#IspReportByService') is not null
							drop table #IspReportByService\r\n";									
			
			return $strQuery;
		}
		
		//Get All Provinces Query String
		function GetAllProvincesQS()
		{
			$strQuery="Select id,name from isp_province.dbo.tlkpLocation where type=2";
			return $strQuery;
		}
		//endregion
	}
?>