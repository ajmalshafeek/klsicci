<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

/*
	function addStaff($con,$staffId,$fullName,$name,$email,$phone,$userName,$password,$status,$role,$orgId,$staffDesignation,$department,$address1,$address2,$city,$postalCode,$state,$contact,$married,$education,$license,$staffIC,$staffPassport,$staffPerkeso,$staffKWSP,$staffGender,$staffStart){
		$success=false;
		//debug
		$role=$role;

		$query="INSERT INTO organizationuser (staffId,fullname,name,email,phone,username,password,status,role,orgId,staffDesignation,department,address1,address2,city,postalCode,state,contact,married,education,license,ic,passport,perkeso,kwsp,gender,startdate)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssssiiissssssssiiissssss',$staffId,$fullName,$name,$email,$phone,$userName,$password,$status,$role,$orgId,$staffDesignation,$department,$address1,$address2,$city,$postalCode,$state,$contact,$married,$education,$license,$staffIC,$staffPassport,$staffPerkeso,$staffKWSP,$staffGender,$staffStart);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
            mysqli_stmt_close($stmt);
		}
		return $success;
	}

        function checkStaff($con,$staffId){
            $success=false;
            //debug
            $query="select staffId FROM organizationuser WHERE staffId = ?";
            $stmt=mysqli_prepare($con,$query);
            mysqli_stmt_bind_param($stmt,'s',$staffId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $num_rows = mysqli_num_rows($result);
            if($num_rows>0){
                $success=true;
                }
            mysqli_stmt_close($stmt);

        return $success;
         }

	function deleteOrganizationUser($con,$staffId,$orgId){
		$success=false;

		$query="DELETE FROM organizationuser WHERE id=? AND orgId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$staffId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
            mysqli_stmt_close($stmt);
		}


		return $success;
	}

	function organizationLogin($con,$memberId,$password,$status,$orgId){
		$success=false;

		$query="SELECT username FROM organizationuser WHERE username=? AND password=? AND status=? AND orgId=?";
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

	function fetchOrganizationStaffList($con,$orgId,$role,$status){
		//require_once('organizationType.php');
		$internal=0;
		//$dataListUsage= fetchOrgUse($con);

		//foreach($dataListUsage as $usageData){
			//if($usageData['internal']==1){
			if($_SESSION['internalUse']==1){
				$internal=true;
			}else{
				$internal=false;
			}
		//}
		$dataList=array();

		$query="SELECT * FROM organizationuser WHERE 1=1 ";
		$paramType="";
		$paramList = array();

		if($status!=null){
			$query.="AND $status=? ";
			$paramList[] = $status;
		}
		if($internal==true){
			$query.="AND role NOT IN (?, 1) ";
			$paramList[]=$role;
		}
		elseif($role!=null){
			$query.="AND role NOT IN (?, 1) ";
			$paramList[]=$role;
		}

		if($orgId!=null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}
		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);


		return $dataList;
	}

function fetchDepartmentList($con){
    $dataList=array();
    $num=0;
    $query="SELECT * FROM `department` WHERE 1";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[] = $row;

}

    return $dataList;
}

function fetchDesignationList($con){
    $dataList=array();
    $num=0;
    $query="SELECT * FROM `departmentprofile` WHERE 1";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
    return $dataList;
}

  function fetchOrganizationStaffListIfIsStaff($con,$orgId,$isStaff,$status){
    $dataList=array();
$role=42;
    $query="SELECT * FROM organizationuser WHERE 1=1 ";
    $paramType="";
    $paramList = array();

    if($status!=null){
      $query.="AND $status=? ";
      $paramList[] = $status;
    }
   /* if($isStaff!=null){
      $query.="AND isStaff=? ";
      $paramList[]=$isStaff;
    }
*/
/*
	  if($isStaff!=null){
		  $query.="AND role!=? ";
		  $paramList[]=$role;
	  }
    if($orgId!=null){
      $query.="AND orgId=? ";
      $paramList[] = $orgId;

    }
    $stmt=mysqli_prepare($con,$query);
    DynamicBindVariables($stmt, $paramList);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
      $dataList[]=$row;
    }
    mysqli_stmt_close($stmt);


    return $dataList;
  }

	function fetchOrganizationUserList($con){
      $dataList=array();

      $query="SELECT * FROM organizationuser
      WHERE 1";
      $stmt=mysqli_prepare($con,$query);
      //mysqli_stmt_bind_param($stmt);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      while($row=$result->fetch_assoc()){
        $dataList[]=$row;

      }
      mysqli_stmt_close($stmt);

      return $dataList;
    }

    function fetchOrganizationUserListbyId($con,$userid){

      $dataList=array();

      $query="SELECT * FROM organizationuser
      WHERE id=?";
      $stmt=mysqli_prepare($con,$query);
      mysqli_stmt_bind_param($stmt,'i',$userid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      while($row=$result->fetch_assoc()){
        $dataList[]=$row;
      }
      mysqli_stmt_close($stmt);
      return $dataList;
    }
    
    function fetchOrganizationUserbyId($con,$userid){
      $query="SELECT * FROM organizationuser
      WHERE id=?";
      $stmt=mysqli_prepare($con,$query);
      mysqli_stmt_bind_param($stmt,'i',$userid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row=$result->fetch_assoc();
      mysqli_stmt_close($stmt);

      return $row;
    }


	function getOrganizationLoginDetails($con,$memberId,$password,$status,$orgId){

		$query="SELECT * FROM organizationuser WHERE username=? AND password=? AND status=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$memberId,$password,$status,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;

	}

	function fetchOrganizationAdminDetails($con,$orgId){
		$query="SELECT * FROM organizationuser WHERE orgId=? AND role=1";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function getOrganizationUserDetails($con,$id){
		$query="SELECT * FROM organizationuser WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function noOfOrgStaff($con,$role,$orgId){

		$query="SELECT * FROM organizationuser WHERE role=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$role,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$num_rows = mysqli_num_rows($result);

		return $num_rows;

	}

	function getOrganizationUserDetailsByUsername($con,$username){
		$query="SELECT * FROM organizationuser WHERE username=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$username);
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
/*
	function updateNewPassword($con,$uid,$password){
		$success=false;
		$query="UPDATE organizationuser SET password=? WHERE id=?";
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
/*
// Department Start
function addCCEmail($con,$ccemail){
    $success=false;
	$status=0;
    //debug
    $query="INSERT INTO department (name)
		VALUES (?); ";

    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$departmentName);
    if(mysqli_stmt_execute($stmt)){



	       mysqli_stmt_close($stmt);
    }


    return $success;
}

function deleteDepartment($con,$departmentId){
    $success=false;
    $query="DELETE FROM department WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$departmentId);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
        mysqli_stmt_close($stmt);
    }

    return $success;
}
// Departmen End

// Designation Start
function addDesignation($con,$designation){
    $success=false;
    //debug
	$status=0;
    $query="INSERT INTO departmentprofile (designation)
		VALUES (?)";
	$query1="INSERT INTO roles (`role`,`status`)
		VALUES (?,?)";
	    $stmt=mysqli_prepare($con,$query1);
	    mysqli_stmt_bind_param($stmt,'si',$designation,$status);
	    if(mysqli_stmt_execute($stmt)){
		    $stmt=mysqli_prepare($con,$query);
		    mysqli_stmt_bind_param($stmt,'s',$designation);
		    if(mysqli_stmt_execute($stmt)){
		    $success=true;
		    }
		}

        mysqli_stmt_close($stmt);

    return $success;
}

function deleteDesignation($con,$designationId){
	$success=false;
/*
    $query="DELETE FROM departmentprofile WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$designationId);
    if(mysqli_stmt_execute($stmt)){




        $success=true;
        mysqli_stmt_close($stmt);
    }
	*/
/*
	$query="SELECT `designation` FROM departmentprofile WHERE id=?";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'i',$designationId);
	mysqli_stmt_execute($stmt);
	if($result = mysqli_stmt_get_result($stmt)) {
		$row = $result->fetch_assoc();

		$query="SELECT `id` FROM roles WHERE role=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$row['designation']);
		mysqli_stmt_execute($stmt);
		if($result = mysqli_stmt_get_result($stmt)) {
			$row1 = $result->fetch_assoc();
			$query="DELETE FROM rolemodules WHERE roleId=?";
			$stmt=mysqli_prepare($con,$query);
			mysqli_stmt_bind_param($stmt,'i',$row1['id']);
			if(mysqli_stmt_execute($stmt)){
		$query="DELETE FROM roles WHERE role=?";
				$stmt=mysqli_prepare($con,$query);
				mysqli_stmt_bind_param($stmt,'s',$row['designation']);
				if(mysqli_stmt_execute($stmt)){
					$query="DELETE FROM departmentprofile WHERE id=?";
					$stmt=mysqli_prepare($con,$query);
					mysqli_stmt_bind_param($stmt,'i',$designationId);
					if(mysqli_stmt_execute($stmt)){
						$success=true;
					}
				}
			}

		}

		mysqli_stmt_close($stmt);
	}






    return $success;
}
// Designation End

*/

// Department Start
function addCCEmail($con,$ccEmail){
	$success=false;
	$status=0;
	$id=1;
	//debug
	$query="UPDATE appconf SET ccemail=? WHERE id=?";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'si',$ccEmail,$id);
	if(mysqli_stmt_execute($stmt)){
		$success=true;
	}
	echo $success;
	mysqli_stmt_close($stmt);

	return $success;
}

function getCCEmail($con){
	$email="";
	$status=0;
	$id=1;
	//debug
	$query="SELECT * FROM appconf WHERE id=?";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'i',$id);
	mysqli_stmt_execute($stmt);
	if($result = mysqli_stmt_get_result($stmt)) {
		$row = $result->fetch_assoc();
		$email=$row['ccemail'];
	}
	mysqli_stmt_close($stmt);
	return 	$email;
}

?>
