<?php

	function updateOrganization($con,$name,$address1,$address2,$postalCode,$city,$state,
	$contact,$orgFaxNo,$logo,$orgId,$supportEmail,$salesEmail){
		$success=false;
		$query="UPDATE organization SET name=?, address1=?,address2=?,postalCode=?,
		city=?, state=?, contact=?, faxNo=?,logoPath=?, supportEmail=?, salesEmail=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssssssssi',$name,$address1,$address2,$postalCode,$city,$state,
		$contact,$orgFaxNo,$logo,$supportEmail,$salesEmail,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function getOrganizationDetails($con,$orgId){

		$query="SELECT * FROM organization WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;

	}


	function limitOfOrg($con,$orgId){


		$query="SELECT maxStaff,maxVendor,maxClient FROM organization WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function updateBannerName($con, $orgId, $banner){
		$success=false;
		$query="UPDATE organization SET banner=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$banner, $orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateOrgType($con, $orgType, $orgId){
		$success=false;
		$query="UPDATE organization SET type=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$orgType, $orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

?>
