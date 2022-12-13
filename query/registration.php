<?php
function checkIfCompanyExist($con,$company){
    $bool=true;
    $query="SELECT * FROM `clientcompany` WHERE `name`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$company);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $bool = false;
        }
        mysqli_stmt_close($stmt);
    }
    return $bool;
}
function checkIfEmailExist($con,$email){
    $temp=true;
    $query="SELECT * FROM `clientuser` WHERE `email`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$email);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $temp = 0;
        }
        mysqli_stmt_close($stmt);
    }
    return $temp;
}
function getPlanDetails($con,$price){
    $temp=array();
    $query="SELECT * FROM `membershiplan` WHERE `price`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'d',$price);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $temp = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $temp;
}
function createVarificationCode($con,$salt,$code,$email,$app){
    $bool=false;
    validationVarificationCode($con,$email);
    $query="INSERT INTO `resetpass` (`salt`, `key`, `userid`, `appuser`) VALUES (?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'sssi',$salt,$code,$email,$app);
    if(mysqli_stmt_execute($stmt)){
        $bool = true;
    }
    mysqli_stmt_close($stmt);

    return $bool;
}
function validationVarificationCode($con,$email){
    $query="SELECT * FROM `resetpass` WHERE `userid`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$email);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        while ($row = $result->fetch_assoc()) {
            $query1="DELETE FROM `resetpass` WHERE `userid`=?";
            $stmt1=mysqli_prepare($con,$query1);
            mysqli_stmt_bind_param($stmt1,'s',$email);
            if (mysqli_stmt_execute($stmt1)) {

            }
        }
        mysqli_stmt_close($stmt);
    }
}
function checkVerificationCode($con,$vcode,$email){
    $status=false;
    $query="SELECT * FROM `resetpass` WHERE `userid`=? AND `key`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ss',$email,$vcode);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)) {
        $status=true;
    }
    return $status;
}
function updateClientPassword($con,$email,$pass){
    $s=false;
    $query="UPDATE `clientuser` SET `password`=? WHERE `username`=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ss',$pass,$email);
    if(mysqli_stmt_execute($stmt)){
        $s=true;
        $query1="DELETE FROM `resetpass` WHERE `userid`=?";
        $stmt1=mysqli_prepare($con,$query1);
        mysqli_stmt_bind_param($stmt1,'s',$email);
        if (mysqli_stmt_execute($stmt1)) {
        }
    }
    mysqli_stmt_close($stmt);
    return $s;
}


function newStoreAccountRequest($con,$name,$company,$email,$country,$status,$register,$business,$incorp,$financialYear,$address1,$address2,$city,$postalCode,$state,$clientFaxNo,$clientContacNo,$clientType,$planId){
    $bool=0;
    $query="INSERT INTO `newrequest` (`name`, `company`, `email`, `country`, `status`,`register`,`business`,`incorp`,`financialYear`,`address1`,`address2`,`city`,`postalCode`,`state`,`faxNo`,`contactNo`,`clientType`,`mpid`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssssisssssssssssii',$name,$company,$email,$country,$status,$register,$business,$incorp,$financialYear,$address1,$address2,$city,$postalCode,$state,$clientFaxNo,$clientContacNo,$clientType,$planId);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    else{
        mysqli_error();
    }
    mysqli_stmt_close($stmt);

    return $bool;
}
function paymentUpdateNewUser($con,$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request){
    $bool=0;
    $query="INSERT INTO `memberTransanction` (`pay`, `end`, `payStatus`, `memberStatus`, `title`,`description`,`price`,`transactionId`,`tid`) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssiissdsi',$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    mysqli_stmt_close($stmt);

    return $bool;
}
function renuewalTransaction($con,$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request){
    $bool=0;
    $query="INSERT INTO `memberTransanction` (`pay`, `end`, `payStatus`, `memberStatus`, `title`,`description`,`price`,`transactionId`,`cid`) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssiissdsi',$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    mysqli_stmt_close($stmt);

    return $bool;
}

function newClientTransaction($con,$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request){
    $bool=0;
    $query="INSERT INTO `memberTransanction` (`pay`, `end`, `payStatus`, `memberStatus`, `title`,`description`,`price`,`transactionId`,`cid`) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssiissdsi',$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    mysqli_stmt_close($stmt);

    return $bool;
}

function paymentUpdateUser($con,$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request){
    $bool=0;
    $query="INSERT INTO `memberTransanction` (`pay`, `end`, `payStatus`, `memberStatus`, `title`,`description`,`price`,`transactionId`,`cid`) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ssiissdsi',$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$request);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    mysqli_stmt_close($stmt);

    return $bool;
}

function fetchRequestListForDS($con){
    $query='SELECT * FROM `newrequest` WHERE `clientType`=1 AND `requestTime` >= DATE(NOW()) - INTERVAL 7 DAY ORDER BY ID';
    $dataList = array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}


function deletNewClientById($con, $clientId){
    $success=false;

    $query="DELETE FROM `newrequest` WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$clientId);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}
function terminateRequest($con,$compId){

    $query='SELECT count(*) as `count` FROM `subscriptionCancelRequest` WHERE `cid`=?';
    $data = 0;
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$compId);
    mysqli_stmt_execute($stmt);

    if($result = mysqli_stmt_get_result($stmt)) {
        while($row=$result->fetch_assoc()){
            $data=$row['count'];
        }
        mysqli_stmt_close($stmt);
    }
    return $data;
}
function requestForTerminateFromClient($con,$compId,$notes){
    $bool=0;
    $status=0;
    $query="INSERT INTO `subscriptionCancelRequest` (`cid`, `Notes`, `status`) VALUES (?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'isi',$compId,$notes,$status);
    if(mysqli_stmt_execute($stmt)){
        $last_id = mysqli_insert_id($con);
        $bool = $last_id;
    }
    mysqli_stmt_close($stmt);
    return $bool;
}