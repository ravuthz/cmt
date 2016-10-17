<?	
	require("conn.php");
	
	class sie
	{
		#region Private Variables
		var $BillEndDate,$ServiceId,$DecPlace=2,$PreCycleDate,$CurCycleDate;
		var $TableIspProvince;
		#endregion		
		
		
		#region Public Methods
		function GetReportBodyByService($ServiceId,$BillEndDate)
		{			
			$this->BillEndDate=$BillEndDate;
			$this->TableIspProvince="isp_province.dbo";
			
			if($ServiceId!=-1)
				$this->ServiceId=$ServiceId;
			else
				$this->ServiceId="1,3,8";
							
			return $this->BillStatisticReport();			
		}
		#endregion
		
		
		#region Private Methods
		function BillStatisticReport()
		{						
			$TotalPreNoUser=0;
			$TotalPreAmount=0;
			$TotalCurNoUser=0;
			$TotalCurAmount=0;
			$TotalVarUser=0;
			$TotalVarAmount=0;
				
			$strQuery=$this->QueryResolver($this->GetBillStatisticReportQS());			
			$result=mssql_query($strQuery);
			$i=0;
			while($row=mssql_fetch_array($result))
			{								
				if($row["PreNoUser"]>0 || 				
				$row["CurNoUser"]>0)
				{
					$i++;
					$strBody.= "<tr class=\"servicebody\">\r\n".
						"<td align=\"right\">".$i."</td>\r\n".
						"<td>&nbsp;".$row["Provinces"]."</td>\r\n".
						"<td align=\"right\">".$row["PreNoUser"]."</td>\r\n".
						"<td align=\"right\">".number_format($row["PreAmount"],$this->DecPlace)."</td>\r\n".
						"<td align=\"right\">".$row["CurNoUser"]."</td>\r\n".
						"<td align=\"right\">".number_format($row["CurAmount"],$this->DecPlace)."</td>\r\n".
						"<td align=\"right\">".number_format($row["VarUser"],$this->DecPlace)."%</td>\r\n".
						"<td align=\"right\">".number_format($row["VarAmount"],$this->DecPlace)."%</td>\r\n".
					"</tr>\r\n";
				}
				$TotalPreNoUser+=$row["PreNoUser"];
				$TotalPreAmount+=$row["PreAmount"];
				$TotalCurNoUser+=$row["CurNoUser"];
				$TotalCurAmount+=$row["CurAmount"];
				$TotalVarUser+=$row["VarUser"];
				$TotalVarAmount+=$row["VarAmount"];
			}				
			
			$strBody.="<tr class=\"servicefooter\">\r\n".
				"<td colspan=\"2\" align=\"center\">Total</td>\r\n".
				"<td align=\"right\">".$TotalPreNoUser."</td>\r\n".
				"<td align=\"right\">".number_format($TotalPreAmount,$this->DecPlace)."</td>\r\n".
				"<td align=\"right\">".$TotalCurNoUser."</td>\r\n".
				"<td align=\"right\">".number_format($TotalCurAmount,$this->DecPlace)."</td>\r\n".
				"<td align=\"right\">".number_format($TotalVarUser,$this->DecPlace)."%</td>\r\n".
				"<td align=\"right\">".number_format($TotalVarAmount,$this->DecPlace)."%</td>\r\n".
			"</tr>\r\n";
	  		
			return $strBody;
		}			
		
		function SetCycleDateProperties($BillEndDate)
		{			
			$this->BillEndDate=$BillEndDate;
			$strQuery=$this->QueryResolver($this->GetCycleDateQS());
			$result=mssql_query($strQuery);
			while($row=mssql_fetch_array($result))
			{
				$timestamp = strtotime($row["PreCycle"]);		
				$strDates = date("M-Y",$timestamp);
				$this->PreCycleDate=$strDates;
				
				$timestamp = strtotime($row["CurCycle"]);		
				$strDates = date("M-Y",$timestamp);
				$this->CurCycleDate=$strDates;
			}
		}
		#endregion
		
		
		#region Query String
		function GetBillStatisticReportQS()
		{
			
				
			$strQuery=
					"if object_id('tempdb..?TempCurrent') is not null\r\n".
					"drop table ?TempCurrent\r\n".
					
					"if object_id('tempdb..?TempPre') is not null\r\n".
					"drop table ?TempPre\r\n".
					
					"if object_id('tempdb..?TempCurrent1') is not null\r\n".
					"drop table ?TempCurrent1\r\n".
					
					"if object_id('tempdb..?TempPre1') is not null\r\n".
					"drop table ?TempPre1\r\n".
					
					"--Current Cycle\r\n".
					"Select ca.CityID,count(*) NoOfInv,sum(ci.InvoiceAmount) Amount \r\n".
					"into ?TempCurrent \r\n".
					"from tblCustProduct cp\r\n".
					"left outer join tblCustomerInvoice ci on ci.AccID=cp.AccID\r\n".
					"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
					"left outer join tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
					"where tp.ServiceID in (?ServiceId) and ci.BillingCycleId in (Select CycleId from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='?BillEndDate')\r\n".
					"and ci.InvoiceType in (1,2) and ci.InvoiceAmount>0\r\n".				
					"group by ca.CityID\r\n".
					
					"--Current Cycle Isp Province\r\n".
					"Select ca.CityID,count(*) NoOfInv,sum(ci.InvoiceAmount) Amount \r\n".
					"into ?TempCurrent1 \r\n".
					"from ?TableIspProvince.tblCustProduct cp\r\n".
					"left outer join ?TableIspProvince.tblCustomerInvoice ci on ci.AccID=cp.AccID\r\n".
					"inner join ?TableIspProvince.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
					"left outer join ?TableIspProvince.tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
					"where tp.ServiceID in (?ServiceId) and ci.BillingCycleId in (Select CycleId from ?TableIspProvince.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='?BillEndDate')\r\n".
					"and ci.InvoiceType in (1,2) and ci.InvoiceAmount>0\r\n".
					"group by ca.CityID\r\n".
					
					"--Previouse Cycle\r\n".
					"Select ca.CityID,count(*) NoOfInv,sum(ci.InvoiceAmount) Amount \r\n".
					"into ?TempPre \r\n".
					"from tblCustProduct cp\r\n".
					"left outer join tblCustomerInvoice ci on ci.AccID=cp.AccID\r\n".
					"inner join tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
					"left outer join tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
					"where tp.ServiceID in (?ServiceId) and ci.BillingCycleId in \r\n".
					"(\r\n".
					"	Select CycleID from tblSysBillRunCycleInfo where BillEndDate = \r\n".
					"	(\r\n".
					"		Select max(BillEndDate) \r\n".
					"		from tblSysBillRunCycleInfo \r\n".
					"		where BillEndDate<convert(datetime,'?BillEndDate')\r\n".
					"		and PackageID in (Select PackageID from tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='?BillEndDate')\r\n".
					"	)\r\n".
					")\r\n".
					"and ci.InvoiceType in (1,2) and ci.InvoiceAmount>0\r\n".
					"group by ca.CityID\r\n\r\n".
					
					
					
					"--Previouse Cycle Isp Province\r\n".
					"Select ca.CityID,count(*) NoOfInv,sum(ci.InvoiceAmount) Amount \r\n".
					"into ?TempPre1 \r\n".
					"from ?TableIspProvince.tblCustProduct cp\r\n".
					"left outer join ?TableIspProvince.tblCustomerInvoice ci on ci.AccID=cp.AccID\r\n".
					"inner join ?TableIspProvince.tblTarPackage tp on tp.PackageID=cp.PackageID\r\n".
					"left outer join ?TableIspProvince.tblCustAddress ca on ca.AccID=ci.AccID and ca.IsBillingAddress=0\r\n".
					"where tp.ServiceID in (?ServiceId) and ci.BillingCycleId in \r\n".
					"(\r\n".
					"	Select CycleID from ?TableIspProvince.tblSysBillRunCycleInfo where BillEndDate = \r\n".
					"	(\r\n".
					"		Select max(BillEndDate) \r\n".
					"		from ?TableIspProvince.tblSysBillRunCycleInfo \r\n".
					"		where BillEndDate<convert(datetime,'?BillEndDate')\r\n".
					"		and PackageID in (Select PackageID from ?TableIspProvince.tblSysBillRunCycleInfo where convert(varchar,BillEndDate,112)='?BillEndDate')\r\n".
					"	)\r\n".
					")\r\n".
					"and ci.InvoiceType in (1,2) and ci.InvoiceAmount>0\r\n".
					"group by ca.CityID\r\n\r\n".
					
					"Select loc.name Provinces,isnull(tdp.NoOfInv,0)+isnull(tdp1.NoOfInv,0) PreNoUser,isnull(tdp.Amount,0)+isnull(tdp1.Amount,0) PreAmount,\r\n".
					"isnull(tdc.NoOfInv,0)+isnull(tdc1.NoOfInv,0) CurNoUser,isnull(tdc.Amount,0)+isnull(tdc1.Amount,0) CurAmount,\r\n".
					"case when (Round(isnull(tdp.NoOfInv,0),?DecPlace)+Round(isnull(tdp1.NoOfInv,0),?DecPlace))>0 then\r\n".					"Round((((Round(convert(float,isnull(tdc.NoOfInv,0)),?DecPlace)+Round(convert(float,isnull(tdc1.NoOfInv,0)),?DecPlace))-(Round(convert(float,isnull(tdp.NoOfInv,0)),?DecPlace)+Round(convert(float,isnull(tdp1.NoOfInv,0)),?DecPlace)))/(Round(convert(float,isnull(tdp.NoOfInv,0)),?DecPlace)+Round(convert(float,isnull(tdp1.NoOfInv,0)),?DecPlace)))*100,2)\r\n".
					"else\r\n". 
					
					"0\r\n".
					
					
					"end VarUser,\r\n".
					
					"case when (Round(isnull(tdp.Amount,0),?DecPlace)+Round(isnull(tdp1.Amount,0),?DecPlace))>0 then\r\n".
					"Round((((Round(isnull(tdc.Amount,0),?DecPlace)+Round(isnull(tdc1.Amount,0),?DecPlace))-(Round(isnull(tdp.Amount,0),?DecPlace)+Round(isnull(tdp1.Amount,0),?DecPlace)))/(Round(isnull(tdp.Amount,0),?DecPlace)+Round(isnull(tdp1.Amount,0),?DecPlace)))*100,2)\r\n".
					
					"else\r\n".
					
					"0\r\n".
					
					
					"end VarAmount\r\n".
					"from isp_province.dbo.tlkpLocation loc\r\n".
					"left outer join ?TempCurrent tdc on tdc.CityID=loc.id\r\n".
					"left outer join ?TempCurrent1 tdc1 on tdc1.CityID=loc.id\r\n".
					"left outer join ?TempPre tdp on tdp.CityID=loc.id\r\n".
					"left outer join ?TempPre1 tdp1 on tdp1.CityID=loc.id\r\n".
					"where Type=2\r\n";										
					
					
			return $strQuery;
		}
		
		function GetCycleDateQS()
		{
			$strQuery=	"Select max(BillEndDate) PreCycle,convert(datetime,'?BillEndDate') CurCycle\r\n".
						"from tblSysBillRunCycleInfo\r\n".
						"where BillEndDate<convert(datetime,'?BillEndDate')\r\n".
						"and PackageID in (Select PackageID from tblSysBillRunCycleInfo\r\n". 
						"where convert(varchar,BillEndDate,112)='?BillEndDate')";

			return $strQuery;
		}
		
		function QueryResolver($strQuery)
		{			
			if($this->ServiceId==1)
			{
				$TempCurrent="#tmpDialCurrent";
				$TempPre="#tmpDialPre";
				$TempCurrent1="#tmpDialCurrent1";
				$TempPre1="#tmpDialPre1";
			}
			else if ($this->ServiceId==3)
			{
				$TempCurrent="#tmpADSLCurrent";
				$TempPre="#tmpADSLPre";
				$TempCurrent1="#tmpADSLCurrent1";
				$TempPre1="#tmpADSLPre1";
			}
			else if ($this->ServiceId==8)
			{
				$TempCurrent="#tmpISDNCurrent";
				$TempPre="#tmpISDNPre";
				$TempCurrent1="#tmpISDNCurrent1";
				$TempPre1="#tmpISDNPre1";
			}else
			{
				$TempCurrent="#tmpAllCurrent";
				$TempPre="#tmpAllPre";
				$TempCurrent1="#tmpAllCurrent1";
				$TempPre1="#tmpAllPre1";
				$this->ServiceId="1,3,8";
			}			
				
			
			$strQuery=str_replace("?BillEndDate",$this->BillEndDate,$strQuery);
			$strQuery=str_replace("?ServiceId",$this->ServiceId,$strQuery);
			$strQuery=str_replace("?TableIspProvince",$this->TableIspProvince,$strQuery);			
			$strQuery=str_replace("?TempCurrent",$TempCurrent,$strQuery);			
			$strQuery=str_replace("?TempPre",$TempPre,$strQuery);
			$strQuery=str_replace("?TempCurrent1",$TempCurrent1,$strQuery);			
			$strQuery=str_replace("?TempPre1",$TempPre1,$strQuery);			
			$strQuery=str_replace("?DecPlace",$this->DecPlace,$strQuery);			
			
			
			return $strQuery;
		}
		#endregion
	}
?>