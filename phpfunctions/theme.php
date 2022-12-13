<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/theme.php");

if (isset($_GET['changeTheme'])) {
  $con=connectDb();
  $orgId=$_SESSION['orgId'];
  $theme = $_GET['changeTheme'];
  $feedback = changeTheme($con,$theme,$orgId);
  if ($feedback) {
      $_SESSION['theme']=$theme;
  }
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/home.php");
}
?>
