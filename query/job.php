<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

	function addJob($con,$jobRefNo,$jobName,$address,$cliendCompanyId,
	$vendorId,$vendorUserId,$picName,
	$picContactNo,$dateRequire,$remarks,
	$createdDate,$createdBy,$orgId){
		$success=false;
		$query="INSERT INTO job (
		refNo,
		jobName,
		jobLocation,
		clientCompanyId,
		vendorId,
		vendorUserId,
		picName,
		picContactNo,
		requireDate,
		remarks,
		createdDate,
		createdBy,orgId
		)
		VALUES (
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?,
			?
		)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssiiisssssii',$jobRefNo,$jobName,$address,$cliendCompanyId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$remarks,$createdDate,$createdBy,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);

		return $success;
	}

	function addImmediateJob2($con,$jobRefNo,$jobName,$address,$cliendCompanyId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarks,$createdDate,$startTime,$endTime,$createdBy,$orgId){
		$jobId=0;
		$query="INSERT INTO job (refNo,jobName,clientCompanyId,vendorId,vendorUserId,status,remarks,createdDate,startTime,endTime,createdBy,orgId)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiiissssii',$jobRefNo,$jobName,$cliendCompanyId,$vendorId,$vendorUserId,$status,$remarks,$createdDate,$startTime,$endTime,$createdBy,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$jobId=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);

		return $jobId;
	}

	function addImmediateJob($con,$jobRefNo,$jobName,$address,$cliendCompanyId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarks,$createdDate,$createdBy,$orgId){
		$jobId=0;
		$query="INSERT INTO job (refNo,jobName,clientCompanyId,vendorId,vendorUserId,status,remarks,createdDate,createdBy,orgId)
		VALUES (?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiiissii',$jobRefNo,$jobName,$cliendCompanyId,$vendorId,$vendorUserId,$status,$remarks,$createdDate,$createdBy,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$jobId=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);

		return $jobId;
	}

	function createComplaintJob($con,$jobRefNo,$jobName,$address,$cliendCompanyId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarks,$createdDate,$startTime,$endTime,$createdBy,$orgId,$complaintId){
		$jobId=0;
		$query="INSERT INTO job (refNo,jobName,clientCompanyId,vendorId,vendorUserId,status,remarks,createdDate,startTime,endTime,createdBy,orgId,complaintId)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiiissssiii',$jobRefNo,$jobName,$cliendCompanyId,$vendorId,$vendorUserId,$status,$remarks,$createdDate,$startTime,$endTime,$createdBy,$orgId,$complaintId);
		if(mysqli_stmt_execute($stmt)){
			$jobId=mysqli_insert_id($con);
		}
		mysqli_stmt_close($stmt);

		return $jobId;
	}


        function updateComplaintImage($con,$file,$complaintid,$taken){
        $jobId=0;
        $success=false;
        $query="INSERT INTO clientupdateimage (path,complaintid,taken) VALUES (?,?,?)";
        //"INSERT INTO clientupdateimage (path,complaintid) VALUES (?,?)";
        $stmt=mysqli_prepare($con,$query);
        mysqli_stmt_bind_param($stmt,'sii',$file,$complaintid,$taken);
        if(mysqli_stmt_execute($stmt)){
            $jobId=mysqli_insert_id($con);
        }
            if($jobId>0){
                $success=true;
            }
            return $success;
    }

    function checkModule($con,$role){
		$dataList = false;
		$query="SELECT * FROM `rolemodules` WHERE roleId=? AND moduleId=53";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$role);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList=true;
		}
		//	return json_encode($dataList);
		return $dataList;

    }
	function fetchComplaintImage($con,$cid){
		$dataList = array();
		$query="SELECT * FROM clientupdateimage WHERE complaintid=? ORDER by taken";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$cid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return $dataList;
	}

	function updateJobStatus($con,$jobId,$status){
		$success=false;
		$query="UPDATE job SET status=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$status,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobLatLon($con,$jobId,$latlon){
		$success=false;
		$query="UPDATE job SET latlon=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$latlon,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobName($con,$jobId,$jobName){
		$success=false;
		$query="UPDATE job SET jobName=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$jobName,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function fetchIncompleteJobList($con,$vendorUserId,$status){
		$dataList=array();
		$query="SELECT * FROM job WHERE vendorUserId=? AND status >?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorUserId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchAllJobListAdmin($con){
		$dataList=array();
		$query="SELECT * FROM job ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchJobListByVendor($con,$vendorId,$status){
		$dataList=array();
		$query="SELECT * FROM job WHERE vendorId=? AND status >=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$vendorId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}


	function fetchOrgStaffJobList($con,$createdBy,$cliendCompanyId,$dateFrom,$dateTo,$status,$orgId){
		$dataList=array();
		$query="";
		$paramType="";
		$paramList = array();
		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
			$query="SELECT job.*,telecom_service.docDate,telecom_service.cpa,telecom_service.category,telecom_service.state,telecom_service.region,telecom_service.vsattechnology, telecom_service.sitename,telecom_service.note,telecom_service.remarks AS remarkslog,telecom_service.resolution,telecom_service.docrecivedate,telecom_service.docattenddate,telecom_service.docclosedate,telecom_service.sla,telecom_service.troubleshoot,telecom_service.status AS casestatus FROM job JOIN telecom_service ON  1=1 AND telecom_service.cid=job.complaintId AND vendorId='0' ";
 
            if ($createdBy!=null) {
                $query.="AND job.createdBy=? ";
                $paramList[]=$createdBy;
            }

            if ($cliendCompanyId!=null) {
                $query.="AND job.clientCompanyId=? ";
                $paramList[]=$cliendCompanyId;
            }

            if ($status!=null) {
                $query.="AND job.status=? ";
                $paramList[] = $status;
            }
            if ($dateFrom!=null && $dateTo!=null) {
                $query.="AND job.createdDate between ? AND ? ";
                $paramList[] = $dateFrom;
                $paramList[] = $dateTo;
            }
            if ($orgId!=null) {
                $query.="AND job.orgId=? ";
                $paramList[] = $orgId;
            }
		}
		else{
            $query="SELECT * FROM job WHERE 1=1 AND vendorId='0' ";
 
            if ($createdBy!=null) {
                $query.="AND createdBy=? ";
                $paramList[]=$createdBy;
            }

            if ($cliendCompanyId!=null) {
                $query.="AND clientCompanyId=? ";
                $paramList[]=$cliendCompanyId;
            }

            if ($status!=null) {
                $query.="AND status=? ";
                $paramList[] = $status;
            }
            if ($dateFrom!=null && $dateTo!=null) {
                $query.="AND createdDate between ? AND ? ";
                $paramList[] = $dateFrom;
                $paramList[] = $dateTo;
            }
            if ($orgId!=null) {
                $query.="AND orgId=? ";
                $paramList[] = $orgId;
            }
        }

		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);
		//mysqli_stmt_bind_param($stmt,'iiss',$paramList);


		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchJobByComplaintId($con,$complaintId){
		$query="SELECT * FROM job WHERE complaintId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$complaintId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function fetchJobList($con,$vendorId,$cliendCompanyId,$dateFrom,$dateTo,$status,$orgId){
		$dataList=array();
		$query="SELECT * FROM job WHERE 1=1 AND vendorId>0 ";
		$paramType="";
		$paramList = array();


		if($vendorId!=null){
			$query.="AND vendorId=? ";
			$paramList[] = $vendorId;
		}
		if($cliendCompanyId!=null){
			$query.="AND clientCompanyId=? ";
			$paramList[]=$cliendCompanyId;
		}

		if($status!=null){
			$query.="AND status=? ";
			$paramList[] = $status;

		}
		if($dateFrom!=null && $dateTo!=null){
			$query.="AND createdDate between ? AND ? ";
			$paramList[] = $dateFrom;
			$paramList[] = $dateTo;
		}
		if($orgId!=null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}

		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);
		//mysqli_stmt_bind_param($stmt,'iiss',$paramList);


		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchJobDetails($con,$jobId){
		$query="SELECT * FROM job WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$jobId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);

		return $row;
	}

	function fetchJobDetailsByComplaintId($con,$complaintId){
		$query="SELECT * FROM job WHERE complaintId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$complaintId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);

		return $row;
	}

	function updateJobSignaturePath($con,$jobTransactionId,$signaturePath){
		$success=false;
		$query="UPDATE job SET signaturePath=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$signaturePath,$jobTransactionId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}



	function updateJobWorkingPerod($con,$jobId,$startTime,$endTime){
		$success=false;
		$query="UPDATE job SET startTime=?,endTime=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssi',$startTime,$endTime,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
	function fetchAssignedTask($con,$jobRefNo,$clientCompanyId,$status,$createdBy,$dateFrom,$dateTo,$assignType,$workerId,$orgId,$complaintId,$jobId,$messageStatus)
	{
		$comliantList=array();
		$query="";
        if (isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) {
            $query="SELECT j.*, jt.* ,j.id as idJ, jt.id as idJt , jt.status as statusJt , jt.createdDate as createdDateJt FROM job j INNER JOIN jobtransaction jt INNER JOIN telecom_service s WHERE
		jt.jobId=j.id AND j.complaintId>0 AND j.status IN ( 2 , 3 ) AND s.cid=j.complaintId "  ;
		}
		else{
			$query="SELECT *,j.id as idJ, jt.id as idJt , jt.status as statusJt , jt.createdDate as createdDateJt FROM job j INNER JOIN jobtransaction jt WHERE
			jt.jobId=j.id AND j.complaintId>0 " ;
		}

		$paramType="";
		$paramList = array();

		if($jobRefNo!=null){
			$query.="AND j.refNo=? ";
			$paramList[] = $jobRefNo;
		}

		if($clientCompanyId!=null){
			$query.="AND j.clientCompanyId=? ";
			$paramList[]=$clientCompanyId;
		}

		if($status>=0 && $status!==null){
			if($status==="UNRESOLVED"){
				$query.="AND jt.status>0 ";
			}else{
				$query.="AND jt.status=? ";
				$paramList[] = $status;
			}

		}

		if($createdBy!=null){
			$query.="AND j.createdBy=? ";
			$paramList[] = $createdBy;

		}

		if($dateFrom!=null && $dateTo!=null){
			$query.="AND jt.createdDate between ? AND ? ";
			$paramList[] = $dateFrom;
			$paramList[] = $dateTo;
		}

		if($assignType!=null && $workerId!=null){
			$query.="AND jt.assignType=? ";
			$paramList[] = $assignType;
			$query.="AND jt.workerId=? ";
			$paramList[] = $workerId;
		}

		if($orgId!=null){
			$query.="AND j.orgId=? ";
			$paramList[] = $orgId;
		}

		if($complaintId!=null){
			$query.="AND j.complaintId=? ";
			$paramList[] = $complaintId;
		}

		if($jobId!=null){
			$query.="AND j.id=? ";
			$paramList[] = $jobId;
		}

		if($messageStatus!=null){
			$query.="AND jt.messageStatus=? ";
			$paramList[] = $messageStatus;
		}

		$query.="ORDER BY j.createdDate DESC ";
		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);
		//mysqli_stmt_bind_param($stmt,'iiss',$paramList);

		if(mysqli_stmt_execute($stmt)){
			$result = mysqli_stmt_get_result($stmt);
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}

		while($row=$result->fetch_assoc()){
			$comliantList[]=$row;
		}
		mysqli_stmt_close($stmt);
		return $comliantList;
	}

/*
	function DynamicBindVariables($stmt, $params)
	{
		if ($params != null)
		{
			$types = '';
			foreach($params as $param)
			{
				if(is_int($param)) {
					$types .= 'i';
				} elseif (is_float($param)) {
					$types .= 'd';
				} elseif (is_string($param)) {
					$types .= 's';
				} else {
					$types .= 'b';
				}
			}

			$bind_names[] = $types;

			for ($i=0; $i<count($params);$i++)
			{
				$bind_name = 'bind' . $i;
				$$bind_name = $params[$i];
				$bind_names[] = &$$bind_name;
			}
			call_user_func_array(array($stmt,'bind_param'), $bind_names);
		}
		return $stmt;
	}
	*/

	function staffCompletedJobArray($con,$workerId){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=?";
		$stmt=mysqli_prepare($con,$query);
		$status = 0;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'ii',$workerId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffCompletedJobArrayByDate($con,$workerId,$dateYear,$dateMonth){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=? AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		$status = 0;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'iiss',$workerId,$status,$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffNewJobArray($con,$workerId){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=?";
		$stmt=mysqli_prepare($con,$query);
		$status = 1;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'ii',$workerId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffPendingJobArray($con,$workerId){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=?";
		$stmt=mysqli_prepare($con,$query);
		$status = 2;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'ii',$workerId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffPendingJobArrayByDate($con,$workerId,$dateYear,$dateMonth){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=? AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		$status = 2;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'iiss',$workerId,$status,$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffInProgressJobArray($con,$workerId){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=?";
		$stmt=mysqli_prepare($con,$query);
		$status = 3;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'ii',$workerId,$status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}

	function staffInProgressJobArrayByDate($con,$workerId,$dateYear,$dateMonth){
		$query="SELECT * FROM jobtransaction WHERE workerId=? AND status=? AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		$status = 3;
		$dataList = array();
		mysqli_stmt_bind_param($stmt,'iiss',$workerId,$status,$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		//	return json_encode($dataList);
		return count($dataList);
	}


	function fetchStaffJobDetail($con,$id){
		$query="SELECT * FROM job WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		return json_encode($row);
	}
	function fetchTelecomComplaintDetail($con,$cid){
		$query="SELECT * FROM telecom_service WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$cid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		return json_encode($row);
	}
	function fetchDetailsComplaintExtra($con,$cid){
		$query="SELECT * FROM complaintextra WHERE comid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$cid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		return json_encode($row);
	}


	if (isset($_GET['downloadPDF'])) {
		$orgId = $_SESSION['orgId'];
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/jobReportPDF.php");
		$jobListDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/job/";
		if (!file_exists($jobListDirectory)){
			mkdir($jobListDirectory, 0777, true);
		}
		$jobListName = "Jobsheet Report";
		$createdDate = date("Y-m-d");
		$generateJobListPDF=generateJobListPDF(); // phpfunction/pdf/invoicePDF.php
		$generateJobListPDF->output("$jobListDirectory/$jobListName.pdf",'F'); // save pdf to server // html2pdf method
		$file = $jobListDirectory."/Jobsheet Report.pdf";

		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=" . basename($file));

		readfile ($file);
		exit();
	}

	function fetchSpareParts($con,$jobId,$workerId){
		$query="SELECT * FROM jobspareparts WHERE refNo=? AND workerId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$jobId,$workerId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList = NULL;
		if(mysqli_stmt_execute($stmt)){
			while($row=$result->fetch_assoc()){
					$dataList[]=$row;
			}
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function updateMeterReading($con, $jobId, $meter1, $meter2, $meter3, $meter4, $meterTotal){
		$success=false;
		$query="UPDATE job SET meter1=?,meter2=?,meter3=?,meter4=?,meterTotal=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssi',$meter1,$meter2,$meter3,$meter4,$meterTotal,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateActionTaken($con, $jobId, $action){
		$success=false;
		$query="UPDATE job SET action=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$action,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function insertSpareParts($con,$jobId,$workerId,$description,$qty){
	$feedback = false;
	$query="INSERT INTO jobspareparts (workerId,refNo,description,qty)
	VALUES (?,?,?,?)";
	$stmt=mysqli_prepare($con,$query);
	mysqli_stmt_bind_param($stmt,'iisi',$workerId,$jobId,$description,$qty);
	if(mysqli_stmt_execute($stmt)){
		$feedback = true;
	}
	mysqli_stmt_close($stmt);

	return $feedback;
	}

	function fetchSparepartbyrefNo($con,$refNo){
		$dataList=array();
		$query="SELECT * FROM jobspareparts WHERE refNo=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$refNo);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function deleteSparepartsById($con,$id){
		$success=false;

		$query="DELETE FROM jobspareparts WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);

		return $success;
	}

	function updateJobRemarks($con, $jobId, $remarks){
		$success=false;
		$query="UPDATE job SET remarks=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$remarks,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobZoneCharge($con, $jobId, $zone){
		$success=false;
		$query="UPDATE job SET zone=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$zone,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobServiceCharge($con, $jobId, $service){
		$success=false;
		$query="UPDATE job SET service=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$service,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateJobCsName($con, $jobId, $csName){
		$success=false;
		$query="UPDATE job SET cName=?  WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$csName,$jobId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function insertJobReassign($con, $jobId, $workerId, $timeDate){
		$feedback = false;
		$query="INSERT INTO jobreassign (jobId, workerId, timeDate)
		VALUES (?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'iis',$jobId, $workerId, $timeDate);
		if(mysqli_stmt_execute($stmt)){
			$feedback = true;
		}
		mysqli_stmt_close($stmt);

		return $feedback;
	}
	
	function fetchLastRow($con){
	    $query="SELECT * FROM job ORDER BY id DESC LIMIT 1";
	    //$query="SELECT * FROM job WHERE complaintId=?";
		$stmt=mysqli_prepare($con,$query);
		//mysqli_stmt_bind_param($stmt,'i',$complaintId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);

		return $row;
	}
?>
