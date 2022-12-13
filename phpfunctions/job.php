<?php

 date_default_timezone_set("Asia/Kuala_Lumpur");

 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

 if(!isset($_SESSION)) {
     session_name($config['sessionName']);
     session_start();
 }


define('FS_METHOD', 'direct');

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");



	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/autoNum.php");



  if(isset($_POST['reassignWorker'])){

    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

      <strong>FAILED!</strong> FAILED TO REASSIGN JOB\n

      </div>\n";

    $workerType=$_POST['workerType'];

    $workerId=$_POST['workerId'];

    $complaintId=$_POST['complaintIdValue'];

$con=connectDb();

    $data = fetchJobByComplaintId($con,$complaintId);

    $jobId = $data['id'];



    //Update workerId in `job`

    $feedbackFetch = updateWorkerIdJobTransaction($con, $jobId, $workerId);



    //Store workerId in `jobreassign`

    $timeDate = date("Y-m-d h:i:sa");

    $feedbackStore = insertJobReassign($con, $jobId, $workerId, $timeDate);



    //Check

    if ($feedbackFetch && $feedbackStore) {

      $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

      <strong>SUCCESS!</strong> JOB REASSIGNED SUCCESSFULLY\n

      </div>\n";

    }



     header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php");

  }



	if(isset($_POST['assignWorker'])){

	$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

			<strong>FAILED!</strong> FAILED ASSIGN JOB\n

			</div>\n";

		$con=connectDb();



		$workerType=$_POST['workerType'];

		$workerId=$_POST['workerId'];

		$complaintId=$_POST['complaintIdValue'];

		//debug $nortiSmsNum used for sms nortification

		$stphone=json_decode($_SESSION['stphone'], true);

		//$stphone=json_decode($stphone);

		$nortiSmsNum="";

		foreach ($stphone as $key => $value) {

            if ($value["wid"]==$workerId) {

			$nortiSmsNum=$value["phone"];

            }

		}


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");

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

		$remarks=null;

		$createdDate=date('Y-m-d H:i:s');

		$startTime=null;

		$endTime=null;

		$createdBy=$_SESSION['userid'];

		$orgId=$_SESSION['orgId'];

		$refId=null;

		$workerDetails=null;

		if($workerType==="vendors"){

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");

			$workerDetails=fetchVendorAdminDetails($con,$vendorId,$orgId);

			$refId=fetchAutoNum($con,$vendorId);

		}else{

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");

			$workerDetails=getOrganizationUserDetails($con,$workerId);

			$refId=fetchOrgAutoNum($con,$orgId);

		}



			$jobNo = $refId['jobNo'];

		$jobRefNo=$refId['jobPrefix'].sprintf('%08d',$jobNo);

		$autoNumId=$refId['id'];



		$jobNo=$jobNo+1;

		$updateSuccess=updateJobNo($con,$autoNumId,$jobNo);



		$jobId=createComplaintJob($con,$jobRefNo,$jobName,$address,$clientCompanyId,$vendorId,

		$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarks,$createdDate,

		$startTime,$endTime,$createdBy,$orgId,$complaintId);



		if($jobId>0){

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

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




					

					//$orgAddress=$orgDetails[];



					require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");

					$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong> JOB ASSIGNED SUCCESSFULLY\n

					</div>\n";


					/*

					$mailMessage="/n<div class='alert alert-warning' role='alert'>\n

					<strong>FAILED!</strong> MAIL FAILED TO SEND\n

					</div>\n";

					*/

                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
                    $orgDetails=getOrganizationDetails($con,$orgId);

            		$from=$orgDetails['supportEmail'];
					$fromName=$orgDetails['name'];
					$to=$workerDetails['email'];
					$subject='NEW TASK';
					$body='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].
                        '. Thus, ticket no: '.ticketno($complaintDetails['id']).
                        ' click on this <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].
                        '" target="_blank" >Login</a> to the task further action.'
					."<br/><b>Task</b>: ".$complaintDetails['issueName']."<br/><b>Details</b>: ".$complaintDetails['issueDetail'].
					'<br /><br />Thank You';
					//.$footer;


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



		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php");

	}





	if(isset($_POST["forceUpdateComplaintStatus"])){



		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");



		$con=connectDb();

		$complaintId=$_POST["complaintIdValue"];


		$jobDetail=fetchJobDetailsByComplaintId($con,$complaintId);

		$jobTransDetail=fetchJobTransByJobId($con,$jobDetail['id']);

		if($jobDetail!=null){

// remove comment

			updateJobStatus($con,$jobDetail['id'],0);

			updateJobTransStatus($con,$jobTransDetail['id'],0);

			updateJobTransMessageStatus($con,"O",$jobTransDetail['id']);

// remove commnet end

		}



		$complaintDetails=fetchComplainDetails($con,$complaintId);



		$updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$complaintDetails['issueName'],$complaintDetails['issueDetail'],

		$complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

		$complaintDetails['createdDate'],$complaintDetails['createdBy'],"O",0,

		$complaintDetails['companyId'],$complaintDetails['orgId']);

		

		

        if ($updateSuccess){

			if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) {

				// telecom update status

			$result="";

			$closedDoc= "".date('Y-m-d')." ".date("h:i"); 

			$docremarks=$complaintDetails['remarkslog'];

			if(isset($_POST['remarks'])&&!empty(trim($_POST['remarks']))){

				$docremarks=$docremarks."<br />[".$closedDoc."] ".$_POST['remarks'];

			}

			$cid=$complaintDetails['id'];

			$docStatus="Closed";

			$docattenddate=$complaintDetails['docattenddate'];

		

			$result=updateDocketComplaintStatus($con,$docStatus,$docattenddate,$closedDoc,$docremarks,$cid);			 

			//UPDATE telecom_service SET troubleshoot=?, docattenddate=?, docclosedate=?,resolution=?,remarks=?,note=? WHERE cid=?";

		}

	}





		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong>MARKED AS COMPLETED\n

					</div>\n";

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php");



	}

	if(isset($_POST["updatecompletestatus"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");



		$jobid=$_POST['lookjobid'];

		$status=$_POST['status'];

		$textMessage="";

		if($status==0){$textMessage="COMPLETE";}

		else if($status==5){$textMessage="VERIFICATION";}

		else if($status==6){$textMessage="CLOSED";}

		else if($status==7){$textMessage="REOPEN";}



		$complaintId=$_SESSION['cid'];

		$complaintDetails=fetchComplainDetails($con,$complaintId);



		$updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$complaintDetails['issueName'],$complaintDetails['issueDetail'],

		$complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

		$complaintDetails['createdDate'],$complaintDetails['createdBy'],"O",$status,

		$complaintDetails['companyId'],$complaintDetails['orgId']);







		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

		<strong>SUCCESS!</strong>CHANGED TO ".$textMessage."\n

		</div>\n";



		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/completed.php");



	}

  if(isset($_POST["forceUpdateComplaint"])){



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



    $jobDetail=fetchJobDetailsByComplaintId($con,$complaintId);

    $jobTransDetail=fetchJobTransByJobId($con,$jobDetail['id']);

    if($jobDetail!=null){



      updateJobName($con,$jobDetail['id'],$problem);



    }



    $complaintDetails=fetchComplainDetails($con,$complaintId);



    $updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$problem,$problemDetails,

    $complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

    $complaintDetails['createdDate'],$complaintDetails['createdBy'],$complaintDetails['messageStatus'],$complaintDetails['status'],

	$complaintDetails['companyId'],$complaintDetails['orgId']);

	// telecom service remarks update

	$docRemarks="";

	if($updateSuccess&&isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){



		if(isset($_POST['remarks']) && !empty(trim($_POST['remarks']))){

			$docRemarks=$_POST['remarks'];

	if (!empty(trim($complaintDetails['remarkslog']))) {

			$docRemarks=$complaintDetails['remarkslog']."<br />[".date('Y-m-d')." ".date("h:i")."]: ".$docRemarks;

	}

	else {	

		$docRemarks="[".date('Y-m-d')." ".date("h:i")."] ".$docRemarks; 

	}

	$result=updateDocRemarks($con,$complaintDetails['id'],$docRemarks);

}

	}





    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

          <strong>SUCCESS!</strong>SUCCESSFULLY EDITED\n

          </div>\n";

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php");

  }



	/*if(isset($_POST["addJob"])){

		$con=connectDb();

		$jobName=$_POST['jobName'];

		$address=$_POST['address'];

		$cliendCompanyId=$_POST['cliendCompanyId'];

		$vendorId=$_SESSION['vendorId'];

		$vendorUserId=$_POST['vendorUserId'];

		$picName=$_POST['picName'];

		$picContactNo=$_POST['picContactNo'];

		$dateRequire=$_POST['dateRequire'];

		$remarks=$_POST['remarks'];

		$createdDate=date('Y-m-d H:i:s');

		$createdBy=$_SESSION['userid'];



		$refId=fetchAutoNum($con,$vendorId);

		$jobNo=$refId['jobNo'];

		$jobRefNo=$refId['jobPrefix'].sprintf('%08d',$jobNo);

		$autoNumId=$refId['id'];

		$orgId=$_SESSION['orgId'];

		$saveSuccess=addJob($con,$jobRefNo,$jobName,$address,$cliendCompanyId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$remarks,$createdDate,$createdBy,$orgId);

		$jobNo++;

		if($saveSuccess){

			$updateSuccess=updateJobNo($con,$autoNumId,$jobNo);

		}



	}

	*/

	/*

	if(isset($_POST["updateJobUser"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		$con=connectDb();

		$jobId=$_POST['updateJobUser'];

		$remarks=$_POST['remarks'];

		$createdDate=date('Y-m-d H:i:s');

		$createdBy=$_SESSION['userid'];

		$base64Image=$_POST['imageBase64'];

		$status=$_POST['status'];

		$jobDetail=fetchJobDetails($con,$jobId);



		$transactionId=createJobTransaction($con,$jobId,$createdDate,$createdBy,$remarks,$status);

		$updateSuccess=updateJobStatus($con,$jobId,$status);



		$jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['vendorId']."/".$jobDetail['refNo'];

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}



	//	echo "<br/>remarks : ".$remarks;

	//	echo "<br/>createdDate : ".$createdDate;

	//	echo "<br/>createdBy : ".$createdBy;

	//	echo "<br/>base64 : ".$base64Image;

	//	echo  "<br/><img src='".$base64Image."' />";

		list(, $base64Image)      = explode(',', $base64Image);

		$base64Image = base64_decode($base64Image);



		file_put_contents($jobDirectory.'/'.$transactionId.'.png', $base64Image);

		$signaturePath=$_SESSION['vendorId']."/".$jobDetail['refNo'];

	//	echo "<br/>".$signaturePath;

		$updateSucess=updateJobTransactionSignaturePath($con,$transactionId,$signaturePath);

	}

	*/



	if(isset($_GET["updateJobTransMessageStatus"])){

		$con=connectDb();

		$messageStatus="O";



		$jobTransId=$_GET["updateJobTransMessageStatus"];



		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		$saveSuccess=updateJobTransMessageStatus($con,$messageStatus,$jobTransId);

		echo $jobTransId."<br/>".$saveSuccess;



	}


	if(isset($_GET["viewTaskDetailsModeContent"])){



		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");

  		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");



		$jobId=$_GET["viewTaskDetailsModeContent"];

		$con=connectDb();

		$jobDetail= fetchJobDetails($con,$jobId);

   		$clientCompanyDetails=fetchClientCompanyDetails($con,$jobDetail['clientCompanyId']);



    if (!(array)$clientCompanyDetails) {

      $rowOrg=getOrganizationDetails($con,$_SESSION['orgId']);

      $clientOrgName = $rowOrg['name'];

      $clientOrgAddress = $rowOrg['address1'].", ".$rowOrg['address2'].", ".$rowOrg['postalCode'].", ".$rowOrg['city'].", ".$rowOrg['state'];

      $clientOrgInstalAddress = $clientOrgAddress;

    }else {

      $clientOrgName = $clientCompanyDetails['name'];

      $clientOrgAddress = $clientCompanyDetails['address1'].", ".$clientCompanyDetails['address2'].", ".$clientCompanyDetails['postalCode'].", ".$clientCompanyDetails['city'].", ".$clientCompanyDetails['state'];

      $clientOrgInstalAddress = $clientCompanyDetails['instalAddress1']." ".$clientCompanyDetails['instalAddress2'];

    }

	$complaintDetails=fetchComplainDetails($con,$jobDetail['complaintId']);

	$_SESSION['cid']=$jobDetail['complaintId'];

	?>

	

	<?php



	{

		//debug formview

		?>

		

		<?php if($_SESSION['orgType']==6){ ?>

		<div>

			<center><h4>Ticket No: <?php echo sprintf("%07d", $jobDetail['complaintId']); ?></h4></center>

			</div>

		<?php } ?>

		<?php if($_SESSION['orgType']!=6){ ?>

		<div class="form-group row">

		<label for="complaintDate" class="col-sm-2 col-form-label"><b>ASSIGNED DATE</b></label>

		<div class="col-sm-10">

		  <input type="text" readonly class="form-control-plaintext" id="complaintDate" value="<?php echo $complaintDetails['createdDate'];  ?>">

		</div>

	  </div>



    <div class="form-group row">

		<label for="complaintDate" class="col-sm-2 col-form-label"><b>BOOKING DATE/TIME</b></label>

		<div class="col-sm-10">

		  <input type="text" readonly class="form-control-plaintext" id="complaintDate" value="<?php echo date("Y-m-d H:i:sa", strtotime($complaintDetails['requireDate']));  ?>">

		</div>

	  </div>

	<?php } else {?>

		<input type="text" readonly hidden class="form-control-plaintext" id="complaintDate" value="<?php echo $complaintDetails['createdDate'];  ?>">

		<input type="text" readonly hidden class="form-control-plaintext" id="complaintDate" value="<?php echo date("Y-m-d H:i:sa", strtotime($complaintDetails['requireDate']));  ?>">

	<?php }?>

	  <div class="form-group row">

		<label for="clientName" class="col-sm-2 col-form-label"><b>CLIENT NAME</b></label>

		<div class="col-sm-10">

		  <input type="text" readonly class="form-control-plaintext" id="clientName" name="clientName" value="<?php echo  $clientOrgName; ?>">

		</div>

	  </div>

	  <?php if ($_SESSION['orgType']==1) { ?>

	  <div class="form-group row">

		<label for="clientName" class="col-sm-2 col-form-label"><b>PRODUCT</b></label>

		<div class="col-sm-10">

		  <?php echo productTableList($jobId); ?>

		</div>

	  </div>

    <?php } ?>

	  <?php 

 	//if(empty($clientCompanyDetails['instalAddress1'])

			   if($clientCompanyDetails['instalAddress1']==NULL){ ?>

	  <div class="form-group row">

		<label for="clientAddress" class="col-sm-2 col-form-label"><b>ADDRESS</b></label>

		<div class="col-sm-10">

		  <!--<input type="text" readonly class="form-control-plaintext" id="clientAddress" name="clientAddress" value="<?php echo $clientCompanyDetails['address1'].", ".$clientCompanyDetails['address2'].", ".$clientCompanyDetails['postalCode'].", ".$clientCompanyDetails['city'].", ".$clientCompanyDetails['state']; ?>"> -->

		  <textarea readonly class="form-control-plaintext" id="clientAddress" name="clientAddress" rows="3" cols="50"><?php echo $clientOrgAddress;

                ?></textarea>

		</div>

	  </div>

	  <?php }else{ ?>

	  <div class="form-group row">

		<label for="clientInstalAddress" class="col-sm-2 col-form-label"><b>SERVICE ADDRESS</b></label>

		<div class="col-sm-10">

		  <!--

		  <input type="text" readonly class="form-control-plaintext" id="clientInstalAddress" name="clientInstalAddress" value="<?php

          if ($clientCompanyDetails['instalAddress1']!=null) {

              echo $clientCompanyDetails['instalAddress1'].", ".$clientCompanyDetails['instalAddress2'].", ".$clientCompanyDetails['instalPCode'].", ".$clientCompanyDetails['instalCity'].", ".$clientCompanyDetails['instalState'];

          } else {

              echo "";

          }

          ?>"> -->

   		  <?php if ($clientCompanyDetails['instalAddress1']!=null) { ?>

		  <textarea readonly class="form-control-plaintext" id="clientAddress" name="clientAddress" rows="3" cols="50"><?php echo $clientCompanyDetails['instalAddress1'].", ".$clientCompanyDetails['instalAddress2'].", ".$clientCompanyDetails['instalPCode'].", ".$clientCompanyDetails['instalCity'].", ".$clientCompanyDetails['instalState'];

          ?></textarea><?php } else { ?>

          <textarea readonly class="form-control-plaintext" id="clientAddress" name="clientAddress" rows="3" cols="50"><?php echo ""

          ?></textarea><?php } ?>



		</div>

	  </div>

    <?php } ?>

	  <div class="form-group row">

		<label for="problem" class="col-sm-2 col-form-label"><b><?php if($_SESSION['orgType']==7 || $_SESSION['staffRole']){ ?>TASK <?php } else{ ?>PROBLEM <?php } ?></b></label>

		<div class="col-sm-10">

		  <input type="text" readonly class="form-control-plaintext" id="problem" name="jobName" value="<?php echo $complaintDetails['issueName']; ?>">

		</div>

	  </div>



	  <div class="form-group row">

		<label for="problemDetails" class="col-sm-2 col-form-label"><b><?php if($_SESSION['orgType']==7 || $_SESSION['staffRole']){ ?>TASK <?php } else{ ?>PROBLEM <?php } ?> DETAILS</b></label>

		<div class="col-sm-10">

		  <input type="text" readonly class="form-control-plaintext" name="problemDetails" id="problemDetails" value="<?php echo $complaintDetails['issueDetail'] ?>">

		</div>

	  </div>

	  <?php if($_SESSION['orgType']==6 ){

	if(!empty(trim($complaintDetails['note']))){ ?>

	<div class="form-group row">

	<label class="col-sm-2 col-form-label"><b>Note</b></label>

	<div class="col-sm-10">

	<?php echo $complaintDetails['note'] ?>

	</div>

	</div>

	<?php }

			 if(!empty(trim($complaintDetails['remarkslog']))){ ?>

	<div class="form-group row">

    <label class="col-sm-2 col-form-label"><b>Remarks</b></label>

    <div class="col-sm-10">

	<?php echo $complaintDetails['remarkslog'] ?>

    </div>

    </div>


	<?php }} else { ?>
<?php if($_SESSION['orgType']!=7  || $_SESSION['staffRole']!=true){ /* ?>
		<div class="form-group row">
			<label for="invoice" class="col-sm-2 col-form-label"><b>INVOICE</b></label>

    <div class="col-sm-10">

      <?php

      if ($complaintDetails['invoiceNo']==null) {

          $invoice = "Not Created";

      } else {

          $invoice = $complaintDetails['invoiceNo'];

      }

      ?>

      <input type="text" readonly class="form-control-plaintext" name="invoice" id="invoice" value="<?php echo $invoice ?>">

    </div>

    </div>



	<?php */ } }?>
		<?php if($complaintDetails['fileAttach']!=NULL){ ?>
		<div class="form-group row">

			<label class="col-sm-2 col-form-label"><b>Document</b></label>

			<div class="col-sm-10">
				<a class="btn" href="https://
				<?php
					echo $_SERVER['HTTP_HOST'] . $config['appRoot'].'/resources/'.$_SESSION['orgId'].'/complaint/'.$complaintDetails['fileAttach'] ?>
					"> download <?php echo $complaintDetails['fileAttach'] ?></a>

			</div>

		</div>
	<?php } ?>

	  <input type="text" hidden class="form-control-plaintext" name="clientInstalAddress" id="clientInstalAddress" value="<?php echo $clientOrgInstalAddress ?>">

	  <input type="text" hidden class="form-control-plaintext" name="zone" id="zone" value="<?php echo $jobDetail['zone'] ?>">



	  <input type="text" hidden class="form-control-plaintext" name="service" id="service" value="<?php echo $jobDetail['service'] ?>">



	  <input type="text" hidden class="form-control-plaintext" name="csName" id="csName" value="<?php echo $jobDetail['cName'] ?>">

	<?php }



	}


	if(isset($_POST["updateAssignedTaskOrgStaff"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");



		$con=connectDb();



		$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

		<strong>FAILED!</strong>JOB UPDATE FAILED\n

		</div>\n";

		$remarks=$_POST['remarks'];

		$updateSuccess=false;

		$base64Image=$_POST['imageBase64'];

		$jobId=$_POST['jobId'];



		//(START) GETTING LATITUDE & LONGITUDE VALUES

		$latitude = $_POST['latitude'];

        $longitude = $_POST['longitude'];

        $latlon = $latitude.",".$longitude;

        //(END) GETTING LATITUDE & LONGITUDE VALUES



		// start time & end time

    	/*

		$startDateTime=explode("T",$_POST['startTime']);

		$startTime=$startDateTime[0]." ".$startDateTime[1].":00";

		$startTime=strtotime($startTime);

		$startTime=date('Y-m-d H:i:s',$startTime); */

		$startTime = $_POST['startTime'];



		$jobTransId=$_POST['jobTransId'];

		$status=$_POST['status'];

		$orgId=$_SESSION['orgId'];

		$createdBy=$_SESSION['userid'];

    	if ($status == 0) {

        /*

          $endDateTime=explode("T",$_POST['endTime']);

          $endTime=$endDateTime[0]." ".$endDateTime[1].":00";

          $endTime=strtotime($endTime);

          $endTime=date('Y-m-d H:i:s',$endTime); */

    	  $endTime = $_POST['endTime'];

   	 	}

    	else {

      	$endTime = NULL;

		}

		$troubleshoot="";

		$notes="";

		$modem="";

		$buc="";

		$lnb="";

		$feaderhon="";

		$antenna="";

		$connector="";

		$poweradaptor="";

		$docResolution="";

		$docRemarks="";

		$docStartTime="";

		$docEndTime="";

		$cid="";

		// Telecom Technical issue 

		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){

			$c=0;

			if(isset($_POST['modem'])){

				$modem=$_POST['modem'];

				$c=1;

			}

			if(isset($_POST['buc'])){

				if($c>0){

					$buc=", ".$_POST['buc']; 

				}else{

					$buc=$_POST['buc']; 

				}

				$c=$c+1;

				

			}

            if (isset($_POST['lnb'])) {

				if($c>0){$lnb=", ".$_POST['lnb']; 

				}else{

                    $lnb=$_POST['lnb'];

                }

				$c=$c+1;

			}

			

            if (isset($_POST['feaderhon'])) {

                if ($c>0) {

                    $feaderhon=", ".$_POST['feaderhon'];

                } else {

                    $feaderhon=$_POST['feaderhon'];

                }

                $c=$c+1;

            }

			

			if (isset($_POST['antenna'])) {

				if($c>0){$antenna=", ".$_POST['antenna']; 

				} else {

                    $antenna=$_POST['antenna'];

                }

				$c=$c+1;

			}

			if (isset($_POST['connector'])) {

				if($c>0){$connector=", ".$_POST['connector']; 

				} else {

                    $connector=$_POST['connector'];

                }

				$c=$c+1;

			}

			if (isset($_POST['poweradapter'])) {

				if($c>0){$poweradaptor=", ".$_POST['poweradapter']; 

				} else {

                    $poweradaptor=$_POST['poweradapter'];

                }

				$c=$c+1;

			}



			$notes=$_POST['notes'];

            if ($status==4) {

                $endTime = $_POST['endTime'];

			}

			

			$docEndTime=str_replace("T"," ",$endTime);

			$troubleshoot="".$modem.$buc.$lnb.$feaderhon.$antenna.$connector.$poweradaptor;

			$docResolution=(isset($_POST['action'])?$_POST['action']:"");

			$docRemarks=(isset($_POST['remarks'])?$_POST['remarks']:"");

			$cid=$_SESSION['cid'];

		}



		$jobDetail=fetchJobDetails($con,$jobId);

		$jobRefNo=$jobDetail['refNo'];



        $jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/orgJob/".$jobRefNo;



	//	$jobDirectory="./../resources/".$orgId."/orgJob/".$jobRefNo;

        //file_put_contents("./log_file.log","\nRun 1 Before path:".$jobDirectory,FILE_APPEND);

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}

		$signaturePath="";
		if(!empty(trim($base64Image))) {
			list(, $base64Image) = explode(',', $base64Image);

			$base64Image = base64_decode($base64Image);


			file_put_contents($jobDirectory . '/' . $jobTransId . '.png', $base64Image);

			//copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');

			//file_put_contents("./log_file.log","\nRun 2 Before path",FILE_APPEND);

			copy("./../index.php", $jobDirectory . '/index.php');

			$signaturePath = $orgId . "/orgJob/" . $jobRefNo . "/" . $jobTransId;
		}
        //file_put_contents("./log_file.log","\nRun  Before path:".$signaturePath,FILE_APPEND);

		$updateSuccess=updateJobStatus($con,$jobId,$status);

		$updateSuccess=updateJobSignaturePath($con,$jobId,$signaturePath);

		$updateSucesss=updateJobWorkingPerod($con,$jobId,$startTime,$endTime);

		$updateSuccess=updateJobLatLon($con,$jobId,$latlon);



		$updateSuccess=updateJobTransStatus($con,$jobTransId,$status);



		$updateSuccess=updateJobTransRemarks($con,$jobTransId,$remarks);

		$updateSucess=updateJobTransactionSignaturePath($con,$jobTransId,$signaturePath);

		$updateSucesss=updateJobTransactionWorkingPerod($con,$jobId,$startTime,$endTime);



		$complaintDetails=fetchComplainDetails($con,$jobDetail['complaintId']);

		$updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$complaintDetails['issueName'],$complaintDetails['issueDetail'],

		$complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

		$complaintDetails['createdDate'],$complaintDetails['createdBy'],$complaintDetails['messageStatus'],$status,

		$complaintDetails['companyId'],$complaintDetails['orgId']);

		$result="";

		// telecom 
        if ($updateSuccess&&isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) {

			if(isset($_POST['updateAssignedTask'])){

                if (empty(trim($complaintDetails['docattenddate']))) {

                    //		$res=stripos($complaintDetails['remarkslog'],"FE");

                    //        if ($res>0) {

                    $docStartTime=str_replace("T", " ", $startTime);

                //        }

                } else {

                    $docStartTime=$complaintDetails['docattenddate'];

                }

            }

			

			if (!empty(trim($docRemarks))) {

            if (!empty(trim($complaintDetails['remarkslog']))) {

                $docRemarks=$complaintDetails['remarkslog']."<br />[".date('Y-m-d')." ".date("h:i")."]: FE ".$docRemarks;

			}

			else{

                if (empty(trim($complaintDetails['remarkslog']))) {

                    $docRemarks="[".date('Y-m-d')." ".date("h:i")."]: FE ".$docRemarks;

                }

			}

		} else{

			$notes=$complaintDetails['remarkslog'];

		}

            if (!empty(trim($notes))) {

                if (!empty($complaintDetails['note'])) {

                    $notes=$complaintDetails['note']."<br />[".date('Y-m-d')." ".date("h:i")."]: FE ".$notes;

                } else {

                    if (empty(trim($complaintDetails['note']))) {

                        $notes="[".date('Y-m-d')." ".date("h:i")."]: FE ".$notes;

                    }

                }

            } else{

				$notes=$complaintDetails['note'];

			}

			

            $result=updateDocketComplaint($con, $troubleshoot, $docStartTime, $docResolution, $docRemarks, $notes, $cid);

	      //  file_put_contents("./0update.log","\n\n".print_r($result,1),FILE_APPEND);
        

			//troubleshoot, docattenddate, docclosedate,resolution,remarks,note,cid";

		}elseif($updateSuccess && isset($_SESSION['orgType']) && $_SESSION['supportCam']==1){
            if(isset($_POST['updateAssignedTask'])){
                $img = $_POST['image'];
	            $taken=$_POST['taken'];

                $folderPath = "upload/";


                foreach ($img as $data) {
                    // code...

                    $image_parts = explode(";base64,", $data);

                    $image_type_aux = explode("image/", $image_parts[0]);

                    $image_type = $image_type_aux[1];



                    $image_base64 = base64_decode($image_parts[1]);

                    $fileName = date('Ymd')."".uniqid() .'.png';



                    $file = $jobDirectory .'/'. $fileName;

                    file_put_contents($file, $image_base64);

                    updateComplaintImage($con,$fileName,$complaintDetails['id'],$taken);




                }
            }
        }

		if($_SESSION['complaintExtra']&&isset($_SESSION['complaintExtra'])){


			$modelNo=$_POST['modelNo'];
			$eqDetails=$_POST['equipmentDetails'];
			$smrNo=$_POST['smrNo'];
			$jobType=$_POST['jobType'];
			$rootCause=$_POST['rootCause'];
			$partsReplace=$_POST['partsReplace'];
			$comments=$_POST['comments'];
			$star=$_POST['star'];
			$compId = $_SESSION['cid'];
			$saveStateSuccess = updateComplaintExtra($con, $modelNo, $eqDetails, $smrNo, $jobType, $rootCause, $partsReplace, $comments, $star, $compId);

			if ($saveStateSuccess) {

				$_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong> JOB / REQUEST CREATED SUCCESSFULLY\n

					<br /><a href='".'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php' style='text-decoration: none;color: blue;'>View For Assign Task List</a>

					</div>";

			}
		}

    	//(START)RECHARTS ADDITIONAL VARIABLES

      //(START)METER READING

      if (isset($_POST['meter1'])) {

        $meter1 = $_POST['meter1'];

      }else {

        $meter1 = NULL;

      }

      if (isset($_POST['meter2'])) {

        $meter2 = $_POST['meter2'];

      }else {

        $meter2 = NULL;

      }

      if (isset($_POST['meter3'])) {

        $meter3 = $_POST['meter3'];

      }else {

        $meter3 = NULL;

      }

      if (isset($_POST['meter4'])) {

        $meter4 = $_POST['meter4'];

      }else {

        $meter4 = NULL;

      }

      if (isset($_POST['meterTotal'])) {

        $meterTotal = $_POST['meterTotal'];

      }else {

        $meterTotal = NULL;

      }

      $feedbackMeterReading = updateMeterReading($con, $jobId, $meter1, $meter2, $meter3, $meter4,$meterTotal);

      if (!$feedbackMeterReading) {

        $updateSuccess = false;

      }

      //(END)METER READING



      //(START)ACTION TAKEN

      if (isset($_POST['action'])) {

        $action = $_POST['action'];

      }else{

        $action = NULL;

      }



      $feedbackAction = updateActionTaken($con, $jobId, $action);

      if (!$feedbackAction) {

        $updateSuccess = false;

      }

      //(END)ACTION TAKEN



      //(START)REMARKS

      if (isset($_POST['remarks'])) {

        $remarks = $_POST['remarks'];

        $feedbackRemarks = updateJobRemarks($con, $jobId, $remarks);

        if (!$feedbackRemarks) {

          $updateSuccess = false;

        }

      }

      //(END)REMARKS



      //(START)CHARGES

      if (isset($_POST['zone'])) {

        echo $zone = $_POST['zone'];

        $feedbackZone = updateJobZoneCharge($con, $jobId, $zone);

        if (!$feedbackZone) {

          $updateSuccess = false;

        }

      }

      if (isset($_POST['service'])) {

        echo $service = $_POST['service'];

        $feedbackService = updateJobServiceCharge($con, $jobId, $service);

        if (!$feedbackService) {

          $updateSuccess = false;

        }

      }

      //(END)CHARGES



      //(START)CUSTOMER'S NAME

      if (isset($_POST['csName'])) {

        echo $csName = $_POST['csName'];

        $feedbackCsName = updateJobCsName($con, $jobId, $csName);

        if (!$feedbackCsName) {

          $updateSuccess = false;

        }

      }

      //(END)CUSTOMER'S NAME



      //START SPAREPARTS

      if ($_POST['spDes0'] != NULL ) {

        $spDes = "spDes";

        $spQty = "spQty";

        $workerId=$_SESSION['userid'];

        $jobId = $_POST['jobId'];

        $i = 0;

		$itest =50;

		while ($i < $itest) {

			 echo $spDes.$i."<br>";

            $description = $_POST[$spDes.$i];

            $qty = $_POST[$spQty.$i];

            $sparepart = insertSpareParts($con,$jobId,$workerId,$description,$qty);

            if (!$sparepart) {

              echo "an error occur<br>";

              break;

            }

            $i++;

          }

        }

			//END SPAREPARTS

			//(END)RECHARTS ADDITIONAL VARIABLE

			if($updateSuccess){

				$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

						<strong>SUCCESS!</strong>SUCCESSFULLY ASSIGNED JOB\n

						</div>\n";

			}

			//if (isset($_POST['emailsSubmit']))) {

			if (isset($_POST['emailsSubmit']) && !empty($_POST['emailsSubmit'])) {

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");

			$emailsSubmit = $_POST['emailsSubmit'];

			$emailsCcSubmit = $_POST['emailsCcSubmit'];

			$invoiceNo = $_POST['invoiceNo'];

		

			// debug

			$detailslist="\nEmail Submit:".$emailsSubmit." Email CC:".$emailsCcSubmit." worker-Invoice No:".$invoiceNo;

     		$_SESSION['feedback'] .= mailInvoice($emailsSubmit,$emailsCcSubmit,$invoiceNo);

   			}

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/assignedTask/viewTask.php");

	}


	if(isset($_POST["updateImmediateJobOrgStaff"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");



		$con=connectDb();

		$jobListId=$_POST['orgJobListId'];

		$orgId=$_SESSION['orgId'];

		$createdBy=$_SESSION['userid'];

		$clientId=$_POST['clientCompanyId'];

		$jobName=$_POST['jobName'];



		$startDateTime=explode("T",$_POST['startTime']);

		$endDateTime=explode("T",$_POST['endTime']);



		$startTime=$startDateTime[0]." ".$startDateTime[1].":00";

		$endTime=$endDateTime[0]." ".$endDateTime[1].":00";



		$startTime=strtotime($startTime);

		$endTime=strtotime($endTime);

		$startTime=date('Y-m-d H:i:s',$startTime);

		$endTime=date('Y-m-d H:i:s',$endTime);



		if($jobListId>0){

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/orgJobList.php");

			$jobListDetail=fetchOrgJobListDetail($con,$jobListId);

			$jobName=$jobListDetail['jobName'];

		}



		$remarksO=$_POST['remarks'];

		$status=$_POST['status'];

		$createdDate=date('Y-m-d H:i:s');

		$base64Image=$_POST['imageBase64'];



		$refId=fetchOrgAutoNum($con,$orgId);

		$jobNo=$refId['jobNo'];

		$jobRefNo=$refId['jobPrefix'].sprintf('%08d',$jobNo);

		$autoNumId=$refId['id'];



		$address="";

		$vendorUserId=0;

		$vendorId=0;

		$picName="";

		$picContactNo="";

		$dateRequire="";









		$jobId=addImmediateJob2($con,$jobRefNo,$jobName,$address,$clientId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarksO,$createdDate,$startTime,$endTime,$createdBy,$orgId);

		$jobNo++;

		$updateSuccess=updateJobNo($con,$autoNumId,$jobNo);

		$transactionId=createJobTransaction($con,$jobId,$createdDate,$createdBy,$startTime,$endTime,$remarksO,$status,$orgId);

		$updateSuccess=updateJobStatus($con,$jobId,$status);



		// job item list used for this transaction

		if($config['joblistitem']==true){

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobListItem.php");

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransactionItemList.php");



			$jobListItems=fetchJobListItem($con,null,$orgId);

			//$indexes=explode(",",$_POST[''])

			$size=sizeof($jobListItems);

			$transationItem=array();



			$i=1;

			foreach($jobListItems as $item){

				if($_POST["item".$i."Qty"]>0){

					$item['name']=$item['itemName'];

					$item['qty']=$_POST["item".$i."Qty"];

					$transationItem[]=$item;


				}

				$i++;

			}



			if($_POST["itemQtyOthers"]>0){

				$item['name']=$_POST['itemNameOthers'];

				$item['qty']=$_POST["itemQtyOthers"];

				$transationItem[]=$item;



			}



			foreach($transationItem as $item){

				$updateSuccess=createJobTransactionItem($con,$transactionId,$item['name'],$item['qty'],$orgId);

			}

		}



		$jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/orgJob/".$jobRefNo;

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}



		list(, $base64Image)      = explode(',', $base64Image);

		$base64Image = base64_decode($base64Image);



		file_put_contents($jobDirectory.'/'.$transactionId.'.png', $base64Image);

		copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');



		$signaturePath=$orgId."/orgJob/".$jobRefNo."/".$transactionId;



		$updateSucess=updateJobTransactionSignaturePath($con,$transactionId,$signaturePath);

		$updateSuccess=updateJobSignaturePath($con,$jobId,$signaturePath);

		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

		<strong>SUCCESS!</strong> SUCCESSFULLY UPDATED\n

			</div>\n";

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/assignedTask/viewTask.php");





	}


	if(isset($_POST["updateAssignedTaskVendor"])){



		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");



		$con=connectDb();



		$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

		<strong>FAILED!</strong>JOB UPDATE FAILED\n

		</div>\n";



		$remarks=$_POST['remarks'];

		$updateSuccess=false;

		$base64Image=$_POST['imageBase64'];

		$jobId=$_POST['jobId'];



		// start time & end time

		$startDateTime=explode("T",$_POST['startTime']);

		$endDateTime=explode("T",$_POST['endTime']);



		$startTime=$startDateTime[0]." ".$startDateTime[1].":00";

		$endTime=$endDateTime[0]." ".$endDateTime[1].":00";



		$startTime=strtotime($startTime);

		$endTime=strtotime($endTime);

		$startTime=date('Y-m-d H:i:s',$startTime);

		$endTime=date('Y-m-d H:i:s',$endTime);



		$jobTransId=$_POST['jobTransId'];

		$status=$_POST['status'];

		$orgId=$_SESSION['orgId'];

		$createdBy=$_SESSION['userid'];



		$jobDetail=fetchJobDetails($con,$jobId);

		$jobRefNo=$jobDetail['refNo'];





		$jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/vendor/".$jobRefNo;

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}



		list(, $base64Image)      = explode(',', $base64Image);

		$base64Image = base64_decode($base64Image);



		file_put_contents($jobDirectory.'/'.$jobTransId.'.png', $base64Image);

		copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');

		$signaturePath=$orgId."/vendor/".$jobRefNo."/".$jobTransId;





		$updateSuccess=updateJobStatus($con,$jobId,$status);

		$updateSuccess=updateJobSignaturePath($con,$jobId,$signaturePath);

		$updateSucesss=updateJobWorkingPerod($con,$jobId,$startTime,$endTime);



		$updateSuccess=updateJobTransStatus($con,$jobTransId,$status);



		$updateSuccess=updateJobTransRemarks($con,$jobTransId,$remarks);

		$updateSucess=updateJobTransactionSignaturePath($con,$jobTransId,$signaturePath);

		$updateSucesss=updateJobTransactionWorkingPerod($con,$jobId,$startTime,$endTime);





		$complaintDetails=fetchComplainDetails($con,$jobDetail['complaintId']);

		$updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$complaintDetails['issueName'],$complaintDetails['issueDetail'],

		$complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

		$complaintDetails['createdDate'],$complaintDetails['createdBy'],$complaintDetails['messageStatus'],$status,

		$complaintDetails['companyId'],$complaintDetails['orgId']);

		if($updateSuccess){

			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong>SUCCESSFULLY ASSIGNED JOB\n

					</div>\n";

		}



		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/vendor/assignedTask/viewTask.php");





	}


	if(isset($_POST["updateImmediateJobVendor"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobList.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendorClient.php");







		$con=connectDb();

		$vendorClientId=$_POST['vendorClientId'];



		$jobListId=$_POST['vendorJobListId'];

		$remarks=$_POST['remarks'];

		$createdDate=date('Y-m-d H:i:s');

		$createdBy=$_SESSION['userid'];

		$base64Image=$_POST['imageBase64'];

		$status=$_POST['status'];



		$startDateTime=explode("T",$_POST['startTime']);

		$endDateTime=explode("T",$_POST['endTime']);



		$startTime=$startDateTime[0]." ".$startDateTime[1].":00";

		$endTime=$endDateTime[0]." ".$endDateTime[1].":00";



		$startTime=strtotime($startTime);

		$endTime=strtotime($endTime);

		$startTime=date('Y-m-d H:i:s',$startTime);

		$endTime=date('Y-m-d H:i:s',$endTime);



		$jobListDetail=fetchVendorJobListDetail($con,$jobListId);



		$vendorId=$_SESSION['vendorId'];

		$vendorUserId=$_SESSION['userid'];

		$address="";

		$picName="";

		$picContactNo="";

		$dateRequire="1992-11-11";

		$remarksO=$_POST['remarks'];

		$orgId=$_SESSION['orgId'];

		$refId=fetchAutoNum($con,$vendorId);

		$jobNo=$refId['jobNo'];

		$jobRefNo=$refId['jobPrefix'].sprintf('%08d',$jobNo);

		$autoNumId=$refId['id'];

		//echo $address;

		$vendorClientDetail=vendorClientDetails($con,$vendorClientId);

		$jobName=$jobListDetail['jobName'];

		//$jobId=addImmediateJob($con,$jobRefNo,$jobListDetail['jobName'],$address,$vendorClientDetail['clientCompanyId'],$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarksO,$createdDate,$createdBy,$orgId);

		$jobId=addImmediateJob2($con,$jobRefNo,$jobName,$address,$vendorClientId,$vendorId,$vendorUserId,$picName,$picContactNo,$dateRequire,$status,$remarksO,$createdDate,$startTime,$endTime,$createdBy,$orgId);



		$jobNo++;

		$updateSuccess=updateJobNo($con,$autoNumId,$jobNo);



		$transactionId=createJobTransaction($con,$jobId,$createdDate,$startTime,$endTime,$createdBy,$remarks,$status,$orgId);



		$updateSuccess=updateJobStatus($con,$jobId,$status);



		$jobDirectory=$_SERVER['DOCUMENT_ROOT']."/jobsheet/resources/".$orgId."/vendor/".$jobRefNo;

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}



		list(, $base64Image)      = explode(',', $base64Image);

		$base64Image = base64_decode($base64Image);



		file_put_contents($jobDirectory.'/'.$transactionId.'.png', $base64Image);

		copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');



		$signaturePath=$orgId."/vendor/".$jobRefNo."/".$transactionId;



		$updateSucess=updateJobTransactionSignaturePath($con,$transactionId,$signaturePath);

		$updateSuccess=updateJobSignaturePath($con,$jobId,$signaturePath);

		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

		<strong>SUCCESS!</strong> SUCCESSFULLY UPDATED\n

			</div>\n";

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/vendor/assignedTask/viewTask.php");



	}


	function activeJobTable(){

		$con=connectDb();

   GLOBAL $config;

		echo "<div class='table-responsive'>";

		echo "<table id='activeJob' class='table'>";

		echo "<thead class='thead-dark'>";

		echo "<tr>";

		echo 	"<th>";

		echo 		"#";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REF NO";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"JOB NAME";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"CLIENT";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REQUIRE DATE";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REMARKS";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"STATUS";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"OPTION";

		echo 	"</th>";

		echo "</tr>";

		echo "</thead >";

		$status=0;



		$dataList=fetchIncompleteJobList($con,$_SESSION['userid'],$status);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

		$i=1;

		foreach($dataList as $data){



			//echo "<br/>".$data['clientCompanyId'];

			$clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);

			//echo "<br/>".$clientCompanyDetails['name'];



			echo "<tr ";

			if($i%2==0)

				echo "style='background-color:#FFF5EB;'";

			else{

				echo "style='background-color:#F9F9F9;'";

			}

			echo ">";

			echo	"<td><b>";

			echo		$i++;

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['refNo'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['jobName'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$clientCompanyDetails['name'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['requireDate'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['remarks'];

			echo	"</b></td>";

			echo	"<td><b>";

			if($data['status']===0){

				echo "completed";

			}

			else if($data['status']===1){

				echo "new";

			}else if($data['status']===2){

				echo "in progress";

			}

			else if($data['status']===3){

				echo "pending";

			}

			else if($data['status']===4){

				echo "resolved";

			}

			echo	"</b></td>";

			echo	"<td><b>";

			echo	"<button name='viewJobDetail' value='".$data['id']."' class='btn btn-info' type='submit' >view</button>";

			echo	"</b></td>";

			echo "</tr>";





		}

		echo "</table>";

		echo "</div>";



	}

     if(isset($_POST['viewMyTaskTable'])){
         $jobRefNo_=null;
         $clientCompanyId_=null;
         $status_="UNRESOLVED";
         $createdBy_=null;
         $dateFrom_=null;
         $dateTo_=null;
         $assignType_="myStaff";
         $workerId_=$_SESSION['userid'];
         $orgId_=null;
         $complaintId_=null;
         $jobId_=null;
         $messageStatus_=null;



         $con=connectDb();
         $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
         require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");
         require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
         $table="";
         $table="<div class='table-responsive'  id=\"mytask\">\n";

         $table.="<table class='table table-hover' id='dataTable' width='100%' cellspacing='0' >";

         $table.=  "<thead >";

         $table.=  "<tr>";

         $table.=  	"<th>";

         $table.=  		"TICKET NO";

         $table.=  	"</th>";

         $table.=  	"<th>";

         $table.=  		"ASSIGNED DATE";

         $table.=  	"</th>";

         $table.=  	"<th>";

         $table.=  		"REF NO";

         $table.=  	"</th>";

         $table.=  	"<th>";

         $table.=  		"CLIENT";

         $table.=  	"</th>";

         $table.=  	"<th>";

             $table.=  		"PROBLEM";

         $table.=  	"</th>";

         $table.=  	"<th>";

         $table.=  		"STATUS";

         $table.=  	"</th>";

         $table.=  "</tr>";

         $table.=  "</thead >";

         $table.="<tbody>";

         if($status_>=0 && $status_!==null){

             $status=$status_;

         }

         $jobRefNo=$jobRefNo_;

         $clientCompanyId=$clientCompanyId_;

         $status=$status_;

         $createdBy=$createdBy_;

         $dateFrom=$dateFrom_;

         $dateTo=$dateTo_;

         $assignType=$assignType_;

         $workerId=$workerId_;

         $orgId=$orgId_;

         $complaintId=$complaintId_;

         $jobId=$jobId_;

         $messageStatus=$messageStatus_;
         $comliantList=array();
         $comliantList=fetchAssignedTask($con,$jobRefNo,$clientCompanyId,$status,$createdBy,$dateFrom,$dateTo,$assignType,$workerId,$orgId,$complaintId,$jobId,$messageStatus);

         require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

         foreach($comliantList as $data){

             $clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);

             $fontWeight="font-weight:normal";

             if($data['messageStatus']==="N"){
                 $fontWeight="font-weight:bold";
             }

             if (!(array)$clientCompanyDetails) {

                 $rowOrg = getOrganizationDetails($con,$_SESSION['orgId']);

                 $clientName = $rowOrg['name'];

             }else {

                 $clientName = $clientCompanyDetails['name'];

             }

             //data-toggle='modal' data-target='#messageContent'

             $table.=  "<tr style='".$fontWeight.";cursor:pointer;' onclick='updateMessageStatus(".$data['idJt'].");viewTaskDetails(".$data['idJ'].",".$data['idJt'].")' data-toggle='modal' data-target='#messageContent' >";

             $table.= 	"<td>";

             $table.= 		sprintf("%07d",$data['complaintId']);
             //$table.= 		ticketno($data['complaintId']);

             $table.= 	"</td>";

             $table.= 	"<td>";

             $table.= 		$data['createdDateJt'];

             $table.= 	"</td>";

             $table.= 	"<td>";

             $table.= 		$data['refNo'];

             $table.= 	"</td>";

             $table.= 	"<td>";

             $table.= 		$clientName;

             $table.= 	"</td>";

             $table.= 	"<td>";

             $table.= 		$data['jobName'];

             $table.= 	"</td>";

             $table.= 	"<td>";

             if($data['status']===0){

                 $table.=  "completed";

             }

             else if($data['status']===1){

                 $table.=  "new";

             }else if($data['status']===2){

                 $table.=  "pending";

             }else if($data['status']===3){

                 $table.=  "in progress";

             }else if($data['status']===4){

                 $table.=  "resolved";

             }

             $table.= 	"</td>";

             $table.=  "</tr>";
         }

         $table.="</tbody>";

         $table.=  "</table>";

         $table.=  "</div>";

         echo $table;

     }

	function viewAssignedTask($jobRefNo_,$clientCompanyId_,$status_,$createdBy_,$dateFrom_,$dateTo_,$assignType_,$workerId_,$orgId_,$complaintId_,$jobId_,$messageStatus_)
	{

		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$table="";
		$table="<div class='table-responsive'>\n";

		$table.="<table class='table table-hover' id='dataTable' width='100%' cellspacing='0' >";

		$table.=  "<thead >";

		$table.=  "<tr>";

		$table.=  	"<th>";

		$table.=  		"TICKET NO";

		$table.=  	"</th>";

		$table.=  	"<th>";

		$table.=  		"ASSIGNED DATE";

		$table.=  	"</th>";

		$table.=  	"<th>";

		$table.=  		"REF NO";

		$table.=  	"</th>";

		$table.=  	"<th>";

		$table.=  		"CLIENT";

		$table.=  	"</th>";

		$table.=  	"<th>";
        if($_SESSION['orgType']==7){
            $table.=  		"TASK";
        }
        else{
            $table.=  		"PROBLEM";
        }
		

		$table.=  	"</th>";

		$table.=  	"<th>";

		$table.=  		"STATUS";

		$table.=  	"</th>";



		$table.=  "</tr>";

		$table.=  "</thead >";



		$table.="<tbody>";



		if($status_>=0 && $status_!==null){

			$status=$status_;

		}



		$jobRefNo=$jobRefNo_;

		$clientCompanyId=$clientCompanyId_;

		$status=$status_;

		$createdBy=$createdBy_;

		$dateFrom=$dateFrom_;

		$dateTo=$dateTo_;

		$assignType=$assignType_;

		$workerId=$workerId_;

		$orgId=$orgId_;

		$complaintId=$complaintId_;

		$jobId=$jobId_;

		$messageStatus=$messageStatus_;

		$complaintList=fetchAssignedTask($con,$jobRefNo,$clientCompanyId,$status,$createdBy,$dateFrom,$dateTo,$assignType,$workerId,$orgId,$complaintId,$jobId,$messageStatus);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

        foreach($complaintList as $data){
			//echo "<br/>".$data['clientCompanyId'];

			$clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);
			//echo "<br/>".$clientCompanyDetails['name'];
			$fontWeight="font-weight:normal";

			if($data['messageStatus']==="N"){
				$fontWeight="font-weight:bold";
			}

      if (!(array)$clientCompanyDetails) {

        $rowOrg = getOrganizationDetails($con,$_SESSION['orgId']);

        $clientName = $rowOrg['name'];

      }else {

        $clientName = $clientCompanyDetails['name'];

      }

			//data-toggle='modal' data-target='#messageContent'

			$table.=  "<tr style='".$fontWeight.";cursor:pointer;' onclick='updateMessageStatus(".$data['idJt'].");viewTaskDetails(".$data['idJ'].",".$data['idJt'].")' data-toggle='modal' data-target='#messageContent'  >";

			$table.= 	"<td>";

			$table.= 		ticketno($data['complaintId']);

			$table.= 	"</td>";

			$table.= 	"<td>";

			$table.= 		$data['createdDateJt'];

			$table.= 	"</td>";

			$table.= 	"<td>";

			$table.= 		$data['refNo'];

			$table.= 	"</td>";

			$table.= 	"<td>";

			$table.= 		$clientName;

			$table.= 	"</td>";

			$table.= 	"<td>";

			$table.= 		$data['jobName'];

			$table.= 	"</td>";

			$table.= 	"<td>";

			if($data['status']===0){

				$table.=  "completed";

			}

			else if($data['status']===1){

				$table.=  "new";

			}else if($data['status']===2){

				$table.=  "pending";

			}else if($data['status']===3){

				$table.=  "in progress";

			}else if($data['status']===4){

				$table.=  "resolved";

			}

			$table.= 	"</td>";

			$table.=  "</tr>";
		}

		$table.="</tbody>";

		$table.=  "</table>";

		$table.=  "</div>";

		echo $table;

	}

	function allJobTableAll(){
    GLOBAL $config;
		$con=connectDb();



		echo "<div class='table-responsive'>";

		echo "<table id='activeJob' class='table'>";

		echo "<thead class='thead-dark'>";

		echo "<tr>";

		echo 	"<th>";

		echo 		"#";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REF NO";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"JOB NAME";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"CLIENT";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REQUIRE DATE";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"REMARKS";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"STATUS";

		echo 	"</th>";

		echo 	"<th>";

		echo 		"OPTION";

		echo 	"</th>";

		echo "</tr>";

		echo "</thead >";

		$status=0;

		$dataList=fetchAllJobListAdmin($con);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

		$i=1;

		foreach($dataList as $data){



			//echo "<br/>".$data['clientCompanyId'];

			$clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);

			//echo "<br/>".$clientCompanyDetails['name'];



			echo "<tr ";

			if($i%2==0)

				echo "style='background-color:#FFF5EB;'";

			else{

				echo "style='background-color:#F9F9F9;'";

			}

			echo ">";

			echo	"<td><b>";

			echo		$i++;

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['refNo'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['jobName'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$clientCompanyDetails['name'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['requireDate'];

			echo	"</b></td>";

			echo	"<td><b>";

			echo		$data['remarks'];

			echo	"</b></td>";

			echo	"<td><b>";

			if($data['status']===0){

				echo "completed";

			}

			else if($data['status']===1){

				echo "new";

			}else if($data['status']===2){

				echo "in progress";

			}

			else if($data['status']===3){

				echo "pending";

			}

			else if($data['status']===4){

				echo "resolved";

			}

			echo	"</b></td>";

			echo	"<td><b>";

			echo	"<button name='viewJobDetail' value='".$data['id']."' class='btn btn-info' type='submit' >view</button>";

			echo	"</b></td>";

			echo "</tr>";





		}

		echo "</table>";

		echo "</div>";



	}



	function jobItemListForm($jobListId,$orgId){

		$con=connectDb();

		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		$script="<script>

		function toggleField(str){



		  if(str.id=='itemOthers'){

			 var qtyField=document.getElementById('itemQtyOthers');

			 var nameField=document.getElementById('itemNameOthers');

			 if(str.checked==true){

			  qtyField.style.display='block';

			  nameField.style.display='block';



			  qtyField.required='true';

			  nameField.required='true';

			}else{

			  qtyField.style.display='none';

			  nameField.style.display='none';



			  qtyField.required='';

			  nameField.required='';



			}

		  }else{

			var itemIndex=str.value;

			var qtyField=document.getElementById('item'+itemIndex+'Qty');

			if(str.checked==true){

			  qtyField.style.display='block';

			  qtyField.required='true';



			}else{

			  qtyField.style.display='none';

			  qtyField.required='';

			}



		  }

		}

	  </script>";

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobListItem.php");

		$form=$script;

		$form.='<div class="form-group row">

		<label for="items" class="col-sm-2 col-form-label col-form-label-lg">ITEM</label>

		  <div class="col-sm-10">';



		$jobListItems=fetchJobListItem($con,$jobListId,$orgId);



		$table='<table>';

		$itemIndex=1;

		foreach($jobListItems as $item){

			$table.="<tr/>";



			$table.="<td>";

			$table.=$item['itemName'];

			$table.="</td>";

//custom-switch-label-yesno

			$table.="<td>";

			$table.="<div>

						<div class='custom-switch '>

						<input class='form-control custom-switch-input' value='".$itemIndex."' onchange='toggleField(this)' id='item".$itemIndex."'

						name='item".$itemIndex."' type='checkbox'>

						<label class='custom-switch-btn' for='item".$itemIndex."'></label>

					</div>";

			$table.="</td>";



			$table.="<td>

					<input type='number'  min='1' style='display:none' class='form-control' placeholder='Enter quantity' id='item".$itemIndex."Qty' name='item".$itemIndex."Qty' />

			";

			$table.="</td>";



			$table.="<tr/>";

			$itemIndex++;

		}

		$table.="<tr>";



		$table.="<td>OTHERS";

		$table.="</td>";

		$table.="<td>

					<div

						<div class='custom-switch '>

						<input class='form-control custom-switch-input' onchange='toggleField(this)' id='itemOthers'

						name='itemOthers' type='checkbox'>

						<label class='custom-switch-btn' for='itemOthers'></label>

					</div>";

		$table.="</td>";



		$table.="<td>

					<input type='text' style='display:none' class='form-control' placeholder='Enter item name' id='itemNameOthers' name='itemNameOthers' />

		";

		$table.="</td>";



		$table.="<td>

					<input type='number' style='display:none' class='form-control' placeholder='Enter quantity' id='itemQtyOthers' name='itemQtyOthers' />

		";

		$table.="</td>";





		$table.="</tr>";



		$table.='</table>';



		$form.="$table";

		$form.="</div>";

		$form.="</div>";

		echo $form;



	}



  function tableSparePart(){

  $con=connectDb();

  $jobId = $_POST['jobId'];

  $workerId = $_SESSION['userid'];

  $dataList = fetchSpareParts($con,$jobId,$workerId);

  return $dataList;

  }



  function meterDetails($jobId){

    $con=connectDb();

    $jobDetail = fetchJobDetails($con,$jobId);

    $meter1 = $jobDetail['meter1'];

    $meter2 = $jobDetail['meter2'];

    $meter3 = $jobDetail['meter3'];

    $meter4 = $jobDetail['meter4'];

    $meterTotal = $jobDetail['meterTotal'];



    $meterArray[0] = $meter1;

    $meterArray[1] = $meter2;

    $meterArray[2] = $meter3;

    $meterArray[3] = $meter4;

    $meterArray[4] = $meterTotal;

    return $meterArray;

  }





  function actionDetail($jobId){

    $con=connectDb();

    $jobDetail = fetchJobDetails($con,$jobId);

    $action = $jobDetail['action'];

    return $action;

  }



  function startTime($jobId){

    $con=connectDb();

    $jobDetail = fetchJobDetails($con,$jobId);

    $startTime = $jobDetail['startTime'];

    return $startTime;

  }



  function productTableList($jobId){

    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

    $con=connectDb();

    $jobDetail = fetchJobDetails($con,$jobId);

    $complaintId = $jobDetail['complaintId'];

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/product.php");

    $dataList = fetchComplaintProductByComplaintId($con,$complaintId);



    $table = "<table style='border-collapse: collapse;border: 1px solid black;width: 100%;border: 1px solid black;'><tr><th style='background: grey;color: black;border: 1px solid black;'>PRODUCT</th><th style='background: grey;color: black;border: 1px solid black;'>CONTRACT STATUS</th></tr>";

    foreach ($dataList as $data) {

      $productIdComplaint = $data['productId'];

      $clientProductRow = fetchClientProductRowByProductId($con,$productIdComplaint);

      $productId = $clientProductRow['productId'];

      $cStatusId = $clientProductRow['cStatus'];

      $productRow = fetchProductListById($con,$productId);



      switch ($cStatusId) {

        case '0':

          $cStatus = "TG";

          break;

        case '1':

          $cStatus = "WTY";

          break;

        case '2':

          $cStatus = "PERCALL";

          break;

        case '3':

          $cStatus = "RENTAL";

          break;

        case '4':

          $cStatus = "AD HOC";

          break;

      }



      $table.= "<tr><td style='border: 1px solid black;'>".strtoupper($productRow['brand'])." ".$productRow['model']." [S/N:".$productRow['serialNum']."]</td><td style='border: 1px solid black;'>".$cStatus."</td></tr>";

    }

    $table.= "</table>";

    return $table;

  }



  if (isset($_POST['deleteSpareparts'])) {

    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

      <strong>FAILED!</strong> FAILED REMOVE SPAREPART\n

      </div>\n";

    $test="failed";

    $con=connectDb();

	$_SESSION['jobName'] = $_POST['jobName'];

  $_SESSION['jobId'] = $_POST['jobId'];

	$_SESSION['client'] = $_POST['client'];

  $_SESSION['jobTransId'] = $_POST['jobTransId'];

	$_SESSION['problemDetails'] = $_POST['problemDetails'];

	$_SESSION['zone'] = $_POST['zone'];

	$_SESSION['service'] = $_POST['service'];

	$_SESSION['csName'] = $_POST['csName'];

    $id = $_POST['sparepartToDelete'];

    $feedback = deleteSparepartsById($con,$id);

    if ($feedback) {

      $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong>SUCCESSFULLY REMOVED SPAREPART\n

					</div>\n";

    }

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/updateJob.php");

  }



  function remarksDetail($jobId){

    $con=connectDb();

    $jobDetail = fetchJobDetails($con,$jobId);

    $remarks = $jobDetail['remarks'];

    return $remarks;

  }

  function ticketno($ticket){

	  return sprintf("%07d",$ticket);

  }


	if(isset($_POST["updateAssignedMyTaskOrgStaff"])){

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobTransaction.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");



		$con=connectDb();



		$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n

		<strong>FAILED!</strong>JOB UPDATE FAILED\n

		</div>\n";

		$remarks=$_POST['remarks'];

		$updateSuccess=false;

		$base64Image=$_POST['imageBase64'];

		$jobId=$_POST['jobId'];



		//(START) GETTING LATITUDE & LONGITUDE VALUES

		$latitude = $_POST['latitude'];

		$longitude = $_POST['longitude'];

		$latlon = $latitude.",".$longitude;

		//(END) GETTING LATITUDE & LONGITUDE VALUES



		// start time & end time

		/*

		$startDateTime=explode("T",$_POST['startTime']);

		$startTime=$startDateTime[0]." ".$startDateTime[1].":00";

		$startTime=strtotime($startTime);

		$startTime=date('Y-m-d H:i:s',$startTime); */

		$startTime = $_POST['startTime'];



		$jobTransId=$_POST['jobTransId'];

		$status=$_POST['status'];

		$orgId=$_SESSION['orgId'];

		$createdBy=$_SESSION['userid'];

		if ($status == 0) {

			/*

			  $endDateTime=explode("T",$_POST['endTime']);

			  $endTime=$endDateTime[0]." ".$endDateTime[1].":00";

			  $endTime=strtotime($endTime);

			  $endTime=date('Y-m-d H:i:s',$endTime); */

			$endTime = $_POST['endTime'];

		}

		else {

			$endTime = NULL;

		}

		$troubleshoot="";

		$notes="";

		$modem="";

		$buc="";

		$lnb="";

		$feaderhon="";

		$antenna="";

		$connector="";

		$poweradaptor="";

		$docResolution="";

		$docRemarks="";

		$docStartTime="";

		$docEndTime="";

		$cid="";

		// Telecom Technical issue

		if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){

			$c=0;

			if(isset($_POST['modem'])){

				$modem=$_POST['modem'];

				$c=1;

			}

			if(isset($_POST['buc'])){

				if($c>0){

					$buc=", ".$_POST['buc'];

				}else{

					$buc=$_POST['buc'];

				}

				$c=$c+1;



			}

			if (isset($_POST['lnb'])) {

				if($c>0){$lnb=", ".$_POST['lnb'];

				}else{

					$lnb=$_POST['lnb'];

				}

				$c=$c+1;

			}



			if (isset($_POST['feaderhon'])) {

				if ($c>0) {

					$feaderhon=", ".$_POST['feaderhon'];

				} else {

					$feaderhon=$_POST['feaderhon'];

				}

				$c=$c+1;

			}



			if (isset($_POST['antenna'])) {

				if($c>0){$antenna=", ".$_POST['antenna'];

				} else {

					$antenna=$_POST['antenna'];

				}

				$c=$c+1;

			}

			if (isset($_POST['connector'])) {

				if($c>0){$connector=", ".$_POST['connector'];

				} else {

					$connector=$_POST['connector'];

				}

				$c=$c+1;

			}

			if (isset($_POST['poweradapter'])) {

				if($c>0){$poweradaptor=", ".$_POST['poweradapter'];

				} else {

					$poweradaptor=$_POST['poweradapter'];

				}

				$c=$c+1;

			}



			$notes=$_POST['notes'];

			if ($status==4) {

				$endTime = $_POST['endTime'];

			}



			$docEndTime=str_replace("T"," ",$endTime);

			$troubleshoot="".$modem.$buc.$lnb.$feaderhon.$antenna.$connector.$poweradaptor;

			$docResolution=(isset($_POST['action'])?$_POST['action']:"");

			$docRemarks=(isset($_POST['remarks'])?$_POST['remarks']:"");

			$cid=$_SESSION['cid'];

		}



		$jobDetail=fetchJobDetails($con,$jobId);

		$jobRefNo=$jobDetail['refNo'];



		$jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/orgJob/".$jobRefNo;



		//	$jobDirectory="./../resources/".$orgId."/orgJob/".$jobRefNo;

		//file_put_contents("./log_file.log","\nRun 1 Before path:".$jobDirectory,FILE_APPEND);

		if (!file_exists($jobDirectory)) {

			mkdir($jobDirectory, 0777, true);

		}

		$signaturePath="";
		if(!empty(trim($base64Image))) {
			list(, $base64Image) = explode(',', $base64Image);

			$base64Image = base64_decode($base64Image);


			file_put_contents($jobDirectory . '/' . $jobTransId . '.png', $base64Image);

			//copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');

			//file_put_contents("./log_file.log","\nRun 2 Before path",FILE_APPEND);

			copy("./../index.php", $jobDirectory . '/index.php');

			$signaturePath = $orgId . "/orgJob/" . $jobRefNo . "/" . $jobTransId;
		}
		//file_put_contents("./log_file.log","\nRun  Before path:".$signaturePath,FILE_APPEND);

		$updateSuccess=updateJobStatus($con,$jobId,$status);

		$updateSuccess=updateJobSignaturePath($con,$jobId,$signaturePath);

		$updateSucesss=updateJobWorkingPerod($con,$jobId,$startTime,$endTime);

		$updateSuccess=updateJobLatLon($con,$jobId,$latlon);



		$updateSuccess=updateJobTransStatus($con,$jobTransId,$status);



		$updateSuccess=updateJobTransRemarks($con,$jobTransId,$remarks);

		$updateSucess=updateJobTransactionSignaturePath($con,$jobTransId,$signaturePath);

		$updateSucesss=updateJobTransactionWorkingPerod($con,$jobId,$startTime,$endTime);



		$complaintDetails=fetchComplainDetails($con,$jobDetail['complaintId']);

		$updateSuccess=updateClientComplaint($con,$complaintDetails['id'],$complaintDetails['issueName'],$complaintDetails['issueDetail'],

			$complaintDetails['occuredDate'],$complaintDetails['picName'],$complaintDetails['picContactNo'],

			$complaintDetails['createdDate'],$complaintDetails['createdBy'],$complaintDetails['messageStatus'],$status,

			$complaintDetails['companyId'],$complaintDetails['orgId']);

		$result="";

		// telecom

		if ($updateSuccess&&isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) {

			if(isset($_POST['updateAssignedTask'])){

				if (empty(trim($complaintDetails['docattenddate']))) {

					//		$res=stripos($complaintDetails['remarkslog'],"FE");

					//        if ($res>0) {

					$docStartTime=str_replace("T", " ", $startTime);

					//        }

				} else {

					$docStartTime=$complaintDetails['docattenddate'];

				}

			}



			if (!empty(trim($docRemarks))) {

				if (!empty(trim($complaintDetails['remarkslog']))) {

					$docRemarks=$complaintDetails['remarkslog']."<br />[".date('Y-m-d')." ".date("h:i")."]: FE ".$docRemarks;

				}

				else{

					if (empty(trim($complaintDetails['remarkslog']))) {

						$docRemarks="[".date('Y-m-d')." ".date("h:i")."]: FE ".$docRemarks;

					}

				}

			} else{

				$notes=$complaintDetails['remarkslog'];

			}

			if (!empty(trim($notes))) {

				if (!empty($complaintDetails['note'])) {

					$notes=$complaintDetails['note']."<br />[".date('Y-m-d')." ".date("h:i")."]: FE ".$notes;

				} else {

					if (empty(trim($complaintDetails['note']))) {

						$notes="[".date('Y-m-d')." ".date("h:i")."]: FE ".$notes;

					}

				}

			} else{

				$notes=$complaintDetails['note'];

			}



			$result=updateDocketComplaint($con, $troubleshoot, $docStartTime, $docResolution, $docRemarks, $notes, $cid);

			file_put_contents("./0update.log","\n\n".print_r($result,1),FILE_APPEND);


			//troubleshoot, docattenddate, docclosedate,resolution,remarks,note,cid";

		}elseif($updateSuccess && isset($_SESSION['orgType']) && $_SESSION['staffCam']==1){
			if(isset($_POST['updateAssignedTask'])){
				$img = $_POST['image'];
				$taken=$_POST['taken'];

				$folderPath = "upload/";


				foreach ($img as $data) {
					// code...

					$image_parts = explode(";base64,", $data);

					$image_type_aux = explode("image/", $image_parts[0]);

					$image_type = $image_type_aux[1];



					$image_base64 = base64_decode($image_parts[1]);

					$fileName = date('Ymd')."".uniqid() .'.png';



					$file = $jobDirectory .'/'. $fileName;

					file_put_contents($file, $image_base64);

					updateComplaintImage($con,$fileName,$complaintDetails['id'],$taken);




				}
			}
		}



		//(START)RECHARTS ADDITIONAL VARIABLES

		//(START)METER READING

		if (isset($_POST['meter1'])) {

			$meter1 = $_POST['meter1'];

		}else {

			$meter1 = NULL;

		}

		if (isset($_POST['meter2'])) {

			$meter2 = $_POST['meter2'];

		}else {

			$meter2 = NULL;

		}

		if (isset($_POST['meter3'])) {

			$meter3 = $_POST['meter3'];

		}else {

			$meter3 = NULL;

		}

		if (isset($_POST['meter4'])) {

			$meter4 = $_POST['meter4'];

		}else {

			$meter4 = NULL;

		}

		if (isset($_POST['meterTotal'])) {

			$meterTotal = $_POST['meterTotal'];

		}else {

			$meterTotal = NULL;

		}

		$feedbackMeterReading = updateMeterReading($con, $jobId, $meter1, $meter2, $meter3, $meter4,$meterTotal);

		if (!$feedbackMeterReading) {

			$updateSuccess = false;

		}

		//(END)METER READING



		//(START)ACTION TAKEN

		if (isset($_POST['action'])) {

			$action = $_POST['action'];

		}else{

			$action = NULL;

		}



		$feedbackAction = updateActionTaken($con, $jobId, $action);

		if (!$feedbackAction) {

			$updateSuccess = false;

		}

		//(END)ACTION TAKEN



		//(START)REMARKS

		if (isset($_POST['remarks'])) {

			$remarks = $_POST['remarks'];

			$feedbackRemarks = updateJobRemarks($con, $jobId, $remarks);

			if (!$feedbackRemarks) {

				$updateSuccess = false;

			}

		}

		//(END)REMARKS



		//(START)CHARGES

		if (isset($_POST['zone'])) {

			echo $zone = $_POST['zone'];

			$feedbackZone = updateJobZoneCharge($con, $jobId, $zone);

			if (!$feedbackZone) {

				$updateSuccess = false;

			}

		}

		if (isset($_POST['service'])) {

			echo $service = $_POST['service'];

			$feedbackService = updateJobServiceCharge($con, $jobId, $service);

			if (!$feedbackService) {

				$updateSuccess = false;

			}

		}

		//(END)CHARGES



		//(START)CUSTOMER'S NAME

		if (isset($_POST['csName'])) {

			echo $csName = $_POST['csName'];

			$feedbackCsName = updateJobCsName($con, $jobId, $csName);

			if (!$feedbackCsName) {

				$updateSuccess = false;

			}

		}

		//(END)CUSTOMER'S NAME



		//START SPAREPARTS

		if ($_POST['spDes0'] != NULL ) {

			$spDes = "spDes";

			$spQty = "spQty";

			$workerId=$_SESSION['userid'];

			$jobId = $_POST['jobId'];

			$i = 0;

			$itest =50;

			while ($i < $itest) {

				echo $spDes.$i."<br>";

				$description = $_POST[$spDes.$i];

				$qty = $_POST[$spQty.$i];

				$sparepart = insertSpareParts($con,$jobId,$workerId,$description,$qty);

				if (!$sparepart) {

					echo "an error occur<br>";

					break;

				}

				$i++;

			}

		}

		//END SPAREPARTS

		//(END)RECHARTS ADDITIONAL VARIABLE

		if($updateSuccess){

			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

						<strong>SUCCESS!</strong>SUCCESSFULLY ASSIGNED JOB\n

						</div>\n";

		}

		//if (isset($_POST['emailsSubmit']))) {

		if (isset($_POST['emailsSubmit']) && !empty($_POST['emailsSubmit'])) {

			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");

			$emailsSubmit = $_POST['emailsSubmit'];

			$emailsCcSubmit = $_POST['emailsCcSubmit'];

			$invoiceNo = $_POST['invoiceNo'];

			$_SESSION['feedback'] .= mailInvoice($emailsSubmit,$emailsCcSubmit,$invoiceNo);

		}

	header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/myTask/viewTask.php");

	}

?>