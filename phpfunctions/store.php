<?php

use PhpParser\Node\Expr\FuncCall;

$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/product.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/store.php");


function getCategoryList()
{
  $con = connectDb();
  $dataList = fetchProductTypeList($con);
  return $dataList;
}
function storeItems($cat, $title)
{
  $con = connectDb();
  $dataList = fetchStoreItems($con, $cat, $title);
  return $dataList;
}
function storeItemsPaging($cat, $title, $view, $pages, $limit)
{
  $con = connectDb();
  $dataList = fetchStoreItemsPaging($con, $cat, $title, $view, $pages, $limit);
  return $dataList;
}

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $con = connectDb();
  $dataList = fetchStoreItem($con, $id);
  echo json_encode($dataList);
}

function getProductDetails($pid)
{
  $con = connectDb();
  $dataList = fetchStoreItem($con, $pid);
  return $dataList;
}
if (isset($_POST['item_src'])) {
  if (!isset($_SESSION['item_id'])) {
    $_SESSION['title'] = [];
    $_SESSION['item_id'] = [];
    $_SESSION['price'] = [];
    $_SESSION['src'] = [];
    $_SESSION['quantity'] = [];
    $_SESSION['total'] = [];
  }

  $key = array_search($_POST['item_id'], $_SESSION['item_id']);
  if ($key > -1) {
    if ($_POST['item_quantity'] == 1) {
      $_SESSION['quantity'][$key] = $_SESSION['quantity'][$key] + $_POST['item_quantity'];
    } else {
      $_SESSION['quantity'][$key] = $_POST['item_quantity'];
    }
    $_SESSION['total'][$key] = $_SESSION['price'][$key] * $_SESSION['quantity'][$key];
  } else {

    $_SESSION['item_id'][] = $_POST['item_id'];
    $_SESSION['title'][] = $_POST['item_name'];
    $_SESSION['price'][] = $_POST['item_price'];
    $_SESSION['src'][] = $_POST['item_src'];
    $_SESSION['quantity'][] = $_POST['item_quantity'];
    $_SESSION['total'][] = $_POST['item_quantity'] * $_POST['item_price'];
  }

  echo count($_SESSION['title']);
}

if (isset($_POST['showcart'])) {
  $cart = "";
  $json = "";
  $count = 0;
  if (isset($_SESSION['src'])) {
    $_SESSION['cartTotal'] = 0;
    $max = "";
    for ($i = 0; $i < count($_SESSION['src']); $i++) {
      $max = getQtyByID($_SESSION['item_id'][$i]);
      $_SESSION['total'][$i] = $_SESSION['quantity'][$i] * $_SESSION['price'][$i];
      $cart .= '<li class="cart-item alert fade show cartItem-' . $count . '" id="cartItem' . $count . '" >
          <div class="cart-image"><a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/product.php?pid=' . $_SESSION['item_id'][$i] . '"><img src="' . $_SESSION['src'][$i] . '" alt="product"></a></div>
          <div class="cart-info">
            <h5><a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/query/product.php?pid=' . $_SESSION['item_id'][$i] . '">' . $_SESSION['title'][$i] . '</a></h5><span>RM ' . $_SESSION['price'][$i] . ' X </span>
            <input type="hidden" name="pid[]" id="cartItem' . $count . '_pid" value="' . $_SESSION['item_id'][$i] . '" />
            <input type="hidden" name="price[]" id="cartItem' . $count . '_price" value="' . $_SESSION['price'][$i] . '" />
            <div class="product-action"><button class="action-minus" onclick="cartMinus(\'cartItem' . $count . '\')"  title="Quantity Minus"><i class="icofont-minus"></i></button>
            <input class="action-input" title="Quantity Number" id="cartItem' . $count . '_qty" type="text" onchange="updateCartItem(\'cartItem' . $count . '\')" name="quantity[]" value="' . $_SESSION['quantity'][$i] . '" min="1" max="' . $max . '" />
            <button class="action-plus" onclick="cartPlus(\'cartItem' . $count . '\')" title="Quantity Plus"><i class="icofont-plus"></i></button></div>
            <h6 id="cartItem' . $count . '_total" >RM ' . $_SESSION['total'][$i] . '</h6>
          </div><button class="cart-delete" onclick="removeCart(' . $i . ')" data-dismiss="alert"><i class="icofont-bin"></i></button>
        </li>';
      $count++;
      $_SESSION['cartTotal'] += $_SESSION['total'][$i];
    }
  } else {
    $cart = "<center class='mt-30'>No item found in cart</center>";
    $_SESSION['cartTotal'] = 0;
  }
  echo $cart;
}

if (isset($_POST['cartTotalCount'])) {
  $json = 0;

  if (isset($_SESSION['cartTotal'])) {
    $total = 0;
    $count = count($_SESSION['quantity']);
    for ($i = 0; $i < $count; $i++) {
      $total += $_SESSION['quantity'][$i] * $_SESSION['price'][$i];
    }
    $_SESSION['cartTotal'] = $total;
    $json = '{ "cartotal":"' . $total . '", "itemcount":"' . $count . '" }';
    json_encode($json);
  }
  echo $json;
}
if (isset($_POST['removeItemFromCart'])) {
  $key = $_POST['removeItemFromCart'];
  $item_id = $_SESSION['item_id'];
  $title = $_SESSION['title'];
  $price = $_SESSION['price'];
  $src = $_SESSION['src'];
  $quantity = $_SESSION['quantity'];
  $total = $_SESSION['total'];

  array_splice($item_id, $key, 1);
  array_splice($title, $key, 1);
  array_splice($price, $key, 1);
  array_splice($src, $key, 1);
  array_splice($quantity, $key, 1);
  array_splice($total, $key, 1);

  $_SESSION['item_id'] = $item_id;
  $_SESSION['title'] = $title;
  $_SESSION['price'] = $price;
  $_SESSION['src'] = $src;
  $_SESSION['quantity'] = $quantity;
  $_SESSION['total'] = $total;
  return true;
}
if (isset($_POST['cartSingleItemUpdate'])) {
  $key = "";
  $pid = $_POST['pid'];
  $qty = $_POST['qty'];
  $total = $_POST['total'];
  $key = array_search($pid, $_SESSION['item_id']);
  $_SESSION['quantity'][$key] = $qty;
  $_SESSION['total'][$key] = $total;
  return true;
}

function getQtyByID($pid)
{
  $con = connectDb();
  $data = fetchQtyByID($con, $pid);
  return $data;
}
if (isset($_POST['tableCart'])) {
  $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
  $table = 0;
  $i = 0;
  if (isset($_SESSION['src'])) {
    $table = '<table class="table-list">
                  <thead>
                    <tr>
                      <th scope="col">SL No</th>
                      <th scope="col">Product</th>
                      <th scope="col">Name</th>
                      <th scope="col">Price</th>
                      <th scope="col">Quantity</th>
                      <th scope="col">Total</th>
                      <th scope="col">action</th>
                    </tr>
                  </thead>
                  <tbody>';
    $i = 0;
    for ($i; $i < count($_SESSION['src']); $i++) {
      $table .= '<tr id="cart' . $i . '">
                      <td>
                        <h5>' . str_pad($i + 1, 2, 0, STR_PAD_LEFT) . '</h5>
                      </td>
                      <td><img src="' . $_SESSION['src'][$i] . '" alt="product"></td>
                      <td>
                        <h5>' . $_SESSION['title'][$i] . '</h5>
                      </td>
                      <td>
                        <h5>RM ' . $_SESSION['price'][$i] . '</h5>
                      </td>
                      <td>
                        <h5>' . $_SESSION['quantity'][$i] . '</h5>
                      </td>
                      <td>
                        <h5>RM ' . $_SESSION['total'][$i] . '</h5>
                      </td>
                      <td>
                        <ul class="table-action">
                          <li><a class="trash" title="Remove This Item" onclick="removeItem(' . $i . ');"><i class="icofont-trash"></i></a></li>
                        </ul>
                      </td>
                    </tr>';
    }
    $table .= '</tbody>
                </table>';
  }
  if ($i == 0) {
    $table = 0;
  }
  echo $table;
}
if (isset($_POST['cartTotal'])) {
  if (isset($_SESSION['cartTotal'])) {
    $total = 0;
    $count = count($_SESSION['quantity']);
    for ($i = 0; $i < $count; $i++) {
      $total += $_SESSION['quantity'][$i] * $_SESSION['price'][$i];
    }
    $_SESSION['cartTotal'] = $total;
  }
  echo $total;
}
function clientAddressDetails($cid)
{
  $con = connectDb();
  $dataList = fetchClientAddressDetails($con, $cid);
  return $dataList;
}

if (isset($_POST['updateProfile'])) {
  $con = connectDb();
  $add1 = $_POST['add1'];
  $add2 = $_POST['add2'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $zip = $_POST['zip'];
  $country = $_POST['country'];
  $id = $_SESSION['companyId'];
  $status = updateProfileDetails($con, $add1, $add2, $city, $state, $zip, $country, $id);
  if ($status) {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Success!</strong> Profile updated successful.</p>
          </div>';
  } else {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Danger!</strong> Profile not updated.</p>
          </div>';
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
}

if (isset($_POST['updateDetails'])) {
  $con = connectDb();
  $title = $_POST['title'];
  $value = $_POST['value'];
  $id = $_POST['id'];
  $status = updateDetails($con, $title, $value, $id);
  if ($status) {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Success!</strong> Details updated successful.</p>
          </div>';
  } else {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Failed!</strong> Details not updated.</p>
          </div>';
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
}
if (isset($_POST['addDetails'])) {
  $con = connectDb();
  $title = $_POST['title'];
  $value = $_POST['value'];
  $id = $_POST['cid'];
  $cat = $_POST['cat'];
  $status = insertDetails($con, $title, $value, $cat, $id);
  if ($status) {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Success!</strong> ' . $cat . ' details added successful.</p>
          </div>';
  } else {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Failed!</strong> ' . $cat . ' details not added.</p>
          </div>';
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
}

if (isset($_POST['removeDetails'])) {
  $con = connectDb();
  $id = $_POST['removeDetails'];
  $status = deleteDetails($con, $id);
  if ($status) {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Success!</strong> Details deleted successful.</p>
          </div>';
  } else {
    $_SESSION['message'] = '<div class="alert-info">
            <p><strong>Failed!</strong> Details not deleted.</p>
          </div>';
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
}
function contactList()
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $dataList = getContactList($con, $cid);
  $count = 1;
  $contact = "";
  foreach ($dataList as $data) {

    $contact .= '<div class="col-md-6 col-lg-4 alert my-2 fade show">
                  <div class="profile-card contact ' . $data["active"] . '" id="contact' . $count . '">
                    <h5>' . $data["title"] . '</h5>
                    <p>' . $data['value'] . '</p>
                    <ul class="user-action">
                        <input type="hidden" value="' . $data["title"] . '" id="title' . $count . '" >
                        <input type="hidden" value="' . $data["value"] . '" id="value' . $count . '" >
                        <input type="hidden" value="' . $data["id"] . '" id="id' . $count . '" >
                      <li><button class="edit"  title="Edit This" onclick="edit(\'contact\',' . $count . ')" data-toggle="modal" data-target="#contact-edit"><i class="icofont-edit"></i></button></li>
                      <li>
                      <form action="../../phpfunctions/store.php" method="post">
                      <button class="delete" title="Remove This" name="removeDetails" value="' . $data['id'] . '"><i class="icofont-ui-delete"></i></button>
                      </form>
                      </li>
                    </ul>
                  </div>
                </div>';
    $count++;
  }
  return $contact;
}

function addressList()
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $dataList = getAddressList($con, $cid);
  $count = 1;
  $contact = "";
  foreach ($dataList as $data) {
    $contact .= '<div class="col-md-6 col-lg-4 my-2 alert fade show ">
                  <div class="profile-card address ' . $data["active"] . '" id="address' . $count . '">
                    <h5>' . $data["title"] . '</h5>
                    <p>' . $data['value'] . '</p>
                    <ul class="user-action">
                        <input type="hidden" value="' . $data["title"] . '" id="title' . $count . '" >
                        <input type="hidden" value="' . $data["value"] . '" id="value' . $count . '" >
                        <input type="hidden" value="' . $data["id"] . '" id="id' . $count . '" >
                      <li><button class="edit"  title="Edit This" onclick="edit(\'address\',' . $count . ')" data-toggle="modal" data-target="#address-edit"><i class="icofont-edit"></i></button></li>
                      <li><form action="../../phpfunctions/store.php" method="post">
                      <button class="delete" title="Remove This" name="removeDetails" value="' . $data['id'] . '"><i class="icofont-ui-delete"></i></button>
                      </form>
                      </li>
                    </ul>
                  </div>
                </div>';
    $count++;
  }
  return $contact;
}

function contactCartList()
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $dataList = getContactList($con, $cid);
  $count = 1;
  $contact = "";
  foreach ($dataList as $data) {

    $contact .= '<div class="col-md-6 col-lg-4 alert my-2 fade show">
                  <div class="profile-card contact ' . $data["active"] . '" id="contact' . $count . '" onclick="active(\'contact\',' . $data["id"] . ')">
                    <h5>' . $data["title"] . '</h5>
                    <p>' . $data['value'] . '</p>
                    <ul class="user-action">
                        <input type="hidden" value="' . $data["title"] . '" id="title' . $count . '" >
                        <input type="hidden" value="' . $data["value"] . '" id="value' . $count . '" >
                        <input type="hidden" value="' . $data["id"] . '" id="id' . $count . '" >
                      <ul>
                  </div>
                </div>';
    $count++;
  }
  return $contact;
}

function addressCartList()
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $dataList = getAddressList($con, $cid);
  $count = 1;
  $contact = "";
  foreach ($dataList as $data) {
    $contact .= '<div class="col-md-6 col-lg-4 my-2 alert fade show ">
                  <div class="profile-card address ' . $data["active"] . '" id="address' . $count . '" onclick="active(\'address\',' . $data["id"] . ')">
                    <h5>' . $data["title"] . '</h5>
                    <p>' . $data['value'] . '</p>
                    <ul class="user-action">
                        <input type="hidden" value="' . $data["title"] . '" id="title' . $count . '" >
                        <input type="hidden" value="' . $data["value"] . '" id="value' . $count . '" >
                        <input type="hidden" value="' . $data["id"] . '" id="id' . $count . '" >
                    </ul>
                  </div>
                </div>';
    $count++;
  }
  return $contact;
}

if (isset($_POST['makeActive'])) {
  $id = $_POST['id'];
  $tag = $_POST['tag'];
  $con = connectDb();
  return setDefaultDetail($con, $id, $tag);
}

if (isset($_POST['checkoutCart'])) {
  $cid = $_SESSION['companyId'];
  $con = connectDb();
  $name = $_SESSION['name'];
  $cat = "address";
  $address = getDeliveryContact($con, $cid, $cat);
  $email = $_SESSION['email'];
  $cat = "contact";
  $contact = getDeliveryContact($con, $cid, $cat);
  $cartTotal = $_SESSION['cartTotal'];
  $notes = null;
  if (isset($_POST['notes'])) {
    $notes = $_POST['notes'];
  }
  $orderid = cartCheckout($con, $cid, $name, $address, $email, $contact, $cartTotal, $notes);
  if (!empty($orderid)) {
    $status = 1;
    $src = "";
    $title = "";
    $price = 0;
    $qty = 0;
    $total = 0;
    $result = array();
    for ($i = 0; $i < count($_SESSION['src']); $i++) {
      $pid = $_SESSION['item_id'][$i];
      $src = $_SESSION['src'][$i];
      $title = $_SESSION['title'][$i];
      $price = $_SESSION['price'][$i];
      $qty = $_SESSION['quantity'][$i];
      $total = $_SESSION['total'][$i];
      $result[$i] = cartcheCkoutItem($con, $pid, $orderid, $src, $title, $price, $qty, $total);
    }
    $res = orderStatusUpdate($con, $orderid, $status);
    $_SESSION['title'] = [];
    $_SESSION['item_id'] = [];
    $_SESSION['price'] = [];
    $_SESSION['src'] = [];
    $_SESSION['quantity'] = [];
    $_SESSION['total'] = [];
  }
  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/invoice.php?oid=" . $orderid);
}

function getOrderDetailById($oid)
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $datalist = fetchOrderDetailById($con, $oid, $cid);
  return $datalist;
}
function getOrderItemDetails($oid)
{
  $con = connectDb();
  $datalist = fetchOrderItemDetails($con, $oid);
  return $datalist;
}
function getOrderItemCount($oid)
{
  $con = connectDb();
  $datalist = fetchOrderItemCount($con, $oid);
  return $datalist;
}
function getOrderCount()
{
  $con = connectDb();
  $cid = $_SESSION['companyId'];
  $datalist = fetchOrderCount($con, $cid);
  return $datalist;
}

if (isset($_GET['orderList'])) {
  ob_start();
  $con = connectDb();
  $limit = 4;
  $pages = $_GET['orderList'];
  $status = 0;
  if (isset($_GET['orderType'])) {
    $status = $_GET['orderType'];
  }
  $orderMonth = 0;
  if (isset($_GET['orderMonth'])) {
    $orderMonth = $_GET['orderMonth'];
  }
  $page = filter_var($pages, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
  $position = (($page - 1) * $limit);
  $cid = $_SESSION['companyId'];

  $datalist = fetchOrderDetailByClient($con, $cid, $status, $orderMonth, $position, $limit);
  $accord = "";
  if (!isset($_SESSION['ocount'])) {
    $_SESSION['ocount'] = 1;
  }
  $oid = "";
  foreach ($datalist as $data) {
    $status = $data['status'];
    switch ($status) {
      case 1: {
          $orderstatus = "Received";
          break;
        }
      case 2: {
          $orderstatus = "Processed";
          break;
        }
      case 3: {
          $orderstatus = "Shipped";
          break;
        }
      case 4: {
          $orderstatus = "Delivered";
          break;
        }
      case -1: {
          $orderstatus = "Canceled";
          break;
        }
      default: {
          $orderstatus = "Pending";
        }
    }
    $active = "active";
    $oid = $data['id'];
    $date = date_create($data['orderdate']);
    $accord .= '<div class="orderlist">
            <div class="orderlist-head" data-toggle="collapse" data-target="#collapse' . $_SESSION['ocount'] . '">
              <h4>order#' . str_pad($_SESSION['ocount'], 2, 0, STR_PAD_LEFT) . '</h4>
              <h4>order ' . $orderstatus . '</h4>
            </div>
            <div class="orderlist-body collapse" id="collapse' . $_SESSION['ocount'] . '" >
              <div class="row">
                <div class="col-lg-12">
                  <div class="order-track">
                    <ul class="order-track-list">
                      <li class="order-track-item ';
    if ($status >= 1) {
      $accord .= $active;
    }
    $accord .= '"><i class="icofont-close"></i><span>order received</span></li>
                      <li class="order-track-item ';
    if ($status >= 2) {
      $accord .= $active;
    }
    $accord .= '"><i class="icofont-close"></i><span>order processed</span></li>
                      <li class="order-track-item ';
    if ($status >= 3) {
      $accord .= $active;
    }
    $accord .= '"><i class="icofont-close"></i><span>order shipped</span></li>
                      <li class="order-track-item ';
    if ($status >= 4) {
      $accord .= $active;
    }
    $accord .= '"><i class="icofont-close"></i><span>order delivered</span></li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-4">
                  <ul class="orderlist-details">
                    <li>
                      <h5>order id</h5>
                      <p>' . $data['id'] . '</p>
                    </li>
                    <li>
                      <h5>Total Item</h5>
                      <p>' . fetchOrderItemCount($con, $oid) . ' Items</p>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-4">
                  <ul class="orderlist-details">
                    <li>
                      <h5>Order Time</h5>
                      <p>' . date_format($date, 'dS M Y') . '</p>
                    </li>
                    <li>
                      <h5>Total<small>(Incl. VAT)</small></h5>
                      <p>RM ' . $data['grandtotal'] . '</p>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-4">
                  <div class="orderlist-deliver">
                    <h5>Delivery location</h5>
                    <p>' . $data['address'] . '</p>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="table-scroll">
                    <table class="table-list">
                      <thead>
                        <tr>
                          <th scope="col">SL No</th>
                          <th scope="col">Product</th>
                          <th scope="col">Name</th>
                          <th scope="col">Price</th>
                          <th scope="col">Quantity</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>';
    $count = 0;
    $itemlist = fetchOrderItemDetails($con, $oid);
    foreach ($itemlist as $item) {
      $accord .= '<tr>
                          <td>
                            <h5>' . str_pad($count + 1, 2, 0, STR_PAD_LEFT) . '</h5>
                          </td>
                          <td><img src="' . $item['img1'] . '" alt="product"></td>
                          <td>
                            <h5>' . $item['title'] . '</h5>
                          </td>
                          <td>
                            <h5>RM ' . $item['price'] . '</h5>
                          </td>
                          <td>
                            <h5>' . $item['quantity'] . '</h5>
                          </td>
                          <td>
                            <h5>RM ' . $item['total'] . '</h5>
                          </td>
                        </tr>';
      $count++;
    }
    $accord .= '</tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>';
    $_SESSION['ocount'] = $_SESSION['ocount'] + 1;
  }
  ob_clean();
  ob_end_clean();
  echo $accord;
}

function getPagination($cat, $title, $onPage)
{
  $con = connectDb();
  $page = fetchStoreProductCount($con, $cat, $title);
  $paging = $page / 10;
  $paging = intval(sprintf("%d", $paging));
  $paging = $paging + 1;
  $loop = 7;
  $temp = $loop / 2;
  $temp = sprintf("%d", $temp);
  $start = $onPage - $temp;
  $end = $onPage + $temp;

  if ($start > 1) {
    echo '<button class="btn btn-outline pagination-b" ';
    echo ' value="1">|<</button>';
  }

  for ($i = 1; $i <= $loop; $i++) {
    if ($start <= $paging && $start <= $end) {
      if ($start > 0) {
        echo '<button class="btn btn-outline pagination-b" ';
        if ($start == $onPage) {
          //   echo 'disabled="disabled" ';
        }
        echo ' value="' . $start . '">' . $start . '</button>';
      }
    }
    $start++;
  }
  if ($end < $paging) {
    echo '<button class="btn btn-outline pagination-b" ';
    echo ' value="' . $paging . '">>|</button>';
  }
}

if (isset($_POST['updatePassword'])) {
  $cid = $_POST['cid'];
  $password = $_POST['password'];
  if ($password == 12345678) {
    $_SESSION['message'] = '<div class="alert-info py-2 px-2">
            <p><strong>Failed!</strong> Kindly change your password.</p>
          </div>';
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
  }
  $con = connectDb();
  $res = savePassword($con, $cid, $password);
  $_SESSION['message'] = '<div class="alert-info py-2 px-2">
            <p><strong>Failed!</strong> update password failed.</p>
          </div>';
  if ($res) {
    $_SESSION['message'] = '<div class="alert-success py-2 px-2">
            <p><strong>Success!</strong> Password Successfuly updated.</p>
          </div>';
  }


  header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php");
}
function checkPassword()
{
  $config = parse_ini_file(__DIR__ . "../../jsheetconfig.ini");
  $username = $_SESSION['username'];
  $con = connectDb();
  $res = checkDefaultPassword($con, $username);
  if ($res) {
    $_SESSION['message'] = '<div class="alert-info py-2 px-2">
            <p><strong>Update Password!</strong> Kindly change your password. <a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/profile.php">click here</a></p>
          </div>';
  }
}
function fetchBannerList($con, $date)
{
  $dataList = array();
  $query = "SELECT `path` FROM storebanner WHERE '" . $date . "' > start AND '" . $date . "' < end";

  $stmt = mysqli_prepare($con, $query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while ($row = $result->fetch_assoc()) {
    $dataList[] = $row;
  }
  mysqli_stmt_close($stmt);

  return $dataList;
}
