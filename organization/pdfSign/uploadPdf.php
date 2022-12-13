<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>
<!DOCTYPE>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <!--
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>
    <script src="../../js/bootstrap.min.js" ></script>
    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
-->

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <style>
    .buttonAsLink {

        background: none !important;

        color: inherit;

        border: none;

        font: inherit;

        cursor: pointer;



    }



    table {

        border-collapse: collapse;

        width: 100%;

    }



    th {

        background: grey;

        color: black;

    }



    table,

    td,

    th {

        border: 1px solid black;

        text-align: center;

    }

    </style>

    <link rel="stylesheet" href="./../../css/jquery-ui.min.css">

    <!--<script src="./../../js/jquery-ui.js"></script> -->

  <script src="./../../js/jquery-ui.min.js"></script>

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

</head>
<body class="fixed-nav">
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

                <li class="breadcrumb-item">PDF Sign</li>

                <li class="breadcrumb-item active">Upload pdf</li>

            </ol>
        </div>



        <div class="container">

            <form method="POST" action="../../phpfunctions/signPDF.php" class="needs-validation" id="f" novalidate enctype="multipart/form-data" >

                <div id="jobListForm">

                    <?php

                  if (isset($_SESSION['feedback'])) {

                      echo $_SESSION['feedback'];

                      unset($_SESSION['feedback']);

                  }

              ?>

	            <div class="form-group row">

		            <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">
			            <?php echo $_SESSION['clientas']; ?>
		            </label>

		            <div class="col-sm-10">

			            <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
		dropDownListOrganizationClientCompanyActive3();   ?>

			            <div class="invalid-feedback">

				            Please enter a client

			            </div>

		            </div>

	            </div>

	                <div class="form-group row">

		                <label for="myfile" class="col-sm-2 col-form-label col-form-label-lg">PDF Upload</label>

		                <div class="col-sm-10">

			                <input id="myfile" class="form-control" type="file" name="myfile" onchange="filePDFValidation(this)" accept="application/pdf" required />

			                <div class="invalid-feedback">

			                </div>

		                </div>

	                </div>

                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label col-form-label-lg"></label>

                        <div class="col-sm-10">


	                        <button id="hidden-submit" name='uploadPdf' class="btn btn-primary btn-lg btn-block"

	                                type='submit'>Submit</button>

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

<script>
    function filePDFValidation(a){
        var fileInput = a;

        var filePath = fileInput.value;
        var allowedExtensions = /(\.pdf)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload file having extensions .pdf only.');
            fileInput.value = '';
            return false;
        }
    }
</script>

</html>