   <?php
   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
	session_name($config['sessionName']);
	session_start(); 
} 

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");



	if(isset($_POST["forgetPassword"])){
	
		$con=connectDb();
		
		//$type=$_POST['type'];
		$username=$_POST['username'];
		$type=1;
	//	if($type=='1')
	//	{
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
			$userDetails=getOrganizationUserDetailsByUsername($con,$username);
			if($userDetails!=null){
				echo "org";
				$type=1;
			}else{
				
			}
		//$emailAddr= $userDetails['email'];		
		//}
		if($userDetails==null){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");
			$userDetails=getVendorDetailsByUsername($con,$username);
			if($userDetails!=null){
				echo "VENDOR";
				$type=0;
			}else{
				
			}
		}
		if($userDetails==null){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
			$userDetails=getClientDetailsByUsername($con,$username);
			if($userDetails!=null){
				echo "CLIENT";
				$type=-1;
			}else{
				
			}
		}
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");

		$orgId=$config['orgId'];//$_POST['orgId'];
		$orgDetails=getOrganizationDetails($con,$orgId);

		$orgAddress=$orgDetails['address1'].",";
		if($orgDetails['address2']!=null){
			$orgAddress.="<br/>".$orgDetails['address2'].",";
		}
		$orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].","; 	
		$orgAddress.= "<br/>".$orgDetails['state'];

		$footer='<br/><img style="height:100px;max-width:200pt"  id="myorglogo" src="cid:logo_2u">
		<br/>
		'.$orgAddress;

		$from=$orgDetails['supportEmail'];
		$fromName=$orgDetails['name'];
		$to=$userDetails['username'];
		$subject="RESET PASSWORD";
		$body='Please click <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resetPassword.php?type='.$type.'&userId='.$userDetails['id'].'
		" target="_blank" > here </a> to reset your password'.$footer;
		 $orgLogo=$orgDetails['logoPath'];
		//echo $body;
		$mailMessage=mailTask($from,$fromName,$to,$subject,$body,$orgLogo);
	//	echo $mailMessage;
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/login.php?feedback=true");
	}

	
	if(isset($_POST["resetPassword"])){
		$con=connectDb();

		$type =$_POST["type"];
		$userId =$_POST["userId"];
		$password =$_POST["password"];

		$update=false;
		
		if($type=='1')
		{
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
			$update=updateNewPassword($con,$userId,$password);	
			if($update){
				$userDetails=getOrganizationUserDetails($con,$id);
			}
			$_SESSION['fp_admin']=true;
		}
		
		else if($type == '0')
		{
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");
			$update=updateVendorNewPassword($con,$userId,$password);
			if($update){
				$userDetails=getVendorDetails($con,$userId);
			}
			$_SESSION['fp_user']=false;
		}
		else if ($type == '-1')
		{
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
			$update=updateClientNewPassword($con,$userId,$password);
			if($update){
				$userDetails=getClientDetails($con,$userId);
			}
			$_SESSION['fp_user']=false;
		}
		
			/* start of CLEANTO LOGIN */
			if($update){
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_adminprofile.php");
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");
				$database= new cleanto_db();
				$objadmininfo = new cleanto_adminprofile();
				$objadmininfo->conn = $database->connect();
				$objadmininfo->email = $userDetails['username'];
				$objadmininfo->password = $userDetails['password'];
			//	echo $objadmininfo->email. " : ".$objadmininfo->password;
				$reset_new_pass = $objadmininfo->update_password2();
				if($reset_new_pass){
					echo "Success : ".$reset_new_pass;
				}else{
					echo "Failed : ".$reset_new_pass;
				}
				unset($_SESSION['fp_admin']);
				unset($_SESSION['fp_user']);
			}
			
			/* end of CLEANTO LOGIN */
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/login.php?feedback=true");

	}

	

?>