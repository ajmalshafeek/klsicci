<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
/*
if(!isset($_SESSION))
{
	session_name($config['sessionName']);

	session_start();
}
*/
//	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/attendanceCron.php");

	/*
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/attendanceSetting.php");
*/
	date_default_timezone_set("Asia/Kuala_Lumpur");

	function getLateEmailSetting()
	{

		$con = connectDb();
		$data = getLateEmailSettingDetail($con);
		return $data;
	}

	function emailShoot($email,$name){

		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$con=connectDb();
		$orgId=2;
		$orgDetails=getOrganizationDetails($con,$orgId);
		$address=$orgDetails['supportEmail'];
		$data=getLateEmailSetting();
		$officerEmail=$data['emailOfficer'];
	//	require_once("../phpfunctions/mail.php");

		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n

					<strong>SUCCESS!</strong> JOB ASSIGNED SUCCESSFULLY\n

					</div>\n";

		$orgAddress=$orgDetails['address1'].",";

		if($orgDetails['address2']!=null){
			$orgAddress.="<br/>".$orgDetails['address2'].",";
		}
		$orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";
		$orgAddress.= "<br/>".$orgDetails['state'];

		$orgLogo=$orgDetails['logoPath'];
//path for the image
		$source_url = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgLogo.".png";

//separate the file name and the extention
		$source_url_parts = pathinfo($source_url);
		$filename = $source_url_parts['filename'];
		$extension = $source_url_parts['extension'];

//detect the width and the height of original image
		list($width, $height) = getimagesize($source_url);
		$width;
		$height;

//define any width that you want as the output. mine is 200px.
		$after_width = 150;
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
			//imagejpeg($imgResized, $filename . '-resized.'.$extension);
			imagepng($imgResized, "../".$filename . '-resized.'.$extension);
			//Finally frees any memory associated with image
			//**NOTE THAT THIS WONT DELETE THE IMAGE
			$newfilename=$filename . '-resized.'.$extension;
			imagedestroy($img);
			imagedestroy($imgResized);
		}

		//$footer='<br/><table width="150px"><tr><td><img style="max-height:80px;max-width:150px"  id="myorglogo" src="cid:logo_2u"></td></tr></table>
		$footer='<br/><img id="myorglogo" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'.$newfilename.'">

					<br/>

					'.$orgAddress;

		$from=$orgDetails['supportEmail'];

		$fromName=$orgDetails['name'];

		$to=$email; //$workerDetails['email'];
		$cc=explode(",",$officerEmail);
			$subject=$name.'! You are Not On Time Today';
		$body='Hi '.$name.',<br /><br />You are not attend office on time. <br /><br /> Kindly attend office on time.'
			."<br/><b>Thank You<br/><br/>".$footer;
		//debug
		//$detailslist="\nFrom:".$from." Name:".$from." worker-email:".$to." subject:".$subject;



		$orgLogo=$orgDetails['logoPath'];

		$mailMessage=mailLateEmail($from,$fromName,$to,$subject,$body,$orgLogo,$cc);
		return $mailMessage;
	}




	function mailLateEmail($from,$fromName,$to,$subject,$body,$orgLogo,$cc){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/PHPMailer.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/SMTP.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/external/phpmailer/src/Exception.php");
		$mailMessage="/n<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO SEND E-MAIL \n
		</div>\n";

		$email = new PHPMailer\PHPMailer\PHPMailer();
		$email->From      = $from;
		$email->FromName  = strtoupper($fromName);
		$email->Subject   = $subject;
		$email->Body      = $body;
		$email->AddAddress($to);

		foreach($cc as $mail)
		{
		$email->AddCC($mail);

		}
		$email->AddCC($cc);
		$email->IsHTML(true);
		$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgLogo.".png", 'logo_2u');

		if(!$email->send()) {
			file_put_contents("../attendaceEmail.log",'['.date('Y-m-d H:i:s').']Message could not be sent. Mailer Error: ' . $email->ErrorInfo.'\n',FILE_APPEND);
		} else {
			file_put_contents("../attendaceEmail.log",'['.date('Y-m-d H:i:s').']Message sent. Email ID: '.$to.'\n\r',FILE_APPEND);
			$mailMessage="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS !</strong> E-MAIL SENT SUCCESSFULLY\n
			</div>\n";
		}

		return $mailMessage;
	}

	function getListAttendanceStaff(){

		$con=connectDb();
		$data=getLateEmailSetting();
		$time1=" ".$data['startTime'].":00";
		$time1=date("Y-m-d").$time1;
		$time2=date("Y-m-d")." 00:00:01";
		$listStaffId =getListAttendanceStaffList($con,$time1,$time2);
		//$listStaffId=$time1." / ".$time2;
		$count=1;
		$query="SELECT `id`,`name`,`email` FROM organizationuser where `role` NOT IN(42) and id NOT IN(";
		foreach($listStaffId as $sid){
			foreach($sid as $item){
				if($count==1) {
					$query .= "" . $item;
					$count++;
				}else{
					$query.="," . $item;
				}
			}
		}
		if($count==1){
			$query.="''";
		}
		$query.=")";
		$emailList = getLateStaffEmailsId($con,$query);
		return $emailList;
	}


	$staffEmails=getListAttendanceStaff();
	$status="";
	foreach($staffEmails as $emails) {
		if (isset($emails['name'])) {
			$status=emailShoot($emails['email'], $emails['name']);
			}
	}
	echo $status;
?>