   <?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 if(!isset($_SESSION)) 
 { 
	 session_name($config['sessionName']);
	session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	
	function newTaskNotification($assignType_,$userId_){
		$messageList=null;
		
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");	
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");	
		$con=connectDb();
		$jobRefNo=null;
		$clientCompanyId=null;
		$status=null;
		$createdBy=null;
		$dateFrom=null;
		$dateTo=null;
		$assignType=$assignType_;
		$workerId=$userId_;
		$orgId=$_SESSION['orgId'];
		$complaintId=null;
		$jobId=null;
		$messageStatus="N";
		$taskList=fetchAssignedTask($con,$jobRefNo,$clientCompanyId,$status,$createdBy,$dateFrom,$dateTo,$assignType,$workerId,$orgId,$complaintId,$jobId,$messageStatus);
		
		if($taskList!=null){
			
			$messages=array();
			foreach($taskList as $task){
				$clientDetails=fetchClientCompanyDetails($con,$task['clientCompanyId']);
				$message=array("jobId"=>"35", "jobTrans"=>"37","Sender"=>$clientDetails['name'], "Datetime"=>$task['createdDateJt'],"Subject"=>$task['jobName']);
				$messageList[]=$message;
				
			}
		}
		
		return $messageList;

	}

	if(isset($_GET['messages'])){
		$newMessages=0;

		$userId_=$_GET['userId'];
		$assignType_=$_GET['userType'];
		$newMessages=newTaskNotification($assignType_,$userId_);
		
		echo json_encode($newMessages);
		
	}
?>