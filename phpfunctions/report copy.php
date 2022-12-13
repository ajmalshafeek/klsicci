<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION))
{
session_name($config['sessionName']);
session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/autoNum.php");


if(isset($_GET["reportTypeDropdown"])){
 $dropDown="";
 if($_GET['reportTypeDropdown']==='staffSpan'){
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");

   $dropDown=dropDownListOrgStaffListActive();
 }else{
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");
   $dropDown=dropDownListVendorActive3();
 }
 echo $dropDown;
}

function getInvoiceQuotationSummary($dateFrom_,$dateTo_,$orgId_){
 $data=array();

 $con=connectDb();

 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");

 $quotationId=null;
 $customerName=null;
 $jobId=null;
 $quotationNo=null;
 $customerId=null;
 $createdBy=null;
 $dateFrom=$dateFrom_;
 $dateTo=$dateTo_;
 $status=null;
 $orgId=$orgId_;

 $invoiceId=null;
 $invoiceNo=null;

 $quotList=fetchQuotationList($con,$quotationId,$customerName,$jobId,$quotationNo,$customerId,
 $createdBy,$dateFrom,$dateTo,$status,$orgId);


 $invceList=fetchInvoiceListValid($con,$invoiceId,$customerName,$jobId,$invoiceNo,$customerId,
 $createdBy,$dateFrom,$dateTo,$status,$orgId);

 return array($quotList,$invceList);


}

if(isset($_GET["quotationSummary"])){
 $dateFrom=$_GET['dateFrom'];
 $dateTo=date('Y-m-d H:i:s', strtotime($_GET['dateTo'] . ' +1 day'));
 $orgId=$_SESSION['orgId'];

 list($quotList, $invcList) = getInvoiceQuotationSummary($dateFrom,$dateTo,$orgId);

 $quotList=$quotList;
 header("Content-Type: application/json");

 echo json_encode($quotList);

}



if(isset($_GET["invoiceSummary"])){
 $dateFrom=$_GET['dateFrom'];
 $dateTo=date('Y-m-d H:i:s', strtotime($_GET['dateTo'] . ' +1 day'));

 $orgId=$_SESSION['orgId'];

 list($quotList, $invcList) = getInvoiceQuotationSummary($dateFrom,$dateTo,$orgId);
 $invcList=$invcList;
 header("Content-Type: application/json");

 echo json_encode($invcList);

}
if(isset($_GET["viewJobListItemReport"])){

 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransactionItemList.php");

 $transId=$_GET["viewJobListItemReport"];
 $con=connectDb();
 $jobItemList= fetchJobItemList($con,$transId);

 $table="<div class='table-responsive'>\n";
 $table.="<table class='table table-bordered table-hover' id='dataTable' width='100%' cellspacing='0'>";
 $table.="<thead>";
   $table.="<tr>";
   $table.="<th scope='col' style='width:10%'>#</th>";
   $table.="<th scope='col'>ITEM NAME</th>";
   $table.="<th scope='col'>QTY</th>";
   $table.="</tr>";
 $table.="</thead>";

 $table.="<tbody>";
 $i=1;
 foreach($jobItemList as $item){
   $table.="<tr > ";

   $table.="<td>";
   $table.=$i++;
   $table.="</td>";

   $table.="<td>";
   $table.=$item['itemName'];
   $table.="</td>";

   $table.="<td>";
   $table.=$item['qty'];
   $table.="</td>";

     $table.="</tr>";
 }
 $table.="</tbody>";


 $table.="</table>";
 $table.="</div>";

 echo $table;

}

if(isset($_POST["jobReportTable"])){
 date_default_timezone_set("Asia/Kuala_Lumpur");
 $con=connectDb();
 $vendorId=null;
 $cliendCompanyId=null;
 $dateFrom=null;
 $dateTo=null;
 $status=null;
 $orgStaffId=null;
 $spanValue=$_POST['hiddenSpanValue'];
 $orgId=$_SESSION['orgId'];
 if(isset($_POST['vendorId'])){
   $vendorId=$_POST['vendorId'];
   $_SESSION['vendorIdReport'] = $vendorId;
 }
 if(isset($_POST['orgStaffId'])){
   $orgStaffId=$_POST['orgStaffId'];
   $_SESSION['orgStaffIdReport']=$orgStaffId;
 }

 if(isset($_POST['clientCompanyId'])){
   $cliendCompanyId=$_POST['clientCompanyId'];
   $_SESSION['clientCompanyIdReport']= $cliendCompanyId;
 }
 if(isset($_POST['dateFrom']) && isset($_POST['dateTo']) ){

   $dateFrom=new DateTime($_POST['dateFrom']);
   $dateTo=new DateTime($_POST['dateTo']);
   $dateFrom->setTime(0,0);
   $dateTo->setTime(23,59);
   $dateFrom=$dateFrom->format('Y-m-d H:i:s');
   $dateTo=$dateTo->format('Y-m-d H:i:s');

   $today = date('Y-m-d H:i:s');
   if ($dateFrom == $today && $dateTo == $today) { //SESSION dateReport
     $_SESSION['dateReport'] = "All";
   }else {
     $_SESSION['dateReport'] = "<b>FROM</b> ".$dateFrom." <b>TO</b> ".$dateTo;
   }
 }
 if(isset($_POST['status'])){
   $status=$_POST['status'];
   if ($status == 0) { //SESSION statusReport
     $_SESSION['statusReport'] = "COMPLETED";
   }elseif ($status == 2) {
     $_SESSION['statusReport'] = "PENDING";
   }elseif ($status == 3) {
     $_SESSION['statusReport'] = "IN PROGRESS";
   }
 }

 $table="<div class='table-responsive'>\n";
 $table.="<table  class='table table-bordered' id='reportTable' width='100%' cellspacing='0' >\n";
 $table.="<thead  class='thead-dark'>\n";
 $table.="<tr>\n";
 $table.=	"<th>\n";
 $table.=		"#\n";
 $table.=	"</th>\n";
 $table.=	"<th>\n";
 $table.=		"Ref No\n";
 $table.= 	"</th>\n";
 $table.= 	"<th>\n";
 $table.= 		"Client\n";
 $table.= 	"</th>\n";

 //PDF1
 $tablePDF="";
 $tablePDF.="<div class='table-responsive'>\n";
 $tablePDF.="<table  class='table table-bordered' id='reportTable' width='100%' cellspacing='0' >\n";
 $tablePDF.="<thead  class='thead-dark'>\n";
 $tablePDF.="<tr>\n";
 $tablePDF.=	"<th>\n";
 $tablePDF.=		"No.\n";
 $tablePDF.=	"</th>\n";

 if ($_SESSION['orgType']==1) {
   $tablePDF.=	"<th>\n";
   //$tablePDF.=		"REF NO\n";
   $tablePDF.=	"Product\n";
   $tablePDF.= 	"</th>\n";
 }

 $tablePDF.= 	"<th>\n";
 //$tablePDF.= 		"CLIENT\n";
 //$tablePDF.= 		"Customer\n";
 $tablePDF.= 		"Date\n";
 $tablePDF.= 	"</th>\n";
 //END PDF1

 if($spanValue=='vendor'){
   $table.= 	"<th>\n";
   $table.= 		"Vendor\n";
   $table.= 	"</th>\n";
   //PDF2
   $tablePDF.= 	"<th>\n";
   $tablePDF.= 		"VENDOR\n";
   $tablePDF.= 	"</th>\n";
   //END PDF2
 }else if($spanValue=='staff'){
   $table.= 	"<th>\n";
   $table.= 		"Staff Name\n";
   $table.= 	"</th>\n";

   //PDF3
   $tablePDF.= 	"<th>\n";
   //$tablePDF.= 		"STAFF NAME\n";
   //$tablePDF.= 		"Location\n";
   $tablePDF.= 		"Description\n";
   $tablePDF.= 	"</th>\n";


   if ($_SESSION['orgType']) {
     $tablePDF.= 	"<th>\n";
     //$tablePDF.= 		"STAFF ID\n";
     //$tablePDF.= 		"Contact No.\n";
     $tablePDF.= 		"Meter Reading\n";
     $tablePDF.= 	"</th>\n";
   }
   //END PDF3

 }
 $table.= 	"<th>\n";
 $table.= 		"Job Name\n";
 $table.= 	"</th>\n";
 $table.= 	"<th>\n";
 $table.= 		"Products\n";
 $table.= 	"</th>\n";
 $table.= 	"<th>\n";
 $table.= 		"Job Created Date\n";
 $table.= 	"</th>\n";
 $table.= 	"<th>\n";
 $table.= 		"Status\n";
 $table.= 	"</th>\n";
 $table.= "</tr>\n";
 $table.= "</thead >\n";

 //PDF4
 $tablePDF.= 	"<th>\n";
 //$tablePDF.= 		"JOB NAME\n";
 $tablePDF.= 		"Technician\n";
 $tablePDF.= 	"</th>\n";
/* $tablePDF.= 	"<th>\n";
 $tablePDF.= 		"JOB CREATED DATE\n";
 $tablePDF.= 	"</th>\n";
 $tablePDF.= 	"<th>\n";
 $tablePDF.= 		"REMARKS\n";
 $tablePDF.= 	"</th>\n"; */
 $tablePDF.= 	"<th>\n";
 $tablePDF.= 		"STATUS\n";
 $tablePDF.= 	"</th>\n";
 $tablePDF.= "</tr>\n";
 $tablePDF.= "</thead >\n";
 //END PDF4
 if($spanValue=='vendor'){

   $dataList=fetchJobList($con,$vendorId,$cliendCompanyId,$dateFrom,$dateTo,$status,$orgId);

 }else if($spanValue=='staff'){
   $vendorId=0;
   $dataList=fetchOrgStaffJobList($con,$orgStaffId,$cliendCompanyId,$dateFrom,$dateTo,$status,$orgId);

 }
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendor.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/product.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");

//(START)FILTER BY SERIAL NUMBER MODULE
 if(isset($_POST['serialNumFilter'])){
     $serialNumFilter = $_POST['serialNumFilter'];
     //echo "Serial Num:".$serialNumFilter."<br>";
     $rowProductFilter = fetchProductListBySerialNum($con,$serialNumFilter);
     //echo "Product Id:".$rowProductFilter['id']."<br>"; //17
     $dataListClientProductFilter =fetchClientProductListByProductIdValue($con,$rowProductFilter['id']);
     $_SESSION['serialNumFilter']=$serialNumFilter;
     $_SESSION['brandFilter']=$rowProductFilter['brand'];
     $_SESSION['modelFilter']=$rowProductFilter['model'];
 }
//(END)FILTER BY SERIAL NUMBER MODULE

 $i=1;
 $table.="<tbody>";
 foreach($dataList as $data){
    //(START)FILTER BY SERIAL NUMBER MODULE COMPARE
    if($_POST['serialNumFilter']!=NULL){
        echo "ISSET";
        $searchBreak = false;
        //echo "Complaint Id:".$data['complaintId']."<br>";
        $rowComplaintProduct = fetchComplaintProductRowByComplaintId($con,$data['complaintId']);
        $compare1 = $rowComplaintProduct['productId']; //database


        foreach($dataListClientProductFilter as $rowClientProductFilter){
            $compare2 = $rowClientProductFilter['id']; //form
            //echo $z."start compare:".$compare1."-".$compare2."<br>";
            if($compare1 == $compare2){
            //echo "compared<br>";
                $searchBreak = true;
                break;
            }
        }
        if(!$searchBreak){
            continue;
        }
    }
    //(END)FILTER BY SERIAL NUMBER MODULE COMPARE

   $clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);
   $vendorDetails=fetchVendorDetails($con,$data['vendorId']);
   $orgStaffDetails=getOrganizationUserDetails($con,$data['createdBy']);
   $remarks=$data['remarks'];
   $jobTransDetails=fetchJobTransByJobId($con,$data['id']);

    switch ($data['status']) {
    case "0":
        $statusJob = "Completed";
        break;
    case "2":
        $statusJob = "Pending";
        break;
    case "3":
        $statusJob = "In Progress";
        break;
}


   if($data['complaintId']>0){

     $orgStaffDetails=getOrganizationUserDetails($con,$jobTransDetails['workerId']);
     $remarks=$jobTransDetails['remarks'];
   }


   $table.= "<tr  ";
   if($i%2==0){
     $table.= "style='background-color:#FFF5EB;'";
   }else{
     $table.= "style='background-color:#F9F9F9;'";
   }
   $table.= ">";
   $table.=	"<td  data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].");showLocation(".$data['id'].",\"".$data['latlon']."\")' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   $table.=		$i++;
   $table.=	"</b></td>";

   $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   $table.=		$data['refNo'];
   $table.=	"</b></td>";

   $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   if ($clientCompanyDetails['name']==null) {
    $dataOrg = fetchOrganizationDetails($_SESSION['orgId']);
    $table.= $dataOrg['name'];
   }else {
     $table.=		$clientCompanyDetails['name'];
   }
   $table.=	"</b></td>";

   if($spanValue=='vendor'){
     $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
     $table.=		$vendorDetails['name'];
     $table.=	"</b></td>";

   }else if($spanValue=='staff'){
     $staffName=$orgStaffDetails['fullName'];
     $staffId=$orgStaffDetails['staffId'];

     $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
     $table.=		$staffName;
     $table.=	"</b></td>";

   }

   $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   $table.=		$data['jobName'];
   $table.=	"</b></td>";

    $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
/*   $table.=		productTableList($data['id']); //PRODUCT DATABASE PATH: job(complaintId-complaintId)->complaintproduct(productId-id)->clientproduct(productId-id)->product */
    //$dataListProduct = fetchClientProductListById($con,$data['id']);
    $dataListProduct = fetchComplaintProductByComplaintId($con,$data['complaintId']);
    if ($dataListProduct == NULL) {
        $table.= "NO PRODUCT";
    }else{
        foreach ($dataListProduct as $dataProduct) {
    //$rowProduct = fetchProductListById($con,$dataProduct['productId']); //NEED TO FIX
    $rowClientProduct = fetchClientProductRowByProductId($con,$dataProduct['productId']);
    $rowProduct = fetchProductListById($con,$rowClientProduct['productId']);
    if($rowProduct['model']!=NULL){
      $table.=   "-".strtoupper($rowProduct['brand'])." ".$rowProduct['model']." [S/N: ".$rowProduct['serialNum']."]<br>"; //ET JAP
    }
  }
}
   $table.=	"</b></td>";

   $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   $table.=		$data['createdDate'];
   $table.=	"</b></td>";

    $table.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
   $table.=		$statusJob;
   $table.=	"</b></td>";

   $table.= "</tr>";
 }

 //THIS PART IS FOR PDF CONVERTING. THE ARRANGEMENT OF THE COLUMN IS MESSED UP BECAUSE OF THE PDF CONVERTER SCRIPT.
 //THE PDF CONVERTING SCRIPT DOES NOT WORK WELL IF A SESSION VARIABLE IS INPUTTED IN IT.
 //TO SOLVE THIS, I HAVE TO REARRANGE THE COLUMN BY BRUTE-FORCE TO MAKE THE TABLE ARRANGE PREFECTLY IN THE PDF
 $j = 1;
 $k = 1;
 foreach($dataList as $data){
    //(START)FILTER BY SERIAL NUMBER MODULE COMPARE
    if($_POST['serialNumFilter']!=NULL){
        $searchBreak = false;
        //echo "Complaint Id:".$data['complaintId']."<br>";
        $rowComplaintProduct = fetchComplaintProductRowByComplaintId($con,$data['complaintId']);
        $compare1 = $rowComplaintProduct['productId']; //database


        foreach($dataListClientProductFilter as $rowClientProductFilter){
            $compare2 = $rowClientProductFilter['id']; //form
            //echo $z."start compare:".$compare1."-".$compare2."<br>";
            if($compare1 == $compare2){
            //echo "compared<br>";
                $searchBreak = true;
                break;
            }
        }
        if(!$searchBreak){
            continue;
        }
    }
    //(END)FILTER BY SERIAL NUMBER MODULE COMPARE
   //echo "<br/>".$data['clientCompanyId'];
   $clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);
   $vendorDetails=fetchVendorDetails($con,$data['vendorId']);
   $orgStaffDetails=getOrganizationUserDetails($con,$data['createdBy']);
   $remarks=$data['remarks'];
   $jobTransDetails=fetchJobTransByJobId($con,$data['id']);

   if($data['complaintId']>0){

     $orgStaffDetails=getOrganizationUserDetails($con,$jobTransDetails['workerId']);
     $remarks=$jobTransDetails['remarks'];
   }

    switch ($data['status']) {
    case "0":
        $statusJobPDF = "COMPLETED";
        break;
    case "2":
        $statusJobPDF = "PENDING";
        break;
    case "3":
        $statusJobPDF = "IN PROGRESS";
        break;
}

   //echo "<br/>".$clientCompanyDetails['name'];
//1 row
$tablePDF.= "<tr  ";
if($k%2==0){
  $tablePDF.= "style='background-color:#FFF5EB;'";
}else{
  $tablePDF.= "style='background-color:#F9F9F9;'";
}
$tablePDF.= ">";
$tablePDF.=	"<td  data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$k++;
$tablePDF.=		"1";
$tablePDF.=	"</td>";

$tablePDF.=	"<td  data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
$tablePDF.=		$k++;
//$tablePDF.=		"10";
$tablePDF.=	"</td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$clientCompanyDetails['name'];
$tablePDF.=	"3";
$tablePDF.=	"</td>";

if($spanValue=='vendor'){
  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
  //$tablePDF.=		$vendorDetails['name'];
  $tablePDF.=		"4";
  $tablePDF.=	"</td>";

}else if($spanValue=='staff'){
  $staffName=$orgStaffDetails['fullName'];
  $staffId=$orgStaffDetails['staffId'];

  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
  //$tablePDF.=		$data['refNo'];
  //$dataListProduct = fetchClientProductListById($con,$clientCompanyDetails['id']);
  $dataListProduct = fetchComplaintProductByComplaintId($con,$data['complaintId']);
  if ($dataListProduct == NULL) {
    $tablePDF.= "NO PRODUCT";
  }else{
  foreach ($dataListProduct as $dataProduct) {
    $rowClientProduct = fetchClientProductRowByProductId($con,$dataProduct['productId']);
    $rowProduct = fetchProductListById($con,$rowClientProduct['productId']);
    if($rowProduct['model']!=NULL){
      $tablePDF.=   "-".strtoupper($rowProduct['brand'])." ".$rowProduct['model']." [S/N: ".$rowProduct['serialNum']."]<br>";
    }
  }
}
  //$tablePDF.=		"2";
  $tablePDF.=	"</td>";

  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//  $tablePDF.=		$staffId;
  $tablePDF.=		"6";
  $tablePDF.=	"</td>";

}

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$clientCompanyDetails['name']; // TUKAR DATE
$tablePDF.=		$data['createdDate'];
//$tablePDF.=	"12";
$tablePDF.=	"</td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$data['createdDate'];
$tablePDF.=		"8";
$tablePDF.=	"</td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;margin-left: auto;
    margin-right: auto;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$staffName;
//$tablePDF.=   $clientCompanyDetails['address1'].", ".$clientCompanyDetails['address2'].", ".$clientCompanyDetails['city'].", ".$clientCompanyDetails['postalCode'].", ".$clientCompanyDetails['state'];
$tablePDF.=   $data['jobName'];
if($data['action']!=NULL){
    $tablePDF.= " + ".$data['action'];
}
if($data['remarks']!=NULL){
    $tablePDF.= " + ".$data['remarks'];
}
//$tablePDF.=		"5";
$tablePDF.=	"</td>"; //TUKAR PROBLEM

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."

if($data['status']===0){
  $tablePDF.= "COMPLETED";
}
else if($data['status']===1){
  $tablePDF.= "NEW";
}else if($data['status']===2){
  $tablePDF.= "PENDING";
}
else if($data['status']===3){
  $tablePDF.= "IN PROGRESS";
}

$tablePDF.=	"</td>";
/*
$tablePDF.=	"<td><b>";
$tablePDF.=	"<button name='viewJobDetail' data-toggle='modal' data-target='#signatureModal' onclick='viewSignature(this.value)' value='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$data['signaturePath']."' class='btn btn-info' type='submit' >view</button>";
$tablePDF.=	"</b></td>"; */

/*      $table.=	"<td><b>";
$table.=	"<button name='print' data-toggle='modal' data-target='#printModal' onclick='printDetail(".$data['id'].")' class='btn btn-info' type='submit' >print</button>";
$table.=	"</b></td>"; */

$tablePDF.= "</tr>";

//START 1 row copy
$tablePDF.= "<tr  ";
if($k%2==0){
  $tablePDF.= "style='background-color:#FFF5EB;'";
}else{
  $tablePDF.= "style='background-color:#F9F9F9;'";
}
$tablePDF.= ">";
$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$staffId;
if($data['meter1']!=NULL){
  $tablePDF.= "-".$data['meter1']."<br>";
}
if($data['meter2']!=NULL){
  $tablePDF.= "-".$data['meter2']."<br>";
}
if($data['meter3']!=NULL){
  $tablePDF.= "-".$data['meter3']."<br>";
}
if($data['meter4']!=NULL){
  $tablePDF.= "-".$data['meter4']."<br>";
}
if($data['meter1']==NULL && $data['meter2']==NULL && $data['meter3']==NULL && $data['meter4']==NULL){
  $tablePDF.= "<i style='color: grey;'>EMPTY</i>";
}else{
    $sumMeter = $data['meter1'] + $data['meter2'] + $data['meter3'] + $data['meter4'];
  $tablePDF.= "Total= ".$data['meterTotal'];
}
//$tablePDF.=		"15";
$tablePDF.=	"</td>"; // TUKAR MATER READING

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$data['refNo'];
$tablePDF.=		"11";
$tablePDF.=	"</td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$data['jobName'];
$tablePDF.=		$staffName;
//$tablePDF.=		"7";
$tablePDF.=	"</td>";

if($spanValue=='vendor'){
  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' >"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
  //$tablePDF.=		$vendorDetails['name'];
  $tablePDF.=		"13";
  $tablePDF.=	"</td>";

}else if($spanValue=='staff'){
  $staffName=$orgStaffDetails['fullName'];
  $staffId=$orgStaffDetails['staffId'];
/*
  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
  //$tablePDF.=		$staffName;
  $tablePDF.=		"14";
  $tablePDF.=	"</b></td>";

  $tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
  $tablePDF.=		$data['createdDate'];
  //$tablePDF.=		"17";
  $tablePDF.=	"</b></td>"; */

}

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$data['jobName'];
$tablePDF.=		"16";
$tablePDF.=	"</b></td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
$tablePDF.=		$statusJobPDF; //ET MARKER
//$tablePDF.=		"9";
$tablePDF.=	"</b></td>";
/*
$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."
//$tablePDF.=		$remarks;
$tablePDF.=		"18";
$tablePDF.=	"</b></td>";

$tablePDF.=	"<td data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='printDetail(".$data['id'].")')' ><b>"; //data-target='#jobDetailsModal' onclick='viewJobDetails(".$jobTransDetails['id']."

if($data['status']===0){
  $tablePDF.= "COMPLETED";
}
else if($data['status']===1){
  $tablePDF.= "NEW";
}else if($data['status']===2){
  $tablePDF.= "PENDING";
}
else if($data['status']===3){
  $tablePDF.= "IN PROGRESS";
}
$tablePDF.=	"</b></td>"; */
/*
$tablePDF.=	"<td><b>";
$tablePDF.=	"<button name='viewJobDetail' data-toggle='modal' data-target='#signatureModal' onclick='viewSignature(this.value)' value='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$data['signaturePath']."' class='btn btn-info' type='submit' >view</button>";
$tablePDF.=	"</b></td>"; */

/*      $table.=	"<td><b>";
$table.=	"<button name='print' data-toggle='modal' data-target='#printModal' onclick='printDetail(".$data['id'].")' class='btn btn-info' type='submit' >print</button>";
$table.=	"</b></td>"; */

$tablePDF.= "</tr>";
//END 1 row copy
 }
 //END FOR PDF

 $table.="</tbody>";
 $table.= "</table>";
 $table.= "</div>";
 //PDF CLOSING
 $tablePDF.="</tbody>";
 $tablePDF.= "</table>";
 $tablePDF.= "</div>";
 //END PDF CLOSING
 $_SESSION['tablePDF']= $tablePDF;
//    $_SESSION['tableReportPDF']=$table;
 $_SESSION['tableReport']=$table;
//	echo $table;
 header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/report/report.php");

}

if(isset($_GET["staffJobDetail"])) {
//    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
 require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransactionItemList.php");
 $con = connectDb();
 $id = $_GET["staffJobDetail"];
 $data = fetchStaffJobDetail($con,$id);
 $obj = json_decode($data);
 //debug
 $orgId = $_SESSION['orgId'];
 //REF NO.
 $refNo = $obj->{'refNo'};
 //CLIENT
 $clientCompanyId = $obj->{'clientCompanyId'};
 if ($clientCompanyId==0) {
   $dataOrg = fetchOrganizationDetails($orgId);
   $clientCompanyName = $dataOrg['name'];
 }else {
   $dataClientCompany = fetchClientCompanyDetails($con,$clientCompanyId);
   $clientCompanyName = $dataClientCompany['name'];
 }
 //STAFF NAME & STAFF ID
 $jobId  = $obj->{'id'};
 $dataJobTransaction = fetchJobTransByJobId($con,$jobId);
 $staffId = $dataJobTransaction['workerId'];
 $dataOrganizationUser = getOrganizationUserDetails($con,$staffId);
 $staffName = $dataOrganizationUser['fullName'];
 $staffID = $dataOrganizationUser['staffId'];
 //JOB NAME
 $jobName = $obj->{'jobName'};
 //JOB CREATED DATE
 $jobCreatedDate = $obj->{'createdDate'};
 //JOB START DATE
 if ($obj->{'startTime'} == NULL) {
   $jobStartDate = " - ";
 }else {
   $jobStartDate = $obj->{'endTime'};
 }
 //JOB END DATE
 if ($obj->{'endTime'} == NULL) {
   $jobEndDate = " - ";
 }else {
   $jobEndDate = $obj->{'endTime'};
 }

 //ACTION
 $action = $obj->{'action'};
 if ($action==NULL) {
   $action = "<i style='color:grey'>not filled</i>";
 }
 //METER READING
 $meter1 = $obj->{'meter1'};
 if ($meter1==NULL) {
   $meter1 = "<i style='color:grey'>not filled</i>";
 }
 $meter2 = $obj->{'meter2'};
 if ($meter2==NULL) {
   $meter2 = "<i style='color:grey'>not filled</i>";
 }
 $meter3 = $obj->{'meter3'};
 if ($meter3==NULL) {
   $meter3 = "<i style='color:grey'>not filled</i>";
 }
 $meter4 = $obj->{'meter4'};
 if ($meter4==NULL) {
   $meter4 = "<i style='color:grey'>not filled</i>";
 }
 $meterTotal = $obj->{'meterTotal'};
 if ($meterTotal==NULL) {
   $meterTotal = "<i style='color:grey'>not filled</i>";
 }
 //REMARKS
 $remarks = $obj->{'remarks'};
 if($remarks == NULL){
     $remarks = "<i style='color:grey'>not filled</i>";
 }
 //STATUS
 $statusCode = $obj->{'status'};
 if ($statusCode == 0) {
   $status = "COMPLETED";
 }elseif ($statusCode == 1) {
   $status = "NEW";
 }elseif ($statusCode == 2) {
   $status = "PENDING";
 }elseif ($statusCode == 3) {
   $status = "IN PROGRESS";
 }
 //ITEMS DETAIL
 $transId=$dataJobTransaction['id'];
 $jobItemList= fetchJobItemList($con,$transId);
 //SIGNATURE
 $signature = $obj->{'signaturePath'};
 //ASSIGN BY
 $staffId = $obj->{'createdBy'};
 $dataOrganizationUser2 = getOrganizationUserDetails($con,$staffId);
 $assignBy = $dataOrganizationUser2['fullName'];
 //SPAREPARTS
 $dataSpareparts = fetchSparepartbyrefNo($con,$jobId); //$jobId is equal to $refNo
 //CHARGES
 $zone = $obj->{'zone'};
 $service = $obj->{'service'};
 //CUSTOMER'S NAME
 $csName = $obj->{'cName'};

 $td = "style='border: 1px solid black;'";
 $width = "style='width:100%;font-size:10px; border: 1px solid black; border-collapse: collapse;'";
 $th = "style='background: #B5B5B5; border: 1px solid black; color: black;text-align: left;'";
 $table="Assign by: ".$assignBy;
 $table.="<div class='table-responsive'>";
 $table.="<table ".$width.">";
 //REF NO.
 $table.="<tr><th ".$th.">REF NO.</th>";
 $table.="<td ".$td.">".$refNo."</td></tr>";
 //CLIENT
 $table.="<tr><th ".$th.">CLIENT</th>";
 $table.="<td ".$td.">".$clientCompanyName."</td></tr>";
 //STAFF NAME
 $table.="<tr><th ".$th.">STAFF NAME</th>";
 $table.="<td ".$td.">".$staffName."</td></tr>";
 //STAFF ID
 //$table.="<tr><th ".$th.">STAFF ID</th>";
 //$table.="<td ".$td.">".$staffID."</td></tr>";
 //JOB NAME
 $table.="<tr><th ".$th.">JOB NAME</th>";
 $table.="<td ".$td.">".$jobName."</td></tr>";
 //JOB CREATED DATE
 $table.="<tr><th ".$th.">JOB CREATED DATE</th>";
 $table.="<td ".$td.">".$jobCreatedDate."</td></tr>";
 //JOB START DATE
 $table.="<tr><th ".$th.">START DATE</th>";
 $table.="<td ".$td.">".$jobStartDate."</td></tr>";
 //JOB END DATE
 $table.="<tr><th ".$th.">END DATE</th>";
 $table.="<td ".$td.">".$jobEndDate."</td></tr>";
 //ACTION TAKEN
 $table.="<tr><th ".$th.">ACTION</th>";
 $table.="<td ".$td.">".$action."</td></tr>";
 //ACTION TAKEN
 $table.="<tr><th ".$th.">REMARKS</th>";
 $table.="<td ".$td.">".$remarks."</td></tr>";
 //LANDSCAPE COMPANY
 if ($_SESSION['orgType']==5) {
   $landscapeDetails = fetchJobDetails($con,$jobId);

   //MACHINERY
   $table.="<tr><th ".$th.">MACHINERY</th>";
   $table.="<td ".$td.">".$landscapeDetails['machinery']."</td></tr>";

   //TOTAL TREE
   $table.="<tr><th ".$th.">TOTAL TREE</th>";
   $table.="<td ".$td.">".$landscapeDetails['totalTree']."</td></tr>";

   //OTHER WORK
   $table.="<tr><th ".$th.">OTHER WORK</th>";
   $table.="<td ".$td.">".$landscapeDetails['otherWork']."</td></tr>";

   //MAN DAYS
   $table.="<tr><th ".$th.">MAN DAYS</th>";
   $table.="<td ".$td.">".$landscapeDetails['manDays']."</td></tr>";

   //TIME IN
   $table.="<tr><th ".$th.">TIME IN</th>";
   $table.="<td ".$td.">".$landscapeDetails['timeIn']."</td></tr>";

   //TIME OUT
   $table.="<tr><th ".$th.">TIME OUT</th>";
   $table.="<td ".$td.">".$landscapeDetails['timeOut']."</td></tr>";
 }
 //PHOTOCOPY COMPANY
 if ($_SESSION['orgType']==1) {
   //PRODUCT
   $table.="<tr><th ".$th.">PRODUCT</th>";
   $table.="<td ".$td.">".productTableList($jobId)."</td></tr>";

   //METER READING
   $table.="<tr><th ".$th.">METER READING</th>";
   $table.="<td ".$td."><table style='width:100%;'>";
   $table.="<tr><th style='background: #B5B5B5; border: 1px solid black; color: black;width:20%;'>&bull;METER 1</th><td style='border: 1px solid black;width:80%;'>".$meter1."</td></tr>";
   $table.="<tr><th style='background: #B5B5B5; border: 1px solid black; color: black;width:20%;'>&bull;METER 2</th><td style='border: 1px solid black;width:80%;'>".$meter2."</td></tr>";
   $table.="<tr><th style='background: #B5B5B5; border: 1px solid black; color: black;width:20%;'>&bull;METER 3</th><td style='border: 1px solid black;width:80%;'>".$meter3."</td></tr>";
   $table.="<tr><th style='background: #B5B5B5; border: 1px solid black; color: black;width:20%;'>&bull;METER 4</th><td style='border: 1px solid black;width:80%;'>".$meter4."</td></tr>";
   $table.="<tr><th style='background: #B5B5B5; border: 1px solid black; color: black;width:20%;'>&bull;TOTAL</th><td style='border: 1px solid black;width:80%;'>".$meterTotal."</td></tr>";
   $table.="</table></td></tr>";

   //SPAREPARTS
   $table.="<tr><th ".$th.">SPARE PARTS REQUEST</th>";
   $table.="<td ".$td.">";
   if ($dataSpareparts) {
     $i=1;
     foreach($dataSpareparts as $data){
         $table.=$i.") ".$data['description']." (Quantity<b>: ".$data['qty']."</b>)<br>";
         $i++;
     }
   }else {
     $table.="<i style='color:grey'>no spareparts</i>";
   }

   //CHARGES
   if(isset($zone) || isset($service)){
       $table.= "<table style='border-collapse: collapse;border: 1px solid black;'>";
       if(isset($zone) && $zone!=NULL ){
           $table.="<tr><th style='border: 1px solid black;background: #B5B5B5;'>Zone Charge</th><td style='border: 1px solid black;'>RM".$zone."</td></tr>";
       }
       if(isset($service)  && $service!=NULL ){
           $table.="<tr><th style='border: 1px solid black;background: #B5B5B5;'>Service Charge</th><td style='border: 1px solid black;'>RM".$service."</td></tr>";
       }
       $table.= "</table>";
   }

   $table.="</td></tr>";
 }

 //REMARKS
 //$table.="<tr><th ".$th.">REMARKS</th>";
 //$table.="<td ".$td.">".$remarks."</td></tr>";
 //STATUS
 $table.="<tr><th ".$th.">STATUS</th>";
 $table.="<td ".$td.">".$status."</td></tr>";

  //GEOLOCATION
 $table.="<tr><th ".$th.">LOCATION</th>";
 //debug
 $table.="<td ".$td."><div id='staticMap".$jobId."'></div></td></tr>";

 //DETAILS
 //$table.="<tr><th ".$th.">ITEMS DETAIL</th>";
 //$table.="<td ".$td.">";
 //if ($jobItemList) {
//   $i=1;
//   foreach($jobItemList as $item){
//       $table.=$i.") ".$item['itemName']." (Quantity<b>: ".$item['qty']."</b>)<br>";
//       $i++;
//   }
 //}else {
  // $table.="<i>[empty]</i>";
 //}
 //$table.="</td></tr>";

 //SIGNATURE
 if($statusCode==0){
 $table.="<tr><th ".$th.">CUSTOMER'S<br>ACKNOWLEDGEMENT</th>";
 $table.="<td ".$td."> <br>Name: ".$csName."<br><br><img src='../../resources/".$signature.".png' width='200px' alt='cannot load signature'></u></td></tr></table></div>";
 }

 $_SESSION['printStaffJob'] = $table;
 echo $table;
 //echo $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/"."resources/".$signature.".png";
//  echo "<script> window.print() </script>";

}


?>