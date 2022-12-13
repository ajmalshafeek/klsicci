<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/signPDF.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";
if (isset($_POST['uploadPdf'])) {

    $saveSuccess = false;

    $companyId = "";
    if (isset($_POST['clientCompanyId'])) {
        $companyId = $_POST['clientCompanyId'];
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n<strong>Failed!</strong> UPDATE ALL FIELDS</div>";
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/uploadPdf.php");
    }


    $uploadFileName = "";


    if (file_exists($_FILES['myfile']['tmp_name']) || is_uploaded_file($_FILES['myfile']['tmp_name'])) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
        $name = $_FILES['myfile']['name'];

        $temp_name = $_FILES['myfile']['tmp_name'];
        $size = $_FILES['myfile']['size'];
        if ($_FILES['myfile']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
            header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/uploadPdf.php");
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['myfile']['tmp_name']);
        $ok = FALSE;
        $extension = "";
        //application/pdf, application/msword, 	application/vnd.openxmlformats-officedocument.wordprocessingml.document
        switch ($mime) {
            case 'application/pdf':
                $extension = ".pdf";
                break;
            default:
                $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n

					<strong>NO PDF FILE!</strong> UPLOAD FILE IS FAILED</div>";
        }


        $uploadFileName = time() . "" . $extension;
        $path = "/resources/" . $_SESSION['orgId'] . "/pdf/";
        $pdfDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, TRUE);
            copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $pdfDirectory . '/index.php');
        }
        move_uploaded_file($temp_name, $pdfDirectory . "/$uploadFileName");
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n<strong>Failed!</strong> UPDATE ALL FIELDS</div>";
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/uploadPdf.php");
    }

    $uploadBy = $_SESSION['name'];
    $con = connectDb();
    $saveSuccess = uploadPDF($con, $uploadBy, $companyId, $uploadFileName);
    if ($saveSuccess) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> UPLOAD PDF SUCCESSFULLY\n
					<br /><a href='" . 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php' style='text-decoration: none;color: blue;'>View For PDF List</a>
					</div>";

        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationUser.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organization.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php";

        $companyDetails = fetchClientCompanyDetails($con, $companyId);
        $orgDetails = getOrganizationDetails($con, $_SESSION['orgId']);

        //$orgAddress=$orgDetails[];


        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/mail.php";


        $orgAddress = $orgDetails['address1'] . ",";

        if ($orgDetails['address2'] != null) {

            $orgAddress .= "<br/>" . $orgDetails['address2'] . ",";

        }

        $orgAddress .= "<br/>" . $orgDetails['postalCode'] . " " . $orgDetails['city'] . ",";

        $orgAddress .= "<br/>" . $orgDetails['state'];


        $footer = '<br/><table width="150px"><tr><td><img style="height:80px;max-width:150px"  id="myorglogo" src="cid:logo_2u"></td></tr>></table>

			<br/>

			' . $orgAddress;


        $from = $companyDetails['emailAddress'];

        $fromName = $orgDetails['name'];

        $to = $orgDetails['supportEmail'];

        $subject = 'PDF SIGN / REQUEST';

        $body = 'PDF Document uploaded by ' . $_SESSION['name'] . '@' . $companyDetails['name'] . '. Thus, click on this <a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client" target="_blank" >link</a>

			to view detailed information and further action.<br/>
			    <br/>' . 'Thank You';

        $orgLogo = $_SESSION['orgLogo'];

        $mailMessage = mailTask($from, $fromName, $to, $subject, $body, $orgLogo);


    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n

					<strong>FAILED!</strong> UPLOAD PDF NOT UPLOADED \n

					<br /></div>";

    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/uploadPdf.php");
}

function fetchPdfList($uploadBy, $useBy, $companyId, $status, $setPlace)
{

    $con = connectDb();
    $table = "<div class='table-responsive table-stripped table-bordered' id='datatable'>\n";
    $table .= "<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .= "<th>\n";
    $table .= "No\n";
    $table .= "</th>\n";
    $table .= "<th>\n";
    $table .= "Upload By\n";
    $table .= "</th>\n";
    $table .= "<th>\n";
    $table .= "Client\n";
    $table .= "</th>\n";

    $table .= "<th>\n";
    $table .= "Signatory\n";
    $table .= "</th>\n";

    $table .= "<th>\n";
    $table .= "Status\n";
    $table .= "</th>\n";

    $table .= "<th>\n";
    $table .= "Modify Date\n";
    $table .= "</th>\n";;
    $table .= "<th>\n";
    $table .= "Action\n";
    $table .= "</th>\n";

    $table .= "</tr>\n";
    $table .= "</thead >\n";
    $i = 1;
    $dataList = getPdfList($con, $uploadBy, $useBy, $companyId, $status, $setPlace);
    $table .= "<tbody>";
    foreach ($dataList as $data) {
        $nameDetails=getCompanyDetailsById($con, $data['user']);
        $table .= "<tr ";
        if ($i % 2 == 0)
            $table .= "style='background-color:#FFF5EB;'";
        else {
            $table .= "style='background-color:#F9F9F9;'";
        }
        $table .= ">";

        $table .= "<td style='font-weight:bold'>";
        $table .= $i++;
        $table .= "</td>";
        $table .= "<td>";
        $table .= $data['uploadBy'];
        $table .= "</td>";

        $table .= "<td>";
        if(isset($nameDetails['name'])){
        $table .= $nameDetails['name'];}
        else{
            $table .= '';
        }
        $table .= "</td>";

        $table .= "<td>";
        if($data['auth']==0){
            if(isset($nameDetails['customer'])){
        $table .= $nameDetails['customer'];}
            else{
                $table .= '';
            }
        }
        else{
            $table .= getSignatoryName($con, $data['auth']);
        }
        $table .= "</td>";

        $table .= "<td>";

        $table.=		($data['status']?"Pending":"Signed");
       // $table .= $data['status'];
        $table .= "</td>";
        $temp = date_create($data['updated_at']);
        $table .= "<td>";
        $table .= date_format($temp, 'Y-m-d');
        $table .= "</td>";

        $table .= "<td>";
        $table .= "<div class='dropdown'>";
        $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";
        if ($_SESSION['role'] == 1) {
            $table .= "<button type='submit' name='pdf' class='dropdown-item' onclick=\"pdfclick('" . $data['id'] . "','" . $data['pdfName'] . "')\" value='$data[pdfName]' style='cursor:pointer'>Set Point</button>";
            $table .= "<button type='submit' name='pdf' class='dropdown-item' onclick=\"pdfsetall('" . $data['id'] . "','" . $data['pdfName'] . "',1)\" value='$data[pdfName]' style='cursor:pointer'>Set point for all pdf same place</button>";
            $table.="<button type='submit' name='pdf' class='dropdown-item' onclick=\"pdfShared('" . $data['id'] . "','" . $data['pdfName'] . "')\" value='$data[pdfName]' style='cursor:pointer'>Shared with for Sign</button>";
        }
        if (!empty($data['setPlace']) && $data['setPlace'] == 1) {
            $table .= "<button type='submit' name='signPdf' class='dropdown-item' onclick=\"pdfsign('" . $data['id'] . "','" . $data['pdfName'] . "')\" value='$data[pdfName]' style='cursor:pointer'>Sign PDF</button>";
        }
        $table .= "<button type='submit' name='pdf' class='dropdown-item' onclick=\"pdfview('";
        if (empty($data['signPdf'])) {
            $table .= $data['pdfName'];
        } else {
            $table .= $data['signPdf'];
        }
        $table .= "')\" value='";
        if (empty($data['signPdf'])) {
            $table .= $data['pdfName'];
        } else {
            $table .= $data['signPdf'];
        }
        $table .= "' style='cursor:pointer'>View PDF</button>";
        $table .= "	</div>
							</div>";
        $table .= "</td>";
        $table .= "</tr>";
    }
    $table .= "</tbody>";
    $table .= "</table>";
    $table .= "</div>";
    echo $table;
}

if (isset($_POST['savepdf'])) {
    $file = "";
    if (isset($_POST['output'])) {
        $file = $_POST['output'];
    }
    $con = connectDb();
    if (!empty($file)) {
        $pfid = $_POST['id'];
        $result = saveSignedPdf($con, $pfid, $file);
        if ($result) {

            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
                            <strong>SUCCESSFUL!</strong> UPDATE FILE IS SUCCESSFUL</div>";

            if (isset($_SESSION['pdfFileName'])) {
                unset($_SESSION['pdfFileName']);
            }
            if (isset($_SESSION['pfid'])) {
                unset($_SESSION['pfid']);
            }

            header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");

        } else {
            $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> UPDATE FILE IS FAILED</div>";
        }
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> UPDATE FILE NOT FOUND</div>";
    }

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");
}
if (isset($_POST['savepdfc'])) {
    $file = "";
    if (isset($_POST['output'])) {
        $file = $_POST['output'];
    }
    $con = connectDb();
    if (!empty($file)) {
        $pfid = $_POST['id'];
        $result=false;
        $mu=fechpdfdetails($con,$pfid);
        if($mu['mu']){
            $result=updateMuPDF($con,$mu['pdfName'],$file,$pfid);
            }else{
        $result = saveSignedPdf($con, $pfid, $file);}
        if ($result) {


            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
                            <strong>SUCCESSFUL!</strong> UPDATE FILE IS SUCCESSFUL</div>";

            if (isset($_SESSION['pdfFileName'])) {
                unset($_SESSION['pdfFileName']);
            }
            if (isset($_SESSION['pfid'])) {
                unset($_SESSION['pfid']);
            }

            header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/pdfSign/pdfList.php");

        } else {
            $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> UPDATE FILE IS FAILED</div>";
        }
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> UPDATE FILE NOT FOUND</div>";
    }

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/pdfSign/pdfList.php");
}
if (isset($_POST['saveSignLocation'])) {
    $x = array();
    $y = array();
    $p = array();
    $fileName = "";
    $same = 0;
    $id = $_SESSION['pfid'];
    if (isset($_POST['x'])) {
        $x = json_encode($_POST['x']);
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> SIGNING POINT NOT FOUND</div>";
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");
    }
    if (isset($_POST['y'])) {
        $y = json_encode($_POST['y']);
    }
    if (isset($_POST['p'])) {
        $p = json_encode($_POST['p']);
    }
    if (isset($_POST['pdfFile'])) {
        $fileName = $_POST['pdfFile'];
    }
    if (isset($_POST['same'])) {
        $same = $_POST['same'];
    }
    $con = connectDb();
    echo "<pre>";
    $res = addSignLocation($con, $x, $y, $p, $same, $id);
    if ($res) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> SIGNING PLACE ADDED SUCCESSFUL</div>";

    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> SIGNING POINT ADD FAILED</div>";
    }

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");
}
function getpdfdetails($id)
{

    $res = array();
    if (isset($id) && !empty($id)) {
        $con = connectDb();
        $res = fechpdfdetails($con, $id);
    }
    return $res;
}
if (isset($_GET['userType'])) {

    $dropDown = "<select class='form-control'  name='workerId' id='workerId' required>";
    $optionList = "";
    if ($_GET['userType'] == 0) {
        require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organizationUser.php";
        $dropDown .= dropDownOptionListOrgStaffListActive(); //phpfunctions/organizationUser.php
    } else {
        $optionList="";
        $con=connectDb();
        $status=1;
        $role=42;
        $orgId=$_SESSION['orgId'];
        $id=$_GET['id'];
        $client=dropDownOptionListActiveSignatory($con,$id);
        $count=0;
        if(!empty($client)){
        foreach ($client as $data){
            $optionList.="<option value='".$data['id']."' >".$data['name']." [ ".$data['position']." ]"."</option>";
            $count++;
        }}
        else{
            $optionList.="<option value='0' disabled selected>No More Signatory Found</option>";
        }
    }
    $dropDown .= $optionList;
    $dropDown .= "</select>";
    echo $dropDown;

}
function getButtonStatus($id){
    $con = connectDb();
    $res = fetchButtonStatus($con, $id);
    return $res;
}
if(isset($_POST['assignPDF'])) {
    print_r($_POST);
    if(isset($_POST['template'])&&!empty($_POST['template'])){
        $template = $_POST['template'];
    }
    else{ $template=null;};
    $pfid = $_POST['pfid'];
    if(isset($_POST['workerId'])){
        $auth = $_POST['workerId'];}
    else {
        $auth=null;
    }
    $is_attest=$_POST['is_attest'];
    $stat=$_POST['approve'];

    $con = connectDb();
    $res = assignPDFShardToUser($con, $template, $auth,$stat,$is_attest,$pfid);
    if ($res) {
            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> ASSIGN PDF SAVE SUCCESSFUL</div>";
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> ASSIGN FAILED SAVE </div>";
    }
    //header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/list.php");
}
/*
if(isset($_POST['assignPDF'])) {
    print_r($_POST);
    if(isset($_POST['template'])&&!empty($_POST['template'])){
        $template = $_POST['template'];
    }
    else{ $template=0;};
    echo $template;
    die();
    $pfid = $_POST['pfid'];
    $template = $_POST['template'];
    $useBy = $_POST['workerType'];
    if(isset($_POST['workerId'])){
        $auth = $_POST['workerId'];}
    else {
        $auth='';
    }
    $createdBy = $_SESSION['name'];
    $stat=$_POST['approve'];

    $con = connectDb();
    $user = getClientIdFromPDFList($con, $pfid);
    $res = assignPDFShardToUser($con, $createdBy, $useBy, $user['user'], $template, $auth);
    if ($res) {
        $result = assignPDFUpdateMultiUser($con, $template);
        if ($result) {
            $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> ASSIGN PDF SUCCESSFUL</div>";
        } else {
            $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> NOT PDF ASSIGN</div>";
        }
    } else {
        $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> NOT ASSIGN NOT SAVE </div>";
    }
    //header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/pdfList.php");
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/pdfSign/list.php");

} */
// Department start
if(isset($_POST['addSignatory'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $con=connectDb();

    $signatoryName=$_POST['signatoryName'];
    $cid=$_POST['clientCompanyId'];
    $position=$_POST['position'];
    $saveSuccess=addSignatory($con,$cid,$signatoryName,$position);
    if($saveSuccess){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> <strong>".$signatoryName."</strong> SIGNATORY SUCCESSFULLY ADDED</div>";
        // End of CLEANTO ADD STAFF
    }else{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>FAILED!</strong> FAILED TO ADD SIGNATORY\n
				</div>\n";
    }

    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/addSignatory.php");
}

if(isset($_POST['editSignatory'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    $signatoryId=$_POST['signatoryIdToEdit'];
    $con = connectDb();
    $sql = "SELECT * FROM `client_signatory` WHERE `id` = '$signatoryId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $_SESSION['signatoryIdEdit'] = $row['id'];
    $_SESSION['nameEdit'] = $row['name'];
    $_SESSION['positionEdit'] = $row['position'];
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/editSignatory.php");

}

if(isset($_POST['removeSignatory'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE SIGNATORY \n
			</div>\n
			";
    $con=connectDb();
    $signatoryId=$_POST['signatoryIdToEdit'];
    $saveSuccess=deleteSignatory($con,$signatoryId);
    if($saveSuccess){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SINGATORY SUCCESSFULLY REMOVED \n
			</div>\n";

    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/viewSignatory.php");

}

if(isset($_POST['editSignatoryProcess'])){
    $lastId = $_SESSION['signatoryIdEdit'];
    $currentId =$_POST['signatoryId'];
    $name=$_POST['name'];
    $position=$_POST['position'];

    $sql = "UPDATE `client_signatory`
            SET `name`='$name',`position`='$position', id='$currentId' WHERE `id`='$lastId'";
    $result = mysqli_query(connectDb(),$sql);
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/viewSignatory.php");
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> SIGNATORY INFORMATIONS SUCCESSFULLY UPDATED</div>";
}

    function organizationSignatoryListTableEditable(){
        $con=connectDb();

        $table="<div class='table-responsive table-stripped table-bordered'>\n";
        $table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
        $table.="<thead class='thead-dark'>\n";
        $table.="<tr>\n";
        $table.=	"<th>\n";
        $table.=		"Id\n";
        $table.=	"</th>\n";
        $table.=	"<th>\n";
        $table.=		"Company\n";
        $table.= 	"</th>\n";
        $table.=	"<th>\n";
        $table.=		"Signatory Name\n";
        $table.= 	"</th>\n";
        $table.=	"<th>\n";
        $table.=		"Position Name\n";
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
        $dataList=fetchSignatoryList($con);
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
            $table.=		$data['company'];
            $table.=	"</td>";
            $table.=	"<td>";
            $table.=		$data['name'];
            $table.=	"</td>";
            $table.=	"<td>";
            $table.=		$data['position'];
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