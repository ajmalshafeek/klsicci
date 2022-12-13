<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

function insertPOSupplier($con,$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email){

  $feedback = false;

  $query="INSERT INTO posuppliers (supplierName,contactPerson,contactNumber,address1,address2,zipCode,city,state,fax,email) VALUES (?,?,?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssssss',$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchSupplierListAll($con){
  $dataList=array();

  $query="SELECT * FROM posuppliers WHERE 1 ORDER BY id DESC";
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

function fetchSupplierById($con,$supplierId){
  $query="SELECT * FROM posuppliers WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$supplierId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function updateSupplierDetails($con,$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email,$supplierId){
  $success=false;
  $query="UPDATE posuppliers SET supplierName=?,contactPerson=?,contactNumber=?,address1=?,address2=?,zipCode=?,city=?,state=?,fax=?,email=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssssssi',$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email,$supplierId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteSupplierById($con,$supplierId){
  $success=false;

  $query="DELETE FROM posuppliers WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$supplierId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

// `pogenerated` table
function insertPurchaseOrder($con,$supplierId,$shippingVia,$deliveryDate,$poDate,$remarks,$fileName){

  $query="INSERT INTO pogenerated (supplierId,shippingVia,deliveryDate,poDate,remarks,fileName) VALUES (?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isssss',$supplierId,$shippingVia,$deliveryDate,$poDate,$remarks,$fileName);
  if(mysqli_stmt_execute($stmt)){
    $poId = mysqli_insert_id($con);
  }
  mysqli_stmt_close($stmt);

  return $poId;
}

function fetchPOListAll($con){
  $dataList=array();

  $query="SELECT * FROM pogenerated WHERE 1 ORDER BY id DESC";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchPOGeneratedById($con,$poId){
  $query="SELECT * FROM pogenerated WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$poId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function deletePOById($con,$poId){
  $success=false;

  $query="DELETE FROM pogenerated WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$poId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
//`pogenerateditemlist`
function insertPOItemList($con,$pogeneratedId,$product,$qty,$price){

  $query="INSERT INTO pogenerateditemlist (pogeneratedId,product,qty,price) VALUES (?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isss',$pogeneratedId,$product,$qty,$price);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}
?>
