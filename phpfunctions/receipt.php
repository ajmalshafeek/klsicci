<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/receipt.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
$con = connectDb();

if (isset($_POST['getReceiptList'])) {
  if (isset($_POST['clientCompanyId'])) {
    $clientCompanyId = $_POST['clientCompanyId'];
  }else {
    $clientCompanyId = null;
  }

  if ($_POST['dateFrom'] != null) {
    $dateFrom = $_POST['dateFrom'];
  }else {
    $dateFrom = null;
  }

  if ($_POST['dateTo'] != null) {
    $dateTo = $_POST['dateTo'];
  }else {
    $dateTo = null;
  }

  $invoiceNo = strval($_POST['invoiceNo']);

  $table =
  "
  <table id='receiptTable'>
    <thead>
      <tr>
        <th>Date</th>
        <th>Receipt No.</th>
        <th>Invoice No.</th>
        <th>Total Amount</th>
        <th>Total Balance</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
  ";

  if ($invoiceNo != null) {
    $rowInvoice = fetchInvoiceDetailsByInvoiceNo($con,$invoiceNo);
    if (count($rowInvoice) != 0) {
      $invoiceId = $rowInvoice['id'];
      $dataList = fetchReceiptByDynamicVariable($con, $invoiceId, $clientCompanyId, $dateFrom, $dateTo);
      if (count($dataList)!=0) {
        $sumPaidAmount = 0;
        foreach ($dataList as $data) {
          $receiptDateTime = $data['receiptDateTime'];
          $receiptNo = $data['receiptNo'];
          $totalAmount = $rowInvoice['total'];

          $sumPaidAmount = $sumPaidAmount + $data['paidAmount'];
          $totalBalance = $totalAmount - $sumPaidAmount;

          $data_toggle = "data-toggle='modal' data-target='#receiptModal' onclick='showReceipt(".$data['id'].")'";
          $table .="
          <tr style='cursor:pointer'>
            <td ".$data_toggle.">".$receiptDateTime."</td>
            <td ".$data_toggle.">".str_pad($receiptNo,10,"0",STR_PAD_LEFT)."</td>
            <td ".$data_toggle.">".str_pad($invoiceNo,10,"0",STR_PAD_LEFT)."</td>
            <td ".$data_toggle.">".$totalAmount."</td>
            <td ".$data_toggle.">".number_format($totalBalance, 2, '.', '')."</td>
            <td><button class='btn btn-secondary btn-lg btn-block' data-toggle='modal' data-target='#receiptPDFModal' onclick='showReceiptPDF(\"".$data['fileName']."\")'>View Receipt</button></td>
          </tr>
          ";
        }
      }elseif(count($dataList)==0){
        $table .="
        <tr data-toggle='modal' data-target='#receiptModal' onclick='showInvoice(".$rowInvoice['id'].")' style='cursor:pointer'>
          <td></td>
          <td></td>
          <td>".str_pad($rowInvoice['invoiceNo'],10,"0",STR_PAD_LEFT)."</td>
          <td>".$rowInvoice['total']."</td>
          <td></td>
          <td><i>[No Receipt]</i></td>
        </tr>
        ";
      }
    }
  }else {
    $invoiceId = null;
    $dataList = fetchReceiptByDynamicVariable($con, $invoiceId, $clientCompanyId, $dateFrom, $dateTo);
    foreach ($dataList as $data) {
      $receiptDateTime = $data['receiptDateTime'];
      $receiptNo = $data['receiptNo'];

      $invoiceId = $data['invoiceId'];
      $rowInvoice = fetchInvoiceDetailsById($con,$invoiceId);
      $invoiceNo = str_pad($rowInvoice['invoiceNo'],10,"0",STR_PAD_LEFT);

      $totalAmount = $rowInvoice['total'];
      $totalBalance = $totalAmount - $data['paidAmount'];

      $data_toggle = "data-toggle='modal' data-target='#receiptModal' onclick='showReceipt(".$data['id'].")'";

      $table .="
      <tr style='cursor:pointer'>
        <td ".$data_toggle.">".$receiptDateTime."</td>
        <td ".$data_toggle.">".str_pad($receiptNo,10,"0",STR_PAD_LEFT)."</td>
        <td ".$data_toggle.">".$invoiceNo."</td>
        <td ".$data_toggle.">".number_format($totalAmount, 2, '.', '')."</td>
        <td ".$data_toggle.">".number_format($totalBalance, 2, '.', '')."</td>
        <td><button class='btn btn-secondary btn-lg btn-block' data-toggle='modal' data-target='#receiptPDFModal' onclick='showReceiptPDF(\"".$data['fileName']."\")'>View Receipt</button></td>
      </tr>
      ";
    }
  }

  $table.="
    </tbody>
  </table>
  ";

  $_SESSION['receiptTable'] =  $table;

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/receipt.php");
}

if (isset($_GET['showReceipt'])) {
  $receiptId = $_GET['showReceipt'];
  $row = fetchReceiptById($con, $receiptId);
  $rowInvoice = fetchInvoiceDetailsById($con,$row['invoiceId']);
  $invoiceNo = str_pad($rowInvoice['invoiceNo'],10,"0",STR_PAD_LEFT);
  $totalAmount  = $rowInvoice['total'];
  $paidAmount = $row['paidAmount'];
  $invoiceId = $rowInvoice['id'];

  $obj = '{"id":3,"invoiceNo":"'.$invoiceNo.'","totalAmount":'.$totalAmount.',"paidAmount":"'.$paidAmount.'","invoiceId":'.$invoiceId.'}';

  echo $obj;
}

if (isset($_POST['submitReceipt'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/receiptPDF.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoiceItem.php");
  $invoiceId = $_POST['invoiceId'];
  $amountToPay = $_POST['amountToPay'];

  $receiptDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/receipt/";
  if (!file_exists($receiptDirectory)) {
    mkdir($receiptDirectory, 0777, true);
  }

  //$date
  $date = date("Y-m-d");

  //$receiptNo
  $receiptNo=getLatestReceiptNo($con);
  if($receiptNo==null){
    $receiptNo="0";
  }
 $receiptNo=$receiptNo+1;
	

  //$invoiceNo
  $rowInvoice = fetchInvoiceDetailsById($con,$invoiceId);
  $invoiceNo = $rowInvoice['invoiceNo'];

  //$amount
  $amount = $amountToPay;

  //$receivedFrom
  $receivedFrom = $rowInvoice['attention'];

  //$receivedBy
  $receivedBy = $rowInvoice['customerName'];

  //$forPaymentOf
  $forPaymentOf = "";
  $invoiceItemList = fetchInvoiceDetailsByInvoiceId($con,$invoiceId);
  $comma = false;
  foreach ($invoiceItemList as $invoiceItem) {
    if ($comma) {
      $forPaymentOf .= ",";
      $comma = false;
    }
    $forPaymentOf .= $invoiceItem['itemDescription'];
    $comma = true;
  }

  //$totalAmount
  $totalAmount = $rowInvoice['total'];

  //$balance
  $balance =  calcReceiptBalanceByInvoiceId($invoiceId) - $amount;

  $receiptPDF = generateReceiptPDF($date,$receiptNo,$invoiceNo,$amount,$receivedFrom,$receivedBy,$forPaymentOf,$totalAmount,$balance);
  $receiptIdentity = "R".date("ymdHis");
  $receiptName = $receiptIdentity.".pdf";
  $receiptPDF->output($receiptDirectory."/".$receiptName,'F');


  $receiptDateTime = date("Y-m-d H:i:s");
  $clientCompanyId = $rowInvoice['customerId'];
  $feedback = insertReceipt($con,$receiptNo,$invoiceId,$clientCompanyId,$amountToPay,$receiptDateTime,$receiptName);

  $_SESSION['receiptNumber'] = $receiptIdentity;

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS !</strong> RECEIPT HAVE BEEN GENERATED\n
    </div>\n";
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/mailReceipt.php");
	  
  }else {
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO GENERATE RECEIPT\n
    </div>\n";
	 header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/receipt.php");
  }

}

function calcReceiptBalanceByInvoiceId($invoiceId){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/receipt.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");

  $con = connectDb();
  $dataList = fetchReceiptListByInvoiceId($con,$invoiceId);
  $sumPaidAmount = 0;
  foreach ($dataList as $data) {
    $sumPaidAmount = $sumPaidAmount + $data['paidAmount'];
  }

  $row = fetchInvoiceDetailsById($con,$invoiceId);

  $balanceReceipt = $row['total'] - $sumPaidAmount;

  return $balanceReceipt;
}

function calcReceiptBalanceByReceiptIdAndInvoiceId($receiptId,$invoiceId){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/receipt.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");

  $con = connectDb();
  $dataList = fetchReceiptListByInvoiceId($con,$invoiceId);
  $sumPaidAmount = 0;
  foreach ($dataList as $data) {
    $sumPaidAmount = $sumPaidAmount + $data['paidAmount'];
    if ($data['id'] == $receiptId) {
      break;
    }
  }

  $row = fetchInvoiceDetailsById($con,$invoiceId);

  $balanceReceipt = $row['total'] - $sumPaidAmount;

  return $balanceReceipt;
}

if (isset($_GET['getReceiptBalance'])) {
  $invoiceId = $_GET['invoiceId'];
  echo calcReceiptBalanceByInvoiceId($invoiceId);
}

if (isset($_GET['getInvoiceTotal'])) {
  $invoiceId = $_GET['getInvoiceTotal'];
  $row = fetchInvoiceDetailsById($con,$invoiceId);
  echo $row['total'];
}

if (isset($_GET['mailReceipt'])) {
  $_SESSION['receiptNumber'] = $_GET['mailReceipt'];
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/mailReceipt.php");
}

function getInvoiceDetailsByReceiptFileName($receiptFileName){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/receipt.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
  $con = connectDb();

  $fileName = $receiptFileName.".pdf";
  $row = fetchReceiptByFileName($con, $fileName);
  return fetchInvoiceDetailsById($con,$row['invoiceId']);
}
?>
