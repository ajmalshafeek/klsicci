<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/nortification.php");

function monthlySales(){
    $con=connectDb();
$result=fetchmonthlySales($con);
return $result;
}

function monthlyStoreSales(){
    $con=connectDb();
$result=fetchMonthlyStoreSales($con);
return $result;
}

function yearSalesReport(){
    $con=connectDb();
$result=fetchYearlyReportSales($con);
return $result;
}

function subscriptionEndGet(){
    $con=connectDb();
$result=subscriptionEndFetch($con);
return $result;
}
function noSubscriotionGet(){
    $con=connectDb();
$result=noSubscriotionFetch($con);
return $result;
}


if(isset($_GET['newrequest'])){
    $con = connectDb();
    $data = fetchRequestCountForDS($con);
    $_SESSION['newrequestNor']=$data;
    echo $data;
}
if(isset($_GET['potential'])){
    $con = connectDb();
    $data = fetchPotentialCountForDS($con);
    $_SESSION['potentialNor']=$data;
    echo $data;
}

if(isset($_GET['order'])){
    $con=connectDb();
    $result=orderNorificationSales($con);
    echo $result;
}

if(isset($_GET['member'])){
    $con=connectDb();
    $result=fetchRegisterMembers($con);
    echo $result;
}


function orderSalesCount(){
    $con=connectDb();
    $result=orderNorificationSales($con);
    return $result;
}


function RegisterMembers(){
    $con=connectDb();
$result=fetchRegisterMembers($con);
return $result;
}

function dsInvoiceUnpaid()
{
    $con = connectDb();
    $sdata="";

    $dataList = fetchInvoiceUnpaidListForDS($con);
    if ($dataList == null) {
        echo "<center><h5 style='padding: 20px'>No Invoice generated within 3 month</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<div><center><h3>UnPaid Invoice</h3></center></div>";
        $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No.\n";
        $table .=  "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Customer\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Invoice No.\n";
        $table .=   "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Inv. Date\n";
        $table .=   "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $table .= "<body>";

        $i=1;
        foreach ($dataList as $data) {

            $table .= "<tr>";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['customerName'];
            $table .=  "</td>";

            $table .=  "<td>";
            $table .=  sprintf("%09d", $data['invoiceNo']);
            $table .=  "</td>";

            $table .=  "<td>";
            $date=date_create($data['invoiceDate']);
            $table .=    date_format($date,"Y-m-d");
            $table .=  "</td>";

            $table .= "</tr>";
            $i++;
        }
        $table .= "</body>";
        $table .= "</table>";
        $table .= "</div>";

        echo $table;
    }
}


function dsInvoiceC1Unpaid()
{
    $con = connectDb();
    $sdata="";

    $dataList = fetchInvoiceUnpaidC1ListForDS($con);
    if ($dataList == null) {
        echo "<center><h5 style='padding: 20px'>No Invoice generated within 3 month</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<div><center><h3>UnPaid My Invoice</h3></center></div>";
        $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No.\n";
        $table .=  "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Customer\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Invoice No.\n";
        $table .=   "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Inv. Date\n";
        $table .=   "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $table .= "<body>";

        $i=1;
        foreach ($dataList as $data) {

            $table .= "<tr>";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['customerName'];
            $table .=  "</td>";

            $table .=  "<td>";
            $table .=  sprintf("%09d", $data['invoiceNo']);
            $table .=  "</td>";

            $table .=  "<td>";
            $date=date_create($data['invoiceDate']);
            $table .=    date_format($date,"Y-m-d");
            $table .=  "</td>";

            $table .= "</tr>";
            $i++;
        }
        $table .= "</body>";
        $table .= "</table>";
        $table .= "</div>";

        echo $table;
    }
}


function dsTripInvoiceUnpaid()
{
    $con = connectDb();
    $sdata="";
    $dataList = fetchTripInvoiceUnpaidListForDS($con);
    if ($dataList == null) {
        echo "<center><h5 style='padding: 20px'>No Invoice generated within 3 month</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<div><center><h3>UnPaid Trip Invoice</h3></center></div>";
        $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No.\n";
        $table .=  "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Customer\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Invoice No.\n";
        $table .=   "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Inv. Date\n";
        $table .=   "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $table .= "<body>";

        $i=1;
        foreach ($dataList as $data) {

            $table .= "<tr>";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['customerName'];
            $table .=  "</td>";

            $table .=  "<td>";
            $table .=  sprintf("%09d", $data['invoiceNo']);
            $table .=  "</td>";

            $table .=  "<td>";
            $date=date_create($data['invoiceDate']);
            $table .=    date_format($date,"Y-m-d");
            $table .=  "</td>";

            $table .= "</tr>";
            $i++;
        }
        $table .= "</body>";
        $table .= "</table>";
        $table .= "</div>";

        echo $table;
    }
}



?>