<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

function insertProduct($con,$brand,$model,$producttype,$serialNum,$remarks){
  $feedback = false;
  $query="INSERT INTO product (brand,model,producttype,serialNum,remarks)
  VALUES (?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssss',$brand,$model,$producttype,$serialNum,$remarks);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;

}

function insertProductStore($con, $title,$description, $brand, $model, $producttype, $sku, $quantity, $price, $storeImg1, $storeImg2, $storeImg3,$uploadDocName,$cid){
  $feedback = false;
  $lastId=0;
  $query="INSERT INTO product (title,description,brand,model,producttype)
  VALUES (?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssss',$title,$description,$brand,$model,$producttype);
  if(mysqli_stmt_execute($stmt)){
    $lastId= mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
  }


  $query="INSERT INTO productstore (pid, sku, quantity, price, img1, img2, img3, doc, cid)
  VALUES (?,?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isidssssi',$lastId, $sku, $quantity, $price, $storeImg1, $storeImg2, $storeImg3,$uploadDocName,$cid);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}
function insertProductWhouse($con,$brand,$model,$capacity,$producttype,$location,$ponumber,$serialNum,$remarks){
  $feedback = false;

  $query="INSERT INTO product (brand,model,capacity,producttype,`location`,ponumber,serialNum,remarks)
  VALUES (?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssss',$brand,$model,$capacity,$producttype,$location,$ponumber,$serialNum,$remarks);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchProductListAll($con){
  $dataList=array();
  $vendorCreated=0;
  $query="";
  if($_SESSION['orgType']==8){
    $query="SELECT * FROM product, productstore WHERE product.id = productstore.pid";}
  elseif($_SESSION['orgType']==3){
    $query="SELECT * , (SELECT clientcompany.name AS cname FROM `clientcompany` WHERE `clientcompany`.id=`productstore`.cid) AS cname FROM `product`, `productstore` WHERE `product`.id = `productstore`.pid";

  }else{
    $query="SELECT * FROM product WHERE 1";
  }

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

function fetchProductListSearch($con,$data){
  $dataList=array();
  $vendorCreated=0;
  $query="SELECT * FROM product
  WHERE ".$data."1";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $count=0;
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
      $count++;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
function fetchProductStoreListSearch($con,$data){
  $dataList=array();
  $query="SELECT * FROM storeorder WHERE ".$data." ? ORDER BY id DESC";
  $stmt=mysqli_prepare($con,$query);
  $id=1;
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $count=0;
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
    $count++;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
function getphoneaddress($con,$cid){
  $dataList=array();
  $query="SELECT * FROM clientcompany WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$cid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $count=0;
  while($row=$result->fetch_assoc()){
    $dataList=$row;
    $count++;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClientProductListById($con,$clientId){
  $dataList=array();
  $query="SELECT * FROM clientproduct WHERE companyId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$clientId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchClientProductRowByProductId($con,$productId){
  $query="SELECT * FROM clientproduct WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$productId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchClientProductListByProductIdValue($con,$productId){
  $dataList=array();
  $query="SELECT * FROM clientproduct WHERE productId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$productId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
function fetchStoreOrderNotes($con, $oid){
  $dataList=array();
  $query="SELECT notes, manager_note AS mgr, `status` FROM storeorder WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$oid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}
function fetchStoreReportList($con,$qvar){

$dataList=array();
  $query="SELECT * FROM storeorder WHERE ?".$qvar;
  $num=1;
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$num);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}
function fetchStoreItemsListReport($con,$id){

$dataList=array();
  $query='SELECT od.pid, p.producttype, p.description, ps.quantity AS stock, od.quantity as qty FROM `storeorderlist` AS od, `productstore` AS ps, `product` AS p WHERE od.pid=ps.pid AND od.pid=p.id AND od.oid=?';
    $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);
  return $dataList;
}



function fetchProductListById($con,$productId){
  $query="";
  if($_SESSION['orgType']==8){
    $query="SELECT `product`.*, `p`.`sku`, `p`.`quantity`, `p`.`price` FROM `product`, `productstore` AS `p` WHERE `product`.id = `p`.pid AND `p`.`pid`= ?";
  }else if($_SESSION['orgType']==3){
    $query="SELECT `product`.*, `p`.`sku`, `p`.`quantity`, `p`.`price`,`p`.`cid` FROM `product`, `productstore` AS `p` WHERE `product`.id = `p`.pid AND `p`.`pid`= ?";
  }
  else{
  $query="SELECT * FROM product WHERE id=?";
  }
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$productId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchProductOrderListById($con,$oid){
$dataList=array();

  $query="SELECT * FROM `storeorderlist` WHERE `oid`= ?";

  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$oid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
      $dataList[]=$row;
    }
  mysqli_stmt_close($stmt);
  return $dataList;
}


function fetchProductSentListById($con,$donumber){
  $dataList[]=array();
  $query="SELECT * FROM product WHERE donumber=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$donumber);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
require_once("../phpfunctions/organization.php");
$orgId = $_SESSION['orgId'];
$bannerName = bannerName($orgId);
//echo $bannerName;

  //$row=$result->fetch_assoc();
  $table='<table width="100%" class="senttable"><tbody>';
  $table.='<tr><td colspan="2"><img src="../../resources/2/myOrg/banner/'.$bannerName.'" width="100%"></td></tr>';
  $table.='<tr><th><label>Sent Out For</label></th>';
  $count=0;
  while($row=$result->fetch_assoc()){
    if($count==0){
      $table.='<th><label>'.$row['contract'].'</label></th></tr>';
      $table.='<tr><td><label>Sent Out Date</label></td><td><label>'.substr($row['dodate'],0,10).'</label></td></tr>';
      $table.='<tr><td><label>Client</label></td><td><label>'.$row['doto'].'</label></td></tr>';
      $table.='<tr><td><label>D/O No.</label></td><td><label>'.$row['donumber'].'</label></td></tr>';
       $table.='<tr><td style="vertical-align:top;"><label>Details</label></td><td><label>';
       $table.=$row['producttype'].' SR No: '.$row['serialNum'];
     }
    if($count>0){
       $table.=', <br />'.$row['producttype'].''.((isset($row['capacity']))?('('.$row['capacity']).')':'').' SR No: '.$row['serialNum'];
    }
    $dataList[]=$row;
    $count++;
  }
  $table.="</label></td></tr></tbody</table>";
  mysqli_stmt_close($stmt);
  if($count>0){
  $_SESSION['sentouttable']=$table;
  return $table;}
  return "<h5>details Not Found</h5>";
}

function fetchProductListBySerialNum($con,$serialNum){
  $query="SELECT * FROM product WHERE `serialNum`=? ";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$serialNum);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}
function getOrderDetailsForAdminID($con,$id){
  $datalist=array();
  $query="SELECT * FROM  `storeorder` WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$id);
  mysqli_stmt_execute($stmt);
  if($result = mysqli_stmt_get_result($stmt)){
  while($row=$result->fetch_assoc()){
    $datalist=$row;
  }
  mysqli_stmt_close($stmt);}
return $datalist;
}


function deleteClientProductById($con,$clientproductId){
  $success=false;

  $query="DELETE FROM clientproduct WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$clientproductId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updateProductById($con,$brand,$producttype,$model,$serialNum,$remarks,$productId){
  $success=false;
  $query="UPDATE product SET brand=?,producttype=?,model=?,serialNum=?,remarks=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssssi',$brand,$producttype,$model,$serialNum,$remarks,$productId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function updateSentOutById($con,$donumber,$doto,$doincharge,$status,$productId){
    $success=false;
  $query="UPDATE product SET donumber=?,doto=?,doincharge=?,dodate=TIMESTAMP,status=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssi',$donumber,$doto,$doincharge,$status,$productId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function updateProductWhouseById($con,$brand,$producttype,$model,$capacity,$location,$ponumber,$serialNum,$remarks,$productId){
  $success=false;
  $query="UPDATE product SET brand=?,model=?,serialNum=?,remarks=?,producttype=?,capacity=?,location=?,ponumber=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssssi',$brand,$model,$serialNum,$remarks,$producttype,$capacity,$location,$ponumber,$productId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
function updateProductStoreByID($con,$title,$des, $brand, $producttype, $model, $sku, $qty, $price, $productId){
$success=false;
$query="UPDATE `product` SET `title`=?,`description`=?,`brand`=?,`model`=?,`producttype`=? WHERE `id`=?";
$stmt=mysqli_prepare($con,$query);
mysqli_stmt_bind_param($stmt,'sssssi',$title,$des,$brand,$model,$producttype,$productId);
if(mysqli_stmt_execute($stmt)){
  $query1="UPDATE `productstore` SET `sku`=?,`quantity`=?, `price`=? WHERE `pid` = ?";
  $stmt1=mysqli_prepare($con,$query1);
  mysqli_stmt_bind_param($stmt1,'sidi',$sku,$qty,$price,$productId);
  if(mysqli_stmt_execute($stmt1)){
  }
  $success=true;
}
  return $success;
}
function updateProductDocByID($con, $title, $des, $producttype, $qty, $price,$filePath,$uploadDocName,$cid, $productId){
  $success=false;
  $query="UPDATE `product` SET `title`=?,`description`=?,`producttype`=? WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssi',$title,$des,$producttype,$productId);
  if(mysqli_stmt_execute($stmt)){
   $temp="";
    if(!empty($filePath)){
      $temp=", `img1`='".$filePath."'";
    }
    if(!empty($uploadDocName)){
      $temp.=", `doc`='".$uploadDocName."'";
    }
    $query1="UPDATE `productstore` SET `quantity`=?, `price`=?, `cid`=?".$temp." WHERE `pid` = ?";
    $stmt1=mysqli_prepare($con,$query1);
    mysqli_stmt_bind_param($stmt1,'idii',$qty,$price,$cid,$productId);
    if(mysqli_stmt_execute($stmt1)){
    }
    $success=true;
  }
  return $success;
}

function updateStoreOrderByOrderid($con,$status,$managerNote,$id){
  $success=false;
  $query="UPDATE `storeorder` SET `status`=?, `manager_note`=CONCAT(if(`manager_note` is null ,'',`manager_note`), ?) WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isi',$status,$managerNote,$id);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function updateStoreNotesByOrderid($con,$managerNote,$id){
  $success=false;
  $query="UPDATE `storeorder` SET `manager_note`=CONCAT(if(`manager_note` is null ,'',`manager_note`), ?) WHERE `id`=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$managerNote,$id);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteProductById($con,$productId){
  $success=false;

  $query="DELETE FROM product WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$productId);
  if(mysqli_stmt_execute($stmt)){
    if($_SESSION['orgType']==8||$_SESSION['orgType']==3){
    $query1="DELETE FROM `productstore` WHERE `pid` = ?";
    $stmt1=mysqli_prepare($con,$query1);
    mysqli_stmt_bind_param($stmt1,'i',$productId);
    if(mysqli_stmt_execute($stmt1)){
    }}
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function insertBrand($con,$brand){
    $success=false;

    $query="INSERT INTO productbrand(brand)
  VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$brand);
  if(mysqli_stmt_execute($stmt)){
    $success = true;
  }
  mysqli_stmt_close($stmt);
    return $success;
}

function checkBrand($con,$brand){
    $rstat=false;
  $query="SELECT * FROM productbrand WHERE brand=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$brand);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)==0){$rstat=true;}
  mysqli_stmt_close($stmt);

  return $rstat;
}

function updateBrand($con,$brand,$brandId){
  $success=false;
  $query="UPDATE productbrand SET brand=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$brand,$brandId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteBrand($con,$brand){
  $success=false;

  $query="DELETE FROM productbrand WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$brand);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
function fetchBrandList($con){

$query="SELECT * FROM productbrand
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

function insertProductType($con,$productType){
    $success=false;

    $query="INSERT INTO producttype(type)
  VALUES (?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$productType);
  if(mysqli_stmt_execute($stmt)){
    $success = true;
  }
  mysqli_stmt_close($stmt);
    return $success;
}

function checkProductType($con,$productType){
    $rstat=false;
  $query="SELECT * FROM producttype WHERE type=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$productType);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)==0){$rstat=true;}
  mysqli_stmt_close($stmt);

  return $rstat;
}

function updateProductType($con,$productType,$productTypeId){
  $success=false;
  $query="UPDATE producttype SET type=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$productType,$productTypeId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function deleteProductType($con,$productType){
  $success=false;

  $query="DELETE FROM producttype WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$productType);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
function fetchProductTypeList($con){

$query="SELECT * FROM producttype
  WHERE 1";
  $dataList=array();
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function updateSentOut($con,$num,$clientName,$value,$inCharge,$contract,$workertype,$worker,$vehicleno,$startdate,$enddate){
  $success=false;
    $dstatus="Sent Out";
     if(!isset($num)){
         $num=1;
     }
    $datetime=date("Y-m-d h:s:m");
  $query="UPDATE product SET doto=?,doincharge=?,dodate=?,`status`=?, donumber=?, contract=?, workertype=?, worker=?, vehicleno=?, startdate=?, enddate=? WHERE serialNum=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssssssss',$clientName,$inCharge,$datetime,$dstatus,$num,$contract, $workertype, $worker, $vehicleno, $startdate, $enddate,$value);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function checkSr($con,$value){
    $success=0;
    $instock="In Stock";
    $query="SELECT serialNum FROM product WHERE serialNum=? AND `status`=? AND 1";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'is',$value,$instock);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)==1){$success=1;}
    mysqli_stmt_close($stmt);
    return $success;
}

function checkSrNo($con,$value){
    $success=0;
    $query="SELECT serialNum FROM product WHERE serialNum=? AND 1";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$value);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)==0){$success=1;}
    mysqli_stmt_close($stmt);
    return $success;
}

function fetchDoNumber($con){

$query="SELECT MAX(donumber) FROM product";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $data=0;
  while($row=$result->fetch_assoc()){
    $data=(int)$row['MAX(donumber)']+1;
  }
  mysqli_stmt_close($stmt);
  return $data;
}

function fetchProductInstock($con,$format){
$dataList[]=array();
$query="SELECT producttype as \"product\", count(*) AS \"count\" FROM product
  WHERE `date` LIKE \"%".$format."%\" AND `status`=\"In Stock\" GROUP BY producttype";
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
function fetchProductOutstock($con,$format){
$dataList[]=array();
$query="SELECT producttype as \"product\", count(*) AS \"count\" FROM product
  WHERE `date` LIKE \"%".$format."%\" AND `status`=\"Sent Out\" GROUP BY producttype";
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
function fetchProductType($con){

$query="SELECT producttype as \"product\"  FROM `product` Group by producttype ORDER BY producttype";
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

function fetchProductOutstockContract($con,$format){
$dataList[]=array();
$query="SELECT  contract, count(*) AS \"count\" FROM product
  WHERE `date` LIKE \"%".$format."%\" AND `status`=\"Sent Out\" GROUP BY contract";
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

function fetchProductSentList($con){
  $dataList=array();
  $vendorCreated=0;
  $query="SELECT donumber, dodate, doto, doincharge,worker, workertype, contract FROM `product` GROUP BY donumber DESC" ;
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $count=0;
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
      $count++;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
