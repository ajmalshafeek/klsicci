<?php
function insertBill($con,$category,$subcategory,$invoiceNum,$accountNum,$dateBill,$amount,$vehicleNo,$tripId,
                    $requestBy,$jobNo,$location,$description,$remarks,$status){
  $feedback = false;
  $query="INSERT INTO `bill`(`product`, `subcategory`, `invoiceNum`, `accountNum`, `dateBill`, `amount`, `number`,`tripid`,`requestBy`, `jobNo`, `location`, `description`, `remarks`, `status`)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iisssssisssssi',$category,
      $subcategory,
      $invoiceNum,
      $accountNum,
      $dateBill,
      $amount,
      $vehicleNo,
      $tripId,
      $requestBy,
      $jobNo,
      $location,
      $description,
      $remarks,
      $status);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}

function fetchBills($con){
  $dataList = array();
  $temp=1;
  $query="SELECT * FROM bill  ORDER BY `id` DESC";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt,'i',$temp);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}

function fetchBillCategoryAll($con){
  $dataList=array();

  $query="SELECT * FROM billcategory WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchBillListByDate($con,$dateYear,$dateMonth){
  $dataList = array();
  $query="SELECT * FROM bill WHERE YEAR(dateBill) = ? AND MONTH(dateBill) = ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }

  return $dataList;
}

function fetchBillListByYear($con,$dateYear){
  $dataList = array();
  $query="SELECT * FROM bill WHERE YEAR(dateBill) = ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$dateYear);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}

function deleteBill($con,$billId){
  $success=false;

  $query="DELETE FROM `bill` WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$billId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

//`billsubcategory` TABLE

function fetchBillSubcategoryByBillCategoryId($con,$billCategory){
  $dataList = array();
  $query="SELECT * FROM billsubcategory WHERE billCategoryId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$billCategory);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}

function fetchBillSubcategory($con){
  $dataList = array();
  $temp=1;
  $query="SELECT * FROM billsubcategory WHERE ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$temp);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}


function insertBillCategory($con,$billCategory){
  $feedback = false;
  $query="INSERT INTO `billcategory`(`category`)
  VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$billCategory);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}

function updateBillCategory($con, $billCategory, $billCategoryId){
  $success=false;
  $query="UPDATE `billcategory` SET `category`=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$billCategory,$billCategoryId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteBillCategory($con,$billCategoryId){
  $success=false;

  $query="DELETE FROM billcategory WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$billCategoryId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function insertExpenseFor($con,$billExpenseFor,$billCategory){
  $feedback = false;
  $query="INSERT INTO `billsubcategory`(`billCategoryId`,`subcategory`)
  VALUES (?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'is',$billCategory,$billExpenseFor);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}

function updateBillExpenseFor($con, $billCategoryId, $subcategory,$billExpenseForId){
  $success=false;
  $query="UPDATE `billsubcategory` SET `billCategoryId`=?,subcategory=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isi',$billCategoryId,$subcategory,$billExpenseForId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteBillExpenseFor($con,$billsubcategory){
  $success=false;

  $query="DELETE FROM `billsubcategory` WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$billsubcategory);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}




?>