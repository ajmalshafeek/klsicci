<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

function fetchmonthlySales($con){
    $dataList=array();
  $query="SELECT DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY)) AS __timestamp,
MONTHNAME(pay) AS month,
       sum(price) AS `total`
FROM `memberTransanction`
WHERE end != 0
GROUP BY DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY))
ORDER BY month DESC
LIMIT 12;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function fetchMonthlyStoreSales($con){
    $dataList=array();
  $query="SELECT DATE(DATE_SUB(orderdate, INTERVAL DAYOFMONTH(orderdate) - ? DAY)) AS __timestamp,
MONTHNAME(orderdate) AS month,
COUNT(orderdate) AS count,
       sum(grandtotal) AS `total`
FROM storeorder
GROUP BY DATE(DATE_SUB(orderdate, INTERVAL DAYOFMONTH(orderdate) - ? DAY))
ORDER BY `orderdate` DESC
LIMIT 3;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function subscriptionEndFetch($con){
    $dataList=array();
  $query="SELECT DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY)) AS __timestamp,
MONTHNAME(pay) AS month,
       count(DISTINCT cid) AS `count`
FROM `memberTransanction`
WHERE ((DATEDIFF(curdate(), DATE(DATE_SUB(pay, INTERVAL + end month)))<0))
GROUP BY DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY))
ORDER BY month DESC
LIMIT 4;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}
function noSubscriotionFetch($con){
    $dataList=array();
  $query="SELECT DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY)) AS __timestamp,
       COUNT(*) AS count,
       MONTHNAME(pay) AS month
FROM `memberTransanction`
WHERE end = 0
GROUP BY DATE(DATE_SUB(pay, INTERVAL DAYOFMONTH(pay) - ? DAY))
ORDER BY month DESC
LIMIT 4;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function fetchYearlyReportSales($con){
    $dataList=array();
  $query="SELECT DATE(DATE_SUB(pay, INTERVAL DAYOFYEAR(pay) - ? DAY)) AS __timestamp,
       sum(price) AS `total`,
       year(pay) AS `year`
FROM `memberTransanction`
WHERE end != 0
GROUP BY DATE(DATE_SUB(pay, INTERVAL DAYOFYEAR(pay) - ? DAY))
ORDER BY `year` DESC
LIMIT 4;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function orderNorificationSales($con){
    $dataList=array();
  $query="SELECT count(`name`) AS `saleCount`
FROM storeorder where ?=?
LIMIT 10;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row['saleCount'];
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}


function fetchRequestCountForDS($con){
    $query='SELECT count(*) as `count` FROM `newrequest` WHERE `clientType`=1';
    $data = 0;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $data=$row['count'];
        }
        mysqli_stmt_close($stmt);}
    return $data;
}

function fetchPotentialCountForDS($con)
{
    $query = 'SELECT count(*) as `count` FROM `newrequest` WHERE `clientType`=0 ';
    $data = 0;
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    if ($result = mysqli_stmt_get_result($stmt)) {
        while($row=$result->fetch_assoc()){
            $data=$row['count'];
        }
        mysqli_stmt_close($stmt);
    }
    return $data;
}


function fetchRegisterMembers($con){
    $dataList=array();
  $query="SELECT count(id) AS `registerMembers`
FROM clientcompany WHERE ?=?
LIMIT 10;";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'ii',$id,$id);
  mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row['registerMembers'];
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}

function fetchInvoiceUnpaidListForDS($con){
    $query='SELECT customerName, invoiceDate, invoiceNo FROM `invoice` WHERE `invoiceDate` >= (CURDATE() - INTERVAL 3 MONTH ) AND status=1 ORDER BY ID DESC';
    $dataList = array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}

function fetchInvoiceUnpaidC1ListForDS($con){
    $query='SELECT customerName, invoiceDate, invoiceNo FROM `invoicec1` WHERE `invoiceDate` >= (CURDATE() - INTERVAL 3 MONTH ) AND status=1 ORDER BY ID DESC';
    $dataList = array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}

function fetchTripInvoiceUnpaidListForDS($con){
    $query='SELECT customerName, invoiceDate, invoiceNo FROM `tripinvoice` WHERE `invoiceDate` >= (CURDATE() - INTERVAL 3 MONTH ) AND status=1 ORDER BY ID DESC';
    $dataList = array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}
?>