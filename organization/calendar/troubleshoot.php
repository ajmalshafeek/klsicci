<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

if (isset($_POST["id"])) {
  $userid = $_SESSION['userid'];
  $title = $_POST['title'];
  $start_event = $_POST['start'];
  $end_event = $_POST['end'];
  echo $userid.$title.$start_event.$end_event;
//  $sql = "INSERT INTO `events`  (`userid`,`title`, `start_event`, `end_event`)  VALUES ('$userid', '$title', '$start_event', '$end_event')";
  $result = mysqli_query(connectDb(), $sql);
}

?>
