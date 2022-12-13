   <?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);

	session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");	

	function dropDownListVendorUserActive(){
		$con=connectDb();
		$vendorId=$_SESSION['vendorId'];
		$orgId=$_SESSION['orgId'];
		$status=1;
		$client=fetchVendorUserList($con,$vendorId,$status,$orgId);
		echo "<div class='form-group row' >\n" ;
		echo "<label for='vendorUserId' class='col-sm-2 col-form-label col-form-label-lg'  >Staff</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='vendorUserId' class='form-control' id='vendorUserId' required>";
		
		foreach ($client as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}

		echo	"</select>";
		echo "</div>";
		echo "</div>";
		
	}
?>