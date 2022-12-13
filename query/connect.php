<?php

	function connectDb(){
    $addr="localhost";
		$db="admin_devgk";
		$user="admin_devgk";
		$password='802%0svsE';
		$con = mysqli_connect($addr,$user,$password,$db);

		if (mysqli_connect_errno())
		{
			die("Failed to connect to MySQL: " . mysqli_connect_error());
		}
		return $con;
	}

	function pdoConnect(){
		$addr="localhost";
		$db="admin_devgk";
		$user="admin_devgk";
		$password='802%0svsE';
		//$pdo = "'mysql:host=".$addr.";dbname=".$db."', '".$user."', '".$password."'";
		$con = array();
		$con[0] = "mysql:host=".$addr.";dbname=".$db;
		$con[1] = $user;
		$con[2] = $password;
		return $con;
	}
?>