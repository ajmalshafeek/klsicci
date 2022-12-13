<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createMailSentList($con,$invoiceId,$maillAddress,$mailAddressType,$orgid){
		
		$success=false;
		$query="INSERT INTO invoicemaillist ( 
		invoiceId,
		mailAddress,
		type,
		orgId
		) 
		VALUES (
			?,
			?,
			?,
			?
		)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'issi',$invoiceId,$maillAddress,$mailAddressType,$orgid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchInvoiceMailListByInvId($con,$invoiceId){
		$dataList=array();
		$query="SELECT * FROM invoicemaillist WHERE invoiceId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$invoiceId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
				
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
			
		}
		mysqli_stmt_close($stmt);
	
		return $dataList;
	}

	function deleteInvoiceMailListByInvId($con,$invoiceId){
		$success=false;
	
		
		$query="DELETE FROM invoicemaillist WHERE invoiceId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$invoiceId);

		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}

?>