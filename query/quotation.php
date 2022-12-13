<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");





	function createQuotation($con,$customerName,$customerAddress,$jobId,$quotationNumber,
	$customerId,$attention,$createdBy,$createdDate,$quotationDate,$dueDate,$subTotal,$discount,$tax,$total,
	$status,$orgId,$fileName,$footerId){
		$quotId=0;
		//$success=false;
		if(empty($jobId)){
			$jobId=null;
		}

		$query="INSERT INTO quotation(
		customerName,
		customerAddress,
		jobId,
		quotationNo,
		customerId,
		attention,
		createdBy,
		createdDate,
		dueDate,
		quotationDate,
		subTotal,
		discount,
		tax,
		total,
		status,
		orgId,
		fileName,
		footerId
		)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiisisssssssiisi',
			$customerName,
			$customerAddress,
			$jobId,
			$quotationNumber,
			$customerId,
			$attention,
			$createdBy,
			$createdDate,
			$dueDate,
			$quotationDate,
			$subTotal,
			$discount,
			$tax,
			$total,
			$status,
			$orgId,
			$fileName,
			$footerId
		);
		if(mysqli_stmt_execute($stmt)){
			$quotId=mysqli_insert_id($con);
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);

		return $quotId;
	}

	function deleteQuotationByQuotId($con,$quotationId){
		$success=false;

		$query="DELETE FROM quotation WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$quotationId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}

		mysqli_stmt_close($stmt);

		return $success;
	}

	function updateQuotationStatusByQuotId($con,$quotId,$status){
		$success=false;
		$query="UPDATE quotation SET status=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$status,$quotId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;

	}


	function updateEditQuotation($con,$customerName,$customerAddress,$jobId,$quotationNumber,
	$customerId,$attention,$createdBy,$createdDate,$quotationDate,$dueDate,$subTotal,$discount,$tax,$total,
	$status,$orgId,$fileName,$quotationId,$footerId){
		$success=false;

		$query="UPDATE quotation SET
		customerName=?,
		customerAddress=?,
		jobId=?,
		quotationNo=?,
		customerId=?,
		attention=?,
		createdBy=?,
		createdDate=?,
		dueDate=?,
		quotationDate=?,
		subTotal=?,
		discount=?,
		tax=?,
		total=?,
		status=?,
		orgId=?,
		fileName=?,
		footerId=?
		WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiisisssssssiisii',
		$customerName,$customerAddress,$jobId,$quotationNumber,
		$customerId,$attention,$createdBy,$createdDate,
			$dueDate,$quotationDate,
		$subTotal,$discount,
		$tax,$total,
		$status,
		$orgId,
		$fileName,
		$footerId,
		$quotationId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}

		mysqli_stmt_close($stmt);

		return $success;
	}


	function getLatestQuotationNo($con,$orgId){
		$quotationNo=0;
		$query="SELECT MAX(quotationNo) as quotNo FROM quotation WHERE orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$quotationNo=$row['quotNo'];
		}

		mysqli_stmt_close($stmt);

		return $quotationNo;
	}

	function fetchQuotationDetailsById($con,$quotationId){

		$query="SELECT * FROM quotation WHERE id=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$quotationId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function fetchQuotationDetailsByQuotationNo($con,$quotationNo){

		$query="SELECT * FROM quotation WHERE quotationNo=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$quotationNo);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

function deleteQuotation($con,$quotationNo)
{
	$success = FALSE;
	$quotation="%".$quotationNo;
	$query = 'DELETE FROM `quotationitem` WHERE `quotationId`=(SELECT `id` FROM `quotation` WHERE `quotationNo` LIKE ? LIMIT 1)';
	$stmt = mysqli_prepare($con, $query);
	mysqli_stmt_bind_param($stmt, 's', $quotation);
	if (mysqli_stmt_execute($stmt)) {
	}
	$query1 = 'DELETE FROM `quotation` WHERE quotationNo LIKE ?';
	$stmt1 = mysqli_prepare($con, $query1);
	mysqli_stmt_bind_param($stmt1, 's', $quotation);
	if (mysqli_stmt_execute($stmt1)) {
		$success = TRUE;
	}
	mysqli_stmt_close($stmt);

	return $success;
}

	function fetchQuotationList($con,$quotationId,$customerName,$jobId,$quotationNo,$customerId,
	$createdBy,$dateFrom,$dateTo,$status,$orgId){
		$dataList=array();
		$query="SELECT *, (SELECT name FROM `organizationuser` WHERE quotation.createdBy=id) as name FROM quotation WHERE 1=1 ";
		$paramType="";
		$paramList = array();

		if($quotationId!==null){

			$query.="AND id=? ";
			$paramList[]=$quotationId;
		}
		if($customerName!=null){
			$query.="AND customerName=? ";
			$paramList[]=$customerName;
		}
		if($jobId!==null){
			$query.="AND jobId=? ";
			$paramList[]=$jobId;
		}
		if($quotationNo!==null){
			$query.="AND quotationNo=? ";
			$paramList[]=$quotationNo;
		}
		if($customerId!==null){
			$query.="AND customerId=? ";
			$paramList[]=$customerId;
		}
		if($createdBy!==null){
			$query.="AND createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($dateFrom!==null && $dateTo!==null){
			$query.="AND createdDate between ? AND ? ";
			$paramList[]=$dateFrom;
			$paramList[]=$dateTo;
		}
		if($status!==null){
			$query.="AND status=? ";
			$paramList[]=$status;
		}
		if($orgId!==null){
			$query.="AND orgId=? ";
			$paramList[]=$orgId;
		}

		file_put_contents("./0qu.log",$query);
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

	function quotationListPaid($con){
		$query="SELECT * FROM quotation WHERE status=1";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function quotationListUnpaid($con){
		$query="SELECT * FROM quotation WHERE status=0";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList=array();
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function quotationListPaidByDate($con,$dateYear,$dateMonth){
		$dataList = array();
		$query="SELECT * FROM quotation WHERE status=1 AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$dataList=array();
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function quotationListUnpaidByDate($con,$dateYear,$dateMonth){
		$dataList = array();
		$query="SELECT * FROM quotation WHERE status=0 AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

function fetchClientCompanyContactByQuotationId($con,$clientId){

    $query="SELECT (SELECT `contactNo` FROM `clientcompany` WHERE `id`=`quotation`.`customerId`) AS `email` FROM `quotation` WHERE `quotationNo`=? ";

    $stmt=mysqli_prepare($con,$query);

    mysqli_stmt_bind_param($stmt,'s',$clientId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $value="";
    while($row=$result->fetch_assoc()){
        $value=$row['email'];
    }
    //	$row=$result->fetch_assoc();
    mysqli_stmt_close($stmt);
    return $value;	}

?>