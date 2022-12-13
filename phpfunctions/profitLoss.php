<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");

$con = connectDb();
if (isset($_POST['processReport'])) {
  $timeCategory = $_POST['timeCategory'];
  if ($timeCategory == 0) {
    $date = $_POST['dateMonth'];
    $year = date("Y", strtotime($date));
    $m = date("m", strtotime($date));
    $month = showMonth($m);
    $dateReport = $month." ".$year;

    $totalRevenue = sumInvoicePaidByMonth($date);
    $totalExpenses = sumExpensesByMonth($date) + sumPayrollByMonth($date)+sumPayrollBonus($date) + sumBillByMonth($date);
    $netIncome = sumInvoicePaidByMonth($date) - $totalExpenses;
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
       <tr class='border_top'><td>Profit & Loss Report for (".$dateReport.")</td><td></td></tr>
       <tr><td></td><td>RM</td></tr>
       <tr><td><u><b>Revenue</b></u></td><td></td></tr>
       <tr><td>Sales</td><td>".sumInvoicePaidByMonth($date)."</td></tr>
       <tr><td><b>Total Revenue</b></td><td ".$sumRule.">".$totalRevenue."</td></tr>
       <tr><td></td><td></td></tr>
       <tr><td><u><b>Bill Payment</b></u></td><td></td></tr>
       <tr><td>Total</td><td>".sumBillByMonth($date)."</td></tr>";
       //<tr><td><u>Expenses</u></td><td></td></tr>
        $table .="<tr><td><u>Purchase Invoice</u></td><td></td></tr>
       <tr><td>Supplier</td><td>".sumExpensesByMonth($date)."</td></tr>
       <tr><td></td><td></td></tr>
       <tr><td><u><b>Payroll</b></u></td><td></td></tr>
       <tr><td>Salaries, Benefits, Wages</td><td>".sumPayrollByMonth($date)."</td></tr>
       <tr><td>Allowance, Claims, Commission, OT, Bonus</td><td>".sumPayrollBonus($date)."</td></tr>
       <tr><td></td><td></td></tr>
       <tr><td></td><td></td></tr>
       <tr><td><b>Total Expenses</b></td><td ".$sumRule.">".$totalExpenses."</td></tr>
       <tr><td></td><td></td></tr>
       <tr><td><b>Net Income</b></td><td ".$sumRuleNet.">".$netIncome."</td></tr>
       <tr><td></td><td></td></tr>
       <tr><td></td><td></td></tr>
     <tbody>
    </table>
    ";
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
        $date = $year."-".$i;
        $m = date("m", strtotime($date));
        $month = showMonth($m);
        $dateReport = $month." ".$year;


        $totalRevenue = sumInvoicePaidByMonth($date);
        $totalExpenses = sumExpensesByMonth($date) + sumPayrollByMonth($date)+sumPayrollBonus($date) + sumBillByMonth($date);
        $netIncome = sumInvoicePaidByMonth($date) - $totalExpenses;
        $sumRule = "style='border-top:1px solid black'";
        $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

        $table.="
        <tr class='border_top'><td>Profit & Loss Report for (".$dateReport.")</td><td></td></tr>
        <tr><td></td><td>RM</td></tr>
         <tr><td><u><b>Revenue</b></u></td><td></td></tr>
        <tr><td>Sales</td><td>".sumInvoicePaidByMonth($date)."</td></tr>
        <tr><td><b>Total Revenue</b></td><td ".$sumRule.">".$totalRevenue."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><u>Bill Payment</u></td><td></td></tr>
        <tr><td>Total</td><td>".sumBillByMonth($date)."</td></tr>";
        //<tr><td><u>Expenses</u></td><td></td></tr>
        $table .="<tr><td><u>Purchase Invoice</u></td><td></td></tr>
        <tr><td>Supplier</td><td>".sumExpensesByMonth($date)."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><u><b>Payroll</b></u></td><td></td></tr>
        <tr><td>Salaries, Benefits, Wages</td><td>".sumPayrollByMonth($date)."</td></tr>
        <tr><td>Allowance, Claims, Commission, OT, Bonus</td><td>".sumPayrollBonus($date)."</td></tr>
        <tr><td></td><td></td></tr>

        <tr><td></td><td></td></tr>
        <tr><td><b>Total Expenses</b></td><td ".$sumRule.">".$totalExpenses."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td><b>Net Income</b></td><td ".$sumRuleNet.">".$netIncome."</td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        ";
      }
    }elseif($yearReportFormat == "combined"){
      $date = $_POST['dateYear'];
      $dateReport = "Year ".$date;

      $totalRevenue = sumInvoicePaidByYear($date);
      $totalExpenses = sumExpensesByYear($date) + sumPayrollByYear($date)+sumPayrollBonusByYear($date) + sumBillByYear($date);
      $netIncome = sumInvoicePaidByYear($date) - $totalExpenses;
      $sumRule = "style='border-top:1px solid black'";
      $sumRuleNet = "style='border-top:1px solid black;border-bottom:3px double black'";

      $table .= "
         <tr class='border_top'><td>Profit & Loss Report for (".$dateReport.")</td><td></td></tr>
         <tr><td></td><td>RM</td></tr>
         <tr><td><u><b>Revenue</b></u></td><td></td></tr>
         <tr><td>Sales</td><td>".sumInvoicePaidByYear($date)."</td></tr>
         <tr><td><b>Total Revenue</b></td><td ".$sumRule.">".$totalRevenue."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><u>Bill Payment</u></td><td></td></tr>
         <tr><td>Total</td><td>".sumBillByYear($date)."</td></tr>";
         //<tr><td><u>Expenses</u></td><td></td></tr>
        $table .="<tr><td><u>Purchase Invoice</u></td><td></td></tr>
         <tr><td>Supplier</td><td>".sumExpensesByYear($date)."</td></tr>
         <tr><td></td><td></td></tr>
         <tr><td><u><b>Payroll</b></u></td><td></td></tr>
         <tr><td>Salaries, Benefits, Wages</td><td>".sumPayrollByYear($date)."</td></tr>
         <tr><td>Allowance, Claims, Commission, OT, Bonus</td><td>".sumPayrollBonusByYear($date)."</td></tr>
         <tr><td></td><td></td></tr>

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
  }
  $_SESSION['profitLossTable'] = $table;
  $_SESSION['profitLossTableExport'] = $table;
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/profitLoss/profitLoss.php");
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

function sumPayrollBonus($date){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
    $con = connectDb();

    $dateMonth = date("m",strtotime($date));
    $dateYear =  date("Y", strtotime($date));

    $sum = 0;
    $dataList = fetchPayrollListByDate($con,$dateYear,$dateMonth);
    foreach ($dataList as $data) {
        $str = (int)$data['allowance']+(int)$data['claims']+(int)$data['commissions']+(int)$data['ot']+(int)$data['bonus'];
        $newStr = str_replace(',', '', $str);
        $salaryMonth = intval($str);
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

function sumPayrollBonusByYear($date){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/bill.php");
    $con = connectDb();

    //$dateMonth = date("m",strtotime($date));
    //$dateYear =  date("Y", strtotime($date));

    $sum = 0;
    $dataList = fetchPayrollListByYear($con,$date);
    foreach ($dataList as $data) {
        $billMonth = (int)$data['allowance']+(int)$data['claims']+(int)$data['commissions']+(int)$data['ot']+(int)$data['bonus'];
        $sum = $sum + (int)$billMonth;
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
