<?php


	function createJobTransactionItem($con,$jobTransactionId,$itemName,$qty,$orgId){
		$success=false;
		$query="INSERT INTO jobtransactionitemlistbreakdown (jobTransactionId,itemName,qty,orgId) 
		VALUES (?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isii',$jobTransactionId,$itemName,$qty,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);
		
		return $success;

	}

	function fetchJobItemList($con,$transId){
		$dataList=array();
		$query="SELECT * FROM jobtransactionitemlistbreakdown WHERE jobTransactionId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$transId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);
	
		return $dataList;
	}

?>