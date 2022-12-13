<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createInvoiceItemBreakdown($con,$quotId,$itemName,$itemDescription,$itemCost,
	$itemQty,$subTotal,$tax,$discount,$total){
		
		$success=false;
		$query="INSERT INTO invoiceitem (
		invoiceId,
		itemName,
		itemDescription,
		itemPrice,
		quantity,
		subTotal,
		tax,
		discount,
		total
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
			?
		)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isssissss',$quotId,$itemName,$itemDescription,$itemCost,
		$itemQty,$subTotal,$tax,$discount,$total);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchInvoiceDetailsByInvoiceId($con,$invoiceId){
		$dataList=array();
		$query="SELECT * FROM invoiceitem WHERE invoiceId=? ";
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
	
		
		$query="DELETE FROM invoiceitem WHERE invoiceId=? ";
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