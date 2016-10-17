<?	
	class XmlHandler
	{
		var $filePath;		
		
		//Load xml file Path
		function Load($file_path)
		{						
			$this->filePath = $file_path;				
		}
		//Get Query String
		function GetQueryString($Element)
		{											
			$xml= simplexml_load_file($this->filePath);			
			$strValue=$xml->$Element;			
			return $strValue;
		}
	}		
?>
