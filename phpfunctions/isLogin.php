<?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	date_default_timezone_set("Asia/Kuala_Lumpur");
	if(!isset($_SESSION['userid'])) {
		session_destroy();
		header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/index.php');
		exit();
	}else{
		
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
		$con=connectDb();
		$userType=0;
		$userId=$_SESSION['userid'];
		if($_SESSION['type']=='org'){
			$userType=1;//$_SESSION['orgId'];
		}else if($_SESSION['type']=='client'){
			$userType=-1;
		}
		else if($_SESSION['type']=='vendor'){
			$userType=0;
		}
		//echo $_SESSION['userid'].":".$userType.":".null.":".$_SESSION['orgId'];
		$session=fetchSession($con,$_SESSION['userid'],$userType,null,$_SESSION['orgId']);
		//echo "ASDASDASD".$session['sessionId'];	
		if($session==null){
		
			session_destroy();
			header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/index.php');
			exit();
		}
	}

	
?>