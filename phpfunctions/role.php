<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/roles.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");

	function isOrganization(){
		$result=false;
		if($_SESSION['type']==='org'){
			$result=true;
		}
        return $result;
	}

	function isClientStore(){
		$result=false;
		if($_SESSION['type']==='clientStore'){
			$result=true;
		}
		return $result;
	}

	function isClient(){
		$result=false;
		if($_SESSION['type']==='client'){
			$result=true;
		}
		return $result;

	}
	function isVendor(){
		$result=false;
		if($_SESSION['type']==='vendor'){
			$result=true;
		}
		return $result;

	}
	function isAdmin(){
    $con= connectDb();
		$result=false;

    $row = fetchRoleById($con,$_SESSION['role']);
		if($row['status'] === 0){
			$result=true;
		}

		return $result;
	}

	function isNormalUser(){
		$result=false;
		if($_SESSION['serviceEngineer']){
			$result=true;
		}
		return $result;
	}

	function loadAllRoles(){
    $con= connectDb();
		$dataList = fetchAllRoles($con);
		$result = "";
		foreach ($dataList as $data) {
			$result .= "<option value='".$data['id']."'>".$data['role']."</option>";
		}
		return $result;
	}

  function loadAllRolesSelected($role){
    $con= connectDb();
    $dataList = fetchAllRoles($con);
    $result = "";
    foreach ($dataList as $data) {
      $selected = "";
      if ($role==$data['id']) {
        $selected = "selected";
      }
      $result .= "<option value='".$data['id']."' ".$selected.">".$data['role']."</option>";
    }
    return $result;
  }

function getLoanDetails($staffId){
    $con= connectDb();
    $dataList = fetchLoanDetails($con,$staffId);
    return $dataList;
}

  function loadModulesByRoleId($roleId){
    $con= connectDb();
    $dataList = fetchRoleModulesByRoleId($con,$roleId);
    return $dataList;
  }

  if (isset($_GET['loadPrivilegeCheckbox'])) {
    $con= connectDb();
    $roleId = $_GET['loadPrivilegeCheckbox'];
    $dataListModules = fetchAllModules($con);
    $dataListRoleModules = fetchRoleModulesByRoleId($con,$roleId);

    $result = "<table><tr><th>Module ID</th><th>MODULES</th><th><center><input id='checkAll' onclick='checkAllBox()' type='checkbox' ></center></th></tr>";
    foreach ($dataListModules as $dataModules) {
      $checked="";
      foreach ($dataListRoleModules as $dataRoleModule) {
        if ($dataModules['id'] == $dataRoleModule['moduleId']) {
          $checked="checked";
          break;
        }
      }
      $result .= "<tr><td>".$dataModules['id']."</td><td>".$dataModules['module']."</td><td><center><div class='checkbox'><label><input class='checkAllBox' type='checkbox' name='".$dataModules['id']."' value='".$dataModules['id']."' ".$checked."></label></div></center></td></tr>";
    }
    $result .= "</table><input type='text' name='roleId' value='$roleId' hidden>";
    echo $result;
  }

  if (isset($_POST['updatePrivilege'])) {
    $feedback = "";
    $con = connectDb();

    //(START)UPDATE ORGANIZATION TABLE
    $orgType = $_POST['orgType'];
    $orgId = $_SESSION['orgId'];
    $row = updateOrgType($con, $orgType, $orgId);
    if ($row) {
      $feedback .= "<div class='alert alert-success' role='alert'>\n
            <strong>SUCCESS!</strong> ORGANIZATION TYPE CHANGED\n
            </div>\n";
      $_SESSION['orgType'] = $orgType;
    }else {
      $feedback .= "<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO CHANGE ORGANIZATION TYPE\n
        </div>\n";
    }
    //(END)UPDATE ORGANIZATION TABLE

	  // Internal or External usage update start
	  $internal=0;
	  $external=0;
	  $supportCam=0;
	  $staffCam=0;
	  $memberReg=0;
	  $staffLoan=0;

	  if(isset($_POST['internal'])) {
		  $internal = $_POST['internal'];
	  }
	  if(isset($_POST['external'])) {
		  $external=$_POST['external'];}
	  if(isset($_POST['staffcam'])) {
		  $staffCam=$_POST['staffcam'];}
	  if(isset($_POST['supportcam'])) {
		  $supportCam=$_POST['supportcam'];}
	  if(isset($_POST['membership'])) {
          $memberReg=$_POST['membership'];}
	  if(isset($_POST['staffloan'])) {
          $staffLoan=$_POST['staffloan'];}
	  if(isset($_POST['location'])) {
          $location=$_POST['location'];}
	  $clientas=0;
	  if(isset($_POST['clientas'])){
		  $clientas=$_POST['clientas'];
	  }
	  $clientpasscol=0;
	  if(isset($_POST['clientpasscol'])){
		  $clientpasscol=$_POST['clientpasscol'];
	  }


	  $feedbackUpdate = updateUsage($con,$internal,$external,$staffCam,$supportCam,$clientas,$clientpasscol,$memberReg,$staffLoan,$location);
	  // Internal or External usage update end

    if (isset($_POST['roleId'])) {
      $roleId = $_POST['roleId'];
      $feedbackDelete = removeRoleModulesByRoleId($con,$roleId);
      $addDate = date("Y-m-d");
      $addTime = date("h:i:sa");

      $dataList = fetchAllModules($con);
        $temp='';
        foreach ($dataList as $data) {
        if (isset($_POST[$data['id']])) {
          $moduleId = $data['id'];
          $feedbackInsert = insertRoleModules($con,$roleId,$moduleId,$addDate,$addTime);
        }
      }

      //feedback
      if ($feedbackDelete && $feedbackInsert) {
        $feedback .= "<div class='alert alert-success' role='alert'>\n
              <strong>SUCCESS!</strong> PRIVILEGE UPDATED\n
              </div>\n";
      }
      if (!$feedbackDelete) {
        $feedback .= "<div class='alert alert-danger' role='alert'>\n
          <strong>FAILED!</strong> FAILED TO DELETE DATA IN TABLE\n
          </div>\n";
      }
      if (!$feedbackInsert) {
        $feedback .= "<div class='alert alert-danger' role='alert'>\n
          <strong>FAILED!</strong> FAILED TO INSERT DATA IN TABLE\n
          </div>\n";
      }
    }




		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");

	  $orgDetails=getOrganizationDetails($con,$orgId);
/*
	  $orgAddress=$orgDetails['address1'].",";

	  $from=$orgDetails['supportEmail'];
	  $fromName=$orgDetails['name'];
	  $to='krish@jsoftsolution.com.my';
	  $subject='Privilege Updated! Notification '.$orgDetails['name'];
	  $body='Updated action performed in privilege for '.$_SERVER['SERVER_NAME'].' by '.$_SESSION['name'].'@'.$orgDetails['name'].
		  '. In case! This action not done in you supervision then take action immediately.<br /><br />Thank You';

	  //debug
	  $detailslist="\nFrom:".$from." Name:".$from." worker-email:".$to." subject:".$subject;
	  $orgLogo=$_SESSION['orgLogo'];
	  $mailMessage=mailTask($from,$fromName,$to,$subject,$body,$orgLogo);
*/


    $_SESSION['feedback']=$feedback;
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/role/role.php");
  }



?>