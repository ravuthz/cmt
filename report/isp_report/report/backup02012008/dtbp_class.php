<?
	require("conn.php");
	
	class dtbp
	{
		var $ServiceId, $ProvinceId, $strBillEndDate;
		var $DecPlace=2;
		
		function GetReportBody($ServiceId, $ProvinceId, $strBillEndDate)
		{
			$DecPlace=$this->DecPlace;
			$this->ServiceId=$ServiceId;
			$this->ProvinceId=$ProvinceId;
			$this->strBillEndDate=$strBillEndDate;
						
			$strQuery=$this->GetDetailReportBodyQS();							
			$result = mssql_query($strQuery);
			$i=0;
			
			$TotalMonthlyFee=0;
			$TotalOvChAmount=0;
			$TotalAdditionalMailAmount=0;
			$TotalSubTotal=0;
			$TotalVAT=0;
			$TotalTotalAmount=0;
			$TotalOvChUsage=0;
			$TotalCredit=0;
			$TotalOther=0;
			$TotalPaid=0;
			$TotalUnpaid=0;
			
			while($row=mssql_fetch_array($result))
			{
				
					$i++;				
					
					$TotalMonthlyFee+=$row["MonthlyFee"];
					$TotalOvChAmount+=$row["OvChAmount"];
					$TotalAdditionalMailAmount+=$row["AdditionalMailAmount"];
					$TotalSubTotal+=$row["SubTotal"];
					$TotalVAT+=$row["VAT"];
					$TotalTotalAmount+=$row["TotalAmount"];
					$TotalOvChUsage+=$row["OvChUsage"];
					$TotalCredit+=$row["CreditAllowance"];
					$TotalOther+=$row["OtherAmount"];
					$TotalPaid+=$row["Paid"];
					$TotalUnpaid+=$row["Unpaid"];
					
				
					$strBody.= "           <tr class=\"servicebody\">\r\n".
					"              <td>".$i."</td>\r\n".
					"              <td>".$row["AccID"]."</td>\r\n".
					"              <td>".$row["InvoiceID"]."</td>\r\n".				
					"              <td>".$row["CustName"]."</td>\r\n".
					"              <td>".$row["Telephone"]."</td>\r\n".
					"              <td align=\"right\">".number_format($row["MonthlyFee"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".$row["OvChUsageStr"]."</td>\r\n".
					"              <td align=\"right\">".number_format($row["OvChAmount"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["AdditionalMailAmount"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["CreditAllowance"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["OtherAmount"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["SubTotal"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["VAT"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["TotalAmount"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["Paid"],$DecPlace)."</td>\r\n".
					"              <td align=\"right\">".number_format($row["TotalAmount"]-$row["Paid"],$DecPlace)."</td>\r\n".
					"            </tr>";
				
			}
			
			$strBody.= "           <tr class=\"servicefooter\">\r\n".
				"              <td>&nbsp;</td>\r\n".
				"              <td>&nbsp;</td>\r\n".
				"              <td>&nbsp;</td>\r\n".				
				"              <td>&nbsp;</td>\r\n".
				"              <td>&nbsp;</td>\r\n".
				"              <td align=\"right\">".number_format($TotalMonthlyFee,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".$this->ConvertSecondToHour($TotalOvChUsage)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalOvChAmount,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalAdditionalMailAmount,$DecPlace)."</td>\r\n".
				"			   <td align=\"right\">".number_format($TotalCredit,$DecPlace)."</td>\r\n".
				"			   <td align=\"right\">".number_format($TotalOther,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalSubTotal,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalVAT,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalTotalAmount,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalPaid,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalTotalAmount-$TotalPaid,$DecPlace)."</td>\r\n".
				"            </tr>";
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
		
		//Select Data From Database
		function SelectDataFromDb($strQuery)
		{
			$result = mssql_query($strQuery);			
			return $result;		
		}
		
		function GetAllProvincesQS($ProvinceId)
		{
			$strQuery="Select id,name from isp_province.dbo.tlkpLocation where type=2 and id in (".$ProvinceId.")";
			return $strQuery;
		}
		
		function GetServiceName($ServiceId)
		{
			$strQuery="Select ServiceName from tlkpService where ServiceID=".$ServiceId;
			$resutl = mssql_query($strQuery);
			while($row=mssql_fetch_array($resutl))
			{
				$ServiceName=$row["ServiceName"];
			}
			return $ServiceName;
		}
		
		#region Query String
		private function GetDetailReportBodyQS()
		{
			$strQuery = "Declare @DecPlace int\r\n".
						"Set @DecPlace=2\r\n".
						
						"Select ci.AccID,ci.InvoiceID,ltrim(rtrim(cp.SubscriptionName)) CustName,isnull(cid.Amount,0)+isnull(t.Amount,0) MonthlyFee,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600))>1 then CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(isnull(ibu.OverageChargeUsage,0))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(isnull(ibu.OverageChargeUsage,0))) % 60), 2) OvChUsageStr,ibu.OverageChargeUsage OvChUsage,\r\n".
						
						"Round(isnull(ibu.OverageChargeAmount,0),@DecPlace) OvChAmount,\r\n".
						"(Select sum(Amount) from tblCustomerInvoiceDetail where InvoiceID=ci.InvoiceID and BillItemID=29) AdditionalMailAmount,\r\n".
						"ci.NetAmount SubTotal, ci.VatAmount VAT,ci.InvoiceAmount TotalAmount,cust.Telephone,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,12,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,12,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						"CreditAllowance,\r\n".
						
						"(isnull(isnull(ciaa.InvoiceAmount,0)-isnull(ciaa.OriginalUnpaidAmount,0),0))+(isnull(isnull(ciab.InvoiceAmount,0)-isnull(ciab.UnpaidAmount,0),0)) Paid,\r\n".
						"isnull(ciaa.OriginalUnpaidAmount,0)+isnull(ciab.UnpaidAmount,0) Unpaid,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo \r\n".
						"		where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						"OtherAmount\r\n".
						
						"from tblCustomerInvoice ci\r\n".
						"inner join tblCustomer cust on cust.CustID=ci.CustID\r\n".
						"inner join tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						"left outer join tblCustomerInvoiceDetail cid on cid.InvoiceID=ci.InvoiceID and cid.BillItemID=4\r\n".						
						"left outer join tblBillingSummaryTmp t on t.InvoiceID=ci.InvoiceID and t.BillItemID=4\r\n".						
						"left outer join tblIspBillableUsage ibu on ibu.AccID=ci.AccID and ibu.BillCycleID=ci.BillingCycleID\r\n".
						"inner join tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"left outer join tblCustAddress ca on ca.AccID=cp.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join tblCustomerInvoice ciaa on ciaa.InvoiceId=ci.InvoiceId and ciaa.InvoiceType=1\r\n".
						"left outer join tblCustomerInvoice ciab on ciab.InvoiceId=ci.InvoiceId and ciab.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".
						"where tp.ServiceID in (".$this->ServiceId.") and ci.InvoiceAmount>0\r\n".
						"and ci.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and ca.CityID in (".$this->ProvinceId.")  and tp.PackageID not in (79)\r\n".
						
												
						"UNION\r\n".
						
						
						
						"Select ci.AccID,ci.InvoiceID,ltrim(rtrim(cp.SubscriptionName)) CustName,isnull(cid.Amount,0)+isnull(t.Amount,0) MonthlyFee,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600))>1 then CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(isnull(ibu.OverageChargeUsage,0))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(isnull(ibu.OverageChargeUsage,0))) % 60), 2) OvChUsageStr,ibu.OverageChargeUsage OvChUsage,\r\n".
						
						"Round(isnull(ibu.OverageChargeAmount,0),@DecPlace) OvChAmount,\r\n".
						"(Select sum(Amount) from tblCustomerInvoiceDetail where InvoiceID=ci.InvoiceID and BillItemID=29) AdditionalMailAmount,\r\n".
						"ci.NetAmount SubTotal, ci.VatAmount VAT,ci.InvoiceAmount TotalAmount,cust.Telephone,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,12,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,12,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						"CreditAllowance,\r\n".
						
						"(isnull(isnull(ciaa.InvoiceAmount,0)-isnull(ciaa.OriginalUnpaidAmount,0),0))+(isnull(isnull(ciab.InvoiceAmount,0)-isnull(ciab.UnpaidAmount,0),0)) Paid,\r\n".
						"isnull(ciaa.OriginalUnpaidAmount,0)+isnull(ciab.UnpaidAmount,0) Unpaid,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo \r\n".
						"		where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						"OtherAmount\r\n".
						
						"from isp_province.dbo.tblCustomerInvoice ci\r\n".
						"inner join isp_province.dbo.tblCustomer cust on cust.CustID=ci.CustID\r\n".
						"inner join isp_province.dbo.tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoiceDetail cid on cid.InvoiceID=ci.InvoiceID and cid.BillItemID=4\r\n".						
						"left outer join isp_province.dbo.tblBillingSummaryTmp t on t.InvoiceID=ci.InvoiceID and t.BillItemID=4\r\n".						
						"left outer join isp_province.dbo.tblIspBillableUsage ibu on ibu.AccID=ci.AccID and ibu.BillCycleID=ci.BillingCycleID\r\n".
						"inner join isp_province.dbo.tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join isp_province.dbo.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"left outer join isp_province.dbo.tblCustAddress ca on ca.AccID=cp.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice ciaa on ciaa.InvoiceId=ci.InvoiceId and ciaa.InvoiceType=1\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice ciab on ciab.InvoiceId=ci.InvoiceId and ciab.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".
						"where tp.ServiceID in (".$this->ServiceId.") and ci.InvoiceAmount>0\r\n".
						"and ci.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and ca.CityID in (".$this->ProvinceId.")  and tp.PackageID not in (79)\r\n".
						"order by ltrim(rtrim(cp.SubscriptionName))\r\n";
						
						/*"Select ci.AccID,ci.InvoiceID,ltrim(rtrim(cp.SubscriptionName)) CustName,isnull(cid.Amount,0)+isnull(t.Amount,0) MonthlyFee,\r\n".
						
						"case when len(CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600))>1 then CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600) else RIGHT('0' +CONVERT(varchar, convert(int,(isnull(ibu.OverageChargeUsage,0)))/3600),2) end\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), (convert(int,(isnull(ibu.OverageChargeUsage,0))) % 3600) / 60), 2)\r\n".
						"+ ':' + RIGHT('0' + CONVERT(varchar(2), convert(int,(isnull(ibu.OverageChargeUsage,0))) % 60), 2) OvChUsageStr,ibu.OverageChargeUsage OvChUsage,\r\n".
						
						"Round(isnull(ibu.OverageChargeAmount,0),@DecPlace) OvChAmount,\r\n".
						"(Select sum(Amount) from isp_province.dbo.tblCustomerInvoiceDetail where InvoiceID=ci.InvoiceID and BillItemID=29) AdditionalMailAmount,\r\n".
						"ci.NetAmount SubTotal, ci.VatAmount VAT,ci.InvoiceAmount TotalAmount,cust.Telephone,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from tblBillingSummaryTmp cid \r\n".
						"		inner join tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (10,13,16,17,18,19,20,22,25) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						"CreditAllowance,\r\n".
						
											"(isnull(ciaa.InvoiceAmount,0)-isnull(ciaa.OriginalUnpaidAmount,0))+(isnull(ciab.InvoiceAmount,0)-isnull(ciab.UnpaidAmount,0)) Paid,\r\n".
						"isnull(ciaa.OriginalUnpaidAmount,0)+isnull(ciab.UnpaidAmount,0) Unpaid,\r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblCustomerInvoiceDetail cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0)+ \r\n".
						
						"isnull(\r\n".
						"	(\r\n".
						"		Select sum(isnull(cid.Amount,0)) from isp_province.dbo.tblBillingSummaryTmp cid \r\n".
						"		inner join isp_province.dbo.tblCustProduct cpa on cpa.AccID=cid.AccID\r\n".
						"		inner join isp_province.dbo.tblTarPackage tpa on tpa.PackageID=cpa.PackageID\r\n".
						"		left outer join isp_province.dbo.tblCustAddress cad on cad.AccID=cid.AccID and cad.IsBillingAddress=0\r\n".
						"		where cid.InvoiceID=ci.InvoiceID and \r\n".
						"		cid.BillItemID in (6,7,8,14,15,32) \r\n".
						"		and cid.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo \r\n".
						"where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and cad.CityID in (".$this->ProvinceId.") and tpa.PackageID not in (79)\r\n".
						"	)\r\n".
						",0) \r\n".
						
						" OtherAmount\r\n".
						
						
						"from isp_province.dbo.tblCustomerInvoice ci\r\n".
						"inner join isp_province.dbo.tblCustomer cust on cust.CustID=ci.CustID\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoiceDetail cid on cid.InvoiceID=ci.InvoiceID and cid.BillItemID=4\r\n".						
						"left outer join isp_province.dbo.tblBillingSummaryTmp t on t.InvoiceID=ci.InvoiceID and t.BillItemID=4\r\n".						
						"left outer join isp_province.dbo.tblIspBillableUsage ibu on ibu.AccID=ci.AccID and ibu.BillCycleID=ci.BillingCycleID\r\n".
						"inner join isp_province.dbo.tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join isp_province.dbo.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"inner join tblSysBillRunCycleInfo sbrc on sbrc.CycleId=ci.BillingCycleId\r\n".
						"left outer join isp_province.dbo.tblCustAddress ca on ca.AccID=cp.AccID and ca.IsBillingAddress=0\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice ciaa on ciaa.InvoiceId=ci.InvoiceId and ciaa.InvoiceType=1\r\n".
						"left outer join isp_province.dbo.tblCustomerInvoice ciab on ciab.InvoiceId=ci.InvoiceId and ciab.PaymentDate between sbrc.BillStartDate and sbrc.BillEndDate\r\n".
						"where tp.ServiceID in (".$this->ServiceId.") and ci.InvoiceAmount>0\r\n".
						"and ci.BillingCycleID in (Select CycleID from isp_province.dbo.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and ca.CityID in (".$this->ProvinceId.")  and tp.PackageID not in (79)\r\n".
						"order by ltrim(rtrim(cp.SubscriptionName))\r\n";*/
			
			return $strQuery;
		}
		#endregion
	}
?>
