<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/services.php");
$con = connectDb();

if (isset($_GET['addService'])) {
  $service = ucwords(strtolower($_GET['addService']));
  $feedback  = insertService($con, $service);
}

if (isset($_GET['loadServices'])) {
  $table = "
  <table id='servicesTableContent' style='width:100%'>
    <thead>
      <th>No</th>
      <th>Type of Services</th>
      <th>Created Date</th>
      <th>Action</th>
    </thead>
    <tbody>
  ";

  $dataList = fetchAllServices($con);
  $i = 1;
  foreach ($dataList as $data) {
    $table .="
        <tr>
          <td>".$i."</td>
          <td>".$data['service']."</td>
          <td>".$data['dateTime']."</td>
          <td><button type='button' onclick='updateServiceCheck(".$data['id'].")' class='btn-primary'>Edit</button><button onclick='removeService(".$data['id'].")' type='button' class='btn-danger'>Remove</button></td>
        </tr>
    ";
    $i++;
  }

  $table .="
    </tbody>
  </table>
  ";

  echo $table;
}

if (isset($_GET['updateServicesCheck'])) {
  $serviceId = $_GET['updateServicesCheck'];
  $data = fetchServiceById($con,$serviceId);
  echo $data['service'];
}

if (isset($_GET['getServiceDetails'])) {
  $serviceId = $_GET['getServiceDetails'];
  $row = fetchServiceById($con,$serviceId);
  echo json_encode($row);
}

if (isset($_GET['updateService'])) {
  $serviceId = $_GET['updateService'];
  $service = ucwords(strtolower($_GET['service']));
  $feedback = updateServiceById($con,$service,$serviceId);
  if ($feedback) {
    echo "0";
  }else {
    echo "1";
  }
}

if (isset($_GET['removeService'])) {
  $serviceId = $_GET['removeService'];
  $feedback = deleteServiceById($con,$serviceId);
  if ($feedback) {
    echo "0";
  }else {
    echo "1";
  }
}

function servicesOptionList(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  $con = connectDb();

  $option = "<option value='empty' selected disabled>Select Service</option>";
  $dataList = fetchServicesListAll($con);
  foreach ($dataList as $data) {
    $option .= "<option value='".$data['id']."'>".$data['service']."</option>";
  }

  return $option;
}

if (isset($_GET['addServiceDesc'])) {
  $serviceId = $_GET['serviceId'];
  $item = $_GET['item'];
  $description = $_GET['description'];
  $unitPrice = $_GET['unitPrice'];
  $quantity = $_GET['quantity'];
  $feedback = insertServiceDesc($con,$serviceId,$item,$description,$unitPrice,$quantity);

  if ($feedback) {
    echo "0";
  }else {
    echo "1";
  }
}

if (isset($_GET['loadServiceDescTable'])) {
  $serviceId = $_GET['loadServiceDescTable'];

  $table = "
  <table style='width:100%'>
    <thead>
      <tr>
        <th>No</th>
        <th>Item</th>
        <th>Description</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
  ";

  $i = 1;
  $dataList = fetchServiceDescListByServiceId($con,$serviceId);
  foreach ($dataList as $data) {
    $table .= "
      <tr>
        <td>".$i."</td>
        <td>".$data['item']."</td>
        <td>".$data['description']."</td>
        <td>".$data['unitPrice']."</td>
        <td>".$data['quantity']."</td>
        <td><button type='button' onclick='updateServiceDescCheck(".$data['id'].")' class='btn-primary'>Edit</button><button onclick='removeServiceDesc(".$data['id'].")' type='button' class='btn-danger'>Remove</button></td>
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


if (isset($_GET['removeServiceDesc'])) {
  $serviceDescId = $_GET['removeServiceDesc'];
  $feedback = deleteServiceDescById($con,$serviceDescId);
  if ($feedback) {
    echo "0";
  }else {
    echo "1";
  }
}

if (isset($_GET['updateServiceDescCheck'])) {
  $serviceDescId = $_GET['updateServiceDescCheck'];
  $row = fetchServiceDescById($con,$serviceDescId);
  echo json_encode($row);
}

if (isset($_GET['updateServiceDesc'])) {
  $serviceDescId = $_GET['updateServiceDesc'];
  $item = $_GET['item'];
  $description = $_GET['description'];
  $unitPrice = $_GET['unitPrice'];
  $quantity = $_GET['quantity'];

  $feedback = updateServiceByServiceDescId($con,$item,$description,$unitPrice,$quantity,$serviceDescId);
  if ($feedback) {
    echo "0";
  }else {
    echo "1";
  }
}

if (isset($_GET['getServiceDescDetails'])) {
  $serviceId = $_GET['getServiceDescDetails'];
  $dataList = fetchServiceDescListByServiceId($con,$serviceId);
  echo json_encode($dataList);
}
?>
