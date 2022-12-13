   <?php
   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);
	session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobList.php");	
	


	if(isset($_POST["addJobList"])){
		$con=connectDb();
		$vendorId=$_POST['vendorId'];
		$jobName=$_POST['jobName'];
		$createdDate=date('Y-m-d H:i:s');
		$createdBy=$_SESSION['userid'];

		$orgId=$_SESSION['orgId'];
		$saveSuccess=addJobListByVendor($con,$jobName,$vendorId,$createdDate,$createdBy,$orgId);
		$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
		<strong>SUCCESS!</strong> SUCCESSFULLY ADDED\n
			</div>\n";
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/addJobList.php");

		
	}

	

	function dropDownVendorJobList(){
		$con=connectDb();
		$vendorId=$_SESSION['vendorId'];
		$status=1;
		
		$jobList=fetchVendorJobList($con,$vendorId,$status);
		
		echo "<div class='form-group row' >\n" ;
		echo "<label for='vendorJobListId' class='col-sm-2 col-form-label col-form-label-lg'  >JOB</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='vendorJobListId' class='form-control' id='vendorJobListId' required>";
		foreach ($jobList as $data){
			echo "<option value=".$data['id']." >".$data['jobName']."</option>";
		}
		echo	"</select>";
		echo "</div>";
		echo "</div>";
	}
	
?>