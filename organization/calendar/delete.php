<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");

if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

if (isset($_POST["id"])) {
  $id = $_POST['id'];
  $userid = $_SESSION['userid'];
  $sql = "DELETE from `events` WHERE `id`='$id' AND `userid`='$userid'";
  $result = mysqli_query(connectDb(), $sql);
}

/*
if(isset($_POST["id"]))
{
 $connect = new PDO('mysql:host=localhost;dbname=jcloudmy_jobsheetDemo', 'jcloudmy_admin2', 'admin2jsoft');
 $query = "
 DELETE from events WHERE id=:id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':id' => $_POST['id'],
  )
 );
}
*/
?>
