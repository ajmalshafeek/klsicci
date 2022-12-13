<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function addTrip($con,$date,$clientid,$driverid,$vehicleNo,$placeDes,$shipment,$amount,
                 $collectionPoint, $deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus){
    $feedback=false;
    $last_id=0;
    $query="INSERT INTO `trip` (`date`,`client`,`truck_no`,`place`,`shipment_no`,`driver`,`amount`,`collectionPoint`,`deliveryPoint`,`remarks`,`diesel`,`toll`,`driverTrip`,`maintenance`,`paymentStatus`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssssssdsssiiiis',$date,$clientid,$vehicleNo,$placeDes,$shipment,$driverid,$amount,$collectionPoint, $deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
      $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $last_id;
}

function updateTrip($con,$date,$clientid,$driverid,$vehicleNo,$placeDes,$shipment,$amount,$collectionPoint, $deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus,$tripId){
    $feedback=false;
    $query="UPDATE `trip` SET `date`=?,`client`=?,`truck_no`=?,`place`=?,`shipment_no`=?,`driver`=?,`amount`=?, `collectionPoint`=?,`deliveryPoint`=?,`remarks`=?,`diesel`=?,`toll`=?,`driverTrip`=?,`maintenance`=?,`paymentStatus`=? WHERE id=?";
   // $query="UPDATE `trip` SET `date`=? ,`client`=? ,`truck_no`=? ,`place`=? ,`shipment_no`=? ,`driver`=? ,`amount`=? WHERE `id`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssssssdsssiiiisi',$date,$clientid,$vehicleNo,$placeDes,$shipment,$driverid,$amount,$collectionPoint,$deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus,$tripId);
    if(mysqli_stmt_execute($stmt)){
      $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;
  }

  function fetchTripList($con,$search){
    $dataList = array();
    $temp=1;
     $query='SELECT t.*, 
(SELECT `fullName` FROM `organizationuser` WHERE `staffId`=t.driver) AS fullName, 
(SELECT `name` FROM `clientcompany` WHERE `id`=t.client) AS name, 
(SELECT `type` FROM `vehicles` WHERE `number`=t.truck_no) AS truck 
FROM trip AS t WHERE 1=1 '.$search.' ORDER BY ID DESC';
    // $query="SELECT t.*, o.fullName, c.name FROM trip AS t, organizationuser AS o, clientcompany AS c WHERE ? AND c.id=t.client AND o.staffId=t.driver ORDER BY ID DESC";
    $stmt=mysqli_prepare($con,$query);
   // mysqli_stmt_bind_param($stmt,'i',$temp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
      $dataList[]=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
  }

function fetchTripListLastMonth($con){
    $dataList = array();
    $temp=1;
    $dateMonth=date("Y-m-d",strtotime("-1 month"));
    $dateMonth.= " AND `date` > '".$dateMonth."'";
    $query='SELECT t.*, 
(SELECT `fullName` FROM `organizationuser` WHERE `staffId`=t.driver) AS fullName, 
(SELECT `name` FROM `clientcompany` WHERE `id`=t.client) AS name, 
(SELECT `type` FROM `vehicles` WHERE `number`=t.truck_no) AS truck 
FROM trip AS t WHERE DATE(t.`date`) >= DATE(NOW()) - INTERVAL 30 DAY ORDER BY ID DESC ';
    //$query="SELECT t.*, o.fullName, c.name FROM trip AS t, organizationuser AS o, clientcompany AS c WHERE ? AND c.id=t.client AND o.staffId=t.driver AND DATE(`date`) >= DATE(NOW()) - INTERVAL 30 DAY ORDER BY ID DESC";
    $stmt=mysqli_prepare($con,$query);
  //  mysqli_stmt_bind_param($stmt,'i',$temp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}

function fetchTripListBySearch($con,$query){
    ob_start();
    $dataList = array();
    $temp=1;
    $query='SELECT *, 
(SELECT `fullName` FROM `organizationuser` WHERE `staffId`=trip.driver) AS fullName, 
(SELECT `name` FROM `clientcompany` WHERE `id`=trip.client) AS name, 
(SELECT `type` FROM `vehicles` WHERE `number`=trip.truck_no) AS truck 
FROM trip WHERE ".$query." ORDER BY ID DESC';
    $query="SELECT t.*, o.fullName, c.name FROM trip AS t, organizationuser AS o, clientcompany AS c WHERE ? AND c.id=t.client AND o.staffId=t.driver".$query." ORDER BY ID DESC" ;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$temp);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }else{
        $dataList="";
    }
    ob_clean();
    ob_end_clean();
    return $dataList;
}

  function fetchTripListById($con,$tripId){
    $dataList = array();
    $temp=1;
    $query="SELECT *, 
(SELECT `fullName` FROM `organizationuser` WHERE `staffId`=trip.driver) AS fullName, 
(SELECT `name` FROM `clientcompany` WHERE `id`=trip.client) AS name, 
(SELECT `type` FROM `vehicles` WHERE `number`=trip.truck_no) AS truck 
FROM trip WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$tripId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
      $dataList=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
  }
  function removeTrip($con,$tripId){
    $success=false;
    $query="DELETE FROM trip WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$tripId);
    if(mysqli_stmt_execute($stmt)){
      $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
  }

  function getClaimByTripId($con,$tripId){
      $dataList = array();
      $temp=1;
      $query="SELECT * FROM claim WHERE `tripid`=?";
      $stmt=mysqli_prepare($con,$query);
      mysqli_stmt_bind_param($stmt,'i',$tripId);
      mysqli_stmt_execute($stmt);
      if($result = mysqli_stmt_get_result($stmt)) {
          while ($row = $result->fetch_assoc()) {
              $dataList[] = $row;
          }
          mysqli_stmt_close($stmt);
      }else{
          $dataList="";
      }
      return $dataList;
  }
function getExpenseByTripId($con,$tripId){
    $dataList = array();
    $temp=1;
    $query="SELECT * FROM bill WHERE `tripid`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$tripId);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }else{
        $dataList="";
    }
    return $dataList;
}

function getBillSubCategoryNameById($con,$id){

    $dataList = "";
    $temp=1;
    $query="SELECT `subcategory` FROM `billsubcategory` WHERE `id`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList = $row['subcategory'];
        }
        mysqli_stmt_close($stmt);
    }else{
        $dataList="";
    }
    return $dataList;
}

function getTripAmount($con,$tripid){
    $dataList =array();
    $temp=1;
    $query="SELECT * FROM `trip` WHERE `id`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$tripid);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}
function getDriverName($con,$staffId){
    $dataList ="";
    $temp=1;
    $query="SELECT fullName FROM `organizationuser` WHERE `staffId`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$staffId);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList = $row['fullName'];
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;

}function getTripByVehicle($con,$vehicleNo){
    $dataList =array();
    $temp=1;
    $query="SELECT * FROM `trip` WHERE `truck_no`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$vehicleNo);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $dataList;
}
function getTripByVehicleAndDate($con,$vehicleNo,$q){
 //   ob_start();
    $dataList =array();
    $temp=1;
    $query="SELECT * FROM `trip` WHERE `truck_no`=?".$q;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$vehicleNo);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
 //   ob_clean();
 //   ob_end_clean();
    return $dataList;
}
function getMaintenanceDetailByDate($con,$vehicleNo,$q){
    ob_start();
    $dataList =array();
    $temp=1;
    $query="SELECT * FROM `trip` WHERE `truck_no`=?".$q." tripid IS NULL";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$vehicleNo);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $dataList[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    ob_clean();
    ob_end_clean();
    return $dataList;
}