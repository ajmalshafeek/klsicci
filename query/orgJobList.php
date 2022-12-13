<?php
/*
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
*/
	function fetchOrgJobList($con,$vendorId,$status){
		$dataList=array();
		$query="SELECT * FROM orgjoblist WHERE orgId=? AND status >=?";
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


	function fetchOrgJobListDetail($con,$jobListId){
		$query="SELECT * FROM orgjoblist WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$jobListId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
	
		return $row;
	}

	
?>