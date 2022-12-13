<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	function addClientUser($con,$fullName,$name,$identification,$username,$password,$email,$status,$role,$companyId,$orgId){
		$success=false;
		
		$query="INSERT INTO clientuser (fullName,name,identification,username,password,email,status,role,companyId,orgId) 
		VALUES (?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssssssiiii',$fullName,$name,$identification,$username,$password,$email,$status,$role,$companyId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}
function checkIfEmailSentSetModule($con,$role){
$query="SELECT * FROM `rolemodules` WHERE `roleId`=? AND `moduleId`=50";
$status=false;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$role);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while($row = $result->fetch_assoc()){
            $status=true;
        }
        mysqli_stmt_close($stmt);
    }
    return $status;
}

	function deleteClientUser($con,$staffId,$companyId,$orgId){
		$success=false;
	
		
		$query="DELETE FROM clientuser WHERE 1=1 ";
		$paramList = array();

		if($staffId!==null){
			$query.="AND id=? ";
			$paramList[] = $staffId;
		}

		if($companyId!==null){
			$query.="AND companyId=? ";
			$paramList[] = $companyId;

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
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}

	function getClientDetailsByUsername($con,$username){
		$query="SELECT * FROM clientuser WHERE username=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function getClientDetails($con,$id){
		$query="SELECT * FROM clientuser WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function clientlogin($con,$username,$password,$status,$orgId){
		$success=false;
		
		$query="SELECT username FROM clientuser WHERE username=? AND password=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$username,$password,$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		return $success;	
	}

	function getClientLoginDetails($con,$username,$password,$status,$orgId){

		$query="SELECT * FROM clientuser WHERE username=? AND password=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$username,$password,$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;

	}

	function fetchClientUserList($con,$companyId,$orgId){
		$dataList=array();

		$query="SELECT * FROM clientuser WHERE companyId=? AND orgId=? ";
		$paramType="";
		$paramList = array();

		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$companyId,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);
		

		return $dataList;
	}

	function fetchClientUserDetail($con,$id){
		$query="SELECT * FROM clientuser WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function fetchClientAdminDetails($con,$clientId,$orgId){
		$query="SELECT * FROM clientuser WHERE companyId=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$clientId,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function updateClientNewPassword($con,$uid,$password){
		$success=false;
		$query="UPDATE clientuser SET password=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$password,$uid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
/*
	function fetchVendorUserList($con,$vendorId,$status,$orgId){
		$dataList=array();

		$query="SELECT * FROM vendoruser WHERE vendorId=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'iii',$status,$vendorId,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
			
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}
	*/
function checkMembershipModule($con,$cid){
    $dataList=array();
    $query="SELECT * FROM memberTransanction WHERE cid=? ORDER BY id DESC";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$cid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count=1;
    while($row=$result->fetch_assoc()){
        if($count==1){
        $dataList=$row;
        $count++;
        }
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}
?>