<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);

	session_start(); 
}
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/help.php");

	if(isset($_POST['updateHelp'])){
        define('FS_METHOD', 'direct');
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO UPDATE DETAILS \n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$website=$_POST['website'];
		$email=$_POST['email'];
		$call=$_POST['call'];
        $uploadFileName="";
        if(file_exists($_FILES['manual']['tmp_name']) || is_uploaded_file($_FILES['manual']['tmp_name'])) {
            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
            $name = $_FILES['manual']['name'];
            $temp_name = $_FILES['manual']['tmp_name'];
            $size = $_FILES['manual']['size'];
            if ($_FILES['manual']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
                    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/help.php");
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['manual']['tmp_name']);
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
            $path = "/resources/" . $orgId . "/";
            $jobDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
            if (!file_exists($jobDirectory)) {
                mkdir($jobDirectory, 0777, TRUE);
                copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $jobDirectory . '/index.php');
            }
            move_uploaded_file($temp_name, $jobDirectory . "/$uploadFileName");
        }
		$updateSuccess=updateHelp($con,$website,$email,$call,$uploadFileName);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY UPDATED \n
			</div>\n";
		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/help.php");
	}

function getHelpDetails(){
	$con=connectDb();
	   $data= fetchHelpDetails($con);
	    return $data;
}

?>