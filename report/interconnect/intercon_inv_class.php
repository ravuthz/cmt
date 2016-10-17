<?php
	require("connect.php");
	require("xml_handler.php");
	
	class InterconInv
	{
		var $com_id,$com_name,$addr,
			$com_vatin,$owner_vatin,
			$attn,$position,$inv_no,
			$issue_date,$due_date,
			$net_amount_in,$vat_amount_in,
			$inv_amount_in,$call_in,
			$dur_in;
			
		var $xml,$period;
		
		function InterconInv($com_id,$period)
		{
			$this->com_id=$com_id;
			$this->period=$period;
		}
		
		function GetInvoiceHeader()
		{
			$dec_place=2;
			$dec_place_call=0;
			$this->xml = new XmlHandler();
			$this->xml->Load("config.xml");
			
			$strQuery = $this->xml->GetQueryString("CompanyProfile");
			$strQuery=$this->QueryResolver($strQuery);
			$result=mssql_query($strQuery);
			while($row=mssql_fetch_array($result))
			{
				$this->com_id =$row["com_id"];
				$this->com_name =$row["com_name"];
				$this->addr =$row["com_addr"];
				$this->com_vatin =$row["vat_no"];
				$this->attn=$row["attn"];
				$this->position=$row["position"];
				$this->inv_no="INV".$row["InvoiceId"];
				$this->issue_date=$row["IsDate"];
				$this->due_date=$row["DuDate"];
				$this->call_in=$row["NoOfCall"];
				$this->dur_in=$row["Duration"];
				$this->net_amount=$row["Net"];
				$this->vat_amount=$row["VAT"];
				$this->inv_amount=$row["Amount"];										
				$this->owner_vatin ="VT 100045049";				
			}
		}			
		
		function GetInvoiceBody()
		{
			$dec_place=2;
			$dec_place_call=0;
			$this->xml = new XmlHandler();
			$this->xml->Load("config.xml");
			
			$strQuery = $this->xml->GetQueryString("InvoiceDetail");
			$strQuery=$this->QueryResolver($strQuery);
			$result=mssql_query($strQuery);
			return $result;
		}	
		
		function QueryResolver($QueryString)
		{
			$QueryString=str_replace("@@com_id",$this->com_id,$QueryString);
			$QueryString=str_replace("@@period",$this->period,$QueryString);			
			return $QueryString;
		}
	}
?>