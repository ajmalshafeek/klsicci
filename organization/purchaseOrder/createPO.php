<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/purchaseOrder.php");
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <style>

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="fixed-nav ">
  <?php
    include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Purchase Order</li>
        <li class="breadcrumb-item active">Create Purchase Order</li>
      </ol>
    </div>
    <div class="container">
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
      ?>
      <form class="" action="../../phpfunctions/purchaseOrder.php" method="post">
        <div class="form-group row">

          <!-- SUPPLIER -->
          <label for="supplierName" class="col-sm-2 col-form-label col-form-label-lg">Supplier</label>
          <div class="col-sm-10">
              <select class="form-control" name="supplier" required>
                <?php suppliersDropDownOptionsAll() ?>
              </select>
              <div class="invalid-feedback">
                  Please select supplier
              </div>
          </div>

        </div>

        <hr>

        <div class="form-group row">

          <!-- SHIPPING VIA -->
          <label for="shippingVia" class="col-sm-2 col-form-label col-form-label-lg">Shipping Via</label>
          <div class="col-sm-4">
              <input class="form-control" type="text" name="shippingVia" placeholder="Exp: Pos Laju, J&T, FedEx ..." required>
              <div class="invalid-feedback">
                  Please insert shipping via
              </div>
          </div>

          <!-- DELIVERY DATE -->
          <label for="deliveryDate" class="col-sm-2 col-form-label col-form-label-lg">Delivery Date</label>
          <div class="col-sm-4">
              <input class="form-control" type="date" name="deliveryDate" required>
              <div class="invalid-feedback">
                  Please insert Delivery Date
              </div>
          </div>

        </div>
        <div class="form-group row">

          <div class="col-sm-6">
          </div>

          <!-- PURCHASE ORDER DATE -->
          <label for="poDate" class="col-sm-2 col-form-label col-form-label-lg">PO Date</label>
          <div class="col-sm-4">
              <input class="form-control" type="date" value="<?php echo date("Y-m-d") ?>" name="poDate">
              <div class="invalid-feedback">
                  Please insert Purchase Order date
              </div>
          </div>

        </div>

        <hr>
        <script type="text/javascript">
        function addItemlist(){
          var count = document.getElementById("itemListCount").value;
          var countInc = count*1 + 1;
          //var element = document.getElementById('itemList').innerHTML;
          var newElement = '<div id="element' + countInc + '" class="form-group row"><div class="col-sm-6"><input class="form-control" type="text" name="product' + countInc + '" placeholder="Product Name/Description" required><div class="invalid-feedback">Please insert Product</div></div><div class="col-sm-2"><input class="form-control" type="number" min="1" name="qty' + countInc + '" placeholder="Quantity" required><div class="invalid-feedback">Please insert Quantity</div></div><div class="col-sm-4"><input class="form-control" type="text" name="price' + countInc + '" placeholder="Unit Price(RM)" required><div class="invalid-feedback">Please insert Price</div></div></div>';

          var div = document.createElement('div');
          div.innerHTML = newElement;
          document.getElementById("itemList").appendChild(div);

          document.getElementById("itemListCount").value = countInc;
        }

        function removeItemList(){
          var count = document.getElementById("itemListCount").value;

          if (count > 1) {
            document.getElementById("element" + count).remove();
            document.getElementById("itemListCount").value = count*1 - 1;
            var updatedElement = document.getElementById('itemList').innerHTML;
            console.log(updatedElement);
          }
        }
        </script>
        <div class="form-group row">
          <!-- ITEM LIST -->
          <div class="col-sm-6">
              <input class="form-control" type="text" name="product1" placeholder="Product Name/Description" required>
              <div class="invalid-feedback">
                  Please insert Product
              </div>
          </div>
          <div class="col-sm-2">
              <input class="form-control" type="number" min="1" name="qty1" placeholder="Quantity" required>
              <div class="invalid-feedback">
                  Please insert Quantity
              </div>
          </div>
          <div class="col-sm-4">
              <input class="form-control" type="number" name="price1" placeholder="Unit Price(RM)" required>
              <div class="invalid-feedback">
                  Please insert Price
              </div>
          </div>
        </div>
        <div id="itemList"></div>
        <div class="form-group row">
          <!-- ADD/REMOVE ROW BUTTON-->
          <input id="itemListCount" name="maxNum" type="number" value="1" hidden>
          <div class="col-md-8">
            <button onclick="addItemlist()" class="btn btn-primary btn-lg btn-block" type="button" name="button"><i class="fa fa-plus"></i></button>
          </div>
          <div class="col-md-4">
            <button onclick="removeItemList()" class="btn btn-primary btn-lg btn-block" type="button" name="button"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <hr>

        <div class="form-group row">

          <!-- REMARKS -->
          <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
          <div class="col-sm-10">
              <textarea class="form-control" name="remarks" rows="8" cols="80"></textarea>
              <div class="invalid-feedback">
                  Please insert Remarks
              </div>
          </div>

        </div>

        <div class="form-group row">
          <div class="col-sm-2">
          </div>
          <div class="col-sm-10">
            <button class="btn btn-primary btn-lg btn-block" type="submit" name="createPO">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
  </a>
  <div class="footer">
      <p>Powered by JSoft Solution Sdn. Bhd</p>
  </div>
</body>
</html>
