   <?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);
	session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/orgJobList.php");	
	


/*

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
		header("Location:  https://".$_SERVER['HTTP_HOST']."/jobsheet/organization/addJobList.php");

		
	}
*/
	function dropDownOrgJobList(){
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$status=1;
		
		$jobList=fetchOrgJobList($con,$orgId,$status);
		
	
		$input="";
		echo "<select name='orgJobListId' class='form-control' id='orgJobListId' onchange='toggleJobField(this.value)' >";
		foreach ($jobList as $data){
			echo "<option value=".$data['id']." >".$data['jobName']."</option>";
		}
		echo "<option value='0' >OTHERS</option>";
		echo	"</select>";

	}

?>