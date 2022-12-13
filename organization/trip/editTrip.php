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
            <li class="breadcrumb-item ">Trip</li>
            <li class="breadcrumb-item active">Edit Trip</li>
        </ol>
    </div>
    <div class="container">
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
                Please select a driver
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
                <label for="placeDes" class="col-sm-2 col-form-label col-form-label-lg">Collection Point</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="collectionPoint" name="collectionPoint" required>
                    <div class="invalid-feedback">
                        Please enter collection point
                    </div>
                </div>

                <label for="shipment" class="col-sm-2 col-form-label col-form-label-lg">Delivery Point</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="deliveryPoint" name="deliveryPoint" required>
                    <div class="invalid-feedback">
                        Please enter delivery point
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
                <label for="placeDes" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="remarks" name="remarks">
                    <div class="invalid-feedback">
                        Please enter remarks
                    </div>
                </div>

                <label for="shipment" class="col-sm-2 col-form-label col-form-label-lg">Diesel(optional)</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="diesel" name="diesel">
                    <div class="invalid-feedback">
                        Please enter diesel
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="placeDes" class="col-sm-2 col-form-label col-form-label-lg">Toll(optional)</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="toll" name="toll">
                    <div class="invalid-feedback">
                        Please enter toll
                    </div>
                </div>

                <label for="shipment" class="col-sm-2 col-form-label col-form-label-lg">Driver Trip</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="driverTrip" name="driverTrip">
                    <div class="invalid-feedback">
                        Please enter driver trip
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="placeDes" class="col-sm-2 col-form-label col-form-label-lg">Maintenance</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="maintenance" name="maintenance">
                    <div class="invalid-feedback">
                        Please enter maintenance
                    </div>
                </div>

                <label for="shipment" class="col-sm-2 col-form-label col-form-label-lg">Payment Status</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" id="paymentStatus" name="paymentStatus" required>
                    <div class="invalid-feedback">
                        Please enter payment status
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
                <input type="hidden" name="tripToEdit" id="tripToEdit" value="<?php echo $_SESSION['tripToEdit'];  ?>" />
              <button name='editTripProcess' class="btn btn-primary btn-lg btn-block" type='submit'>Submit</button>
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
<script>
  $( document ).ready(function() {
        const tid =""+"<?php echo $_SESSION['tripToEdit']; ?>";
        const date =""+"<?php echo $_SESSION['tripDateEdit']; ?>";
        const client =""+"<?php echo $_SESSION['tripClientEdit']; ?>";
        const truck =""+"<?php echo $_SESSION['tripTruckEdit']; ?>";
        const place=""+"<?php echo $_SESSION['tripPlaceEdit']; ?>";
        const ship =""+"<?php echo $_SESSION['tripShipmentEdit']; ?>";
        const driver =""+"<?php echo $_SESSION['tripDriverEdit']; ?>";
        const collectionPoint =""+"<?php echo $_SESSION['collectionPointEdit']; ?>";
        const deliveryPoint =""+"<?php echo $_SESSION['deliveryPointEdit']; ?>";
        const remarks =""+"<?php echo $_SESSION['remarksEdit']; ?>";
        const diesel =""+"<?php echo $_SESSION['dieselEdit']; ?>";
        const toll =""+"<?php echo $_SESSION['tollEdit']; ?>";
        const driverTrip =""+"<?php echo $_SESSION['driverTripEdit']; ?>";
        const maintenance =""+"<?php echo $_SESSION['maintenanceEdit']; ?>";
        const paymentStatus =""+"<?php echo $_SESSION['paymentStatusEdit']; ?>";
        const amount =""+"<?php echo $_SESSION['tripAmountEdit']; ?>";
        $('#date').val(date);
        $('#amount').val(amount);
        $('#shipment').val(ship);
        $('#placeDes').val(place);
        $('#vehicleNumber').val(truck);
        $('#driver').val(driver);
        $('#clientCompanyId').val(client);
        $('#collectionPoint').val(collectionPoint);
        $('#deliveryPoint').val(deliveryPoint);
        $('#remarks').val(remarks);
        $('#diesel').val(diesel);
        $('#toll').val(toll);
        $('#driverTrip').val(driverTrip);
        $('#maintenance').val(maintenance);
        $('#paymentStatus').val(paymentStatus);
        $('#tripToEdit').val(tid);

    });
</script>
</body>

</html>