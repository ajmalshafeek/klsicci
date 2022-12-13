<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/trip.php");
$con = connectDb();

if(isset($_POST['addTrip'])){
    //ob_start();
    $date= $_POST['date'];
    $clientid= $_POST['clientCompanyId'];
    $driverid= $_POST['driver'];
    $vehicleNo=$_POST['vehicleNumber'];
        $placeDes=$_POST['placeDes'];
        $shipment=$_POST['shipment'];
        $collectionPoint=$_POST['collectionPoint'];
        $deliveryPoint=$_POST['deliveryPoint'];
        $remarks=$_POST['remarks'];
        $diesel=$_POST['diesel'];
        $toll=$_POST['toll'];
        $driverTrip=$_POST['driverTrip'];
        $maintenance=$_POST['maintenance'];
        $paymentStatus=$_POST['paymentStatus'];
        $amount=$_POST['amount'];
    $con=connectDb();
    $date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
    $feedback=addTrip($con,$date,$clientid,$driverid,$vehicleNo,$placeDes,$shipment,$amount,
        $collectionPoint, $deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus);
    if($feedback>0){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>TRIP IS SUCCESSFULLY ADDED AND JOB NUMBER IS : SMU-".$feedback."\n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>TRIP IS FAILED TO ADDED \n
        </div>\n";
    }
   // ob_clean();
   // ob_end_clean();
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/trip/trip.php");


}

if(isset($_POST['editTrip'])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
    $tripId = $_POST['tripToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `trip` WHERE `id` = '$tripId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['tripToEdit'] = $tripId;
    $date=date_create($row['date']);
     $_SESSION['tripDateEdit'] = date_format($date,"Y-m-d");
    $_SESSION['tripClientEdit'] = $row['client'];
    $_SESSION['tripTruckEdit'] = $row['truck_no'];
    $_SESSION['tripPlaceEdit'] = $row['place'];
    $_SESSION['tripShipmentEdit'] = $row['shipment_no'];
    $_SESSION['tripDriverEdit'] = $row['driver'];
    $_SESSION['collectionPointEdit']=$row['collectionPoint'];
    $_SESSION['deliveryPointEdit']=$row['deliveryPoint'];
    $_SESSION['remarksEdit']=$row['remarks'];
    $_SESSION['dieselEdit']=$row['diesel'];
    $_SESSION['tollEdit']=$row['toll'];
    $_SESSION['driverTripEdit']=$row['driverTrip'];
    $_SESSION['maintenanceEdit']=$row['maintenance'];
    $_SESSION['paymentStatusEdit']=$row['paymentStatus'];
    $_SESSION['tripAmountEdit'] = $row['amount'];
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/editTrip.php");
}
if(isset($_POST['editTripProcess'])){
    $date= $_POST['date'];
    $clientid= $_POST['clientCompanyId'];
    $driverid= $_POST['driver'];
    $vehicleNo=$_POST['vehicleNumber'];
        $placeDes=$_POST['placeDes'];
        $shipment=$_POST['shipment'];
    $collectionPoint=$_POST['collectionPoint'];
    $deliveryPoint=$_POST['deliveryPoint'];
    $remarks=$_POST['remarks'];
    $diesel=$_POST['diesel'];
    $toll=$_POST['toll'];
    $driverTrip=$_POST['driverTrip'];
    $maintenance=$_POST['maintenance'];
    $paymentStatus=$_POST['paymentStatus'];
        $amount=$_POST['amount'];
        $tripId=$_SESSION['tripToEdit'];
      $con=connectDb();
      $feedback=updateTrip($con,$date,$clientid,$driverid,$vehicleNo,$placeDes,$shipment,$amount,
          $collectionPoint, $deliveryPoint,$remarks,$diesel,$toll,$driverTrip,$maintenance,$paymentStatus,$tripId);
    if($feedback){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>TRIP IS SUCCESSFULLY UPDATED \n
        </div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
        <strong>FAILED!</strong>TRIP IS FAILED TO UPDATED \n
        </div>\n";
    }
    unset($_SESSION['tripIdEdit']);
    unset($_SESSION['tripDateEdit']);
    unset($_SESSION['tripClientEdit']);
    unset($_SESSION['tripTruckEdit']);
    unset($_SESSION['tripPlaceEdit']);
    unset($_SESSION['tripShipmentEdit']);
    unset($_SESSION['tripDriverEdit']);
    unset($_SESSION['collectionPointEdit']);
    unset($_SESSION['deliveryPointEdit']);
    unset($_SESSION['remarksEdit']);
    unset($_SESSION['dieselEdit']);
    unset($_SESSION['tollEdit']);
    unset($_SESSION['driverTripEdit']);
    unset($_SESSION['maintenanceEdit']);
    unset($_SESSION['paymentStatusEdit']);
    unset($_SESSION['tripAmountEdit']);

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/viewTrip.php");
}

if(isset($_POST['tripViewSearch'])){
    $query="";
    $c=0;
    if(isset($_POST['driver'])){
        $query.=" AND `driver` LIKE '".$_POST['driver']."'";
    }
    if(isset($_POST['vehicleNo'])){
        $query.=" AND `truck_no` LIKE '%".$_POST['vehicleNo']."%'";
    }
    $timecategory = $_POST['timeCategory'];
    if ($timecategory==1) {
        $query .= " AND `date` LIKE '%".$_POST['dateYear']."%'";
    } else if($timecategory==0) {
        $query .= " AND `date` LIKE '%".$_POST['dateMonth']."%'";
    } else if($timecategory==2) {
        $query .= " AND `date` LIKE '%".$_POST['sdate']."%'";
    }
$_SESSION['searchTripView']=$query;
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/viewTrip.php");
}

function tripListTableEditable()
{
    $search='';
    if(isset($_SESSION['searchTripView'])){
        $search=$_SESSION['searchTripView'];
    }
    $con = connectDb();
    $dataList = fetchTripList($con,$search);

    $table = "<div class='table-responsive table-stripped table-bordered'>\n";
    $table .= "<table id='dtable' class='dtable' width='100%' cellspacing='0'>\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th width='20px'>\n";
    $table .=  "</th>\n";
    $table .=  "<th>\n";
    $table .=    "SR No\n";
    $table .=  "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Date\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Truck\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Job No\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Customer(Bill to)\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Customer D/O No\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Collection Point\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Delivery Point\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Trip Rate\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Remarks\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Diesel\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Toll\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Driver\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Driver Based Trip\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Maintenance\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Payment Status\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Action\n";
    $table .=   "</th>\n";
    $table .= "</tr>\n";
    $table .= "</thead >\n";
    $i = 1;

    $table .= "<tbody>";
    foreach ($dataList as $data) {
        $table .= "<tr ";
        if ($i % 2 == 0)
            $table .= "style='background-color:#e6ebe0;'";
        else {
            $table .= "style='background-color:#f5f5ef;'";
        }
        $table .= ">";

        $table.='<td style="text-align: center; vertical-align: middle;">
						<input type="checkbox"  value="'.$data['id'].'" name="checkedRow[]" />
			</td>';
        $table .=  "<td style='font-weight:bold'>";
        $table .=  $i;
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['date'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['truck_no'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    "SMU-".$data['id'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['name'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    $data['shipment_no'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['collectionPoint'])?'':$data['collectionPoint'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['deliveryPoint'])?'':$data['deliveryPoint'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['amount'])?'0':$data['amount'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['remarks'])?'':$data['remarks'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['diesel'])?'':$data['diesel'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['toll'])?'':$data['toll'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['driver'])?'':$data['driver'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['driverTrip'])?'':$data['driverTrip'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['maintenance'])?'':$data['maintenance'];
        $table .=  "</td>";
        $table .=  "<td>";
        $table .=    empty($data['paymentStatus'])?'':$data['paymentStatus'];
        $table .=  "</td>";
        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

        $table .= "<button type='button' data-toggle='modal' data-target='#tripEditModal' class='dropdown-item' onclick='tripEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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
if(isset($_POST['tripReport'])){
    $query="";
    if(isset($_POST['driver'])){
        $query.=" AND `staffId`=".$_POST['driver'];
    }
    if(isset($_POST['vehicleNo'])){
        $query.=" AND `truck_no` LIKE '%".$_POST['vehicleNo']."%'";
    }
    $timecategory = $_POST['timeCategory'];
    if ($timecategory==1) {
        $query .= " AND `date` LIKE '%".$_POST['dateYear']."%'";
    } else if($timecategory==0) {
        $query .= " AND `date` LIKE '%".$_POST['dateMonth']."%'";
    } else if($timecategory==2) {
        $query .= " AND `date` LIKE '%".$_POST['sdate']."%'";
    }

        $con = connectDb();
        $dataList = fetchTripListBySearch($con,$query);
        if(!empty($dataList)){

        $table = "<div class='table-responsive table-stripped table-bordered'>\n";
        $table .= "<table id='dtable' class='table' width='100%' cellspacing='0'>\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No\n";
        $table .=  "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Date\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Order By\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Truck No\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Place Description\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Shipment/Document No\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Driver\n";
        $table .=   "</th>\n";
        $table .=  "<th>\n";
        $table .=    "Amount\n";
        $table .=   "</th>\n";
        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $i = 1;

        $table .= "<tbody>";
        foreach ($dataList as $data) {
            $table .= "<tr ";
            if ($i % 2 == 0)
                $table .= "style='background-color:#e6ebe0;'";
            else {
                $table .= "style='background-color:#f5f5ef;'";
            }
            $table .= "onclick=\"viewDetails('details".$i."','".$data['id']."')\" data-toggle='modal' data-target='#detailModal' class='details".$i."' >";

            $table .=  "<td style='font-weight:bold'>";
            $table .=  $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['date'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['name'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['truck_no'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['place'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['shipment_no'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['fullName'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['amount'];
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
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/reports/tripReport.php");
}

if(isset($_GET['tripDetails'])){
    $tripId=$_GET['tripDetails'];
    $con=connectDb();
    $dataList=fetchTripListById($con,$tripId);
    echo json_encode($dataList);
}
if(isset($_POST['removeTrip'])){
    $tripId=$_POST['tripToEdit'];
    $con=connectDb();
    $result = removeTrip($con,$tripId);
    if ($result) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>TRIP IS SUCCESSFULLY DELETED \n
     </div>\n";
    }else{
        $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO DELETE TRIP \n
        </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/viewTrip.php");

}

function dropDownListTrip(){
    $con=connectDb();
    $status=1;
    $orgId=$_SESSION['orgId'];
    $client=fetchTripListLastMonth($con);

    echo "<select name='tripId' class='form-control' id='tripId' >";
    echo "<option selected disabled >--Select--</option>\n";
    foreach ($client as $data){
        echo "<option value=".$data['id']." >".$data['place']." [".$data['date']."]</option>";
    }
    echo	"</select>";

}

if(isset($_GET['tripExpenses'])){
    $sent="";
    $data="";
    $datalist="";
    $total=0;
    $tripid=$_GET['tripExpenses'];
    $con=connectDb();
    $trip=getTripAmount($con,$tripid);
    $sent='<center><table style="width:80%" class="printTable">';
$amount=0;
    if (!empty($trip)){
        $driver=getDriverName($con,$trip['driver']);
        $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Trip Details</b></td></tr>";
        $sent.='<tr><td width="120px;"><label>Date</label></td><td align="right"><label id="date" class="text-center">'.$trip['date'].'</label></td></tr>';
        $sent.='<tr><td><label>Trip</label></td><td align="right"><label id="date" class="text-center">'.$trip['client'].'</label></td></tr>';
        $sent.='<tr><td><label>Place Details</label></td><td align="right"><label id="date" class="text-center">'.$trip['place'].'</label></td></tr>';
        $sent.='<tr><td><label>Vehicle No</label></td><td align="right"><label id="date" class="text-center">'.$trip['truck_no'].'</label></td></tr>';
        $sent.='<tr><td><label>Shipment No</label></td><td align="right"><label id="date" class="text-center">'.$trip['shipment_no'].'</label></td></tr>';
        $sent.='<tr><td><label>Driver Name</label></td><td align="right"><label id="date" class="text-center">'.$driver.'</label></td></tr>';
        $sent.='<tr><td><label>Trip Amount</label></td><td align="right"><label id="date" class="text-center">RM '.number_format($trip['amount'], 2).'</label></td></tr>';
        $total=(double)$trip['amount'];
    }
    $data=getClaimByTripId($con,$tripid);
    $ii=1;
    if(!empty($data)){
        foreach ($data as $item){
            if($ii==1){
            $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Claim</b></td></tr>";}
            $sent.="<tr><td>".$item['project']."</td><td align='right'>RM ".number_format($item['claim'], 2)."</td></tr>";
            $total+=(double)$item['claim'];
            $ii++;
            }
    }
    $data="";
    $data=getExpenseByTripId($con,$tripid);
    $ii=1;
    if(!empty($data)){
    foreach($data as &$item){
        $temp=getBillSubCategoryNameById($con,$item['subcategory']);
        if($ii==1){
        $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Expense</b></td></tr>";}
        $sent.="<tr><td>".$temp."</td><td align='right'>RM ".number_format($item['amount'], 2)."</td></tr>";
        (double)$total+=(double)$item['amount'];
        $ii++;
    }}
    $sent.="<tr><td colspan='2' style='border-bottom: 1px solid #474747'><b style='font-size:18px'>Grand Total Expense</b></td></tr>";
    $sent.="<tr><td style='border-bottom: 1px solid #474747'></td><td align='right' style='border-bottom: 1px solid #474747'>RM ".number_format(((double)$total+(double)$amount),2)."</td></tr>";
    $sent.='</table></center>';
    echo $sent;
}