<?php 

$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
    session_name($config['sessionName']);
    session_start(); 
} 
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_email_template.php');
	
	$database= new cleanto_db();
	$conn=$database->connect();
	$database->conn=$conn;
	
	$email_template = new cleanto_email_template();
	$email_template->conn = $conn;
	
	if(isset($_POST['save_email_template'])){
		$email_template->id = $_POST['id'];
		$email_template->email_message = base64_encode($_POST['email_message']);
		$updated = $email_template->update_email_template();
	}
	else if(isset($_POST['save_email_template_status'])){
		$email_template->id = $_POST['id'];
		$email_template->email_template_status = $_POST['email_template_status'];
		$updated = $email_template->update_email_template_status();
	}
    else if(isset($_POST['default_email_content'])){
        $email_template->id = $_POST['id'];
        $getdata = $email_template->get_default_email_template();
        echo base64_decode($getdata);
    }
?>