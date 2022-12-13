
<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}

?>
<!DOCTYPE html>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <!--
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/bootstrap.min.js" ></script>
-->
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    //      require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/staff/moreForm/form.php");
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
        .buttonAsLink{
            background:none!important;
            color:inherit;
            border:none;
            font: inherit;
            cursor: pointer;
        }
        /*
        a.buttonNav {
             -webkit-appearance: button;
             -moz-appearance: button;
             appearance: button;
             text-decoration: none;
             color: white;
             background-color:red;
         }
         */

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
            <li class="breadcrumb-item active">Expenses</li>
            <li class="breadcrumb-item active">Add Expense For</li>

        </ol>
    </div>
    <div class="container" >
        <form method="POST" action="../../../phpfunctions/bill.php" class="needs-validation" novalidate >
            <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
            ?>
            <div id="criteriaForm">
                <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label col-form-label-lg">Category</label>
                    <div class="col-sm-10"   >
                        <select name="category"  id="category" class="form-control" required >

                            <?php
                            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/bill.php");
                            categoryOptionListAll() ?>
                        </select>
                        <div class="invalid-feedback">
                            Please choose Category
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label col-form-label-lg">Expense For</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter Maintenance For"  class="form-control" id="expenseFor" name="expenseFor" required></input>
                        <div class="invalid-feedback">
                            Please enter Expense For
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='addExpenseFor' class="btn btn-primary btn-lg btn-block" type='submit' style="text-align: center;">SUBMIT</button>
                    </div>
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
</div>
</body>
</html>
