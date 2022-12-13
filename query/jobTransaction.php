<?php


	function createJobTransaction($con,$jobId,$createdDate,$createdBy,$startTime,$endTime,$remarks,$status,$orgId){
		$id=0;
		$query="INSERT INTO jobtransaction (jobId,createdDate,createdBy,startTime,endTime,remarks,status,orgId)
		VALUES (?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isssssii',$jobId,$createdDate,$createdBy,$startTime,$endTime,$remarks,$status,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$id=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);
		
		return $id;

	}

	function createWorkerJobTransaction($con,$jobId,$createdDate,$createdBy,$remarks,$status,$orgId,$assignType,$workerID){
		$id=0;
		$query="INSERT INTO jobtransaction (jobId,createdDate,createdBy,remarks,status,orgId,assignType,workerId)
		VALUES (?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isssiisi',$jobId,$createdDate,$createdBy,$remarks,$status,$orgId,$assignType,$workerID);
		if(mysqli_stmt_execute($stmt)){
			$id=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);
		
		return $id;

	}

	function updateJobTransMessageStatus($con,$messageStatus,$jobTransId){
		$success=false;
		$query="UPDATE jobtransaction SET messageStatus=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$messageStatus,$jobTransId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;

	}

	function updateJobTransRemarks($con,$jobTransId,$remarks){
		$success=false;
		$query="UPDATE jobtransaction SET remarks=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$remarks,$jobTransId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobTransStatus($con,$jobTransId,$status){
		$success=false;
		$query="UPDATE jobtransaction SET status=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$status,$jobTransId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobTransactionWorkingPerod($con,$jobId,$startTime,$endTime){
		$success=false;
		$query="UPDATE jobtransaction SET startTime=?,endTime=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssi',$startTime,$endTime,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
	

	function updateJobTransactionSignaturePath($con,$jobTransactionId,$signaturePath){
		$success=false;
		$query="UPDATE jobtransaction SET signaturePath=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$signaturePath,$jobTransactionId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function fetchJobTransByJobId($con,$jobId){
		$query="SELECT * FROM jobtransaction WHERE jobId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$jobId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}
	
	function updateWorkerIdJobTransaction($con, $jobId, $workerId){
		$success=false;
		$query="UPDATE jobtransaction SET workerId=? WHERE jobId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$workerId, $jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
?>