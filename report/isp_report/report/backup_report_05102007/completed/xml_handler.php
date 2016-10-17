<?	
	class XmlHandler
	{
		var $filePath;		
		
		function Load($file_path)
		{						
			$this->filePath = $file_path;				
		}
		
		function GetData($object_name)
		{														
			$xml= simplexml_load_file($this->filePath);			
			foreach($xml as $user)
			{
				$strValue = $user->$object_name;				
			}
			return $strValue;
		}
	}		
?>
