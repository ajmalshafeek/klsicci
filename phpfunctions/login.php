<?php
   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
	session_name($config['sessionName']);
	session_start();
}

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");

	if(isset($_POST["login"])){

		$con=connectDb();

		$username=$_POST['username'];
		//echo $username."<br/>";
		$password=$_POST['password'];
		//echo $password."<br/>";
		$orgId=$config['orgId'];//$_POST['orgId'];
		//echo $orgId."<br/>";
		$status=1;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
		//if($_POST['type']==0){

		//}else if($_POST['type']==-1){


		//}else{

		//}
		$type=1;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
		$login=organizationLogin($con,$username,$password,$status,$orgId);

		if($login==false){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
			$st=2;
			$login=clientlogin($con,$username,$password,$st,$orgId);
			$type=2;
			file_put_contents('./login.log','login type: '.$_SESSION['type'].', user : '.$username.'');
		}
		else if($login==false){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
			$login=clientlogin($con,$username,$password,$status,$orgId);
			$type=-1;
		}
		else if($login==false){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendoruser.php");
			$login=login($con,$username,$password,$status,$orgId);
			$type=0;
		}
		if($login==true){
			if($type==0){
				$data=getLoginDetails($con,$username,$password,$status,$orgId);

				$_SESSION['identification']=$data['identification'];
				$_SESSION['vendorId']=$data['vendorId'];

				$_SESSION['type']='vendor';
			}else if($type==-1){

				$data=getClientLoginDetails($con,$username,$password,$status,$orgId);
				$_SESSION['identification']=$data['identification'];
				$_SESSION['companyId']=$data['companyId'];

				$_SESSION['type']='client';
			}else if($type==2){
                $_SESSION['message']="<div class='alert alert-danger'>You have entered wrong username or password. Please try again.</div>";
                header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/index.php");
				$st=2;
				$data=getClientLoginDetails($con,$username,$password,$st,$orgId);
				$_SESSION['identification']=$data['identification'];
				$_SESSION['companyId']=$data['companyId'];

				$_SESSION['type']='clientStore';

				file_put_contents('./login.log','2 login type: '.$_SESSION['type'].', user : '.$username.'',FILE_APPEND);
			}else{
				$data=getOrganizationLoginDetails($con,$username,$password,$status,$orgId);
				$_SESSION['type']='org';
			}

			$orgDetails=getOrganizationDetails($con,$data['orgId']);
			$_SESSION['orgLogo']=$orgDetails['logoPath'];
		    $_SESSION['orgType']=$orgDetails['type'];
		    $_SESSION['orgName']=$orgDetails['name'];

			$_SESSION['orgId']=$data['orgId'];

			$_SESSION['userid']=$data['id'];
			$_SESSION['staffId']=$data['staffId'];
			$_SESSION['fullName']=$data['fullName'];
			$_SESSION['name']=$data['name'];
			$_SESSION['username']=$data['username'];
			$_SESSION['email']=$data['email'];
			$_SESSION['status']=$data['status'];
			$_SESSION['role']=$data['role'];
			$_SESSION['email']=$data['email'];
			$session=fetchSession($con,$data['id'],$type,null,$orgId);

			if($session!=null){
				$success=updateSessionUser($con,$data['id'],$type,session_id(),date('Y-m-d H:i:s'),$orgId);
				ob_start();
				appExtraFeatures();
				ob_end_clean();

			}else{
				addSessionUser($con,$data['id'],$type,session_id(),date('Y-m-d H:i:s'),$orgId);
			}

			/* start of CLEANTO LOGIN */
		/*	if($_SESSION['userid']>0){
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_login_check.php");
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");
				$database= new cleanto_db();
				$objlogin = new cleanto_login_check();
				$objlogin->conn = $database->connect();
				$objlogin->checkadmin($_SESSION['username'],md5($password));
			}
			*/
			/* end of CLEANTO LOGIN */
			header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/home.php");
		}else{
			$_SESSION['message']="<div class='alert alert-danger'>You have entered wrong username or password. Please try again.</div>";
			header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/index.php");
		}

	}

	   function appExtraFeatures()
	   {
		  $conf= $GLOBALS['config'];
		   require_once($_SERVER['DOCUMENT_ROOT'] . $conf['appRoot'] . "/query/organizationType.php");
		   require_once($_SERVER['DOCUMENT_ROOT'] . $conf['appRoot'] . "/query/roles.php");
		   $con = connectDb();
		   $dataList = fetchOrgUse($con);
           $dataM=fetchAllModules($con);
           foreach ($dataM as $m) {
               if($m['id']==58){
                   $_SESSION['membership']=true;
               }
           }

		   foreach ($dataList as $data) {
			   $_SESSION['internalUse'] = $data['internal'];
			   $_SESSION['externalUse'] = $data['external'];
			   $_SESSION['staffCam'] = $data['staffcam'];
			   $_SESSION['supportCam'] = $data['supportcam'];
			   $_SESSION['memberReg'] = $data['membership'];
			   if(isset($data['staffloan']))
			   $_SESSION['staffloan'] = $data['staffloan'];
			   if(isset($data['location']))
			   $_SESSION['location'] = $data['location'];
			   $_SESSION['clientas']="";
			   if($data['clientas']==1){
				   $_SESSION['clientas']="Client";
			   }elseif($data['clientas']==2){
				   $_SESSION['clientas']="Client/Site";
			   }elseif($data['clientas']==3){
				   $_SESSION['clientas']="Site";
			   }elseif($data['clientas']==4){
                   $_SESSION['clientas']="Member";
               }
			   $_SESSION['clientpasscol'] = $data['clientpasscol'];
		   }
		   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/role.php");
		   $roleModulesList = loadModulesByRoleId($_SESSION['role']);
		   $module[]=false;
		   $_SESSION['serviceEngineer']=false;
		   $_SESSION['staffRole']=false;
		   $_SESSION['ManagerRole']=false;
		   foreach ($roleModulesList as $roleModuleData) {
			  $module[$roleModuleData['moduleId']]=true;
			   if ($roleModuleData['moduleId']==42) {
				   $_SESSION['serviceEngineer']=true;
			   }
			   if ($roleModuleData['moduleId']==43) {
				   $_SESSION['staffRole']=true;
			   }
			   if ($roleModuleData['moduleId']==44) {
				   $_SESSION['ManagerRole']=true;
			   }
		   }
	   }

if(isset($_POST["loginClientByForm"])){

	$con=connectDb();

	$username=$_POST['username'];
	//echo $username."<br/>";
	$password=$_POST['password'];
	//echo $password."<br/>";
	$orgId=$config['orgId'];//$_POST['orgId'];
	//echo $orgId."<br/>";
	$status=1;
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
	$type=1;
	$login=false;
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");
	if($login==false){

		$st=2;
		$login=clientlogin($con,$username,$password,$st,$orgId);
		$type=2;
	}else if($login==false){

		$login=clientlogin($con,$username,$password,$status,$orgId);
		$type=-1;
	}
	$data="";
	if($login==true){

	 if($type==-1){
			$data=getClientLoginDetails($con,$username,$password,$status,$orgId);
			$_SESSION['type']='client';
		}else if($type==2){
			$st=2;
			$data=getClientLoginDetails($con,$username,$password,$st,$orgId);
			$_SESSION['type']='clientStore';
		}
        ob_start();
        appExtraFeatures();
        ob_end_clean();
       if($_SESSION['memberReg']==1){
            $cid=$data['companyId'];
            $mem=checkMembershipModule($con,$cid);
           
           if(empty($mem)&&$st==2){
                $_SESSION['message']="<div class='alert alert-danger'>We are not found any subscription. Please subscribe membership on plan.</div>";
            //   header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/index.php");
               $_SESSION['memberRegPending']=false;
               $_SESSION['memberRegOver']=false;
           }
           else {
                $date=$mem['pay'];
                $plan=$mem['title'];
                $end=$mem['pay'];
                $l=strlen($plan);

               print_r($date);
               $date=date('Y-m-d', strtotime($date. ' + '.$end.' days'));
               $date=date_create($date);
               $date=date_format($date,"Ymd");
                $now=date("Ymd");
                $date= intval($now)-intval($date);
                $_SESSION['memberRegOver']=true;
                if($date>-1){
                    $_SESSION['memberRegOver']=false;
                }
                $_SESSION['memberRegPending']=true;
                if($l>3){
                    $_SESSION['memberRegPending']=false;
                }
            }
        }

		$_SESSION['identification']=$data['identification'];
		$_SESSION['companyId']=$data['companyId'];
		$data1=fetchClientCompanyDetails($con,$_SESSION['companyId']);
		$orgDetails=getOrganizationDetails($con,$data['orgId']);
		$_SESSION['orgLogo']=$orgDetails['logoPath'];
		$_SESSION['orgType']=$orgDetails['type'];
		$_SESSION['name']=$data1['name'];
		$_SESSION['orgId']=$data['orgId'];

		$_SESSION['userid']=$data['id'];
		$_SESSION['username']=$data['username'];
		$_SESSION['email']=$data['email'];
		$_SESSION['status']=$data['status'];
		$_SESSION['role']=$data['role'];
        $_SEESION['samePass']=false;
        $compres=strcasecmp($data['password'],'12345678');
        if($compres==0){
            $_SEESION['samePass']=true;
        }

		$session=fetchSession($con,$data['id'],$type,null,$orgId);

		if($session!=null){
			$success=updateSessionUser($con,$data['id'],$type,session_id(),date('Y-m-d H:i:s'),$orgId);
		}else{
			addSessionUser($con,$data['id'],$type,session_id(),date('Y-m-d H:i:s'),$orgId);
		}

		/* start of CLEANTO LOGIN */
		/*	if($_SESSION['userid']>0){
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_login_check.php");
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");
				$database= new cleanto_db();
				$objlogin = new cleanto_login_check();
				$objlogin->conn = $database->connect();
				$objlogin->checkadmin($_SESSION['username'],md5($password));
			}
			*/
		/* end of CLEANTO LOGIN */

        $_SESSION['oneTime']=1;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/home.php");
	}else{
		$_SESSION['message']="<div class='alert alert-danger'>You have entered wrong username or password. Please try again. Or required <a href=\"./registration-for-membership.php\">registration</a></div>";
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/index.php");
	}
}
?>