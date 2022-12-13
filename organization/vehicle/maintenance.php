<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/vehicle.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/maintenance.php");
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
            <li class="breadcrumb-item active">Add Maintenance</li>
        </ol>
    </div>
    <div class="container">
        <form method="POST" action="../../phpfunctions/maintenance.php" class="needs-validation" enctype="multipart/form-data" novalidate>
            <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
            ?>
            <div id="vehicleForm">
                <!--(START)PRODUCT FORM-->
                <div class="form-group row">
                    <label for="date" class="col-sm-2 col-form-label col-form-label-lg">Date</label>
                    <div class="col-sm-10">
                        <input type="date" name="date" id="date" class="form-control" required />
                        <div class="invalid-feedback">
                            Please select a Date
                        </div>
                    </div>
                </div>
                <!--(START)BRAND-->
                <div class="form-group row">
                    <label for="vehicletype" class="col-sm-2 col-form-label col-form-label-lg">Truck Type</label>
                    <div class="col-sm-10">
                        <select name="vehicletype" id="vehicletype" class="form-control" required>
                            <option value="" selected disabled>--Select Truck Type--</option>
                            <?php getVehicleType(); ?>
                        </select>
                        <div class="invalid-feedback">
                            Please select a Truck Type
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehiclecategory" class="col-sm-2 col-form-label col-form-label-lg">Truck Number</label>
                    <div class="col-sm-10">
                        <select name="vehicleno" id="vehicleno" class="form-control" required>
                            <option value="" selected>--Select Truck Number--</option>
                            <?php getVehicleNumber(); ?>
                        </select>
                        <div class="invalid-feedback">
                            Please select a Truck Number
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehiclebrand" class="col-sm-2 col-form-label col-form-label-lg">Maintenance For</label>
                    <div class="col-sm-10">
                        <select name="maintenanceFor" id="maintenanceFor" class="form-control" required>
                            <option value="" selected disabled>--Select Maintenance For--</option>
                            <?php getMaintenanceType(); ?>
                        </select>
                        <div class="invalid-feedback">
                            Please choose maintenance for
                        </div>
                    </div></div>
                <div class="form-group row">
                    <label for="jobNo" class="col-sm-2 col-form-label col-form-label-lg">Job No</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" id="jobNo" name="jobNo" />
                        <div class="invalid-feedback">
                            Please choose job no
                        </div>
                    </div>
                    <label for="invoiceNo" class="col-sm-2 col-form-label col-form-label-lg">Invoice No</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" id="invoiceNo" name="invoiceNo" />
                        <div class="invalid-feedback">
                            Please choose invoice no
                        </div>
                    </div></div>
                <div class="form-group row">
                    <label for="workshop" class="col-sm-2 col-form-label col-form-label-lg">Workshop / Location</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="workshop" name="workshop" />
                        <div class="invalid-feedback">
                            Please choose Workshop/Location
                        </div>
                    </div></div>

                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label col-form-label-lg">Description</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="description" name="description" />
                        <div class="invalid-feedback">
                            Please choose Description
                        </div>
                    </div></div>
                <div class="form-group row">
                    <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="remarks" name="remarks" />
                        <div class="invalid-feedback">
                            Please choose remarks
                        </div>
                    </div></div>
                <div class="form-group row">
                    <label for="amount" class="col-sm-2 col-form-label col-form-label-lg">Amount</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="number" step="0.01" id="amount" name="amount" />
                        <div class="invalid-feedback">
                            Please choose amount
                        </div>
                    </div>
                    <label for="status" class="col-sm-2 col-form-label col-form-label-lg">Status</label>
                    <div class="col-sm-4">
                        <select name="status" id="status" class="form-control" required>
                            <option value="" selected>--Select Status--</option>
                            <option value="0">Pending</option>
                            <option value="1">Completed</option>
                        </select>
                        <div class="invalid-feedback">
                            Please choose status
                        </div>
                    </div></div>
                <div class="form-group row">
                    <label for="nextDate" class="col-sm-2 col-form-label col-form-label-lg">Next Date</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" id="nextDate" name="nextDate" required />
                        <div class="invalid-feedback">
                            Please select a next date
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="reminder" class="col-sm-2 col-form-label col-form-label-lg">Reminder</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="reminder" name="reminder">
                        <div class="invalid-feedback">
                            Please enter reminder
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='addMaintenance' class="btn btn-primary btn-lg btn-block" type='submit'>Submit</button>
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