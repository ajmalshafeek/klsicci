<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/bill.php");

$con = connectDb();

if (isset($_POST['addBill'])) {
  ob_start();
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD BILL PAYMENT \n
  </div>\n";
  $con = connectDb();
  $category = $_POST['category'];
  $subcategory = $_POST['subcategory'];
  $invoiceNum = $_POST['invoiceNum'];
  $accountNum = $_POST['accountNum'];
  $dateBill = $_POST['dateBill'];
  $amount = $_POST['amount'];
  $vehicleNo="";
      if(isset($_POST['vehicleNumber'])){
          $vehicleNo=$_POST['vehicleNumber'];
      }

  $requestBy=$_POST['requestBy'];
  $jobNo=$_POST['jobNo'];
  $location=$_POST['location'];
  $description=$_POST['description'];
  $remarks=$_POST['remarks'];
  $status=$_POST['status'];
  $status=$_POST['status'];
  $tripId="";
  if(isset($_POST['tripId'])){
    $tripId=$_POST['tripId'];}

  $feedback = insertBill($con,$category,$subcategory,$invoiceNum,$accountNum,$dateBill,$amount,$vehicleNo,$tripId,
      $requestBy,$jobNo,$location,$description,$remarks,$status);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>BILL PAYMENT IS SUCCESSFULLY ADDED FOR REVIEW\n
    </div>\n";
  }else{
    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>BILL PAYMENT IS NOT ADDED FOR REVIEW\n
    </div>\n";
  }
ob_clean();
  ob_end_clean();
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/bill/bill.php");
}

function categoryOptionListAll(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/bill.php");
  $con = connectDb();
  $dataList = fetchBillCategoryAll($con);
  $options = "<option  value='' selected disabled >--Select Category--</option>";
  foreach ($dataList as $data) {
    $options .= "<option value='".$data['id']."'>".$data['category']."</option>";
  }

  echo $options;
}

if (isset($_GET['showSubcategoryOptions'])) {
  $billCategoryId = $_GET['showSubcategoryOptions'];
  $dataList = fetchBillSubcategoryByBillCategoryId($con,$billCategoryId);

  $options = "<option  value='' selected disabled >--Select Expense For--</option>";

  foreach ($dataList as $data) {
    $options .= "<option value='".$data['id']."'>".$data['subcategory']."</option>";;
  }
  echo $options;
}

if(isset($_POST['addBillCategory'])){
$billCategory=$_POST['billCategory'];
$con=connectDb();
  $feedback = insertBillCategory($con,$billCategory);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>EXPENSE CATEGORY IS SUCCESSFULLY ADDED\n
    </div>\n";
  }else{
    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>EXPENSE CATEGORY IS NOT ADDED\n
    </div>\n";
  }
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/bill/option/addCategory.php");
}

function billListTableEditable()
{
  $con = connectDb();

  $table = "<div class='table-responsive table-stripped table-bordered'>\n";
  $table .= "<table id='dtable' class='table' width='100%' cellspacing='0'>\n";
  $table .= "<thead class='thead-dark'>\n";
  $table .= "<tr>\n";
  $table .=  "<th>\n";
  $table .=    "Sr No\n";
  $table .=  "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Date\n";
  $table .=  "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Category\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Expense For\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Invoice No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Account No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Vehicle No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Request By\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Job No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Location\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Description\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Remarks\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Amount\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Status\n";
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
 $billList= fetchBills($con);
  $subList = fetchBillSubcategory($con);
  $cateList = fetchBillCategoryAll($con);
  $table .= "<tbody>";
  foreach ($billList as $data) {
    $table .= "<tr ";
    if ($i % 2 == 0)
      $table .= "style='background-color:#FFF5EB;'";
    else {
      $table .= "style='background-color:#F9F9F9;'";
    }
    $table .= ">";

    $table .=  "<td style='font-weight:bold'>";
    $table .=  $i;
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['dateBill'];
    $table .=  "</td>";


    $table .=  "<td>";
    foreach ($cateList as $cate) {
      if($data['product']==$cate['id']){
        $table .= $cate['category'];
      }
    }
    $table .=  "</td>";
    $table .=  "<td>";
    foreach ($subList as $sub) {
      if($data['subcategory']==$sub['id']){
        $table .= $sub['subcategory'];
      }
    }
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['invoiceNum'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['accountNum'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['number'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['requestBy'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['jobNo'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['location'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['description'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['remarks'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['amount'];
    $table .=  "</td>";
    $table .=  "<td>";
    if($data['status']){
      $table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: green;"><span style="display: none">Completed</span></div>';
    }elseif($data['status']==0){$table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: red;"><span style="display: none">Pending</span></div>';}

    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#billEditModal' class='dropdown-item' onclick='billEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
    $table .= "	</div>
							</div>";
    $table .= "</td>";
    $table .= "</tr>";
    $i++;
  }

  $table .= "</tbody>";
  $table .= "</table>";
  $table .= "</div>";
  echo $table;
}
function purchasedListTableEditable()
{
  $con = connectDb();

  $table = "<div class='table-responsive table-stripped table-bordered'>\n";
  $table .= "<table id='dTable' class='table' width='100%' cellspacing='0'>\n";
  $table .= "<thead class='thead-dark'>\n";
  $table .= "<tr>\n";
  $table .=  "<th>\n";
  $table .=    "Date\n";
  $table .=  "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Expense For\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Job No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Invoice No\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Request By\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Shop/Location\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Description\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Amount\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Remarks\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Status\n";
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
  $billList= fetchBills($con);
  $subList = fetchBillSubcategory($con);
  $cateList = fetchBillCategoryAll($con);
  $table .= "<tbody>";
  foreach ($billList as $data) {
    $table .= "<tr ";
    if ($i % 2 == 0)
      $table .= "style='background-color:#e6ebe0;'";
    else {
      $table .= "style='background-color:#f5f5ef;'";
    }
    $table .= ">";
    $table .=  "<td>";
    $table .= $data['dateBill'];
    $table .=  "</td>";
    $table .=  "<td>";
    foreach ($subList as $sub) {
      if($data['subcategory']==$sub['id']){
        $table .= $sub['subcategory'];
      }
    }

    $table .=  "<td>";
    $table .= $data['jobNo'];
    $table .=  "</td>";
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['invoiceNum'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['requestBy'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['location'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['description'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['amount'];
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['remarks'];
    $table .=  "</td>";
    $table .=  "<td>";
    if($data['status']){
      $table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: green;"><span style="display: none">Completed</span></div>';
    }elseif($data['status']==0){$table .= '<div style="height: 15px;width: 15px;border-radius:15px;background-color: red;"><span style="display: none">Pending</span></div>';}

    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#billEditModal' class='dropdown-item' onclick='billEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
    $table .= "	</div>
							</div>";
    $table .= "</td>";
    $table .= "</tr>";
    $i++;
  }

  $table .= "</tbody>";
  $table .= "</table>";
  $table .= "</div>";
  echo $table;
}
if(isset($_POST['removeBill'])){
  $con = connectDb();
  $billCategoryId = $_POST['billIdToEdit'];
  $feedback = deleteBill($con,$billCategoryId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong> EXPENSE IS SUCCESSFULLY DELETED \n
     </div>\n";
  }else{
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO DELETE EXPENSE \n
        </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/viewBill.php");
}

function billCategoryListTableEditable()
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
  $table .=    "Category\n";
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
  $dataList = fetchBillCategoryAll($con);
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
    $table .=    $data['category'];
    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#billCategoryEditModal' class='dropdown-item' onclick='billCategoryEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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
if(isset($_POST['editBillCategory'])){
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
  $vehicleCategoryId = $_POST['billCategoryIdToEdit'];
  $con = connectDb();
  $sql = "SELECT * FROM `billcategory` WHERE `id` = '$vehicleCategoryId'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($result);
  $_SESSION['billCategoryIdEdit'] = $row['id'];
  $_SESSION['billCategoryEdit'] = $row['category'];
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/editCategory.php");
}

if(isset($_POST['editBillCategoryProcess'])){
  $con =connectDb();
  $billCategory=$_POST['billCategoryEdit'];
  $billCategoryId=$_POST['billCategoryEditId'];
  $feedback = updateBillCategory($con, $billCategory, $billCategoryId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>EXPENSE CATEGORY IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }else{
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>EXPENSE CATEGORY IS NOT UPDATED \n
    </div>\n";
  }
  unset($_SESSION['billCategoryIdEdit']);
  unset($_SESSION['billCategoryEdit']);
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/viewCategory.php");
}
if(isset($_POST['removeBillCategory'])){
  $con = connectDb();
  $billCategoryId = $_POST['billCategoryIdToEdit'];
  $feedback = deleteBillCategory($con,$billCategoryId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong>EXPENSE CATEGORY IS SUCCESSFULLY DELETED \n
     </div>\n";
  }else{
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO DELETE EXPENSE CATEGORY \n
        </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/viewCategory.php");
}


if(isset($_POST['addExpenseFor'])){
  $billExpenseFor=$_POST['expenseFor'];
  $billCategory=$_POST['category'];
  $con=connectDb();
  $feedback = insertExpenseFor($con,$billExpenseFor,$billCategory);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>BILL EXPENSE FOR IS SUCCESSFULLY ADDED\n
    </div>\n";
  }else{
    $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>BILL EXPENSE FOR IS NOT ADDED\n
    </div>\n";
  }
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/bill/option/addExpensFor.php");
}
function billExpenseForListTableEditable()
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
  $table .=    "Category\n";
  $table .=   "</th>\n";
  $table .=  "<th>\n";
  $table .=    "Expense For\n";
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
  $dataList = fetchBillSubcategory($con);
  $cateList = fetchBillCategoryAll($con);
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
    foreach ($cateList as $cate) {
      if($data['billCategoryId']==$cate['id']){
      $table .= $cate['category'];
      }
    }
    $table .=  "</td>";
    $table .=  "<td>";
    $table .= $data['subcategory'];
    $table .=  "</td>";
    $table .= "<td>";
    $table .= "<div class='dropdown'>";
    $table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

    $table .= "<button type='button' data-toggle='modal' data-target='#billExpenseForEditModal' class='dropdown-item' onclick='expenseForCategoryEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
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
if(isset($_POST['editBillExpenseFor'])){
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/session.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
  $subCateId = $_POST['billExpenseForIdToEdit'];
  $con = connectDb();
  $sql = "SELECT * FROM `billsubcategory` WHERE `id` = '$subCateId'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($result);
  $_SESSION['billExpenseForIdEdit'] = $row['id'];
  $_SESSION['billExpenseForEdit'] = $row['subcategory'];
  $_SESSION['billExpenseForCateEdit'] = $row['billCategoryId'];
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/editExpensFor.php");
}

if(isset($_POST['editBillExpenseForProcess'])){
  $con =connectDb();
  $subcategory=$_POST['billExpenseForEdit'];
  $billCategoryId=$_POST['category'];
  $billExpenseForId=$_POST['billExpenseForEditId'];
  $feedback = updateBillExpenseFor($con, $billCategoryId, $subcategory,$billExpenseForId);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>EXPENSE FOR IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }else{
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
    <strong>FAILED!</strong>EXPENSE FOR IS NOT UPDATED \n
    </div>\n";
  }
  unset($_SESSION['billExpenseForIdEdit']);
  unset($_SESSION['billExpenseForEdit']);
  unset($_SESSION['billExpenseForCateEdit']);
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/viewExpensFor.php");
}

if(isset($_POST['removeBillExpenseFor'])){
  $con = connectDb();
  $billExpenseForIdToEdit = $_POST['billExpenseForIdToEdit'];
  $feedback = deleteBillExpenseFor($con,$billExpenseForIdToEdit);
  if ($feedback) {
    $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
     <strong>SUCCESS!</strong> Expense For IS SUCCESSFULLY DELETED \n
     </div>\n";
  }else{
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> FAILED TO DELETE Expense For \n
        </div>\n";
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/viewExpensFor.php");
}



?>