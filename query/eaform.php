<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");


	function addEAForm($con,
	                   $staffName,
	                   $position,
	                   $staffNum,
	                   $icNum,
	                   $passNum,
	                   $perkesoNum,
	                   $kwspNum,
	                   $childrenNum,
	                   $startDate,
	                   $endDate,
	                   $grossSalary,
	                   $fiBonus,
	                   $payFor,
	                   $payAmount,
	                   $taxByEmployer,
	                   $esos,
	                   $rewardFrom,
	                   $rewardTo,
	                   $previousYear,
	                   $currentYear,
	                   $benefitsState,
	                   $benefitsAmount,
	                   $resiAdd,
	                   $resiValue,
	                   $refundKWSP,
	                   $reparationJob,
	                   $pensionAmount,
	                   $annuitAmount,
	                   $totalPension,
	                   $pcbAmount,
	                   $deductionCP,
	                   $zakatAmount,
	                   $deductionTP1a,
	                   $deductionTP1b,
	                   $jumlahPele,
	                   $fundName,
	                   $contribution,
	                   $perkesoPaidAmount,
	                   $totalAllowances,
	                   $officerName,
	                   $officerPosition,
	                   $employerNameAdd,
	                   $employerNum,
	                   $year,
	                   $date,
	                   $status){
		$success=false;
		//debug
		$query="INSERT INTO eaform (staffName,`position`,staffNum,icNum,passNum,perkesoNum,kwspNum,childrenNum,startDate,endDate,grossSalary,fiBonus,payFor,payAmount,taxByEmployer,esos,rewardFrom,rewardTo,previousYear,currentYear,benefitsState,benefitsAmount,resiAdd,resiValue,refundKWSP,reparationJob,pensionAmount,annuitAmount,totalPension,pcbAmount,deductionCP,zakatAmount,deductionTP1a,deductionTP1b,jumlahPele,fundName,contribution,perkesoPaidAmount,totalAllowances,officerName,officerPosition,employerNameAdd,employerNum,`year`,`date`,`status` )
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssssissddsdddsssssdsddddddddddddsdddssssisi',

			$staffName,
			$position,
			$staffNum,
			$icNum,
			$passNum,
			$perkesoNum,
			$kwspNum,
			$childrenNum,
			$startDate,
			$endDate,
			$grossSalary,
			$fiBonus,
			$payFor,
			$payAmount,
			$taxByEmployer,
			$esos,
			$rewardFrom,
			$rewardTo,
			$previousYear,
			$currentYear,
			$benefitsState,
			$benefitsAmount,
			$resiAdd,
			$resiValue,
			$refundKWSP,
			$reparationJob,
			$pensionAmount,
			$annuitAmount,
			$totalPension,
			$pcbAmount,
			$deductionCP,
			$zakatAmount,
			$deductionTP1a,
			$deductionTP1b,
			$jumlahPele,
			$fundName,
			$contribution,
			$perkesoPaidAmount,
			$totalAllowances,
			$officerName,
			$officerPosition,
			$employerNameAdd,
			$employerNum,
			$year,
			$date,
			$status);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
            mysqli_stmt_close($stmt);
		}
		return $success;
	}

	function updateEAForm($con,
	                   $staffName,
	                   $position,
	                   $staffNum,
	                   $icNum,
	                   $passNum,
	                   $perkesoNum,
	                   $kwspNum,
	                   $childrenNum,
	                   $startDate,
	                   $endDate,
	                   $grossSalary,
	                   $fiBonus,
	                   $payFor,
	                   $payAmount,
	                   $taxByEmployer,
	                   $esos,
	                   $rewardFrom,
	                   $rewardTo,
	                   $previousYear,
	                   $currentYear,
	                   $benefitsState,
	                   $benefitsAmount,
	                   $resiAdd,
	                   $resiValue,
	                   $refundKWSP,
	                   $reparationJob,
	                   $pensionAmount,
	                   $annuitAmount,
	                   $totalPension,
	                   $pcbAmount,
	                   $deductionCP,
	                   $zakatAmount,
	                   $deductionTP1a,
	                   $deductionTP1b,
	                   $jumlahPele,
	                   $fundName,
	                   $contribution,
	                   $perkesoPaidAmount,
	                   $totalAllowances,
	                   $officerName,
	                   $officerPosition,
	                   $employerNameAdd,
	                   $employerNum,
	                   $year,
	                   $date,
	                   $status,
					   $id){
		$success=false;
		//debug
		$query="UPDATE eaform SET staffName=?,`position`=?,staffNum=?,icNum=?,passNum=?,perkesoNum=?,kwspNum=?,childrenNum=?,startDate=?,endDate=?,grossSalary=?,fiBonus=?,payFor=?,payAmount=?,taxByEmployer=?,esos=?,rewardFrom=?,rewardTo=?,previousYear=?,currentYear=?,benefitsState=?,benefitsAmount=?,resiAdd=?,resiValue=?,refundKWSP=?,reparationJob=?,pensionAmount=?,annuitAmount=?,totalPension=?,pcbAmount=?,deductionCP=?,zakatAmount=?,deductionTP1a=?,deductionTP1b=?,jumlahPele=?,fundName=?,contribution=?,perkesoPaidAmount=?,totalAllowances=?,officerName=?,officerPosition=?,employerNameAdd=?,employerNum=?,`year`=?,`date`=?,`status`=? WHERE id=?";
		//$query="INSERT INTO eaform (staffName,`position`,staffNum,icNum,perkesoNum,kwspNum,childrenNum,startDate,endDate,grossSalary,fiBonus,payFor,payAmount,taxByEmployer,esos,rewardFrom,rewardTo,previousYear,currentYear,benefitsState,benefitsAmount,resiAdd,resiValue,refundKWSP,reparationJob,pensionAmount,annuitAmount,totalPension,pcbAmount,deductionCP,zakatAmount,deductionTP1a,deductionTP1b,jumlahPele,fundName,contribution,perkesoPaidAmount,totalAllowances,officerName,officerPosition,employerNameAdd,employerNum,`year`,`date`,`status` )
		//VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssssissddsdddsssssdsddddddddddddsdddssssisi',
			$staffName,
			$position,
			$staffNum,
			$icNum,
			$passNum,
			$perkesoNum,
			$kwspNum,
			$childrenNum,
			$startDate,
			$endDate,
			$grossSalary,
			$fiBonus,
			$payFor,
			$payAmount,
			$taxByEmployer,
			$esos,
			$rewardFrom,
			$rewardTo,
			$previousYear,
			$currentYear,
			$benefitsState,
			$benefitsAmount,
			$resiAdd,
			$resiValue,
			$refundKWSP,
			$reparationJob,
			$pensionAmount,
			$annuitAmount,
			$totalPension,
			$pcbAmount,
			$deductionCP,
			$zakatAmount,
			$deductionTP1a,
			$deductionTP1b,
			$jumlahPele,
			$fundName,
			$contribution,
			$perkesoPaidAmount,
			$totalAllowances,
			$officerName,
			$officerPosition,
			$employerNameAdd,
			$employerNum,
			$year,
			$date,
			$status,
			$id);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
			mysqli_stmt_close($stmt);
		}
		return $success;
	}


	function fetchEAFormAll($con){
		$dataList=array();
		$query="";
		if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole'] ){
			$query="SELECT * FROM eaform WHERE 1 ORDER BY id DESC";
		}
		else{
			$query="SELECT * FROM eaform WHERE 1 AND staffNum='".$_SESSION['staffId']."' ORDER BY id DESC";
		}
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function getEAFormAllSubmitted($con,$staffNum,$year){
		$dataList=array();
		$query="";
		$staffNumber="";
		$qyear="";
		if(isset($staffNum)&&$staffNum!=NULL){
			$staffNumber='AND staffNum="'.$staffNum.'" ';
		}
		if(isset($year)&&$year!=NULL){
			$qyear='AND year="'.$year.'" ';
		}

		if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole'] ){
			$query="SELECT * FROM eaform WHERE 1 ".$staffNumber." ".$qyear." AND status=0 ORDER BY id DESC";
		}
		else{
			$query="SELECT * FROM eaform WHERE 1 ".$staffNumber." ".$qyear."AND status=0 ORDER BY id DESC";
		}
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}
	function fetchEAForm($con,$id){
		$dataList=array();
		$query="SELECT * FROM eaform WHERE id=".$id."";

		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchEAStaffList($con){
	$dataList=array();
	$query="";
	if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole'] ){
		$query="SELECT staffId FROM eaform WHERE 1 ORDER BY id DESC";
	}
	else{
		$query="SELECT * FROM eaform WHERE 1 AND staffNum='".$_SESSION['staffId']."' ORDER BY id DESC";
	}
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	while($row=$result->fetch_assoc()){
		$dataList[]=$row;

	}
	mysqli_stmt_close($stmt);

	return $dataList;

	}


	function addOfficerDetails($con,$name,$position,$employerAdd,$employerTel){
		$success=false;
		//debug
		$query="INSERT INTO eaofficer (`name`,`position`,`employerAdd`,`emloyerTel`)
		VALUES (?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssss',$name,$position,$employerAdd,$employerTel);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
			mysqli_stmt_close($stmt);
		}
		return $success;
	}
	function getOfficerList($con){
		$dataList=array();
		$query="SELECT * FROM eaofficer WHERE 1=1 ";

		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function deleteOfficerDetails($con,$Id){
		$success=false;

		$query="DELETE FROM eaofficer WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$Id);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
			mysqli_stmt_close($stmt);
		}
		return $success;
	}


	function fetchOfficerDetailsList($con){
		$dataList=array();
		$num=0;
		$query="SELECT * FROM `eaofficer` WHERE 1";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		return $dataList;
	}

	function fetchEaOfficerbyId($con,$id){
		$query="SELECT * FROM eaofficer
      WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);

		return $row;
	}
function checkEAForm($con,$staffName,$staffNum,$year){
		$status=true;
	$query="SELECT staffName FROM eaform
      WHERE staffName=? and staffNum=? AND year=?";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'ssi',$staffName,$staffNum,$year);
	mysqli_stmt_execute($stmt);
	if($result = mysqli_stmt_get_result($stmt)) {
		//;
		//$row=$result->fetch_assoc();
		while ($row = $result->fetch_assoc()) {
		//	$dataList[] = $row;
			$status=false;
		}
		mysqli_stmt_close($stmt);
	}
	return $status;
}

?>
