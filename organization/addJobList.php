
<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
 session_name($config['sessionName']);
   session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendorClient.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");

require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendorUser.php");
?>
<!DOCTYPE html>

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <!--
      <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>
    <script src="../js/bootstrap.min.js" ></script>
-->
    <?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
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
            .bg-red{
                background-color: #E32526;
            }
    </style>
   
</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">VENDOR</li>
        <li class="breadcrumb-item active">ADD JOB</li>

      </ol>

          <div class="container" >
            <form method="POST" action="../phpfunctions/jobList.php" class="needs-validation" novalidate >
          
              <div id="jobListForm">
              <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <?php
                dropDownListVendorActive2();
              ?>
              <?php
            //   dropDownVendorClientCompany();
              ?>
              
              <div class="form-group row">
                <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">JOB NAME</label>
                <div class="col-sm-10"   >
                  <input type="text" class="form-control" placeholder="Enter job name" id="jobName" name="jobName" required></input>
                  <div class="invalid-feedback">
                    Please enter job name
                  </div>
                </div>
              </div>
              
            

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                  <div class="col-sm-10">
                      <button name='addJobList' class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
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

