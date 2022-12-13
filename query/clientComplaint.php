<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");


	function createComplaint($con,$problemName,$problemDetails,$occuredDate,$picName,$picContactNo,$requireDate,$createdDate,$createdBy,$status,$companyId,$timeFrame,$invoiceNo,$orgId,$comType,$uploadFileName){
		$saveSuccess=false;
		$query="INSERT INTO clientcomplaint (issueName,issueDetail,occuredDate,picName,picContactNo,requireDate,createdDate,createdBy,status,companyId,timeFrame,invoiceNo,orgId,`type`,fileAttach)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssssiiisiiis',$problemName,$problemDetails,$occuredDate,$picName,$picContactNo,$requireDate,$createdDate,$createdBy,$status,$companyId,$timeFrame,$invoiceNo,$orgId,$comType,$uploadFileName);
		if(mysqli_stmt_execute($stmt)){
			$saveSuccess=true;
			$_SESSION['complaintId']=mysqli_insert_id($con);
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);
		return $saveSuccess;
	}

    function createComplaintTelecome($con, $docDate, $cpa, $category, $state, $region, $vsattechnology, $sitename, $note, $cid, $remarks, $resolution, $docrecivedate, $docattenddate, $docclosedate, $sla, $troubleshoot, $status)
    {
        $saveSuccess=false;
        $query="INSERT INTO telecom_service (docDate,cpa,category,state,region,vsattechnology,sitename,note,cid,remarks,resolution,docrecivedate,docattenddate,docclosedate,sla,troubleshoot,status)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt=mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'sissssssissssssss', $docDate, $cpa, $category, $state, $region, $vsattechnology, $sitename, $note, $cid, $remarks, $resolution, $docrecivedate, $docattenddate, $docclosedate, $sla, $troubleshoot, $status);
        if (mysqli_stmt_execute($stmt)) {
            $saveSuccess=true;
            $_SESSION['telecid']=mysqli_insert_id($con);
        } else {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
		mysqli_stmt_close($stmt);
		return $saveSuccess;
    }
		
	
	
	function deleteClientComplaint($con,$complaintId,$messageStatus,$status,$companyId,$orgId){
		$success=false;


		$query="DELETE FROM clientcomplaint WHERE 1=1 ";
		$paramList = array();

		if($complaintId!==null){
			$query.="AND id=? ";
			$paramList[] = $complaintId;
		}

		if($complaintId!==null){
			$query.="AND messageStatus=? ";
			$paramList[] = $messageStatus;
		}

		if($complaintId!==null){
			$query.="AND status=? ";
			$paramList[] = $status;
		}

		if($companyId!==null){
			$query.="AND companyId=? ";
			$paramList[] = $companyId;

		}

		if($orgId!==null){
			$query.="AND orgId=? ";
			$paramList[] = $orgId;

		}

		$stmt=mysqli_prepare($con,$query);

		DynamicBindVariables($stmt, $paramList);

		//mysqli_stmt_bind_param($stmt,'iii',$staffId,$companyId,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);

		return $success;
	}


	function fetchComplainList($con,$picName,$occuredDate,$createdDate,$createdBy,$status,$companyId,$orgId){
		$dataList=array();
		$query="";
		$paramType="";
		$paramList = array();
		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
			$query="SELECT clientcomplaint.* FROM clientcomplaint JOIN telecom_service ON  1=1 AND telecom_service.cid=clientcomplaint.id ";
		
		if($picName!=null){
			$query.="AND clientcomplaint.picName=? ";
			$paramList[]=$picName;
		}

		if($occuredDate!=null){
			$query.="AND clientcomplaint.occuredDate=? ";
			$paramList[]=$occuredDate;
		}

		if($createdDate!=null){
			$query.="AND clientcomplaint.createdDate=? ";
			$paramList[]=$createdDate;
		}

		if($createdBy!=null){
			$query.="AND clientcomplaint.createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($companyId!=null){
			$query.="AND clientcomplaint.companyId=? ";
			$paramList[]=$companyId;
		}

		if($orgId!=null){
			$query.="AND clientcomplaint.orgId=? ";
			$paramList[]=$orgId;
		}
		$query.="AND clientcomplaint.status NOT IN (1, 2, 3, 4) ORDER BY clientcomplaint.createdDate DESC, clientcomplaint.messageStatus ASC ";
	}else{
		$query="SELECT * FROM clientcomplaint WHERE 1=1 ";
		
		if($picName!=null){
			$query.="AND picName=? ";
			$paramList[]=$picName;
		}

		if($occuredDate!=null){
			$query.="AND occuredDate=? ";
			$paramList[]=$occuredDate;
		}

		if($createdDate!=null){
			$query.="AND createdDate=? ";
			$paramList[]=$createdDate;
		}

		if($createdBy!=null){
			$query.="AND createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($companyId!=null){
			$query.="AND companyId=? ";
			$paramList[]=$companyId;
		}

		if($orgId!=null){
			$query.="AND orgId=? ";
			$paramList[]=$orgId;
		}
		$query.="AND status=0 ORDER BY createdDate DESC, messageStatus ASC ";
	}

		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

	function fetchUnresolvedComplainList($con,$picName,$occuredDate,$createdDate,$createdBy,$companyId,$orgId){
		$dataList=array();
		$query="";
		$paramType="";
		$paramList = array();
		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
			$query="SELECT clientcomplaint.* FROM clientcomplaint JOIN telecom_service ON  1=1 AND telecom_service.cid=clientcomplaint.id ";
		
		if($picName!=null){
			$query.="AND clientcomplaint.picName=? ";
			$paramList[]=$picName;
		}

		if($occuredDate!=null){
			$query.="AND clientcomplaint.occuredDate=? ";
			$paramList[]=$occuredDate;
		}

		if($createdDate!=null){
			$query.="AND clientcomplaint.createdDate=? ";
			$paramList[]=$createdDate;
		}

		if($createdBy!=null){
			$query.="AND clientcomplaint.createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($companyId!=null){
			$query.="AND clientcomplaint.companyId=? ";
			$paramList[]=$companyId;
		}

		if($orgId!=null){
			$query.="AND clientcomplaint.orgId=? ";
			$paramList[]=$orgId;
		}
		$query.="AND clientcomplaint.status NOT IN ( 0 , 5 , 6 ,7) ORDER BY clientcomplaint.createdDate DESC, clientcomplaint.messageStatus ASC ";
	}else{
		$query="SELECT * FROM clientcomplaint WHERE 1=1 ";
		
		if($picName!=null){
			$query.="AND picName=? ";
			$paramList[]=$picName;
		}

		if($occuredDate!=null){
			$query.="AND occuredDate=? ";
			$paramList[]=$occuredDate;
		}

		if($createdDate!=null){
			$query.="AND createdDate=? ";
			$paramList[]=$createdDate;
		}

		if($createdBy!=null){
			$query.="AND createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($companyId!=null){
			$query.="AND companyId=? ";
			$paramList[]=$companyId;
		}

		if($orgId!=null){
			$query.="AND orgId=? ";
			$paramList[]=$orgId;
		}
		$query.="AND status>0 ORDER BY createdDate DESC, messageStatus ASC ";
	}
		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);
		//debug un list 
		return $dataList;
	}

	function fetchComplaintListByMessageStatus($con,$orgId,$messageStatus){
		$dataList=array();
		$query="SELECT * FROM clientcomplaint WHERE messageStatus=? AND orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$messageStatus,$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;

	}

	function fetchComplainDetails($con,$complaintId){
		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
			$query="SELECT clientcomplaint.*,telecom_service.docDate,telecom_service.cpa,telecom_service.category,telecom_service.state,telecom_service.region,telecom_service.vsattechnology, telecom_service.sitename,telecom_service.note,telecom_service.remarks AS remarkslog,telecom_service.resolution,telecom_service.docrecivedate,telecom_service.docattenddate,telecom_service.docclosedate,telecom_service.sla,telecom_service.troubleshoot,telecom_service.status AS ComplaintStatus, clientcomplaint.fileAttach FROM clientcomplaint JOIN telecom_service ON telecom_service.cid=clientcomplaint.id AND clientcomplaint.id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt, 'i', $complaintId);
		}
			else{
		$query="SELECT * FROM clientcomplaint WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt, 'i', $complaintId);}
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
		return $row;
	}

	function fetchComplaintUncompleteForExcel($con){
		$status=0;
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=data-incomplete-'.date('Ymd').'.csv');
		$output = fopen("php://output", "w"); 
				if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
 
     fputcsv($output, array('Ticket No', 'Issue Name', 'Issue Detail', 'Docket Created Date', 'CPA', 'Category', 'State','VSat Technology','sitename', 'Note', 'Remarks', 'Resolution', 'Docket Recive Date', 'Docket Attend Date', 'Docket Close Date', 'SLA', 'Troubleshooting', 'Status' ));  
			$query="SELECT clientcomplaint.id,clientcomplaint.issueName,clientcomplaint.issueDetail,telecom_service.docDate,telecom_service.cpa,telecom_service.category,telecom_service.state,telecom_service.vsattechnology, telecom_service.sitename,telecom_service.note,telecom_service.remarks,telecom_service.resolution,telecom_service.docrecivedate,telecom_service.docattenddate,telecom_service.docclosedate,telecom_service.sla,telecom_service.troubleshoot,telecom_service.status FROM clientcomplaint JOIN telecom_service ON telecom_service.cid=clientcomplaint.id AND clientcomplaint.status>? AND clientcomplaint.status<5 AND telecom_service.docDate BETWEEN DATE(NOW()) - INTERVAL 7 DAY AND DATE(NOW()) ORDER BY clientcomplaint.id DESC";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt, 'i', $status);
		}
			else{
				fputcsv($output, array('Ticket No','Issue Name', 'Issue Detail', 'Require Date','Created Date', 'Created By', 'Status', 'companyId', 'SLA Time Frame'));
				$query="SELECT `id`, `issueName`, `issueDetail`, `requireDate`, `createdDate`, (SELECT name FROM organizationuser WHERE id=`createdBy`) AS `createdBy`, `status`, (SELECT name FROM clientcompany WHERE id=`companyId`) AS company, `timeFrame` FROM clientcomplaint WHERE status>?";
				$stmt=mysqli_prepare($con,$query);
				mysqli_stmt_bind_param($stmt, 'i', $status);}
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				//$json=array();
				while($row=$result->fetch_assoc()){
					$status=$row['status'];
					$data="";
					 if ($status=== 0) {
						 $data = "COMPLETE";
					 } else if ($status === 2) {
						 $data = "PENDING";
					 } else if ($status === 3) {
						 $data = "IN PROGRESS";
					 }else if ($status === 4) {
						 $data = "RESOLVED";
					 }else if ($status === 5) {
						 $data = "VERIFICATION";
					 }else if ($status === 6) {
						 $data = "CLOSED";
					 }else if ($status === 7) {
						 $data = "REOPEN";
					 }
					$row['status']=$data;
					fputcsv($output, $row);
				//	$json[] = $row;
				}
				fclose($output);    
				rewind($output); 
				mysqli_stmt_close($stmt);
		return $output;
	}
	
	function fetchComplaintCompleteForExcel($con){
		$status=0;
		$output="";
		$query="";
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');
		$output = fopen("php://output", "w");
		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
			    fputcsv($output, array('Ticket No', 'Issue Name', 'Issue Detail', 'Docket Created Date', 'CPA', 'Category', 'State', 'VSat Technology','sitename', 'Note', 'Remarks', 'Resolution', 'Docket Recive Date', 'Docket Attend Date', 'Docket Close Date', 'SLA', 'Troubleshooting', 'Status' ));
			$query="SELECT clientcomplaint.id,clientcomplaint.issueName,clientcomplaint.issueDetail,telecom_service.docDate,telecom_service.cpa,telecom_service.category,telecom_service.state,telecom_service.vsattechnology, telecom_service.sitename,telecom_service.note,telecom_service.remarks,telecom_service.resolution,telecom_service.docrecivedate,telecom_service.docattenddate,telecom_service.docclosedate,telecom_service.sla,telecom_service.troubleshoot,telecom_service.status FROM clientcomplaint JOIN telecom_service ON telecom_service.cid=clientcomplaint.id AND clientcomplaint.status IN (?, 5 ,6 ,7 ) AND telecom_service.docDate BETWEEN DATE(NOW()) - INTERVAL 7 DAY AND DATE(NOW()) ORDER BY clientcomplaint.id DESC;";
		}
			else{
				fputcsv($output, array('id','Issue Name', 'Issue Detail', 'Require Date', 'Created Date', 'Created By', 'Status', 'companyId', 'SLA Time Frame'));
		$query="SELECT `id`, `issueName`, `issueDetail`, `requireDate`, `createdDate`, (SELECT name FROM organizationuser WHERE id=`createdBy`) AS `createdBy`, `status`, (SELECT name FROM clientcompany WHERE id=`companyId`) AS `company`, `timeFrame` FROM clientcomplaint WHERE status>=?";}
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt, 'i', $status);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$status=$row['status'];
			$data="";
			if ($status=== 0) {
				$data = "COMPLETE";
			} else if ($status === 2) {
				$data = "PENDING";
			} else if ($status === 3) {
				$data = "IN PROGRESS";
			}else if ($status === 4) {
				$data = "RESOLVED";
			}else if ($status === 5) {
				$data = "VERIFICATION";
			}else if ($status === 6) {
				$data = "CLOSED";
			}else if ($status === 7) {
				$data = "REOPEN";
			}
			$row['status']=$data;

			 fputcsv($output, $row);
		}
		fclose($output);  
		rewind($output); 
		mysqli_stmt_close($stmt);
		return $output;
	}
// telecome service get doc remarks by org staff
	function getdocremarks($con,$cid){
		$query="SELECT remarks FROM telecom_service WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$cid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}
		// telecome service check doc attend date by org staff
		function checkdocattenddate($con,$cid){
		$query="SELECT docattenddate FROM telecom_service WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$cid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;

	}
		function updateDocketComplaintStatus($con,$docStatus,$docattenddate,$docclosedate,$docremarks,$cid){
			$success=false;

		$query="UPDATE telecom_service SET status=?,docclosedate=?, sla=TIMEDIFF(?,?), remarks=? WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssi',$docStatus,$docclosedate,$docclosedate,$docattenddate,$docremarks,$cid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

		function updateDocRemarks($con,$cid,$remarks){
			$success=false;
		$query="UPDATE telecom_service SET remarks=? WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$remarks,$cid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;

	}
	// telecome service update by org staff
	function updateDocketComplaint($con,$troubleshoot,$docattenddate,$resolution,$remarks,$note,$cid){
		$success=false;
	$query="UPDATE telecom_service SET troubleshoot=?,docattenddate=?,resolution=?,remarks=?,note=? WHERE cid=?";
	$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sssssi',$troubleshoot,$docattenddate,$resolution,$remarks,$note,
		$cid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
	function updateDocAssignTime($con,$docrecivedate,$remarks,$cid){
		$success=false;
		$query="UPDATE telecom_service SET docrecivedate=?,remarks=? WHERE cid=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssi',$docrecivedate,$remarks,$cid);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;

	}
	function updateClientComplaint($con,$complaintId,$issueName,$issueDetails,$occuredDate,$picName,$picContactNo,
	$createdDate,$createdBy,$messageStatus,$status,$companyId,$orgId){
		$success=false;
		$query="UPDATE clientcomplaint SET issueName=?,issueDetail=?,occuredDate=?,picName=?,
		picContactNo=?,createdDate=?,createdBy=?,messageStatus=?,status=?,companyId=?,orgId=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssssssisiiii',$issueName,$issueDetails,$occuredDate,$picName,$picContactNo,
		$createdDate,$createdBy,$messageStatus,$status,$companyId,$orgId,$complaintId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function insertComplaintProduct($con,$complaintId,$productId){
		$feedback=false;
		$query="INSERT INTO complaintproduct (complaintId,productId)
		VALUES (?,?)";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$complaintId,$productId);
		if(mysqli_stmt_execute($stmt)){
			$feedback=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);

		return $feedback;
	}

	function fetchComplaintProductRowByComplaintId($con,$complaintId){
		$dataList=array();
		$query="SELECT * FROM complaintproduct WHERE complaintId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$complaintId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;

	}

	function fetchComplaintProductByComplaintId($con,$complaintId){
		$dataList=array();
		$query="SELECT * FROM complaintproduct WHERE complaintId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$complaintId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;

	}

	function fetchComplaintProductByComplaintIdAndProductId($con,$complaintId,$productId){
		$dataList=array();
		$query="SELECT * FROM complaintproduct WHERE complaintId=? AND productId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$complaintId,$productId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}
		mysqli_stmt_close($stmt);

		return $dataList;

	}

	function deleteComplaintProduct($con,$complaintId,$productId){
	  $success=false;

	  $query="DELETE FROM complaintproduct WHERE complaintId=? AND productId=?";
	  $stmt=mysqli_prepare($con,$query);
	  mysqli_stmt_bind_param($stmt,'ii',$complaintId,$productId);
	  if(mysqli_stmt_execute($stmt)){
	    $success=true;
	  }
	  mysqli_stmt_close($stmt);

	  return $success;
	}

		function createComplaintExtra($con, $modelNo, $eqDetails, $smrNo, $jobType, $rootCause, $partsReplace, $comments, $star, $lastRepair, $requestType, $lastRequest, $compId){
			$feedback=false;

			$query="INSERT INTO `complaintextra` (`modelNo`,`eqDetails`,`smrNo`,`jobType`,`rootCause`,`partsReplace`,`comments`,`star`,`requestType`,`lastRequest`,`lastRepair`,`comid`)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt=mysqli_prepare($con,$query);
			mysqli_stmt_bind_param($stmt,'sssssssisssi',$modelNo,$eqDetails,$smrNo,$jobType,$rootCause,$partsReplace,$comments,$star,$lastRepair,$requestType,$lastRequest,$compId);
			if(mysqli_stmt_execute($stmt)){
				$feedback=true;
			}else{
				die('execute() failed: ' . htmlspecialchars($stmt->error));
			}
			mysqli_stmt_close($stmt);

			return $feedback;
		}
		function updateComplaintExtra($con, $modelNo, $eqDetails, $smrNo, $jobType, $rootCause, $partsReplace, $comments, $star, $compId){
			$success=false;
			$query="UPDATE `complaintextra` SET `modelNo`=?,`eqDetails`=?,`smrNo`=?,`jobType`=?,`rootCause`=?,`partsReplace`=?,`comments`=?,`star`=? WHERE `comid`=?";
			$stmt=mysqli_prepare($con,$query);
			mysqli_stmt_bind_param($stmt,'sssssssii',$modelNo, $eqDetails, $smrNo, $jobType, $rootCause, $partsReplace, $comments, $star, $compId);
			if(mysqli_stmt_execute($stmt)){
				$success=true;
			}
			return $success;
		}
?>
