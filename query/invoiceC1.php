<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");


	function createInvoice($con,$customerName,$customerAddress,$jobId,$invoiceNumber,
	$customerId,$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,$total,
	$amountPaid,$status,$orgId,$fileName,$footerId,$recurring,$recurringDate,$recurringEnd,$recurringBy){
		$invoiceId=0;
		//$success=false;
		$query="INSERT INTO invoicec1 (
		customerName,
		customerAddress,
		jobId,
		invoiceNo,
		customerId,
		attention,
		createdBy,
		createdDate,
		invoiceDate,
		paymentDate,
		dueDate,
		subTotal,
		discount,
		tax,
		total,
		amountPaid,
		status,
		orgId,
		fileName,
		footerId,
		isrecurring,
		startdate,
		enddate,
		recurring
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
		mysqli_stmt_bind_param($stmt,'ssiiisisssssssssiisiisss',$customerName,$customerAddress,$jobId,$invoiceNumber,
		$customerId,$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,$total,
		$amountPaid,$status,$orgId,$fileName,$footerId,$recurring,$recurringDate,$recurringEnd,$recurringBy);
		if(mysqli_stmt_execute($stmt)){
			$invoiceId=mysqli_insert_id($con);
		}else{
			die('\nexecute() failed: ' . htmlspecialchars($stmt->error));
		}

		mysqli_stmt_close($stmt);

		return $invoiceId;
	}


	function updateInvoice($con,$invoiceId,$paymentDate,$amountPaid,$status){
		$success=false;
		$query="UPDATE invoicec1 SET paymentDate=?, amountPaid=?, status=? WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssii',$paymentDate,$amountPaid,$status,$invoiceId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}

		return $success;

	}
	function updateEditInvoice($con,$customerName,$customerAddress,$jobId,$invoiceNumber,$customerId,
	$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,
	$total,$amountPaid,$status,$fileName,$orgId,$invoiceId,$footerId){
		$success=false;
		$query="UPDATE invoicec1 SET
		customerName=?,
		customerAddress=?,
		jobId=?,
		invoiceNo=?,
		customerId=?,
		attention=?,
		createdBy=?,
		createdDate=?,
		invoiceDate=?,
		paymentDate=?,
		dueDate=?,
		subTotal=?,
		discount=?,
		tax=?,
		total=?,
		amountPaid=?,
		status=?,
		fileName=?,
		orgId=?,
		footerId=?
		WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssiiisisssssssssisiii',$customerName,$customerAddress,$jobId,$invoiceNumber,
		$customerId,$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,$total,
		$amountPaid,$status,$fileName,$orgId,$footerId,$invoiceId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		mysqli_stmt_close($stmt);
		return $success;
	}

	function getLatestInvoiceNo($con,$orgId){
		$InvoiceNo=0;
		$query="SELECT MAX(invoiceNo) as invcNo FROM invoicec1 WHERE orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$InvoiceNo=$row['invcNo'];
		}

		mysqli_stmt_close($stmt);

		return $InvoiceNo;
	}

	function fetchInvoiceDetailsByInvoiceNo($con,$InvoiceNo){

		$query="SELECT * FROM invoicec1 WHERE invoiceNo=? ";
		$stmt = mysqli_stmt_init($con);
		$row=0;
		if(!mysqli_stmt_prepare($stmt, $query))
		{
   		 print "Failed to prepare statement\n";
		}
		else{
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$InvoiceNo);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=mysqli_fetch_assoc($result);
	//	$row=$result->fetch_assoc();
		}
		mysqli_stmt_close($stmt);
		return $row;
	}

function deleteInvoice($con,$invoiceNo)
{
	$success = FALSE;
	$inv="%".$invoiceNo;
	$query = 'DELETE FROM `invoiceitemc1` WHERE `invoiceId`=(SELECT `id` FROM `invoicec1` WHERE `invoiceNo` LIKE ? LIMIT 1)';
	$stmt = mysqli_prepare($con, $query);
	mysqli_stmt_bind_param($stmt, 's', $inv);
	if (mysqli_stmt_execute($stmt)) {
	}
	$query1 = 'DELETE FROM `invoicec1` WHERE invoiceNo LIKE ?';
	$stmt1 = mysqli_prepare($con, $query1);
	mysqli_stmt_bind_param($stmt1, 's', $inv);
	if (mysqli_stmt_execute($stmt1)) {
		$success = TRUE;
	}
	mysqli_stmt_close($stmt);

	return $success;
}

	function fetchInvoiceListC1($con,$InvoiceId,$customerName,$jobId,$invoiceNo,$customerId,
	$createdBy,$dateFrom,$dateTo,$status,$orgId){
		$dataList=array();
		$query="SELECT * , (SELECT name FROM `organizationuser` WHERE invoicec1.createdBy=id) as name FROM invoicec1 WHERE 1=1 ";
		$paramType="";
		$paramList = array();

		if($InvoiceId!==null){

			$query.="AND id=? ";
			$paramList[]=$InvoiceId;

		}

		if($customerName!=null){
			$query.="AND customerName=? ";
			$paramList[]=$customerName;
		}

		if($jobId!==null){
			$query.="AND jobId=? ";
			$paramList[]=$jobId;
		}

		if($invoiceNo!==null){
			$query.="AND invoiceNo=? ";
			$paramList[]=$invoiceNo;
		}
		if($customerId!==null){
			$query.="AND customerId=? ";
			$paramList[]=$customerId;
		}
		if($createdBy!==null){
			$query.="AND createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($dateFrom!==null && $dateTo !==null){
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

	function fetchInvoiceListValid($con,$InvoiceId,$customerName,$jobId,$invoiceNo,$customerId,
	$createdBy,$dateFrom,$dateTo,$status,$orgId){
		$dataList=array();
		$query="SELECT * FROM invoicec1 WHERE invalidate=0 ";
		$paramType="";
		$paramList = array();

		if($InvoiceId!==null){

			$query.="AND id=? ";
			$paramList[]=$InvoiceId;

		}

		if($customerName!=null){
			$query.="AND customerName=? ";
			$paramList[]=$customerName;
		}

		if($jobId!==null){
			$query.="AND jobId=? ";
			$paramList[]=$jobId;
		}

		if($invoiceNo!==null){
			$query.="AND invoiceNo=? ";
			$paramList[]=$invoiceNo;
		}
		if($customerId!==null){
			$query.="AND customerId=? ";
			$paramList[]=$customerId;
		}
		if($createdBy!==null){
			$query.="AND createdBy=? ";
			$paramList[]=$createdBy;
		}
		if($dateFrom!==null && $dateTo !==null){
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


	function invalidateInvoice($con,$invoiceNo,$remark){
		$query="UPDATE invoicec1 SET invalidate=1, remark=? WHERE invoiceNo=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$remark,$invoiceNo);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			$success=false;
		}
		return $success;
	}

	function revalidateInvoice($con,$invoiceNo){
		$query="UPDATE invoicec1 SET invalidate=0, remark=? WHERE invoiceNo=?";
		$stmt=mysqli_prepare($con,$query);
		$remark = "";
		mysqli_stmt_bind_param($stmt,'si',$remark,$invoiceNo);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}else{
			$success=false;
		}
		return $success;
	}

	function invoiceListPaid($con){
	    $dataList = array();
		$query="SELECT * FROM invoicec1 WHERE status=0 AND invalidate=0";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function invoiceListUnpaid($con){
	    $dataList = array();
		$query="SELECT * FROM invoicec1 WHERE status=1 AND invalidate=0";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function invoiceListInvalid($con){
	    $dataList = array();
		$query="SELECT * FROM invoicec1 WHERE invalidate=1 ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		return $dataList;
	}

	function invoiceListPaidByDate($con,$dateYear,$dateMonth){
		$dataList = array();
		$query="SELECT * FROM invoicec1 WHERE status=0 AND invalidate=0 AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function invoiceListPaidByYear($con,$dateYear){
		$dataList = array();
		$query="SELECT * FROM invoicec1 WHERE status=0 AND invalidate=0 AND YEAR(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'s',$dateYear);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}

	function invoiceListUnpaidByDate($con,$dateYear,$dateMonth){
		$dataList = array();
		$query="SELECT * FROM invoicec1 WHERE status=1 AND invalidate=0 AND YEAR(createdDate) = ? AND MONTH(createdDate) = ?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}


	function fetchInvoiceDetailsById($con,$invoiceId){
		$query="SELECT * FROM invoicec1 WHERE id=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$invoiceId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}


function fetchClientCompanyContactByInvoiceId($con,$clientId){

    $query="SELECT (SELECT `contactNo` FROM `clientcompany` WHERE `id`=`invoicec1`.`customerId`) AS `email` FROM `invoicec1` WHERE `invoiceNo`=? ";

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