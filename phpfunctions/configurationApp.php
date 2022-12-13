<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION))
{
	session_name($config['sessionName']);
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/configurationApp.php");


if(isset($_POST['AddCCEmail'])){
	$con=connectDb();
	$ccemail=$_POST['ccEmail'];
	$saveSuccess=addCCEmail($con,$ccemail);
	if($saveSuccess){
		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> <strong>".$ccemail."</strong> CC Email SUCCESSFULLY SAVED</div>";
		// End of CLEANTO ADD STAFF
	}else{
		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>FAILED!</strong> FAILED TO SAVE CC Email\n
				</div>\n";
	}


	header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/configuration/appConfiguration.php");
}

function getCCEmailId(){
	$con=connectDb();
return getCCEmail($con);
}


?>
