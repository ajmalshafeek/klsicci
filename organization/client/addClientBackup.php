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
        <li class="breadcrumb-item">CLIENT</li>
        <li class="breadcrumb-item active">ADD CLIENT</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/clientCompany.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="clientForm">
                <div class="form-group row">
                  <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">COMPANY NAME</label>
                  <div class="col-sm-10"   >
                    <input type="text" class="form-control" placeholder="Enter company name" id="clientName" name="clientName" required></input>
                    <div class="invalid-feedback">
                    Please enter client name
                  </div>
                  </div>
                </div>


              <div class="form-group row">
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">ADDRESS 1</label>
                <div class="col-sm-10"   >
                  <input type="text" class="form-control" placeholder="Street address, P.O box, C/O"  id="address1" name="address1" required ></input>
                  <div class="invalid-feedback">
                    Please enter address 1.
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="address2" class="col-sm-2 col-form-label col-form-label-lg">ADDRESS 2</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="Building, Suite, Unit, Floor"  id="address2" name="address2" ></input>
                    <div class="invalid-feedback">
                      Please enter address 2.
                    </div>
                  </div>

              </div>

              <div class="form-group row">
                <label for="city" class="col-sm-2  col-form-label col-form-label-lg">CITY / TOWN</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="City / Town"  id="city" name="city" required></input>
                    <div class="invalid-feedback">
                      Please enter city / town name
                    </div>
                  </div>
              </div>

            <div class="form-group row">
              <label for="postalCode" class="col-sm-2  col-form-label col-form-label-lg">ZIP  / POSTAL CODE</label>
                <div class="col-sm-10"   >

                  <input type="text" class="form-control" placeholder="Zip / Postal Code"  id="postalCode" name="postalCode" required></input>
                  <div class="invalid-feedback">
                    Please enter zip /postal code
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="state" class="col-sm-2  col-form-label col-form-label-lg">STATE</label>
                  <div class="col-sm-10"   >

                    <select name="state"  id="state" class="form-control" required >
                      <option  value="" selected disabled >--Select A State--</option>
                      <option value="Johor">Johor</option>
                      <option value="Kedah">Kedah</option>
                      <option value="Kelantan">Kelantan</option>
                      <option value="Kuala Lumpur">Kuala Lumpur</option>
                      <option value="Labuan">Labuan</option>
                      <option value="Malacca">Malacca</option>
                      <option value="Negeri Sembilan">Negeri Sembilan</option>
                      <option value="Pahang">Pahang</option>
                      <option value="Perak">Perak</option>
                      <option value="Perlis">Perlis</option>
                      <option value="Penang">Penang</option>
                      <option value="Sabah">Sabah</option>
                      <option value="Sarawak">Sarawak</option>
                      <option value="Selangor">Selangor</option>
                      <option value="Terengganu">Terengganu</option>
                    </select>
                  </div>
              </div>

              <div class="form-group row">
              <label for="clientContactNo" class="col-sm-2  col-form-label col-form-label-lg">PHONE NO.</label>
                <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxxx"  id="clientContactNo" name="orgContactNo" ></input>
                    <div class="invalid-feedback">
                      Please enter client phone no.
                    </div>
                  </div>
              </div>



              <div class="form-group row">
                <label for="clientFaxNo" class="col-sm-2  col-form-label col-form-label-lg">FAX NO.</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxx"  id="clientFaxNo" name="orgFaxNo" ></input>
                    <div class="invalid-feedback">
                      Please enter client fax no.
                    </div>
                  </div>
              </div>



                <div class="form-group row">
                  <label for="clientEmail" class="col-sm-2 col-form-label col-form-label-lg">CLIENT EMAIL</label>
                  <div class="col-sm-10"   >
                    <input type="email"  placeholder="Email Address" required class="form-control" id="clientEmail" name="clientEmail"  ></input>
                    <div class="invalid-feedback">
                      Please enter client email address
                    </div>
                  </div>
                </div>


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
                          <button name='addClientCompanyOrg'
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
