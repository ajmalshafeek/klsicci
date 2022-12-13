<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store_banner.php");
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

<body class="fixed-nav ">

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
        <li class="breadcrumb-item ">Store</li>
        <li class="breadcrumb-item ">Banner</li>
        <li class="breadcrumb-item active">Add Banner</li>
      </ol>
    </div>
    <div class="container">
      <form method="POST" action="../../phpfunctions/store_banner.php" class="needs-validation" enctype="multipart/form-data" novalidate>
        <?php
        if (isset($_SESSION['feedback'])) {
          echo $_SESSION['feedback'];
          unset($_SESSION['feedback']);
        }
        ?>
        <div id="bannerForm">
          <!--(START)Banner FORM-->

          <!--banner path-->
          <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label col-form-label-lg">Banner Image</label>
            <div class="col-sm-10">
              <input class="form-control" type="file" name="path" onchange="fileJPGValidation(this)" accept="image/jpeg" required>
              <small>width 1200px hieght 400px upload jpeg and jpg format only</small>
              <div class="invalid-feedback">
                Please upload Image Banner
              </div>
            </div>
          </div>
          <!--start date-->
          <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label col-form-label-lg">Start</label>
            <div class="col-sm-10">
              <input class="form-control" type="date" name="start" required>
              <small>Banner start date</small>
              <div class="invalid-feedback">
                Please select start date
              </div>
            </div>
          </div>
          <!--end date-->
          <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label col-form-label-lg">End</label>
            <div class="col-sm-10">
              <input class="form-control" type="date" name="end" required>
              <small>Banner end date</small>
              <div class="invalid-feedback">
                Please select end date
              </div>
            </div>
          </div>
          <!-- submit button-->
          <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
              <button name='addBanner' class="btn btn-primary btn-lg btn-block" type='submit'>Submit</button>
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
  <script type="text/javascript">
    $(document).ready(function() {

      // allowed maximum input fields
      var max_input = 20;

      // initialize the counter for textbox
      var x = 1;

      // handle click event on Add More button
      $('.add-btn').click(function(e) {

        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          $('.wrapper').append(`
            <div class="row">
            <div class="input-box col-sm-11">
              <input type="text" name="serialNum[]" class="form-control " style=\"width= \"/>
            </div>
            <a href="#" class="remove-lnk"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>
          `);
          // add input field
          $('#count').html("Quantity: " + x);
        }
      });

      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function(e) {
        e.preventDefault();
        $(this).parent('.row').remove(); // remove input field
        x--; // decrement the counter
        $('#count').html("Quantity: " + x);
      })

    });

    function fileJPGValidation(a) {
      var fileInput = a;

      var filePath = fileInput.value;
      var allowedExtensions = /(\.jpg|\.jpeg)$/i;
      if (!allowedExtensions.exec(filePath)) {
        alert('Please upload file having extensions .jpeg /.jpg only.');
        fileInput.value = '';
        return false;
      }
    }

    function fileDocValidation(a) {
      var fileInput = a;

      var filePath = fileInput.value;
      var allowedExtensions = /(\.doc|\.docx|\.xls|\.xlsx|\.pdf)$/i;
      if (!allowedExtensions.exec(filePath)) {
        alert('Please upload file having extensions .doc/ .docx/ .xls/ .xlsx /.pdf only.');
        fileInput.value = '';
        return false;
      }
    }
  </script>
</body>

</html>