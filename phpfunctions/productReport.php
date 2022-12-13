<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/product.php");

$con = connectDb();
 ob_start();
function sumProduct($data){
 
$count=0;
 foreach($data as $temp){
    if(isset($temp)){
$count+=$temp['count'];}
}
return $count;
}

if (isset($_POST['processReport'])) {
  $timeCategory = $_POST['timeCategory'];
  if ($timeCategory == 0) {
    $date = $_POST['dateMonth'];
 
    $product =fetchProductType($con);
    $sentout =fetchProductOutstock($con,$date);
    $sentoutTotal=sumProduct(fetchProductOutstock($con,$date));
    $instock =fetchProductInstock($con,$date);
    $instockTotal=sumProduct(fetchProductInstock($con,$date));
    $contract= fetchProductOutstockContract($con,$date);

    $year = date("Y", strtotime($date));
    $m = date("m", strtotime($date));
    $month = showMonth($m);
    $dateReport = $month." ".$year;

   /* $totalRevenue = sumInvoicePaidByMonth($date);
    $totalExpenses = sumExpensesByMonth($date) + sumPayrollByMonth($date) + sumBillByMonth($date);
    $netIncome = sumInvoicePaidByMonth($date) - $totalExpenses; */
    $sumRule = "style='border-top:1px solid black'";
    $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

    $table = "
    <table id='profitLossTable'>
    <thead>
      <tr>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
       <tr class='border_top'><td>Product Report for (".$dateReport.")</td><td></td></tr>
       <tr><td></td><td>Total</td></tr>
       <tr><td><b>In Stock</b></td><td></td></tr>";
        $instock = fetchProductInstock($con,$date);
        foreach($instock as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>In Stock Total </td><td ".$sumRule.">".$instockTotal."</td></tr>";
        $table .="<tr><td><b>Sent Out</b></td><td></td></tr>";
        foreach($sentout as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>Sent Out Total </td><td ".$sumRule.">".$sentoutTotal."</td></tr>";
        $table .="<tr><td><b>Contract</b></td><td></td></tr>";
        foreach($contract as $temp){
        $table .="<tr><td>".$temp['contract']."</td><td>".$temp['count']."</td></tr>";}

        $table.="<tbody></table>";



  }else {
    $yearReportFormat = $_POST['yearReportFormat'];
        
    $table = "
    <table id='profitLossTable'>
    <thead>
      <tr>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>";

    if ($yearReportFormat == "separated") {


    
      for ($i=1; $i <= 12; $i++) {
        $year = $_POST['dateYear'];
        $date="";
        if($i>9){
        $date = $year."-".$i;
        }else{
        $date = $year."-0".$i;
        }
        $m = date("m", strtotime($date));
        $month = showMonth($m);
        $dateReport = $month." ".$year;

        $product =fetchProductType($con);
        $sentout =fetchProductOutstock($con,$date);
        $sentoutTotal=sumProduct(fetchProductOutstock($con,$date));
        $instock =fetchProductInstock($con,$date);
        $instockTotal=sumProduct(fetchProductInstock($con,$date));
        $contract= fetchProductOutstockContract($con,$date);

        $totalRevenue = sumInvoicePaidByMonth($date);
        $totalExpenses = sumExpensesByMonth($date) + sumPayrollByMonth($date) + sumBillByMonth($date);
        $netIncome = sumInvoicePaidByMonth($date) - $totalExpenses;
        $sumRule = "style='border-top:1px solid black'";
        $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

        $table .= "<tr class='border_top'><td>Product Report for (".$dateReport.")</td><td></td></tr>
        <tr><td></td><td>Total</td></tr>
        <tr><td><b>In Stock</b></td><td></td></tr>";
        $instock = fetchProductInstock($con,$date);
        foreach($instock as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>In Stock Total </td><td ".$sumRule.">".$instockTotal."</td></tr>";
        $table .="<tr><td><b>Sent Out</b></td><td></td></tr>";
        foreach($sentout as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>Sent Out Total </td><td ".$sumRule.">".$sentoutTotal."</td></tr>";
        $table .="<tr><td><b>Contract</b></td><td></td></tr>";
        foreach($contract as $temp){
        $table .="<tr><td>".$temp['contract']."</td><td>".$temp['count']."</td></tr>";
       }
       /* $table.="
        <tr class='border_top'><td>Profit & Loss Report for (".$dateReport.")</td><td></td></tr>
        <tr><td></td><td>RM</td></tr>
        <tr><td><b>Revenue</b></td><td></td></tr>
        <tr><td>Sales</td><td>".sumInvoicePaidByMonth($date)."</td></tr>
        <tr><td><b>Total Revenue</b></td><td ".$sumRule.">".$totalRevenue."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><b>Expenses</b></td><td></td></tr>
        <tr><td><u>Purchase Invoice</u></td><td></td></tr>
        <tr><td>Supplier</td><td>".sumExpensesByMonth($date)."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><u>Payroll</u></td><td></td></tr>
        <tr><td>Salaries, Benefits, Wages</td><td>".sumPayrollByMonth($date)."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><u>Bill Payment</u></td><td></td></tr>
        <tr><td>Total</td><td>".sumBillByMonth($date)."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><b>Total Expenses</b></td><td ".$sumRule.">".$totalExpenses."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><b>Net Income</b></td><td ".$sumRuleNet.">".$netIncome."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        ";
        */
      }
    }elseif($yearReportFormat == "combined"){
      $date = $_POST['dateYear'];
      $dateReport = "Year ".$date; 

        $product =fetchProductType($con);
        $sentout =fetchProductOutstock($con,$date);
        $sentoutTotal=sumProduct(fetchProductOutstock($con,$date));
        $instock =fetchProductInstock($con,$date);
        $instockTotal=sumProduct(fetchProductInstock($con,$date));
        $contract= fetchProductOutstockContract($con,$date);

      $sumRule = "style='border-top:1px solid black'";
      $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

        $table .= "<tr class='border_top'><td>Product Report for (".$dateReport.")</td><td></td></tr>
        <tr><td></td><td>Total</td></tr>
        <tr><td><b>In Stock</b></td><td></td></tr>";
        $instock = fetchProductInstock($con,$date);
        foreach($instock as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>In Stock Total </td><td ".$sumRule.">".$instockTotal."</td></tr>";
        $table .="<tr><td><b>Sent Out</b></td><td></td></tr>";
        foreach($sentout as $temp){
        $table .="<tr><td>".$temp['product']."</td><td>".$temp['count']."</td></tr>";}
        $table .="<tr><td>Sent Out Total </td><td ".$sumRule.">".$sentoutTotal."</td></tr>";
        $table .="<tr><td><b>Contract</b></td><td></td></tr>";
        foreach($contract as $temp){
        $table .="<tr><td>".$temp['contract']."</td><td>".$temp['count']."</td></tr>";}
    

/*
      $totalRevenue = sumInvoicePaidByYear($date);
      $totalExpenses = sumExpensesByYear($date) + sumPayrollByYear($date) + sumBillByYear($date);
      $netIncome = sumInvoicePaidByYear($date) - $totalExpenses;
      $sumRule = "style='border-top:1px solid black'";
      $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

      $table .= "
         <tr class='border_top'><td>Profit & Loss Report for (".$dateReport.")</td><td></td></tr>
         <tr><td></td><td>RM</td></tr>
         <tr><td><b>Revenue</b></td><td></td></tr>
         <tr><td>Sales</td><td>".sumInvoicePaidByYear($date)."</td></tr>
         <tr><td><b>Total Revenue</b></td><td ".$sumRule.">".$totalRevenue."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><b>Expenses</b></td><td></td></tr>
         <tr><td><u>Purchase Invoice</u></td><td></td></tr>
         <tr><td>Supplier</td><td>".sumExpensesByYear($date)."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><u>Payroll</u></td><td></td></tr>
         <tr><td>Salaries, Benefits, Wages</td><td>".sumPayrollByYear($date)."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><u>Bill Payment</u></td><td></td></tr>
         <tr><td>Total</td><td>".sumBillByYear($date)."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><b>Total Expenses</b></td><td ".$sumRule.">".$totalExpenses."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><b>Net Income</b></td><td ".$sumRuleNet.">".$netIncome."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td></td><td></td></tr>
      ";
    }

     $table .= "
     <tbody>
    </table>
    ";
    */
  }
$table.="<tbody></table>";
}
  ob_clean();
  ob_end_flush();
  
  $_SESSION['profitLossTable'] = $table;
  $_SESSION['profitLossTableExport'] = $table;
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/product/report/report.php");
}

function sumInvoicePaidByMonth($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
  $con = connectDb();

  $dateMonth = date("m",strtotime($date));
  $dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = invoiceListPaidByDate($con,$dateYear,$dateMonth);
  foreach ($dataList as $data) {
    $sum = $sum + $data['amountPaid'];
  }
  return $sum;
}

function sumExpensesByMonth($date){
  return 0;
}

function sumPayrollByMonth($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
  $con = connectDb();

  $dateMonth = date("m",strtotime($date));
  $dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = fetchPayrollListByDate($con,$dateYear,$dateMonth);
  foreach ($dataList as $data) {
    $str = $data['salaryMonth'];
    $newStr = str_replace(',', '', $str);
    $salaryMonth = intval($newStr);
    $sum = $sum + $salaryMonth;
  }
  return $sum;
}

function sumBillByMonth($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/bill.php");
  $con = connectDb();

  $dateMonth = date("m",strtotime($date));
  $dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = fetchBillListByDate($con,$dateYear,$dateMonth);
  foreach ($dataList as $data) {
    $billMonth = $data['amount'];
    $sum = $sum + $billMonth;
  }
  return $sum;
}

//YEAR------------------------------------------------------------------------------------
function sumInvoicePaidByYear($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
  $con = connectDb();

  //$dateMonth = date("m",strtotime($date));
  //$dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = invoiceListPaidByYear($con,$date);
  foreach ($dataList as $data) {
    $sum = $sum + $data['amountPaid'];
  }
  return $sum;
}

function sumExpensesByYear($date){
  return 0;
}

function sumPayrollByYear($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
  $con = connectDb();

  //$dateMonth = date("m",strtotime($date));
  //$dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = fetchPayrollListByYear($con,$date);
  foreach ($dataList as $data) {
    $str = $data['salaryMonth'];
    $newStr = str_replace(',', '', $str);
    $salaryMonth = intval($newStr);
    $sum = $sum + $salaryMonth;
  }
  return $sum;
}

function sumBillByYear($date){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/bill.php");
  $con = connectDb();

  //$dateMonth = date("m",strtotime($date));
  //$dateYear =  date("Y", strtotime($date));

  $sum = 0;
  $dataList = fetchBillListByYear($con,$date);
  foreach ($dataList as $data) {
    $billMonth = $data['amount'];
    $sum = $sum + $billMonth;
  }
  return $sum;
}

function showMonth($m){
  switch ($m) {
  case "01":
      $month = 'January';
      break;
  case "02":
      $month = 'February';
      break;
  case "03":
      $month = 'March';
      break;
  case "04":
      $month = 'April';
      break;
  case "05":
      $month = 'May';
      break;
  case "06":
      $month = 'Jun';
      break;
  case "07":
      $month = 'July';
      break;
  case "08":
      $month = 'Augusts';
      break;
  case "09":
      $month = 'September';
      break;
  case "10":
      $month = 'October';
      break;
  case "11":
      $month = 'November';
      break;
  case "12":
      $month = 'December';
      break;
  default:
      $month = 'Not a valid month!';
      break;
  }

  return $month;
}
?>
