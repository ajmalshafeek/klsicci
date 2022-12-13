<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/purchaseOrder.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
$con = connectDb();

if (isset($_POST['addSupplier'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD SUPPLIER \n
  </div>\n";

  $con = connectDb();

  $supplierName = $_POST['supplierName'];
  $contactPerson = $_POST['contactPerson'];
  $contactNumber = $_POST['contactNumber'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $zipCode = $_POST['zipCode'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $fax = $_POST['fax'];
  $email = $_POST['email'];

  $feedback = insertPOSupplier($con,$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>SUPPLIER IS SUCCESSFULLY ADDED \n
    </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/manageSuppliers.php");
}

function suppliersDropDownOptionsAll(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/purchaseOrder.php");

  $con = connectDb();
  $dataList = fetchSupplierListAll($con);
  $options = "<option  disabled selected value>--Select Supplier--</option>";
  foreach ($dataList as $data) {
    $options .= "<option value='".$data['id']."'>".$data['supplierName']."</option>";
  }

  echo $options;
}

if (isset($_POST['createPO'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>ERROR!</strong> AN ERRO OCCUR \n
  </div>\n";

  $supplier = $_POST['supplier'];
  $shippingVia = $_POST['shippingVia'];
  $deliveryDate = $_POST['deliveryDate'];
  $poDate = $_POST['poDate'];
  $remarks = $_POST['remarks'];
  $maxNum = $_POST['maxNum'];
  $i = 1;



  while ($maxNum > 0) {
    $product[$i] = $_POST['product'.$i];
    $qty[$i] = $_POST['qty'.$i];
    $price[$i] = $_POST['price'.$i];
    $maxNum--;
    $i++;
  }

  $fileName = generatePurchaseOrder($supplier,$shippingVia,$deliveryDate,$poDate,$remarks,$product,$qty,$price);
  $poId = insertPurchaseOrder($con,$supplier,$shippingVia,$deliveryDate,$poDate,$remarks,$fileName);

  foreach ($product as $key => $value) {
    $productInd = $product[$key];
    $qtyInd = $qty[$key];
    $priceInd = $price[$key];
    echo $productInd." ".$qtyInd." ".$priceInd."<br>";
    $feedback = insertPOItemList($con,$poId,$productInd,$qtyInd,$priceInd);
    if (!$feedback) {
      break;
    }
  }


  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PURCHASE ORDER IS SUCCESSFULLY GENERATED \n
    </div>\n";
  }

  header("Location: https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/createPO.php");
}

function generatePurchaseOrder($supplier,$shippingVia,$deliveryDate,$poDate,$remarks,$product,$qty,$price){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/purchaseOrderPDF.php");

  $orgId=$_SESSION['orgId'];
  $pdfDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/purchaseOrder/";
  if (!file_exists($pdfDirectory)) {
    mkdir($pdfDirectory, 0777, true);
  }
  $pdfName = "P".rand(1000000,9999999).".pdf";
  while (file_exists($pdfDirectory.$pdfName)) {
    $pdfName = "P".rand(1000000,9999999).".pdf";
  }

  $pdf=generatePurchaseOrderPDF($supplier,$shippingVia,$deliveryDate,$poDate,$pdfName,$remarks,$product,$qty,$price);
  $pdf->output($pdfDirectory."/".$pdfName,'F');

  return $pdfName;
}

function purchaseOrderDesign($supplier,$shippingVia,$deliveryDate,$poDate,$pdfName,$remarks,$product,$qty,$price){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/purchaseOrder.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
  $con = connectDb();

  //(START)SUPPLIER INFORMATIONS
  $rowSupplier = fetchSupplierById($con,$supplier);
  $supplierContactPerson = $rowSupplier['contactPerson'];
  $supplierName = $rowSupplier['supplierName'];
  $supplierAddress = $rowSupplier['address1']."<br>".$rowSupplier['address2']."<br>".$rowSupplier['zipCode']." ".$rowSupplier['city'];
  $supplierZip = $rowSupplier['state'];
  $supplierContact  = $rowSupplier['contactNumber'];
  $supplierFax = $rowSupplier['fax'];
  $supplierEmail = $rowSupplier['email'];
  //(END)SUPPLIER INFORMATIONS

  //(START)ORG INFORMATIONS
  $rowOrg = getOrganizationDetails($con,$_SESSION['orgId']);
  $orgContactPerson = "";
  $orgName = $rowOrg['name'];
  $orgAddress = $rowOrg['address1']."<br>".$rowOrg['address2']."<br>".$rowOrg['postalCode']." ".$rowOrg['city'];
  $orgZip = $rowOrg['state'];
  $orgContact  = $rowOrg['contact'];
  $orgFax = $rowOrg['faxNo'];
  $orgEmail = $rowOrg['supportEmail'];
  //(END)ORG INFORMATIONS

  $styleOpen = "style='";
  $styleClose = "'";
  $tableWidth = "width:100%;font-size:12px;";
  $tableBorder = "border: 1px solid black;border-collapse: collapse;";

  $backgroundLightGrey = "background: #EAEAEA;";

  $tdBorder = "border: 1px solid black;";
  $tdWidth10 = "width:10%;";
  $tdWidth20 = "width:20%;";
  $tdWidth25 =  "width:25%;";
  $tdWidth30 =  "width:30%;";
  $tdWidth33 =  "width:33.33%;";
  $tdWidth40 = "width:40%;";
  $tdWidth50 =  "width:50%;";
  $tdWidth60 =  "width:60%;";

  $tdExtra = "border-bottom: 1px solid black;border-left: 1px solid black; border-right:1px solid black;";

  $design = "
    <table ".$styleOpen.$tableWidth.$styleClose.">
      <tr>
        <td style='width:66.67%'><h2 style='margin-bottom: 0px;'><img style='width:50%' src='".$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/myOrg/banner/".$rowOrg['banner']."'></td>
        <td style='text-align:right;width:33.33%;'><h2 style='margin-bottom: 0px;'>Purchase Order</h2></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
        <table ".$styleOpen.$tableWidth.$tableBorder.$styleClose.">
          <tr>
            <td  ".$styleOpen.$tdBorder.$tdWidth50.$backgroundLightGrey.$styleClose.">Date</td>
            <td  ".$styleOpen.$tdBorder.$tdWidth50.$styleClose.">".$poDate."</td>
          </tr>
          <tr>
            <td ".$styleOpen.$tdBorder.$tdWidth50.$backgroundLightGrey.$styleClose.">PO Number</td>
            <td ".$styleOpen.$tdBorder.$tdWidth50.$styleClose.">".basename($pdfName, '.pdf')."</td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    <table ".$styleOpen.$tableWidth.$styleClose.">
      <tr>
        <td ".$styleOpen.$tdWidth33.$backgroundLightGrey.$styleClose.">Supplier</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$backgroundLightGrey.$styleClose.">Ship to</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$supplierContactPerson."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$orgContactPerson."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose."><b>".$supplierName."</b></td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose."><b>".$orgName."</b></td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$supplierAddress."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$orgAddress."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$supplierZip."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$orgZip."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">Phone: ".$supplierContact."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">Phone: ".$orgContact."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">Fax: ".$supplierFax."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">Fax: ".$orgFax."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$supplierEmail."</td>
        <td ".$styleOpen.$tdWidth33.$styleClose."></td>
        <td ".$styleOpen.$tdWidth33.$styleClose.">".$orgEmail."</td>
      </tr>
    </table>
    <table ".$styleOpen.$tableWidth.$tableBorder.$styleClose.">
      <tr>
        <td ".$styleOpen.$tdWidth50.$tdBorder.$backgroundLightGrey.$styleClose.">Ship Via</td>
        <td ".$styleOpen.$tdWidth50.$tdBorder.$backgroundLightGrey.$styleClose.">Delivery Date</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth50.$tdBorder.$styleClose.">".$shippingVia."</td>
        <td ".$styleOpen.$tdWidth50.$tdBorder.$styleClose.">".$deliveryDate."</td>
      </tr>
    </table>
    <table><tr><td></td></tr></table>
    <table ".$styleOpen.$tableWidth.$tableBorder.$styleClose.">
      <tr>
        <td ".$styleOpen.$tdBorder.$tdWidth10."text-align:center;".$backgroundLightGrey.$styleClose.">No</td>
        <td ".$styleOpen.$tdBorder.$tdWidth40."text-align:center;".$backgroundLightGrey.$styleClose.">Product Name</td>
        <td ".$styleOpen.$tdBorder.$tdWidth10."text-align:center;".$backgroundLightGrey.$styleClose.">Qty</td>
        <td ".$styleOpen.$tdBorder.$tdWidth20."text-align:center;".$backgroundLightGrey.$styleClose.">Unit Price</td>
        <td ".$styleOpen.$tdBorder.$tdWidth20."text-align:center;".$backgroundLightGrey.$styleClose.">Total</td>
      </tr>
      ";
  $total = 0;
  foreach ($product as $index => $value) {
    $subTotal = $price[$index]*$qty[$index];
    $total = $total + $subTotal;
    $design .= "
        <tr>
          <td ".$styleOpen.$tdBorder."text-align:center;".$styleClose.">".$index."</td>
          <td ".$styleOpen.$tdBorder.$styleClose.">".$product[$index]."</td>
          <td ".$styleOpen.$tdBorder."text-align:right;".$styleClose.">".$qty[$index]."</td>
          <td ".$styleOpen.$tdBorder."text-align:right;".$styleClose.">".number_format($price[$index],2,".",",")."</td>
          <td ".$styleOpen.$tdBorder."text-align:right;".$styleClose.">".number_format($subTotal,2,".",",")."</td>
        </tr>
        ";
  }

  $design .="
    </table>
    <table><tr><td></td></tr></table>
    <table ".$styleOpen.$tableWidth.$styleClose.">
      <tr>
        <td ".$styleOpen.$tdWidth50.$tdBorder.$backgroundLightGrey.$styleClose.">Remarks</td>
        <td ".$styleOpen.$tdWidth30.$styleClose."></td>
        <td ".$styleOpen.$tdWidth20.$styleClose.">Total: RM".number_format($total,2,".",",")."</td>
      </tr>
      <tr>
        <td ".$styleOpen.$tdWidth50.$tdExtra."font-size:9px;".$styleClose.">".$remarks."</td>
        <td ".$styleOpen.$tdWidth30.$styleClose."></td>
        <td ".$styleOpen.$tdWidth20.$styleClose."></td>
      </tr>
    </table>
    <table ".$styleOpen.$tableWidth.$styleClose."><tr><td style='text-align:center;'><b>This is not a Tax Invoice!</b></td></tr></table>
    <table ".$styleOpen.$tableWidth.$styleClose.">
      <tr>
        <td ".$styleOpen.$tdWidth20.$tdBorder."font-size:9px;".$styleClose.">Date</td>
        <td ".$styleOpen.$tdWidth40.$styleClose."></td>
        <td ".$styleOpen.$tdWidth40.$tdBorder."font-size:9px;".$styleClose.">Authorized Signature</td>
      </tr>
    </table>
  ";
  return $design;
}

function purchaseOrderTable(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/purchaseOrder.php");
  $con = connectDb();

  $dataList = fetchPOListAll($con);

  $table="
  <table id='poTable'>
    <thead>
      <tr>
        <th>No.</th>
        <th>P.O. Number</th>
        <th>Supplier</th>
      </tr>
    </thead>
    <tbody>
  ";
  $i = 1;
  foreach ($dataList as $data) {
    $rowSupplier = fetchSupplierById($con,$data['supplierId']);
    $table.="
      <tr onclick='showPODetails(".$data['id'].")' data-toggle='modal' data-target='#poModal'>
        <td>".$i."</td>
        <td>".basename($data['fileName'], '.pdf')."</td>
        <td>".$rowSupplier['supplierName']."</td>
      </tr>
    ";
    $i++;
  }
  $table.="
    </tbody>
  </table>
  ";
  echo $table;
}

if (isset($_GET['showPO'])) {
  $poId = $_GET['showPO'];
  $row = fetchPOGeneratedById($con,$poId);
  echo json_encode($row);
}

function suppliersListTableAll(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/purchaseOrder.php");
  $con = connectDb();

  $dataList = fetchSupplierListAll($con);

  $table = "
  <table id='supplierTable'>
    <thead>
      <tr>
        <th>No</th>
        <th>Supplier</th>
        <th>Contact Person</th>
        <th>Contact Number</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
  ";

  $i = 0;
  foreach ($dataList as $data) {
    $i++;
    $table.="
      <tr onclick='showSupplierDetails(".$data['id'].")' data-toggle='modal' data-target='#supplierModal'>
        <td>".$i."</td>
        <td>".$data['supplierName']."</td>
        <td>".$data['contactPerson']."</td>
        <td>".$data['contactNumber']."</td>
        <td>".$data['email']."</td>
      </tr>
    ";
  }

  $table.="
    </tbody>
  </table>
  ";

  echo $table;
}

if (isset($_GET['showSupplier'])) {
  $supplierId = $_GET['showSupplier'];
  $row = fetchSupplierById($con,$supplierId);
  echo json_encode($row);
}

if (isset($_POST['editSupplierDetails'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPDATE SUPPLIER'S DETAILS \n
  </div>\n";

  $supplierId = $_POST['supplierId'];
  $supplierName = $_POST['supplierName'];
  $contactPerson = $_POST['contactPerson'];
  $contactNumber = $_POST['contactNumber'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $zipCode = $_POST['zipCode'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $fax = $_POST['fax'];
  $email = $_POST['email'];

  $feedback = updateSupplierDetails($con,$supplierName,$contactPerson,$contactNumber,$address1,$address2,$zipCode,$city,$state,$fax,$email,$supplierId);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>SUPPLIER DETAILS SUCCESSFULLY UPDATED \n
        </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/manageSuppliers.php");
}

if (isset($_GET['supplierIdRemove'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO REMOVE SUPPLIER \n
  </div>\n";

  $supplierId = $_GET['supplierIdRemove'];
  $feedback = deleteSupplierById($con,$supplierId);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>SUPPLIER DETAILS SUCCESSFULLY REMOVED \n
        </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/manageSuppliers.php");
}

if (isset($_GET['poIdRemove'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO REMOVE PURCHASE ORDER \n
  </div>\n";

  $poId = $_GET['poIdRemove'];
  $feedback = deletePOById($con,$poId);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>PURCHASE ORDER SUCCESSFULLY REMOVED \n
        </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/viewPO.php");
}

if (isset($_GET['emailPO'])) {
  $emailTo = $_GET['emailTo'];
  $ccTo = $_GET['ccTo'];
  $poId = $_GET['poId'];
  $_SESSION['feedback'] = emailPurchaseOrder($poId,$emailTo,$ccTo);
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/purchaseOrder/viewPO.php");
}
?>
