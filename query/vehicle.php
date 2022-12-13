<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function addVehicle($con,$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo){
  $feedback=false;
  $query="INSERT INTO `vehicles` (`type`,`brand`,`category`,`driver`,`number`) VALUES (?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssss',$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}

function updateVehicle($con,$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo,$vehicleId){
  $feedback=false;
  $query="UPDATE `vehicles` SET `type`=?,`brand`=?,`category`=?,`driver`=?,`number`=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssssi',$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo,$vehicleId);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}


//Type
function addVehicleType($con,$vehicleType){
  $result=false;
  $query="INSERT INTO `vehicletype` (`type`) VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$vehicleType);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}
function fetchVehiclesList($con){
  $dataList = array();
  $temp=1;
  $query="SELECT * FROM vehicles WHERE ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$temp);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  foreach($dataList as &$item){
    $item['driver']=trim($item['driver']);
    if(!empty($item['driver'])){
    $id=$item['driver'];
    $query2= "SELECT fullName FROM organizationuser WHERE staffId=?";
    $stmt1=mysqli_prepare($con,$query2);
    mysqli_stmt_bind_param($stmt1,'i',$id);
    mysqli_stmt_execute($stmt1);
    if($result1 = mysqli_stmt_get_result($stmt1)){
    while($row1=$result1->fetch_assoc()){
      $item['driver']=$row1['fullName'];
    }}}
  }

  mysqli_stmt_close($stmt);
  return $dataList;
}
function fetchVehicleDetailById($con,$vehicleId){
  $dataList = array();
  $query="SELECT *,brand AS vbrand, driver AS fullName FROM vehicles WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$vehicleId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList=$row;
    $staffId=&$row['fullName'];
    if(!empty($staffId)){
      $query2= "SELECT fullName FROM organizationuser WHERE staffId=?";
      $stmt1=mysqli_prepare($con,$query2);
      mysqli_stmt_bind_param($stmt1,'i',$staffId);
      mysqli_stmt_execute($stmt1);
      if($result1 = mysqli_stmt_get_result($stmt1)){
        while($row1=$result1->fetch_assoc()){
          $item['fullName']=$row1['fullName'];
        }}}
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}


function fetchVehicleType($con){
$dataList = array();
$temp=1;
$query="SELECT * FROM vehicletype WHERE ?";
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

function deleteVehicleType($con,$vehicleId){
  $success=false;

  $query="DELETE FROM vehicletype WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$vehicleId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateVehicleType($con, $vehicleType, $vehicleId){
  $success=false;
  $query="UPDATE vehicletype SET type=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$vehicleType,$vehicleId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

//Brand
function addVehicleBrand($con,$vehicleBrand){
  $result=false;
  $query="INSERT INTO `vehiclebrand` (`brand`) VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$vehicleBrand);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}

function fetchVehicleBrand($con){
$dataList = array();
$temp=1;
$query="SELECT * FROM vehiclebrand WHERE ?";
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

function deleteVehicleBrand($con,$vehicleBrandId){
  $success=false;

  $query="DELETE FROM vehiclebrand WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$vehicleBrandId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateVehicleBrand($con, $vehicleBrand, $vehicleBrandId){
  $success=false;
  $query="UPDATE vehiclebrand SET brand=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$vehicleBrand,$vehicleBrandId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

//Category
function addVehicleCategory($con,$vehicleCategory){
  $result=false;
  $query="INSERT INTO `vehiclecategory` (`category`) VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$vehicleCategory);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}

function fetchVehicleCategory($con){
$dataList = array();
$temp=1;
$query="SELECT * FROM vehiclecategory WHERE ?";
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
function vehicleDriverList($con,$driver){
  $success=false;

  $query="SELECT * FROM organizationuser WHERE staffDesignation LIKE '".$driver."'";
  $dataList = array();
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;

}
function deleteVehicleCategory($con,$vehicleCategoryId){
  $success=false;

  $query="DELETE FROM vehiclecategory WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$vehicleCategoryId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function deleteVehicle($con, $productBrandId){
  $success=false;

  $query="DELETE FROM vehicles WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$productBrandId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateVehicleCategory($con, $vehicleCategory, $vehicleCategoryId){
  $success=false;
  $query="UPDATE `vehiclecategory` SET `category`=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$vehicleCategory,$vehicleCategoryId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function fetchVehicleNumber($con){
  $dataList = array();
  $temp=1;
  $query="SELECT * FROM vehicles WHERE ?";
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

function fetchVehicleListBySearch($con,$e){
  $dataList = array();
  $temp=1;
  ob_start();
  $query="SELECT * FROM vehicles WHERE ?".$e;
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$temp);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)){
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);}
  ob_clean();
  ob_end_clean();
  return $dataList;
};

function getVehicleByVehicleNumber($con,$vehicleNo){
  $dataList = array();
  $temp=1;
  ob_start();
  $query="SELECT * FROM vehicles WHERE `number`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$vehicleNo);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)){
    while($row=$result->fetch_assoc()){
      $dataList=$row;
    }
    mysqli_stmt_close($stmt);}
  ob_clean();
  ob_end_clean();
  return $dataList;
}

?>
