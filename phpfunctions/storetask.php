<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";
error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/product.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/product.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/autoNum.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/phpfunctions/mail.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/organization.php";

function make_Task_Store($problem, $problemDetails,$requireTime, $requireDate, $companyId) {
    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
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
        $invoiceNumber = null;

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
    if(isset($_POST['comType'])) {
        $comtype = $_POST['comType'];
    }
    $uploadFileName=null;
//                                    $con, $problem, $problemDetails, $occuredDate, $picName, $picContactNo, $requireDate, $createdDate, $createdBy, $status, $companyId, $timeFrame, $invoiceNumber, $orgId,$comtype,$uploadFileName);
    $saveSuccess = createComplaint($con, $problem, $problemDetails, $occuredDate, $picName, $picContactNo, $requireDate, $createdDate, $createdBy, $status, $companyId, $timeFrame, $invoiceNumber, $orgId,$comType,$uploadFileName);
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
            require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";
            $companyDetails = fetchClientCompanyDetails($con, $companyId);
            $orgDetails = getOrganizationDetails($con, $orgId);
            //$orgAddress=$orgDetails[];
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
			<strong>FAILED!</strong> FAILED TO CREATE JOB / REQUEST\n</div>\n";
    }
}

function assignTaskStore($workerType,$workerId){
    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
    require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/phpfunctions/job.php");
    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
            <strong>FAILED!</strong> FAILED ASSIGN JOB\n
            </div>\n";
    $con=connectDb();
    $complaintId=$_SESSION['complaintId'];
    $stphone=json_decode($_SESSION['stphone'], true);
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
        require_once( $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] ."/query/clientComplaint.php");
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

        $transactionId=createWorkerJobTransaction($con,$jobId,$createdDate,$createdBy,$remarks,$status,$orgId,$assignType,$workerId);
        if($transactionId>0){
            $messageStatus="O";
            $success=updateClientComplaint($con,$complaintId,$complaintDetails['issueName'],$complaintDetails['issueDetail'],
                $complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],
                $complaintDetails['createdDate'],$complaintDetails['createdBy'],$messageStatus,$complaintDetails['status'],
                $complaintDetails['companyId'],$complaintDetails['orgId']);
            if($success){

                $orgDetails=getOrganizationDetails($con,$orgId);
                $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
                    <strong>SUCCESS!</strong> JOB ASSIGNED SUCCESSFULLY\n
                    </div>\n";
                $orgAddress=$orgDetails['address1'].",";
                if($orgDetails['address2']!=null){
                    $orgAddress.="<br/>".$orgDetails['address2'].",";
                }
                $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";
                $orgAddress.= "<br/>".$orgDetails['state'];
                $footer='<br/><img style="height:100px;max-width:200pt"  id="myorglogo" src="cid:logo_2u">
                    <br/>
                    '.$orgAddress;
                $ticket= sprintf("%07d",$complaintDetails['id']);
                $from=$orgDetails['supportEmail'];
                $fromName=$orgDetails['name'];
                $to=$workerDetails['email'];
                $subject='NEW TASK';
                $body='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].'. Thus, ticket no: '.$ticket.' click on this <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'" target="_blank" >link</a>
                    to view the task description and further action.<br/>Thank You';

                $orgLogo=$_SESSION['orgLogo'];
                $mailMessage=mailTask($from,$fromName,$to,$subject,$body,$orgLogo);
                //Sms Notification
                $smsstatus="";

                if(!empty($nortiSmsNum)){
                    $message='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].'. Thus, ticket no: '.$ticket.' https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'" login your account for more details.';
                    $smsstatus=smsNotification($nortiSmsNum,$subject,$message);
                }
                $_SESSION['feedback'].=$mailMessage;
            }
        }
    }
}
?>