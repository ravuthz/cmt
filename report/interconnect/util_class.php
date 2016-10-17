<?
	class Utilities
	{
		function CurrencyFormat($value,$DecPlace)
		{
			$strValue="$".number_format($value,$DecPlace);
			return $strValue;
		}
	}
?>