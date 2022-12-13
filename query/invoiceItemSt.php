<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	


	function createInvoiceItemBreakdownSt($con,$quotId,$itemDate,$itemRef,$itemDescription,$itemCredit,
	$itemDebit,$itemPrice){
		
		$success=false;
		$query="INSERT INTO invoiceitemst (
		invoiceId,
        itemDate,                   
		itemRef,
		itemDescription,
		itemCredit,
		itemDebit,
		itemPrice
		) 
		VALUES (
			?,
			?,
			?,
			?,
			?,
			?,
		    ?
		)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'issssss',$quotId,$itemDate,$itemRef,$itemDescription,$itemCredit,
            $itemDebit,$itemPrice);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	
	function fetchInvoiceDetailsByInvoiceIdSt($con,$invoiceId){
		$dataList=array();
		$query="SELECT * FROM invoiceitemst WHERE invoiceId=? ";
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
	
		
		$query="DELETE FROM invoiceitemst WHERE invoiceId=? ";
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