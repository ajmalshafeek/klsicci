<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
    define('FS_METHOD', 'direct');
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/store_banner.php");

if (isset($_POST['addBanner'])) {

    //$description = $_POST['description']; kiv
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD STORE BANNER \n
  </div>\n";
    $feedback = false;
    $con = connectDb();

    $img_file = "";
    $startDate = "";
    $endDate = "";

    if (isset($_POST['start'])) {
        $startDate = $_POST['start'];
    }
    if (isset($_POST['end'])) {
        $endDate = $_POST['end'];
    }

    $img_file = $_FILES["path"]["name"];
    $folderName = "../resources/2/storebanner/";
    if (!file_exists($folderName)) {
        mkdir($folderName, '0755', true);
    }
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    $filePath = "";
    $ext = "";
    $msg = "";
    if ($img_file == "") {
        $msg = "Attach an image";
    } elseif ($_FILES["path"]["size"] <= 0) {
        $msg = "Image is not proper.";
    } else {
        $ext = strtolower(end(explode(".", $img_file)));
        if (!in_array($ext, $validExt)) {
            $msg = "Not a valid image file";
        } else {

            // Generate a unique name for the image
            // to prevent overwriting the existing image
            $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;

            if (move_uploaded_file($_FILES["path"]["tmp_name"], $filePath)) {
                $filePath = substr($filePath, 2);
            } else {
                $msg = "Problem in uploading file";
            }
        }
    }

    if ($msg === "") {
        $feedback = insertStoreBanner($con, $filePath, $startDate, $endDate);
    } else {
        $feedback = false;
        $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>
  <strong>FAILED!</strong> " . $msg . "</div>";
    }
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> PRODUCT IS SUCCESSFULLY ADDED \n
    </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/store/addBanner.php");
}

function bannerListTable()
{
    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
    $con = connectDb();
    $dataList = fetchBannerListAll($con);
    $table = "";
    if (empty($dataList)) {
        $table = "<center><h5>No Record Found</h5></center>";
    } else {
        $table = "<div class='table-responsive'>\n";
        $table .= "<table  class='table' id='dataTableBanner' width='100%' cellspacing='0' >\n";
        $table .= "<thead class='thead-dark'>\n";
        $table .= "<tr>\n";
        $table .= "<th>\n";
        $table .= "Sr No.\n";
        $table .= "</th>\n";
        $table .= "<th>\n";
        $table .= "Image\n";
        $table .= "</th>\n";
        $table .= "<th>\n";
        $table .= "Start Date\n";
        $table .= "</th>\n";
        $table .= "<th>\n";
        $table .= "End Date\n";
        $table .= "</th>\n";
        $table .=  "<th>\n";
        $table .=  "Action\n";
        $table .=  "</th>\n";
        $table .= "</tr>\n";
        $table .= "</thead >\n";
        $table .= "<tbody>";
        $i = 1;
        foreach ($dataList as $data) {
            $table .= "<tr onclick='editProduct(" . $data['id'] . ")' ";
            " data-toggle='modal' data-target='#productEditModal' ";

            if ($i % 2 == 0) {
                $table .= "style='background-color:#a9ffdd6b;cursor:pointer;'";
            } else {
                $table .= "style='background-color:#bde8ff8c;cursor:pointer;'";
            }
            $table .= ">";
            $table .= "<td>\n";
            $table .= $i;
            $table .= "</td>\n";
            $table .=  "<td style='font-weight:bold'>";
            $table .=    "<img src='https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/" . $data['path'] . "' width='150px' >";
            $table .=  "</td>";
            $table .=  "<td>";
            $table .=  $data['start'];
            $table .=  "</td>";
            $table .= "<td>";
            $table .= $data['end'];
            $table .= "</td>";
            $table .= "<td>";
            $table .= "<div class='dropdown'>";
            $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>OPTION</button>";
            $table .= "<div class='dropdown-menu'>";
            $table .= "<button type='button' data-toggle='modal' data-target='#bannerEditModal' class='dropdown-item' onclick='editBanner(" . $data['id'] . ")' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
            $table .= "	</div> </div>";
            $table .= "</td>";
            $table .= "</tr>";
            $i++;
        }
        $table .= "</tbody>";
        $table .= "</table>";
        $table .= "</div>";
    }
    echo $table;
}

if (isset($_POST['bannerToDelete'])) {
    $con = connectDb();
    echo $bannerId = $_POST['bannerToDelete'];
    $success = deleteClientProductById($con, $bannerId);
    if (!$success) {
        $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE BANNER\n</div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/client/editClient.php");
}

if (isset($_GET['bannerData'])) {
    $con = connectDb();
    $bannerId = $_GET['bannerData'];
    $data = fetchBannerListById($con, $bannerId);
    echo json_encode($data);
}

if (isset($_POST['editBanner'])) {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO UPDATE BANNER \n
   </div>\n";
    $con = connectDb();
    $bannerId = $_POST['bannerIdToEdit'];
    $bannerPath = "";
    if (isset($_POST['path'])) {
        $bannerPath = $_POST['path'];
    }
    $startDate = "";
    if (isset($_POST['start'])) {
        $startDate = $_POST['start'];
    }
    $endDate = "";
    if (isset($_POST['end'])) {
        $endDate = $_POST['end'];
    }
    $uploadDocName = "";


    $img_file = "";
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    $filePath = "";
    $ext = "";
    $msg = "";

    if (isset($_FILES['path'])) {
        $img_file = $_FILES["path"]["name"];
        if ($img_file == "") {
            $msg = "Attach an image";
        } elseif ($_FILES["path"]["size"] <= 0) {
            $msg = "Image is not proper.";
        } else {
            $ext = strtolower(end(explode(".", $img_file)));
            if (!in_array($ext, $validExt)) {
                $msg = "Not a valid image file";
            } else {
                $folderName = "../resources/2/storebanner/";
                if (!file_exists($folderName)) {
                    mkdir($folderName, '0755', true);
                }
                // Generate a unique name for the image
                // to prevent overwriting the existing image
                $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;

                if (move_uploaded_file($_FILES["path"]["tmp_name"], $filePath)) {
                    $filePath = substr($filePath, 2);
                } else {
                    $msg = "Problem in uploading file";
                }
            }
        }
    }

    $feedback = updateBannerById($con, $filePath, $startDate, $endDate, $bannerId);

    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>STORE BANNER IS SUCCESSFULLY UPDATED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/store/viewBanner.php");
}

if (isset($_POST['editBannerId'])) {
    $bannerId = $_POST['bannerIdToEdit'];
    $_SESSION['bannerIdToEdit'] = $bannerId;
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/store/editBanner.php");
}

if (isset($_POST['removeBanner'])) {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE BANNER \n
   </div>\n";
    $con = connectDb();
    $bannerId = $_POST['bannerIdToEdit'];
    $feedback = deleteBannerById($con, $bannerId);
    if ($feedback) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>BANNER IS SUCCESSFULLY DELETED \n
     </div>\n";
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/store/viewBanner.php");
}

function fetchBannerById($bannerId)
{
    $con = connectDb();
    $data = fetchBannerListById($con, $bannerId);
    return $data;
}
