   <?php
		  
		  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		  if(!isset($_SESSION)) 
   { 
	   session_name($config['sessionName']);
   
	session_start(); 
	} 
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendorClient.php");	

	if(isset($_POST["addVendorClient"])){
		$con=connectDb();
		$cliendCompanyId=$_POST['cliendCompanyId'];
		$vendorId=$_POST['vendorId'];
		$createdDate=date('Y-m-d H:i:s');
		$createdBy=$_SESSION['userid'];
		$orgId=$_SESSION['orgId'];
		$saveSuccess=addVendorClient($con,$cliendCompanyId,$createdDate,$createdBy,$vendorId,$orgId);
		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY LINKED\n
			</div>\n";
			header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/addVendorClient.php");
		}else{
			$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> FAIlED TO LINK\n
			</div>\n";
			header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/addVendorClient.php");
		}
		

	}

	function dropDownVendorClientCompany(){
		$con=connectDb();
		$vendorId=$_SESSION['vendorId'];
		$status=1;
	
		$client=fetchVendorClientList($con,$vendorId,$status);
		$config=parse_ini_file(realpath("../jsheetconfig.ini"));

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");	

		echo "<div class='form-group row' >\n" ;
		echo "<label for='vendorClientId' class='col-sm-2 col-form-label col-form-label-lg'  >Client</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='vendorClientId' class='form-control' id='vendorClientId' required>";
		foreach ($client as $data){
			$clientCompanyDetails=fetchClientCompanyDetails($con,$data['clientCompanyId']);
			echo "<option value=".$data['id']." >".$clientCompanyDetails['name']."</option>";
		}

		echo	"</select>";
		echo "</div>";
		echo "</div>";
	}

?>