<?php

$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

   if(!isset($_SESSION)) 
   { 
	    session_name($config['sessionName']);
	    session_start();
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");	
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendor.php");	



	if(isset($_POST['addVendor'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/autoNum.php");
		$isInLimit=isInLimit($_SESSION['orgId'],2,"vendor");
        $con=connectDb();
        $vendorName=$_POST['vendorName'];
        $vendorUsername=$_POST['vendorUsername'];
        $tempNum=checkVenderName($con,$vendorName);
        $tempNum1=checkVenderUser($con,$vendorUsername);

        if($tempNum>0||$tempNum1>0){
            if($tempNum>0){
            $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> VENDOR ALREADY EXIST \n
			</div>\n";}
            elseif ($tempNum1>0){
                $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> VENDOR USERNAME ALREADY EXIST \n
			</div>\n";}
        }else{
		if($isInLimit){
			$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
				<strong>FAILED!</strong> YOU ALREADY REACH THE LIMIT TO ADD VENDOR \n
				</div>\n
				";
		}
		else{


			$con=connectDb();
			$vendorName=$_POST['vendorName'];
			$vendorRegNo=$_POST['vendorRegNo'];
			$vendorAddress=$_POST['vendorAddress'];
			$vendorContactNo=$_POST['vendorContactNo'];
			$vendorEmail=$_POST['vendorEmail'];

			$vendorPassword="admin";
			$createdDate=date('Y-m-d H:i:s');
			$createdBy=$_SESSION['userid'];
			$name="admin";
			$role=1;
			$orgId=$_SESSION['orgId'];
			$vendorId=addVendor($con,$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$createdBy,$createdDate,$orgId);
			if($vendorId>0){
				$saveSuccess=addVendorUser($con,$vendorId,$vendorName,$vendorUsername,$vendorPassword,$vendorEmail,$createdBy,$createdDate,$role,$orgId);

				$saveSuccess=addAutoNum($con,$vendorId,$orgId);
				$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> SUCCESSFULLY ADDED ".$vendorName."\n
				<br/><strong>Username : </strong>".$vendorUsername."\n
				<br/><strong>Password : </strong>".$vendorPassword."
				</div>\n
				";
				
			}
			
		}
        }
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/addVendor.php");
	}

	if(isset($_POST['removeVendor'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobList.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendorClient.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");


		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE VENDOR \n
			</div>\n
			";
		$saveSuccess=false;
		$con=connectDb();
		$staffId=null;
		$vendorId=$_POST['vendorIdToDelete'];
		$orgId=$_SESSION['orgId'];
		$status=null;
		$vendorUserList=fetchVendorUserList($con,$vendorId,$status,$orgId);
		$sessionId=null;
		$userType=0;
		foreach($vendorUserList as $user){
			$saveSuccess=deleteUserSession($con,$user['id'],$sessionId,$userType,$orgId);
		
		}
		$saveSuccess=deleteVendorUser($con,$staffId,$vendorId,$orgId);
		$companyId=null;
		$saveSuccess=deleteVendorClient($con,$companyId,$vendorId,$orgId);
		$jobId=null;
		$saveSuccess=deleteJobList($con,$jobId,$vendorId,$companyId,$orgId);
	
		$saveSuccess=deleteVendor($con,$vendorId,$orgId);

		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> VENDOR SUCCESSFULLY REMOVED \n
			</div>\n";
		
		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/viewVendor.php");

	}

    if(isset($_GET['vendorSingleDetails'])){
        $vendorId=$_GET['vendorSingleDetails'];
        $con = connectDb();
        $vendorDe=fetchVendorDetails($con,$vendorId);
        $result=json_encode($vendorDe);
        file_put_contents("./report.log",print_r($result,1),FILE_APPEND);
     return $result;
    }
	if(isset($_GET["vendorDetails"])){
		$vendorId=$_GET['vendorDetails'];
		
		$con=connectDb();
		$_SESSION["vendorDetailsTable"]="null";
		
		if($_GET['type']=='clientList'){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendorClient.php");	
			$table="<div class='table-responsive'>\n";
			$table.="<table  class='table'>\n";
			$table.="<thead class='thead-dark'>\n";
			$table.="<tr>\n";
			$table.=	"<th>\n";
			$table.=		"#\n";
			$table.=	"</th>\n";
			$table.=	"<th>\n";
			$table.=		"CLIENTS\n";
			$table.=	"</th>\n";
			$table.= "</tr>\n";
			$table.= "</thead >\n";
			$i=1;
			$dataList=fetchVendorClientList($con,$vendorId,1);
			foreach($dataList as $data){
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");	
				$clientData=fetchClientCompanyDetails($con,$data['clientCompanyId']);

				$table.= "<tr ";
				if($i%2==0)
					$table.= "style='background-color:#FFF5EB;'";
				else{
					$table.= "style='background-color:#F9F9F9;'";
				}$table.= ">";

				$table.=	"<td><b>";
				$table.=		$i++;
				$table.=	"</b></td>";
				$table.=	"<td><b>";
				$table.=		 $clientData['name'];
				$table.=	"</b></td>";

				$table.= "</tr>";
	
			}
			$table.= "</table>";
			$table.= "</div>";
		}else{
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/jobList.php");	
			$table="<div class='table-responsive'>\n";
			$table.="<table  class='table'>\n";
			$table.="<thead class='thead-dark'>\n";
			$table.="<tr>\n";
			$table.=	"<th>\n";
			$table.=		"#\n";
			$table.=	"</th>\n";
			$table.=	"<th>\n";
			$table.=		"JOBS\n";
			$table.=	"</th>\n";
			$table.= "</tr>\n";
			$table.= "</thead >\n";
			$i=1;
			$dataList=fetchVendorJobList($con,$vendorId,1);
			foreach($dataList as $data){
			
				$table.= "<tr ";
				if($i%2==0)
					$table.= "style='background-color:#FFF5EB;'";
				else{
					$table.= "style='background-color:#F9F9F9;'";
				}$table.= ">";

				$table.=	"<td><b>";
				$table.=		$i++;
				$table.=	"</b></td>";
				$table.=	"<td><b>";
				$table.=		 $data['jobName'];
				$table.=	"</b></td>";

				$table.= "</tr>";
	
			}
			$table.= "</table>";
			$table.= "</div>";
			
		}
		
		$_SESSION["vendorDetailsTable"]=$table;
	}



	function vendorListTable(){
		$con=connectDb();

		$table="<div class='table-responsive'>\n";
		$table.="<table  class='table' id='dataTable' width='100%' cellspacing='0'>\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"Company Name\n";
		$table.= 	"</th>\n";
		$table.= 	"<th>\n";
		$table.= 		"Registration No\n";
		$table.= 	"</th>\n";
	 /*
		$table.= 	"<th>\n";
		$table.= 		"Job List\n";
		$table.= 	"</th>\n";

		$table.=	"<th>\n";
		$table.=		"Client List\n";
		$table.= 	"</th>\n";
    */
		$table.= 	"<th>\n";
		$table.= 		"Username\n";
		$table.= 	"</th>\n";
		
		$table.= 	"<th>\n";
		$table.= 		"Password\n";
		$table.= 	"</th>\n";

		$table.=	"<th>\n";
		$table.=		"Action\n";
		$table.= 	"</th>\n";
		$table.= "</tr>\n";
		$table.= "</thead >\n";

		$i=1;
		$orgId=$_SESSION['orgId'];
		$dataList=fetchVendorList($con,1,$orgId);
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");	

		foreach($dataList as $data){
			$vendorAdminDetails=fetchVendorAdminDetails($con,$data['id'],$orgId);

			$table.= "<tr ";
			if($i%2==0)
				$table.= "style='background-color:#FFF5EB;'";
			else{
				$table.= "style='background-color:#F9F9F9;'";
			}$table.= ">";

			$table.=	"<td><b>";
			$table.=		$i++;
			$table.=	"</b></td>";
			$table.=	"<td><b>";
			$table.=		$data['name'];
			$table.=	"</b></td>";
			$table.=	"<td><b>";
			$table.=		$data['regNo'];
		/*
			$table.=	"<td><b>";
			$table.=	"<button name='viewJobList' onclick='viewVendorDetails(this.value,1)' value='".$data['id']."' data-toggle='modal' data-target='#vendorDetailsModal' class='btn btn-info' type='submit' >view</button>";
			$table.=	"</b></td>";

			$table.=	"<td><b>";
			$table.=	"<button name='viewClientList' onclick='viewVendorDetails(this.value,2)' value='".$data['id']."' data-toggle='modal' data-target='#vendorDetailsModal' class='btn btn-info' type='submit' >view</button>";
			$table.=	"</b></td>";
        */
			$table.=	"<td>";
			$table.=		$vendorAdminDetails['username'];
			$table.=	"</td>";

			$table.=	"<td>";
			$table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$vendorAdminDetails['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
			$table.=	"</td>";
			$table.="<td>";

			$table.="<div class='dropdown'>";
			$table.="<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";
		
			$table.="<button type='button' data-toggle='modal' data-target='#vendorDeleteModal' class='dropdown-item' onclick='vendorDelete(this)' value='$data[id]' style='cursor:pointer'>Action</button>";
			$table.="</div>
					</div>";
			$table.="</td>";

			$table.= "</tr>";	
		}
		$table.= "</table>";
		$table.= "</div>";

		echo $table;
	}

	function dropDownListVendorActive(){
		$con=connectDb();
        $orgId=$_SESSION['orgId'];
		$status=1;
		$vendor=fetchVendorList($con,$status,$orgId);
		
		echo "<div class='form-group' >" ;
		echo "<label for='vendorId'  >Company</label>";
		
		echo "<div >\n";
		echo "<select name='vendorId' class='form-control form-control-lg' id='vendorId' required>";
			foreach ($vendor as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}

		
		echo	"</select>";
		echo "</div>";
		echo "</div>";
		
	}
if(isset($_POST['editVendor'])){
    $_SESSION['vendorId']=$_POST['vendorIdToDelete'];
    $vendorId=$_POST['vendorIdToDelete'];
    $con = connectDb();
    $vendorDe=fetchVendorDetails($con,$vendorId);
        $_SESSION['regNo']=$vendorDe['regNo'];
        $_SESSION['name']=$vendorDe['name'];
        $_SESSION['address']=$vendorDe['address'];
        $_SESSION['contactNo']=$vendorDe['contactNo'];
        $_SESSION['emailAddress']=$vendorDe['emailAddress'];

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/editVendor.php");

}
if(isset($_POST['editVendorProcess'])) {
    $id=$_POST['vendorId'];
    $vendorRegNo=$_POST['regNo'];
    $vendorName=$_POST['vendorName'];
    $vendorAddress=$_POST['address'];
    $vendorContactNo=$_POST['contactNo'];
    $vendorEmail=$_POST['emailAddress'];

    $result=updateVendor($con,$vendorName,$vendorRegNo,$vendorAddress,$vendorContactNo,$vendorEmail,$id);
if($result){
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>VENDOR ID :".$id." IS SUCCESSFULLY UPDATED \n
    </div>\n";
}

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/viewVendor.php");
}
	
	function dropDownListVendorActive2(){
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$vendor=fetchVendorList($con,$status,$orgId);
		
		echo "<div class='form-group row' >" ;
		echo "<label for='vendorId' class='col-sm-2 col-form-label col-form-label-lg'  >Vendor</label>";
		
		echo "<div class='col-sm-10' >\n";
		echo "<select name='vendorId' class='form-control form-control-lg' id='vendorId' onchange='displayVendorJobTable(this.value)'>";

		foreach ($vendor as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}

		
		echo	"</select>";
		echo "</div>";
		echo "</div>";
		
	}

	function dropDownListVendorActive3(){
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$vendor=fetchVendorList($con,$status,$orgId);
		
		echo "<select name='vendorId' class='form-control' id='vendorId' onchange='displayVendorJobTable(this.value)'>";
		echo "<option selected disabled >--SELECT--</option>\n";

		foreach ($vendor as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}

		
		echo	"</select>";
	}

	function dropDownOptionListActiveVendor(){
		$optionList="";
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$vendor=fetchVendorList($con,$status,$orgId);
		
		foreach ($vendor as $data){
			$optionList.="<option value='".$data['id']."' >".$data['name']."</option>";
		}
		return $optionList;
	}
?>