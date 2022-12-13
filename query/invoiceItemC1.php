<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createInvoiceItemBreakdownC1($con,$quotId,$itemName,$itemDescription,$itemVehi,$itemCost,
	$itemQty,$subTotal,$tax,$discount,$total,$itemNo,$itemDate){
		
		$success=false;
		$query="INSERT INTO invoiceitemc1 (
		invoiceId,
		itemName,
		itemDescription,
		itemVehi,
		itemPrice,
		quantity,
		subTotal,
		tax,
		discount,
		total,
        itemDate,
        itemNo
		) 
		VALUES (
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
		    ?,
		    ?,    
		    ?
		)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'issssissssis',$quotId,$itemName,$itemDescription,$itemVehi,$itemCost,
		$itemQty,$subTotal,$tax,$discount,$total,$itemDate,$itemNo);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchInvoiceDetailsByInvoiceIdC1($con,$invoiceId){
		$dataList=array();
		$query="SELECT * FROM invoiceitemc1 WHERE invoiceId=? ";
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
	
		
		$query="DELETE FROM invoiceitemc1 WHERE invoiceId=? ";
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