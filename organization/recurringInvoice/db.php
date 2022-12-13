<?php
error_reporting(E_ALL ^ E_DEPRECATED);
?>
<?php   

$db_host = "localhost"; 
$db_username = "u163220262_devgk";
$db_pass = "IIdn10a|kS7";
$db_name = "u163220262_devgk";
 
$con = mysqli_connect("$db_host","$db_username","$db_pass") or die ("could not connect to mysql");
 mysqli_select_db($con,$db_name);            
?>