<?php
//if()
$config=parse_ini_file(__DIR__."/jsheetconfig.ini");
session_name($config['sessionName']);
session_start();
header("Location:https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/login.php");
?>

