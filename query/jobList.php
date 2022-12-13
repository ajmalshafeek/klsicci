<?php
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	function addJobListByVendor($con,$jobName,$vendorId,$createdDate,$createdBy,$orgId){
		$success=false;
		$query="INSERT INTO joblist (jobName,vendorId,createdDate,createdBy,orgId) 
		VALUES (?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sisii',$jobName,$vendorId,$createdDate,$createdBy,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}

	function deleteJobList($con,$jobId,$vendorId,$companyId,$orgId){
		$success=false;
	
		
		$query="DELETE FROM joblist WHERE 1=1 ";
		$paramList = array();

		if($jobId!==null){
			$query.="AND jobId=? ";
			$paramList[] = $jobId;
		}

		if($companyId!==null){
			$query.="AND clientId=? ";
			$paramList[] = $companyId;
		}

		if($vendorId!==null){
			$query.="AND vendorId=? ";
			$paramList[] = $vendorId;

		}

		if($orgId!==null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}
		
		$stmt=mysqli_prepare($con,$query);

		DynamicBindVariables($stmt, $paramList);

		//mysqli_stmt_bind_param($stmt,'iii',$staffId,$companyId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	function fetchVendorJobList($con,$vendorId,$status){
		$dataList=array();
		$query="SELECT * FROM joblist WHERE vendorId=? AND status >=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);
	
		return $dataList;
	}


	function fetchVendorJobListDetail($con,$jobListId){
		$query="SELECT * FROM joblist WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$jobListId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
	
		return $row;
	}
	
	
?>