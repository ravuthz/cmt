<!--
	*
	* This code is not to be distributed without the written permission of BRC Technology.
	* Copyright © 2006 <a href="http://www.brc-tech.com" target="_blank">BRC Technology</a> 
	* 
-->
<?php
	/**
	 *	@Project: Wise Biller	
	 *	@File:		audit.php	
	 *	
	 *	@Author: Chea vey	 
	 *
	 */
require_once("agent.php")	 ;

	class Audit{
	 	//var $CustomerID;
//		var $AccountID;
//		var $Context;
//		var $Description;
//		var $Operator;
//		# IsSuccess==> 1: Success; 0: fail
//		var $IsSuccess;
//		# GroupID==> 0: add to new group; else: add to that group
//		var $GroupID;
//		var $GroupName;
//		# typeID==> 1: Service; 2: Finance; 3: Reminder / other
//		var $TypeID;
		
			//function Audit($CustomerID = "", $AccountID = "", $Context = "", $Description = "", $Operator = "", $IsSuccess = 1, $GroupID = 0, $GroupName = "", $TypeID = 1){
			function Audit(){								
			} 
			
			//
			//	Add new audit group
			//
			function AddAuditGroup($GroupName, $TypeID = 1){
				global $mydb;
				
				$sql = "INSERT INTO tlkpAuditGroup(GroupName, TypeID) VALUES('".$GroupName."', '".$TypeID."')";
				if($mydb->sql_query($sql))
					return true;
				else{
					$error = $mydb->sql_error();
					return $error['message'];
				}
			}
			
			//
			//	Add new audit type
			//
			function AddAuditType($TypeName){
				global $mydb;
				
				$sql = "INSERT INTO tlkpAuditType(TypeName) VALUES('".$TypeName."')";
				if($mydb->sql_query($sql))
					return true;
				else{
					$error = $mydb->sql_error();
					return $error['message'];
				}
			}
			
			//
			//	Add new audit
			//
			function AddAudit($CustomerID, $AccountID, $Context, $Description, $Operator, $IsSuccess, $GroupID){
				global $mydb;
				$now = date("Y M d H:i:s");
				
				$sql = "INSERT INTO tblAuditTrial(CustomerID, AccountID, Context, Description, AuditDate, Operator, IsSuccess, GroupID)
								VALUES('".$CustomerID."', '".$AccountID."', '".$Context."', '".$Description."', '".$now."', '"
												 .$Operator."', '".$IsSuccess."', '".$GroupID."')
							";
				if($mydb->sql_query($sql))
					return true;
				else{
					$error = $mydb->sql_error();
					return $error['message'];
				}							
			}
			
			//
			//	View audit by customer
			//
			function GetAuditByCustomer($CustomerID, $TypeID = 0){
				global $mydb;
				
				$sql = "SELECT a.AuditID, a.CustomerID, a.AccountID, a.Context, a.Description, a.AuditDate, a.Operator, g.GroupName
							  FROM tblAuditTrial a, tlkpAuditGroup g, tlkpAuditType p
								WHERE a.GroupID = g.GroupID and g.TypeID = p.TypeID and a.CustomerID = $CustomerID
							 ";
				if($TypeID != 0)
					$sql .= " and p.TypeID=$TypeID";
				if($que = $mydb->sql_query($sql))
					return $que;
				else
					return false;
			}
			
			//
			//	View audit by operator
			//
			function GetAuditByOperator($Operator, $TypeID = 0){
				global $mydb;
				
				$sql = "SELECT a.AuditID, a.CustomerID, a.AccountID, a.Context, a.Description, a.AuditDate, a.Operator, g.GroupName
							  FROM tblAuditTrial a, tlkpAuditGroup g, tlkpAuditType p
								WHERE a.GroupID = g.GroupID and g.TypeID = p.TypeID and a.Operator like '%".$Operator."%'
							 ";
				if($TypeID != 0)
					$sql .= " and p.TypeID=$TypeID";
				if($que = $mydb->sql_query($sql))
					return $que;
				else
					return false;
			}
			
			//
			//	View specific audit
			//
			function GetAuditInfo($AuditID){
				global $mydb;
				
				$sql = "SELECT a.AuditID, a.CustomerID, a.AccountID, a.Context, a.Description, a.AuditDate, a.Operator, g.GroupName
							  FROM tblAuditTrial a, tlkpAuditGroup g, tlkpAuditType p
								WHERE a.GroupID = g.GroupID and g.TypeID = p.TypeID and a.AuditID = ".$AuditID."
							 ";			
				if($que = $mydb->sql_query($sql)){
					if($result = $mydb->sql_fetchrow($que)){
						return $result;
					}
					else
						return false;
				}else	
					return false;
			}
			
	 } // end class
?>