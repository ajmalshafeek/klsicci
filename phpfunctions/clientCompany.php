   <?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
	 session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");


	if(isset($_GET['quotClientId'])){
		$companyId=$_GET['quotClientId'];
		$clientCompanyDetails=fetchClientCompanyDetail($companyId);
		echo json_encode($clientCompanyDetails);
	}

	if(isset($_POST['addClientCompanyOrg'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
		$isInLimit=isInLimit($_SESSION['orgId'],2,"client");

		$con=connectDb();
        $clientName="";
        if(isset($_POST['clientName'])&&!empty($_POST['clientName']))
		$clientName=trim($_POST['clientName']);
		$tempNum=checkClientName($con,$clientName);

		if($tempNum>0){
			$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> CLIENT ALREADY EXIST \n
			</div>\n";
		}else{
		if($isInLimit){
			$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
				<strong>FAILED!</strong> YOU ALREADY REACH THE LIMIT TO ADD CLIENT \n
				</div>\n
				";
		}
		else{

			$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> FAILED TO ADD CLIENT\n
			</div>\n";


			$createdDate=date('Y-m-d H:i:s');
			$createdBy=$_SESSION['userid'];
			$orgId=$_SESSION['orgId'];
			$regNo="";
			$businessType=NULL;
			if (isset($_POST['business'])) {
				$businessType=$_POST['business'];
			}
			$incorpDate=NULL;
			if (isset($_POST['incorp'])) {
				$incorpDate=$_POST['incorp'];
			}
			$financialYear=NULL;
			if (isset($_POST['financialYear'])&&isset($_POST['financialMonth'])) {
                $financialMonth=$_POST['financialMonth'];
				$financialYear=$_POST['financialYear'].'-'.str_pad($_POST['financialMonth'], 2, '0', STR_PAD_LEFT); ;
			}
			$register=NULL;
			if (isset($_POST['register'])) {
				$register=$_POST['register'];
			}
			$fullName=NULL;
			if(isset($_POST['name'])){
				$fullName=$_POST['name'];
            }
                $emailAddress = NULL;
			if (isset($_POST['clientEmail'])) {
            $emailAddress=$_POST['clientEmail'];
            }

			$address1=$_POST['address1'];
			$address2=$_POST['address2'];
			$postalCode=$_POST['postalCode'];
			$city=$_POST['city'];
			$state=strtoupper($_POST['state']);
			$contactNo=$_POST['clientContactNo'];
			if(isset($_POST['clientFaxNo'])){
                $orgFaxNo=$_POST['clientFaxNo'];
			}else{
			    $orgFaxNo=NULL;
			}

		$clientCpa=NULL;
		$clientlat=NULL;
		$clientlong=NULL;
      $cStatus=NULL;
      if(isset($_POST['clientCpa'])){
      	$clientCpa=$_POST['clientCpa'];
      }
      if(isset($_POST['clientlat'])){
      	$clientlat=$_POST['clientlat'];
      }
      if(isset($_POST['clientlong'])){
      	$clientlong=$_POST['clientlong'];
      }
      //(START)INSTALLATION ADDRESS
      if (isset($_POST['instalAddress1'])) {
        $instalAddress1=$_POST['instalAddress1'];
      }else {
        $instalAddress1=NULL;
      }

      if (isset($_POST['instalAddress2'])) {
        $instalAddress2=$_POST['instalAddress2'];
      }else {
        $instalAddress2=NULL;
      }

      if (isset($_POST['instalPCode'])) {
        $instalPCode=$_POST['instalPCode'];
      }else {
        $instalPCode=NULL;
      }

      if (isset($_POST['instalCity'])) {
        $instalCity=$_POST['instalCity'];
      }else {
        $instalCity=NULL;
      }

      if (isset($_POST['instalState'])) {
        $instalState=strtoupper($_POST['instalState']);//strtoupper() change all letters in string into capital letter
      }else {
        $instalState=NULL;
      }
	if (isset($_POST['instalCountry'])) {
		$instalCountry=strtoupper($_POST['instalCountry']);//strtoupper() change all letters in string into Country letter
	}else {
		$instalCountry=NULL;
	}
	if (isset($_POST['country'])) {
		$country=strtoupper($_POST['country']);//strtoupper() change all letters in string into Country letter
	}else {
		$country=NULL;
	}

      //(END)INSTALLATION ADDRESS
			$compId=addClientCompany($con,$clientName,$emailAddress,$address1,$address2,$postalCode,
    	$city,$state,$contactNo,$orgFaxNo,$createdDate,$createdBy,$orgId,$instalAddress1,$instalAddress2,$instalPCode,
    	$instalCity,$instalState,$cStatus,$country,$instalCountry,$fullName,$businessType,$incorpDate,$financialYear,$register);
			if($compId>0){
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");


        if(isset($_SESSION['orgType']) && $_SESSION['orgType']==6){

				$teleId=addClientCompanyTelecom($con,$compId,$clientCpa,$clientlat,$clientlong);
			}

        //START PRODUCT
        if (isset($_POST['product'])&&$_POST['product'] != NULL) {
          $con = connectDb();
          $product = "product";
          $cStatus = "cStatus";
          $i = 0;
           while (isset($_POST[$product.$i])) {
              $productId = $_POST[$product.$i];
              $cStatusId = $_POST[$cStatus.$i];
              $feedbackProduct = addClientProduct($con,$compId,$productId,$cStatusId);
              if (!$feedbackProduct) {
                echo "an error occur<br>";
//                header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/fail.php");
                break;
              }
              $i++;
            }
        }
        //END PRODUCT
;

				$name="";
				$identification="";
				$userName=$_POST['clientEmail'];
				$password="12345678";
				$email=$_POST['clientEmail'];
				$status=1;
				if(isset($_POST['status'])){
					$status=$_POST['status'];
				}
				$role=9999;
				$companyId=$compId;
				$saveSuccess=addClientUser($con,$fullName,$name,$identification,$userName,$password,$email,$status,$role,$companyId,$orgId);
				if($saveSuccess){
				    if($_SESSION['memberReg']){
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/subscriber.php");
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/registration.php");
                    $tid=$_POST['tid'];
                    if(empty($tid)){
                        $price=null;
                        $payNow=0;
                        $transactionID=null;
                        $planList=array();
                        $title = "";
                        $description ="";
                        $duration=0;
                        $memebershipStatus=0;
                        $memebershipPaid=0;
                        $name = $name;
                        $date=date("Y-m-d h:i:s");
                        $payStatus= newClientTransaction($con,$date,$duration,$memebershipStatus,$memebershipPaid,$title,$description,$price,$transactionID,$companyId);
                    }else{updateClientId($con,$companyId,$tid);}
				    }
					if(isset($_SESSION['orgType']) && $_SESSION['orgType']==3){

						$ClientDetails=getClientDetailsByUsername($con,$userName);
						$folder=updateFMClient($con,$ClientDetails['id'],1,0,0,$clientName);
					if($folder>0){
						$path = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/files/".$clientName;
						if (!is_dir($path)) {
							mkdir($path, 0777, true);
						}
					}
				}
				/* start of CLEANTO ADD USER */
					require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_users.php");
					require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");

					$database= new cleanto_db();

					$user=new cleanto_users();
					$user->conn=$database->connect();
					$user->user_pwd=md5($password);
					$user->first_name=ucwords("");
					$user->last_name=ucwords("");
					$user->user_email=$email;
					$user->phone=$contactNo;

					$user->address=$address1." ".$address2;
					$user->zip=$postalCode;
					$user->city=$city;
					$user->state=$state;

					$user->notes="";
					$user->vc_status="-";
					$user->p_status="-";
					$user->status='E';
					$user->usertype=serialize(array('client'));
					$user->contact_status="";
					$add_user=$user->add_user();
				/* end of CLEANTO ADD USER */

					$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> SUCCESSFULLY ADDED ".$clientName."\n
					<br/><strong>Email : </strong>".$email."\n
					<br/><strong>Password : </strong>".$password."
					</div>\n";
					if($_SESSION['orgType']==8||$_SESSION['orgType']==3){
                            $rq = removeFromNewRequest($con, $_POST['id']);
					}
					if(checkIfEmailSentSetModule($con,$_SESSION['role'])){
                        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
						require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
						$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
						$from=$orgDetails['supportEmail'];
						$fromName=$orgDetails['name'];
						$subject="Your Account is created successful";
						$body="<h4 style='text-align: center'>THANK YOU FOR JOINING!</h4>";
						$body.="<p style='text-align: center'>Hi ".$clientName.",<br/><br/>Your account has been successfully created. We welcome you to the community of GK & Associates. It is our privilege to have you as our customer.</p>
                        <br />Below are your login credentials.    
						<br/><strong>Email : </strong>".$email."
						<br/><strong>Password : </strong>".$password."
                            <br />Click the <a href='https://".$_SERVER['HTTP_HOST'] . $config['appRoot']."'>link</a>  to login to your account! <br/><br/>";
                        if($_SESSION['memberReg']){
                            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/subscriber.php");
                            $myPlan=getMembershipDetails($con,$companyId);
                            $plan="";
                            if(strlen($myPlan['title'])>3) {
                                $plan .= "Subscription Date : " . $myPlan['pay'] . "<br/>";
                                $plan .= "Your Plan         : " . $myPlan['title'] . "<br/>";
                                $plan .= "Plan Detail       : " . $myPlan['description'] . "<br/>";
                                $plan .= "Plan Price        : RM " . $myPlan['price'] . "<br/>";
                                $plan .= "Plan Duration     : " . $myPlan['end'] . " Months<br/><br/><br/><br/>";
                            }
                            else{
                                $plan="Your free subscription is created now you can select any plan.<br/><br/><br/>";
                            }
                        }
                        $body.=$plan;
						mailsend($from,$fromName,$email,$subject,$body);
					}

				}
		}
	}

}
        header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/addClient.php");
	}


  if (isset($_POST['editClient'])) {
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $clientId=$_POST['clientIdToEdit'];
    $con = connectDb();
    $sql = "SELECT `clientcompany`.*,`clientuser`.`status` as app FROM `clientcompany`, `clientuser` WHERE `clientcompany`.`id` = '$clientId' AND `clientcompany`.`id` = `clientuser`.`companyId`";
    $result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if(isset($_SESSION['orgType']) && $_SESSION['orgType']==6) {
	fetchClientTelCompanyById($con,$clientId);
	}

    $orgId=$_SESSION['orgId'];
    $sessionId=null;
    $userType=$orgId;
    $_SESSION['idEdit'] = $clientId;
      $name="";
      if(isset($row['name'])&&!empty($row['name']))
          $name=trim($row['name']);
    $_SESSION['clientNameEdit'] = $name;
    $_SESSION['clientAddress1Edit'] = $row['address1'];
    $_SESSION['clientAddress2Edit'] = $row['address2'];
    $_SESSION['clientCityEdit'] = $row['city'];
    $_SESSION['clientPostalCodeEdit'] = $row['postalCode'];
    $_SESSION['clientStateEdit'] = $row['state'];
    $_SESSION['clientPhoneNumberEdit'] = $row['contactNo'];
    $_SESSION['clientFaxNoEdit'] = $row['faxNo'];
    $_SESSION['clientEmailAddressEdit'] = $row['emailAddress'];
    $_SESSION['instalAddress1Edit'] = $row['instalAddress1'];
    $_SESSION['instalAddress2Edit'] = $row['instalAddress2'];
    $_SESSION['instalCityEdit'] = $row['instalCity'];
    $_SESSION['instalPCodeEdit'] = $row['instalPCode'];
    $_SESSION['instalStateEdit'] = $row['instalState'];
    $_SESSION['cStatusEdit'] = $row['cStatus'];
	$_SESSION['country'] = $row['country'];
	$_SESSION['instalCountry'] = $row['instalCountry'];
	$_SESSION['nameEdit']=$row['customer'];
    $_SESSION['businessEdit']=$row['businessType'];
    $_SESSION['incorpEdit']=$row['incorpDate'];
    $_SESSION['finYearEdit']=$row['FinancialMonth'];
    $_SESSION['registerEdit']=$row['regNo'];
    $_SESSION['app']=$row['app'];

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/editClient.php");
  }
  if (isset($_POST['editClientCompanyProcess'])) {
    $clientId = $_SESSION['idEdit'];
      $name="";
      if(isset($_POST['clientName'])&&!empty($_POST['clientName']))
          $name=trim($_POST['clientName']);
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $postalCode = $_POST['postalCode'];
    $state = $_POST['state'];
    $contactNo = $_POST['clientContactNo'];
    $cStatusS = NULL; //This value is unused
    if(isset($_POST['clientContactNo'])){
        $contactNo = $_POST['clientContactNo'];
    }else{
        $contactNo = NULL;
    }
      $businessType=NULL;
      if (isset($_POST['business'])) {
          $businessType=$_POST['business'];
      }
      $incorpDate=NULL;
      if (isset($_POST['incorp'])) {
          $incorpDate=$_POST['incorp'];
      }
      $financialYear=NULL;
      if (isset($_POST['financialYear'])) {
          $financialYear=$_POST['financialYear'];
      }
      $register=NULL;
      if (isset($_POST['register'])) {
          $register=$_POST['register'];
      }
      $fullName=NULL;
      if(isset($_POST['name'])){
          $fullName=$_POST['name'];}
      $appUser=NULL;
      if(isset($_POST['status'])){
          $appUser=$_POST['status'];}

          if(isset($_POST['clientFaxNo'])){
        $faxNo = $_POST['clientFaxNo'];
    }else{
        $faxNo = NULL;
    }
    $emailAddress = $_POST['clientEmail'];
    	//(START)NEW
      	//(START)INSTALLATION ADDRESS
      if (isset($_POST['instalAddress1'])) {
        $instalAddress1=$_POST['instalAddress1'];
      }else {
        $instalAddress1=NULL;
      }

      if (isset($_POST['instalAddress2'])) {
        $instalAddress2=$_POST['instalAddress2'];
      }else {
        $instalAddress2=NULL;
      }

      if (isset($_POST['instalPCode'])) {
        $instalPCode=$_POST['instalPCode'];
      }else {
        $instalPCode=NULL;
      }

      if (isset($_POST['instalCity'])) {
        $instalCity=$_POST['instalCity'];
      }else {
        $instalCity=NULL;
      }

      if (isset($_POST['instalState'])) {
        $instalState=strtoupper($_POST['instalState']);//strtoupper() change all letters in string into capital letter
      }else {
        $instalState=NULL;
      }
	  if (isset($_POST['country'])) {
		  $country=strtoupper($_POST['country']);//strtoupper() change all letters in string into capital letter
	  }else {
		  $country=NULL;
	  }
	  if (isset($_POST['instalCountry'])) {
		  $instalCountry=strtoupper($_POST['instalCountry']);//strtoupper() change all letters in string into capital letter
	  }else {
		  $instalCountry=NULL;
	  }
      	//(END)INSTALLATION ADDRESS
    	//$cStatus = $_POST['cStatus'];

   		//START PRODUCT
    if (isset($_POST['product0'])){
        if ($_POST['product0'] != NULL ) {
          $con = connectDb();
          $product = "product";
          $cStatus = "cStatus";
          $i = 0;
           while (isset($_POST[$product.$i])) {
              echo $productId = $_POST[$product.$i];
              $cStatusId = $_POST[$cStatus.$i];
              $feedbackProduct = addClientProduct($con,$clientId,$productId,$cStatusId);
              if (!$feedbackProduct) {
                echo "an error occur<br>";
    			// header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/fail.php");
                break;
              }
              $i++;
            }
        }
    }


    //END PRODUCT
    //(END)NEW

    $sql = "UPDATE `clientcompany` SET `name`='$name',`address1`='$address1',`address2`='$address2',`city`='$city',`postalCode`='$postalCode',`state`='$state',`contactNo`='$contactNo',`faxNo`='$faxNo',`emailAddress`='$emailAddress',`instalAddress1`='$instalAddress1',`instalAddress2`='$instalAddress2', `instalCity`='$instalCity', `instalPCode`='$instalPCode',`instalState`='$instalState',`country`='$country', `instalCountry`='$instalCountry',`customer`='$fullName', `businessType`='$businessType', `incorpDate`='$incorpDate', `FinancialMonth`='$financialYear', `regNo`='$register' WHERE `id`='$clientId'";

    $con = connectDb();
    //$result = mysqli_query($con,$sql);
    if(mysqli_query($con,$sql)){
        if(isset($_SESSION['orgType'])&&$_SESSION['orgType']!=3 || isset($_SESSION['orgType'])&&$_SESSION['orgType']!=8){
            $appUser=NULL;
        }
                $sqlupdate = "UPDATE `clientuser` SET `status`='$appUser', `username`='$emailAddress', `email`='$emailAddress' WHERE `companyId`='$clientId'";
                $result = mysqli_query($con,$sqlupdate);

        if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6){
            $clientCpa = $_POST['clientCpa'];
            $clientlat =$_POST['clientlat'];
            $clientlong=$_POST['clientlong'];

            $query="SELECT * FROM `clienttelcompany` WHERE comid=?";
            $stmtsl=mysqli_prepare($con,$query);
            mysqli_stmt_bind_param($stmtsl,'i',$clientId);
            mysqli_stmt_execute($stmtsl);
            $result = mysqli_stmt_get_result($stmtsl);
            if (mysqli_num_rows($result) == 1) {
            $sqlupdate = "UPDATE `clienttelcompany` SET `cpa`='$clientCpa',`latitude`='$clientlat',`longitude`='$clientlong'   WHERE `comid`='$clientId'";
            $result = mysqli_query($con,$sqlupdate);}
            else{
                $query="INSERT INTO clientTelCompany (comid,cpa,latitude,longitude)
		VALUES (?,?,?,?)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,'iiss',$clientId,$clientCpa,$clientlat,$clientlong);
                if(mysqli_stmt_execute($stmt)){
                    $teleId=mysqli_insert_id($con);
                }
                mysqli_stmt_close($stmt);

      }}

        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> CLIENT INFORMATIONS SUCCESSFULLY UPDATED\n
    <br/><strong>NAME : </strong>".$name."\n
    <br/><strong>EMAIL : </strong>".$emailAddress."\n
    </div>\n"; //TROUBLESHOOT
    }else{
        $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO EDIT CLIENT'S INFORMATIONS\n
			</div>\n
			";
    }


    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/viewClient.php");
  }

	if(isset($_POST['removeClient'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendorClient.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");


		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE CLIENT \n
			</div>\n
			";
		$saveSuccess=false;
		$con=connectDb();
		$staffId=null;
		$companyId=$_POST['clientIdToEdit'];
		$orgId=$_SESSION['orgId'];

		$clientUserList=fetchClientUserList($con,$companyId,$orgId);
		$sessionId=null;
		$userType=-1;
		foreach($clientUserList as $user){
			$saveSuccess=deleteUserSession($con,$user['id'],$sessionId,$userType,$orgId);
		}

		$saveSuccess=deleteClientUser($con,$staffId,$companyId,$orgId);
		if($saveSuccess){
			$saveSuccess=deleteClientCompany($con,$companyId,$orgId);
			if($saveSuccess){
				$messageStatus=null;
				$status=2;
				$complaintId=null;
				$vendorId=null;
				$saveSuccess=deleteVendorClient($con,$companyId,$vendorId,$orgId);
				$saveSuccess=deleteClientComplaint($con,$complaintId,$messageStatus,$status,$companyId,$orgId);

			}
		}

		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> CLIENT SUCCESSFULLY REMOVED \n
			</div>\n";

		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/viewClient.php");

	}

	function getCpaList(){
		$con=connectDb();
		$list=fetchCPAList($con);
		return $list;
	}
	function clientListTable(){
		$con=connectDb();

		$table="<div class='table-responsive'>\n";
		$table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"CLIENT NAME\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"USERNAME\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"PASSWORD\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"ACTION\n";
		$table.= 	"</th>\n";

		$table.= "</tr>\n";
		$table.= "</thead >\n";

		$i=1;
		$orgId=$_SESSION['orgId'];
		$dataList=fetchClientCompanyListByCreatedOrg($con,1,$orgId);
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

		foreach($dataList as $data){
			$clientAdminDetails=fetchClientAdminDetails($con,$data['id'],$orgId);
			$table.= "<tr ";
			if($i%2==0)
				$table.= "style='background-color:#FFF5EB;'";
			else{
				$table.= "style='background-color:#F9F9F9;'";
			}$table.= ">";

			$table.=	"<td style='font-weight:bold'>";
			$table.=		$i++;
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=		$data['name'];
			$table.=	"</td>";

			$table.=	"<td>";
			$table.=		$clientAdminDetails['username'];
			$table.=	"</td>";

			$table.=	"<td>";
			$table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$clientAdminDetails['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
			$table.=	"</td>";

			$table.="<td>";
			$table.="<div class='dropdown'>";
			$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";

			$table.="<button type='button' data-toggle='modal' data-target='#clientDeleteModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>REMOVE</button>";
			$table.="</div>
					</div>";
			$table.="</td>";

			$table.= "</tr>";
		}
		$table.= "</table>";
		$table.= "</div>";

		echo $table;
	}

	function dropDownListOrganizationClientCompanyActiveUnlinked(){
		$con=connectDb();
		$status=1;
		$client=fetchOrganizationClientCompanyListUnlinked($con,$status);
		echo "<div class='form-group row' >\n" ;
		echo "<label for='cliendCompanyId' class='col-sm-2 col-form-label col-form-label-lg'  >CLIENT</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='cliendCompanyId' class='form-control' id='cliendCompanyId' required>";
		echo "<option selected disabled >--SELECT--</option>\n";
		foreach ($client as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}


		echo	"</select>";
		echo "</div>";
		echo "</div>";
	}
	function dropDownOptionListOrganizationClientCompanyActive(){
		$con=connectDb();
		$optionList="";
		$status=1;
		$orgId=$_SESSION['orgId'];
		$client=fetchOrganizationClientCompanyList($con,$status,$orgId);
		//echo "<div class='form-group row' >\n" ;
		//echo "<label for='cliendCompanyId' class='col-sm-2 col-form-label col-form-label-lg'  >CLIENT</label>";
		echo "<div class='col-sm-10' >\n";
		//echo "<select name='cliendCompanyId' class='form-control' id='cliendCompanyId' required>";
		//echo "<option selected disabled >--SELECT--</option>\n";
		foreach ($client as $data){
      $organization = "organization";
			$optionList.="<option value=".$data['id'].">".$data['name']."</option>";
		}


		//echo	"</select>";
		//echo "</div>";
		//echo "</div>";
		return $optionList;
	}

	function dropDownListOrganizationClientCompanyActive(){
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$client=fetchOrganizationClientCompanyList($con,$status,$orgId);
		echo "<div class='form-group row' >\n" ;
		echo "<label for='cliendCompanyId' class='col-sm-2 col-form-label col-form-label-lg'  >Client</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='cliendCompanyId' class='form-control' id='cliendCompanyId' required>";
		echo "<option selected disabled >--Select--</option>\n";
		foreach ($client as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}


		echo	"</select>";
		echo "</div>";
		echo "</div>";

	}

	function dropDownListOrganizationClientCompanyActive3(){
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$client=fetchOrganizationClientCompanyList($con,$status,$orgId);

		echo "<select onchange='clientId()' name='clientCompanyId' class='form-control' id='clientCompanyId'>";
		echo "<option selected >--Select--</option>\n";
		foreach ($client as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}
		echo	"</select>";

	}

	function dropDownListOrganizationClientCompanyName(){
		$con=connectDb();
		$status=1;
		$orgId=$_SESSION['orgId'];
		$client=fetchOrganizationClientCompanyList($con,$status,$orgId);

		echo "<select onchange='clientId()' name='clientCompanyName' class='form-control' id='clientCompanyName' required>";
		echo "<option selected disabled >--Select--</option>\n";
		foreach ($client as $data){
			echo "<option value=\"".trim($data['name'])."\" >".$data['name']."</option>";
		}

		echo	"</select>";

	}

	function dropDownListClientCompanyActive(){
		$con=connectDb();
		$vendorId=$_SESSION['vendorId'];
		$status=1;
		$client=fetchClientCompanyList($con,$vendorId,$status);
		echo "<div class='form-group row' >\n" ;
		echo "<label for='cliendCompanyId' class='col-sm-2 col-form-label col-form-label-lg'  >CLIENT</label>";
		echo "<div class='col-sm-10' >\n";
		echo "<select name='cliendCompanyId' class='form-control' id='cliendCompanyId' required>";

		foreach ($client as $data){
			echo "<option value=".$data['id']." >".$data['name']."</option>";
		}


		echo	"</select>";
		echo "</div>";
		echo "</div>";

	}

	function fetchClientCompanyDetail($companyId){
		$con=connectDb();
		$clientCompanyDetails=fetchClientCompanyDetails($con,$companyId);
		return $clientCompanyDetails;
	}

  if (isset($_GET['clientCheckboxTable'])) {
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/product.php");

    $con=connectDb();
    $companyId=$_GET['clientCheckboxTable'];
    $dataList = fetchClientProductListById($con,$companyId);
    $table = "<table><tr><th style='width:60%'>PRODUCT</th><th style='width:30%'>CONTRACT STATUS</th></tr>";
    $i = 0;
    $imax = 0;
    if ($dataList == NULL) {
      $table.= "<tr><td><i>EMPTY</td><td><input hidden class='form-control' type='checkbox' name='product0' value='' required>EMPTY</i></td></tr>";
    }else{
      foreach ($dataList as $data) {
          $product = fetchProductListById($con,$data['productId']);
          $cStatus = $data['cStatus'];
          switch ($cStatus) {
            case '0':
              $cStatusName = "TG";
              break;
            case '1':
              $cStatusName = "WTY";
              break;
            case '2':
              $cStatusName = "PERCALL";
              break;
            case '3':
              $cStatusName = "RENTAL";
              break;
            case '4':
              $cStatusName = "AD HOC";
              break;
          }
          $table.= "<tr><td>".$product['model']."[".strtoupper($product['brand'])."][".$product['serialNum']."]</td><td>".$cStatusName."</td><td><input class='form-control' type='checkbox' name='product".$i."' value='".$data['id']."'></td></tr>";
          $i++;
          $imax++;
      }
    }
    //$table.= "<input hidden class='form-control' type='checkbox' name='imax' value='".$imax."'>";
    $table.= "<input hidden type='text' name='imax' value='".$imax."'>";
    $table.= "</table>";
    if ($dataList == NULL) {
      $table.= "<div class='alert alert-danger' role='alert'>\n
			<strong>WARNING!</strong> ADD PRODUCT TO THE CLIENT TO PROCEED\n
			</div>";
    }
    /*$table = "<table><tr><th>PRODUCT</th><th>CONTRACT STATUS</th></tr>";
    foreach ($clientCompanyDetails as $dataClient) {
      $table.= "<tr>".$dataClient['name']."</tr>";
    }
    $table.= "</table>"; */
    //echo json_encode($clientCompanyDetails);
    echo $table;
  }

  if (isset($_GET['clientCheckboxTableEdit'])) {
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/product.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");
    $con=connectDb();
    $complaintId = $_GET['clientCheckboxTableEditComplaint'];
    $companyId=$_GET['clientCheckboxTableEdit'];
    $dataList = fetchClientProductListById($con,$companyId);
    $table = "<table style='width:100%;'><tr><th style='width:60%;'>PRODUCT</th><th style='width:30%;'>CONTRACT STATUS</th></tr>";
    $i = 0;
    $imax = 0;
    if ($dataList == NULL) {
      $table.= "<tr><td><i>EMPTY</td><td><input hidden class='form-control' type='checkbox' name='product0' value='' required>EMPTY</i></td></tr>";
    }else{
      foreach ($dataList as $data) {
          $dataListComplaint = fetchComplaintProductByComplaintId($con,$complaintId);
          $checkbox="unchecked";
          foreach ($dataListComplaint as $dataComplaint) {
            if ($dataComplaint['productId'] == $data['id']) {
              $checkbox="checked";
              break;
            }
          }
          $product = fetchProductListById($con,$data['productId']);
          $cStatus = $data['cStatus'];
          switch ($cStatus) {
            case '0':
              $cStatusName = "TG";
              break;
            case '1':
              $cStatusName = "WTY";
              break;
            case '2':
              $cStatusName = "PERCALL";
              break;
            case '3':
              $cStatusName = "RENTAL";
              break;
            case '4':
              $cStatusName = "AD HOC";
              break;
          }
          $table.= "<tr><td>[".strtoupper($product['brand'])."] ".$product['model']." [ S/N: ".$product['serialNum']."]</td><td>".$cStatusName."</td><td><input ".$checkbox." class='form-control' type='checkbox' name='product".$i."' value='".$data['id']."'><input hidden type='text' name='productRef".$i."' value='".$data['id']."'></td></tr>";
          $i++;
          $imax++;
      }
    }
    //$table.= "<input hidden class='form-control' type='checkbox' name='imax' value='".$imax."'>";
    $table.= "<input hidden type='text' name='imax' value='".$imax."'>";
    $table.= "</table>";
    if ($dataList == NULL) {
      $table.= "<div class='alert alert-danger' role='alert'>\n
      <strong>WARNING!</strong> ADD PRODUCT TO THE CLIENT TO PROCEED\n
      </div>";
    }
    /*$table = "<table><tr><th>PRODUCT</th><th>CONTRACT STATUS</th></tr>";
    foreach ($clientCompanyDetails as $dataClient) {
      $table.= "<tr>".$dataClient['name']."</tr>";
    }
    $table.= "</table>"; */
    //echo json_encode($clientCompanyDetails);
    echo $table;
  }

  function dropDownOptionListOrganizationClientCompanyActiveInvoice($organizationId){
    $con=connectDb();
    $optionList="";
    $status=1;
    $orgId=$organizationId;
    $client=fetchOrganizationClientCompanyListInvoice($con,$status,$orgId);
    //echo "<div class='form-group row' >\n" ;
    //echo "<label for='cliendCompanyId' class='col-sm-2 col-form-label col-form-label-lg'  >CLIENT</label>";
    if ($client[0] == "empty") {
      $optionList.="<option disabled>There is no invoice created for this client</option>";
    }
    else {
      echo "<div class='col-sm-10' >\n";
      //echo "<select name='cliendCompanyId' class='form-control' id='cliendCompanyId' required>";
      //echo "<option selected disabled >--SELECT--</option>\n";
      foreach ($client as $data){
        $optionList.="<option value=".$data.">".$data."</option>";
      }
    }



    //echo	"</select>";
    //echo "</div>";
    //echo "</div>";
    return $optionList;
  }

  function clientListTableEditable(){
		$con=connectDb();

		$table="<div class='table-responsive'>\n";
		$table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		$_SESSION['clientas']." Name\n";
		$table.= 	"</th>\n";
	  	$table.=	"<th>\n";
	  	$table.=	"Email";
	  	$table.= 	"</th>\n";
	  	$table.=	"<th>\n";
	  	$table.=	"Contact";
	  	$table.= 	"</th>\n";
	  	$table.=	"<th>\n";
	  	$table.=	"Address";
	  	$table.= 	"</th>\n";
if($_SESSION['clientpasscol']==1){
		$table.= 	"<th>\n";
		$table.= 		"Password\n";
		$table.= 	"</th>\n";}

		$table.= 	"<th>\n";
		$table.= 		"Action\n";
		$table.= 	"</th>\n";

		$table.= "</tr>\n";
		$table.= "</thead >\n";

		$i=1;
		$orgId=$_SESSION['orgId'];
		$dataList=fetchClientCompanyListByCreatedOrg($con,1,$orgId);
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
		$address="";

		foreach($dataList as $data){
			$clientAdminDetails=fetchClientAdminDetails($con,$data['id'],$orgId);
			if(!empty($data['address1'])){
				$address=$data['address1'];
			}
			if(!empty($data['address2'])) {
				$address .= ", " . $data['address2'];
			}
			if(!empty($data['city'])) {
				$address .=  ", " . $data['city'];
			}
			if(!empty($data['postalCode'])) {
				$address .=  " " . $data['postalCode'];
			}
			if(!empty($data['state'])) {
				$address .=  ", " . $data['state'];
			}
			if(!empty($data['country'])) {
				$address .= " " . $data['country'];
			}
			$table.= "<tr ";
			if($i%2==0)
				$table.= "style='background-color:#FFF5EB;'";
			else{
				$table.= "style='background-color:#F9F9F9;'";
			}
            "onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
			$table.= ">";

			$table.=	"<td style='font-weight:bold' >";
			$table.=		$i++;
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=		$data['name'];
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=	$data['emailAddress'];
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=	$data['contactNo'];
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=	$address;
			$table.=	"</td>";
			if($_SESSION['clientpasscol']==1){
			$table.=	"<td>";
			$table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$clientAdminDetails['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
			$table.=	"</td>";}

			$table.="<td>";
			$table.="<div class='dropdown'>";
			$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";

			$table.="<button type='button' data-toggle='modal' data-target='#clientEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
			$table.="</div>
					</div>";
			$table.="</td>";

			$table.= "</tr>";
		}
		$table.= "</table>";
		$table.= "</div>";

		echo $table;
	}
  if (isset($_POST['testing'])) {
    if ($_POST['product0'] != NULL ) {
      $product = "product";
      $cStatus = "cStatus";
      $i = 0;
       while (isset($_POST[$product.$i])) {
          $productId = $_POST[$product.$i];
          $cStatusId = $_POST[$cStatus.$i];
          echo $productId."and".$cStatusId."<br>";
          /*$feedbackProduct = addClientProduct($con,$comId,$productId,$cStatus);
          if (!$feedbackProduct) {
            echo "an error occur<br>";
            break;
          } */
          $i++;
        }
    }
  }

  if (isset($_GET['clientProductTableComplaint'])) {
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientComplaint.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");
    $con=connectDb();
    $complaintId = $_GET['clientProductTableComplaint'];
    $row = fetchJobByComplaintId($con,$complaintId);
    $jobId = $row['id'];
    echo productTableList($jobId);
  }


    function clientListDataTable(){
		$con=connectDb();

		$table="<div class='table-responsive'>\n";
		$table.="<table  class='table' id='dataTable2' width='100%' cellspacing='0' >\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"COMPANY NAME\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"PERSON IN CHARGE\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"ADDRESS\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"CONTACT NO\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"EMAIL\n";
		$table.= 	"</th>\n";

		$table.= "</tr>\n";
		$table.= "</thead>\n";
        $table.="<tbody>\n";
        $i=1;
        $orgId=$_SESSION['orgId'];
		$dataList=fetchClientCompanyListAll($con);
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");

		foreach($dataList as $data){
			$clientAdminDetails=fetchClientAdminDetails($con,$data['id'],$orgId);
		$table.="<tr>\n";
		$table.=	"<td>\n";
		$table.=		$i."\n";
		$table.=	"</td>\n";
		$table.=	"<td>\n";
		$table.=		$data['name']."\n";
		$table.= 	"</td>\n";

		$table.= 	"<td>\n";
		$table.= 		$clientAdminDetails['username']."\n";
		$table.= 	"</td>\n";

		$table.= 	"<td>\n";
		$table.= 		$data['address1'].",".$data['address2'].",".$data['postalCode'].",".$data['city'].",".$data['state']."\n";
		$table.= 	"</td>\n";

		$table.= 	"<td>\n";
		$table.= 		$data['contactNo']."\n";
		$table.= 	"</td>\n";

		$table.= 	"<td>\n";
		$table.= 		$data['emailAddress']."\n";
		$table.= 	"</td>\n";

		$table.= "</tr>\n";
        $i++;
		}
		$table.= "</tbody>\n";
		$table.= "</table>";
		$table.= "</div>";

		echo $table;
	}

   function newClientRequestListTableEditable($value){
	   $con=connectDb();

	   $table="<div class='table-responsive'>\n";
	   $table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
	   $table.="<thead class='thead-dark'>\n";
	   $table.="<tr>\n";
	   $table.=	"<th>\n";
	   $table.=		"#\n";
	   $table.=	"</th>\n";
	   $table.=	"<th>\n";
	   $table.=	" Name\n";
	   $table.= 	"</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Company";
	   $table.= 	"</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Email";
	   $table.= 	"</th>\n";
		   $table.= 	"<th>\n";
		   $table.= 		"Country\n";
		   $table.= 	"</th>\n";
	   $table.= 	"<th>\n";
	   $table.= 		"Action\n";
	   $table.= 	"</th>\n";

	   $table.= "</tr>\n";
	   $table.= "</thead >\n";

	   $i=1;
	   $orgId=$_SESSION['orgId'];
	   $dataList=fetchNewClientCompanyList($con,$value);
	   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

	   foreach($dataList as $data){
		   $table.= "<tr ";
		   if($i%2==0)
			   $table.= "style='background-color:#FFF5EB;'";
		   else{
			   $table.= "style='background-color:#F9F9F9;'";
		   }$table.= ">";

		   $table.=	"<td style='font-weight:bold' onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
		   $table.=		$i++;
		   $table.=	"</td>";
		   $table.=	"<td onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
		   $table.=		$data['name'];
		   $table.=	"</td>";
		   $table.=	"<td onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
		   $table.=	$data['company'];
		   $table.=	"</td>";
		   $table.=	"<td onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
		   $table.=	$data['email'];
		   $table.=	"</td>";
		   $table.=	"<td onclick='clientProduct(".$data['id'].")' data-toggle='modal' data-target='#clientProductModal' >";
		   $table.=	$data['country'];
		   $table.=	"</td>";
		   $table.="<td>";
		   $table.="<div class='dropdown'>";
		   $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";
		   $table.="<button type='button' data-toggle='modal' data-target='#clientEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
		   $table.="</div>
					</div>";
		   $table.="</td>";

		   $table.= "</tr>";
	   }
	   $table.= "</table>";
	   $table.= "</div>";

	   echo $table;
   }
   function getNewClientDetails($id){
  	$con=connectDb();
  	$data=fetchNewClientDetails($con,$id);
  	return $data;
   }

  function updatePotantialClient($id,$value){
      $con=connectDb();
      $res=addPotantialClient($con,$id,$value);

      if($res){
          header( "Location:  https://".$_SERVER['HTTP_HOST'].$GLOBALS['config']['appRoot']."/organization/client/newClientRequest.php");
      }
   }

?>