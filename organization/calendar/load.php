<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

//The connect needs to be manually change its server variables
//$connect = new PDO('mysql:host=localhost:3307;dbname=easy', 'root', '');
$connect = new PDO(pdoConnect()[0],pdoConnect()[1],pdoConnect()[2]);
$data = array();
$userid = $_SESSION['userid'];
$query = "SELECT * FROM events WHERE userid = $userid ORDER BY id";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'title'   => $row["title"],
  'start'   => $row["start_event"],
  'end'   => $row["end_event"],
 );
}

echo json_encode($data);

?>
