<?php

$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");



if (!isset($_SESSION)) {

    session_name($config['sessionName']);

    session_start();

}

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";

error_reporting(E_ALL ^ E_NOTICE);

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/product.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/product.php";


function make_complaint($problem, $problemDetails,$requireTime, $requireDate, $companyId) {
    $saveSuccess = false;

    $con = connectDb();

   $orgId = $_SESSION['orgId'];

   // $requireDate = null;

    if (!isset($problemDetails)) {

       $problemDetails = null;

    }

        $requireDate = $requireDate . " " . $requireTime;


    if (isset($_POST['timeFrame'])) {

        $timeFrame = $_POST['timeFrame'];

    } else {

        $timeFrame = null;

    }

    //(START)CREATE INVOICE

    if (isset($_POST['checkServiceCharge'])) {

        if (isset($_POST['serviceCharge'])) {

            $serviceCharge = $_POST['serviceCharge'];

        } else {

            $serviceCharge = null;

        }



        if (isset($_POST['invoiceDueDate'])) {

            $invoiceDueDate = $_POST['invoiceDueDate'];

        } else {

            $invoiceDueDate = null;

        }


        if (isset($_POST['attention'])) {

            $attention = $_POST['attention'];

        } else {

            $attention = null;

        }
        $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/invoice.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organization.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/invoice.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/pdffooterlist.php";


        //`clientCompany` TABLE

        if ($companyId == 0) {

            $customerId = $companyId; //Organization

            $rowClientCompany = getOrganizationDetails($con, $_SESSION['orgId']);

            $customerAddress = $rowClientCompany['address1'] . ",<br>" . $rowClientCompany['address2'] . ",<br>" . $rowClientCompany['postalCode'] . $rowClientCompany['city'] . ",<br>" . $rowClientCompany['state'];

            $customerName = $rowClientCompany['name'];

        } else {

            $customerId = $companyId; //Client

            $rowClientCompany = fetchClientCompanyDetails($con, $customerId);

            $customerAddress = $rowClientCompany['address1'] . ",<br>" . $rowClientCompany['address2'] . ",<br>" . $rowClientCompany['postalCode'] . $rowClientCompany['city'] . ",<br>" . $rowClientCompany['state'];

            $customerName = $rowClientCompany['name'];

        }

        //`organization` TABLE

        $rowOrganization = getOrganizationDetails($con, $_SESSION['orgId']);

        $myOrgName = $rowOrganization['name'];

        $myOrgAddress = $rowOrganization['address1'] . ",<br>" . $rowOrganization['address2'] . ",<br>" . $rowOrganization['postalCode'] . $rowOrganization['city'] . ",<br>" . $rowOrganization['state'];

        $orgPhone = $rowOrganization['contact'];

        $orgFaxNo = $rowOrganization['faxNo'];

        //`invoice` TABLE

        $invoiceNumber = getLatestInvoiceNo($con, $_SESSION['orgId']) + 1;

        $invoiceNumber = str_pad($invoiceNumber, 10, '0', STR_PAD_LEFT);

        //`pdffooterlist` TABLE

        $footerId = $_POST['footer'];



        $attention = $_POST['attention']; //Invoice Attention

        $invoiceDate = $_POST['bookingDate']; //Booking Date

        $quotationTotalAmount = $_POST['serviceCharge']; //Service Charge

        $maxItemIndex = 1; //1

        $dueDate = $_POST['invoiceDueDate']; //Invoice Due Date



        $itemName = array($_POST['problem']);

        $itemDescription = array($_POST['problemDetails']);

        $itemCost = array($_POST['serviceCharge']);

        $itemQty = array(1);

        $price = array($_POST['serviceCharge']);



        buildInvoice($customerName, $attention, $myOrgName, $myOrgAddress, $orgPhone, $orgFaxNo, $invoiceNumber, $invoiceDate, $quotationTotalAmount, $maxItemIndex, $dueDate, $customerId, $customerAddress, $itemName, $itemDescription, $itemCost, $itemQty, $price, $footerId);

    } else {

        $invoiceNumber = null;

    }

    $dCreationDate = "";

    $dCpa = "";

    $dCategotry = "";

    $dState = "";

    $dRegion = "";

    $dVsattech = "";

    $dSitename = "";

    //(END)CREATE INVOICE



    $createdBy = $_SESSION['userid'];



    $createdDate = date('Y-m-d H:i:s');

    $occuredDate = date('Y-m-d H:i:s');

    $picName = 'NULL';

    $picContactNo = 'NULL';

    $orgType = $_SESSION['orgType'];



    $status = 2; // new complaint

    // debug
    $comType=2;

    if(isset($_POST['comType'])){
        $comtype=$_POST['comType'];
    }

    $saveSuccess = createComplaint($con, $problem, $problemDetails, $occuredDate, $picName, $picContactNo, $requireDate, $createdDate, $createdBy, $status, $companyId, $timeFrame, $invoiceNumber, $orgId,$comType);



    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/role.php";

    if ($saveSuccess) {
        $_SESSION['saveComplaint']=$saveSuccess;

        if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 6) {

            $dCreationDate = $_POST['dcreatedate']; // Docket Creation Date

            $dCpa = $_POST['cpa']; // CPA

            $dCategotry = $_POST['category']; //Category

            $dState = $_POST['state']; //Stete

          //  $dRegion = $_POST['subregion']; //sub region

            $dVsattech = $_POST['vsattech']; //Vsat Technology

            $dSitename = $_POST['sitename']; //site name

            $dNote="";

            if (isset($_POST['note']) && !empty(trim($_POST['note']))) {

                $dNote = "[".date('Y-m-d')." ".date("h:i")."]: ".$_POST['note']; // note

            }

            $compId = $_SESSION['complaintId']; // last update complaint id

            $saveStateSuccess = createComplaintTelecome($con, $dCreationDate, $dCpa, $dCategotry, $dState, $dRegion, $dVsattech, $dSitename, $dNote, $compId, "", "", "", "", "", "", "", "Open");

            if ($saveStateSuccess) {

                $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong> JOB / REQUEST CREATED SUCCESSFULLY\n

					<br /><a href='".'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php' style='text-decoration: none;color: blue;'>View For Assign Task List</a>

					</div>";

            }

        } else{

            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

			<strong>SUCCESS!</strong> JOB / REQUEST CREATED SUCCESSFULLY\n

			<br /><a href='".'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php' style='text-decoration: none;color: blue;'>View For Assign Task List</a>

			</div>";

        }

    



        //START PRODUCT CHECKBOX

        echo $imax = $_POST['imax'];

        if ($imax > 0) {

            $product = "product";

            $i = 0;

            while ($imax != 0) {

                if (isset($_POST[$product . $i])) {

                    $productId = $_POST[$product . $i];

                    echo $productId . "<br>";

                    $complaintId = $_SESSION['complaintId'];

                    $feedbackComplaintProduct = insertComplaintProduct($con, $complaintId, $productId);

                    if (!$feedbackComplaintProduct) {

                        echo "an error occur<br>";

                        break;

                    }

                }

                $i++;

                $imax--;

            }

        }

        //END PRODUCT CHECKBOX



        if (isOrganization() != true) {



            require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationUser.php";

            require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organization.php";

            require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";



            $companyDetails = fetchClientCompanyDetails($con, $companyId);

            $orgDetails = getOrganizationDetails($con, $orgId);

            //$orgAddress=$orgDetails[];



            require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/mail.php";



            $orgAddress = $orgDetails['address1'] . ",";

            if ($orgDetails['address2'] != null) {

                $orgAddress .= "<br/>" . $orgDetails['address2'] . ",";

            }

            $orgAddress .= "<br/>" . $orgDetails['postalCode'] . " " . $orgDetails['city'] . ",";

            $orgAddress .= "<br/>" . $orgDetails['state'];



            $footer = '<br/><img style="height:100px;max-width:200pt"  id="myorglogo" src="cid:logo_2u">

			<br/>

			' . $orgAddress;



            $from = $companyDetails['emailAddress'];

            $fromName = $orgDetails['name'];

            $to = $orgDetails['supportEmail'];

            $subject = 'NEW TASK / REQUEST';

            $body = 'Job / request has been created by admin@' . $companyDetails['name'] . '. Thus, click on this <a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '" target="_blank" >link</a>

			to view detailed information and further action.<br/>Thank You';

            $orgLogo = $_SESSION['orgLogo'];



            $mailMessage = mailTask($from, $fromName, $to, $subject, $body, $orgLogo);



        }



    } else {



        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n

			<strong>FAILED!</strong> FAILED TO CREATE JOB / REQUEST\n

			</div>\n";

    }




}

function assignTaskSentOut($workerType,$workerId){
    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
    require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/phpfunctions/job.php");
    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
            <strong>FAILED!</strong> FAILED ASSIGN JOB\n
            </div>\n";
        $con=connectDb();
        $complaintId=$_SESSION['complaintId'];
        //debug $nortiSmsNum used for sms nortification
        $stphone=json_decode($_SESSION['stphone'], true);
        //$stphone=json_decode($stphone);
        $nortiSmsNum="";
        foreach ($stphone as $key => $value) {
            if ($value["wid"]==$workerId) {
            $nortiSmsNum=$value["phone"];
            }
        }

        require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/clientComplaint.php");
        $complaintDetails=fetchComplainDetails($con,$complaintId);
        $clientCompanyId=$complaintDetails['companyId'];

        $jobRefNo=null;
        //$rowJobLast = fetchLastRow($con);
        //$jobRefNo = $rowJobLast['refNo'] + 1;
        $jobName=$complaintDetails['issueName'];
        $address=null;
        $vendorId=0;
        $vendorUserId=0;
        if($workerType==="vendors"){
            $vendorId=$workerId;
        }
        $picName=null;
        $picContactNo=null;
        $dateRequire=null;
        $status=2;
if($_SESSION['orgType']==7){
$status=3;
}
        $remarks=null;
        $createdDate=date('Y-m-d H:i:s');
        $startTime=null;
        $endTime=null;
        $createdBy=$_SESSION['userid'];
        $orgId=$_SESSION['orgId'];
        $refId=null;
        $workerDetails=null;
        if($workerType==="vendors"){
            require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/vendoruser.php");
            $workerDetails=fetchVendorAdminDetails($con,$vendorId,$orgId);
            $refId=fetchAutoNum($con,$vendorId);

            require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/organizationUser.php");
            require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/autoNum.php");
            $workerDetails=getOrganizationUserDetails($con,$workerId);
            $refId=fetchOrgAutoNum($con,$orgId);
        }
        $jobNo=$refId['jobNo'];
        $jobRefNo=$refId['jobPrefix'].sprintf('%08d',$jobNo);
        $autoNumId=$refId['id'];
        $jobNo++;
        $updateSuccess=updateJobNo($con,$autoNumId,$jobNo);
        $jobId=createComplaintJob($con,$jobRefNo,$jobName,$address,$clientCompanyId,$vendorId,
        $vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarks,$createdDate,
        $startTime,$endTime,$createdBy,$orgId,$complaintId);


        if($jobId>0){

            require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/jobTransaction.php");

            $status=2;

            $assignType=$workerType;



            $transactionId=createWorkerJobTransaction($con,$jobId,$createdDate,$createdBy,

            $remarks,$status,$orgId,$assignType,$workerId);



            if($transactionId>0){

                $messageStatus="O";

                $success=updateClientComplaint($con,$complaintId,$complaintDetails['issueName'],$complaintDetails['issueDetail'],

                $complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

                $complaintDetails['createdDate'],$complaintDetails['createdBy'],$messageStatus,$complaintDetails['status'],

                $complaintDetails['companyId'],$complaintDetails['orgId']);

                if($success){

                    if (isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) {

                        $docrecivedate="".date('Y-m-d')." ".date("h:i")."";

                        $docremarks="[".$docrecivedate."] Mail acknowledged by ".$_SESSION['name'];;

                        $cid=$complaintDetails['id'];

                        $result = updateDocAssignTime($con,$docrecivedate,$docremarks,$cid);

                    }



                    require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/organization.php");

                    $orgDetails=getOrganizationDetails($con,$orgId);

                    

                    //$orgAddress=$orgDetails[];



                    require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/phpfunctions/mail.php");

                    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

                    <strong>SUCCESS!</strong> JOB ASSIGNED SUCCESSFULLY\n

                    </div>\n";



                    /*

                    $mailMessage="/n<div class='alert alert-warning' role='alert'>\n

                    <strong>FAILED!</strong> MAIL FAILED TO SEND\n

                    </div>\n";

                    */

                    $orgAddress=$orgDetails['address1'].",";

                    if($orgDetails['address2']!=null){

                        $orgAddress.="<br/>".$orgDetails['address2'].",";

                    }

                    $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";

                    $orgAddress.= "<br/>".$orgDetails['state'];



                    $footer='<br/><img style="height:100px;max-width:200pt"  id="myorglogo" src="cid:logo_2u">

                    <br/>

                    '.$orgAddress;



                    $from=$orgDetails['supportEmail'];
                    $fromName=$orgDetails['name'];
                    $to=$workerDetails['email'];
                    $subject='NEW TASK';
                    $body='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].'. Thus, ticket no: '.ticketno($complaintDetails['id']).' click on this <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'" target="_blank" >link</a>
                    to view the task description and further action.<br/>Thank You';
                    $orgLogo=$_SESSION['orgLogo'];
                    $mailMessage=mailTask($from,$fromName,$to,$subject,$body,$orgLogo);
                    //Sms Notification
                    $smsstatus="";

                    if(!empty($nortiSmsNum)){
                        $message='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].'. Thus, ticket no: '.ticketno($complaintDetails['id']).' https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'" login your account for more details.';
                        $smsstatus=smsNotification($nortiSmsNum,$subject,$message);
                    }
                    $_SESSION['feedback'].=$mailMessage;
                }
            }
        }
    }

if (isset($_POST['sentProduct'])) {
   $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO SENT OUT PRODUCT \n
   </div>\n";
  require_once("./clientCompany.php");

   $con=connectDb();

   $compdetails=fetchClientCompanyDetail($_POST['clientCompanyId']);
   $clientName = $compdetails['name'];
 
   $srNo = $_POST['serialnumber'];
   $inCharge = $_POST['incharge'];
   $contract = $_POST['contract'];
   $workertype=$_POST['workerType'];
   $workerId=$_POST['workerId'];
   $vehicleno=$_POST['vehicleno'];
    $worker="";
   $temp=array();
   if(strcasecmp($workertype,"myStaff")==0){
    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationUser.php";
    $temp=fetchOrganizationUserListbyId($con,$_POST['workerId']);
    foreach($temp as $value){
    $worker=$value["name"];}
   }
   elseif(strcasecmp($workertype,"vendors")==0){
    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/vendor.php";
    $temp=fetchVendorDetails($con,$_POST['workerId']);
    $worker=$temp["name"];
   } 

   
   $startdate="";
   $enddate="";
   if(isset($_POST['startdate'])){
   $startdate=$_POST['startdate'];}

    if(isset($_POST['enddate'])){
   $enddate=$_POST['enddate'];}
    $mydate=getdate(date("U"));
   $datetask="$mydate[year]-".sprintf('%02d', $mydate[mon])."-$mydate[mday]";


   $faild=array();
     $output="";

   if(is_array($srNo)){ 
     $count=0;
     $st="";
     $sentoutdetails="";
     $temp="";
     $num=fetchDoNumber($con);
    foreach ($srNo as $value) {
        $temp="";
        if(checkSr($con,$value)){
             $st= updateSentOut($con,$num,$clientName,$value,$inCharge,$contract, $workertype, $worker, $vehicleno, $startdate, $enddate); 
            $faild[]=$st;
            if($st){
            $count++;
            $temp="SR NO: ".$value." UPDATE TO SENT OUT<br />";
            $sentoutdetails.=", ".$value;
            $output.=$temp;
            }else{ 
                $output.="SR NO: ".$value." ALREADY SENT OUT<br />";
            }
        }else{ 
            $faild[]=0;
            $output.="SR NO: ".$value." NOT AVAILABLE SENT OUT<br />";
        }
        
    }

    if($count>0){
    $problem="Sent Out For ".$contract." D/O Number ".$num;
    $problemDetails="Product Sr No for delivery ".$sentoutdetails;
    $requireDate=date("Y-m-d");
    $companyId=$_POST['clientCompanyId'];
    $requireTime=date("h:i:s");
    make_complaint($problem, $problemDetails,$requireTime, $requireDate, $companyId);

    if(isset($_SESSION['saveComplaint']) && $_SESSION['saveComplaint']==true){
        assignTaskSentOut($workertype,$workerId);
        }
     }
    $_SESSION['feedback']="<div class='alert alert-info' role='alert'>\n
     <strong>INFO!</strong>".$output." \n
     </div>\n";
 }else{
     $value=$srNo;
     if(checkSr($con,$value)){ $feedback = updateSentOut($con,$clientName,$value,$inCharge,$contract, $workertype, $worker, $vehicleno, $startdate, $enddate); 
                             }
}
   if ($feedback) {
     $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>PRODUCT IS SUCCESSFULLY SENT OUT \n
     </div>\n";
   }
   header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/product/sentProduct.php");
 }

?>