<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION))
{
	session_name($config['sessionName']);
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");

	if(isset($_POST['addStaff'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

		$isInLimit=isInLimit($_SESSION['orgId'],2,"staff");
		if($isInLimit){
			$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
				<strong>FAILED!</strong> YOU ALREADY REACH THE LIMIT TO ADD STAFF \n
				</div>\n
				";
		}
		else{

			$con=connectDb();
			$staffName=explode(" ",$_POST['staffName']);
			$fullName=$_POST['staffName'];
			$shortName=$staffName[0];
      $name = $shortName;
			$staffId=$_POST['staffId'];
			$email=$_POST['staffEmail'];
			$role=4;
			if(isset($_POST['role'])){
			$role=$_POST['role'];}

			$staffDesignation="";
			$department="";
			if(isset($_POST['staffDesignation'])){
			$staffDesignation = $_POST['staffDesignation'];}
			if(isset($_POST['department'])){
			$department = $_POST['department'];}

			$orgId=$_SESSION['orgId'];
			$userName=$staffId;
      $password="12345";
			$status=1;

          if (isset($_POST['staffPhone'])) {
              $phone=$_POST['staffPhone'];
          }
		  else{
			$phone="";
		  }
          if (isset($_POST['address1'])) {
            $address1 = $_POST['address1'];
          }else {
            $address1 = null;
          }

          if (isset($_POST['address2'])) {
            $address2 = $_POST['address2'];
          }else {
            $address2 = null;
          }

          if (isset($_POST['city'])) {
            $city = $_POST['city'];
          }else {
            $city = null;
          }

          if (isset($_POST['postalCode'])) {
            $postalCode = $_POST['postalCode'];
          }else {
            $postalCode = null;
          }

          if (isset($_POST['state'])) {
            $state = $_POST['state'];
          }else {
            $state = null;
          }

          if (isset($_POST['contact'])) {
            $contact = $_POST['contact'];
          }else {
            $contact = null;
          }

          if (isset($_POST['married'])) {
            $married = $_POST['married'];
          }else {
            $married = null;
          }

          if (isset($_POST['education'])) {
            $education = $_POST['education'];
          }else {
            $education = null;
          }

          if (isset($_POST['license'])) {
            $license = $_POST['license'];
          }else {
            $license = null;
		  }
			if (isset($_POST['ICNum'])) {
				$staffIC= $_POST['ICNum'];

			}else {
				$staffIC = null;
			}
			if (isset($_POST['staffPassport'])) {
				$staffPassport = $_POST['staffPassport'];
			}else {
				$staffPassport = null;
			}
			if (isset($_POST['staffPerkeso'])) {
				$staffPerkeso = $_POST['staffPerkeso'];
			}else {
				$staffPerkeso = null;
			}
			if (isset($_POST['staffKWSP'])) {
				$staffKWSP = $_POST['staffKWSP'];
			}else {
				$staffKWSP = null;
			}
			if (isset($_POST['staffGender'])) {
				$staffGender= $_POST['staffGender'];
			}else {
				$staffGender = null;
			}
			if (isset($_POST['staffStart'])) {
				$staffStart = $_POST['staffStart'];
			}else {
				$staffStart = null;
			}

			if(checkStaff($con,$staffId)){
          $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
				<strong>FAILED!</strong> STAFF ALREADY EXISTS FAILED TO ADD\n
				</div>\n";}
            else{
                
			if(addStaff($con,$staffId,$fullName,$name,$email,$phone,$userName,$password,$status,$role,$orgId,$staffDesignation,$department,$address1,$address2,$city,$postalCode,$state,$contact,$married,$education,$license,$staffIC,$staffPassport,$staffPerkeso,$staffKWSP,$staffGender,$staffStart)){
				$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> STAFF SUCCESSFULLY ADDED\n
				<br/><strong>NAME : </strong>".$fullName."\n
				<br/><strong>EMAIL : </strong>".$email."\n
				<br/><strong>PASSWORD : </strong>".$password."\n
				</div>\n";
				//	<br/><strong>USERNAME : </strong>".$userName."\n
				// Start of CLEANTO ADD STAFF
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_adminprofile.php");
				require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");



				$database= new cleanto_db();
				$objadmin = new cleanto_adminprofile();

				$objadmin->conn=$database->connect();
				$objadmin->email = $email;
				$objadmin->fullname = ucwords($fullName);
				$objadmin->pass = $password;
				$objadmin->role = "staff";
				$objadmin->add_staff();


				 // End of CLEANTO ADD STAFF

			}else{
				$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>FAILED!</strong> STAFF FAILED TO ADD\n
				</div>\n";

			}}
		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/addStaff.php");
	}

  if(isset($_POST['editStaff'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $staffId=$_POST['staffIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `organizationuser` WHERE `id` = '$staffId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);

		$orgId=$_SESSION['orgId'];
		$sessionId=null;
		$userType=$orgId;

    $_SESSION['idEdit'] = $staffId;
    $_SESSION['staffIdEdit'] = $row['staffId'];
    $_SESSION['fullNameEdit'] = $row['fullName'];
    $_SESSION['nameEdit'] = $row['name'];
    $_SESSION['emailEdit'] = $row['email'];
    $_SESSION['usernameEdit'] = $row['username'];
    $_SESSION['passwordEdit'] = $row['password'];
	$_SESSION['roleEdit'] = $row['role'];
	$_SESSION['phone'] = $row['phone'];
	$_SESSION['staffDesignation']=$row['staffDesignation'];
	$_SESSION['ic']=$row['ic'];
	$_SESSION['passport']=$row['passport'];
	$_SESSION['perkeso']=$row['perkeso'];
	$_SESSION['kwsp']=$row['kwsp'];

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/editStaff.php");

	}

	if(isset($_POST['removeStaff'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");


		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE STAFF \n
			</div>\n
			";

		$con=connectDb();
    $staffId=$_POST['staffIdToEdit'];
		$orgId=$_SESSION['orgId'];
		$sessionId=null;
		$saveSuccess=deleteUserSession($con,$staffId,$sessionId,$userType,$orgId);
		$saveSuccess=deleteOrganizationUser($con,$staffId,$orgId);
		$sessionId=null;
		$userType=$orgId;



		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> STAFF SUCCESSFULLY REMOVED \n
			</div>\n";

		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/viewStaff.php");

	}

  if(isset($_POST['editStaffProcess'])){
    $id = $_SESSION['idEdit'];
    $staffId = $_POST['staffId'];
    $fullName=$_POST['fullName'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $username=$_POST['username'];
	$password=$_POST['password'];
	$phone = $_POST['phone'];
	if(isset($_SESSION['staffloan'])&&$_SESSION['staffloan']==1){
	$loanAmount=$_POST['loanAmount'];
	$emi=$_POST['emi'];
	$pending=$_POST['pendingLoanAmount'];
	}
	  if (isset($_POST['ICNum'])) {
		  $ic= $_POST['ICNum'];

	  }else {
		  $ic = null;
	  }
	  if (isset($_POST['staffPassport'])) {
		  $passport = $_POST['staffPassport'];
	  }else {
		  $passport = null;
	  }
	  if (isset($_POST['staffPerkeso'])) {
		  $perkeso = $_POST['staffPerkeso'];
	  }else {
		  $perkeso = null;
	  }
	  if (isset($_POST['staffKWSP'])) {
		  $kwsp = $_POST['staffKWSP'];
	  }else {
		  $skwsp = null;
	  }

    $isstaff=0;
      if(isset($_POST['isstaff'])){
          $isstaff=$_POST['isstaff'];
      }
	$role=$_POST['role'];
	  $staffDesignation=$_POST['staffDesignation'];
    $sql = "UPDATE `organizationuser`
            SET `staffId`='$staffId',`fullName`='$fullName',`name`='$name',`email`='$email',`phone`='$phone',`username`='$username',`password`='$password', `role`='$role', `isStaff`='$isstaff',`staffDesignation`='$staffDesignation',`ic`='$ic',`passport`='$passport',`perkeso`='$perkeso',`kwsp`='$kwsp' WHERE `id`='$id'";
    $result = mysqli_query(connectDb(),$sql);
    if($result){
        if(isset($_SESSION['staffloan'])&&$_SESSION['staffloan']==1){
        $res=checkifLoanExist(connectDb(),$id);
        if($res){
            if (isset($_SESSION['staffloan']) && $_SESSION['staffloan'] == 1) {
                $loadAmount = $_SESSION['loanAmount'];
                $emi = $_SESSION['emi'];
                $date4 = $_SESSION['start_date'];
                $date2=date("Y-m-d");
                $ts1 = strtotime($date4);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                if($diff>0){
                    $sql0 = "UPDATE `staffloan`
                    SET `pending`=(`pending`-'$emi') WHERE `sid`='$id' ";
                    $res1 = mysqli_query(connectDb(),$sql0);
                }
            }

        } else {
            $date=date("Y-m-d");
            $sql1 = "INSERT INTO `staffloan`( `sid`, `emi`, `status`, `amount`, `pending`, `interest`, `start_date`) 
            VALUES ('$id','$emi',1,'$loanAmount','$loanAmount','0','$date') ";
            $res1 = mysqli_query(connectDb(),$sql1);
        }
    }
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/viewStaff.php");
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> STAFF INFORMATIONS SUCCESSFULLY UPDATED\n
    <br/><strong>NAME : </strong>".$fullName."\n
    <br/><strong>EMAIL : </strong>".$email."\n
    <br/><strong>PASSWORD : </strong>".$password."\n
    </div>\n";
  }

	function organizationStaffListTable(){
		$con=connectDb();

		$table="<div class='table-responsive table-stripped table-bordered'>\n";
		$table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"NAME\n";
		$table.= 	"</th>\n";
		$table.= 	"<th>\n";
		$table.= 		"STAFF ID\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"ROLE\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"USERNAME\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"PASSWORD\n";
		$table.= 	"</th>\n"
		;
		$table.= 	"<th>\n";
		$table.= 		"ACTION\n";
		$table.= 	"</th>\n";

		$table.= "</tr>\n";
		$table.= "</thead >\n";

		$i=1;
		$orgId=$_SESSION['orgId'];
		$status=1;
		$role=2;
		$dataList=fetchOrganizationStaffList($con,$orgId,$role,$status);

		$table.="<tbody>";
		foreach($dataList as $data){
			if($data['fullName']=="superadmin" && $_SESSION['fullName']!="superadmin"){
				// do nothing
			}else{
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
				$table.=		$data['staffId'];
				$table.=	"</td>";

				$table.=	"<td>";
				if($data['role']===1)
				{
					$table.="admin";
				}else{
					$table.="user";
				}
				$table.=	"</td>";

				$table.=	"<td>";
				$table.=		$data['username'];
				$table.=	"</td>";

				$table.=	"<td>";
				$table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$data['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
				$table.=	"</td>";


				$table.="<td>";
					if($data['role']!=1){
						$table.="<div class='dropdown'>";
						$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

						$table.="<button type='button' data-toggle='modal' data-target='#staffDeleteModal' class='dropdown-item' onclick='staffDelete(this)' value='$data[id]' style='cursor:pointer'>REMOVE</button>";


						$table.="	</div>
							</div>";
					}else{
						$table.="<span style='color:red'>RESTRICTED</span>";
					}
				$table.="</td>";

				$table.= "</tr>";
			}
		}

		$table.="</tbody>";
		$table.= "</table>";
		$table.= "</div>";

		echo $table;

	}

	function dropDownListOrgStaffListActive(){
		$con=connectDb();
		$status=1;
		$role=42;
		$orgId=$_SESSION['orgId'];
		$staff=fetchOrganizationStaffList($con,$orgId,$role,$status);

		echo "<select onchange='showStaffInfo()' name='orgStaffId' class='form-control' id='orgStaffId' >";
		echo "<option value='null' selected disabled >--Select--</option>\n";

		foreach ($staff as $data){
			echo "<option value=".$data['id']." >".$data['name']." [ ".$data['staffId']." ]"."</option>";
		}
		echo	"</select>";
	}

	function dropDownListOrgStaffListActiveNames(){
		$con=connectDb();
		$status=1;
		$role=42;
		$orgId=$_SESSION['orgId'];
		$staff=get($con,$orgId,$role,$status);

		foreach ($staff as $data){
			echo "<option value=".$data['fullName']." data-id=".$data['id']." >".$data['fullName']."</option>";
		}
	}

	function dropDownListOrgStaffListEANames(){
		$con=connectDb();
		$status=1;
		$role=42;
		$orgId=$_SESSION['orgId'];
		$staff=fetchOrganizationStaffList($con,$orgId,$role,$status);

		foreach ($staff as $data){
			echo "<option value=".$data['staffId']." >".$data['fullName']."</option>";
		}
	}

  function dropDownListOrgStaffListIfIsStaff(){
    $con=connectDb();
    $status=1;
    $role=42;
    $isStaff = 1;
    $orgId=$_SESSION['orgId'];
    $staff=fetchOrganizationStaffListIfIsStaff($con,$orgId,$isStaff,$status);

    echo "<select onchange='showStaffInfo()' name='orgStaffId' class='form-control' id='orgStaffId' >";
    echo "<option value='null' selected disabled >--Select--</option>\n";

    foreach ($staff as $data){
      echo "<option value=".$data['id']." >".$data['name']." [ ".$data['staffId']." ]"."</option>";
    }


    echo	"</select>";
  }

  function dropDownListOrgStaffListIfIsStaffReturnProjectTaskId($projectTaskId){
    $con=connectDb();
    $status=1;
    $role=42;
    $isStaff = 1;
    $orgId=$_SESSION['orgId'];
    $staff=fetchOrganizationStaffListIfIsStaff($con,$orgId,$isStaff,$status);

    $return = "<select onchange='showStaffInfo()' name='orgStaffId' class='form-control' id='orgStaffId".$projectTaskId."' >";
    $return .= "<option value='null' selected disabled >--Select--</option>\n";

    foreach ($staff as $data){
      $return .= "<option value=".$data['id']." >".$data['name']." [ ".$data['staffId']." ]"."</option>";
    }


    $return .= "</select>";

    return $return;
  }

	function dropDownListOrgListActive($userid){
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		if($userid == 7 || $userid == 37){
		    $staff=fetchOrganizationUserList($con);
		}else{
		    $staff=fetchOrganizationUserListbyId($con,$userid);
		}
		echo "<select onchange='showStaffInfo()' name='orgStaffId' class='form-control' id='orgStaffId' required >";
		echo "<option value='null' selected disabled >--Select--</option>\n";

		foreach ($staff as $data){
			echo "<option value=".$data['id']." >".$data['name']." [ ".$data['staffId']." ]"."</option>";
		}


		echo	"</select>";
	}


	function dropDownOptionListOrgStaffListActive(){
		$optionList="";
		$con=connectDb();
		$status=1;
		$role=42;
		$orgId=$_SESSION['orgId'];
		$staff=fetchOrganizationStaffList($con,$orgId,$role,$status);

		$phone='[';
			$count=0;
		foreach ($staff as $data){
			if($count!=0){ $phone=$phone.',';}
			$phone=$phone.'{"wid":"'.$data['id'].'","phone":"'.$data['phone'].'"}';
			$optionList.="<option value='".$data['id']."' >".$data['name']." [ ".$data['staffId']." ]"."</option>";
			$count++;
		}
		$phone=$phone.']';
		$_SESSION['stphone']=$phone;
		return $optionList;
	}

   function organizationStaffListTableDownload(){
       $con=connectDb();

       $table="<div class='table-responsive table-stripped table-bordered'>\n";
       $table.="<table id='dataTable2' class='table' width='100%' cellspacing='0'>\n";
       $table.="<thead class='thead-dark'>\n";
       $table.="<tr>\n";
       $table.=	"<th>\n";
       $table.=		"#\n";
       $table.=	"</th>\n";
       $table.=	"<th>\n";
       $table.=		"Name\n";
       $table.= 	"</th>\n";
       $table.= 	"<th>\n";
       $table.= 		"Staff ID\n";
       $table.= 	"</th>\n";

       $table.= 	"<th>\n";
       $table.= 		"Username\n";
       $table.= 	"</th>\n";

       $table.= 	"<th>\n";
       $table.= 		"Password\n";
       $table.= 	"</th>\n"
       ;
       $table.= 	"<th>\n";
       $table.= 		"Action\n";
       $table.= 	"</th>\n";

       $table.= "</tr>\n";
       $table.= "</thead >\n";

       $i=1;
       $orgId=$_SESSION['orgId'];
       $status=1;
       $role=42;
       $dataList=fetchOrganizationStaffList($con,$orgId,$role,$status);

       $table.="<tbody>";
       foreach($dataList as $data){
           if($data['fullName']=="superadmin" && $_SESSION['fullName']!="superadmin"){
               // do nothing
           }else{
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
               $table.=		$data['staffId'];
               $table.=	"</td>";

               $table.=	"<td>";
               $table.=		$data['username'];
               $table.=	"</td>";

               $table.=	"<td>";
               $table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$data['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
               $table.=	"</td>";


               $table.="<td>";
               if($data['role']!=1){
                   $table.="<div class='dropdown'>";
                   $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

                   $table.="<button type='button' data-toggle='modal' data-target='#staffEditModal' class='dropdown-item' onclick='staffEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";


                   $table.="	</div>
							</div>";
               }else{
                   $table.="<span style='color:red'>RESTRICTED</span>";
               }
               $table.="</td>";

               $table.= "</tr>";
           }
       }

       $table.="</tbody>";
       $table.= "</table>";
       $table.= "</div>";

       echo $table;

   }
  function organizationStaffListTableEditable(){
		$con=connectDb();

		$table="<div class='table-responsive table-stripped table-bordered'>\n";
		$table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"#\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"Name\n";
		$table.= 	"</th>\n";
		$table.= 	"<th>\n";
		$table.= 		"Staff ID\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"Username\n";
		$table.= 	"</th>\n";

		$table.= 	"<th>\n";
		$table.= 		"Password\n";
		$table.= 	"</th>\n"
		;
		$table.= 	"<th>\n";
		$table.= 		"Action\n";
		$table.= 	"</th>\n";

		$table.= "</tr>\n";
		$table.= "</thead >\n";

		$i=1;
		$orgId=$_SESSION['orgId'];
		$status=1;
		$role=42;
		$dataList=fetchOrganizationStaffList($con,$orgId,$role,$status);
 		$table.="<tbody>";
		foreach($dataList as $data){
			if($data['fullName']=="superadmin" && $_SESSION['fullName']!="superadmin"){
				// do nothing
			}else{
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
				$table.=		$data['staffId'];
				$table.=	"</td>";

				$table.=	"<td>";
				$table.=		$data['username'];
				$table.=	"</td>";

				$table.=	"<td>";
				$table.="<input type='password' class='form-control' style='width:70%' id='".$data['id']."' disabled value='".$data['password']."' /><span style='cursor:pointer' onclick='showPassword(".$data['id'].")' > show</span>";
				$table.=	"</td>";


				$table.="<td>";
					if($data['role']!=1){
						$table.="<div class='dropdown'>";
						$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

						$table.="<button type='button' data-toggle='modal' data-target='#staffEditModal' class='dropdown-item' onclick='staffEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";


						$table.="	</div>
							</div>";
					}else{
						$table.="<span style='color:red'>RESTRICTED</span>";
					}
				$table.="</td>";

				$table.= "</tr>";
			}
		}

		$table.="</tbody>";
		$table.= "</table>";
		$table.= "</div>";

		echo $table;

	}

  function staffNameListArray(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $con=connectDb();
    $orgId=$_SESSION['orgId'];
    $role = 42;
    $status = 1;
    $dataList = fetchOrganizationStaffList($con,$orgId,$role,$status);
      $array="[";
    foreach ($dataList as $data) {
      $array.= "'".$data['staffId']."'".",";
    }
    $array.="]";
    return $array;
  }



  function staffJobStatusCount(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");
    $con=connectDb();
    $orgId=$_SESSION['orgId'];
    $role = 2;
    $status = 1;
      $count=0;
    //$dataList = fetchOrganizationStaffList($con,$orgId,$role,$status);
      $dataList= fetchAllJobListAdmin($con);
    //Completed

    //$array = "[{name:'Completed',data:[";
      $array = "[{name:'Task',colorByPoint: true, data:[{name: 'Completed',y:";
    foreach ($dataList as $data) {
     // $workerId = $data['id'];
     // $array.=staffCompletedJobArray($con,$workerId).",";
        if($data['status']==0)
        $count++;
    }
   // $array.=$count."]},";
     $array.=$count."},";
      $count=0;
    //Pending
    $array .= "{name:'Pending',y:";
    foreach ($dataList as $data) {
     // $workerId = $data['id'];
     // $array.=staffPendingJobArray($con,$workerId).",";
        if($data['status']==2)
        $count++;
    }
      // $array.=$count."]},";
      $array.=$count."},";
      $count=0;
    //New
    //$array .= "{name:'New',data:[";
    //foreach ($dataList as $data) {
    //  $workerId = $data['id'];
    //  $array.=staffNewJobArray($con,$workerId).",";
    //}
    //$array.="]},";

    //In Progress
    $array .= "{name:'In Progress',y:";
    foreach ($dataList as $data) {
     // $workerId = $data['id'];
     // $array.=staffInProgressJobArray($con,$workerId).",";
        if($data['status']==3)
        $count++;
    }
    //$array.=$count."]}]";
      $array.=$count."}] }]";

    return $array;
  }

  if(isset($_GET['nameListArray'])){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $con=connectDb();
    $orgId=$_SESSION['orgId'];
    $role = 42;
    $status = 1;
    $dataList = fetchOrganizationStaffList($con,$orgId,$role,$status);
    $array="[";
    $comma = false;
    foreach ($dataList as $data) {
      if($comma){
        $array.= ",";
      }
      $array.= "'".$data['staffId']."'";
      $comma = true;
    }
    $array.="]";
    //echo $array;
    echo $array;
  }
if(isset($_GET['staffDetail'])){
	$con=connectDb();
    $staffId= $_GET['staffDetail'];
	$data=fetchOrganizationUserbyId($con,$staffId);
	echo json_encode($data);
}
  if(isset($_GET['jobStatusCount'])){
    $dateMonth = $_GET['dateMonth'];
    $dateMonth = date("m",strtotime($dateMonth));
    $dateYear = date("Y",strtotime($dateMonth));

    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");
    $con=connectDb();
    $orgId=$_SESSION['orgId'];
    $role = 42;
    $status = 1;
    $dataList = fetchOrganizationStaffList($con,$orgId,$role,$status);
    //Completed
    $array = "[{name:'Completed',data:[";
    $comma=false;
    foreach ($dataList as $data) {
      if($comma){
        $array.= ",";
      }
      $workerId = $data['id'];
      $array.=staffCompletedJobArrayByDate($con,$workerId,$dateYear,$dateMonth);
      $comma = true;
    }
    $array.="]},";

    //Pending
    $array .= "{name:'Pending',data:[";
    $comma=false;
    foreach ($dataList as $data) {
      if($comma){
        $array.= ",";
      }
      $workerId = $data['id'];
      $array.=staffPendingJobArrayByDate($con,$workerId,$dateYear,$dateMonth);
      $comma = true;
    }
    $array.="]},";

    //New
    //$array .= "{name:'New',data:[";
    //foreach ($dataList as $data) {
    //  $workerId = $data['id'];
    //  $array.=staffNewJobArray($con,$workerId).",";
    //}
    //$array.="]},";

    //In Progress
    $array .= "{name:'In Progress',data:[";
    $comma=false;
    foreach ($dataList as $data) {
      if($comma){
        $array.= ",";
      }
      $workerId = $data['id'];
      $array.=staffInProgressJobArrayByDate($con,$workerId,$dateYear,$dateMonth);
      $comma = true;
    }
    $array.="]}]";
    //echo $array;

    echo json_encode($array);
  }

  if(isset($_GET['jobStatusCount2'])){
    $date = $_GET['dateMonth'];
    $dateMonth = date("m",strtotime($date));
    $dateYear =  date("Y", strtotime($date));

    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/job.php");
    $con=connectDb();
    $orgId=$_SESSION['orgId'];
    $role = 42;
    $status = 1;
    $dataList = fetchOrganizationStaffList($con,$orgId,$role,$status);
    //Completed
    $array = "";
    $test = "";
    foreach ($dataList as $data) {
      $workerId = $data['id'];
      $array.="<input class='complete' type='number' value ='".staffCompletedJobArrayByDate($con,$workerId,$dateYear,$dateMonth)."'>";
    }


    //Pending
    foreach ($dataList as $data) {
      $workerId = $data['id'];
      $array.="<input class='pending' type='number' value ='".staffPendingJobArrayByDate($con,$workerId,$dateYear,$dateMonth)."'>";
    }

    //In Progress
    foreach ($dataList as $data) {
      $workerId = $data['id'];
      $array.="<input class='progress' type='number' value ='".staffInProgressJobArrayByDate($con,$workerId,$dateYear,$dateMonth)."'>";
    }
    //echo $array;

    echo $array;
  }


   // Department start
   if(isset($_POST['addDepartment'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
           $con=connectDb();
           $departmentName=$_POST['departmentName'];
           $saveSuccess=addDepartment($con,$departmentName);
           if($saveSuccess){
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> <strong>".$departmentName."</strong> DEPARTMENT SUCCESSFULLY ADDED</div>";
                // End of CLEANTO ADD STAFF
           }else{
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>FAILED!</strong> FAILED TO ADD DEPARTMENT\n
				</div>\n";
           }

       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/addDepartment.php");
   }

   if(isset($_POST['editDepartment'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
       $departmentId=$_POST['departmentIdToEdit'];
       $con = connectDb();
       $sql = "SELECT * FROM `department` WHERE `id` = '$departmentId'";
       $result = mysqli_query($con, $sql);
       $row = mysqli_fetch_array($result);
       $_SESSION['departmentIdEdit'] = $row['id'];
       $_SESSION['nameEdit'] = $row['name'];
       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/editDepartment.php");

   }

   if(isset($_POST['removeDepartment'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
       $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE DEPARTMENT \n
			</div>\n
			";
       $con=connectDb();
       $departmentId=$_POST['departmentIdToEdit'];
       $saveSuccess=deleteDepartment($con,$departmentId);
       if($saveSuccess){
           $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> DEPARTMENT SUCCESSFULLY REMOVED \n
			</div>\n";

       }
       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/viewDepartment.php");

   }

   if(isset($_POST['editDepartmentProcess'])){
       $lastId = $_SESSION['departmentIdEdit'];
       $currentId =$_POST['departmentId'];
       $name=$_POST['departmentName'];

       $sql = "UPDATE `department`
            SET `name`='$name', id='$currentId' WHERE `id`='$lastId'";
       $result = mysqli_query(connectDb(),$sql);
       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/viewDepartment.php");
       $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> DEPARTMENT INFORMATIONS SUCCESSFULLY UPDATED</div>";
   }

   function organizationDepartmentListTableEditable(){
       $con=connectDb();

       $table="<div class='table-responsive table-stripped table-bordered'>\n";
       $table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
       $table.="<thead class='thead-dark'>\n";
       $table.="<tr>\n";
       $table.=	"<th>\n";
       $table.=		"Id\n";
       $table.=	"</th>\n";
       $table.=	"<th>\n";
       $table.=		"Department Name\n";
       $table.= 	"</th>\n";
       $table.=	"<th>\n";
       $table.=		"Action\n";
       $table.= 	"</th>\n";
       $table.= "</tr>\n";
       $table.= "</thead >\n";
       $i=1;
       $orgId=$_SESSION['orgId'];
       $status=1;
       $role=null;
       $dataList=fetchDepartmentList($con);
       $table.="<tbody>";
       foreach($dataList as $data){
               $table.= "<tr ";
               if($i%2==0)
                   $table.= "style='background-color:#FFF5EB;'";
               else{
                   $table.= "style='background-color:#F9F9F9;'";
               }$table.= ">";

               $table.=	"<td style='font-weight:bold'>";
               $table.=	$data['id'];
               $table.=	"</td>";
               $table.=	"<td>";
               $table.=		$data['name'];
               $table.=	"</td>";
               $table.="<td>";
                   $table.="<div class='dropdown'>";
                   $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

                   $table.="<button type='button' data-toggle='modal' data-target='#departmentEditModal' class='dropdown-item' onclick='departmentEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
                   $table.="	</div>
							</div>";
               $table.="</td>";
               $table.= "</tr>";
       }

       $table.="</tbody>";
       $table.= "</table>";
       $table.= "</div>";
       echo $table;
   }

   // Department end

   // Designation start
   if(isset($_POST['addDesignation'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
       $con=connectDb();
       $designation=$_POST['designation'];
	   $num=checkDesignationName($con,$designation);
	   if($num>0){
		   $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
				<strong>FAILED!</strong> ALREADY EXIST DESIGNATION \n
				</div>\n";
	   }else{
       $saveSuccess=addDesignation($con,$designation);
       if($saveSuccess){
           $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> <strong>".$designation."</strong> DESIGNATION SUCCESSFULLY ADDED</div>";
           // End of CLEANTO ADD STAFF
       }else{
           $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
				<strong>FAILED!</strong> FAILED TO ADD DESIGNATION\n
				</div>\n";
       }
	   }

       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/addDesignation.php");
   }

   if(isset($_POST['editDesignation'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
       $departmentId=$_POST['designationIdToEdit'];
       $con = connectDb();
       $sql = "SELECT * FROM `departmentprofile` WHERE `id` = '$departmentId'";
       $result = mysqli_query($con, $sql);
       $row = mysqli_fetch_array($result);
       $_SESSION['designationIdEdit'] = $row['id'];
       $_SESSION['designationEdit'] = $row['designation'];

       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/editDesignation.php");

   }

   if(isset($_POST['removeDesignation'])){
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
       $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE DESIGNATION \n
			</div>\n
			";
       $con=connectDb();
       $designationId=$_POST['designationIdToEdit'];
       $saveSuccess=deleteDesignation($con,$designationId);
       if($saveSuccess){
           $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> DESIGNATION SUCCESSFULLY REMOVED \n
			</div>\n";

       }
       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/viewDesignation.php");

   }


   if(isset($_POST['editDesignationProcess'])){
       $lastId = $_SESSION['designationIdEdit'];
       $currentId =$_POST['designationId'];
       $designation=$_POST['designationName'];
	   $role=$_POST['designation'];

       $sql = "UPDATE `departmentprofile` SET `designation`='$designation', `id`='$currentId'  WHERE `id`='$lastId'";
       $result = mysqli_query(connectDb(),$sql);
	   $sql1 = "UPDATE `roles` SET `role`='$designation' WHERE `role`='$role'";
	   $result1 = mysqli_query(connectDb(),$sql1);
	   //mysqli_stmt_close($sql1);
       header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/department/viewDesignation.php");
       $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> DESIGNATION INFORMATIONS SUCCESSFULLY UPDATED</div>";
   }

   function organizationDesignationListTableEditable(){
       $con=connectDb();

       $table="<div class='table-responsive table-stripped table-bordered'>\n";
       $table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
       $table.="<thead class='thead-dark'>\n";
       $table.="<tr>\n";
       $table.=	"<th>\n";
       $table.=		"Id\n";
       $table.=	"</th>\n";
       $table.=	"<th>\n";
       $table.=		"Designation\n";
       $table.= 	"</th>\n";
       $table.=	"<th>\n";
       $table.=		"Action\n";
       $table.= 	"</th>\n";
       $table.= "</tr>\n";
       $table.= "</thead >\n";
       $i=1;
       $orgId=$_SESSION['orgId'];
       $status=1;
       $role=null;
       $dataList=fetchDesignationList($con);
       $table.="<tbody>";
       foreach($dataList as $data){
           $table.= "<tr ";
           if($i%2==0)
               $table.= "style='background-color:#FFF5EB;'";
           else{
               $table.= "style='background-color:#F9F9F9;'";
           }$table.= ">";

           $table.=	"<td style='font-weight:bold'>";
           $table.=	$data['id'];
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=		$data['designation'];
           $table.=	"</td>";
           $table.="<td>";
           $table.="<div class='dropdown'>";
           $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

           $table.="<button type='button' data-toggle='modal' data-target='#designationEditModal' class='dropdown-item' onclick='designationEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
           $table.="	</div>
							</div>";
           $table.="</td>";
           $table.= "</tr>";
       }

       $table.="</tbody>";
       $table.= "</table>";
       $table.= "</div>";
       echo $table;
   }
// Designation End



   // Department list - start
   function departmentList(){
       $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
       $con=connectDb();
       $orgId=$_SESSION['orgId'];
       $role = 2;
       $status = 1;
       $dataList = fetchDepartmentList($con);
       $array=array();
       $i=0;
       foreach ($dataList as $data) {
           $array[$i++]= $data['name'];
       }
       return $array;
   }
   // Department list - end

   // Designation list - start
   function designationList(){
       $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
       $con=connectDb();
       $orgId=$_SESSION['orgId'];
       $role = 2;
       $status = 1;
       $dataList = fetchDesignationList($con);
       $array=array();
       $array=array();
       $i=0;
       foreach ($dataList as $data) {
           $array[$i++]= $data['designation'];
       }
       return $array;
   }
   //Designation list - end
?>