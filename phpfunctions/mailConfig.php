<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);

	session_start(); 
}
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/mailConfig.php");

	if(isset($_POST['updateMailConfiguration'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO UPDATE DETAILS \n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$host=$_POST['host'];
		$port=$_POST['port'];
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		$smptsecure=$_POST['smptsecure'];
		$updateSuccess=updateMailConfig($con,$host,$port,$user,$pass,$smptsecure);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY MAIL CONFIG UPDATED \n
			</div>\n";
		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/mailConfiguration.php");
	}

function getHelpDetails(){
	$con=connectDb();
	   $data= fetchHelpDetails($con);
	    return $data;
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/vendor/autoload.php");
if(isset($_POST['testEmail'])){
    require_once("PHPMailer/PHPMailer/PHPMailer.php");
    require_once("PHPMailer/PHPMailer/SMTP.php");
    require_once("PHPMailer/PHPMailer/Exception.php");

    $con=connectDb();
    $data= fetchMailDetails($con);

    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->Host = $data['host'];
    $mail->Username= $data['user'];
    $mail->Password= $data['pass'];
    $mail->SMTPAuth= true;
    $mail->Port = $data['port']; // Or 587
    $mail->SMTPSecure = $data['smtpsecure']; # lowercase
    try {
        $mail->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
    } catch (Exception $e) {
    }
    $mail->isHTML(true);
    $mail->setFrom($data['user'], $data['user']);

    $mail->Subject = "Test Email";
    $mail->Body = "Test Email Message";
    $mail->addAddress('ajmal@jsoftsolution.com.my');



    $message="";
    if(!$mail->send()){
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>SUCCESS!</strong> FAILED MAIL SEND \n
			".$mail->ErrorInfo."</div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY MAIL SEND \n
			</div>\n";
    }

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/mailConfiguration.php");
}

?>