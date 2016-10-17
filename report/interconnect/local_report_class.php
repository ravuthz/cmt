<?
	require("connect.php");
	require("xml_handler.php");
	
	class LocReport
	{
		function GetReportBody()
		{
			$dec_place=2;
			$dec_place_call=0;
			$xml = new XmlHandler();
			$xml->Load("config.xml");
			
			$result = mssql_query($xml->GetQueryString("LocalReport"));
			while($row=mssql_fetch_array($result))
			{
				$strCompanyName = $row["com_short_name"];
				$CallCCIn = $row["Call08ACCIn"];
				$TotalCallCCIn+=$CallCCIn;
				$DurationCCIn = $row["Duration08ACCIn"]/60;
				$TotalDurationCCIn+=$DurationCCIn;
				$CallDRXIn = $row["CallDRXIn"];
				$TotalCallDRXIn+=$CallDRXIn;
				$DurationDRXIn = $row["DurationDRXIn"]/60;
				$TotalDurationDRXIn+=$DurationDRXIn;
				
				$TotalCallIn=$CallCCIn+$CallDRXIn;
				$GTotalCallIn+=$TotalCallIn;
				$TotalDurationIn=$DurationCCIn+$DurationDRXIn;
				$GTotalDurationIn+=$TotalDurationIn;
				
				$CallACCOut = $row["CallACCOut"];
				$TotalCallACCOut+=$CallACCOut;
				$DurationACCOut = $row["DurationACCOut"]/60;
				$TotalDurationACCOut+=$DurationACCOut;
				$CallCCOut = $row["Call08Out"];
				$TotalCallCCOut+=$CallCCOut;
				$DurationCCOut = $row["Duration08Out"]/60;
				$TotalDurationCCOut+=$DurationCCOut;
				$CallDRXOut = $row["CallDRXOut"];
				$TotalCallDRXOut+=$CallDRXOut;
				$DurationDRXOut = $row["DurationDRXOut"]/60;
				$TotalDurationDRXOut+=$DurationDRXOut;
				
				$TotalCallOut=$CallACCOut+$CallCCOut+$CallDRXOut;
				$GTotalCallOut+=$TotalCallOut;
				$TotalDurationOut=$DurationACCOut+$DurationCCOut+$DurationDRXOut;
				$GTotalDurationOut+=$TotalDurationOut;
				
				$strBody.="<tr class=\"report_detail\">
					<td align=\"left\">".$strCompanyName."</td>
					<td>".number_format($CallCCIn,$dec_place_call)."</td>
					<td>".number_format($DurationCCIn,$dec_place)."</td>
					<td>".number_format($CallDRXIn,$dec_place_call)."</td>
					<td>".number_format($DurationDRXIn,$dec_place)."</td>
					<td>".number_format($TotalCallIn,$dec_place_call)."</td>
					<td>".number_format($TotalDurationIn,$dec_place)."</td>
					<td>".number_format($CallACCOut,$dec_place_call)."</td>
					<td>".number_format($DurationACCOut,$dec_place)."</td>
					<td>".number_format($CallCCOut,$dec_place_call)."</td>
					<td>".number_format($DurationCCOut,$dec_place)."</td>
					<td>".number_format($CallDRXOut,$dec_place_call)."</td>
					<td>".number_format($DurationDRXOut,$dec_place)."</td>
					<td>".number_format($TotalCallOut,$dec_place_call)."</td>
					<td>".number_format($TotalDurationOut,$dec_place)."</td>
			  	</tr>";
				
			}
			$strBody.="<tr class=\"report_total\">
					<td>Total</td>
					<td>".number_format($TotalCallCCIn,$dec_place_call)."</td>
					<td>".number_format($TotalDurationCCIn,$dec_place)."</td>
					<td>".number_format($TotalCallDRXIn,$dec_place_call)."</td>
					<td>".number_format($TotalDurationDRXIn,$dec_place)."</td>
					<td>".number_format($GTotalCallIn,$dec_place_call)."</td>
					<td>".number_format($GTotalDurationIn,$dec_place)."</td>
					<td>".number_format($TotalCallACCOut,$dec_place_call)."</td>
					<td>".number_format($TotalDurationACCOut,$dec_place)."</td>
					<td>".number_format($TotalCallCCOut,$dec_place_call)."</td>
					<td>".number_format($TotalDurationCCOut,$dec_place)."</td>
					<td>".number_format($TotalCallDRXOut,$dec_place_call)."</td>
					<td>".number_format($TotalDurationDRXOut,$dec_place)."</td>
					<td>".number_format($GTotalCallOut,$dec_place_call)."</td>
					<td>".number_format($GTotalDurationOut,$dec_place)."</td>
			  	</tr>";
			
			return $strBody;
		}
	}
?>