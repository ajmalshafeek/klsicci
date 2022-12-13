<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);
	session_start(); 
} 

	if(isset($_POST["logout"])){
		session_destroy();
		header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/index.php');
	}

if(isset($_GET["logout"])){
    session_destroy();
    header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/index.php');
}

	
?>