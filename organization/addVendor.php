
<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
 session_name($config['sessionName']);
   session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");	

?>
<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

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
        <li class="breadcrumb-item active">ADD VENDOR</li>

      </ol>

      <div class="container" >
        <form method="POST" action="../phpfunctions/vendor.php" class="needs-validation" novalidate >
          <?php
                if (isset($_SESSION['feedback'])) {
                    echo $_SESSION['feedback'];
                    unset($_SESSION['feedback']);
                }
            ?>
          <div id="criteriaForm">
            <div class="form-group row">
              <label for="vendorName" class="col-sm-2 col-form-label col-form-label-lg">COMPANY NAME</label>
              <div class="col-sm-10"   >
                <input type="text" placeholder="Enter Vendor Company Name"  class="form-control" id="vendorName" name="vendorName" required></input>
              <div class="invalid-feedback">
                Please enter company name
              </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="vendorRegNo" class="col-sm-2 col-form-label col-form-label-lg">REG. NO</label>
              <div class="col-sm-10"   >
                <input type="text" placeholder="Enter Vendor Company Registration No. "  class="form-control" id="vendorRegNo" name="vendorRegNo" required></input>
              <div class="invalid-feedback">
                Please enter company registration no
              </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="vendoAddress" class="col-sm-2 col-form-label col-form-label-lg">ADDRESS</label>
              <div class="col-sm-10"   >
                <input type="text"  placeholder="Enter Vendor Address" class="form-control" id="vendorAddress" name="vendorAddress" ></input>
              </div>
            </div>

            <div class="form-group row">
              <label for="vendorContactNo" class="col-sm-2 col-form-label col-form-label-lg">CONTACT NO.</label>
              <div class="col-sm-10"   >
                <input type="text"  placeholder="Enter Vendor Contact No. " class="form-control" id="vendorContactNo" name="vendorContactNo" ></input>
              </div>
            </div>


            <div class="form-group row">
              <label for="vendorEmail" class="col-sm-2 col-form-label col-form-label-lg">EMAIL</label>
              <div class="col-sm-10"   >
                <input type="email"  placeholder="Enter Vendor Email" class="form-control" id="vendorEmail" name="vendorEmail" ></input>
                <div class="invalid-feedback">
                  Please enter valid email address
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="vendorUsername" class="col-sm-2 col-form-label col-form-label-lg">USERNAME</label>
              <div class="col-sm-10"   >
                <input type="text" placeholder="Enter Vendor Username"  class="form-control" id="vendorUsername" name="vendorUsername" required></input>
                <div class="invalid-feedback">
                Please enter username
              </div>
              </div>
            </div>

            
          <div class="form-group row">
              <label class="col-sm-2 col-form-label col-form-label-lg"></label>
              <div class="col-sm-10">
                  <button name='addVendor'
                  <?php
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
                    
                    $isInLimit=isInLimit($_SESSION['orgId'],2,"vendor");
                    if($isInLimit){
                      ?>
                      disabled 
                      class="btn btn-secondary btn-lg btn-block"
                      <?php
                    }else{
                      ?>
                        class="btn btn-primary btn-lg btn-block"
                      <?php
                    }
                  ?>
                  type='submit' >SUBMIT</button>
              </div>
          </div>

          
          </div>
        </form>
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

