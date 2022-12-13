<?php
/**
 * Created by PhpStorm.
 * User: shafe
 * Date: 7/19/2021
 * Time: 2:23 PM
 */


	function getOrganizationDetails($con,$orgId){
		$query="SELECT * FROM organization WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;

	}

	function listOfficerEmails($con){
		$temp=1;
		$dataList=array();
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

	function getLateEmailSettingDetail($con){
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

	function getListAttendanceStaffList($con,$time1,$time2){
		$temp=1;
		//$dateTime=;
		$dataList=array();
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
	function getLateStaffEmailsId($con,$query){
		$role=42;
		$id=1;
		$dataList=array();
		//$query='SELECT `name`,`email` FROM jcloudmy_saha.organizationuser where `role` NOT IN(42) and id NOT IN(?)';
		//$query=$query. " AND 1=?";
		if($stmt=mysqli_prepare($con,$query)) {
		//	mysqli_stmt_bind_param($stmt, 'i',$id);
			mysqli_stmt_execute($stmt);
			if ($result = mysqli_stmt_get_result($stmt)) {
				while ($row = $result->fetch_assoc()) {
					$dataList[] = $row;
				}
			}
			mysqli_stmt_close($stmt);
		}
		return $dataList;
	}
