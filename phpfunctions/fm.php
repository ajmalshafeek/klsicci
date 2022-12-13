<?php
$config=parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/fm.php");

function getMyFiles(){
    $path="";
    $con=connectDb();
    $res=getMyDirectry($con);
    foreach ($res as $arrayList){
       $path=$arrayList['path'];
    }
    $_SESSION['fm']=$path;
}
function getSharedFolders(){
    $path=array();
    $con=connectDb();
    $res=getSharedFolderList($con);
    foreach ($res as $arrayList){
        $path[]=$arrayList;
    }
    $_SESSION['fsm']=$path;
}
function getFolderClientEmails(){
    $list=array();
    $con=connectDb();
    $list=fetchFolderClientEmail($con);

    return $list;
}
function getOrgEmails(){
    $list=array();
    $con=connectDb();
    $list=fetchOrgEmail($con);
    return $list;
}
?>