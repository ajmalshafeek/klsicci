<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function saveMaintenanceFor($con,$maintenance){
    $feedback=false;
    $query="INSERT INTO `maintenancefor` (`maintenance`) VALUES (?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'s',$maintenance);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;
}
function updateMaintenanceFor($con, $maintenanceFor, $maintenanceForId){
    $feedback=false;
    $query="UPDATE `maintenancefor` SET `maintenance`=? WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'si',$maintenanceFor,$maintenanceForId);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;
}
function fetchMaintenanceFor($con){
    $dataList = array();
    $temp=1;
    $query="SELECT * FROM maintenancefor WHERE ?";
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
function fetchMaintenanceForById($con,$maintenancefor){
    $dataList = array();
    $temp=1;
    $query="SELECT * FROM maintenancefor WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$maintenancefor);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}

function saveMaintenance($con,$date,$vehicleType,$vehicleno,$maintenanceFor,$nextDate,$reminder,
                         $jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status){
    $feedback=false;
    $query="INSERT INTO `maintenance`(`date`, `vehicle_type`, `vehicle_no`, `maintenance`, `next_date`, `reminder`, `jobNo`, `invoiceNo`, `workshop`, `description`, `remarks`, `amount`, `mstatus`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'sssssssssssdi',$date,$vehicleType,$vehicleno,$maintenanceFor,$nextDate,$reminder,
        $jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;
}

function deleteMaintenanceFor($con,$maintenanceForId){
    $success=false;

    $query="DELETE FROM maintenancefor WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$maintenanceForId);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);

    return $success;
}
function fetchMaintenanceList($con){
    $dataList = array();
    $temp=1;
    $query="SELECT * FROM maintenance WHERE ?";
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

function getMaintenanceDetailById($con,$maintenanceId){
    $dataList = array();
    $temp=1;
    $query="SELECT * FROM maintenance WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$maintenanceId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}
function updateMaintenance($con, $date, $vehicletype,$vehicleno,$maintenanceFor,$nextDate,$reminder,$jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status,$mid){
    $feedback=false;
    $query="UPDATE `maintenance` SET `date`=?,`vehicle_type`=?,`vehicle_no`=?,`maintenance`=?,`next_date`=?,`reminder`=?, `jobNo`=?, `invoiceNo`=?, `workshop`=?, `description`=?, `remarks`=?, `amount`=?, `mstatus`=? WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'sssssssssssdii',$date, $vehicletype,$vehicleno,$maintenanceFor,$nextDate,$reminder,
        $jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status,$mid);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;

}
function deleteMaintenance($con,$mid){
    $query="DELETE FROM maintenance WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$mid);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}
function fetchMaintenanceReport($con,$search,$other){

    if(!empty($other)){
        $query='SELECT * FROM `bill` WHERE dateBill LIKE "%'.$search.'%" '.$other;
    }else{
        $query='SELECT * FROM `bill` WHERE dateBill LIKE "%'.$search.'%" ';
    }
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
function fetchMaintenanceReportById($con,$id){
         $query='SELECT * FROM `bill` WHERE id=? ';
    $dataList = array();
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}

function fetchBillCategoryById($con,$id){
    $dataList = "";
    $query='SELECT category FROM `billcategory` WHERE id=?';
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList=$row['category'];
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}
function fetchBillSubCategoryById($con,$id){
    $dataList = "";
    $query='SELECT subcategory FROM `billsubcategory` WHERE id=?';
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList=$row['subcategory'];
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}
function getDriverEmail($con,$vehicleno){
    $dataList="";
    $query='SELECT `driver` FROM `vehicles` WHERE `number` LIKE "%'.$vehicleno.'%" LIMIT 1';
    $stmt=mysqli_prepare($con,$query);
    //mysqli_stmt_bind_param($stmt,'s',$vehicleno);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $d1=$row['driver'];
            $query1='SELECT `fullName`, `email` FROM `organizationuser` WHERE staffId=?';
            $stmt1=mysqli_prepare($con,$query1);
            mysqli_stmt_bind_param($stmt1,'i',$d1);
            mysqli_stmt_execute($stmt1);
            if($result1 = mysqli_stmt_get_result($stmt1)){
                while($data=$result1->fetch_assoc()){
                    $dataList=$data;
                }
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}
}

function fetchVehicleNumberFromBill($con){
    $dataList = array();
    $query='SELECT `number` FROM `vehicles` GROUP BY `number`';
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    if($result = mysqli_stmt_get_result($stmt)){
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);}
    return $dataList;
}
function fetchMaintenanceListForDS($con){
    $query='SELECT * FROM `maintenance` WHERE `next_date` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY ID DESC';
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

?>