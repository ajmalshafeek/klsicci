<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);

	session_start(); 
}
	date_default_timezone_set("Asia/Kuala_Lumpur");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/attendanceSetting.php");
	
	
	if(isset($_POST['addOfficerEmail'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> EMAIL ID TO ADD IN LIST\n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$email=$_POST['emailAdd'];

		$updateSuccess=insertOfficerEmail($con,$email);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY EMAIL ADDED\n
			</div>\n";
		}
		$_SESSION['orgLogo']=$logo;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/setting.php");
	}
	if(isset($_POST['lateAttendanceSetup'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> LATE ATTENDANCE SET IS FAILED\n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$startTime=$_POST['startTime'];
		$endTime=$_POST['endTime'];
		$workingStart=$_POST['workingDayStart'];
		$workingEnd=$_POST['workingDayEnd'];
		$emailTime=$_POST['lateTimeSet'];
		$emailOfficer=$_POST['emailList'];


		if(!isset($emailOfficer)){
			header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/setting.php");
		}

		$to="";
		$count=1;
		if(is_array($emailOfficer)){
		foreach($emailOfficer as $email){
			if($count==1){
				$to=$email;
			}else{
				$to.=",".$email;
			}
			$count++;
					}
		}else{
			$to=$emailOfficer;
		}

		$updateSuccess=insertLateEmailSetting($con,$startTime,$endTime,$workingStart,$workingEnd,$to,$emailTime);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY LATE ATTENDANCE SET\n
			</div>\n";
		}
	//	$_SESSION['orgLogo']=$logo;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/setting.php");
	}


	if(isset($_POST['deleteOfficerEmail'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> EMAIL ID TO DELETE FROM LIST\n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$email=$_POST['emailDelete'];
		$updateSuccess=deleteOfficerEmail($con,$email);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY DELETE FROM LIST\n
			</div>\n";
		}
		//$_SESSION['orgLogo']=$logo;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/setting.php");
	}



	function listOfficerEmail(){
		$con=connectDb();
		$dataList = listOfficerEmails($con);
		foreach($dataList as $data){
			if(isset($data['officerEmail'])){
			echo '<option value="'.$data['officerEmail'].'">'.$data['officerEmail'].'</option>';}
		}

	}
	function getLateEmailSetting(){
		$con = connectDb();
		$datalist =getLateEmailSettingDetials($con);
		return $datalist;
	}
?>