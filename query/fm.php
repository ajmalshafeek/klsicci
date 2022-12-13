<?php
$config=parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function getMyDirectry($con){
    $dataList=array();
    $query="SELECT * FROM fm WHERE access=0 AND uid=".$_SESSION['userid']." AND utype=1";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;

        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}

function getSharedFolderList($con){
    $dataList=array();
    $query="SELECT * FROM fsm WHERE access=1 AND sid=".$_SESSION['userid']." AND utype=1 ORDER BY ftype DESC";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}
function fetchFolderClientEmail($con){
    $dataList=array();
    $query="SELECT fm.uid, fm.path, clientuser.email FROM clientuser, fm WHERE fm.uid=clientuser.id;";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}
function fetchOrgEmail($con){
    $dataList=array();
    $query="SELECT supportEmail FROM `organization`";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}