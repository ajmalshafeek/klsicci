<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

if (isset($_POST["title"])) {
  $userid = $_SESSION['userid'];
  $remarks = $_POST['remarks'];
  $title = $_POST['title'];
  $start_event = $_POST['start'];
  $end_event = $_POST['end'];
  $sql = "INSERT INTO `events`  (`userid`,`title`, `start_event`, `end_event`, `remarks`)  VALUES ('$userid', '$title', '$start_event', '$end_event', '$remarks')";
  $result = mysqli_query(connectDb(), $sql);

  if (isset($_POST['followup'])) {
    $stringCheck = "-Follow Up";

    if(strpos($title, $stringCheck) !== false){
    }else{
      $title = $title."-Follow Up";
    }

    $start_event = $_POST['followup'];
    $end_event =date('Y-m-d h:i:sa',strtotime('+1 hour +20 minutes',strtotime($_POST['followup'])));
    $sql = "INSERT INTO `events`  (`userid`,`title`, `start_event`, `end_event`, `remarks`)  VALUES ('$userid', '$title', '$start_event', '$end_event', '$remarks')";
    $result = mysqli_query(connectDb(), $sql);
  }
}
/*
$connect = new PDO('mysql:host=localhost;dbname=jcloudmy_jobsheetDemo', 'jcloudmy_admin2', 'admin2jsoft');

if(isset($_POST["title"]))
{
 $query = "
 INSERT INTO events
 (title, start_event, end_event)
 VALUES (:title, :start_event, :end_event)
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end']
  )
 );
}
*/

?>
