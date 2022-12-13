<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {
  session_name($config['sessionName']);
  session_start();
  define('FS_METHOD', 'direct');
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/product.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php");

if (isset($_POST['addProduct'])) {

  //$description = $_POST['description']; kiv
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD PRODUCT \n
  </div>\n";
  $feedback = false;
  $con = connectDb();

  $brand = "";
  $model = "";
  $producttype = "";
  $remarks = "";
  $serialNum = "";


  if (isset($_POST['brand'])) {
    $brand = $_POST['brand'];
  }
  if (isset($_POST['model'])) {
    $model = $_POST['model'];
  }
  if (isset($_POST['producttype'])) {
    $producttype = $_POST['producttype'];
  }
  if (isset($_POST['serialNum'])) {
    $serialNum = $_POST['serialNum'];
  }
  if (isset($_POST['remarks'])) {
    $remarks = $_POST['remarks'];
  }

  $cid = "";
  if (isset($_POST['clientCompanyId'])) {
    $cid = $_POST['clientCompanyId'];
  }
  $uploadDocName = "";
  if ($_SESSION['orgType'] == 3) {

    if (file_exists($_FILES['doc']['tmp_name']) || is_uploaded_file($_FILES['doc']['tmp_name'])) {
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
      $name = $_FILES['doc']['name'];

      $temp_name = $_FILES['doc']['tmp_name'];
      $size = $_FILES['doc']['size'];
      if ($_FILES['doc']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";

        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/product.php");
      }
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $_FILES['doc']['tmp_name']);
      $ok = FALSE;
      $extension = "";
      switch ($mime) {
        case 'application/pdf':
          $extension = ".pdf";
          break;
        case 'application/msword':
          $extension = ".doc";
          break;
        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
          $extension = ".docx";
          break;
        case 'application/msexcel':
          $extension = ".xls";
          break;
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $extension = ".xlsx";
          break;
        default:
          $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

					<strong>UNKNOWN FILE!</strong> UPLOAD FILE IS FAILED</div>";
      }


      $uploadDocName = time() . "" . $extension;
      $path = "/resources/2/products/";
      $docDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
      if (!file_exists($docDirectory)) {
        mkdir($docDirectory, 0755, TRUE);
        copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $docDirectory . '/index.php');
      }
      move_uploaded_file($temp_name, $docDirectory . "/$uploadDocName");
    }
  }

  if ($_SESSION['orgType'] == 7) {
    /**
     *  Org type 7
     */
    $location = $_POST['location'];
    $ponumber = $_POST['ponumber'];
    $capacity = "";
    if (isset($_POST['capacity'])) {
      $capacity = $_POST['capacity'];
    }
    //new addition
    $faild = array();
    $output = "";
    if (is_array($serialNum)) {
      $count = 0;
      $st = "";
      foreach ($serialNum as $value) {

        if (checkSrNo($con, $value)) {

          $st = insertProductWhouse($con, $brand, $model, $capacity, $producttype, $location, $ponumber, $value, $remarks);
          $faild[] = $st;
          if ($st) {
            $output .= "SR NO: " . $value . " SUCCESSFULLY ADDED<br />";
          } else {
            $output .= "SR NO: " . $value . " FAILED TO ADDED<br />";
          }
        } else {
          $faild[] = 0;
          $output .= "SR NO: " . $value . " FAILED TO ADDED EXISTS<br />";
        }
        $count++;
      }

      $_SESSION['feedback'] = "<div class='alert alert-info' role='alert'>\n
     <strong>INFO!</strong> PRODUCT STATUS <br />" . $output . " \n
     </div>\n";
    } else {
      if (checkSrNo($con, $serialNum)) {
        $feedback = insertProductWhouse($con, $brand, $model, $capacity, $producttype, $location, $ponumber, $serialNum, $remarks);
      } else {
        $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> PRODUCT SERIAL NUMBER ALREADY EXIST\n
  </div>\n";
      }
    }

    // finish addtion


  } else if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
    /**
     *  Org type 8
     */
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sku = $_POST['sku'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $img_file = $_FILES["img1"]["name"];
    $img_file1 = $_FILES["img2"]["name"];
    $img_file2 = $_FILES["img3"]["name"];
    $folderName = "../resources/2/products/images/";
    if (!file_exists($folderName)) {
      mkdir($folderName, '0755', true);
    }
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    $filePath = "";
    $filePath1 = "";
    $filePath2 = "";
    $ext = "";
    $msg = "";
    $msg1 = "";
    $msg2 = "";
    if ($img_file == "") {
      $msg = "Attach an image";
    } elseif ($_FILES["img1"]["size"] <= 0) {
      $msg = "Image is not proper.";
    } else {
      $ext = strtolower(end(explode(".", $img_file)));
      if (!in_array($ext, $validExt)) {
        $msg = "Not a valid image file";
      } else {

        // Generate a unique name for the image
        // to prevent overwriting the existing image
        $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;

        if (move_uploaded_file($_FILES["img1"]["tmp_name"], $filePath)) {
          $filePath = substr($filePath, 2);
        } else {
          $msg = "Problem in uploading file";
        }
      }
    }
    if (isset($_FILES['img2'])) {
      $ext = "";
      if ($img_file1 == "") {
        $msg1 = "Attach an image";
      } elseif ($_FILES["img1"]["size"] <= 0) {
        $msg1 = "Image is not proper.";
      } else {
        $ext = strtolower(end(explode(".", $img_file1)));
        if (!in_array($ext, $validExt)) {
          $msg1 = "Not a valid image file";
        } else {
          // Generate a unique name for the image
          // to prevent overwriting the existing image
          $filePath1 = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;
          if (move_uploaded_file($_FILES["img2"]["tmp_name"], $filePath1)) {
            $filePath1 = substr($filePath1, 2);
          } else {
            $msg1 = "Problem in uploading file";
          }
        }
      }
    }

    if (isset($_FILES['img3'])) {
      $ext = "";
      if ($img_file2 == "") {
        $msg2 = "Attach an image";
      } elseif ($_FILES["img3"]["size"] <= 0) {
        $msg2 = "Image is not proper.";
      } else {
        $ext = strtolower(end(explode(".", $img_file2)));
        if (!in_array($ext, $validExt)) {
          $msg2 = "Not a valid image file";
        } else {
          // Generate a unique name for the image
          // to prevent overwriting the existing image
          $filePath2 = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;
          if (move_uploaded_file($_FILES["img3"]["tmp_name"], $filePath2)) {
            $filePath2 = substr($filePath2, 2);
          } else {
            $msg2 = "Problem in uploading file";
          }
        }
      }
    }

    if ($msg === "") {
      $feedback = insertProductStore($con, $title, $description, $brand, $model, $producttype, $sku, $quantity, $price, $filePath, $filePath1, $filePath2, $uploadDocName, $cid);
    } else {
      $feedback = false;
      $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>
  <strong>FAILED!</strong>" . $msg . "</div>";
    }
  } else {

    if (checkSrNo($con, $serialNum)) {
      $feedback = insertProduct($con, $brand, $model, $producttype, $serialNum, $remarks);
    } else {
      $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> PRODUCT SERIAL NUMBER ALREADY EXIST\n
  </div>\n";
    }
  }
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PRODUCT IS SUCCESSFULLY ADDED \n
    </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/product.php");
}


function productListTable()
{
  $extable = "";
  $con = connectDb();

  if ($_SESSION['orgType'] == 7) {
    $sdata = "";
    if (isset($_SESSION['sdata'])) {
      $sdata = $_SESSION['sdata'];
    }
    $dataList = fetchProductListSearch($con, $sdata);
    unset($_SESSION['sdata']);
  } else {
    $dataList = fetchProductListAll($con);
  }
  if ($dataList == null) {
    echo "<center><h5>No Record Found</h5></center>";
  } else {
    $table = "<div class='table-responsive'>\n";
    $table .= "<table  class='table' id='dataTableProduct' width='100%' cellspacing='0' >\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .= "<th>\n";
    $table .= "Sr No.\n";
    $table .= "</th>\n";
    if ($_SESSION['orgType'] != 3) {
      $table .= "<th>\n";
      $table .= "ID\n";
      $table .= "</th>\n";
    }
    if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
      $table .=  "<th>\n";
      $table .=    "Product Name\n";
      $table .=   "</th>\n";
      if ($_SESSION['orgType'] != 3) {
        $table .=  "<th>\n";
        $table .=    "SKU\n";
        $table .=   "</th>\n";
      }
    }
    if ($_SESSION['orgType'] != 3) {
      $table .= "<th>\n";
      $table .= "Brand\n";
      $table .= "</th>\n";
    }
    $table .=   "<th>\n";
    if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
      $table .=     "Category\n";
    } else {
      $table .=     "Type\n";
    }

    $table .=   "</th>\n";
    if ($_SESSION['orgType'] != 3) {
      $table .= "<th>\n";
      $table .= "Model\n";
      $table .= "</th>\n";
    }
    if ($_SESSION['orgType'] == 7) {

      $table .=   "<th>\n";
      $table .=     "Capacity\n";
      $table .=   "</th>\n";

      $table .=   "<th>\n";
      $table .=     "PO NO.\n";
      $table .=   "</th>\n";

      $table .=   "<th>\n";
      $table .=     "Date\n";
      $table .=   "</th>\n";
    }
    $table .=   "<th>\n";
    if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
      $table .=     "Qty\n";
    } else {
      $table .=     "Serial NO.\n";
    }
    $table .=   "</th>\n";

    if ($_SESSION['orgType'] == 7) {
      $extable .= $table;

      $temp =   "<th>\n";
      $temp .=     "Status.\n";
      $temp .=   "</th>\n";

      $temp .=   "<th>\n";
      $temp .=     "Do Number.\n";
      $temp .=   "</th>\n";

      $temp .=   "<th>\n";
      $temp .=     "DO Date.\n";
      $temp .=   "</th>\n";

      $temp .=   "<th>\n";
      $temp .=     "Client\n";
      $temp .=   "</th>\n";

      $temp .=   "<th>\n";
      $temp .=     "In Charge.\n";
      $temp .=   "</th>\n";

      $extable .= $temp;
    } else {
      $extable .= $table;
    }
    if ($_SESSION['orgType'] == 3) {
      $temp =   "<th>\n";
      $temp .=     "Client\n";
      $temp .=   "</th>\n";
      $extable .= $temp;
    }
    $temp .=   "<th>\n";
    $temp .=     "Action\n";
    $temp .=   "</th>\n";
    $table .= $temp;
    $temp = "</tr>\n";
    $temp .= "</thead >\n";

    $orgId = $_SESSION['orgId'];

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientUser.php");
    $table .= $temp;
    $extable .= $temp;
    $i = 1;
    foreach ($dataList as $data) {
      //$clientAdminDetails=fetchClientAdminDetails($con,$data['id'],$orgId);


      // code old
      $temp = "<tr onclick='editProduct(" . $data['id'] . ")' ";
      " data-toggle='modal' data-target='#productEditModal' ";

      if ($i % 2 == 0) {
        $temp .= "style='background-color:#a9ffdd6b;cursor:pointer;'";
      } else {
        $temp .= "style='background-color:#bde8ff8c;cursor:pointer;'";
      }
      // $temp = "<tr";

      $temp .= ">";
      $temp .= "<td>\n";
      $temp .= $i;
      $temp .= "</td>\n";

      if ($_SESSION['orgType'] != 3) {
        $temp .=  "<td style='font-weight:bold'>";
        $temp .=    $data['pid'];
        $temp .=  "</td>";
      }
      if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
        $temp .=  "<td>";
        $temp .=    $data['title'];
        $temp .=  "</td>";
        if ($_SESSION['orgType'] != 3) {
          $temp .=  "<td>";
          $temp .=    $data['sku'];
          $temp .=  "</td>";
        }
      }
      if ($_SESSION['orgType'] != 3) {
        $temp .= "<td>";
        $temp .= $data['brand'];
        $temp .= "</td>";
      }
      $temp .=  "<td>";
      $temp .=    $data['producttype'];
      $temp .=  "</td>";
      if ($_SESSION['orgType'] != 3) {
        $temp .= "<td>";
        $temp .= $data['model'];
        $temp .= "</td>";
      }

      if ($_SESSION['orgType'] == 7) {

        $temp .=  "<td>";
        $temp .=    $data['capacity'];
        $temp .=  "</td>";

        $temp .=  "<td>";
        $temp .=    $data['ponumber'];
        $temp .=  "</td>";

        $temp .=  "<td>";
        $temp .=    substr($data['date'], 0, 10);
        $temp .=  "</td>";
      }
      $temp .=  "<td>";
      if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
        $temp .=    $data['quantity'];
      } else {
        $temp .=    $data['serialNum'];
      }
      $temp .=  "</td>";
      $table .= $temp;
      $extable .= $temp;
      if ($_SESSION['orgType'] == 3) {
        $temp =   "<td>";
        $temp .=     $data['cname'];
        $temp .=   "</td>";
        $extable .= $temp;
      }
      $temp .= "<td>";

      $temp .= "<div class='dropdown'>";
      $temp .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";
      $temp .= "<button type='button' data-toggle='modal' data-target='#productEditModal' class='dropdown-item' onclick='editProduct(" . $data['pid'] . ")' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
      $temp .= "	</div> </div>";
      $temp .= "</td>";
      $table .= $temp;

      if ($_SESSION['orgType'] == 7) {
        $temp =  "<td>";
        $temp .=    $data['status'];
        $temp .=  "</td>";
        $temp .=  "<td>";
        $temp .=    $data['donumber'];
        $temp .=  "</td>";
        $temp .=  "<td>";
        $temp .=    $data['dodate'];
        $temp .=  "</td>";
        $temp .=  "<td>";
        $temp .=    $data['doto'];
        $temp .=  "</td>";
        $temp .=  "<td>";
        $temp .=    $data['doincharge'];
        $temp .=  "</td>";

        $extable .= $temp;
      }
      $temp = "</tr>";
      $table .= $temp;
      $extable .= $temp;
      $i++;
    }
    $temp = "</table>";
    $temp .= "</div>";
    $table .= $temp;
    $extable .= $temp;
    $_SESSION['productex'] = $extable;
    echo $table;
  }
}
function dsTable()
{
  $con = connectDb();
  $sdata = "";
  $dataList = fetchProductStoreListSearch($con, $sdata);
  if ($dataList == null) {
    echo "<center><h5>No Record Found</h5></center>";
  } else {
    $table = "<div class='table-responsive'>\n";
    $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th>\n";
    $table .=    "Order ID\n";
    $table .=  "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Name\n";
    $table .=   "</th>\n";

    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Total\n";
    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Order Date\n";
    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Status\n";
    $table .=   "</th>\n";

    $table .= "</tr>\n";
    $table .= "</thead >\n";
    $i = 0;
    foreach ($dataList as $data) {
      if ($i < 10) {
        $i++;
      } else {
        break;
      }
      $table .= "<tr>";
      $table .=  "<td style='font-weight:bold'>";
      $table .=    $data['id'];
      $table .=  "</td>";
      $table .=  "<td>";
      $table .=    $data['name'];
      $table .=  "</td>";

      $table .=  "<td>";
      $table .=    "RM " . $data['grandtotal'];
      $table .=  "</td>";

      $dateTime = date_create($data['orderdate']);

      $table .=  "<td>";
      $table .=    date_format($dateTime, 'd/m/Y');
      $table .=  "</td>";

      $table .=  "<td>";
      switch ($data['status']) {
        case 1: {
            $table .= "Received";
            break;
          }
        case 2: {
            $table .= "Processed";
            break;
          }
        case 3: {
            $table .= "Shipped";
            break;
          }
        case 4: {
            $table .= "Delivered";
            break;
          }
        case -1: {
            $table .= "Canceled";
            break;
          }
        default: {
            $table .= "Pending";
          }
      }
      $table .=  "</td>";
      $table .= "</tr>";
    }
    $table .= "</table>";
    $table .= "</div>";

    echo $table;
  }
}
function productStoreListTable()
{
  $con = connectDb();

  $sdata = "";
  if (isset($_SESSION['sdata'])) {
    $sdata = $_SESSION['sdata'];
    $dataList = fetchProductStoreListSearch($con, $sdata);
    unset($_SESSION['sdata']);
  } else {
    $dataList = fetchProductStoreListSearch($con, $sdata);
  }
  if ($dataList == null) {
    echo "<center><h5>No Record Found</h5></center>";
  } else {
    $table = "<div class='table-responsive'>\n";
    $table .= "<table  class='table' id='OrderTable' width='100%' cellspacing='0' >\n";
    $table .= "<thead class='thead-dark'>\n";
    $table .= "<tr>\n";
    $table .=  "<th>\n";
    $table .=    "Order ID\n";
    $table .=  "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Name\n";
    $table .=   "</th>\n";
    $table .=  "<th>\n";
    $table .=    "Email\n";
    $table .=   "</th>\n";

    $table .=  "<th>\n";
    $table .=    "Phone\n";
    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Address\n";

    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Total\n";
    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Order Date\n";
    $table .=   "</th>\n";

    $table .=   "<th>\n";
    $table .=     "Status\n";
    $table .=   "</th>\n";

    $extable = $table;

    $temp =   "<th>\n";
    $temp .=     "Action\n";
    $temp .=   "</th>\n";
    $table .= $temp;
    $temp = "</tr>\n";
    $temp .= "</thead >\n";

    $i = 1;
    $orgId = $_SESSION['orgId'];

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientUser.php");
    $table .= $temp;
    $extable .= $temp;
    $tempStatus = "";
    $tempColor = "";
    $pa = "";
    foreach ($dataList as $data) {
      switch ($data['status']) {
        case 1: {
            $tempStatus = "Received";
            $tempColor = "background-color:#F9F9F9;";
            break;
          }
        case 2: {
            $tempStatus = "Processed";
            $tempColor = "background-color:#ffdead;";
            break;
          }
        case 3: {
            $tempStatus = "Shipped";
            $tempColor = "background-color:#bdb76b;";
            break;
          }
        case 4: {
            $tempStatus = "Delivered";
            $tempColor = "background-color:#FFF5EB;";
            break;
          }
        case -1: {
            $tempStatus = "Canceled";
            $tempColor = "background-color:#f08080;";
            break;
          }
        default: {
            $tempStatus = "Pending";
            $tempColor = "background-color:#a99a86;";
          }
      }
      $pa = getphoneaddress($con, $data['cid']);
      $temp = "<tr ";
      $temp .= "style='" . $tempColor . "'";
      $temp .= " >";

      $temp .=  "<td style='font-weight:bold'>";
      $temp .=    $data['id'];
      $temp .=  "</td>";
      $temp .=  "<td>";
      $temp .=    $data['name'];
      $temp .=  "</td>";
      $temp .=  "<td>";
      $temp .=    $data['email'];
      $temp .=  "</td>";

      $temp .=  "<td>";
      if (empty($data['phone'])) {
        $temp .= $pa['contactNo'];
      } else {
        $temp .= $data['phone'];
      }
      $temp .=  "</td>";

      $temp .=  "<td>";
      if (empty($data['address'])) {
        $temp .= $pa['address1'] . " " . $pa['address2'] . ", " . $pa['city'] . " " . $pa['postalCode'] . ", " . $pa['state'] . " " . $pa['country'];
      } else {
        $temp .=    $data['address'];
      }
      $temp .=  "</td>";

      $temp .=  "<td>";
      $temp .=    "RM " . $data['grandtotal'];
      $temp .=  "</td>";

      $dateTime = date_create($data['orderdate']);

      $temp .=  "<td>";
      $temp .=    date_format($dateTime, 'd/m/Y');
      $temp .=  "</td>";

      $temp .=  "<td>";
      $temp .= "<span style='padding:5px;border-radius:3px'>" . $tempStatus . "</span>";
      $temp .=  "</td>";

      $table .= $temp;
      $extable .= $temp;
      $temp = "<td>";
      if ($data['status'] != 4) {
        $temp .= "<div class='dropdown'>";
        $temp .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";
        $temp .= "<button type='button' data-toggle='modal' data-target='#productEditModal' class='dropdown-item' onclick='updateStoreOrder(" . $data['id'] . ")' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
        $temp .= "	</div> </div>";
      } else {
        $temp .= "Completed";
      }
      $temp .= "</td>";
      $table .= $temp;


      $temp = "</tr>";
      $table .= $temp;
      $extable .= $temp;
    }
    $temp = "</table>";
    $temp .= "</div>";
    $table .= $temp;
    $extable .= $temp;
    $_SESSION['productex'] = $extable;
    echo $table;
  }
}


function productList()
{
  $con = connectDb();
  $dataList = fetchProductListAll($con);
  return $dataList;
}

function clientproductListById($clientId)
{
  $con = connectDb();
  $dataList = fetchClientProductListById($con, $clientId);
  return $dataList;
}

function productListById($productId)
{
  $con = connectDb();
  $dataList = fetchProductListById($con, $productId);
  return $dataList;
}

if (isset($_POST['productToDelete'])) {
  $con = connectDb();
  echo $clientproductId = $_POST['productToDelete'];
  $success = deleteClientProductById($con, $clientproductId);
  if (!$success) {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE INFORMATIONS\n</div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/client/editClient.php");
}

if (isset($_GET['productData'])) {
  $con = connectDb();
  $productId = $_GET['productData'];
  $data = fetchProductListById($con, $productId);
  echo json_encode($data);
}
if (isset($_GET['storeOrderNotes'])) {
  $con = connectDb();
  $oid = $_GET['storeOrderNotes'];
  $data = fetchStoreOrderNotes($con, $oid);

  echo json_encode($data);
}
if (isset($_POST['storeReportGenerate'])) {
  ob_start();
  $timeCategory = $_POST['timeCategory'];

  $datesearch = "";
  $date = "";
  $name = "";
  $oid = "";
  $status = "";
  if (isset($_POST['status']) && !empty($_POST['status'])) {
    if ($_POST['status'] != 0) {
      $status = " AND status=" . $_POST['status'];
    }
  }
  $orderby = "";
  if (isset($_POST['name']) && !empty($_POST['name'])) {
    $name = " AND `name` LIKE '%" . $_POST['name'] . "%'";
  }
  if (isset($_POST['oid']) && !empty($_POST['oid'])) {
    $oid = " AND `id`=" . $_POST['oid'] . "";
  }
  if (isset($_POST['orderby'])) {
    $orderby = " " . $_POST['orderby'];
  }
  $de = false;
  if (isset($_POST['rtype']) && $_POST['rtype'] == 1) {
    $de = true;
  }

  $date = " AND `orderdate` LIKE '";
  if ($timeCategory == 0) {
    $datesearch = $_POST['dateMonth'];
  } elseif ($timeCategory == 1) {
    $datesearch = $_POST['dateYear'];
  }
  $con = connectDb();
  $qvar = $oid . $name . $date . $datesearch . "%'" . $status . $orderby;
  $reportDetails = fetchStoreReportList($con, $qvar);
  $table = '<table  id="report" class="display" style="width:100%"><thead><tr><th>NO</th>';
  $table = $table . '<th>Order ID</th>';
  if ($de) {
    $table = $table . '<th>Category</th>';
    $table = $table . '<th>Description</th>';
    $table = $table . '<th>Client Name</th>';
    $table = $table . '<th>Product ID</th>';
    $table = $table . '<th>Stock</th>';
    $table = $table . '<th>Qty</th>';
  } else {
    $table = $table . '<th>Name</th>';
    $table = $table . '<th>Email</th>';

    $table = $table . '<th>Address</th>';
    $table = $table . '<th>Phone</th>';
    $table = $table . '<th>Total</th>';
  }

  $table = $table . '<th>Status</th>';
  $table = $table . '<th>Order Date</th>';
  if ($de) {
    $table = $table . '<th>Client Notes</th>';
    $table = $table . '<th>Notes</th>';
  }
  $table = $table . '</tr></thead>';

  $rowscount = 0;

  $rownum = 1;

  $table = $table . '<tbody>';

  $salarycount = 0;
  $staffcount = 1;
  $tempStaff = 0;
  $tempCount = 0;

  foreach ($reportDetails as $data) {
    $statusVal = "";
    switch ($data['status']) {
      case (-1): {
          $statusVal = "Canceled";
          break;
        }
      case (1): {
          $statusVal = "Received";
          break;
        }
      case (2): {
          $statusVal = "Processed";
          break;
        }
      case (3): {
          $statusVal = "Shipped";
          break;
        }
      case (4): {
          $statusVal = "Delivered";
          break;
        }
    }
    $newdate = date_create($data['orderdate']);

    if ($de) {

      $itemsDetails = fetchStoreItemsListReport($con, $data['id']);
      foreach ($itemsDetails as $item) {
        $table = $table . '<tr><td>' . $rownum . '</td><td>' . $data['id'] . '</td>';
        $table = $table . '<td>' . $item['producttype'] . '</td>';
        $table = $table . '<td>' . $item['description'] . '</td>';
        $table = $table . '<td>' . $data['name'] . '</td>';
        $table = $table . '<td>' . $item['pid'] . '</td>';
        $table = $table . '<td>' . $item['stock'] . '</td>';
        $table = $table . '<td>' . $item['qty'] . '</td>';
        $table = $table . '<td>' . $statusVal . '</td>';
        $table = $table . '<td>' . date_format($newdate, 'd/m/Y') . '</td>';
        $table = $table . '<td>' . $data['notes'] . '</td>';
        $table = $table . '<td>' . $data['manager_note'] . '</td>';
        $table = $table . '</tr>';
        $rowscount++;
        $rownum++;
      }
    } else {
      $table = $table . '<tr><td>' . $rownum . '</td><td>' . $data['id'] . '</td>';
      $table = $table . '<td>' . $data['name'] . '</td>';
      $table = $table . '<td>' . $data['email'] . '</td>';
      $table = $table . '<td>' . $data['address'] . '</td>';
      $table = $table . '<td>' . $data['phone'] . '</td>';
      $table = $table . '<td>' . $data['grandtotal'] . '</td>';
      $table = $table . '<td>' . $statusVal . '</td>';
      $table = $table . '<td>' . date_format($newdate, 'd/m/Y') . '</td>';
      $table = $table . '</tr>';
      $rowscount++;
      $rownum++;
    }
  }

  $table = $table . "" . $staffcount;
  $table = $table . '</td>';
  $table = $table . '</tr>';

  $table = $table . '</tbody></table>';

  if ($rowscount == 0) {
    $table = "<center><h3 style=\"color:#fff;background-color: #e91e63; padding:10px;\">No Record Found</h3></center>";
  }

  $_SESSION['datesearch'] = $datesearch;

  $_SESSION['storeReport'] = $table;

  // $_SESSION['attendanceTableExport'] = $table;
  ob_clean();
  ob_end_clean();
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/report.php");
}

if (isset($_GET['productOrderData'])) {
  $con = connectDb();
  $orderId = $_GET['productOrderData'];
  $dataList = fetchProductOrderListById($con, $orderId);
  $table = "No Item Found";
  if (sizeof($dataList) != 0) {

    $table = '<div class="table-responsive"><h4 class="text-center">Order ID: ' . $orderId . '</h4>';
    $table .= '<table class="table" id="dataTable" width="100%" cellspacing="0" >';
    $table .= '<thead class="thead-dark">';
    $table .= '<tr><th>Product Id</th><th>Title</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead><tbody>';
    foreach ($dataList as $item) {
      $table .= '<tr><td>' . $item['pid'] . '</td><td>' . $item['title'] . '</td><td>' . $item['price'] . '</td><td>' . $item['quantity'] . '</td><td>' . $item['total'] . '</td></tr>';
    }
    $table .= '</tbody></table></div>';
  }
  echo $table;
}


if (isset($_GET['productDataSent'])) {
  $con = connectDb();
  $donumber = $_GET['productDataSent'];
  $data = fetchProductSentListById($con, $donumber);
  //echo json_encode($data);
  echo $data;
}

if (isset($_POST['editProduct'])) {
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO EDIT PRODUCT \n
   </div>\n";
  $con = connectDb();
  $productId = $_POST['productIdToEdit'];
  $brand = "";
  if (isset($_POST['brand'])) {
    $brand = $_POST['brand'];
  }
  $model = "";
  if (isset($_POST['model'])) {
    $model = $_POST['model'];
  }
  $uploadDocName = "";
  if ($_SESSION['orgType'] == 3) {

    if (file_exists($_FILES['doc']['tmp_name']) || is_uploaded_file($_FILES['doc']['tmp_name'])) {
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

                  <strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";
      $name = $_FILES['doc']['name'];

      $temp_name = $_FILES['doc']['tmp_name'];
      $size = $_FILES['doc']['size'];
      if ($_FILES['doc']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
                  <strong>Failed!</strong> UPLOAD FILE IS FAILED</div>";

        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/product.php");
      }
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $_FILES['doc']['tmp_name']);
      $ok = FALSE;
      $extension = "";
      switch ($mime) {
        case 'application/pdf':
          $extension = ".pdf";
          break;
        case 'application/msword':
          $extension = ".doc";
          break;
        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
          $extension = ".docx";
          break;
        case 'application/msexcel':
          $extension = ".xls";
          break;
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $extension = ".xlsx";
          break;
        default:
          $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n

                  <strong>UNKNOWN FILE!</strong> UPLOAD FILE IS FAILED</div>";
      }


      $uploadDocName = time() . "" . $extension;
      $path = "/resources/2/products/";
      $docDirectory = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . $path;
      if (!file_exists($docDirectory)) {
        mkdir($docDirectory, 0755, TRUE);
        copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $docDirectory . '/index.php');
      }
      move_uploaded_file($temp_name, $docDirectory . "/$uploadDocName");
    }
  }

  $img_file = "";
  $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
  $filePath = "";
  $ext = "";
  $msg = "";

  if (isset($_FILES['img1'])) {
    $img_file = $_FILES["img1"]["name"];
    if ($img_file == "") {
      $msg = "Attach an image";
    } elseif ($_FILES["img1"]["size"] <= 0) {
      $msg = "Image is not proper.";
    } else {
      $ext = strtolower(end(explode(".", $img_file)));
      if (!in_array($ext, $validExt)) {
        $msg = "Not a valid image file";
      } else {
        $folderName = "../resources/2/products/images/";
        if (!file_exists($folderName)) {
          mkdir($folderName, '0755', true);
        }
        // Generate a unique name for the image
        // to prevent overwriting the existing image
        $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;

        if (move_uploaded_file($_FILES["img1"]["tmp_name"], $filePath)) {
          $filePath = substr($filePath, 2);
        } else {
          $msg = "Problem in uploading file";
        }
      }
    }
  }

  $producttype = $_POST['producttype'];
  $serialNum = "";
  $remarks = "";
  if ($_SESSION['orgType'] != 8 && $_SESSION['orgType'] != 3) {
    $serialNum = $_POST['serialNum'];
    $remarks = $_POST['remarks'];
  }

  $feedback = false;
  $location = "";
  $ponumber = "";
  $capacity = "";
  if ($_SESSION['orgType'] == 7) {
    $location = $_POST['location'];
    $ponumber = $_POST['ponumber'];
    if (isset($_POST['capacity'])) {
      $capacity = $_POST['capacity'];
    }

    $feedback = updateProductWhouseById($con, $brand, $producttype, $model, $capacity, $location, $ponumber, $serialNum, $remarks, $productId);
  } elseif ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
    $title = "";
    if (isset($_POST['title'])) {
      $title = $_POST['title'];
    }
    $des = "";
    if (isset($_POST['des'])) {
      $des = $_POST['des'];
    }
    $price = "";
    if (isset($_POST['price'])) {
      $price = $_POST['price'];
    }
    $qty = "";
    if (isset($_POST['qty'])) {
      $qty = $_POST['qty'];
    }
    $sku = "";
    if (isset($_POST['sku'])) {
      $sku = $_POST['sku'];
    }
    if ($_SESSION['orgType'] == 3) {
      $cid = "";

      if (isset($_POST['clientCompanyId'])) {
        $cid = $_POST['clientCompanyId'];
        $feedback = updateProductDocByID($con, $title, $des, $producttype, $qty, $price, $filePath, $uploadDocName, $cid, $productId);
      }
    } else {
      $feedback = updateProductStoreByID($con, $title, $des, $brand, $producttype, $model, $sku, $qty, $price, $productId);
    }
  } else {
    $feedback = updateProductById($con, $brand, $producttype, $model, $serialNum, $remarks, $productId);
  }
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>PRODUCT IS SUCCESSFULLY EDITED \n
     </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/viewProduct.php");
}





if (isset($_POST['editProductId'])) {
  $productId = $_POST['productIdToEdit'];
  $_SESSION['productIdToEdit'] = $productId;
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/editProduct.php");
}

if (isset($_POST['removeProduct'])) {
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE PRODUCT \n
   </div>\n";
  $con = connectDb();
  $productId = $_POST['productIdToEdit'];
  $feedback = deleteProductById($con, $productId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>PRODUCT IS SUCCESSFULLY DELETED \n
     </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/viewProduct.php");
}

if (isset($_GET['clientProductList'])) {
  $clientId = $_GET['clientProductList'];
  $con = connectDb();
  $dataList = fetchClientProductListById($con, $clientId);
  $i = 1;
  $table = "";
  if ($_GET['orgType'] == 1) {
    $table .= "<table style='border-collapse: collapse;border: 1px solid black; width: 100%;'><tr><th style='border: 1px solid black;color:black;background:grey;'>NO.</th><th style='border: 1px solid black;color:black;background:grey;'>BRAND</th><th style='border: 1px solid black;color:black;background:grey;'>MODEL</th><th style='border: 1px solid black;color:black;background:grey;'>SERIAL NUMBER</th><th style='border: 1px solid black;color:black;background:grey;'>STATUS</th></tr>";
    if ($dataList != NULL) {
      foreach ($dataList as $data) {
        switch ($data['cStatus']) {
          case '0':
            $cStatusTable = "TG";
            break;
          case '1':
            $cStatusTable = "WTY";
            break;
          case '2':
            $cStatusTable = "PERCALL";
            break;
          case '3':
            $cStatusTable = "RENTAL";
            break;
          case '4':
            $cStatusTable = "AD HOC";
            break;
        }


        $productId = $data['productId'];
        $row = fetchProductListById($con, $productId);
        $table .= "<tr><td style='border: 1px solid black;'>" . $i . "</td><td style='border: 1px solid black;'>" . strtoupper($row['brand']) . "</td><td style='border: 1px solid black;'>" . $row['model'] . "</td><td style='border: 1px solid black;'>" . $row['serialNum'] . "</td><td style='border: 1px solid black;'>" . $cStatusTable . "</td></tr>";
        $i++;
      }
    } else {
      $table .= "<tr><td style='border: 1px solid black;'><i>EMPTY</i></td><td style='border: 1px solid black;'><i>EMPTY</i></td><td style='border: 1px solid black;'><i>EMPTY</i></td><td style='border: 1px solid black;'><i>EMPTY</i></td><td style='border: 1px solid black;'><i>EMPTY</i></td></tr>";
    }
    $table .= "</table><br>";
  }

  $rowClient = fetchClientCompanyDetails($con, $clientId);

  $table .= "<table style='border-collapse: collapse;border: 1px solid black; width: 100%;'>";
  //NAME
  $table .= "<tr><th style='border: 1px solid black;color:black;background:grey;'>Name</th><td style='border: 1px solid black;'>" . $rowClient['name'] . "</td></tr>";
  //CUST. ADDRESS
  $table .= "<tr><th style='border: 1px solid black;color:black;background:grey;'>Cust. Address</th><td style='border: 1px solid black;'>" . $rowClient['address1'] . "," . $rowClient['address2'] . ", " . $rowClient['city'] . ", " . $rowClient['postalCode'] . ", " . $rowClient['state'] . "</td></tr>";
  //INSTALLATION ADDRESS
  if ($rowClient['instalAddress1'] != NULL) {
    $table .= "<tr><th style='border: 1px solid black;color:black;background:grey;'>Cust. Address</th><td style='border: 1px solid black;'>" . $rowClient['instalAddress1'] . "," . $rowClient['instalAddress2'] . ", " . $rowClient['instalCity'] . ", " . $rowClient['instalPCode'] . ", " . $rowClient['instalState'] . "</td></tr>";
  } else {
    $table .= "<tr><th style='border: 1px solid black;color:black;background:grey;'>Installation Address</th><td style='border: 1px solid black;'><i>EMPTY</i></td></tr>";
  }
  //EMAIL ADDRESS
  $table .= "<tr><th style='border: 1px solid black;color:black;background:grey;'>Email</th><td style='border: 1px solid black;'>" . $rowClient['emailAddress'] . "</td></tr>";

  $table .= "</table>";
  echo $table;
}

if (isset($_POST['addBrand'])) {
  //$description = $_POST['description']; kiv
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD BRAND \n
  </div>\n";
  $con = connectDb();
  $con = connectDb();
  $brand = $_POST['brandName'];
  if (checkBrand($con, $brand)) {
    $feedback = insertBrand($con, $brand);
    if ($feedback) {
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>BRAND IS SUCCESSFULLY ADDED \n
    </div>\n";
    }
  } else {
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
  <strong>ALREADY EXISTS!</strong> FAILED TO ADD BRAND \n
  </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/addBrand.php");
}

function brandListTableEditable()
{
  $con = connectDb();

  $table = "<div class='table-responsive table-stripped table-bordered'>\n";
  $table .= "<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
  $table .= "<thead class='thead-dark'>\n";
  $table .= "<tr>\n";
  $table .=  "<th>\n";
  $table .=    "Id\n";
  $table .=  "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Brand Name\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Action\n";
  $table .=   "</th>\n";
  $table .= "</tr>\n";
  $table .= "</thead >\n";
  $i = 1;
  $orgId = $_SESSION['orgId'];
  $status = 1;
  $role = null;
  $dataList = fetchBrandList($con);
  $table .= "<tbody>";
  foreach ($dataList as $data) {
    $table .= "<tr ";
    if ($i % 2 == 0)
      $table .= "style='background-color:#FFF5EB;'";
    else {
      $table .= "style='background-color:#F9F9F9;'";
    }
    $table .= ">";

    $table .=  "<td style='font-weight:bold'>";
    $table .=  $data['id'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .=    $data['brand'];
    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#brandEditModal' class='dropdown-item' onclick='brandEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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

if (isset($_POST['removeBrand'])) {
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE BRAND \n
   </div>\n";
  $con = connectDb();
  $productId = $_POST['brandIdToEdit'];
  $feedback = deleteBrand($con, $productId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>BRAND IS SUCCESSFULLY DELETED \n
     </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/viewBrand.php");
}


if (isset($_POST['editBrand'])) {
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
  $brandId = $_POST['brandIdToEdit'];
  $con = connectDb();
  $sql = "SELECT * FROM `productbrand` WHERE `id` = '$brandId'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($result);
  $_SESSION['brandIdEdit'] = $row['id'];
  $_SESSION['brandEdit'] = $row['brand'];
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/editBrand.php");
}

if (isset($_POST['editBrandProcess'])) {
  //$description = $_POST['description']; kiv
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPDATED BRAND \n
  </div>\n";
  $con = connectDb();
  $con = connectDb();
  $brand = $_POST['brand'];
  $brandId = $_POST['brandId'];

  $feedback = updateBrand($con, $brand, $brandId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>BRAND IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }

  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/viewBrand.php");
}



if (isset($_POST['addType'])) {
  //$description = $_POST['description']; kiv
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD PRODUCT TYPE \n
  </div>\n";
  $con = connectDb();
  $con = connectDb();
  $productType = $_POST['productType'];
  if (checkProductType($con, $productType)) {
    $feedback = insertProductType($con, $productType);
    if ($feedback) {
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
        <strong>SUCCESS!</strong>PRODUCT TYPE IS SUCCESSFULLY ADDED \n
        </div>\n";
    }
  } else {
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
  <strong>ALREADY EXISTS!</strong> FAILED TO ADD PRODUCT TYPE \n
  </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/addType.php");
}

function productTypeListTableEditable()
{
  $con = connectDb();

  $table = "<div class='table-responsive table-stripped table-bordered'>\n";
  $table .= "<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
  $table .= "<thead class='thead-dark'>\n";
  $table .= "<tr>\n";
  $table .=  "<th>\n";
  $table .=    "Id\n";
  $table .=  "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Product Type\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Action\n";
  $table .=   "</th>\n";
  $table .= "</tr>\n";
  $table .= "</thead >\n";
  $i = 1;
  $orgId = $_SESSION['orgId'];
  $status = 1;
  $role = null;
  $dataList = fetchProductTypeList($con);
  $table .= "<tbody>";
  foreach ($dataList as $data) {
    $table .= "<tr ";
    if ($i % 2 == 0)
      $table .= "style='background-color:#FFF5EB;'";
    else {
      $table .= "style='background-color:#F9F9F9;'";
    }
    $table .= ">";

    $table .=  "<td style='font-weight:bold'>";
    $table .=  $data['id'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .=    $data['type'];
    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#productTypeEditModal' class='dropdown-item' onclick='productTypeEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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

if (isset($_POST['removeProductType'])) {
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO DELETE PRODUCT TYPE \n
   </div>\n";
  $con = connectDb();
  $productTypeId = $_POST['productTypeIdToEdit'];
  $feedback = deleteProductType($con, $productTypeId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>PRODUCT TYPE IS SUCCESSFULLY DELETED \n
     </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/viewType.php");
}


if (isset($_POST['editProductType'])) {
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
  $productTypeId = $_POST['productTypeIdToEdit'];
  $con = connectDb();
  $sql = "SELECT * FROM `producttype` WHERE `id` = '$productTypeId'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($result);
  $_SESSION['productTypeIdEdit'] = $row['id'];
  $_SESSION['productTypeEdit'] = $row['type'];
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/editType.php");
}

if (isset($_POST['editProductTypeProcess'])) {
  //$description = $_POST['description']; kiv
  $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPDATED PRODUCT TYPE \n
  </div>\n";
  $con = connectDb();
  $productType = $_POST['productType'];
  $productTypeId = $_POST['productTypeId'];
  $feedback = updateProductType($con, $productType, $productTypeId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PRODUCT TYPE IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }

  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/option/viewType.php");
}

function brandList()
{
  $con = connectDb();
  $dataList = fetchBrandList($con);

  foreach ($dataList as $data) {
    echo '<option value="' . $data['brand'] . '">' . $data['brand'] . '</option>';
  }
}

function brandListJson()
{
  $con = connectDb();
  $dataList = fetchBrandList($con);
  $count = 1;

  echo "[";
  foreach ($dataList as $data) {
    if ($count > 1) {
      echo ',';
    }
    echo "\"" . $data['brand'] . "\"";
    $count++;
  }
  echo "]";
}

function productTypeList()
{
  $con = connectDb();
  $dataList = fetchProductTypeList($con);

  foreach ($dataList as $data) {
    echo '<option value="' . $data['type'] . '">' . $data['type'] . '</option>';
  }
}
function productTypeListJson()
{
  $con = connectDb();
  $dataList = fetchProductTypeList($con);
  $count = 1;

  echo "[";
  foreach ($dataList as $data) {
    if ($count > 1) {
      echo ',';
    }
    echo "\"" . $data['type'] . "\"";
    $count++;
  }
  echo "]";
}

if (isset($_POST['search'])) {

  $date = "";
  $brand = "";
  $type = "";
  $ponumber = "";
  $serialno = "";
  $capacity = "";

  if (isset($_POST['sdate']) && $_POST['sdate'] != null) {
    $date = "date LIKE '" . $_POST['sdate'] . "%' AND ";
  }
  if (isset($_POST['sbrand']) && $_POST['sbrand'] != null) {
    $brand = "brand LIKE '%" . $_POST['sbrand'] . "%' AND ";
  }
  if (isset($_POST['sproducttype']) && $_POST['sproducttype'] != null) {
    $type = "producttype LIKE '%" . $_POST['sproducttype'] . "%' AND ";
  }
  if (isset($_POST['sponumber']) && $_POST['sponumber'] != null) {
    $ponumber = "ponumber LIKE '%" . $_POST['sponumber'] . "%' AND ";
  }
  if (isset($_POST['sserial']) && $_POST['sserial'] != null) {
    $serialno = "serialNum LIKE '%" . $_POST['sserial'] . "%' AND ";
  }
  if (isset($_POST['scapacity']) && $_POST['scapacity'] != null) {
    $capacity = "capacity LIKE '%" . $_POST['scapacity'] . "%' AND ";
  }

  $_SESSION['sdata'] = $date . $brand . $type . $ponumber . $serialno . $capacity;


  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/viewProduct.php");
}

if (isset($_POST['searchStore'])) {

  $date = "";
  $status = "";
  $oid = "";
  if (isset($_POST['oid']) && $_POST['oid'] != null) {
    $oid = "id =" . $_POST['oid'] . " AND ";
  }
  if (isset($_POST['sdate']) && $_POST['sdate'] != null) {
    $date = "orderdate LIKE '" . $_POST['sdate'] . "%' AND ";
  }
  if (isset($_POST['status']) && $_POST['status'] != null && $_POST['status'] != 0) {
    $status = "status =" . $_POST['status'] . " AND ";
  }
  $_SESSION['sdata'] = $oid . $date . $status;
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/store.php");
}

if (isset($_POST['setOut'])) {
  $_SESSION['sentoutid'] = $_SESSION['productIdToEdit'];
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/sentProduct.php");
}

function productSentListTable()
{
  $con = connectDb();

  if ($_SESSION['orgType'] == 7) {
    $sdata = "";
    if (isset($_SESSION['sdata'])) {
      $sdata = $_SESSION['sdata'];
    }
    $dataList = fetchProductSentList($con);
    unset($_SESSION['sdata']);
  } else {
    $dataList = fetchProductSentList($con);
  }
  if ($dataList == null) {
    echo "<center><h5>No Record Found</h5></center>";
  } else {
    $table = "<div class='table-responsive'>\n";
    $table .= "<table  class='table' id='dataTable' width='100%' cellspacing='0' >\n";
    $table .= "<thead class='thead-dark'>\n";

    $table .= "<tr>\n";
    if ($_SESSION['orgType'] == 7) {
      $table .=  "<th>\n";
      $table .=    "D/O No\n";
      $table .=  "</th>\n";
      $table .=  "<th>\n";
      $table .=    "Date\n";
      $table .=  "</th>\n";

      $table .=  "<th>\n";
      $table .=    "Client\n";
      $table .=  "</th>\n";

      $table .=  "<th>\n";
      $table .=    "Incharge\n";
      $table .=  "</th>\n";

      $table .=  "<th>\n";
      $table .=    "Worker From\n";
      $table .=  "</th>\n";

      $table .=  "<th>\n";
      $table .=    "Worker\n";
      $table .=  "</th>\n";

      $table .=  "<th>\n";
      $table .=    "Contract\n";
      $table .=  "</th>\n";
    }
    $extable = $table;
  }
  $temp =  "<th>\n";
  $temp .=     "Action\n";
  $temp .=   "</th>\n";
  $table .= $temp;
  $temp = "</tr>\n";
  $temp .= "</thead >\n";

  $i = 1;
  $orgId = $_SESSION['orgId'];

  $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientUser.php");
  $table .= $temp;
  $extable .= $temp;
  foreach ($dataList as $data) {
    $temp = "<tr";

    $temp .= ">";
    if ($_SESSION['orgType'] == 7) {
      $temp .= "<td style='font-weight:bold'>";
      $temp .=   $data['donumber'];
      $temp .= "</td>";
      $temp .= "<td>";
      $temp .=   substr($data['dodate'], 0, 10);
      $temp .= "</td>";

      $temp .= "<td>";
      $temp .=   $data['doto'];
      $temp .= "</td>";

      $temp .= "<td>";
      $temp .=   $data['doincharge'];
      $temp .= "</td>";

      $temp .= "<td>";
      $temp .=   $data['workertype'];
      $temp .= "</td>";

      $temp .= "<td>";
      $temp .=   $data['worker'];
      $temp .= "</td>";


      $temp .= "<td>";
      $temp .=    $data['contract'];
      $temp .= "</td>";
    }
    $table .= $temp;
    $extable .= $temp;
    $temp = "<td>";

    $temp .= "<div class='dropdown'>";
    $temp .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
              OPTION
              </button>
              <div class='dropdown-menu'>";
    $temp .= "<button type='button' data-toggle='modal' data-target='#productEditModal' class='dropdown-item' onclick='editProduct(" . $data['donumber'] . ")' value='$data[donumber]' style='cursor:pointer'>ACTIONS</button>";
    $temp .= "  </div> </div>";
    $temp .= "</td>";
    $table .= $temp;

    $temp = "</tr>";
    $table .= $temp;
    $extable .= $temp;
  }
  $temp = "</table>";
  $temp .= "</div>";
  $table .= $temp;
  $extable .= $temp;
  $_SESSION['productex'] = $extable;
  echo $table;
}
function saveImageStore($pid, $img)
{

  //path for the image
  $source_url = $img;

  //separate the file name and the extention
  $source_url_parts = pathinfo($source_url);
  $filename = $source_url_parts['filename'];
  $extension = $source_url_parts['extension'];
  $footer = "";
  //detect the width and the height of original image
  list($width, $height) = getimagesize($source_url);
  $width;
  $height;

  //define any width that you want as the output. mine is 200px.
  $after_width = 512;
  $newfilename = "";
  //resize only when the original image is larger than expected with.
  //this helps you to avoid from unwanted resizing.
  if ($width > $after_width) {

    //get the reduced width
    $reduced_width = ($width - $after_width);
    //now convert the reduced width to a percentage and round it to 2 decimal places
    $reduced_radio = round(($reduced_width / $width) * 100, 2);

    //ALL GOOD! let's reduce the same percentage from the height and round it to 2 decimal places
    $reduced_height = round(($height / 100) * $reduced_radio, 2);
    //reduce the calculated height from the original height
    $after_height = $height - $reduced_height;

    //Now detect the file extension
    //if the file extension is 'jpg', 'jpeg', 'JPG' or 'JPEG'
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
      //then return the image as a jpeg image for the next step
      $img = imagecreatefromjpeg($source_url);
    } elseif ($extension == 'png' || $extension == 'PNG') {
      //then return the image as a png image for the next step
      $img = imagecreatefrompng($source_url);
    } else {
      //show an error message if the file extension is not available
      echo 'image extension is not supporting';
    }

    //HERE YOU GO :)
    //Let's do the resize thing
    //imagescale([returned image], [width of the resized image], [height of the resized image], [quality of the resized image]);
    $imgResized = imagescale($img, $after_width, $after_height);
    //now save the resized image with a suffix called "-resized" and with its extension.
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
      imagejpeg($imgResized, "../resources/2/product/." . $pid . "/." . $extension);
    } elseif ($extension == 'png' || $extension == 'PNG') {
      imagepng($imgResized, "../resources/2/product/." . $pid . "/." . $extension);
    } else {
      //show an error message if the file extension is not available
      echo 'image extension is not supporting';
    }
    //Finally frees any memory associated with image
    //**NOTE THAT THIS WONT DELETE THE IMAGE
    $newfilename = "email-footer-resized." . $extension;
    imagedestroy($img);
    imagedestroy($imgResized);
  }

  return $newfilename;
}

if (isset($_POST['updateStoreNote'])) {
  $id = $_POST['orderIdToUpdate'];
  $managerNote = "";
  if (isset($_POST['managernote'])) {
    $managerNote = "[" . $_SESSION['fullName'] . "]: " . $_POST['managernote'] . "\n<br>";
  }
  $status = $_POST['orderStatus'];

  $con = connectDb();
  $body = "";
  $result = updateStoreNotesByOrderid($con, $managerNote, $id);
  if ($result) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
   <strong>SUCCESS!</strong> SUCCESSFUL TO ADDED NOTES \n
   </div>\n";
  } else {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO ADD NOTES \n
   </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/store.php");
}

if (isset($_POST['updateStoreOrder'])) {
  $id = $_POST['orderIdToUpdate'];
  $workid = "";
  if (isset($_POST['workerId'])) {
    $workid = $_POST['workerId'];
  }
  $note = "";
  if (isset($_POST['note'])) {
    $note = $_POST['note'];
  }
  $managerNote = "";
  if (isset($_POST['managernote'])) {
    $managerNote = "[" . $_SESSION['fullName'] . "]: " . $_POST['managernote'] . "\n<br>";
  }
  $status = $_POST['orderStatus'];

  $con = connectDb();
  $body = "";
  $result = updateStoreOrderByOrderid($con, $status, $managerNote, $id);
  if ($result) {
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/mail.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/storetask.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/store.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
    $orgDetails = fetchOrganizationDetails($_SESSION['orgId']);
    $from = $orgDetails['salesEmail'];
    $fromName = $orgDetails['name'];
    $order = getOrderDetailsForAdminID($con, $id);
    $to = $order['email'];
    $subject = "Order Status Updated ";
    $status = $order['status'];
    $temp = "";
    switch ($status) {
      case 1: {
          $temp = "Received";
          break;
        }
      case 2: {
          $temp = "Processed";
          break;
        }
      case 3: {
          $temp = "Shipped";
          break;
        }
      case 4: {
          $temp = "Delivered";
          break;
        }
      case -1: {
          $temp = "Canceled";
          break;
        }
      default: {
          $temp = "Pending";
        }
    }

    $body = "Dear " . $order['name'] . ",<br><br/>";
    if ($status == 2) {
      $body . "<h4>Thank You for Book Order!<br/></h4>";
    }
    $body .= "You order status is updated.";
    $body .= "<table cellpadding='5' border='0'>";
    $body .= "<tr style=\"background-color: rgb(236,242,249)\"><td width='150px'>ORDER ID:</td><td width='150px'>" . $id . "</td></tr>";
    $body .= "<tr style=\"background-color: rgb(172,197,226)\"><td>ORDER DATE:</td><td>" . $order['orderdate'] . "</td></tr>";
    $body .= "<tr style=\"background-color: rgb(236,242,249)\"><td>STATUS:</td><td>" . $temp . "</td></tr>";
    $body .= "<tr style=\"background-color: rgb(172,197,226)\"><td>TOTAL BILL:</td><td>" . $order['grandtotal'] . "</td></tr></table><br/><br/>";
    if ($order['status'] == 2) {
      $detail = fetchOrderItemDetails($con, $id);
      $count = 1;
      $style = "";
      $body .= "<table cellpadding='5' border='0' ><thead><tr style=\"background-color: rgb(90,112,138);color:rgb(255,255,255);\"><th width='50px'>SR NO.</th><th width='200px'>Title</th><th width='70px'>Price</th><th width='70px'>Quantity</th><th width='70px'>Total</th></tr></thead><tbody>";
      foreach ($detail as $item) {
        if ($count % 2 == 0) {
          $style = "style=\"background-color:rgb(172,197,226)\"";
        } else {
          $style = "style=\"background-color:rgb(236,242,249)\"";
        }
        $body .= "<tr " . $style . "><td width='50px'>" . $count . "</td><td width='200px'>" . $item['title'] . "</td><td width='70px'>RM " . $item['price'] . "</td><td width='70px'>" . $item['quantity'] . "</td><td width='70'>RM " . $item['total'] . "</td></tr>";
        $count++;
      }
      $body .= "</tbody></table>";
    }
    if ($order['status'] == -1) {
      $body = "Dear " . $order['name'] . ",<br><br/>";
      $body .= "Your order has been canceled due to reason";
      $body .= "<br>" . $note;
    }
    $body .= "<br><br>You can anytime check order status. just click on <a href=\"https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/\">link</a>";

    mailsend($from, $fromName, $to, $subject, $body);

    $workertype = $_POST['workerType'];
    $workerId = $_POST['workerId'];
    if ($result) {
      if ($order['status'] == 3 || $order['status'] == 2) {
        $problem = "Order " . $temp . " order id: " . $id;
        $problemDetails = "Kindly process the order as per request item(s)";
        $requireDate = date("Y-m-d");

        $companyId = $order['cid'];
        $requireTime = date("h:i:s");
        make_Task_Store($problem, $problemDetails, $requireTime, $requireDate, $companyId);

        if (isset($_SESSION['saveComplaint']) && $_SESSION['saveComplaint'] == true) {
          assignTaskStore($workertype, $workerId);
        }
      }
    }

    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
   <strong>SUCCESS!</strong>SUCCESSFUL TO UPDATE ORDER STATUS \n
   </div>\n";
  } else {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
   <strong>FAILED!</strong> FAILED TO UPDATE ORDER STATUS \n
   </div>\n";
  }

  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/store.php");
}
