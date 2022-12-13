<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
function insertAttendance($con,$staffId,$fileName,$latitude,$longitude,$cityName){
  $feedback = false;
    $checkInTime=date("Y-m-d H:i:s");
  $query="INSERT INTO attendance (staffId,fileName,latitude,longitude,cityName,checkinTime) VALUES (?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isssss',$staffId,$fileName,$latitude,$longitude,$cityName,$checkInTime);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}

function fetchAttendanceListAll($con,$userid,$role){
  $dataList=array();
	$query ="";
	$a=1;
	$stmt="";
if($role==42 || $role==3 || $role==1) {
	$query = "SELECT * FROM attendance WHERE 1=? ORDER BY checkinTime DESC";
	}else{
	$query = "SELECT * FROM attendance WHERE 1=? AND staffId=".$userid . " ORDER BY checkinTime DESC";
	}
  $stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'i',$a);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
	  while ($row = $result->fetch_assoc()) {
		  $dataList[] = $row;

	  }
	  mysqli_stmt_close($stmt);
  }
  return $dataList;
}

function fetchAttendanceByStaffIdAndDate($con,$staffId,$date){
  $dataList=array();
  $date1 = $date.' 00:00:00';
  $date2 = $date.' 23:59:59';
  $query="SELECT * FROM attendance WHERE staffId=? AND checkinTime BETWEEN ? AND ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iss',$staffId,$date1,$date2);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function updateAttendanceByStaffIdAndDate($con,$fileName,$latitudeOut,$longitudeOut,$cityName,$staffId,$date){
  $success=false;

  $date1 = $date.' 00:00:00';
  $date2 = $date.' 23:59:59';
  $checkoutTime = date("Y-m-d H:i:s");
  $query="UPDATE attendance SET `fileNameOut`=?,`checkoutTime`=?,`cityNameOut`=?,`latitudeOut`=?,`longitudeOut`=?,`coCheck`=1 WHERE `staffId`=? AND `checkinTime` BETWEEN ? AND ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssssiss',$fileName,$checkoutTime,$cityName,$latitudeOut,$longitudeOut,$staffId,$date1,$date2);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function fetchAttendanceRowByStaffIdAndDate($con,$staffId,$date){
  $dataList=array();
  $date1 = $date.' 00:00:00';
  $date2 = $date.' 23:59:59';
  $query="SELECT * FROM attendance WHERE staffId=? AND checkinTime BETWEEN ? AND ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iss',$staffId,$date1,$date2);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchAttendanceRowById($con,$id){
  $query="SELECT * FROM attendance WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}
?>