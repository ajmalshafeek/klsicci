<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
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

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
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
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item">PRODUCT</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/product.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="productForm">
                <!--(START)PRODUCT FORM-->
                  <!--(START)BRAND-->
                  <div class="form-group row">
                    <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">BRAND</label>
                    <div class="col-sm-10"   >
                      <select name="brand"  id="brand" class="form-control" required >
                        <option  value="" selected disabled >--Select Brand--</option>
                        <option value="hp">HP</option>
                        <option value="cannon">CANNON</option>
                      </select>
                      <div class="invalid-feedback">
                      Please choose brand
                    </div>
                    </div>
                  </div>
                  <!--(END)BRAND-->
                  <!--(START)DESCRIPTION KIV
                  <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label col-form-label-lg">DESCRIPTION</label>
                    <div class="col-sm-10"   >
                      <textarea class="form-control" name="description" required></textarea>
                      <div class="invalid-feedback">
                      Please enter description
                    </div>
                    </div>
                  </div>
                    (END)DESCRIPTION-->
                  <!--(START)MODEL-->
                  <div class="form-group row">
                    <label for="model" class="col-sm-2 col-form-label col-form-label-lg">MODEL</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" type="text" name="model" required>
                      <div class="invalid-feedback">
                      Please enter model
                    </div>
                    </div>
                  </div>
                  <!--(END)MODEL-->
                  <!--(START)SERIAL NUMBER-->
                  <div class="form-group row">
                    <label for="serialNum" class="col-sm-2 col-form-label col-form-label-lg">SERIAL NUMBER</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" type="text" name="serialNum" required>
                      <div class="invalid-feedback">
                      Please enter serial number
                    </div>
                    </div>
                  </div>
                  <!--(END)SERIAL NUMBER-->
                  <!--(START)STATUS-->
                  <div class="form-group row">
                    <label for="status" class="col-sm-2 col-form-label col-form-label-lg">CONTRACT STATUS</label>
                    <div class="col-sm-10">
                      <select name="status"  id="status" class="form-control">
                        <option  value="" selected disabled >--Select Contract Status--</option>
                        <option value="0">TG</option>
                        <option value="1">RENTAL</option>
                        <option value="2">AD HOC</option>
                      </select>
                      <div class="invalid-feedback">
                      Please choose contract status
                    </div>
                    </div>
                  </div>
                  <!--(END)STATUS-->
                  <!--(START)REMARKS-->
                  <div class="form-group row">
                    <label for="remark" class="col-sm-2 col-form-label col-form-label-lg">REMARKS</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="remarks"></textarea>
                      <div class="invalid-feedback">
                      Please enter remarks
                    </div>
                    </div>
                  </div>
                  <!--(END)REMARKS-->
                <!--(END)PRODUCT FORM-->

<!--
                <div class="form-group row">
                    <label for="userName" class="col-sm-2 col-sm-2 col-form-label col-form-label-lg">USERNAME</label>
                      <div class="col-sm-10"   >
                        <input type="text" class="form-control" placeholder="Username" id="userName" name="userName" required></input>
                        <div class="invalid-feedback">
                        Please Enter Username
                      </div>
                    </div>
                  </div>
                  -->
                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <button name='addProduct'
                          <?php
                              require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

                              $isInLimit=isInLimit($_SESSION['orgId'],2,"client");
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
