<?php
	function insertOfficerEmail($con,$email){
	$feedback=false;
	$query="INSERT INTO attendceofficer (officerEmail)
		VALUES (?)";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'s',$email);
	if(mysqli_stmt_execute($stmt)){
		$feedback=true;
	}else{
		die('execute() failed: ' . htmlspecialchars($stmt->error));
	}
	mysqli_stmt_close($stmt);

	return $feedback;}

	function insertLateEmailSetting($con,$startTime,$endTime,$workingStart,$workingEnd,$to,$emailTime)
	{
		$id=1;
	$feedback=false;
	$query="UPDATE attendancesetting SET startTime=?,endTime=?,workingStart=?,workingEnd=?,emailOfficer=?,emailTime=? WHERE id=?";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'ssssssi',$startTime,$endTime,$workingStart,$workingEnd,$to,$emailTime,$id);
	if(mysqli_stmt_execute($stmt)){
		$feedback=true;
	}
	mysqli_stmt_close($stmt);

	return $feedback;}



	function listOfficerEmails($con){
		$temp=1;
		$query="SELECT * FROM attendceofficer WHERE 1=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$temp);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
	$dataList[]=array();
	while($row=$result->fetch_assoc()){
		$dataList[]=$row;
	}

		mysqli_stmt_close($stmt);

		return $dataList;

	}

	function getLateEmailSettingDetials($con){
		$id=1;
		$query="SELECT * FROM attendancesetting WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList=array();
		while($row=$result->fetch_assoc()){
			$dataList=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;

	}

	function getListAttendanceStaffId($con,$time1,$time2){
		$temp=1;
		//$dateTime=;

		$query="SELECT staffId FROM attendance where DATE(?) >= DATE(checkinTime) AND DATE(?) <= DATE(checkinTime) order by staffId;";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$time1,$time2);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList[]=array();
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}
function getLateStaffEmailId($con,$query){
	$role=42;
	//$query='SELECT `name`,`email` FROM jcloudmy_saha.organizationuser where `role` NOT IN(42) and id NOT IN(?)';
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'i',$role);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$dataList[]=array();
	while($row=$result->fetch_assoc()){
		$dataList[]=$row;
	}
	mysqli_stmt_close($stmt);

	return $dataList;
}
	function getEmailTime($con)
	{

		$id = 1;
		$query = "SELECT emailTime FROM attendancesetting WHERE id=?";
		$stmt = mysqli_prepare($con, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList = "";
		while ($row = $result->fetch_assoc()) {
			$dataList = $row['emailTime'];
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

?>