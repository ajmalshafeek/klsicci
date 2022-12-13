
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
$id = $_SESSION['idEdit'];
$staffId = $_SESSION['staffIdEdit'];
$fullName=$_SESSION['fullNameEdit'];
$name=$_SESSION['nameEdit'];
$email=$_SESSION['emailEdit'];
$username=$_SESSION['usernameEdit'];
$password=$_SESSION['passwordEdit'];
$role=$_SESSION['roleEdit'];
$phone=$_SESSION['phone'];
$staffDesignation=$_SESSION['staffDesignation'];
$ic=$_SESSION['ic'];
$passport=$_SESSION['passport'];
$perkeso=$_SESSION['perkeso'];
$kwsp=$_SESSION['kwsp'];

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
              document.getElementById('staffDesignation').value = details.staffDesignation;
              document.getElementById('ICNum').value = details.ic;
              document.getElementById('staffPassport').value = details.passport;
              document.getElementById('staffPerkeso').value = details.perkeso;
              document.getElementById('staffKWSP').value = details.kwsp;
              }
          });
        }
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
        <li class="breadcrumb-item active">STAFF</li>
        <li class="breadcrumb-item active">EDIT STAFF</li>

      </ol>
    </div>
      <div class="container" >
          <form method="POST" action="../../phpfunctions/organizationUser.php" class="needs-validation" novalidate >
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
                  <input type="text" value="<?php echo $staffId ?>"  class="form-control" id="staffId" name="staffId" required />
                <div class="invalid-feedback">
                  Please enter staff id
                </div>
                </div>
              </div>

              <!-- Staff fullName -->
              <div class="form-group row">
                <label for="staffFullName" class="col-sm-2 col-form-label col-form-label-lg">Full Name</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $fullName ?>"  class="form-control" id="staffFullName" name="fullName" required />
                <div class="invalid-feedback">
                  Please enter staff full name
                </div>
                </div>
              </div>

              <!-- Staff name -->
              <div class="form-group row">
                <label for="staffName" class="col-sm-2 col-form-label col-form-label-lg">Name</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $name ?>"  class="form-control" id="staffName" name="name" required />
                <div class="invalid-feedback">
                  Please enter staff name
                </div>
                </div>
              </div>

                <div class="form-group row">
                    <label for="ICNum" class="col-sm-2 col-form-label col-form-label-lg">IC No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter IC Number"  value="<?php echo $ic; ?>" class="form-control" id="ICNum" name="ICNum" required />
                        <div class="invalid-feedback">
                            Please enter IC number
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staffPassport" class="col-sm-2 col-form-label col-form-label-lg">Passport No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter Passport Number" value="<?php echo $passport; ?>" class="form-control" id="staffPassport" name="staffPassport" required />
                        <div class="invalid-feedback">
                            Please enter Passport Number
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staffPerkeso" class="col-sm-2 col-form-label col-form-label-lg">Perkeso No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter Perkeso Number" value="<?php echo $perkeso; ?>" class="form-control" id="staffPerkeso" name="staffPerkeso" required />
                        <div class="invalid-feedback">
                            Please enter Perkeso Number
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staffKWSP" class="col-sm-2 col-form-label col-form-label-lg">KWSP No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter KWSP Number" value="<?php echo $kwsp; ?>" class="form-control" id="staffKWSP" name="staffKWSP" required />
                        <div class="invalid-feedback">
                            Please enter KWSP Number
                        </div>
                    </div>
                </div>

              <!-- Staff email -->
              <div class="form-group row">
                <label for="staffemail" class="col-sm-2 col-form-label col-form-label-lg">Email</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $email ?>"  class="form-control" id="staffemail" name="email" required />
                <div class="invalid-feedback">
                  Please enter staff email
                </div>
                </div>
              </div>

               <!-- Staff phone -->
               <div class="form-group row">
                <label for="staffPhone" class="col-sm-2 col-form-label col-form-label-lg">Phone</label>
                <div class="col-sm-10"   >
                  <input type="number" value="<?php echo $phone ?>"  class="form-control" id="staffPhone" name="phone" required />
                <div class="invalid-feedback">
                  Please enter staff Phone
                </div>
                </div>
              </div>

              <!-- Staff username-->
              <div class="form-group row">
                <label for="staffUsername" class="col-sm-2 col-form-label col-form-label-lg">Username</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $username ?>"  class="form-control" id="staffUsername" name="username" required />
                <div class="invalid-feedback">
                  Please enter staff username
                </div>
                </div>
              </div>

              <!-- Staff password-->
              <div class="form-group row">
                <label for="staffUsername" class="col-sm-2 col-form-label col-form-label-lg">Password</label>
                <div class="col-sm-10"   >
                  <?php if($_SESSION['role']==42) { ?>
                    <input type="text" value="<?php echo $password ?>"  class="form-control" id="staffPassword" name="password" required />
                    <?php }else{ ?>
                      <input type="text" value="<?php echo $password ?>"  class="form-control" id="staffPassword" name="password" required readonly />
                    <?php } ?>
                <div class="invalid-feedback">
                  Please enter staff password
                </div>
                </div>
              </div>

              <!-- Staff Role-->
              <div class="form-group row">
                <label for="staffUsername" class="col-sm-2 col-form-label col-form-label-lg">Role</label>
                <div class="col-sm-10"   >
                  <!--<input type="text" value="<?php echo $password ?>"  class="form-control" id="staffPassword" name="password" required></input>-->
                  <select id="roles" name="role" class="form-control role" required>
                    <option value="">-Select Role-</option>
                    <?php echo loadAllRolesSelected($role) ?>
                  </select>
                <div class="invalid-feedback">
                  Please enter staff role
                </div>
                </div>
              </div>
            <?php if(isset($_SESSION['staffloan'])&&$_SESSION['staffloan']==1){
                $data=getLoanDetails($id);
                ?>

                <!-- start loan amount -->
                <div class="form-group row">
                <label for="loanAmount" class="col-sm-2 col-form-label col-form-label-lg">Loan Amount</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php
                  if(!empty($data)){
                        echo $data['amount'];
                  }
                   ?>"  class="form-control" id="loanAmount" name="loanAmount" />
                <div class="invalid-feedback">
                  Please enter staff loan amount
                </div>
                </div>
              </div>
                <!-- end loan amount -->
                <!-- start emi -->
                <div class="form-group row">
                    <label for="emi" class="col-sm-2 col-form-label col-form-label-lg">EMI</label>
                    <div class="col-sm-10"   >
                        <input type="text" value="<?php
                        if(!empty($data)){
                            echo $data['emi'];
                        }
                        ?>"  class="form-control" id="emi" name="emi" />
                        <div class="invalid-feedback">
                            Please enter staff emi
                        </div>
                    </div>
                </div>
                <!-- end emi -->
                <!-- start pending loan amount -->
                <div class="form-group row">
                    <label for="pendingLoanAmount" class="col-sm-2 col-form-label col-form-label-lg">Pending Amount</label>
                    <div class="col-sm-10"   >
                        <input type="text" value="<?php
                        if(!empty($data)){
                            echo $data['pending'];
                        }
                        ?>"  class="form-control" id="pendingLoanAmount" name="pendingLoanAmount" readonly disabled />
                        <div class="invalid-feedback">
                            Please enter staff pending amount
                        </div>
                    </div>
                </div>
                <!-- end pending loan amount -->
            <?php } ?>
                <!-- Staff -->
                <div class="form-group row">
                    <label for="isstaff" class="col-sm-2 col-form-label col-form-label-lg">Is Staff</label>
                    <div class="col-sm-10"   >
                        <input type="checkbox" id="isstaff" name="isstaff" value="1">
                        <label for="isstaff" class="col-sm-3 col-form-label col-form-label-lg">Yes (for non staff uncheck)</label>

                        <div class="invalid-feedback">
                            Please enter staff
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('.role').change(function(){
                            var mytxt = $(this).find("option:selected").text();
                            alert(mytxt);
                            $('.staffDesignation').val(mytxt);
                        });
                    });
                </script>
                <input type="hidden" placeholder=""  class="form-control staffDesignation" id="staffDesignation" name="staffDesignation" required value="<?php echo $staffDesignation; ?>"/>

              <?php additionalRegisterForm() ?>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                  <div class="col-sm-10">
                      <button name='editStaffProcess'
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

                        type='submit' style="text-align: center;">SUBMIT</button>
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