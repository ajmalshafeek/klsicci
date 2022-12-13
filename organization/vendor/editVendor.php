<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}

$id=$_SESSION['vendorId'];
$regNo=$_SESSION['regNo'];
$name=$_SESSION['name'];
$address=$_SESSION['address'];
$contactNo=$_SESSION['contactNo'];
$emailAddress=$_SESSION['emailAddress'];



?>
<!DOCTYPE html >

<html>
<head><meta https-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <!--
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/bootstrap.min.js" ></script>
-->
<?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
      require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/staff/moreForm/form.php");
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
/*
      function loadLandscape(id){
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/organizationUser.php?',
              data : {loadLandscape:id},
              success: function (data) {
              console.log(data);

              details= JSON.parse(data);
              document.getElementById('address1').value = details.address1;
              document.getElementById('address2').value = details.address2;
              document.getElementById('city').value = details.city;
              document.getElementById('postalCode').value = details.postalCode;
              document.getElementById('state').value = details.state;
              document.getElementById('contact').value = details.contact;
              document.getElementById('married').value = details.married;
              document.getElementById('education').value = details.education;
              document.getElementById('license').value = details.license;
              }
          });
        } */
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
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">VENDOR</li>
        <li class="breadcrumb-item active">EDIT VENDOR</li>

      </ol>
    </div>
      <div class="container" >
          <form method="POST" action="../../phpfunctions/vendor.php" class="needs-validation" novalidate >
          <?php
                if (isset($_SESSION['feedback'])) {
                    echo $_SESSION['feedback'];
                    unset($_SESSION['feedback']);
                }
            ?>
            <div id="criteriaForm">
              <!-- Staff staffId -->
              <div class="form-group row">
                <label for="staffId" class="col-sm-2 col-form-label col-form-label-lg">ID</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $id ?>"  class="form-control"  required disabled />
                    <input type="hidden" value="<?php echo $id ?>"  class="form-control" id="vendorId" name="vendorId" required />
                <div class="invalid-feedback">
                  Please enter vendor id
                </div>
                </div>
              </div>

              <!-- Staff fullName -->
              <div class="form-group row">
                <label for="staffFullName" class="col-sm-2 col-form-label col-form-label-lg">Vendor Name</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $name ?>"  class="form-control" id="vendorName" name="vendorName" required />
                <div class="invalid-feedback">
                  Please enter vendor name
                </div>
                </div>
              </div>

              <!-- Staff name -->
              <div class="form-group row">
                <label for="staffName" class="col-sm-2 col-form-label col-form-label-lg">Register No</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $regNo ?>"  class="form-control" id="regNo" name="regNo" required />
                <div class="invalid-feedback">
                  Please enter register no
                </div>
                </div>
              </div>

              <!-- Staff email -->
              <div class="form-group row">
                <label for="staffName" class="col-sm-2 col-form-label col-form-label-lg">Email</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $emailAddress ?>"  class="form-control" id="emailAddress" name="emailAddress" required />
                <div class="invalid-feedback">
                  Please enter email
                </div>
                </div>
              </div>

               <!-- Staff phone -->
               <div class="form-group row">
                <label for="staffPhone" class="col-sm-2 col-form-label col-form-label-lg">Phone</label>
                <div class="col-sm-10"   >
                  <input type="number" value="<?php echo $contactNo ?>"  class="form-control" id="contactNo" name="contactNo" required />
                <div class="invalid-feedback">
                  Please enter Phone
                </div>
                </div>
              </div>

              <!-- Staff username-->
              <div class="form-group row">
                <label for="staffUsername" class="col-sm-2 col-form-label col-form-label-lg">Address</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $address ?>"  class="form-control" id="address" name="address" required />
                <div class="invalid-feedback">
                  Please enter address
                </div>
                </div>
              </div>


              <?php additionalRegisterForm() ?>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                  <div class="col-sm-10">
                      <button name='editVendorProcess'
                      <?php
                        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

                        $isInLimit=isInLimit($_SESSION['orgId'],2,"staff");
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

                        type='submit' style="text-align: center;">UPDATE</button>
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
    <script type="text/javascript">
      loadLandscape(<?php echo $id; ?>);
    </script>
</body>
</html>
