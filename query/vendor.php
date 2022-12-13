<?php

	function addVendor($con,$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$createdBy,$createdDate,$orgId){
		$vendorId=0;
		$status=1;
		$query="INSERT INTO vendor (name,regNo,address,contactNo,emailAddress,createdDate,createdBy,status,orgId) 
		VALUES (?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssssssiii',$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$createdDate,$createdBy,$status,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$vendorId=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);
		
		return $vendorId;
	}
function updateVendor($con,$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$id){
    $vendorId=$id;
    $success=false;
    $query="UPDATE vendor AS v, vendoruser AS vu SET  v.name=?,vu.name=?, v.regNo=?,v.address=?,v.contactNo=?,v.emailAddress=? WHERE v.id=vu.vendorId AND v.id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssssssi',$vendorName,$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$vendorId);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    return $success;
}

	function deleteVendor($con,$vendorId,$orgId){
		$success=false;
		
		$query="DELETE FROM vendor WHERE id=? AND orgId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}

	function fetchVendorList($con,$status,$orgId){
		$dataList=array();

		$query="SELECT * FROM vendor WHERE status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);
		return $dataList;
	}

	function fetchVendorDetails($con,$id){

		$query="SELECT * FROM vendor WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
        mysqli_stmt_close($stmt);
		return $row;
	}

	function noOfVendor($con,$orgId){
		$query="SELECT * FROM vendor WHERE  orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$num_rows = mysqli_num_rows($result);
	
		return $num_rows;
	}

function checkVenderName($con,$vendorName){
    $query="SELECT COUNT(name) as num FROM `vendor` WHERE `name`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$vendorName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    $success=$row['num'];
    mysqli_stmt_close($stmt);
    return $success;
}

function checkVenderUser($con,$vendorUsername){
    $query="SELECT COUNT(username) as num FROM `vendoruser` WHERE `username`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$vendorUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    $success=$row['num'];
    mysqli_stmt_close($stmt);
    return $success;
}

?>