   <?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
	 session_name($config['sessionName']);
	session_start();
}

define('FS_METHOD', 'direct');
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/subscriber.php");

   function newMemberRequestListTable(){
	   $con=connectDb();
	   $table="<div class='table-responsive'>\n";
	   $table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
	   $table.="<thead class='thead-dark'>\n";
	   $table.="<tr>\n";
	   $table.=	"<th>\n";
	   $table.=	"#\n";
	   $table.=	"</th>\n";
	   $table.=	"<th>\n";
	   $table.=	" Name\n";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	" Date\n";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Transaction ID";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Plan";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Status";
	   $table.= "</th>\n";
	   $table.= "<th>\n";
	   $table.= "Payment Status\n";
	   $table.=	"</th>\n";
	   $table.= "<th>\n";
	   $table.= "Action\n";
	   $table.= "</th>\n";
	   $table.= "</tr>\n";
	   $table.= "</thead >\n";

	   $i=1;
	   $orgId=$_SESSION['orgId'];
	   $dataList=fetchNewMemberList($con);
	   print_r($dataList,1);
	   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

	   foreach($dataList as $data){
		   $table.= "<tr ";
		   if($i%2==0)
			   $table.= "style='background-color:#FFF5EB;'";
		   else{
			   $table.= "style='background-color:#F9F9F9;'";
		   }$table.= ">";

		   $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=		$i++;
		   $table.=	"</td>";
		   $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=		$data['name'];
		   $table.=	"</td>";
           $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
           $date=date_create($data['pay']);
           $date= date_format($date,"d/m/Y H:i");
		   $table.=	$date;
		   $table.=	"</td>";
           $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=	$data['transactionId'];
		   $table.=	"</td>";
           $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=	$data['title'];
		   $table.=	"</td>";
           $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=	($data['memberStatus']>0)?"Activated":"De-Activated";
		   $table.=	"</td>";
           $table.=	"<td onclick=\"cdetails('".$data['contactNo']."','".$data['email']."')\" >";
		   $table.=	($data['payStatus']>0)?"Paid":"Unpaid";
		   $table.=	"</td>";
		   $table.="<td>";
		   $table.="<div class='dropdown'>";
		   $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";

           $table.="<button type='button' data-toggle='modal' data-target='#clientEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>More Action</button>";
           if($data['memberStatus']>0) {
               $table .= "<button type='button' data-toggle='modal' data-target='#regEditModal' class='dropdown-item' onclick='memberEdit(this,1)' value='$data[id]' style='cursor:pointer'>De-Activate</button>";
           }else{
               $table .= "<button type='button' data-toggle='modal' data-target='#regEditModal' class='dropdown-item' onclick='memberEdit(this,0)' value='$data[id]' style='cursor:pointer'>Activate</button>";
           }
           if($data['payStatus']>0) {
               $table .= "<button type='button' data-toggle='modal' data-target='#payEditModal' class='dropdown-item' onclick='payEdit(this,1)' value='$data[id]' style='cursor:pointer'>Unpaid</button>";
           }else{
               $table .= "<button type='button' data-toggle='modal' data-target='#payEditModal' class='dropdown-item' onclick='payEdit(this,0)' value='$data[id]' style='cursor:pointer'>Paid</button>";
           }
		   $table.="</div>
					</div>";
		   $table.="</td>";

		   $table.= "</tr>";
	   }
	   $table.= "</table>";
	   $table.= "</div>";

	   echo $table;
   }

   function approvedMemberRequestListTable(){
	   $con=connectDb();
	   $table="<div class='table-responsive'>\n";
	   $table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
	   $table.="<thead class='thead-dark'>\n";
	   $table.="<tr>\n";
	   $table.=	"<th>\n";
	   $table.=	"#\n";
	   $table.=	"</th>\n";
	   $table.=	"<th>\n";
	   $table.=	" Name\n";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	" Date\n";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Transaction ID";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Plan";
	   $table.= "</th>\n";
	   $table.=	"<th>\n";
	   $table.=	"Status";
	   $table.= "</th>\n";
	   $table.= "<th>\n";
	   $table.= "Paid\n";
	   $table.=	"</th>\n";
	   $table.= "<th>\n";
	   $table.= "Action\n";
	   $table.= "</th>\n";
	   $table.= "</tr>\n";
	   $table.= "</thead >\n";

	   $i=1;
	   $orgId=$_SESSION['orgId'];
	   $dataList=fetchActivatedMemberList($con);
	   print_r($dataList,1);
	   $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");

	   foreach($dataList as $data){
		   $table.= "<tr ";
		   if($i%2==0)
			   $table.= "style='background-color:#FFF5EB;'";
		   else{
			   $table.= "style='background-color:#F9F9F9;'";
		   }$table.= ">";

		   $table.=	"<td>";
		   $table.=		$i++;
		   $table.=	"</td>";
		   $table.=	"<td>";
		   $table.=		$data['name'];
		   $table.=	"</td>";
           $table.=	"<td>";
           $date=date_create($data['pay']);
           $date= date_format($date,"d/m/Y H:i");
		   $table.=	$date;
		   $table.=	"</td>";
           $table.=	"<td>";
		   $table.=	$data['transactionId'];
		   $table.=	"</td>";
           $table.=	"<td>";
		   $table.=	$data['title'];
		   $table.=	"</td>";
           $table.=	"<td>";
		   $table.=	($data['memberStatus']>0)?"Activated":"De-Activated";
		   $table.=	"</td>";
           $table.=	"<td>";
		   $table.=	($data['payStatus']>0)?"Paid":"Unpaid";
		   $table.=	"</td>";
		   $table.="<td>";
		   $table.="<div class='dropdown'>";
		   $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";

           $table.="<button type='button' data-toggle='modal' data-target='#clientEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>More Action</button>";
           if($data['memberStatus']>0) {
               $table .= "<button type='button' data-toggle='modal' data-target='#regEditModal' class='dropdown-item' onclick='memberEdit(this,1)' value='$data[id]' style='cursor:pointer'>De-Activate</button>";
           }else{
               $table .= "<button type='button' data-toggle='modal' data-target='#regEditModal' class='dropdown-item' onclick='memberEdit(this,0)' value='$data[id]' style='cursor:pointer'>Activate</button>";
           }
           if($data['payStatus']>0) {
               $table .= "<button type='button' data-toggle='modal' data-target='#payEditModal' class='dropdown-item' onclick='payEdit(this,1)' value='$data[id]' style='cursor:pointer'>Unpaid</button>";
           }else{
               $table .= "<button type='button' data-toggle='modal' data-target='#payEditModal' class='dropdown-item' onclick='payEdit(this,0)' value='$data[id]' style='cursor:pointer'>Paid</button>";
           }
		   $table.="</div>
					</div>";
		   $table.="</td>";

		   $table.= "</tr>";
	   }
	   $table.= "</table>";
	   $table.= "</div>";

	   echo $table;
   }
if(isset($_POST['updateMembershipStatus'])){
    $tid=$_POST['clientIdToEdit'];
    $con = connectDb();
    $memStatus=$_POST['updateMembershipStatus'];
    $res=updateMembershipStatus($con,$memStatus,$tid);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>UPDATED SUCCESS! </strong>MEMBERSHIP STATUS UPDATED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>UPDATE FAILED!</strong>MEMBERSHIP STATUS UPDATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/subscriber.php");

}
if(isset($_POST['updateActivatedMembershipStatus'])){
    $tid=$_POST['clientIdToEdit'];
    $con = connectDb();
    $memStatus=$_POST['updateActivatedMembershipStatus'];
    $res=updateActivatedMembershipStatus($con,$memStatus,$tid);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>UPDATED SUCCESS! </strong>MEMBERSHIP STATUS UPDATED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>UPDATE FAILED!</strong>MEMBERSHIP STATUS UPDATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/activatedSubscriber.php");
}

if(isset($_POST['updatePaymentStatus'])){
    $tid=$_POST['clientIdToEdit'];
    $con = connectDb();
       $payStatus=$_POST['updatePaymentStatus'];
    $res=updatePaymentStatus($con,$payStatus,$tid);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>UPDATED SUCCESS! </strong>PAYMENT STATUS UPDATED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>UPDATE FAILED!</strong>PAYMENT STATUS UPDATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/subscriber.php");
}

if(isset($_POST['updateActivatedPaymentStatus'])){
    $tid=$_POST['clientIdToEdit'];
    $con = connectDb();
       $payStatus=$_POST['updateActivatedPaymentStatus'];
    $res=updateActivatedPaymentStatus($con,$payStatus,$tid);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>UPDATED SUCCESS! </strong>PAYMENT STATUS UPDATED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>UPDATE FAILED!</strong>PAYMENT STATUS UPDATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/activatedSubscriber.php");
}

if(isset($_GET['cidMember'])){
    $con = connectDb();
    $cid=$_GET['cidMember'];
    $data=getMembershipDetails($con,$cid);
    echo json_encode($data);
}
if(isset($_POST['addPlan'])){
    $title= $_POST['planTitle'];
    $desc=$_POST['description'];
    $price=$_POST['price'];
    $duration=$_POST['duration'];
    $uploadFileName="";
    $jobDirectory="";
    if(file_exists($_FILES['filePdf']['tmp_name']) || is_uploaded_file($_FILES['filePdf']['tmp_name'])) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
        $name = $_FILES['filePdf']['name'];

        $temp_name = $_FILES['filePdf']['tmp_name'];
        $size = $_FILES['filePdf']['size'];
        if ($_FILES['filePdf']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";

                header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/addMembership.php");
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['filePdf']['tmp_name']);
        $ok = FALSE;
        $extension = "";
        //application/pdf, application/msword, 	application/vnd.openxmlformats-officedocument.wordprocessingml.document
        switch ($mime) {
            case 'application/pdf':
                $extension = ".pdf";break;
            case 'application/msword':
                $extension = ".doc";break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $extension = ".docx";break;
            default:
            {
                $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>UNKNOWN FILE!</strong> UPLOAD FILE IS FAILED</div>";
            }
        }

        $uploadFileName = time() . "" . $extension;
        $path = "/resources/2/plan/";
        $jobDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
        if (!file_exists($jobDirectory)) {
            mkdir($jobDirectory, 0777, TRUE);
            copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $jobDirectory . '/index.php');
        }
        move_uploaded_file($temp_name, $jobDirectory . "/$uploadFileName");
    }

    $con=connectDb();
    $res=addMembershipPlan($con,$title,$desc,$duration,$price,$uploadFileName);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>ADDED SUCCESS! </strong>MEMBERSHIP PLAN ADDED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>ADD FAILED!</strong>MEMBERSHIP PLAN ADD FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/addMembership.php");
}
if(isset($_POST['updatePdf'])){
    $planId= $_POST['clientIdToEdit'];
    $uploadFileName="";
    $jobDirectory="";
    if(file_exists($_FILES['filePdf']['tmp_name']) || is_uploaded_file($_FILES['filePdf']['tmp_name'])) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
        $name = $_FILES['filePdf']['name'];

        $temp_name = $_FILES['filePdf']['tmp_name'];
        $size = $_FILES['filePdf']['size'];
        if ($_FILES['filePdf']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";

                header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/addMembership.php");
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['filePdf']['tmp_name']);
        $ok = FALSE;
        $extension = "";
        //application/pdf, application/msword, 	application/vnd.openxmlformats-officedocument.wordprocessingml.document
        switch ($mime) {
            case 'application/pdf':
                $extension = ".pdf";break;
            case 'application/msword':
                $extension = ".doc";break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $extension = ".docx";break;
            default:
                $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>UNKNOWN FILE!</strong> UPLOAD FILE IS FAILED</div>";
        }

        $uploadFileName = time() . "" . $extension;
        $path = "/resources/2/plan/";
        $jobDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
        if (!file_exists($jobDirectory)) {
            mkdir($jobDirectory, 0777, TRUE);
            copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $jobDirectory . '/index.php');
        }
        move_uploaded_file($temp_name, $jobDirectory . "/$uploadFileName");
    }

    $con=connectDb();
    $res=updateMembershipPlanPDF($con,$uploadFileName,$planId);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>ADDED SUCCESS! </strong>MEMBERSHIP PLAN PDF UPLOADED SUCCESSFUL</div>";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>ADD FAILED!</strong>MEMBERSHIP PLAN PDF UPLOADED FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/membershipPlan.php");
}

if (isset($_POST['removePlan'])) {
       $con = connectDb();
       $planId = $_POST['clientIdToEdit'];
       $success = removeMembershipPlan($con, $planId);

       if (!$success) {
           $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO REMOVE PLAN\n
			</div>\n";
       } else {
           $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> REMOVED PLAN \n</div>\n";
       }
           header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/membershipPlan.php");
   }
   function membershipPlanTable(){
       $con=connectDb();
       $table="<div class='table-responsive'>\n";
       $table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
       $table.="<thead class='thead-dark'>\n";
       $table.="<tr>\n";
       $table.=	"<th>\n";
       $table.=	"#\n";
       $table.=	"</th>\n";
       $table.=	"<th>\n";
       $table.=	"Title\n";
       $table.= "</th>\n";
       $table.=	"<th>\n";
       $table.=	"Description\n";
       $table.= "</th>\n";
       $table.=	"<th>\n";
       $table.=	"price";
       $table.= "</th>\n";
       $table.= "<th>\n";
       $table.= "Action\n";
       $table.= "</th>\n";
       $table.= "</tr>\n";
       $table.= "</thead >\n";
       $i=1;
       $dataList=fetchMembershipPlan($con);
       $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
       foreach($dataList as $data){
           $table.= "<tr ";
           if($i%2==0)
               $table.= "style='background-color:#FFF5EB;'";
           else{
               $table.= "style='background-color:#F9F9F9;'";
           }
           if(!empty($data['path'])){
               $table.=" data-value='".$data['path']."' ";}

           $table.= ">";
           $table.=	"<td>";
           $table.=		$i++;
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=		$data['title'];
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=	$data['description'];
           $table.=	"</td>";
           $table.=	"<td>";
           $table.="RM ".$data['price'];
           $table.=	"</td>";
           $table.="<td>";
           $table.="<div class='dropdown'>";
           $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";
           $table.="<button type='button' data-toggle='modal' data-target='#planEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[id]' style='cursor:pointer'>Action</button>";
           $table.="</div>
					</div>";
           $table.="</td>";
           $table.= "</tr>";
       }
       $table.= "</table>";
       $table.= "</div>";
       echo $table;
   }
   function terminateRequestByClient(){
       $con=connectDb();
       $table="<div class='table-responsive'>\n";
       $table.="<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
       $table.="<thead class='thead-dark'>\n";
       $table.="<tr>\n";
       $table.=	"<th>\n";
       $table.=	"#\n";
       $table.=	"</th>\n";
       $table.=	"<th>\n";
           $table.=	"Member Id\n";
       $table.= "</th>\n";
       $table.=	"<th>\n";
       $table.=	"Name\n";
       $table.= "</th>\n";
       $table.=	"<th>\n";
       $table.=	"Notes";
       $table.= "</th>\n";
       $table.= "<th>\n";
       $table.=	"Date";
       $table.= "</th>\n";
       $table.= "<th>\n";
       $table.= "Action\n";
       $table.= "</th>\n";
       $table.= "</tr>\n";
       $table.= "</thead >\n";
       $i=1;
       $dataList=fetchTerminateRequestByClient($con);
       $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
       require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");
       foreach($dataList as $data){
           $table.= "<tr ";
           if($i%2==0)
               $table.= "style='background-color:#FFF5EB;'";
           else{
               $table.= "style='background-color:#F9F9F9;'";
           }$table.= ">";
           $table.=	"<td>";
           $table.=		$i++;
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=		$data['cid'];
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=	$data['name'];
           $table.=	"</td>";
           $table.=	"<td>";
           $table.=$data['Notes'];
           $table.=	"</td>";
           $table.=	"<td>";
           $date=date_create($data['created_at']);
           $date= date_format($date,"Y/m/d H:i");
           $table.=$date;
           $table.=	"</td>";
           $table.="<td>";
           $table.="<div class='dropdown'>";
           $table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
				OPTION
				</button>
				<div class='dropdown-menu'>";
           $table.="<button type='button' data-toggle='modal' data-target='#planEditModal' class='dropdown-item' onclick='clientEdit(this)' value='$data[cid]' style='cursor:pointer'>Action</button>";
           $table.="</div>
					</div>";
           $table.="</td>";
           $table.= "</tr>";
       }
       $table.= "</table>";
       $table.= "</div>";
       echo $table;
   }
if(isset($_POST['removeAccount'])){
    $memberId=$_POST['clientIdToEdit'];
    $con=connectDb();
    $email=getEmailId($con,$memberId);
    $res=terminateAccountRequestApproved($con,$memberId);
    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS! </strong> MEMBER ACCOUNT ID:".$memberId." TERMINATED SUCCESSFUL</div>";

        if(!empty($email)){
            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
            require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
            $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
            $from=$orgDetails['supportEmail'];
            $fromName=$orgDetails['name'];
            $subject="Your Account id:".$memberId." is terminated successful";
            $body="<h4 style='text-align: center'>THANK YOU FOR VISITING!</h4>";
            $body.="<p style='text-align: center'>Hi ,<br/><br/>Your account ".$memberId." has been terminated. </p>
                       
                            <br />Click the <a href='https://".$_SERVER['HTTP_HOST'] . $config['appRoot']."'>link</a>  to login to your account!";

            mailsend($from,$fromName,$email,$subject,$body);
        }
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> MEMBER ACCOUNT ID:".$memberId." TERMINATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/terminateAccount.php");
}
if(isset($_POST['removeMemberFromApp'])){
    $memberId=$_POST['clientCompanyId'];
    $notes=$_POST['description'];
    $con=connectDb();
    $email=getEmailId($con,$memberId);

    $res=terminateAccountByAdmin($con,$memberId,$notes);

    if ($res) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS! </strong> MEMBER ACCOUNT ID:".$memberId." TERMINATED SUCCESSFUL</div>";
        if(!empty($email)){
            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
            require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
            $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
            $from=$orgDetails['supportEmail'];
            $fromName=$orgDetails['name'];
            $subject="Your Account id:".$memberId." is terminated successful";
            $body="<h4 style='text-align: center'>THANK YOU FOR VISITING!</h4>";
            $body.="<p style='text-align: center'>Hi ,<br/><br/>Your account ".$memberId." has been terminated. </p>
                       
                            <br />Click the <a href='https://".$_SERVER['HTTP_HOST'] . $config['appRoot']."'>link</a>  to login to your account!";

            mailsend($from,$fromName,$email,$subject,$body);
        }
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
			<strong>FAILED!</strong> MEMBER ACCOUNT ID:".$memberId." TERMINATE FAILED</div>";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/membership-subscription/terminateRequest.php");
}


?>