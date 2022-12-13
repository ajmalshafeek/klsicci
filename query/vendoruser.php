<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	function addVendorUser($con,$vendorId,$name,$vendorUsername,$vendorPassword,$email,$createdBy,$createdDate,$role,$orgId){
		$success=false;
		$status=1;
		$query="INSERT INTO vendoruser (fullName,name,identification,username,password,email,vendorId,status,role,orgId) 
		VALUES (?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssssssiiii',$fullName,$name,$identification,$vendorUsername,$vendorPassword,$email,$vendorId,$status,$role,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}


	function deleteVendorUser($con,$staffId,$vendorId,$orgId){
		$success=false;
	
		
		$query="DELETE FROM vendoruser WHERE 1=1 ";
		$paramList = array();

		if($staffId!==null){
			$query.="AND id=? ";
			$paramList[] = $staffId;
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
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}


	function getVendorDetails($con,$id){
		$query="SELECT * FROM vendoruser WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function getVendorDetailsByUsername($con,$username){
		$query="SELECT * FROM vendoruser WHERE username=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}


	function login($con,$memberId,$password,$status,$orgId){
		$success=false;
		
		$query="SELECT username FROM vendoruser WHERE username=? AND password=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$memberId,$password,$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$num_rows = mysqli_num_rows($result);
		
		if($num_rows>0){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;	
	}

	function getLoginDetails($con,$memberId,$password,$status,$orgId){

		$query="SELECT * FROM vendoruser WHERE username=? AND password=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$memberId,$password,$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;

	}

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
	
	function fetchVendorUserDetails($con,$userId,$vendorId,$orgId){
		$query="SELECT * FROM vendoruser WHERE id=? and vendorId=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'iii',$userId,$vendorId,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}

	function fetchVendorAdminDetails($con,$vendorId,$orgId){
		$query="SELECT * FROM vendoruser WHERE vendorId=? AND orgId=? AND role=1";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorId,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;
	}
	
/*
	function DynamicBindVariables($stmt, $params)
	{
		if ($params != null)
		{
			$types = '';
			foreach($params as $param)
			{
				if(is_int($param)) {
					$types .= 'i';
				} elseif (is_float($param)) {
					$types .= 'd';
				} elseif (is_string($param)) {
					$types .= 's';
				} else {
					$types .= 'b';
				}
			}
	  
			$bind_names[] = $types;
	  
			for ($i=0; $i<count($params);$i++)
			{
				$bind_name = 'bind' . $i;
				$$bind_name = $params[$i];
				$bind_names[] = &$$bind_name;
			}
			call_user_func_array(array($stmt,'bind_param'), $bind_names);
		}
		return $stmt;
	}

	*/

	function updateVendorNewPassword($con,$uid,$password){
		$success=false;
		$query="UPDATE vendoruser SET password=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$password,$uid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
	/*
	function changeVoteStatus($con,$uid,$status){
		$success=false;
		$query="UPDATE users SET votestatus=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$status,$uid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		echo $success;
		mysqli_stmt_close($stmt);

		return $success;
	}

	function getAllUsers($con){
		$dataList=array();
		$query="SELECT * FROM users";
		$stmt=mysqli_prepare($con,$query);;
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
			
		}
		mysqli_stmt_close($stmt);
		return $dataList;
	}

	function fetchUserDetails($con,$userId){
		$query="SELECT * FROM users WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
		
		return $row;
	}

	function getLoginDetails($con,$memberId,$password){

		
		//$dataList=array();

		$query="SELECT * FROM users WHERE memberId=? AND password=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$memberId,$password);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
			
		mysqli_stmt_close($stmt);

		return $row;

	}
	
	function countGroupMember($con,$groupId){
		
		$query="SELECT COUNT(*) as count FROM users WHERE groupId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$groupId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		$row=$result->fetch_assoc();
		
		mysqli_stmt_close($stmt);

		return $row;
	}
	
	function updateBODForm($con,$memberId,$bodId){
		
		$success=false;
		$query="UPDATE users SET bodId=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$bodId,$memberId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
	*/
?>