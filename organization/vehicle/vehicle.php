<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/vehicle.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/favIcon.ico'; ?>' />

  <?php
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");
  ?>
  <script>
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
  <style>
    .buttonAsLink {
      background: none !important;
      color: inherit;
      border: none;
      font: inherit;
      cursor: pointer;
    }
    .bg-red {
      background-color: #E32526;
    }

    i.fa.fa-minus-circle {
      font-size: 20px;
      margin-top: 10px;
      color: red;
    }
  </style>
</head>
<body class="fixed-nav">
  <?php
  include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Vehicles</li>
        <li class="breadcrumb-item active">Add Vehicle</li>
      </ol>
    </div>
    <div class="container">
      <form method="POST" action="../../phpfunctions/vehicle.php" class="needs-validation" enctype="multipart/form-data" novalidate>
        <?php
        if (isset($_SESSION['feedback'])) {
          echo $_SESSION['feedback'];
          unset($_SESSION['feedback']);
        }
        ?>
        <div id="vehicleForm">
          <!--(START)PRODUCT FORM-->

          <!--(START)BRAND-->
          <div class="form-group row">
              <label for="vehicletype" class="col-sm-2 col-form-label col-form-label-lg">Vehicle Type</label>
              <div class="col-sm-4">
                  <select name="vehicletype" id="vehicletype" class="form-control" required>
                      <option value="" selected disabled>--Select Vehicle Type--</option>
                      <?php getVehicleType(); ?>
                  </select>
                  <div class="invalid-feedback">
                      Please select a Vehicle Type
                  </div>
              </div>
            <label for="vehiclebrand" class="col-sm-2 col-form-label col-form-label-lg">Brand</label>
            <div class="col-sm-4">
              <select name="vehiclebrand" id="vehiclebrand" class="form-control" required>
                <option value="" selected disabled>--Select Brand--</option>
                <?php getVehicleBrand(); ?>
              </select>
              <div class="invalid-feedback">
                Please choose vehicle brand
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="vehiclecategory" class="col-sm-2 col-form-label col-form-label-lg">Category</label>
            <div class="col-sm-4">
              <select name="vehiclecategory" id="vehiclecategory" class="form-control" required>
                <option value="" selected>--Select Category--</option>
                <?php getVehicleCategory(); ?>
              </select>
              <div class="invalid-feedback">
                Please select a Vehicle Category
              </div>
            </div>
              <label for="driver" class="col-sm-2 col-form-label col-form-label-lg">Driver <sub style="color:#00aecd">Optional</sub></label>
            <div class="col-sm-4">
              <select name="driver" id="driver" class="form-control">
                <option value="" selected disabled>--Select Driver--</option>
                <?php getDropDownListOrgStaffListNamesForVehicles(); ?>
              </select>
              <div class="invalid-feedback">
                Please select a Vehicle Category
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="vehicleNo" class="col-sm-2 col-form-label col-form-label-lg">Vehicle No</label>
            <div class="col-sm-4">
              <input class="form-control" type="text" id="vehicleNo" name="vehicleNo" required>
              <div class="invalid-feedback">
                Please enter vehicle no
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
              <button name='addVehicle' class="btn btn-primary btn-lg btn-block" type='submit'>Submit</button>
            </div>
          </div>


        </div>
      </form>
    </div>

  </div>

  </div>


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
  </a>
  <div class="footer">
    <p>Powered by JSoft Solution Sdn. Bhd</p>
  </div>

  </div>

</body>

</html>