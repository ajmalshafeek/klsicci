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
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/trip.php");

$con = connectDb();

if(isset($_POST['addVehicle'])){
    ob_start();
    $vehicleType= $_POST['vehicletype'];
    $vehicleBrand= $_POST['vehiclebrand'];
    $vehicleCategory= $_POST['vehiclecategory'];
    $vehicleNo=$_POST['vehicleNo'];
    $vehicleDriver= "";
    if(isset($_POST['driver'])) {
        $vehicleDriver=$_POST['driver'];
    }
    $con=connectDb();
    $feedback=addVehicle($con,$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>VEHICLE IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>VEHICLE IS FAILED TO ADDED \n
        </div>\n";
    }
    ob_clean();
    ob_end_clean();
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vehicle/vehicle.php");


}

//Type
if(isset($_POST['addVehicleType'])){
    $vehicleType=$_POST['vehicleType'];
    $con=connectDb();
    $feedback=addVehicleType($con,$vehicleType);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>VEHICLE TYPE IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>VEHICLE TYPE IS FAILED TO ADDED \n
        </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vehicle/option/addType.php");
}
if(isset($_POST['editVehicle'])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $vehicleId = $_POST['vehicleIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `vehicles` WHERE `id` = '$vehicleId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['vehicleIdEdit'] = $row['id'];
    $_SESSION['vehicleTypeEdit'] = $row['type'];
    $_SESSION['vehicleNumberEdit'] = $row['number'];
    $_SESSION['vehicleBrandEdit'] = $row['brand'];
    $_SESSION['vehicleCategoryEdit'] = $row['category'];
    $_SESSION['vehicleDriverEdit'] = $row['driver'];

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/editVehicle.php");
}
if(isset($_POST['updateVehicleProcess'])){
    ob_start();
    $vehicleId=$_POST['vehicleId'];
    $vehicleType= $_POST['vehicletype'];
    $vehicleBrand= $_POST['vehiclebrand'];
    $vehicleCategory= $_POST['vehiclecategory'];
    $vehicleNo=$_POST['vehicleNo'];
    $vehicleDriver= "";
    if(isset($_POST['driver'])) {
        $vehicleDriver=$_POST['driver'];
    }
    $con=connectDb();
    $feedback=updateVehicle($con,$vehicleType,$vehicleBrand,$vehicleCategory,$vehicleDriver,$vehicleNo,$vehicleId);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>VEHICLE IS SUCCESSFULLY UPDATED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>VEHICLE IS FAILED TO UPDATED \n
        </div>\n";
    }
    unset($_SESSION['vehicleIdEdit']);
    unset($_SESSION['vehicleBrandEdit']);
    unset($_SESSION['vehicleCategoryEdit']);
    unset($_SESSION['vehicleTypeEdit']);
    unset($_SESSION['vehicleDriverEdit']);
    unset($_SESSION['vehicleNumberEdit']);
    ob_clean();
    ob_end_clean();
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/viewVehicle.php");
}


function vehicleTypeListTableEditable()
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
    $table .=    "Vehicle Type\n";
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
    $dataList = fetchVehicleType($con);
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
        $table .=    $data['type'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#vehicleTypeEditModal' class='dropdown-item' onclick='vehicleTypeEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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

if(isset($_POST['editVehicleType'])){
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $vehicleTypeId = $_POST['vehicleTypeIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `vehicletype` WHERE `id` = '$vehicleTypeId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['productTypeIdEdit'] = $row['id'];
    $_SESSION['productTypeEdit'] = $row['type'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/editType.php");

}

if(isset($_POST['removeVehicleType'])){
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE VEHICLE TYPE \n
   </div>\n";
    $con = connectDb();
    $productTypeId = $_POST['vehicleTypeIdToEdit'];
    $feedback = deleteVehicleType($con, $productTypeId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>VEHICLE TYPE IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewType.php");
}
if(isset($_POST['editVehicleTypeProcess'])){
    $con =connectDb();
    $vehicleType=$_POST['vehicleType'];
    $vehicleTypeId=$_POST['vehicleTypeId'];
    $feedback = updateVehicleType($con, $vehicleType, $vehicleTypeId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>VEHICLE TYPE IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>VEHICLE TYPE IS NOT UPDATED \n
    </div>\n";
    }
    unset($_SESSION['productTypeIdEdit']);
    unset($_SESSION['productTypeEdit']);
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewType.php");
}
function getVehicleType(){
    $con=connectDb();
    $dlist=fetchVehicleType($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['type'].'">'.$list['type'].'</option>';
    }
    echo $listString;
}

//Brand
if(isset($_POST['addVehicleBrand'])){
    $vehicleBrand=$_POST['vehicleBrand'];
    $con=connectDb();
    $feedback=addVehicleBrand($con,$vehicleBrand);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>VEHICLE BRAND IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>VEHICLE BRAND IS FAILED TO ADDED \n
        </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vehicle/option/addBrand.php");
}

function vehicleBrandListTableEditable()
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
    $table .=    "Vehicle Brand\n";
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
    $dataList = fetchVehicleBrand($con);
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
        $table .=    $data['brand'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#vehicleBrandEditModal' class='dropdown-item' onclick='vehicleBrandEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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

if(isset($_POST['editVehicleBrand'])){
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $vehicleBrandId = $_POST['vehicleBrandIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `vehiclebrand` WHERE `id` = '$vehicleBrandId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['vehicleBrandIdEdit'] = $row['id'];
    $_SESSION['vehicleBrandEdit'] = $row['brand'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/editBrand.php");

}

if(isset($_POST['removeVehicleBrand'])){
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE VEHICLE BRAND \n
   </div>\n";
    $con = connectDb();
    $productBrandId = $_POST['vehicleBrandIdToEdit'];
    $feedback = deleteVehicleBrand($con, $productBrandId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>VEHICLE BRAND IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewBrand.php");
}
if(isset($_POST['editVehicleBrandProcess'])){
    $con =connectDb();
    $vehicleType=$_POST['vehicleBrand'];
    $vehicleTypeId=$_POST['vehicleBrandId'];
    $feedback = updateVehicleBrand($con, $vehicleType, $vehicleTypeId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>VEHICLE BRAND IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>VEHICLE BRAND IS NOT UPDATED \n
    </div>\n";
    }
    unset($_SESSION['vehicleBrandIdEdit']);
    unset($_SESSION['vehicleBrandEdit']);
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewBrand.php");
}
function getVehicleBrand(){
    $con=connectDb();
    $dlist=fetchVehicleBrand($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['brand'].'">'.$list['brand'].'</option>';
    }
    echo $listString;
}

//Category
if(isset($_POST['addVehicleCategory'])){
    $vehicleCategory=$_POST['vehicleCategory'];
    $con=connectDb();
    $feedback=addvehicleCategory($con,$vehicleCategory);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>VEHICLE CATEGORY IS SUCCESSFULLY ADDED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>VEHICLE CATEGORY IS FAILED TO ADDED \n
        </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vehicle/option/addCategory.php");
}

function vehicleCategoryListTableEditable()
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
    $table .=    "Vehicle Category\n";
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
    $dataList = fetchVehicleCategory($con);
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
        $table .=    $data['category'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#vehicleCategoryEditModal' class='dropdown-item' onclick='vehicleCategoryEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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

if(isset($_POST['editVehicleCategory'])){
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $vehicleCategoryId = $_POST['vehicleCategoryIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `vehiclecategory` WHERE `id` = '$vehicleCategoryId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['vehicleCategoryIdEdit'] = $row['id'];
    $_SESSION['vehicleCategoryEdit'] = $row['category'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/editCategory.php");

}

if(isset($_POST['removeVehicleBrand'])){
    $con = connectDb();
    $productBrandId = $_POST['vehicleCategoryIdToEdit'];
    $feedback = deleteVehicleCategory($con, $productBrandId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>VEHICLE CATEGORY IS SUCCESSFULLY DELETED \n
     </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO DELETE VEHICLE CATEGORY \n
        </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewCategory.php");
}
if(isset($_POST['editVehicleCategoryProcess'])){
    $con =connectDb();
    $vehicleCategory=$_POST['vehicleCategory'];
    $vehicleCategoryId=$_POST['vehicleCategoryId'];
    $feedback = updateVehicleCategory($con, $vehicleCategory, $vehicleCategoryId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>VEHICLE CATEGORY IS SUCCESSFULLY UPDATED \n
    </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>VEHICLE CATEGORY IS NOT UPDATED \n
    </div>\n";
    }
    unset($_SESSION['vehicleCategoryIdEdit']);
    unset($_SESSION['vehicleCategoryEdit']);
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewCategory.php");
}
function getVehicleCategory(){
    $con=connectDb();
    $dlist=fetchVehicleCategory($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['category'].'">'.$list['category'].'</option>';
    }
    echo $listString;
}

function getVehicleNumber(){
    $con=connectDb();
    $dlist=fetchVehicleNumber($con);
    $listString="";
    foreach ($dlist as $list){
        $listString.='<option value="'.$list['number'].'">'.$list['number'].'</option>';
    }
    echo $listString;
}

function getDropDownListOrgStaffListNamesForVehicles(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    //require_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['config']['appRoot']."query/organizationUser.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
    $con=connectDb();
    $status=1;
    $role=42;
    $orgId=$_SESSION['orgId'];
    $driver="driver";
    $staff=vehicleDriverList($con,$driver);
    $str="";
    foreach ($staff as $data){
        $str.= '<option value="'.$data['staffId'].'">'.$data['fullName'].' ['.$data['staffId'].']</option>';
    }
    echo $str;
}

function vehicleListTableEditable()
{
    $con = connectDb();

    $table = "<div class='table-responsive table-stripped table-bordered'>\n";
    $table .= "<table id='dtable' class='table' width='100%' cellspacing='0'>\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th>\n";
    $table .=    "SR No\n";
    $table .=  "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Vehicle Type\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Vehicle Number\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Brand\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Category\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Driver\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Action\n";
    $table .=   "</th>\n";
    $table .= "</tr>\n";
    $table .= "</thead >\n";
    $i = 1;
    $dataList = fetchVehiclesList($con);
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
        $table .=  $i;
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['type'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['number'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['brand'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['category'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['driver'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#vehicleEditModal' class='dropdown-item' onclick='vehicleEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
        $table .= "	</div>
							</div>";
        $table .= "</td>";
        $table .= "</tr>";
        $i++;
    }

    $table .= "</tbody>";
    $table .= "</table>";
    $table .= "</div>";
    echo $table;
}
if(isset($_GET['vehicleDetails'])){
$vehicleId=$_GET['vehicleDetails'];
$con=connectDb();
$data=fetchVehicleDetailById($con,$vehicleId);
echo json_encode($data);
}

if(isset($_POST['removeVehicle'])){
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE VEHICLE BRAND \n
   </div>\n";
    $con = connectDb();
    $productBrandId = $_POST['vehicleIdToEdit'];
    $feedback = deleteVehicle($con, $productBrandId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>VEHICLE BRAND IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewBrand.php");
}

if(isset($_POST['vehiclesReport'])){
    $query="";
    $time="";
    if(isset($_POST['driver'])){
        $query.=" AND `driver`='".$_POST['driver']."'";
    }
    if(isset($_POST['vehicleNo'])){
        $query.=" AND `number` LIKE '%".$_POST['vehicleNo']."%'";
    }
    $timecategory = $_POST['timeCategory'];
    if ($timecategory==1) {
        $time .= "'%".$_POST['dateYear']."%'";
    } else if($timecategory==0) {
        $time .= "'%".$_POST['dateMonth']."%'";
    } else if($timecategory==2) {
        $time .= "'%".$_POST['sdate']."%'";
    }

    $con = connectDb();
    $dataList = fetchVehicleListBySearch($con,$query);
    if(!empty($dataList)){
        $table = "<div class='table-responsive table-stripped table-bordered'>\n";
        $table .= "<table id='dtable' class='table' width='100%' cellspacing='0'>\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No\n";
        $table .=  "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Vehicle Type\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Vehicle Number\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Brand\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Category\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Driver\n";
        $table .=   "</th>\n";
        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $i = 1;

        $table .= "<tbody>";
        foreach ($dataList as $data) {
            $table .= "<tr ";
            if ($i % 2 == 0)
                $table .= "style='background-color:#FFF5EB;'";
            else {
                $table .= "style='background-color:#F9F9F9;'";
            }
            $table .= "onclick=\"viewDetails(".$time.",'".$data['number']."')\" data-toggle='modal' data-target='#detailModal' class='details".$i."' >";

            $table .=  "<td style='font-weight:bold'>";
            $table .=  $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['type'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['number'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['brand'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['category'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['driver'];
            $table .=  "</td>";
            $table .= "</tr>";
            $i++;
        }

        $table .= "</tbody>";
        $table .= "</table>";
        $table .= "</div>";
        $_SESSION['MRTable']=$table;
    }else{
        $_SESSION['MRTable']="<center><h4>Not a valid search</h4></center>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/reports/vehicleReport.php");
}

if(isset($_GET['vehicleExpenses'])){
    $sent="";
    $data="";
    $datalist="";
    $total=0;
    $vehicleNo=$_GET['vehicleExpenses'];
    $query=$_GET['query'];
    $q=" AND `date` LIKE '".$query."'";
    $con=connectDb();
    $v=getVehicleByVehicleNumber($con,$vehicleNo);
    $trip=getTripByVehicleAndDate($con,$vehicleNo,$q);
    $sent='<center><table style="width:80%" class="printTable">';
    $amount=0;
    $driver="";
    if (!empty($v)){
        $driver=getDriverName($con,$v['driver']);
        $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Vehicle Details</b></td></tr>";
        $sent.='<tr><td width="120px;"><label>Vehicle Type</label></td><td align="right"><label id="date" class="text-center">'.$v['type'].'</label></td></tr>';
        $sent.='<tr><td><label>Vehicle No</label></td><td align="right"><label id="date" class="text-center">'.$v['number'].'</label></td></tr>';
        $sent.='<tr><td><label>Brand</label></td><td align="right"><label id="date" class="text-center">'.$v['brand'].'</label></td></tr>';
        $sent.='<tr><td><label>Category</label></td><td align="right"><label id="date" class="text-center">'.$v['category'].'</label></td></tr>';
        $sent.='<tr><td><label>Driver Name</label></td><td align="right"><label id="date" class="text-center">'.$driver.'</label></td></tr>';
       }
    $data="";
    $data=getMaintenanceDetailByDate($con,$vehicleNo,$q);
    $ii=1;
    if(!empty($data)){
        foreach($data as &$item){
            $temp=getBillSubCategoryNameById($con,$item['subcategory']);
            if($ii==1){
                $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Maintenance Expense</b></td></tr>";}
            $sent.="<tr><td>".$temp."</td><td align='right'>RM ".number_format($item['amount'], 2)."</td></tr>";
            (double)$total+=(double)$item['amount'];
            $ii++;
        }}
    $count=1;
    $td="";
    foreach ($trip as $t){
    $data=getClaimByTripId($con,$t['id']);

    $ii=1;
    if(!empty($data)){
        foreach ($data as $item){
            if($ii==1){
                $td.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:16px'>Claim </b></td></tr>";}
            $td.="<tr><td>".$item['project']."</td><td align='right'>RM ".number_format($item['claim'], 2)."</td></tr>";
            $total+=(double)$item['claim'];
            $ii++;
        }
    }
    $data="";
    $data=getExpenseByTripId($con,$t['id']);
    $ij=1;
    if(!empty($data)){
        foreach($data as &$item){
            $temp=getBillSubCategoryNameById($con,$item['subcategory']);
            if($ij==1){
                $td.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:16px'>Expense</b></td></tr>";}
            $td.="<tr><td>".$temp."</td><td align='right'>RM ".number_format($item['amount'], 2)."</td></tr>";
            (double)$total+=(double)$item['amount'];
            $ij++;
        }}
        if(($count==1&&$ij>1)||$count==1&&$ii>1) {
            $sent .= "<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:16px'>Trip ".$count."</b></td></tr>";
            $sent.=$td;
        }

    $count++;}
    if($total>0){
    $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Grand Total Expense</b></td></tr>";
    $sent.="<tr><td style='border-bottom: 1px solid #474747'></td><td align='right' style='border-bottom: 1px solid #474747'>RM ".number_format(((double)$total),2)."</td></tr>";}
    $sent.='</table></center>';
    echo $sent;
}

?>