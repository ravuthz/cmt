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
			
				$strBody.= "           <tr class=\"servicebody\">\r\n".
				"              <td>".$i."</td>\r\n".
				"              <td>".$row["AccID"]."</td>\r\n".
				"              <td>".$row["InvoiceID"]."</td>\r\n".				
				"              <td>".$row["CustName"]."</td>\r\n".
				"              <td>&nbsp;</td>\r\n".
				"              <td align=\"right\">".number_format($row["MonthlyFee"],$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".$row["OvChUsageStr"]."</td>\r\n".
				"              <td align=\"right\">".number_format($row["OvChAmount"],$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($row["AdditionalMailAmount"],$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($row["SubTotal"],$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($row["VAT"],$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($row["TotalAmount"],$DecPlace)."</td>\r\n".
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
				"              <td align=\"right\">".number_format($TotalSubTotal,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalVAT,$DecPlace)."</td>\r\n".
				"              <td align=\"right\">".number_format($TotalTotalAmount,$DecPlace)."</td>\r\n".
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
			$strQuery="Select id,name from tlkpLocation where type=2 and id in (".$ProvinceId.")";
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
						"isnull(AdditionalMailBox,0) * isnull(ChargePerMailBox,0) AdditionalMailAmount,\r\n".
						"ci.NetAmount SubTotal, ci.VatAmount VAT,ci.InvoiceAmount TotalAmount\r\n".
						"from tblCustomerInvoice ci\r\n".
						"left outer join tblCustomerInvoiceDetail cid on cid.InvoiceID=ci.InvoiceID and cid.BillItemID=4\r\n".
						"left outer join tblBillingSummaryTmp t on t.InvoiceID=ci.InvoiceID and t.BillItemID=4\r\n".
						"left outer join tblIspBillableUsage ibu on ibu.AccID=ci.AccID and ibu.BillCycleID=ci.BillingCycleID\r\n".
						"inner join tblCustProduct cp on cp.AccID=ci.AccID\r\n".
						"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
						"left outer join tblCustAddress ca on ca.AccID=cp.AccID and ca.IsBillingAddress=0\r\n".
						"where tp.ServiceID in (".$this->ServiceId.") \r\n".
						"and ci.BillingCycleID in (Select CycleID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='".$this->strBillEndDate."') and ca.CityID in (".$this->ProvinceId.")\r\n".
						"order by ltrim(rtrim(cp.SubscriptionName))";
			
			return $strQuery;
		}
		#endregion
	}
?>
