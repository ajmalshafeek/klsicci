<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/claim.php");
$con = connectDb();

if(isset($_POST['claimSubmit'])){
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD CLAIM \n
  </div>\n";

  $con = connectDb();
  $staffId = $_POST['orgStaffId'];
  $project = $_POST['project'];
  $claim = $_POST['claim'];
  $counter = $_POST['counter'];
  $claimType=$_POST['claimType'];
  $status = $_POST['claimSubmit'];
    $number="";
  if(isset($_POST['vehicleno'])){
  $number=$_POST['vehicleno'];}

    $tripId=null;
    if(isset($_POST['tripId'])){
        $tripId=$_POST['tripId'];}
  $dateTimeClaim = date("Y-m-d h:i:sa");
  $file = array();

  $claimId = insertClaim($con,$staffId,$project,$claim,$status,$dateTimeClaim,$claimType,$number,$tripId);

  while($counter >= 0){
    $fileName = "file".$counter;

    $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/2/claim/";
    $target_file = $target_dir . basename($_FILES[$fileName]["name"]);
    $temp = explode(".", $_FILES[$fileName]["name"]);
    $newfilename = rand(1000000,10000000) . '.' . end($temp);
    if (move_uploaded_file($_FILES[$fileName]['tmp_name'],"../resources/2/claim/" . $newfilename)) {
      $feedback = insertClaimFile($con,$claimId,$newfilename);
    }
    $counter--;
  }

  if($feedback && $status==1){
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>CLAIM IS SUCCESSFULLY DRAFTED \n
    </div>\n";
  }elseif($feedback && $status==0){
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>CLAIM IS SUCCESSFULLY SUBMITTED \n
    </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/claim/claimForm.php");
}

function claimTable(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/claim.php");

  $con = connectDb();
  $userid = $_SESSION['userid'];
  if($userid==7 || $userid==37){
      $dataList = fetchClaimListAll($con);
  }else{
      $dataList = fetchClaimListById($con,$userid);
  }
  $i = 1;

  $table="
  <table id='claimTable'>
    <thead>
      <tr>
        <th>No</th>
        <th>Staff Name</th>
        <th>Project Name</th>
        <th>Claim Amount</th>
        <th>Status</th>
        <th>Attachment</th>
      </tr>
    </thead>
    <tbody>
  ";
  foreach($dataList as $data){
    $staffId = $data['staffId'];
    $row = getOrganizationUserDetails($con,$staffId);
    $claimFileList = fetchClaimFileListByClaimId($con,$data['id']);

    if($data['status']==0 && $userid == 37){
      $status="Submitted";
      $tag = "data-toggle='modal' data-target='#approveModal'";
    }elseif($data['status']==0 && $userid == 7){
      $status="Submitted";
      $tag = "data-toggle='modal' data-target='#approveModal'";
    }elseif($data['status']==0){
      $status="Submitted";
      $tag = "";
    }elseif($data['status']==1){
      $status="Draft";
      $tag = "data-toggle='modal' data-target='#editClaimModal'";
    }
    elseif($data['status']==3){
      $status="Approved";
      $tag = "";
    }

    $table.="
    <tr>
      <td onclick='showClaim(".$data['id'].");showClaimAtt(".$data['id'].")' ".$tag.">".$i."</td>
      <td onclick='showClaim(".$data['id'].");showClaimAtt(".$data['id'].")' ".$tag.">".$row['fullName']."</td>
      <td onclick='showClaim(".$data['id'].");showClaimAtt(".$data['id'].")' ".$tag.">".$data['project']."</td>
      <td onclick='showClaim(".$data['id'].");showClaimAtt(".$data['id'].")' ".$tag.">".$data['claim']."</td>
      <td onclick='showClaim(".$data['id'].");showClaimAtt(".$data['id'].")' ".$tag.">".$status."</td>
      <td>";
      $j = 1;
      foreach($claimFileList as $claimFile){
        $table.= "<span id='".$claimFile['id']."' style='color:blue;cursor:pointer;font-size:12px;' onclick='showAttachment(\"".$claimFile['fileName']."\")' data-toggle='modal' data-target='#attachmentModal'><i class='fa fa-file'></i></span><br>";
        $j++;
      }
      $table.="</td>
    </tr>
    ";

    $i++;
  }
  $table .= "
    </tbody>
  </table>
  ";

  echo $table;
}

if(isset($_GET['loadAttachment'])){
  $id = $_GET['loadAttachment'];
  $row = fetchClaimFileById($con,$id);
  $fileName = $row['fileName'];
  echo $fileName;
}

if(isset($_POST['claimReport'])){
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");

  $con = connectDb();

  $staffId = null;
  $project = null;
  $dateFrom = null;
  $dateTo = null;
  $status = null;

  if(isset($_POST['orgStaffId'])){
    $staffId = $_POST['orgStaffId'];
  }

  if(isset($_POST['project'])){
    $project = $_POST['project'];
  }

  if(isset($_POST['dateFrom'])){
    $dateFrom = $_POST['dateFrom'];
  }

  if(isset($_POST['dateTo'])){
    $dateTo = $_POST['dateTo'];
    $dateTo = date('Y-m-d H:i:s', strtotime($dateTo . ' +1 day'));
  }

  if(isset($_POST['status'])){
    $status = $_POST['status'];
  }

  $dataList = fetchClaimListSpec($con,$staffId,$project,$dateFrom,$dateTo,$status);

  $table = "
  <table id='claimReportTable'>
    <thead>
      <th>No.</th>
      <th>Staff Name</th>
      <th>Description</th>";
if($_SESSION['orgType']==2) {
    $table .= "<th>Vehicle No</th>";
}
    $table .="<th>Claim(RM)</th>
      <th>Status</th>
      <th>Date/Time Created</th>
    </thead>
    <tbody>
  ";

  $i = 1;
  foreach ($dataList as $data) {
    $row = getOrganizationUserDetails($con,$data['staffId']);
    if($data['status']==0){
      $statusReport = "Submitted";
    }elseif($data['status']==1){
      $statusReport = "Draft";
    }elseif($data['status']==3){
      $statusReport = "Approved";
    }
    $table .="
      <tr>
        <td>".$i."</td>
        <td>".$row['fullName']."</td>
        <td>".$data['project']."</td>";
    if($_SESSION['orgType']==2) {
        $table .= "<td>" . $data['number'] . "</td>";
    }
      $table .="<td>".$data['claim']."</td>
        <td>".$statusReport."</td>
        <td>".$data['dateTimeClaim']."</td>
      </tr>
    ";

    $i++;
  }
  $table.="
    </tbody>
  </table>
  ";

  $_SESSION['claimReport'] = $table;
  $_SESSION['claimReportPrint'] = $table;
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/claim/claimReport.php");
}

if(isset($_GET['showClaim'])){
    $con = connectDb();
    $claimId = $_GET['showClaim'];
    $data = fetchClaimByClaimId($con,$claimId);
    echo json_encode($data);
}

if(isset($_GET['showClaimAtt'])){
    $con = connectDb();
    $claimId = $_GET['showClaimAtt'];
    $dataList = fetchClaimFileListByClaimId($con,$claimId);
    $table = "<table><tr><th style='width:80%;'>Attachment</th><th style='width:20%;'></th></tr>";
    foreach($dataList as $data){
        $table .= "<tr><td style='width:80%;'><object width='100%' data='../../resources/2/claim/".$data['fileName']."'></object></td><td  style='width:20%;text-align:center'><i class='fa fa-times' style='cursor:pointer;' onclick='removeClaimAtt(".$data['claimId'].",".$data['id'].")'></i></td></tr>";
    }
    $table .= "</table>";

    echo $table;
}

if(isset($_GET['removeClaimAtt'])){
    $con = connectDb();
    $claimFileId = $_GET['removeClaimAtt'];
    $data = deleteClaimFileById($con,$claimFileId);
    echo $data;
}

if(isset($_POST['claimUpdate'])){
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO EDIT CLAIM \n
  </div>\n";

  $con = connectDb();
  $claimId = $_POST['claimId'];
  $project = $_POST['project'];
  $claim = $_POST['claim'];
  $counter = $_POST['counter'];
  $status = $_POST['claimUpdate'];
  $dateTimeClaim = date("Y-m-d h:i:sa");
  $file = array();

  if($status == 0 || $status == 1){
      $feedback =updateClaim($con,$claimId,$project,$claim,$status,$dateTimeClaim);

      while($counter >= 0){
        $fileName = "file".$counter;

        $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/2/claim/";
        $target_file = $target_dir . basename($_FILES[$fileName]["name"]);
        $temp = explode(".", $_FILES[$fileName]["name"]);
        $newfilename = rand(1000000,10000000) . '.' . end($temp);
        if (move_uploaded_file($_FILES[$fileName]['tmp_name'],"../resources/2/claim/" . $newfilename)) {
          $feedback = insertClaimFile($con,$claimId,$newfilename);
        }
        $counter--;
      }

      if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>CLAIM IS SUCCESSFULLY EDITED \n
        </div>\n";
      }
  }elseif($status == 2){
      $feedback = deleteClaimById($con,$claimId);
      if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>CLAIM IS SUCCESSFULLY REMOVED \n
        </div>\n";
      }
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/claim/viewClaim.php");
}

if(isset($_POST['claimSubmitApprove'])){
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO APPROVE CLAIM \n
    </div>\n";

    $con = connectDb();
    $claimId = $_POST['claimSubmitApprove'];
    $status = 3;
    $feedback = updateClaimStatusById($con,$claimId,$status);

    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>CLAIM IS SUCCESSFULLY APPROVED \n
        </div>\n";
    }

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/claim/viewClaim.php");
}

if(isset($_GET['claimAmount'])){
  $date = $_GET['dateMonth'];
  $dateMonth = date("m",strtotime($date));
  $dateYear =  date("Y", strtotime($date));
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/claim.php");
  $con = connectDb();
  $sum = 0;
  $dataList = fetchClaimListByDate($con,$dateYear,$dateMonth);
  foreach ($dataList as $data) {
    $sum = $sum + $data['claim'];
  }
  echo $sum;
}

function compressedImage($source, $path, $quality) {

            $info = getimagesize($source);

            if ($info['mime'] == 'image/jpeg')
                $image = imagecreatefromjpeg($source);

            elseif ($info['mime'] == 'image/gif')
                $image = imagecreatefromgif($source);

            elseif ($info['mime'] == 'image/png')
                $image = imagecreatefrompng($source);

            imagejpeg($image, $path, $quality);

            return true;
}
if(isset($_POST['addClaimType'])){
    $claimType=$_POST['claimType'];
    $con=connectDb();
    $feedback=addClaimType($con,$claimType);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>CLAIM TYPE IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>CLAIM TYPE IS FAILED TO ADDED \n
        </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/claim/addType.php");
}

function claimTypeListTableEditable()
{
    $con = connectDb();

    $table = "<div class='table-responsive table-stripped table-bordered'>\n";
    $table .= "<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th>\n";
    $table .=    "Id\n";
    $table .=  "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Claim Type\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Action\n";
    $table .=   "</th>\n";
    $table .= "</tr>\n";
    $table .= "</thead >\n";
    $i = 1;
    $orgId = $_SESSION['orgId'];
    $status = 1;
    $role = null;
    $dataList = fetchClaimType($con);
    $table .= "<tbody>";
    foreach ($dataList as $data) {
        $table .= "<tr ";
        if ($i % 2 == 0)
            $table .= "style='background-color:#FFF5EB;'";
        else {
            $table .= "style='background-color:#F9F9F9;'";
        }
        $table .= ">";

        $table .=  "<td style='font-weight:bold'>";
        $table .=  $data['id'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['request'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#claimTypeEditModal' class='dropdown-item' onclick='claimTypeEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
        $table .= "	</div>
							</div>";
        $table .= "</td>";
        $table .= "</tr>";
    }

    $table .= "</tbody>";
    $table .= "</table>";
    $table .= "</div>";
    echo $table;
}

if(isset($_POST['editClaimType'])){
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $productTypeId = $_POST['claimTypeIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `claimcase` WHERE `id` = '$productTypeId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['productTypeIdEdit'] = $row['id'];
    $_SESSION['productTypeEdit'] = $row['request'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/claim/editType.php");

}

if(isset($_POST['removeClaimType'])){
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE CLAIM TYPE \n
   </div>\n";
    $con = connectDb();
    $productTypeId = $_POST['claimTypeIdToEdit'];
    $feedback = deleteClaimType($con, $productTypeId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>CLAIM TYPE IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/claim/viewType.php");
}
if(isset($_POST['editClaimTypeProcess'])){
    $claimType=$_POST['claimType'];
    $claimTypeId=$_POST['claimTypeId'];
    $feedback = updateProductType($con, $claimType, $claimTypeId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>CLAIM TYPE IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>CLAIM TYPE IS NOT UPDATED \n
    </div>\n";
    }

    unset($_SESSION['productTypeIdEdit']);
    unset($_SESSION['productTypeEdit']);
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/claim/viewType.php");
}
function getClaimType(){
    $con=connectDb();
    $dlist=fetchClaimType($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['request'].'">'.$list['request'].'</option>';
    }
    echo $listString;
}
?>