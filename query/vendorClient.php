<?php
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	function addVendorClient($con,$cliendCompanyId,$createdDate,$createdBy,$vendorId,$orgId){
		$success=false;
		$query="INSERT INTO vendorclient (clientCompanyId,createdDate,createdBy,vendorId,orgId) 
		VALUES (?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'isiii',$cliendCompanyId,$createdDate,$createdBy,$vendorId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}

	function deleteVendorClient($con,$companyId,$vendorId,$orgId){
		$success=false;
	
		
		$query="DELETE FROM vendorclient WHERE 1=1 ";
		$paramList = array();

		if($companyId!==null){
			$query.="AND clientCompanyId=? ";
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

	function fetchVendorClientList($con,$vendorId,$status){
		$dataList=array();

		$query="SELECT * FROM vendorclient WHERE vendorId=? AND status=?";
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

	function vendorClientDetails($con,$id){

		$query="SELECT * FROM vendorclient WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		
		mysqli_stmt_close($stmt);

		return $row;
	}

?>