<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

function insertService($con, $service){

  $query="INSERT INTO services (service) VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$service);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchAllServices($con){
  $dataList=array();

  $query="SELECT * FROM services WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchServiceById($con,$serviceId){
  $query="SELECT * FROM services WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$serviceId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function updateServiceById($con,$service,$serviceId){
  $success=false;
  $query="UPDATE services SET service=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$service,$serviceId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteServiceById($con,$serviceId){
  $success=false;

  $query="DELETE FROM services WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$serviceId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function fetchServicesListAll($con){
  $dataList=array();

  $query="SELECT * FROM services WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)){
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function insertServiceDesc($con,$serviceId,$item,$description,$unitPrice,$quantity){

  $query="INSERT INTO servicesdescription (serviceId,item,description,unitPrice,quantity) VALUES (?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isssi',$serviceId,$item,$description,$unitPrice,$quantity);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchServiceDescListByServiceId($con,$serviceId){
  $dataList=array();

  $query="SELECT * FROM servicesdescription WHERE serviceId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$serviceId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function deleteServiceDescById($con,$serviceDescId){
  $success=false;

  $query="DELETE FROM servicesdescription WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$serviceDescId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateServiceByServiceDescId($con,$item,$description,$unitPrice,$quantity,$serviceDescId){
  $success=false;
  $query="UPDATE servicesdescription SET item=?, description=?, unitPrice=?,quantity=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssii',$item,$description,$unitPrice,$quantity,$serviceDescId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function fetchServiceDescById($con,$serviceDescId){
  $query="SELECT * FROM servicesdescription WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$serviceDescId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}
?>
