<?php
$config=parse_ini_file(__DIR__."./jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
include_once ("db.php");

/*if(isset($_POST['scheduleInvoice'])){
	$schedule_date=date('Y-m-d',strtotime($_POST['schedule_date']));
	$str="insert into invoice_scheduler (schedule_date)values('".$schedule_date."')";
	$top_result = mysqli_query($con,$str) or die(mysqli_error());
	$last_id = mysqli_insert_id($con);
	$client_count=count($_POST['clientCompanyId']);
	
	for($i=0;$i<$client_count;$i++){
		$client_id=$_POST['clientCompanyId'][$i];
		$invoice_id=$_POST['clientInvoiceId'][$i];
		$str1="insert into invoice_scheduler_sub (invoice_scheduler_id,client_id,invoice_id)values('".$last_id."','".$client_id."','".$invoice_id."')";
		$result = mysqli_query($con,$str1) or die(mysqli_error());
	}
	
	
if($top_result){
	header('Location:scheduler-list.php');
}

} else */
if(isset($_SESSION['recurring'])){
    $schedule_date=$_SESSION['reStartDate'];
    $client_id=$_SESSION['reCustomerId'];
    $invoice_id=$_SESSION['reInvoiceid'];
    $recurring=$_SESSION['recurring'];


    $str="insert into invoice_scheduler (schedule_date,recurring,next)values('".$schedule_date."','".$recurring."','".$schedule_date."')";
    $top_result = mysqli_query($con,$str) or die(mysqli_error(e));
    $last_id = mysqli_insert_id($con);


        $str1="insert into invoice_scheduler_sub (invoice_scheduler_id,client_id,invoice_id)values('".$last_id."','".$client_id."','".$invoice_id."')";
        $result = mysqli_query($con,$str1) or die(mysqli_error(e));
    unset($_SESSION['reInvoiceid']);
    unset($_SESSION['reCustomerId']);
    unset($_SESSION['reStartDate']);
    unset($_SESSION['recurring']);
    if($top_result){
        header('Location:scheduler-list.php');
    }

} else{
    $schedule_date=date('Y-m-d',strtotime($_POST['schedule_date']));
	$id=$_POST['id'];
	
	$str = "UPDATE `invoice_scheduler` SET  `schedule_date`='$schedule_date' WHERE `id`='$id'";
	$top_result1 = mysqli_query($con,$str);
	
	$str = "DELETE from invoice_scheduler_sub WHERE `invoice_scheduler_id`='$id'";
	$top_resultd = mysqli_query($con,$str);
	$client_count=count($_POST['clientCompanyId']);
	for($i=0;$i<$client_count;$i++){
		$client_id=$_POST['clientCompanyId'][$i];
		$invoice_id=$_POST['clientInvoiceId'][$i];
		$str1="insert into invoice_scheduler_sub (invoice_scheduler_id,client_id,invoice_id)values('".$id."','".$client_id."','".$invoice_id."')";
		$result = mysqli_query($con,$str1) or die(mysqli_error(e));
	}
	
if($top_result1){
	header('Location:scheduler-list.php');
	
}
	
	
}

 ?>