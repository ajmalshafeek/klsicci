<?php
/**
 * Created by Shafeek Ajmal.
 **/
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function fetchPayrollReportList($con,$query){
    $dataList=array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
    return $dataList;
}

?>