<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function fetchReceiptByDynamicVariable($con, $invoiceId, $clientCompanyId, $dateFrom, $dateTo){
  $dataList=array();
  $query="SELECT * FROM receipt WHERE 1=1 ";
  $paramType="";
  $paramList = array();

  if($invoiceId!==null){
    $query.="AND invoiceId=? ";
    $paramList[]=$invoiceId;
  }

  if($clientCompanyId!==null){
    $query.="AND clientCompanyId=? ";
    $paramList[]=$clientCompanyId;
  }

  if($dateFrom!==null && $dateTo !==null){
    $query.="AND receiptDateTime between ? AND ? ";
    $paramList[]=$dateFrom;
    $paramList[]=$dateTo;
  }
  $query.="ORDER BY id DESC";
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

function fetchReceiptById($con, $receiptId){
  $query="SELECT * FROM receipt WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$receiptId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchReceiptByFileName($con, $fileName){
  $query="SELECT * FROM receipt WHERE fileName=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$fileName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function getLatestReceiptNo($con){
  $query="SELECT MAX(receiptNo) as rcptNo FROM receipt";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt,'i',$orgId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $receiptNo=$row['rcptNo'];
  }
  mysqli_stmt_close($stmt);

  return $receiptNo;
}

function insertReceipt($con,$receiptNo,$invoiceId,$clientCompanyId,$amountToPay,$receiptDateTime,$fileName){
  $feedback = false;
  $query="INSERT INTO receipt (receiptNo,invoiceId,clientCompanyId,paidAmount,receiptDateTime,fileName) VALUES (?,?,?,?,?,?);";
	
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iiisss',$receiptNo,$invoiceId,$clientCompanyId,$amountToPay,$receiptDateTime,$fileName);

  if(mysqli_stmt_execute($stmt)){
	  $feedback = true;
  }
  mysqli_stmt_close($stmt);
 
  return $feedback;
}

function fetchReceiptListByInvoiceId($con,$invoiceId){
  $dataList=array();

  $query="SELECT * FROM receipt WHERE invoiceId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$invoiceId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
?>
