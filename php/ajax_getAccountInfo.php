<?php
		require_once("../common/agent.php");
		require_once("../common/functions.php");
		$AccountID = $_GET['AccountID'];
		$ServiceID = $_GET['ServiceID'];
		$CustomerID = $_GET['CustID'];
		$UserName = $_GET['UserName'];
		$pID = $_GET['pID'];
		$SubscriptionName = $_GET['sName'];
		
$AcountUI.='
							                           
							
                
                                    <table border="0" cellpadding="2" cellspacing="0" class="formbg" align="left" width="100%">
                                            <tr>
                                                <td>
                                                        <table border="0" cellpadding="2" cellspacing="0" width="100%" height="100%" bgcolor="#feeac2" align="left">													
                                                            <tr>
                                                                <td align="left" nowrap="nowrap">Subscription name:</td>
                                                                <td align="left">
                                                                    <input type="text" name="SubscriptionName_'.$AccountID.'" value="'. $SubscriptionName.'"  class="boxenabled" tabindex="70" size="80" /><img src="./images/required.gif" border="0" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left">Package:</td>
                                                                <td align="left">';
                                                                
                                                    
                                                                        if ($ServiceID==0 || $ServiceID==2 || empty($ServiceID) || !isset($ServiceID))  
                                                                        {
                                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                                        ISDNFee, SpecialNumber
                                                                                            from tblTarPackage where Status = 1 and ServiceID = 2 
                                                                                            and DepositAmount<>1
                                                                                            order by 2";													
                                                                        }
                                                                        else  if($ServiceID==1 || $ServiceID==3 || $ServiceID==8)
                                                                        {
                                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                                        ISDNFee, SpecialNumber
                                                                                            from tblTarPackage where Status = 1 and ServiceID in (1,3,8) and DepositAmount<>1 order by 2";													
                                                                        }		
                                                                        else if($ServiceID==4)  
                                                                        {
                                                                            $sql = "SELECT PackageID, TarName, CreatedDate, RegistrationFee, ConfigurationFee, CPEFee,
                                                                                                        ISDNFee, SpecialNumber
                                                                                            from tblTarPackage where Status = 1 and ServiceID in (4) and DepositAmount<>1 order by 2";													
                                                                        }		
                                                                        
                                                                        $que = $mydb->sql_query($sql);									
                                                                        if($que){
                                                                            $tmppackage = "";
                                                                            $tmpdeposit = "";
                                                                            while($rst = $mydb->sql_fetchrow($que)){	
                                                                                $PackageID = $rst['PackageID'];
                                                                                $TName = $rst['TarName'];
                                                                                $ISDNFee = $rst['ISDNFee'];
                                                                                $SpecialNumber = $rst['SpecialNumber'];
                                                                                $CreatedDate = $rst['CreatedDate'];
                                                                                $RegistrationFee = $rst['RegistrationFee'];
                                                                                $ConfigurationFee = $rst['ConfigurationFee'];
                                                                                $CPEFee = $rst['CPEFee'];
                                                                                $PackageName = $TName." (".formatDate($CreatedDate, 4).")";
																				if($PackageID==$pID)
																				{
                                                                                	$tmppackage .= "<option value='".$PackageID."' selected>".$TName."</option><br />";
																				}
																				else
																				{
																					$tmppackage .= "<option value='".$PackageID."'>".$TName."</option><br />";
																				}
                                                                                $tmpdeposit .= "<option value='".$SpecialNumber."'>".$RegistrationFee."</option><br />";
                                                                                $tmpother .= "<option value='".$ConfigurationFee."'>".$CPEFee."</option><br />";
                                                                                $tmpother1 .= "<option value='".$ISDNFee."'>".$ISDNFee."</option><br />";
                                                                            }
                                                                        }
                                                                        $mydb->sql_freeresult();
							$AcountUI.='                                    
           
                                                                    <select name="PackageID_'.$AccountID.'" onchange="act('.$AccountID.');" tabindex="71">
                                                                    <option value="">Please select package</option>
																	'; 
							$AcountUI.=$tmppackage; 
							$AcountUI.='								
                                                                    </select>
                                                                </td>
                                                            </tr>										
                                                            <tr>
                                                                <td align="left" nowrap="nowrap">Account name / Telephone:</td>
                                                                <td align="left">'; 
							
							if ($ServiceID!=2)
							{
								$AcountUI.='													
																<select name="SelPhonePreset_'.$AccountID.'" class="boxenabled" tabindex="74" disabled="disabled">
           														';              
							}
							else
							{
								$AcountUI.='													
																<select name="SelPhonePreset_'.$AccountID.'" class="boxenabled" tabindex="74">
           														';    
								$UserName = substr($UserName,3);
							}
                                                                            $sql = "SELECT PresetNumber, [Default] from tlkpPhonePreset order by PresetNumber";
                                                                            // sql 2005
                                                                            
                                                                            $que = $mydb->sql_query($sql);									
                                                                            if($que){
                                                                                while($rst = $mydb->sql_fetchrow($que)){	
                                                                                    $PresetNumber = $rst['PresetNumber'];
                                                                                    $Default = $rst['Default'];
                                                                                    if($Default) 
                                                                                        $sel = "selected";
                                                                                    else
                                                                                        $sel = "";
                                                                                     $AcountUI.= "<option value='".$PresetNumber."' ".$sel.">".$PresetNumber."</option>";
                                                                                }
                                                                            }
                                                                            $mydb->sql_freeresult();
                           $AcountUI.='
                                                                </select>
                                                                  <input type="text" name="UserName_'.$AccountID.'" id="UserName_'.$AccountID.'" value="'.$UserName.'"  class="boxenabled" tabindex="75" size="21" onfocus="fn_getvalue('.$AccountID.');" onblur="ValidAccountAcc(\'dUserName_'.$AccountID.'\','.$AccountID.','.$ServiceID.');" maxlength="100" />
                                                                <img src="./images/required.gif" border="0" /><span style="display:none" id="dUserName_'.$AccountID.'" class="error"></span><input type="text" id="sidd_'.$AccountID.'" name="sidd_'.$AccountID.'" style="display:none;" />
                                                                </td>
                                                            </tr>
															<tr align="center">
																<td colspan="2">
																	<input type="button" name="Recon_'.$AccountID.'" value="Request" class="button" size="21" onClick="validateForm('.$AccountID.',\'Reconnect\','.$CustomerID.','.$AccountID.',0,4)"/>
																</td>
															</tr>
                                                        </table>
                                                  </td>
                                             </tr>
                                  </table>
                                    
                                 
							
						';
				
				

print $AcountUI;


?>