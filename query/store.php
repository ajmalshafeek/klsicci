<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

function fetchStoreItems($con,$cat,$title){
  $dataList=array();
  if(!empty($cat)){
    $cat=' AND `p`.`producttype`="'.$cat.'"';
  }else{
    $cat="";
  }

  if(!empty($title)){
    $title=' AND `p`.`title` LIKE "%'.$title.'%"';
  }else{
    $title="";
  }
    $query = 'SELECT `p`.id, `p`.`title`,`p`.`description`, `p`.`brand` ,`p`.`model`, `p`.`producttype`, `s`.`sku`, `s`.`quantity`, `s`.`price`, `s`.`img1`,`s`.`img2`,`s`.`img3` FROM `product` AS `p` , `productstore` AS `s` WHERE `p`.`id` =`s`.`pid`'.$cat.$title;
  $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
function fetchStoreItemsPaging($con,$cat,$title,$view,$pages,$limit){
$dataList=array();
  $page = filter_var($pages, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
  $position = (($page-1) * $limit);
if(!empty($cat)){
  $cat=' AND `p`.`producttype`="'.$cat.'"';
}else{
  $cat="";
}
if(!empty($title)){
  $title=' AND `p`.`title` LIKE "%'.$title.'%"';
}else{
  $title="";
}
  $viewTemp="";
if(!empty($view)){
    switch ($view){
      case 2:{ $viewTemp=" ORDER BY `p`.id DESC";  break;}
      case 3:{ $viewTemp=' ORDER BY `p`.`title`  ASC'; break;}
      case 4:{ $viewTemp=' ORDER BY `p`.`title` DESC'; break;}
      case 5:{ $viewTemp=' ORDER BY `s`.`price`  ASC'; break;}
      case 6:{ $viewTemp=' ORDER BY `s`.`price` DESC';break;}
      default:{
        $viewTemp=" ORDER BY `p`.id ASC";
      }
    }
  }else{
  $viewTemp="";;
  }
$pagination= " LIMIT ".$position.", ?";
$query = 'SELECT `p`.id, `p`.`title`,`p`.`description`, `p`.`brand` ,`p`.`model`, `p`.`producttype`, `s`.`sku`, `s`.`quantity`, `s`.`price`, `s`.`img1`,`s`.`img2`,`s`.`img3` FROM `product` AS `p` , `productstore` AS `s` WHERE `p`.`id` =`s`.`pid` AND `s`.cid IN ('.$_SESSION['companyId'].",0) ".$cat.$title.$viewTemp.$pagination;
$stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$limit);
mysqli_stmt_execute($stmt);
if($result = mysqli_stmt_get_result($stmt)){
while($row=$result->fetch_assoc()){
  $dataList[]=$row;
}
  mysqli_stmt_close($stmt);
}


return $dataList;
}

function fetchStoreProductCount($con,$cat, $title){
$page=0;

if(!empty($cat)){
  $cat=' AND `p`.`producttype`="'.$cat.'"';
}else{
  $cat="";
}
if(!empty($title)){
  $title=' AND `p`.`title` LIKE "%'.$title.'%"';
}else{
  $title="";
}
    $query = 'SELECT COUNT(*) AS `page` FROM `product` AS `p` , `productstore` AS `s` WHERE `p`.`id` =`s`.`pid` AND `s`.cid IN (?,0) '.$cat.$title;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$_SESSION['companyId']);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){

        while($row=$result->fetch_assoc()){
          $page=$row['page'];

          mysqli_stmt_close($stmt);
        }
    }
    return $page;
}

function fetchStoreItem($con,$id){
  $list=array();

  $query = 'SELECT `p`.id, `p`.`title`,`p`.`description`, `p`.`brand` ,`p`.`model`, `p`.`producttype`, `s`.`sku`, `s`.`quantity`, `s`.`price`, `s`.`img1`,`s`.`img2`,`s`.`img3` FROM `product` AS `p` , `productstore` AS `s` WHERE `p`.`id` =`s`.`pid` AND `p`.`id`=?';
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)){
    while($row=$result->fetch_assoc()){
      $list=$row;
    }
    mysqli_stmt_close($stmt);
  }
  return $list;
}

function fetchClientAddressDetails($con,$cid){
  $query="SELECT `address1`,`address2`,`city`,`state`,`postalCode`,`contactNo`,`country` FROM clientcompany WHERE `id`=?";
  $dataList=array();
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$cid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}
function updateProfileDetails($con,$add1,$add2,$city,$state,$zip,$country,$id){
  $success=false;
  $query="UPDATE clientcompany SET address1=?,address2=?,city=?,`state`=?, postalCode=?, country=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssi',$add1,$add2,$city,$state,$zip,$country,$id);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function updateDetails($con,$title,$value,$id){
  $success=false;
  $query="UPDATE storeextra SET `title`=?,`value`=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssi',$title,$value,$id);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function setDefaultDetail($con,$id,$tag){
  $success=false;
  $active="active";
  $query="UPDATE `storeextra` SET `active`=? WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$active,$id);
  if(mysqli_stmt_execute($stmt)){
    $active="";
    $query1="UPDATE `storeextra` SET `active`=? WHERE `category`=? AND `id` NOT IN (?)";
    $stmt1=mysqli_prepare($con,$query1);
    mysqli_stmt_bind_param($stmt1,'ssi',$active,$tag,$id);
    if(mysqli_stmt_execute($stmt1)){
      $success=true;
    }
  }

  return $success;
}
function insertDetails($con,$title,$value,$cat,$cid){
  $success=false;
  $query="INSERT INTO `storeextra` (`cid`,`title`,`value`,`category`)
  VALUES (?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'isss',$cid,$title,$value,$cat);
    if(mysqli_stmt_execute($stmt)){
      $success = true;
    }
    mysqli_stmt_close($stmt);
    return $success;
  }
function deleteDetails($con,$id){
  $success=false;
  $query="DELETE FROM storeextra WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  if(mysqli_stmt_execute($stmt)){
    $success = true;
  }
  mysqli_stmt_close($stmt);
  return $success;
}

function getContactList($con,$cid){
  $dataList=array();
  $query="SELECT * FROM `storeextra` WHERE `cid`=? AND `category` LIKE 'contact'";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$cid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $dataList[] = $row;
    }
    mysqli_stmt_close($stmt);
  }
  return $dataList;
}
function getAddressList($con,$cid){
  $dataList=array();
  $query="SELECT * FROM `storeextra` WHERE `cid`=? AND `category` LIKE 'address'";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$cid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $dataList[] = $row;
    }
    mysqli_stmt_close($stmt);
  }
  return $dataList;
}

function getDeliveryContact($con,$cid,$cat){
  $dataList="";
  $query="SELECT * FROM `storeextra` WHERE `category` LIKE ? AND `cid`=? AND active='active'";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$cat,$cid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      if(isset($row['category'])){
      $dataList= $row['value'];}
      elseif(isset($row['category'])){
        $dataList= $row['value'];}
    }
    mysqli_stmt_close($stmt);
  }
  return $dataList;
}
function cartCheckout($con,$cid,$name,$address,$email,$contact,$cartTotal,$notes){
  $numberAsString = number_format($cartTotal, 2);
  $gradtotal=(double)$numberAsString;
  $order_id="";
  $query="INSERT INTO `storeorder` (`cid`,`name`,`address`,`email`,`phone`,`grandtotal`,`notes`)
  VALUES (?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'issssds',$cid,$name,$address,$email,$contact,$gradtotal,$notes);
  if(mysqli_stmt_execute($stmt)){
    $order_id = mysqli_insert_id($con);
  }
  mysqli_stmt_close($stmt);
  return $order_id;
}
function cartcheCkoutItem($con,$pid,$orderid,$src,$title,$price,$qty,$total){
  $success=false;
  $query="INSERT INTO `storeorderlist` (`pid`,`oid`,`img1`,`title`,`price`,`quantity`,`total`)
  VALUES (?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iissdid',$pid,$orderid,$src,$title,$price,$qty,$total);
  if(mysqli_stmt_execute($stmt)){
    $query1="UPDATE `productstore` SET `quantity`=`quantity`- ? WHERE `pid` = ?";
    $stmt1=mysqli_prepare($con,$query1);
    mysqli_stmt_bind_param($stmt1,'ii',$qty,$pid);
    if(mysqli_stmt_execute($stmt1)){

    }

    $success=true;
  }
  mysqli_stmt_close($stmt);
  return $success;
}
function orderStatusUpdate($con,$orderid,$status){
  ob_start();
  $success=false;
  $query="UPDATE `storeorder` SET `status`=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$status,$orderid);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);
  ob_clean();
  ob_end_clean();
  return $success;
}
function fetchQtyByID($con, $pid){
  $data=0;
  $query="SELECT quantity FROM `productstore` WHERE `pid`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$pid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $data = $row['quantity'];
    }
    mysqli_stmt_close($stmt);
  }
  return $data;
}
function fetchOrderDetailById($con,$oid,$cid){
 $datalist=array();
  $query="SELECT * FROM `storeorder` WHERE `id`=? AND `cid`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$oid,$cid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $datalist = $row;
    }
    mysqli_stmt_close($stmt);
  }
  return $datalist;
}
function fetchOrderItemDetails($con,$oid){
  $datalist=array();
  $query="SELECT * FROM `storeorderlist` WHERE `oid`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$oid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $datalist[] = $row;
    }
    mysqli_stmt_close($stmt);
  }
  return $datalist;
}
function fetchOrderItemCount($con,$oid){
  $datalist=0;
  $query="SELECT COUNT(*) AS `count` FROM `storeorderlist` WHERE `oid`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$oid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $datalist = $row['count'];
    }
    mysqli_stmt_close($stmt);
  }
  return $datalist;
}
function fetchOrderCount($con,$cid){
  $datalist=0;
  $query="SELECT COUNT(*) AS `count` FROM `storeorder` WHERE `cid`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$cid);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $datalist = $row['count'];
    }
    mysqli_stmt_close($stmt);
  }
  return $datalist;
}
function fetchOrderDetailByClient($con,$cid,$status,$orderMonth,$position,$limit){
  $datalist=array();
  $statusExtra="";
  if($status!=0){
    $statusExtra=" AND status=".$status;
  }
  if($orderMonth!=0){
    $statusExtra.=" AND orderdate LIKE '".$orderMonth."%'";
  }

  $pagination= " LIMIT ".$position.", ?";
  $query="SELECT * FROM `storeorder` WHERE `cid`=?".$statusExtra." ORDER BY `orderdate` DESC".$pagination;
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$cid,$limit);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)) {
    while ($row = $result->fetch_assoc()) {
      $datalist[] = $row;
    }
    mysqli_stmt_close($stmt);
  }
  return $datalist;
}

function savePassword($con, $cid, $password){
    $success=false;
    $query="UPDATE `clientuser` SET `password`='".$password."' WHERE `companyId`=".$cid;
    if (mysqli_query($con, $query)) {
        $success=true;
        }
    return $success;
}
function checkDefaultPassword($con,$username){
    $success=true;
    $password='12345678';
    $query="SELECT * FROM `clientuser` WHERE `username`=? AND password=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ss',$username,$password);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $success = false;
        }
    }
    return $success;
}


?>