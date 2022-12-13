<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createTripInvoiceItemBreakdown($con,$invcId,$itemDate,$itemJob,$itemVehicle,$itemDocNo,$itemDescription,$unitType,
	$amount,$subTotal,$tax,$discount,$total){
		
		$success=false;
		$query="INSERT INTO `tripinvoiceitem`(`invoiceId`, `itemDate`, `itemJob`, `itemVehicle`, `itemDocNo`, `itemDescription`, `unitType`, `amount`, `subTotal`, `tax`, `discount`, `total`) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isssssssssss',$invcId,$itemDate,$itemJob,$itemVehicle,$itemDocNo,$itemDescription,$unitType,
            $amount,$subTotal,$tax,$discount,$total);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchTripInvoiceDetailsByInvoiceId($con,$invoiceId){
		$dataList=array();
		$query="SELECT * FROM `tripinvoiceitem` WHERE `invoiceId`=? ";
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

	function deleteInvoiceItemBreakdownByInvId($con,$invoiceId){
		$success=false;
	
		
		$query="DELETE FROM `tripinvoiceitem` WHERE `invoiceId`=? ";
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