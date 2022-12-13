<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");


require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


	function mailTask($from,$fromName,$to,$subject,$body,$orgLogo){
	  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$mailMessage="/n<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
		</div>\n";

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    	$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
		$address=$orgDetails['supportEmail'];
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
		$ccemail= getCCEmailId();
		$footerCode=getFooterContent();
		$body.=$footerCode;

		$email = new PHPMailer();
        getEmailConfigCustom($email);
		$email->From      = $from;
		$email->FromName  = strtoupper($fromName);
		$email->Subject   = $subject;
		$email->Body      = $body;
		$email->AddAddress($to);
	//	$email->AddCC( substr($address,1,strlen($address)-2));
		$email->AddCC($ccemail,$ccemail);



		$email->IsHTML(true);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgLogo.".png", 'logo_2u');



		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		} else {
			$mailMessage="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
			</div>\n";
		}

		return $mailMessage;
	}
	//SMS function debug
	function smsNotification($nortiSmsNum,$subject,$message){
		$gwUser = 'jsofthttps';
		$gwPass = 'JRZg8Q';
		$gwFrom = $subject;
		$gwTo = '6'.$nortiSmsNum;
		$gwText = ''.$message.'';
		// Build the header
		$baseUrl = 'https://110.4.44.41:15001/cgi-bin/sendsms';
		file_put_contents("./sms.log","\n[".date(DATE_RFC822)."]:number".$gwTo."result:",FILE_APPEND);
		$data = array(
			'gw-username' => $gwUser,
			'gw-password' => $gwPass,
			'gw-from' => $gwFrom,
			'gw-to' => $gwTo,
			'gw-text' => $gwText,
			'gw-coding' => '1',
			'gw-dlr-mask' => '0'
		);

		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded",
				'method' => 'POST',
				'content' => http_build_query($data)
			),
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents($baseUrl, false, $context);
		file_put_contents("./sms.log","".$result,FILE_APPEND);
		$result=explode("&",$result);
		$result=explode("=",$result[0]);
		return $result[1];

	}

  function mailInvoice($to,$cc,$invoiceNo){
	  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $con = connectDb();
		$mailMessage="/n<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
		</div>\n";

	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
    $rowInvoice = fetchInvoiceDetailsByInvoiceNo($con,$invoiceNo);
    $invoiceName = $rowInvoice['fileName'];
    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/invoice/";
		$filename=$invoiceName.".pdf";

	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
		$address=$orgDetails['supportEmail'];

    $subject = "INVOICE_#".$invoiceName;
    $body = "INVOICE_#".$invoiceName;

	  $footerCode=getFooterContent();
	  $body.=$footerCode;


	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
	  $ccemail= getCCEmailId();
		$email = new PHPMailer();
      getEmailConfigCustom($email);
		$email->From      = $orgDetails['supportEmail'];
		$email->FromName  = $orgDetails['name'];
		$email->Subject   = $subject;
		$email->Body      = $body;

		$email->AddAddress($to);
	  $email->AddCC($ccemail,$ccemail);
		$email->AddCC($cc);
    $email->AddAttachment( $file_to_attach.$filename , "INVOICE_#".$invoiceName.".pdf" );

		$email->IsHTML(true);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgDetails['logoPath'].".png", 'logo_2u');



		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		} else {
			$mailMessage="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
			</div>\n";
		}

		return $mailMessage;
	}

  function mailPayrolls($to,$cc,$payslips){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $con = connectDb();
    $mailMessage="/n<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
    </div>\n";

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
    $address=$orgDetails['supportEmail'];

    $subject = "PAYSLIP";
    $body = "Payslip ".date("Y/m/d");
	  $footerCode=getFooterContent();
	  $body.=$footerCode;
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
	  $ccemail= getCCEmailId();
    $email = new PHPMailer();
      getEmailConfigCustom($email);
    $email->From      = $orgDetails['supportEmail'];
    $email->FromName  = $orgDetails['name'];
    $email->Subject   = $subject;
    $email->Body      = $body;
    $email->AddAddress($to);
    $email->AddCC($ccemail,$ccemail);
    $email->AddCC($cc);
    $payslipArr = json_decode($payslips);
	  $payslipName="";
	  $file_to_attach="";
	  $filename="";
    foreach ($payslipArr as $payslip) {
		$payslipName="";
		$file_to_attach="";
		$filename="";
      $rowPayslip = fetchPayslipRowById($con,$payslip);
      if($rowPayslip['payslip']==null){
		  $voucher=fetchVoucherRowById($con,$payslip);
        $payslipName = $voucher['paymentVoucher'];
        $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/paymentVoucher/";
        $filename=$payslipName;
        $email->AddAttachment( $file_to_attach.$filename , "PAYMENTVOUCHER_#".$payslipName);
      }else{
        $payslipName = $rowPayslip['payslip'];
        $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/payslip/";
        $filename=$payslipName;
        $email->AddAttachment( $file_to_attach.$filename , "PAYSLIP_#".$payslipName);
      }
    }

    $email->IsHTML(true);
    $email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgDetails['logoPath'].".png", 'logo_2u');

    if(!$email->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $email->ErrorInfo;
    } else {
      $mailMessage="<div class='alert alert-success' role='alert'>\n
      <strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
      </div>\n";
    }

    return $mailMessage;
  }

  function emailPurchaseOrder($poId,$to,$cc){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $con = connectDb();
    $mailMessage="/n<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
    </div>\n";

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
    $address=$orgDetails['supportEmail'];

    $subject = "PURCHASE ORDER";
    $body = "Purchase Order ".date("Y/m/d");
	  $footerCode=getFooterContent();
	  $body.=$footerCode;
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
	  $ccemail= getCCEmailId();
    $email = new PHPMailer();
      getEmailConfigCustom($email);
    $email->From      = $orgDetails['-'];
    $email->FromName  = $orgDetails['name'];
    $email->Subject   = $subject;
    $email->Body      = $body;
    $email->AddAddress($to);
	  $email->AddCC($ccemail,$ccemail);
    $email->AddCC($cc);

    $row = fetchPOGeneratedById($con,$poId);
    $poName = $row['fileName'];
    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/purchaseOrder/";
    $filename=$poName;
    $email->AddAttachment( $file_to_attach.$filename , "PO_#".$filename);

    $email->IsHTML(true);
    $email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgDetails['logoPath'].".png", 'logo_2u');


    if(!$email->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $email->ErrorInfo;
    } else {
      $mailMessage="<div class='alert alert-success' role='alert'>\n
      <strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
      </div>\n";
    }

    return $mailMessage;
  }

	if(isset($_POST['mailQuotation'])){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationMailList.php");


		$total = count($_FILES['files']['name']);



		$orgId=$_SESSION['orgId'];
		$orgDetails=getOrganizationDetails($con,$orgId);

		$toAddress=null;
		$ccAddress=null;
		$messageBody=$_POST['messageBody'];

		if(isset($_POST['quotationMailAddress'])){
			$toAddress=$_POST['quotationMailAddress'];
			$toAddress=substr($toAddress,1,strlen($toAddress)-2);
			$toAddress=explode(",",$toAddress);
		}

		if(isset($_POST['quotationMailAddressCC'])){
			$ccAddress=$_POST['quotationMailAddressCC'];
			$ccAddress=substr($ccAddress,1,strlen($ccAddress)-2);
			$ccAddress=explode(",",$ccAddress);
		}

		$quotatationDetails=fetchQuotationDetailsByQuotationNo($con,$_SESSION['quotationNumber']);
		$deleteSucess=deleteQuotationMailListByQuotId($con,$quotatationDetails['id']);
		foreach($toAddress as $address){
			$maillAddress=substr($address,1,strlen($address)-2) ;
			if(strlen($maillAddress)>0){
				createMailSentList($con,$quotatationDetails['id'],$maillAddress,"TO",$orgId);
			}
		}

		foreach($ccAddress as $address){
			$maillAddress=substr($address,1,strlen($address)-2) ;
			if(strlen($maillAddress)>0){
				createMailSentList($con,$quotatationDetails['id'],$maillAddress,"CC",$orgId);
			}
		}

		$messageBody=preg_replace("/id=\"myOrgLogo\" src=\".*?\"/", "src='cid:logo_2u'", $messageBody);

		$footer="";
		$footerCode=getFooterContent();
		$messageBody.=$footerCode;

		$email = new PHPMailer();
        getEmailConfigCustom($email);
		$email->From      = $orgDetails['salesEmail'];
		$email->FromName  = strtoupper($orgDetails['name']);
		$email->Subject   = $_POST['subject'];



		$email->Body      = $messageBody;
		$email->IsHTML(true);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
		$ccemail= getCCEmailId();


		foreach($toAddress as $address){
			$email->AddAddress( substr($address,1,strlen($address)-2) );


		}
		foreach($ccAddress as $address){
			$email->AddCC( substr($address,1,strlen($address)-2));

		}
		$email->AddCC($ccemail,$ccemail);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$_SESSION['orgLogo'].".png", 'logo_2u');


		$file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/quotation/";


		$filename=$quotatationDetails['fileName'].".pdf";

		// attach quotation file
		$email->AddAttachment( $file_to_attach.$filename , "QUOTATION_#".$_SESSION['quotationNumber'].".pdf" );


		// attach uploaded file
		$date = new DateTime(null, new DateTimeZone('Asia/Kuala_Lumpur'));
        $directory=$date->getTimestamp();
		for( $i=0 ; $i < $total ; $i++ ) {

			//Get the temp file path
			$tmpFilePath = $_FILES['files']['tmp_name'][$i];

			//Make sure we have a file path
			if ($tmpFilePath != ""){
			  //Setup our new file path

               if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
                       mkdir(dirname(__DIR__)."/temp/".$directory, 0777, true);

               }


			  $newFilePath = dirname(__DIR__)."/temp/".$directory."/" . $_FILES['files']['name'][$i];

			  //Upload the file into the temp dir

			  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
			  	 $email->addAttachment($newFilePath);
				//Handle other code here

			  }
			}
		}


		$mailMessage="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong> FAILED TO SEND QUOTATION \n
			</div>\n";

		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		  } else {
				$mailMessage="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS !</strong> QUOTATION SENT SUCCESSFULLY\n
				</div>\n";
		  }

	 	if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
	        array_map('unlink', glob(dirname(__DIR__)."/temp/".$directory."/*.*"));
            rmdir(dirname(__DIR__)."/temp/".$directory);
	    }
		$_SESSION['feedback']=$mailMessage;
		unset($_SESSION['quotationNumber']);

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/viewQuotation.php");
	}

	if(isset($_POST['mailInvoice'])){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");

		$total = count($_FILES['files']['name']);

		$orgId=$_SESSION['orgId'];
		$orgDetails=getOrganizationDetails($con,$orgId);

		$toAddress=null;
		$ccAddress=null;
		$messageBody=$_POST['messageBody'];

		if(isset($_POST['invoiceMailAddress'])){
			$toAddress=$_POST['invoiceMailAddress'];
			$toAddress=substr($toAddress,1,strlen($toAddress)-2);
			$toAddress=explode(",",$toAddress);

		}


		if(isset($_POST['invoiceMailAddressCC'])){
			$ccAddress=$_POST['invoiceMailAddressCC'];
			$ccAddress=substr($ccAddress,1,strlen($ccAddress)-2);
			$ccAddress=explode(",",$ccAddress);
		}
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoiceMailList.php");

		$invoiceDetails=fetchInvoiceDetailsByInvoiceNo($con,$_SESSION['invoiceNumber']);
		$deleteSucess=deleteInvoiceMailListByInvId($con,$invoiceDetails['id']);
		foreach($toAddress as $address){
			$maillAddress=substr($address,1,strlen($address)-2) ;
			if(strlen($maillAddress)>0){
				createMailSentList($con,$invoiceDetails['id'],$maillAddress,"TO",$orgId);
			}
		}

		foreach($ccAddress as $address){
			$maillAddress=substr($address,1,strlen($address)-2) ;
			if(strlen($maillAddress)>0){
				createMailSentList($con,$invoiceDetails['id'],$maillAddress,"CC",$orgId);
			}
		}

		$messageBody=preg_replace("/id=\"myOrgLogo\" src=\".*?\"/", "src='cid:logo_2u'", $messageBody);
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");

		$footer="";
		$footerCode=getFooterContent();
		$messageBody.=$footerCode;

		$ccemail= getCCEmailId();
		$email = new PHPMailer();
        getEmailConfigCustom($email);
		$email->From      = $orgDetails['salesEmail'];
		$email->FromName  = strtoupper($orgDetails['name']);
		$email->Subject   = $_POST['subject'];

		$footer="";
		$email->Body      = $messageBody;
		$email->IsHTML(true);

		foreach($toAddress as $address){
			$email->AddAddress( substr($address,1,strlen($address)-2) );

		}

		foreach($ccAddress as $address){
			$email->AddCC( substr($address,1,strlen($address)-2));

		}
		$email->AddCC($ccemail,$ccemail);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$_SESSION['orgLogo'].".png", 'logo_2u');

		//$email->SMTPAuth  =  "true";

		$file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/invoice/";

		$filename=$invoiceDetails['fileName'].".pdf";

		// add invoice file
		$email->AddAttachment( $file_to_attach.$filename , "INVOICE_#".$_SESSION['invoiceNumber'].".pdf" );

		// attach uploaded file
		$date = new DateTime(null, new DateTimeZone('Asia/Kuala_Lumpur'));
		$directory=$date->getTimestamp();
		for( $i=0 ; $i < $total ; $i++ ) {

			//Get the temp file path
			$tmpFilePath = $_FILES['files']['tmp_name'][$i];

			//Make sure we have a file path
			if ($tmpFilePath != ""){
			//Setup our new file path

			if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
					mkdir(dirname(__DIR__)."/temp/".$directory, 0777, true);

			}


			$newFilePath = dirname(__DIR__)."/temp/".$directory."/" . $_FILES['files']['name'][$i];

			//Upload the file into the temp dir

			if(move_uploaded_file($tmpFilePath, $newFilePath)) {
				$email->addAttachment($newFilePath);
				//Handle other code here

			}
			}
		}

		$mailMessage="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong> FAILED TO SEND INVOICE \n
			</div>\n";

		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		  } else {
				$mailMessage="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS !</strong> INVOICE SENT SUCCESSFULLY\n
				</div>\n";
		  }

		if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
	        array_map('unlink', glob(dirname(__DIR__)."/temp/".$directory."/*.*"));
            rmdir(dirname(__DIR__)."/temp/".$directory);
	    }
		$_SESSION['feedback']=$mailMessage;

		unset($_SESSION['invoiceNumber']);
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoice/viewInvoice.php");

	}

	if(isset($_GET['mailSuggestion'])){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/mail.php");

		$emails=fetchEmailSuggestion($con,$_GET['key'],2);
		echo json_encode($emails);

	}


  if(isset($_POST['mailReceipt'])){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");

		$total = count($_FILES['files']['name']);



		$orgId=$_SESSION['orgId'];
		$orgDetails=getOrganizationDetails($con,$orgId);

		$toAddress=null;
		$ccAddress=null;
		$messageBody=$_POST['messageBody'];
	  $footer="";
	  $footerCode=getFooterContent();
	  $messageBody.=$footerCode;

		if(isset($_POST['mailAddress'])){
			$toAddress=$_POST['mailAddress'];
			$toAddress=substr($toAddress,1,strlen($toAddress)-2);
			$toAddress=explode(",",$toAddress);
		}

		if(isset($_POST['mailAddressCC'])){
			$ccAddress=$_POST['mailAddressCC'];
			$ccAddress=substr($ccAddress,1,strlen($ccAddress)-2);
			$ccAddress=explode(",",$ccAddress);
		}


		$messageBody=preg_replace("/id=\"myOrgLogo\" src=\".*?\"/", "src='cid:logo_2u'", $messageBody);
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
	  $ccemail= getCCEmailId();
		$email = new PHPMailer();
        getEmailConfigCustom($email);
		$email->From      = $orgDetails['salesEmail'];
		$email->FromName  = strtoupper($orgDetails['name']);
		$email->Subject   = $_POST['subject'];

		$footer="";


		$email->Body      = $messageBody;
		$email->IsHTML(true);

		foreach($toAddress as $address){
			$email->AddAddress( substr($address,1,strlen($address)-2) );


		}
		foreach($ccAddress as $address){
			$email->AddCC( substr($address,1,strlen($address)-2));

		}
		$email->AddCC($ccemail,$ccemail);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$_SESSION['orgLogo'].".png", 'logo_2u');


		$file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/receipt/";


		$filename = $_SESSION['receiptNumber'].".pdf";

		// attach receipt file
		$email->AddAttachment( $file_to_attach.$filename , "RECEIPT_#".$_SESSION['receiptNumber'].".pdf" );


		// attach uploaded file
		$date = new DateTime(null, new DateTimeZone('Asia/Kuala_Lumpur'));
        $directory=$date->getTimestamp();
		for( $i=0 ; $i < $total ; $i++ ) {

			//Get the temp file path
			$tmpFilePath = $_FILES['files']['tmp_name'][$i];

			//Make sure we have a file path
			if ($tmpFilePath != ""){
			  //Setup our new file path

               if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
                       mkdir(dirname(__DIR__)."/temp/".$directory, 0777, true);

               }


			  $newFilePath = dirname(__DIR__)."/temp/".$directory."/" . $_FILES['files']['name'][$i];

			  //Upload the file into the temp dir

			  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
			  	 $email->addAttachment($newFilePath);
				//Handle other code here

			  }
			}
		}


		$mailMessage="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong> FAILED TO SEND RECEIPT \n
			</div>\n";

		if(!$email->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $email->ErrorInfo;
		  } else {
				$mailMessage="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS !</strong> RECEIPT SENT SUCCESSFULLY\n
				</div>\n";
		  }

	 	if (!file_exists(dirname(__DIR__)."/temp/".$directory)) {
	        array_map('unlink', glob(dirname(__DIR__)."/temp/".$directory."/*.*"));
            rmdir(dirname(__DIR__)."/temp/".$directory);
	    }
		$_SESSION['feedback']=$mailMessage;
		unset($_SESSION['receiptNumber']);

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/receipt.php");
	}

  function getFooterContent(){
	  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
	  $con=connectDb();
	  $orgId=2;
	  $orgDetails=getOrganizationDetails($con,$orgId);

	  $orgAddress=$orgDetails['address1'].",";
	  if($orgDetails['address2']!=null){
		  $orgAddress.="<br/>".$orgDetails['address2'].",";
	  }

	  $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";
	  $orgAddress.= "<br/>".$orgDetails['state'];
	  //$orgLogo=$_SESSION['orgLogo'];
	  $orgLogo=$orgDetails['logoPath'];
//path for the image
	  //$source_url = "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$orgLogo.".png";
	  $source_url ="../resources/".$orgLogo.".png";

//separate the file name and the extention
	  $source_url_parts = pathinfo($source_url);
	  $filename = $source_url_parts['filename'];
	  $extension = $source_url_parts['extension'];
	  $footer="";
//detect the width and the height of original image
	  list($width, $height) = getimagesize($source_url);
	  $width;
	  $height;

//define any width that you want as the output. mine is 200px.
	  $after_width = 200;
	  $newfilename="";
//resize only when the original image is larger than expected with.
//this helps you to avoid from unwanted resizing.
	  if ($width > $after_width) {

		  //get the reduced width
		  $reduced_width = ($width - $after_width);
		  //now convert the reduced width to a percentage and round it to 2 decimal places
		  $reduced_radio = round(($reduced_width / $width) * 100, 2);

		  //ALL GOOD! let's reduce the same percentage from the height and round it to 2 decimal places
		  $reduced_height = round(($height / 100) * $reduced_radio, 2);
		  //reduce the calculated height from the original height
		  $after_height = $height - $reduced_height;

		  //Now detect the file extension
		  //if the file extension is 'jpg', 'jpeg', 'JPG' or 'JPEG'
		  if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
			  //then return the image as a jpeg image for the next step
			  $img = imagecreatefromjpeg($source_url);
		  } elseif ($extension == 'png' || $extension == 'PNG') {
			  //then return the image as a png image for the next step
			  $img = imagecreatefrompng($source_url);
		  } else {
			  //show an error message if the file extension is not available
			  echo 'image extension is not supporting';
		  }

		  //HERE YOU GO :)
		  //Let's do the resize thing
		  //imagescale([returned image], [width of the resized image], [height of the resized image], [quality of the resized image]);
		  $imgResized = imagescale($img, $after_width, $after_height);
		  //now save the resized image with a suffix called "-resized" and with its extension.
		  if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
			  imagejpeg($imgResized, "../resources/2/email-footer-resized.".$extension);
		  } elseif ($extension == 'png' || $extension == 'PNG') {
			  imagepng($imgResized, "../resources/2/email-footer-resized." . $extension);
		  } else {
			  //show an error message if the file extension is not available
			  echo 'image extension is not supporting';
		  }
		  //Finally frees any memory associated with image
		  //**NOTE THAT THIS WONT DELETE THE IMAGE
		  $newfilename="email-footer-resized.".$extension;
		  imagedestroy($img);
		  imagedestroy($imgResized);
		  $footer='<br/><img id="myorglogo" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/2/'.$newfilename.'">';
	  }else{
		  $newfilename="no";
		  $footer='<br/><img id="myorglogo" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$orgLogo.'.png">';
	  }
	  //$footer='<br/><table width="150px"><tr><td><img style="max-height:80px;max-width:150px"  id="myorglogo" src="cid:logo_2u"></td></tr></table>


	  $footer.='<br/>'.$orgAddress;
	  return $footer;
  }
function mailsend($from,$fromName,$to,$subject,$body){
	$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
	$mailMessage="/n<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
		</div>\n";
	$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
	$address=$orgDetails['supportEmail'];
	$orgLogo=$orgDetails['logoPath'];
	$fromName1=$orgDetails['name'];
	$ccemail= getCCEmailId();
	$footerCode=getFooterContent();
	$body.=$footerCode;

	$email = new PHPMailer();
    getEmailConfigCustom($email);
	$email->From      = $address;
	$email->FromName  = $fromName1;
	$email->Subject   = $subject;
	$email->Body      = $body;

	$email->addAddress($to);
	//	$email->AddCC( substr($address,1,strlen($address)-2));
    $email->addCC($ccemail,$ccemail);

	$email->isHTML(true);
	$email->addEmbeddedImage(dirname(__DIR__)."/resources/".$orgLogo.".png", 'logo_2u');

	if(!$email->send()) {
		$mailMessage="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED !</strong> E-MAIL NOT SENT SUCCESSFULLY\n
			</div>\n";
	} else {
		$mailMessage="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
			</div>\n";
	}

	return $mailMessage;
}
function sendFormEmail($id){
      $con=connectDb();
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");

        $data = array();
        $query = "SELECT * FROM `newrequest` AS nr, membershiplan AS mp WHERE nr.`clientType`=0 AND nr.`id`=".$id." AND `mp`.id=`nr`.`mpid` ORDER BY nr.id DESC;";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while (($row = $result->fetch_assoc()) != false) {
            $data = $row;
        }

    $name=$data['name'];
    $to=$data['email'];
    $fileName=$data['path'];
    $path= "/resources/2/plan/";
    $filePdf = $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].$path."".$fileName;


    $_SESSION['feedback']="/n<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
		</div>\n";
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
    $address=$orgDetails['supportEmail'];
    $orgLogo=$orgDetails['logoPath'];
    $fromName1=$orgDetails['name'];
    $ccemail= getCCEmailId();
    $footerCode=getFooterContent();
    $body="Hi, ".$name."<br /><br />";
    $body.="Document attachment is mandatory to fulfill for registration process. <br />Kindly provide this document copy by email after fulfil the document. <br /><br /> Thank you!";
    $body.=$footerCode;
    $subject="Mandatory Document For Registration";

    $email = new PHPMailer();
    getEmailConfigCustom($email);
    $email->From      = $address;
    $email->FromName  = $fromName1;
    $email->Subject   = $subject;
    $email->Body      = $body;

    $email->addAddress($to);
    //	$email->AddCC( substr($address,1,strlen($address)-2));
    $email->addCC($ccemail,$ccemail);

    $email->isHTML(true);
    $email->addEmbeddedImage(dirname(__DIR__)."/resources/".$orgLogo.".png", 'logo_2u');
    $email->AddAttachment($filePdf, "document_required_".$fileName);
    if(!$email->send()) {
        $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED !</strong> E-MAIL NOT SENT SUCCESSFULLY\n
			</div>\n";
    } else {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
			</div>\n";
    }
}

 function getEmailConfigCustom($mail){
   /*  $mail->isSMTP();                            // Set mailer to use SMTP
     $mail->Host = 'mail.jcloud.my';           // Specify main and backup SMTP servers
     $mail->SMTPAuth = true;                     // Enable SMTP authentication
     $mail->Username = 'email@jcloud.my';       // SMTP username
     $mail->Password = 'email@jcloud.my';         // SMTP password
     $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
     $mail->Port = 587;                          // TCP port to connect to
   */
 }
