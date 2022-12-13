<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function insertClaim($con,$staffId,$project,$claim,$status,$dateTimeClaim,$claimType,$number,$tripId){

  $query="INSERT INTO claim (staffId,project,claim,status,dateTimeClaim,claimtype,`number`,`tripid`) VALUES (?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ississsi',$staffId,$project,$claim,$status,$dateTimeClaim,$claimType,$number,$tripId);
  if(mysqli_stmt_execute($stmt)){
    $claimId = mysqli_insert_id($con);
  }
  mysqli_stmt_close($stmt);

  return $claimId;
}

function insertClaimFile($con,$claimId,$fileName){

  $feedback = false;

  $query="INSERT INTO claimfile (claimId,fileName) VALUES (?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'is',$claimId,$fileName);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchClaimListAll($con){
  $dataList=array();

  $query="SELECT * FROM claim
  WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClaimListById($con,$staffId){
  $dataList=array();

  $query="SELECT * FROM claim
  WHERE staffId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$staffId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClaimFileListByClaimId($con,$claimId){
  $dataList=array();
  $query="SELECT * FROM claimfile WHERE claimId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$claimId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClaimFileById($con,$id){
  $query="SELECT * FROM claimfile WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchClaimListSpec($con,$staffId,$project,$dateFrom,$dateTo,$status){
  $dataList=array();
  $query="SELECT * FROM claim WHERE 1=1 ";
  $paramType="";
  $paramList = array();


  if($staffId!=null){
    $query.="AND staffId=? ";
    $paramList[] = $staffId;
  }
  if($project!=null){
    $query.="AND project=? ";
    $paramList[]=$project;
  }

  if($dateFrom!=null && $dateTo!=null){
    $query.="AND dateTimeClaim between ? AND ? ";
    $paramList[] = $dateFrom;
    $paramList[] = $dateTo;
  }

  if($status!=null){
    $query.="AND status=? ";
    $paramList[] = $status;
  }

  $stmt=mysqli_prepare($con,$query);
  DynamicBindVariables($stmt, $paramList);
  //mysqli_stmt_bind_param($stmt,'iiss',$paramList);


  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClaimByClaimId($con,$claimId){
  $query="SELECT * FROM claim WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$claimId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function deleteClaimFileById($con,$claimFileId){
  $success=false;

  $query="DELETE FROM claimfile WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$claimFileId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateClaim($con,$claimId,$project,$claim,$status,$dateTimeClaim){
  $success=false;
  $query="UPDATE claim SET project=?,claim=?,status=?,dateTimeClaim=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssisi',$project,$claim,$status,$dateTimeClaim,$claimId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteClaimById($con,$claimId){
  $success=false;

  $query="DELETE FROM claim WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$claimId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateClaimStatusById($con,$claimId,$status){
  $success=false;
  $query="UPDATE claim SET status=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$status,$claimId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}


function fetchClaimListByDate($con,$dateYear,$dateMonth){
  $dataList = array();
  $query="SELECT * FROM claim WHERE status=3  AND YEAR(dateTimeClaim) = ? AND MONTH(dateTimeClaim) = ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }

  return $dataList;
}
function addClaimType($con,$claimType){
  $result=false;
  $query="INSERT INTO `claimcase` (`request`) VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$claimType);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}
function fetchClaimType($con){
$dataList = array();
$temp=1;
$query="SELECT * FROM claimcase WHERE ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$temp);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}
function deleteClaimType($con,$caseType){
  $success=false;

  $query="DELETE FROM claimcase WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$caseType);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
function updateProductType($con, $claimType, $claimTypeId){
  $success=false;
  $query="UPDATE claimcase SET request=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$claimType,$claimTypeId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;

}


?>
