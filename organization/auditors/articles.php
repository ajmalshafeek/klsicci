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
          <li class="breadcrumb-item ">Auditors</li>
          <li class="breadcrumb-item active">IIAM Articles</li>
      </ol>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <?php if($_SESSION['role']==1){ ?>
                <img src="img/adm-article-1.png" class="tech-1" style="max-width: 100%" />
                <img src="img/adm-article-2.png" class="tech-2" style="max-width: 100%" />
                <img src="img/adm-article-1.png" class="tech-3" style="max-width: 100%" />
                <img src="img/adm-article-4.png" class="tech-4" style="max-width: 100%" />
                <?php } else { ?>
                <img src="img/cra-artocle-1.png" class="tech-1" style="max-width: 100%" />
                <img src="img/cra-artocle-2.png" class="tech-2" style="max-width: 100%" />
                <?php } ?>
                <script>
                    $('.tech-2').css('display','none');
                    <?php if($_SESSION['role']==1){ ?>
                    $('.tech-3').css('display','none');
                    $('.tech-4').css('display','none');
                    <?php } ?>

                    $('.tech-1').on('click',function () {
                        $('.tech-2').css('display','initial');
                        $('.tech-1').css('display','none');
                    });
                    <?php if($_SESSION['role']==1){ ?>
                    $('.tech-2').on('click',function () {
                        $('.tech-3').css('display','initial');
                        $('.tech-2').css('display','none');
                    });
                    $('.tech-3').on('click',function () {
                        $('.tech-4').css('display','initial');
                        $('.tech-3').css('display','none');
                    });
                    $('.tech-4').on('click',function () {
                        $('.tech-1').css('display','initial');
                        $('.tech-4').css('display','none');
                    });
                    <?php } else { ?>
                    $('.tech-2').on('click',function () {
                        $('.tech-1').css('display','initial');
                        $('.tech-2').css('display','none');
                    });
                    <?php } ?>
                </script>
                <?php /*if(isset($_POST['demo'])){
                    echo "<div class='alert-success py-2 px-2 text-center'>Successfully Submit</div>";
                } else{?>
                    <form method="post">
                        <center><button type="submit" name="demo" class="btn btn-default mt-30" value="1">Submit</button></center>
                    </form> <?php } */ ?>
            </div>
        </div>
        <?php /*
      <form method="POST" action="../../phpfunctions/trip.php" class="needs-validation" enctype="multipart/form-data" novalidate>
        <?php
        if (isset($_SESSION['feedback'])) {
          echo $_SESSION['feedback'];
          unset($_SESSION['feedback']);
        }
        ?>
        <div id="vehicleForm">
          <!--(START) FORM-->
          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label col-form-label-lg">Trip Date</label>
            <div class="col-sm-4">
              <input class="form-control" type="date" id="date" name="date" required>
              <div class="invalid-feedback">
                Please enter trip date
              </div>
            </div>
              <label for="client" class="col-sm-2 col-form-label col-form-label-lg">Client</label>
            <div class="col-sm-4">
              <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
		          dropDownListOrganizationClientCompanyActive3();   ?>
              <div class="invalid-feedback">
                Please select client
              </div>
            </div>
          </div>
          <!--(START)BRAND-->
          <div class="form-group row">
          <label for="driver" class="col-sm-2 col-form-label col-form-label-lg">Driver</label>
            <div class="col-sm-4">
              <select name="driver" id="driver" class="form-control" required>
                <option value="" selected disabled>--Select Driver--</option>

                  <?php getDropDownListOrgStaffListNamesForVehicles(); ?>
              </select>
              <div class="invalid-feedback">
                Please select a Vehicle Category
              </div>
            </div>
            <label for="vehicleNumber" class="col-sm-2 col-form-label col-form-label-lg">Vehicle Number</label>
            <div class="col-sm-4">
              <select name="vehicleNumber" id="vehicleNumber" class="form-control" required>
                <option value="" selected>--Select Vehicle Number--</option>
                <?php getVehicleNumber(); ?>
              </select>
              <div class="invalid-feedback">
                Please select a Vehicle Number
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="placeDes" class="col-sm-2 col-form-label col-form-label-lg">Place Description</label>
            <div class="col-sm-4">
              <input class="form-control" type="text" id="placeDes" name="placeDes" required>
              <div class="invalid-feedback">
                Please enter place description
              </div>
            </div>

          
            <label for="shipment" class="col-sm-2 col-form-label col-form-label-lg">Shipment / Document No</label>
            <div class="col-sm-4">
              <input class="form-control" type="text" id="shipment" name="shipment" required>
              <div class="invalid-feedback">
                Please enter vehicle no
              </div>
            </div>
          </div>
            <div class="form-group row">
          <label for="amount" class="col-sm-2 col-form-label col-form-label-lg">Amount</label>
            <div class="col-sm-4">
              <input class="form-control" type="text" id="amount" name="amount" required>
              <div class="invalid-feedback">
                Please enter amount
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
              <button name='addTrip' class="btn btn-primary btn-lg btn-block" type='submit'>Submit</button>
            </div>
          </div>


        </div>
      </form>
    </div>
        */ ?>
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