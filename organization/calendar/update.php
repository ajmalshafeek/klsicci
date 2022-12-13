<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

if (isset($_POST["eventAdjust"])) {
  $id = $_POST['id'];
  $userid = $_SESSION['userid'];
  $title = $_POST['title'];
  $start_event = $_POST['start'];
  $end_event = $_POST['end'];
  $sql = "UPDATE `events`  SET `title`='$title', `start_event`='$start_event', `end_event`='$end_event'  WHERE `id`='$id' AND `userid`='$userid' ";
  $result = mysqli_query(connectDb(), $sql);
}

if (isset($_POST["eventUpdate"])) {
  $id = $_POST['id'];
  $title = $_POST['eventTitle'];
  $remarks = $_POST['eventRemarks'];
  $userid = $_SESSION['userid'];
  $sql = "UPDATE `events`  SET `title`='$title', `remarks`='$remarks'  WHERE `id`='$id' AND `userid`='$userid' ";
  $result = mysqli_query(connectDb(), $sql);
  if (isset($_POST['followUpUpdate'])) {
    $stringCheck = "-Follow Up";
    
    if(strpos($title, $stringCheck) !== false){
    }else{
      $title = $title."-Follow Up";
    }
    
    $start_event = $_POST['followUpUpdate'];
    $end_event =date('Y-m-d h:i:sa',strtotime('+1 hour +20 minutes',strtotime($_POST['followUpUpdate'])));
    $sql = "INSERT INTO `events`  (`userid`,`title`, `start_event`, `end_event`, `remarks`)  VALUES ('$userid', '$title', '$start_event', '$end_event', '$remarks')";
    $result = mysqli_query(connectDb(), $sql);
  }
}

if (isset($_POST["eventReschedule"])) {
  $id = $_POST['id'];
  $userid = $_SESSION['userid'];
  $start_event = $_POST['rescheduleStart'];
  $end_event =date('Y-m-d h:i:sa',strtotime('+1 hour +20 minutes',strtotime($start_event)));
  $remarks = $_POST['rescheduleRemarks'];
  $userid = $_SESSION['userid'];
  $sql = "UPDATE `events`  SET  `start_event`='$start_event', `end_event`='$end_event',`remarks`='$remarks'  WHERE `id`='$id' AND `userid`='$userid' ";

  if (mysqli_query(connectDb(), $sql)) {
    $eventId = $id;
    $sqlHistory = "INSERT INTO `eventshistory`  (`eventId`, `userid`, `start_event`, `end_event`, `remarks`)  VALUES ('$eventId', '$userid', '$start_event', '$end_event', '$remarks')";
    $result = mysqli_query(connectDb(), $sqlHistory);
  }
}
/*
$connect = new PDO('mysql:host=localhost;dbname=jcloudmy_jobsheetDemo', 'jcloudmy_admin2', 'admin2jsoft');

if(isset($_POST["id"]))
{
 $query = "
 UPDATE events
 SET title=:title, start_event=:start_event, end_event=:end_event
 WHERE id=:id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id'   => $_POST['id']
  )
 );
}
 */
?>
