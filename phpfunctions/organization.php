<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);

	session_start(); 
} 
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");
		
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationType.php");
	
	
	if(isset($_POST['updateOrganization'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO UPDATE ORGANIZATION DETAILS \n
		</div>\n";
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$name=$_POST['orgName'];
		$address1=$_POST['address1'];
		$address2=$_POST['address2'];
		$postalCode=$_POST['postalCode'];
		$city=$_POST['city'];
		$state=strtoupper($_POST['state']);
		$contact=$_POST['orgContactNo'];
		$orgFaxNo=$_POST['orgFaxNo'];
		if(isset($_POST['supportEmail'])){
		    $supportEmail=$_POST['supportEmail'];
		}else{
		    $supportEmail=NULL;
		}
		$salesEmail=NULL;
		if(isset($_POST['salesEmail'])){
			$salesEmail=$_POST['salesEmail'];
		}

		$base64Image=$_FILES['orgLogo']['tmp_name'];

	
		$logo=time();
		$jobDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/myOrg/";
		if (!file_exists($jobDirectory)) {
			mkdir($jobDirectory, 0777, true);
			copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $jobDirectory.'/index.php');
		}
		
		move_uploaded_file($base64Image,$jobDirectory."/$logo.png");
		

		
		$logo=$orgId."/myOrg/$logo";
		if($base64Image==null){
			$data=getOrganizationDetails($con,$orgId);
			$logo=$data['logoPath'];
		}
		$updateSuccess=updateOrganization($con,$name,$address1,$address2,$postalCode,$city,$state,
		$contact,$orgFaxNo,$logo,$orgId,$supportEmail,$salesEmail);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY UPDATED \n
			</div>\n";
		}
		$_SESSION['orgLogo']=$logo;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/myOrganization/updateMyOrg.php");
	}

	function loadOrganizationDetail(){
		$con=connectDb();
		$data=getOrganizationDetails($con,$_SESSION['orgId']);
		
		$_SESSION['orgName']=$data['name'];
		$_SESSION['orgAddress']=$data['address1'];
		$_SESSION['orgContactNo']=$data['contact'];
		$_SESSION['orgType']=$data['type'];
	}

	function fetchOrganizationDetails($orgId){
		$con=connectDb();
		$orgDetails=getOrganizationDetails($con,$orgId);
	/*	$orgName=$orgDetails['name'];
		$orgAddress1=$orgDetails['address1'];
		$orgAddress12=$orgDetails['address2'];
		$orgCity=$orgDetails['city'];
		$orgPostalCode=$orgDetails['postalCoce'];
		$orgState=$orgDetails['state'];
		$orgPhoneNo=$orgDetails['contact'];
		$orgFaxNo=$orgDetails['faxNo'];
		$orgSalesEmail=$orgDetails['salesEmail'];
		$orgSupportEmail=$orgDetails['supportEmail'];
		$logo=$orgDetails['logoPath'];
		$orgMaxVendor=$orgDetails['maxVendor'];
		$orgMaxStaff=$orgDetails['maxStaff'];
		$orgMaxClient=$orgDetails['maxClient'];
*/

		return $orgDetails;

	}

	function isInLimit($orgId_,$role_,$type_){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		

		$con=connectDb();
		$role=$role_;
		$orgId=$orgId_;
		$type=$type_;

		$isInLimit=false;
		$currentNo=0;
		$var="";
		if($type==="staff"){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
			$currentNo=noOfOrgStaff($con,$role,$orgId);

			$var='maxStaff';
		}else if($type==="vendor"){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");
			$currentNo=noOfVendor($con,$orgId);

			$var='maxVendor';
		}else if($type==="client"){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
			$currentNo=noOfClient($con,$orgId);

			$var='maxClient';
		}
		$limit=limitOfOrg($con,$orgId);
		
		$limit=$limit["$var"];
		
		if($limit!==null && $currentNo!==null){
			if($currentNo>=$limit){
				$isInLimit=true;
			}
		}
		
		return $isInLimit;
	}

if (isset($_POST['changeBanner'])) {
  $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/2/myOrg/banner/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//(START)NEW FILE NAME
//(END)NEW FILE NAME
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
/* Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
} */
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "png") {
    echo "Sorry, only PNG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $temp = explode(".", $_FILES["fileToUpload"]["name"]);
    $newfilename = rand(1000000,10000000) . '.' . end($temp);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../resources/2/myOrg/banner/" . $newfilename)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        
        $con=connectDb();
        $orgId = $_SESSION['orgId'];
        echo $orgId;
        $banner = $newfilename;
        $bannerFeedback = updateBannerName($con, $orgId, $banner);
        if($bannerFeedback){echo "success";}
        
        if($bannerFeedback){
         $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY UPDATE BANNER \n
			</div>\n";
        
         header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/myOrganization/updateMyOrg.php");
        }else{
            $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    		<strong>FAILED!</strong> FAILED TO UPDATE BANNER \n
    		</div>\n";
        
            header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/myOrganization/updateMyOrg.php");
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
        
        $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO UPDATE BANNER \n
		</div>\n";
        
        header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/myOrganization/updateMyOrg.php"); 
    } 
}
}

function bannerName($orgId){
    $con=connectDb();
    $orgId = $_SESSION['orgId'];
    $row = getOrganizationDetails($con,$orgId);
    $bannerName = $row['banner'];
    return $bannerName;
}
	function fetchOrgUseApplication(){

		$con=connectDb();
		$datalist=fetchOrgUse($con);
		return $datalist;
	}

?>