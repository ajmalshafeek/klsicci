<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");

function uploadPDF($con, $uploadBy, $companyId, $uploadFileName){
    $feedback = false;
    $date = date('Y-m-d H:i:s');
    $query="INSERT INTO pdfsign (`uploadBy`,`user`,`pdfName`,`created_at`) VALUES (?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'siss',$uploadBy,$companyId,$uploadFileName,$date);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);

    return $feedback;
}
function getPdfList($con,$uploadBy,$useBy,$companyId,$status,$setPlace){
    $dataList=array();
    $query="";
            $query="SELECT * FROM `pdfsign` WHERE 1 ";

        if($uploadBy!=null){
            $query.="AND `uploadBy`='".$uploadBy."' ";
        }

        if($companyId!=null){
            $query.="AND `user`=".$companyId." ";
        }
    if($useBy!=null){
        $query.="AND `useBy`=".$useBy." ";
    }

        if($status!=null){
            $query.=" AND `status`=".$status." ";
        }
    if($setPlace!=null){
        $query.=" AND `setPlace`=".$setPlace." ";
    }

        $query.=" ORDER BY updated_at DESC ";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
    }
    mysqli_stmt_close($stmt);
    //debug un list
    return $dataList;

}
function fechpdfdetails($con,$id){
    $dataList=array();
    $query="";
            $query="SELECT * FROM pdfsign WHERE 1 ";
        if($id!=null){
            $query.="AND id='".$id."' ";
        }
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row;
    }
    mysqli_stmt_close($stmt);
    //debug un list
    return $dataList;

}


function getSignatoryName($con,$id){
    $dataList="";
    $query="SELECT * FROM client_signatory WHERE 1 ";
    $query.="AND id='".$id."' ";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row['name'];
    }
    mysqli_stmt_close($stmt);
    //debug un list
    return $dataList;

}

function saveSignedPdf($con,$pfid,$file){
    $success=false;
    $query="UPDATE `pdfsign` SET `signPdf`=?, status=0 WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'si',$file,$pfid);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}
function updateMuPDF($con,$pdfName,$file,$pfid){
    $success=false;
    $query="UPDATE `pdfsign` SET `signPdf`=?, status=0 WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'si',$file,$pfid);
    if(mysqli_stmt_execute($stmt)){
        $query1="UPDATE `pdfsign` SET `signPdf`=? WHERE pdfName=?";
        $stmt1=mysqli_prepare($con,$query1);
        mysqli_stmt_bind_param($stmt1,'ss',$file,$pdfName);
        if(mysqli_stmt_execute($stmt1)){
        $success=true;
        }
    }
    mysqli_stmt_close($stmt);
    return $success;
}

function addSignLocation($con,$x,$y,$p,$same,$id){
    $success=false;
    $query="UPDATE `pdfsign` SET x=?, y=?, p=?, same=?, setPlace=1, status=1 WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'sssii',$x,$y,$p,$same,$id);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}

function dropDownOptionListActiveSignatory($con,$id){
    $dataList=array();
   // $status=1;
    //$query="SELECT `client_signatory`.* FROM `client_signatory`, `pdfsign` WHERE `pdfsign`.status=? AND `client_signatory`.cid=`pdfsign`.user AND `pdfsign`.id=?";
    $query="SELECT `client_signatory`.* FROM `client_signatory`, `tbl_files` WHERE `client_signatory`.cid=`tbl_files`.user AND `tbl_files`.id=?";
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
function getClientIdFromPDFList($con,$pfid){
    $dataList=array();
    $query="SELECT * FROM `pdfsign` WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$pfid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList=$row;
    }
    mysqli_stmt_close($stmt);
    return $dataList;
}

function assignPDFShardToUser($con, $template, $auth,$stat,$is_attest,$pfid){
    $feedback = false;
    $query="UPDATE `tbl_files` SET `user`=?,`signby`=?,`ready`=?, `is_attest`=? WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'isiii',$template,$auth,$stat,$is_attest,$pfid);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);

    return $feedback;
}
function fetchButtonStatus($con, $id){
    $data=0;
    $query="SELECT * FROM `tbl_files` WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $data=$row['is_attest'];
    }
    mysqli_stmt_close($stmt);
    return $data;
}
/*
function assignPDFShardToUser($con,$createdBy,$useBy,$user,$template,$auth){
    $feedback = false;
    $date = date('Y-m-d H:i:s');
    $query="INSERT INTO pdfsign (`uploadBy`,`useBy`,`user`,`pdfName`,`auth`,`created_at`) VALUES (?,?,?,?,?,?)";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'siisis',$createdBy,$useBy,$user,$template,$auth,$date);
    if(mysqli_stmt_execute($stmt)){
        $feedback = true;
    }
    mysqli_stmt_close($stmt);

    return $feedback;
}*/
function assignPDFUpdateMultiUser($con,$template){
    $success=false;
    $x=1;
    $query="UPDATE `pdfsign` SET mu=? WHERE pdfName=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'is',$x,$template);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}
function fetchSignatoryList($con){
    $dataList=array();
    $num=0;
    $query="SELECT `client_signatory`.*, `clientcompany`.`name` as company FROM `client_signatory`, `clientcompany` WHERE `client_signatory`.cid=`clientcompany`.id;";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row=$result->fetch_assoc()){
        $dataList[] = $row;

    }

    return $dataList;
}

// Signatory Start
function addSignatory($con,$cid,$signatoryName,$position){
    $success=false;
    $status=0;
    //debug

    $query="INSERT INTO client_signatory (`cid`,`name`,`position`,`created_at`)
		VALUES (?,?,?,?); ";

    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'isss',$cid,$signatoryName,$position,nowd());
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        $success=true;
    }
    return $success;
}
function nowd(){
    $date = new DateTime();
    return $date=$date->format('Y-m-d H:i:s');
}
function deleteSignatory($con,$signatoryId){
    $success=false;
    $query="DELETE FROM client_signatory WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$signatoryId);
    if(mysqli_stmt_execute($stmt)){
        $success=true;
        mysqli_stmt_close($stmt);
    }

    return $success;
}
// Signatory End