<?php
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	function addSessionUser($con,$userId,$userType,$sessionId,$sessionStartTime,$orgId){
		$success=false;
		
		$query="INSERT INTO session (userId,userType,sessionId,sessionStartTime,orgId) 
		VALUES (?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'iissi',$userId,$userType,$sessionId,$sessionStartTime,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	function deleteUserSession($con,$userId,$sessionId,$userType,$orgId){
		$success=false;
	
		
		$query="DELETE FROM session WHERE 1=1 ";
		$paramList = array();

		if($userId!==null){
			$query.="AND userId=? ";
			$paramList[] = $userId;
		}

		if($sessionId!==null){
			$query.="AND sessionId=? ";
			$paramList[] = $sessionId;

		}

		if($userType!==null){
			$query.="AND userType=? ";
			$paramList[] = $userType;

		}

		if($orgId!==null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}
		
		$stmt=mysqli_prepare($con,$query);

		DynamicBindVariables($stmt, $paramList);

		
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);
		
		return $success;
	}
	function fetchSession($con,$userId,$userType,$sessionId,$orgId){
		$row=null;
		$query="SELECT * FROM session WHERE 1=1 ";
		$paramList = array();

		if($userId!=null){
			$query.="AND userId=? ";
			$paramList[] = $userId;
		}

		if($userType!=null){
			$query.="AND userType=? ";
			$paramList[]=$userType;
		}

		if($sessionId!=null){
			$query.="AND sessionId=? ";
			$paramList[]=$sessionId;
		}
		
		if($orgId!=null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}
		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();	
		mysqli_stmt_close($stmt);

		return $row;


	}
	function updateSessionUser($con,$userId,$userType,$sessionId,$sessionStartTime,$orgId){
		$success=false;
		$query="UPDATE session SET sessionId=?,sessionStartTime=? WHERE userId=? AND userType=? AND orgId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiii',$sessionId,$sessionStartTime,$userId,$userType,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		
		return $success;
	}

?>