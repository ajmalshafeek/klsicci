<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vehicle.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/maintenance.php");

$con = connectDb();
if(isset($_POST['addMaintenanceFor'])){
    $maintenance=$_POST['maintenance'];
    $con=connectDb();
    $feedback=saveMaintenanceFor($con,$maintenance);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>MAINTENANCE IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>MAINTENANCE IS FAILED TO ADDED \n
        </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vehicle/option/addMaintenanceFor.php");
}

function vehicleMaintenanceForListTableEditable()
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
    $table .=    "Maintenance For\n";
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
    $dataList = fetchMaintenanceFor($con);
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
        $table .=    $data['maintenance'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#maintenanceForEditModal' class='dropdown-item' onclick='maintenanceForEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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
if(isset($_POST['editMaintenanceType'])){
    $maintenanceForId = $_POST['maintenanceForIdToEdit'];
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $con = connectDb();
    $sql = "SELECT * FROM `maintenancefor` WHERE `id` = '$maintenanceForId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['maintenanceForId'] = $row['id'];
    $_SESSION['maintenanceFor'] = $row['maintenance'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/editMaintenanceFor.php");
}
if(isset($_POST['editMaintenanceForProcess'])){
    $con =connectDb();
    $maintenanceFor=$_POST['maintenanceFor'];
    $maintenanceForId=$_POST['maintenanceForId'];
    $feedback = updateMaintenanceFor($con, $maintenanceFor, $maintenanceForId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>MAINTENANCE FOR IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>MAINTENANCE FOR IS NOT UPDATED \n
    </div>\n";
    }
    unset($_SESSION['maintenanceForId']);
    unset($_SESSION['maintenanceFor']);
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewMaintenanceFor.php");
}

if(isset($_POST['removeMaintenanceType'])){
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE MAINTENANCE FOR \n
   </div>\n";
    $con = connectDb();
    $maintenanceForId = $_POST['maintenanceForIdToEdit'];
    $feedback = deleteMaintenanceFor($con,$maintenanceForId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>MAINTENANCE FOR IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewMaintenanceFor.php");
}

function getMaintenanceType(){
    $con=connectDb();
    $dlist=fetchMaintenanceFor($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['maintenance'].'">'.$list['maintenance'].'</option>';
    }
    echo $listString;
}

if(isset($_POST['addMaintenance'])){
    $date= $_POST['date'];
    $vehileType=$_POST['vehicletype'];
    $vehicleno=$_POST['vehicleno'];
    $maintenanceFor=$_POST['maintenanceFor'];
    $nextDate=$_POST['nextDate'];
    $reminder=$_POST['reminder'];
    $jobNo=$_POST['jobNo'];
    $invoiceNo=$_POST['invoiceNo'];
    $workshop=$_POST['workshop'];
    $description=$_POST['description'];
    $remarks=$_POST['remarks'];
    $amount=$_POST['amount'];
    $status=$_POST['status'];
    $con=connectDb();
    $result=saveMaintenance($con,$date,$vehileType,$vehicleno,$maintenanceFor,$nextDate,$reminder,
        $jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status);

    if($result){

    $email=getDriverEmail($con,$vehicleno);
    if(empty($email['email'])){
        file_put_contents("../maintenace_no_email.log","Vehicle Number ".$vehicleno." don't have driver ",FILE_APPEND);
    }else{
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
        $subject="Maintenance scheduled ".$nextDate;
        $body="Hi ".$email['fullName'].",<br/><br />Your vehicle number ".$vehicleno." is scheduled for the date ".$nextDate.". Kindly make on time for maintenance.";

        $res=mailsend("","",$email['email'],$subject,$body);
        $_SESSION['feedback']=$res;
    }

        $_SESSION['feedback'] .= "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>MAINTENANCE IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] .= "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>MAINTENANCE IS NOT UPDATED \n
    </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/maintenance.php");
}

function fetchMaintenanceTableList(){
    $con = connectDb();
    $table = "<div class='table-responsive table-stripped table-bordered'>\n";
    $table .= "<table id='dtable' class='table dtable' width='100%' cellspacing='0'>\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th>\n";
    $table .=    "Sr No\n";
    $table .=  "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Date\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Truck\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Customer Job No\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Customer Invoice No\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Workshop/Location\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Description\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Amount\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Remarks\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Status\n";
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
    $c=1;
    $dataList = fetchMaintenanceList($con);

    $table .= "<tbody>";
    foreach ($dataList as $data) {
        $table .= "<tr ";
        if ($i % 2 == 0)
            $table .= "style='background-color:#e6ebe0;'";
        else {
            $table .= "style='background-color:#f5f5ef;'";
        }
        $table .= ">";

        $table .=  "<td style='font-weight:bold'>";
        $table .=  $c;
        $table .=  "</td>";

        $table .=  "<td>";
        $table .=    $data['date'];
        $table .=  "</td>";

        $table .=  "<td>";
        $table .=    $data['vehicle_no'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['jobNo'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['invoiceNo'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['workshop'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['description'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['amount'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['remarks'];
        $table .=  "</td>";
        $table .=  "<td>";
        if($data['mstatus']){
        $table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: green;"><span style="display: none">Completed</span></div>';
        }else{$table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: red;"><span style="display: none">Pending</span></div>';}
        $table .=  "</td>";

        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#maintenanceForEditModal' class='dropdown-item' onclick='maintenanceForEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
        $table .= "	</div>
							</div>";
        $table .= "</td>";
        $table .= "</tr>";
        $c++;
    }

    $table .= "</tbody>";
    $table .= "</table>";
    $table .= "</div>";
    echo $table;

}

if(isset($_GET['maintenanceDetail'])){
    $maintenanceId=$_GET['maintenanceDetail'];
    $con=connectDb();
    $data=fetchMaintenanceReportById($con,$maintenanceId);
    echo json_encode($data);
}
if(isset($_GET['getMaintenanceDetail'])){
    $maintenanceId=$_GET['getMaintenanceDetail'];
    $con=connectDb();
    $data=getMaintenanceDetailById($con,$maintenanceId);
    echo json_encode($data);

}

if(isset($_POST['editMaintenance'])){
    $maintenanceId = $_POST['maintenanceIdToEdit'];
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $con = connectDb();
    $sql = "SELECT * FROM `maintenance` WHERE `id` = '$maintenanceId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['mid'] = $row['id'];
    $_SESSION['date'] = $row['date'];
    $_SESSION['vehicle_type'] = $row['vehicle_type'];
    $_SESSION['vehicle_no'] = $row['vehicle_no'];
    $_SESSION['maintenance'] = $row['maintenance'];
    $_SESSION['next_date'] = $row['next_date'];
    $_SESSION['reminder'] = $row['reminder'];

    $_SESSION['jobNo'] = $row['jobNo'];
    $_SESSION['invoiceNo'] = $row['invoiceNo'];
    $_SESSION['workshop'] = $row['workshop'];
    $_SESSION['description'] = $row['description'];
    $_SESSION['remarks'] = $row['remarks'];
    $_SESSION['amount'] = $row['amount'];
    $_SESSION['mstatus'] = $row['mstatus'];

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/editMaintenance.php");
}

if(isset($_POST['updateVehicleProcess'])){
    $mid=$_POST['maintenanceId'];
    $con=connectDb();
    $date=$_POST['date'];
    $vehicletype=$_POST['vehicletype'];
    $vehicleno=$_POST['vehicleno'];
    $maintenanceFor=$_POST['maintenanceFor'];
    $nextDate=$_POST['nextDate'];
    $reminder=$_POST['reminder'];
    $jobNo=$_POST['jobNo'];
    $invoiceNo=$_POST['invoiceNo'];
    $workshop=$_POST['workshop'];
    $description=$_POST['description'];
    $remarks=$_POST['remarks'];
    $amount=$_POST['amount'];
    $status=$_POST['status'];

    $feedback = updateMaintenance($con, $date, $vehicletype,$vehicleno,$maintenanceFor,$nextDate,$reminder,$jobNo,$invoiceNo,$workshop,$description,$remarks,$amount,$status,$mid);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>MAINTENANCE IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>MAINTENANCE IS NOT UPDATED \n
    </div>\n";
    }
    unset($_SESSION['mid']);
    unset($_SESSION['date']);
    unset($_SESSION['vehicle_type']);
    unset($_SESSION['vehicle_no']);
    unset($_SESSION['maintenance']);
    unset($_SESSION['next_date']);
    unset($_SESSION['reminder']);
    unset($_SESSION['jobNo']);
    unset($_SESSION['invoiceNo']);
    unset($_SESSION['workshop']);
    unset($_SESSION['description']);
    unset($_SESSION['remarks']);
    unset($_SESSION['amount']);
    unset($_SESSION['mstatus']);

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/viewMaintenance.php");
}
if(isset($_POST['removeMaintenance'])){
    $mid=$_POST['maintenanceIdToEdit'];
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE MAINTENANCE \n
   </div>\n";
    $con = connectDb();
    $feedback = deleteMaintenance($con,$mid);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>MAINTENANCE IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/viewMaintenance.php");
}

if(isset($_POST['maintenanceReport'])) {


    $clientname = "";
    $category="";
    $subCategory="";
    $vehicleNo="";
    if(isset($_POST['category'])){
        $category=" AND `product`=".$_POST['category'];}
    if(isset($_POST['subcategory'])){
        $subCategory=" AND `subcategory`=".$_POST['subcategory'];}
    if(isset($_POST['vehicleNo'])){
        $vehicleNo=" AND number='".$_POST['vehicleNo']."'";
    }
    $other=$category.$subCategory.$vehicleNo;
    $timecategory = $_POST['timeCategory'];
    $datesearch = "";
    if ($timecategory==1) {
        $datesearch = $_POST['dateYear']."";
    } else if($timecategory==0) {
        $datesearch = $_POST['dateMonth']."";
    } else if($timecategory==2) {
        $datesearch = $_POST['sdate']."";
    } else{
        $_SESSION['datesearch']="Not Valid Search Report";
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/report.php");
    }
    $con=connectDb();
    $data=fetchMaintenanceReport($con,$datesearch,$other);
    if( $data!=null && !empty($data)) {
        $table = "<div class='table-responsive table-stripped table-bordered'>\n";
        $table .= "<table id='dtable' class='table dtable' width='100%' cellspacing='0'>\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .= "<th>\n";
        $table .= "Sr No\n";
        $table .= "</th>\n";

        $table .= "<th>\n";
        $table .= "Date\n";
        $table .= "</th>\n";

        $table .= "<th>\n";
        $table .= "Category\n";
        $table .= "</th>\n";

        $table .= "<th>\n";
        $table .= "Expense For\n";
        $table .= "</th>\n";
        if($_SESSION['orgType']==2) {
            $table .= "<th>\n";
            $table .= "Vehicle No\n";
            $table .= "</th>\n";
        }
        $table .= "<th>\n";
        $table .= "Amount\n";
        $table .= "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $i = 1;
        $orgId = $_SESSION['orgId'];
        $status = 1;
        $role = null;
        $c = 1;

        $table .= "<tbody>";
        foreach ($data as $item){

            $table .= "<tr ";
            if ($i % 2 == 0)
                $table .= "style='background-color:#e6ebe0;'";
            else {
                $table .= "style='background-color:#f5f5ef;'";
            }
            $table .= " onclick=\"viewDetails('details".$c."')\" data-toggle='modal' data-target='#detailModal' class='details".$c."' >";

            $table .=  "<td style='font-weight:bold'>";
            $table .=  $c;
            $table .=  "</td>";

            $table .=  "<td>";
            $table .=    $item['dateBill'];
            $table .=  "</td>";

            $product= fetchBillCategoryById($con,$item['product']);

            $table .= "<td>";
            $table .= "".$product;
            $table .= "</td>";
            $subCate= fetchBillSubCategoryById($con,$item['subcategory']);
            $table .= "<td>";
            $table .= "".$subCate;
            $table .= "</td>\n";
            if($_SESSION['orgType']==2) {
                $table .= "<td>";
                $table .= $item['number'];
                $table .= "</td>";
            }
            $table .=  "<td>";
            $table .=    $item['amount'];
            $table .=  "</td>";

            $table .= "</tr>";
            $c++;
        }
        $table .= "</tbody>";
        $table .= "</table>";
        $table .= "</div>";
    }
    $_SESSION['MRTable']=$table;
    $_SESSION['datesearch']="SEARCH REPORT ".$datesearch;
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/reports/maintenanceReport.php");
}

function getVehicleNumberListForReport(){
    $con=connectDb();
    $list= fetchVehicleNumberFromBill($con);
    $str1="";
    foreach ($list as $item){
        $str1.='<option value="'.$item['number'].'">'.$item['number'].'</option>';
    }
    echo $str1;
}

function dsMaintenanceTable()
{
    $con = connectDb();
    $sdata="";

    $dataList = fetchMaintenanceListForDS($con);
    if ($dataList == null) {
        echo "<center><h5 style='padding: 20px'>No Maintenance in coming 7 days</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No.\n";
        $table .=  "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Truck\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Vehicle No.\n";
        $table .=   "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Maintenance For\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Date\n";
        $table .=   "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $table .= "<body>";

        $i=1;
        foreach ($dataList as $data) {

            $table .= "<tr onclick=\"showMaintenance($data[id])\" >";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['vehicle_type'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['vehicle_no'];
            $table .=  "</td>";

            $table .=  "<td>";
            $table .=    $data['maintenance'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['next_date'];
            $table .=  "</td>";
            $table .= "</tr>";
            $i++;
        }
        $table .= "</body>";
        $table .= "</table>";
        $table .= "</div>";

        echo $table;
    }
}
?>
