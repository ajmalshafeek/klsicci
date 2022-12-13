<?php

$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");



if (!isset($_SESSION)) {

    session_name($config['sessionName']);

    session_start();

}



require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/role.php";



if (isset($_POST['makeComplaint'])) {


    $saveSuccess = false;

    $con = connectDb();

    $problem = $_POST['problem'];

    $orgId = $_SESSION['orgId'];

    $requireDate = null;

    if (isset($_POST['problemDetails'])) {

        $problemDetails = $_POST['problemDetails'];

    } else {

        $problemDetails = null;

    }



    if (isset($_POST['bookingTime'])) {

        $requireTime = $_POST['bookingTime'];

    }



    if (isset($_POST['bookingDate'])) {

        $requireDate = $_POST['bookingDate'];

        $requireDate = $requireDate . " " . $requireTime;

    }



    if (isset($_POST['clientCompanyId'])) {

        $companyId = $_POST['clientCompanyId'];

    } else {

        $companyId = $_SESSION['companyId'];

    }



    if (isset($_POST['timeFrame'])) {

        $timeFrame = $_POST['timeFrame'];

    } else {

        $timeFrame = null;

    }
	$uploadFileName="";

	if(file_exists($_FILES['myfile']['tmp_name']) || is_uploaded_file($_FILES['myfile']['tmp_name'])) {
		$_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
		$name = $_FILES['myfile']['name'];

		$temp_name = $_FILES['myfile']['tmp_name'];
		$size = $_FILES['myfile']['size'];
		if ($_FILES['myfile']['error'] !== UPLOAD_ERR_OK) {
			$_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
			if (isOrganization() == true) {

				header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/complaint/makeComplaint.php");

			} else {

				header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/complaint/makeComplaint.php");

			}
		}
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['myfile']['tmp_name']);
		$ok = FALSE;
		$extension = "";
		//application/pdf, application/msword, 	application/vnd.openxmlformats-officedocument.wordprocessingml.document
		switch ($mime) {
			case 'application/pdf':
				$extension = ".pdf";break;
			case 'application/msword':
				$extension = ".doc";break;
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
				$extension = ".docx";break;
			default:
				$_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>UNKNOWN FILE!</strong> UPLOAD FILE IS FAILED</div>";
		}


		$uploadFileName = time() . "" . $extension;
		$path = "/resources/" . $orgId . "/complaint/";
		$jobDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
		if (!file_exists($jobDirectory)) {
			mkdir($jobDirectory, 0777, TRUE);
			copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $jobDirectory . '/index.php');
		}
		move_uploaded_file($temp_name, $jobDirectory . "/$uploadFileName");
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




        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/invoice.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organization.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/invoice.php";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/pdffooterlist.php";



        //`clientCompany` TABLE

        if ($_POST['clientCompanyId'] == 0) {

            $customerId = $_POST['clientCompanyId']; //Organization

            $rowClientCompany = getOrganizationDetails($con, $_SESSION['orgId']);

            $customerAddress = $rowClientCompany['address1'] . ",<br>" . $rowClientCompany['address2'] . ",<br>" . $rowClientCompany['postalCode'] . $rowClientCompany['city'] . ",<br>" . $rowClientCompany['state'];

            $customerName = $rowClientCompany['name'];

        } else {

            $customerId = $_POST['clientCompanyId']; //Client

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

    $occuredDate = null;

    $picName = 'NULL';

    $picContactNo = 'NULL';

    $orgType = $_SESSION['orgType'];
    $comtype=1;

    if(isset($_POST['comType'])){
        $comtype=$_POST['comType'];
    }

    $status = 2; // new complaint

    // debug

    $saveSuccess = createComplaint($con, $problem, $problemDetails, $occuredDate, $picName, $picContactNo, $requireDate, $createdDate, $createdBy, $status, $companyId, $timeFrame, $invoiceNumber, $orgId,$comtype,$uploadFileName);



    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/role.php";



    if ($saveSuccess) {

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
	 if($_SESSION['complaintExtra']&&isset($_SESSION['complaintExtra'])){


		$modelNo="";
		$eqDetails="";
        $smrNo="";
		$jobType="";
		$rootCause="";
		$partsReplace="";
		$comments="";
		$star=0;
		 $lastRepair=$_POST['lastRepair'];
		 $requestType=$_POST['requestType'];
		 $lastRequest=$_POST['lastRequest'];
		 $compId = $_SESSION['complaintId'];

		 $saveStateSuccess = createComplaintExtra($con, $modelNo, $eqDetails, $smrNo, $jobType, $rootCause, $partsReplace, $comments, $star, $lastRepair, $requestType, $lastRequest, $compId);

		 if ($saveStateSuccess) {

			 $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong> JOB / REQUEST CREATED SUCCESSFULLY\n

					<br /><a href='".'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php' style='text-decoration: none;color: blue;'>View For Assign Task List</a>

					</div>";

		 }
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



            $footer = '<br/><table width="150px"><tr><td><img style="height:80px;max-width:150px"  id="myorglogo" src="cid:logo_2u"></td></tr>></table>

			<br/>

			' . $orgAddress;



            $from = $companyDetails['emailAddress'];

            $fromName = $orgDetails['name'];

            $to = $orgDetails['supportEmail'];

            $subject = 'NEW TASK / REQUEST';

            $body = 'Job / request has been created by admin@' . $companyDetails['name'] . '. Thus, click on this <a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '" target="_blank" >link</a>

			to view detailed information and further action.<br/>
			<br/>Task:'.$problem.'<br/> Details:'.$problemDetails.
			'<br/>'.'Thank You' ;

            $orgLogo = $_SESSION['orgLogo'];



            $mailMessage = mailTask($from, $fromName, $to, $subject, $body, $orgLogo);



        }



    } else {



        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n

			<strong>FAILED!</strong> FAILED TO CREATE JOB / REQUEST\n

			</div>\n";

    }



    if (isOrganization() == true) {

        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/complaint/makeComplaint.php");

    } else {

        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/complaint/makeComplaint.php");

    }

}



if (isset($_POST['updateComplaint'])) {



    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");



    $con=connectDb();

    $complaintId=$_POST["complaintIdValue"];



    //START PRODUCT CHECKBOX

    $imax = $_POST['imax'];

    if ($imax > 0) {

        $product = "product";

        $productRef = "productRef";

        $i = 0;

        while ($imax != 0) {

            if (isset($_POST[$product.$i])) { //checked

                $productId = $_POST[$product.$i];

                $dataListComplaintProduct = fetchComplaintProductByComplaintIdAndProductId($con,$complaintId,$productId);

                $numRow = sizeof($dataListComplaintProduct);

                if ($numRow==0) {

                    insertComplaintProduct($con,$complaintId,$productId);

                }

            }else { //unchecked

                $productId = $_POST[$productRef.$i];

                $dataListComplaintProduct = fetchComplaintProductByComplaintIdAndProductId($con,$complaintId,$productId);

                $numRow = sizeof($dataListComplaintProduct);

                if ($numRow>0) {

                    deleteComplaintProduct($con,$complaintId,$productId);

                }

            }

            $i++;

            $imax--;

        }

    }

    //END PRODUCT CHECKBOX



    $problem = $_POST['problem'];

    $problemDetails = $_POST['problemDetails'];

    $clientCompanyId = $_POST['clientCompanyId'];







    $jobDetail=fetchJobDetailsByComplaintId($con,$complaintId);

    $jobTransDetail=fetchJobTransByJobId($con,$jobDetail['id']);

    if($jobDetail!=null){



        updateJobName($con,$jobDetail['id'],$problem);



    }



    $complaintDetails=fetchComplainDetails($con,$complaintId);



    $updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$problem,$problemDetails,

        $complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

        $complaintDetails['createdDate'],$complaintDetails['createdBy'],$complaintDetails['messageStatus'],$complaintDetails['status'],

        $clientCompanyId,$complaintDetails['orgId']);

    // telecom service remarks update

    $docRemarks="";

    if($updateSuccess&&isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){

        $cpa = $_POST['cpa'];

        $category = $_POST['category'];

        $state = $_POST['state'];

        $vsattech = $_POST['vsattech'];



        $sitename = $_POST['sitename'];

        $troubleshoot = $_POST['troubleshoot'];

        $note = $_POST['note'];

        $remarks = $_POST['remarks'];

        $resolution = $_POST['resolution'];



            $result=updateDocRemarks($con,$complaintDetails['id'],$cpa,$category,$state,$vsattech,$sitename,$troubleshoot,$note,$remarks,$resolution);

        }

    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

          <strong>SUCCESS!</strong>SUCCESSFULLY EDITED\n

          </div>\n";

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php");

 }

if (isset($_GET['complaintId'])) {

    $details = null;

    $complaintId = $_GET['complaintId'];

    $details = getComplaintDetailsById($complaintId);

    echo json_encode($details);

}



if (isset($_GET['checkIfJob'])) {

    $complaintId = $complaintId = $_GET['checkIfJob'];

    $result = isComplaintAsJob($complaintId);

    echo $result;

}



if (isset($_GET['workerType'])) {

    $dropDown = "<select class='form-control'  name='workerId' id='workerId' required>";

    $optionList = "";



    if ($_GET['workerType'] === 'myStaff') {


        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organizationUser.php";

        $dropDown .= dropDownOptionListOrgStaffListActive(); //phpfunctions/organizationUser.php

    } else {

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/vendor.php";

        $optionList = dropDownOptionListActiveVendor(); //phpfunctions/vendor.php
    }

    $dropDown .= $optionList;

    $dropDown .= "</select>";

    echo $dropDown;

}



function noOfNewComplaint()

{

    $con = connectDb();

    $messageStatus = "N";

    $orgId = $_SESSION['orgId'];

    $newComplaint = fetchComplaintListByMessageStatus($con, $orgId, $messageStatus);

    return sizeof($newComplaint);

}



if (isset($_GET['notf'])) {

    $newComplaint = 0;

    $newComplaint = noOfNewComplaint();

    echo $newComplaint;

}



function myComplaintsTable()

{

    $con = connectDb();



    $picName = null;

    $occuredDate = null;

    $createdDate = null;

    $createdBy = null;

    $status = null;

    $companyId = $_SESSION['companyId'];

    $orgId = $_SESSION['orgId'];



    $complaint = fetchComplainList($con, $picName, $occuredDate, $createdDate, $createdBy, $status, $companyId, $orgId);



    $table = "";

    $table = "<div class='table-responsive'>\n";

    $table .= "<table class='table table-hover '>";

    $table .= "<thead>";



    $table .= "<tr>";

    $table .= "<th style='width:10%' scope='col'>DATE</th>";

    $table .= "<th scope='col'>CREATED BY</th>";

    $table .= "<th scope='col'>ISSUE</th>";

    $table .= "<th scope='col'>DESCRIBTION</th>";

    $table .= "<th style='width:10%' scope='col'>STATUS</th>";

    $table .= "</tr>";



    $table .= "</thead>";



    $table .= "<tbody>";



    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientUser.php";



    foreach ($complaint as $data) {

        $userDetail = fetchClientUserDetail($con, $data['createdBy']);



        //data-toggle='modal' data-target='#messageContent'

        $table .= "<tr > ";



        $table .= "<td  style='cursor:default' >" . $data['createdDate'] . "</td>";

        $table .= "<td  style='cursor:default'  >" . $userDetail['fullName'] . "</td>";

        $table .= "<td  style='cursor:default' >" . $data['issueName'] . "</td>";

        $table .= "<td  style='cursor:default'  >" . $data['issueDetail'] . "</td>";



        $table .= "<td  style='cursor:default' >";

        if ($data['status'] === 2) {

            $table .= "PENDING";

        } else if ($data['status'] === 0) {

            $table .= "COMPLETE";

        }

         else if ($data['status'] === 4) {

          $table .= "RESOLVED";

    }

        $table .= "</td>";



        $table .= "</tr>";

    }



    $table .= "</tbody>";

    $table .= "</table>";

    $table .= "</div>";

    echo $table;

}



function orgComplaintListTable($picName_, $occuredDate_, $createdDate_, $createdBy_, $status_, $companyId_, $orgId_)

{


    $con = connectDb();

    $table = "";

    $picName = null;

    $occuredDate = null;

    $createdDate = null;

    $createdBy = null;

    $status = null;

    $companyId = null;

    $orgId = null;


    if ($picName_ != null) {

        $picName = $picName_;

    }

    if ($occuredDate_ != null) {

        $occuredDate = $occuredDate_;

    }

    if ($createdDate_ != null) {

        $createdDate = $createdDate_;

    }

    if ($createdBy_ != null) {

        $createdBy = $createdBy_;

    }

    if ($status_ >= 0 && $status_ !== null) {

        $status = $status_;

    }

    if ($companyId_ != null) {

        $companyId = $companyId_;

    }

    if ($orgId_ != null) {

        $orgId = $orgId_;

    }


    //debug


    if ($status === "UNRESOLVED") {

        $complaint = fetchUnresolvedComplainList($con, $picName, $occuredDate, $createdDate, $createdBy, $companyId, $orgId);

    } else {

        $complaint = fetchComplainList($con, $picName, $occuredDate, $createdDate, $createdBy, $status, $companyId, $orgId);

    }


    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");


    $table .= '<table class="table table-hover table-bordered" id="clientComplaintTable"

		width="100%" cellspacing="0" role="grid" style="width: 100%;">';

    $table .= "<thead class='thead-dark'>";


    $table .= "<tr>";

    $table .= "<th scope='col'>Ticket No</th>";

    $table .= "<th scope='col' style='width:10%'>";
    if ($_SESSION['orgType'] == 7) {
        $table .= "Request";
    } else {
        $table .= "Incident";
    }
    $table .= " Date</th>";

    /*

    if($config['customerComplaintFormBookingDate']==true)

    {

    $table.="<th scope='col' style='width:10%' >REQUIRED DATE</th>";

    }

     */
    if ($_SESSION['externalUse'] == 1) {
        $table .= "<th scope='col'>" . $_SESSION['clientas'] . "</th>";
    }
    if ($_SESSION['orgType'] == 7) {
        $table .= "<th scope='col'>Task</th>";
    } else {
        $table .= "<th scope='col'>Issue</th>";
    }
    $table .= "<th scope='col'>Description</th>";

    $table .= "<th style='width:15%' scope='col'>Assignment To</th>";

    $table .= "<th style='width:10%' scope='col'>Status</th>";


    $table .= "</tr>";


    $table .= "</thead>";

    $table .= "<tbody>";


    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    //require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/jobTransaction.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/vendor.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationUser.php";

    $count = 1;
    $sizeof = sizeof($complaint);
    foreach ($complaint as $data) {
        if(!empty($data['id'])){
        if(is_array($data)){
        $jobId = fetchJobByComplaintId($con, $data['id']);
        //    $userDetail=fetchClientUserDetail($con,$data['createdBy']);
        if ($data['companyId'] == 0) {
            $clientId = 0;
        } else {
            $clientDetail = fetchClientCompanyDetails($con, $data['companyId']);
            if(empty($clientDetail['id'])){}else{
            $clientId = $clientDetail['id'];}
        }
        //data-toggle='modal' data-target='#messageContent'
        $font_weight = 'normal';
        if ($data['messageStatus'] === 'N') {
            $font_weight = 'bold';
        }
        if ($count % 2 == 0) {
            $color = "class='color'";
        } else {
            $color = "";
        }
        $table .= "<tr data-toggle='modal' data-target='#printModal' style='cursor:pointer;' onclick='clientId(" . $clientId . "," . $data['id'] . ",";
        if(empty($jobId['id'])){

        }
        else{ $table.=$jobId['id'];}
                $table.= ")' style='font-weight:" . $font_weight . ";' data-value='" . $data['id'] . "'> ";
        $table .= "<td  >" . sprintf("%07d", $data['id']) . "</td>";

        $requireDateTD = $data['requireDate'];
        //h:i:s A
        $table .= "<td  >" . date('d-M-Y ', strtotime($data['createdDate'])) . "</td>";

        /*
        if($config['customerComplaintFormBookingDate']==true)
        {
        $table.="<td  >".date('d-M-Y', strtotime($requireDateTD))."</td>";
        }
         */
        if ($_SESSION['externalUse'] == 1) {
            //(START)CLIENT NAME

            $table .= "<td   >";
            if ($data['companyId'] == 0) {
                $dataOrg = fetchOrganizationDetails($orgId);
                $table .= $dataOrg['name'];
            } else {
                if(empty($clientDetail['name'])){}else{
                $table .= $clientDetail['name'];}
            }

            $table .= "</td>";

            //(END)CLIENT NAME
        }
        $table .= "<td  >" . $data['issueName'] . "</td>";

        $table .= "<td  >" . $data['issueDetail'] . "</td>";

        if ($data['messageStatus'] == "N") {

            $table .= "<td>";

            $table .= "<button type='button' class='btn btn-secondary' onclick='resetWorkerModal(this.value)' value='" . $data['id'] . "' data-toggle='modal' data-target='#assignWorkerModel'>Assign";
            if ($_SESSION['orgType'] == 7 && $data['type'] == 2) {
                $table .= " Driver";
            } else {
                $table .= " To";
            }
            //  $table .=" Staff";
            $table .= "</button>";

        } else {

            $table .= "<td data-toggle='modal' data-target='#reassign'>";

            $jobDetails = fetchJobByComplaintId($con, $data['id']);

            if ($jobDetails == null) {

                $table .= "N/A";

            } else {

                $jobTransactionDetails = fetchJobTransByJobId($con, $jobDetails['id']);

                if ($jobTransactionDetails['assignType'] === "myStaff") {

                    $orgStaffDetails = getOrganizationUserDetails($con, $jobTransactionDetails['workerId']);
                    if(empty($orgStaffDetails['name'])){$table .="N/A";}else{
                   $table .= $orgStaffDetails['name'] . "<br/>[" . $orgStaffDetails['staffId'] . "]";}

                    } else {
                    $vendorDetails = fetchVendorDetails($con, $jobTransactionDetails['workerId']);
                    if(empty($vendorDetails['name'])){}else{$table .= $vendorDetails['name'];}
                }
            }
        }

        $table .= "</td>";

        $table .= "<td>";
        if ($data['status'] === 0) {
            $table .= "<div style='background-color: #D3F9C3;padding: 5px;'>COMPLETE</div>";
        } else if ($data['status'] === 2) {
            $table .= "<div style='background-color: #FAE5C3;padding: 5px;'>PENDING</div>";
        } else if ($data['status'] === 3) {
            $table .= "<div style='background-color: #C3D7FA;padding: 5px;'>IN PROGRESS</div>";
        } else if ($data['status'] === 4) {
            $table .= "<div style='background-color: #D3F9C3;padding: 5px;'>RESOLVED</div>";
        } else if ($data['status'] === 5) {
            $table .= "<div style='background-color: #FAC3F8;padding: 5px;'>VERIFICATION</div>";
        } else if ($data['status'] === 6) {
            $table .= "<div style='background-color: #D3F9C3;padding: 5px;'>CLOSED</div>";
        } else if ($data['status'] === 7) {
            $table .= "<div style='background-color: #F8A9A9;padding: 5px;'>REOPEN</div>";
        }


        $table .= "</td>";


        $table .= "</tr>";
        $count++;
    } }
}


    $table .= "</tbody>";

    $table .= "</table>";



    return $table;



}



function orgComplaintListTableSLA($picName_, $occuredDate_, $createdDate_, $createdBy_, $status_, $companyId_, $orgId_)

{



    $con = connectDb();

    $table = "";

    $picName = null;

    $occuredDate = null;

    $createdDate = null;

    $createdBy = null;

    $status = null;

    $companyId = null;

    $orgId = null;



    if ($picName_ != null) {

        $picName = $picName_;

    }

    if ($occuredDate_ != null) {

        $occuredDate = $occuredDate_;

    }

    if ($createdDate_ != null) {

        $createdDate = $createdDate_;

    }

    if ($createdBy_ != null) {

        $createdBy = $createdBy_;

    }

    if ($status_ >= 0 && $status_ !== null) {

        $status = $status_;

    }

    if ($companyId_ != null) {

        $companyId = $companyId_;

    }

    if ($orgId_ != null) {

        $orgId = $orgId_;

    }



    if ($status === "UNRESOLVED") {

        $complaint = fetchUnresolvedComplainList($con, $picName, $occuredDate, $createdDate, $createdBy, $companyId, $orgId);



    } else {

        $complaint = fetchComplainList($con, $picName, $occuredDate, $createdDate, $createdBy, $status, $companyId, $orgId);

    }



    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");



    $table .= '<table class="table table-hover table-bordered" id="clientComplaintTable"

    width="100%" cellspacing="0" role="grid" style="width: 100%;">';

    $table .= "<thead class='thead-dark'>";



    $table .= "<tr>";
	if($_SESSION['externalUse']==1){
    $table .= "<th scope='col'>Client</th>";}
	$table .= "<th scope='col'>Assigned To</th>";

    $table .= "<th scope='col'>Issue</th>";

    $table .= "<th scope='col'>Status</th>";

    $table .= "<th style='width:15%' scope='col'>Assign  Time</th>";

    $table .= "<th style='width:10%' scope='col'>Time Frame</th>";

    $table .= "</tr>";



    $table .= "</thead>";

    $table .= "<tbody>";



    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    //require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/jobTransaction.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/vendor.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationUser.php";



    foreach ($complaint as $data) {

        $jobId = fetchJobByComplaintId($con, $data['id']);

        //    $userDetail=fetchClientUserDetail($con,$data['createdBy']);

        if ($data['companyId'] == 0) {

            $clientId = 0;

        } else {

            $clientDetail = fetchClientCompanyDetails($con, $data['companyId']);

            $clientId = $clientDetail['id'];

        }

        //data-toggle='modal' data-target='#messageContent'

        $font_weight = 'normal';

        if ($data['messageStatus'] === 'N') {

            $font_weight = 'bold';

        }


//starttime
        //(START)CHECK TIMEFRAME

        $backgroundSLA = "";

        $resultTimeFrame = "";

        if ($jobId['id'] != null) {

            date_default_timezone_set("Asia/Kuala_Lumpur");

            $resultTimeFrame .= "<td>";

            $timeAdjustString = "+" . $data['timeFrame'] . " hour";

            $datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($timeAdjustString, strtotime($jobId['createdDate']))), new DateTimeZone('Asia/Kuala_Lumpur'));

            $datetime2 = new DateTime(date("Y-m-d H:i:s"), new DateTimeZone('Asia/Kuala_Lumpur'));

            if ($datetime2 > $datetime1) { //EXCEED DEADLINE

                $backgroundSLA = "background:#FF7575";

                $resultTimeFrame .= "Exceeded";

            } else {

                $interval = $datetime1->diff($datetime2);

                //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');

                $elapsed = $interval->format('%h hours %i minutes');

                //(START)CALCULATE SLA %

                $maxDate2 = new DateTime(date('Y-m-d H:i:s', strtotime($jobId['createdDate'])), new DateTimeZone('Asia/Kuala_Lumpur'));

                $maxInterval = $datetime1->diff($maxDate2);

                $currVal = ($interval->format('%h')) * 60 + ($interval->format('%i'));

                $maxVal = ($maxInterval->format('%h')) * 60 + ($maxInterval->format('%i'));

                $percentageSLA = 100 - ($currVal / $maxVal) * 100;

                //(END)CALCULATE SLA %



                //SHOW TIME DIFF

                $resultTimeFrame .= $elapsed;



                //SHOW PROGRESS BAR

                $resultTimeFrame .= "

          <div class='progress'>

            <div class='progress-bar' role='progressbar'

            aria-valuemin='0' style='width:" . $percentageSLA . "%'>

            </div>

          </div>

          ";

            }

            $resultTimeFrame .= "</td>";

        } else {

            $resultTimeFrame .= "<td  >-</td>";

        }

        $table .= "<script>" . $resultTimeFrame . "</script>";

        //(END)CHECK TIME FRAME

        $table .= "<tr style='cursor:pointer;font-weight:" . $font_weight . ";" . $backgroundSLA . "' data-value='" . $data['id'] . "'> ";

        $requireDateTD = $data['requireDate'];
	    if($_SESSION['externalUse']==1) {
		    //(START)CLIENT NAME

		    $table .= "<td   >";

		    if ($data['companyId'] == 0) {

			    $dataOrg = fetchOrganizationDetails($orgId);

			    $table .= $dataOrg['name'];

		    } else {

			    $table .= $clientDetail['name'];

		    }

		    $table .= "</td>";

		    //(END)CLIENT NAME

	    }


	    $jobTransactionDetails = fetchJobTransByJobId($con, $jobId['id']);

	    if ($jobTransactionDetails['assignType'] === "myStaff") {

		    $orgStaffDetails = getOrganizationUserDetails($con, $jobTransactionDetails['workerId']);

		    $table .= "<td  >" . $orgStaffDetails['name'] . "<br/>[" . $orgStaffDetails['staffId'] . "]". "</td>";

	    } else {

		    $vendorDetails = fetchVendorDetails($con, $jobTransactionDetails['workerId']);

		    $table .=  "<td  >" .$vendorDetails['name']. "</td>";

	    }

        //ISSUE NAME

        $table .= "<td  >" . $data['issueName'] . "</td>";



        //(START)STATUS

        $table .= "<td   >";

        if ($data['status'] === 0) {

            $table .= "COMPLETE";

        } else if ($data['status'] === 2) {

            $table .= "PENDING";

        } else if ($data['status'] === 3) {

            $table .= "IN PROGRESS";

        }

        else if ($data['status'] === 4) {

            $table .= "RESOLVED";

        }

        $table .= "</td>";

        //(END)STATUS



        //(START)ASSIGN DATE/TIME

        if ($jobId['id'] != null) {

            $table .= "<td  >" . $jobId['createdDate'] . "</td>";

        } else {

            $table .= "<td  ><i>Not Assign</i></td>";

        }

        //(END)ASSIGN DATE/TIME



        //(START)TIMEFRAME

        $table .= $resultTimeFrame;

        //(END)TIMEFRAME



        $table .= "</tr>";

    }



    $table .= "</tbody>";

    $table .= "</table>";



    return $table;



}



function getComplaintDetailsById($complaintId)

{

    $con = connectDb();

    $complaintDetails = null;

    $complaintDetails = fetchComplainDetails($con, $complaintId);

    return $complaintDetails;

}



function isComplaintAsJob($complaintId)

{

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");



    $isJob = false;

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";



    $con = connectDb();

    $jobDetail = fetchJobDetailsByComplaintId($con, $complaintId);

    if ($jobDetail != null) {

        $isJob = true;

    }



    return $isJob;



}



if (isset($_GET['productTableEditable'])) {



    echo $_GET['productTableEditable'];

}



function getInvoiceNo($jobId)

{

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/job.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";

    $con = connectDb();



    $rowJob = fetchJobDetails($con, $jobId);

    $complaintId = $rowJob['complaintId'];

    $rowComplaint = fetchComplainDetails($con, $complaintId);

    $invoiceNo = $rowComplaint['invoiceNo'];



    if ($invoiceNo == null) {

        $invoiceNo = 0;

    }



    return $invoiceNo;

}

if (isset($_GET['completeexcel'])) {

    $con = connectDb();

    $details = fetchComplaintCompleteForExcel($con);

    echo $details;

}

if (isset($_GET['uncompleteexcel'])) {

        $con = connectDb();

    $details = fetchComplaintUncompleteForExcel($con);

   echo $details;

}