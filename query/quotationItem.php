<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createQuotationItemBreakdown($con,$quotId,$itemName,$itemDescription,$itemCost,
	$itemQty,$subTotal,$tax,$discount,$total){
		
		$success=false;
		$query="INSERT INTO quotationitem (
		quotationId,
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

	function deleteQuotationItemByQuotId($con,$quotationId){
		$success=false;
		
		$query="DELETE FROM quotationitem WHERE quotationId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$quotationId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchQuotationItemBreakdownByQuotId($con,$quotationId){
		$dataList=array();

		$query="SELECT * FROM quotationitem WHERE quotationId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$quotationId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
			
		}
		mysqli_stmt_close($stmt);
		return $dataList;
	}
?>