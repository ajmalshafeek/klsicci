<?php

	function addAutoNum($con,$vendorId,$orgId){
		$success=false;
		$query="INSERT INTO autonum (vendorId,orgId) 
		VALUES (?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}

	function updateJobNo($con,$autoNumId,$jobNo){
		$success=false;
		$query="UPDATE autonum SET jobNo=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$jobNo,$autoNumId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function fetchAutoNum($con,$vendorId){
		
		$query="SELECT * FROM autonum WHERE vendorId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$vendorId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		
		mysqli_stmt_close($stmt);
		return $row;
	}

	function fetchOrgAutoNum($con,$orgId){
		
		$query="SELECT * FROM autonum WHERE orgId=? AND vendorId='0'";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		
		mysqli_stmt_close($stmt);
		return $row;
	}

?>