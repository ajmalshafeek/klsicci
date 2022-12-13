<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/registration.php");

if (isset($_POST['regClientByForm'])) {
    ob_start();
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $email1 = $_POST['email1'];
    $country = $_POST['country'];

    $register = $_POST['register'];
    $business = $_POST['business'];
    $incorp = $_POST['incorp'];
    $financialYear = $_POST['financialYear'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $postalCode = $_POST['postalCode'];
    $state = $_POST['state'];
    $clientFaxNo = $_POST['clientFaxNo'];
    $clientContacNo = $_POST['clientContactNo'];
    $clientContacNo = $_POST['title'];
    $clientContacNo = $_POST['description'];
    $clientContacNo = $_POST['price'];
    $lname = $_POST['lname'];
    $date=date("Y-m-d hh:mm:ss");
    $duration=$_POST['duration'];
    $transactionID=rand();
    $memebershipStatus=0;
    $memebershipPaid=0;

    $clientType = 1;

    $status = 2;
    $emailstr = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email1str = filter_var($email1, FILTER_SANITIZE_EMAIL);
    ob_clean();
    ob_end_clean();
    if ($email1str === $emailstr) {
        $con = connectDb();
        $bool = checkIfCompanyExist($con, $company);
        if ($bool) {
            $var = checkIfEmailExist($con, $email);
            if ($var) {

                $request = newStoreAccountRequest($con, $name, $company, $email, $country, $status,$register,$business,$incorp,$financialYear,$address1,$address2,$city,$postalCode,$state,$clientFaxNo,$clientContacNo,$clientType);
                if ($request>0) {

                    $_SESSION["msgTitle"]="Thanks for your interest";
                    $_SESSION['message'] = "<div class='alert alert-success' style='margin-right: auto;margin-right: auto'>Succesfully requested  registration. Kindly do wait for your approval.</div>";
                    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
                } else {
                    $_SESSION['message'] = "<div class='alert alert-warning' style='margin-right: auto;margin-right: auto'>Request not send</div>";
                    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
                }
            } else {
                $_SESSION['message'] = "<div class='alert alert-warning' style='margin-right: auto;margin-right: auto'>Email already exists</div>";
                header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
            }
        } else {
            $_SESSION['message'] = "<div class='alert alert-warning' style='margin-right: auto;margin-right: auto'>Company already exists</div>";
            header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
        }
    } else {
        $_SESSION['message'] = "<div class='alert alert-warning' style='margin-right: auto;margin-right: auto'>Email not match</div>";
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
    }

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/registration-for-membership.php");
}


function dsNewRequestTable()
{
    $con = connectDb();
    $sdata="";

    $dataList = fetchRequestListForDS($con);
    if ($dataList == null) {
        echo "<center><h5 style='padding: 20px'>No Request</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .=  "<th>\n";
        $table .=    "SR No.\n";
        $table .=  "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Name\n";
        $table .=   "</th>\n";
        $table .=   "<th>\n";
        $table .=     "Company\n";
        $table .=   "</th>\n";

        $table .=   "<th>\n";
        $table .=     "Date\n";
        $table .=   "</th>\n";

        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $i=1;
        foreach ($dataList as $data) {

            $table .= "<tr>";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    $i;
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['name'];
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=    $data['company'];
            $table .=  "</td>";

            $table .=  "<td>";
            $date_time_Obj = date_create($data['requestTime']);
            //formatting the date/time object
            $format = date_format($date_time_Obj, "y-d-m");
            $table .=  $format  ;

            $table .=  "</td>";
            $table .= "</tr>";
            $i++;
        }
        $table .= "</table>";
        $table .= "</div>";

        echo $table;
    }
}
if(isset($_GET['newrequest'])){
    $con = connectDb();
$data = fetchRequestCountForDS($con);
    $_SESSION['newrequestNor']=$data;
echo $data;
}
if(isset($_GET['potential'])){
    $con = connectDb();
$data = fetchPotentialCountForDS($con);
$_SESSION['potentialNor']=$data;
   echo $data;
}


if (isset($_POST['removeNewClientRequest'])) {
    $con = connectDb();
     $clientId = $_POST['clientIdToEdit'];
    $success = deletNewClientById($con, $clientId);

    if (!$success) {
        $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO REMOVE CLIENT'S INFORMATIONS\n
			</div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong>REMOVE CLIENT'S INFORMATIONS\n</div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/client/newClientRequest.php");
}

