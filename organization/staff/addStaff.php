<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}

?>
<!DOCTYPE html >

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
        <li class="breadcrumb-item active">Staff</li>
        <li class="breadcrumb-item active">Add Staff</li>

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
              <div class="form-group row">
                <label for="staffName" class="col-sm-2 col-form-label col-form-label-lg">Staff Name</label>
                <div class="col-sm-10"   >
                  <input type="text" placeholder="Enter Staff Name"  class="form-control" id="staffName" name="staffName" required />
                <div class="invalid-feedback">
                  Please enter staff name
                </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="staffId" class="col-sm-2 col-form-label col-form-label-lg">Staff ID</label>
                <div class="col-sm-10"   >
                  <input type="text" placeholder="Enter Staff ID "  class="form-control" id="staffId" name="staffId" required />
                <div class="invalid-feedback">
                  Please enter staff ID
                </div>
                </div>
              </div>


              <div class="form-group row">
                <label for="ICNum" class="col-sm-2 col-form-label col-form-label-lg">IC No.</label>
                <div class="col-sm-10"   >
                  <input type="text" placeholder="Enter IC Number"  class="form-control" id="ICNum" name="ICNum" required />
                <div class="invalid-feedback">
                  Please enter IC number
                </div>
                </div>
              </div>

                <div class="form-group row">
                    <label for="staffPassport" class="col-sm-2 col-form-label col-form-label-lg">Passport No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter Passport Number"  class="form-control" id="staffPassport" name="staffPassport" />
                        <div class="invalid-feedback">
                            Please enter Passport Number
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staffPerkeso" class="col-sm-2 col-form-label col-form-label-lg">Perkeso No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter Perkeso Number"  class="form-control" id="staffPerkeso" name="staffPerkeso" required />
                        <div class="invalid-feedback">
                            Please enter Perkeso Number
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staffKWSP" class="col-sm-2 col-form-label col-form-label-lg">KWSP No.</label>
                    <div class="col-sm-10"   >
                        <input type="text" placeholder="Enter KWSP Number"  class="form-control" id="staffKWSP" name="staffKWSP" required />
                        <div class="invalid-feedback">
                            Please enter KWSP Number
                        </div>
                    </div>
                </div>



              <div class="form-group row">
                <label for="staffGender" class="col-sm-2 col-form-label col-form-label-lg">Gender</label>
                <div class="col-sm-10"   >
                  <select placeholder="Enter Staff Gender"  class="form-control" id="staffGender" name="staffGender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                <div class="invalid-feedback">
                  Please select staff gender
                </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="staffEmail" class="col-sm-2 col-form-label col-form-label-lg">Staff Email</label>
                <div class="col-sm-10"   >
                  <input type="email"  placeholder="Enter Staff Email" class="form-control" id="staffEmail" name="staffEmail" />
                  <div class="invalid-feedback">
                    Please enter staff email address
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="staffPhone" class="col-sm-2 col-form-label col-form-label-lg">Staff Phone</label>
                <div class="col-sm-10"   >
                  <input type="number"  placeholder="Enter Staff Phone" required class="form-control" id="staffPhone" name="staffPhone" />
                  <div class="invalid-feedback">
                    Please enter staff phone
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="staffStart" class="col-sm-2 col-form-label col-form-label-lg">Start Date</label>
                <div class="col-sm-10"   >
                  <input type="date" placeholder=""  class="form-control" id="staffStart" name="staffStart" required />
                <div class="invalid-feedback">
                  Please enter staff start Date
                </div>
                </div>
              </div>
<?php /*
              <div class="form-group row">
                <label for="staffDepartment" class="col-sm-2 col-form-label col-form-label-lg">Staff Department</label>
                <div class="col-sm-10"   >
                    <?php
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
                    $departmentlist=departmentList();
                    $listcount=0;
                    if(count($departmentlist)>0){
                                       ?>
                        <select name="staffDesignation" id="staffDesignation" class="form-control">
                        <option value="">-- Select Department --</option>
                        <?php
                        foreach($departmentlist as $dep) {
                            ?> <option value="<?php echo $dep; ?>"><?php echo $dep; ?></option> <?php
                        }
                        ?>
</select>

                   <?php }else{ ?>
                  <input type="text" placeholder="Enter Staff Designation"  class="form-control" id="staffDesignation" name="staffDesignation" required></input>
                    <?php } ?>
                <div class="invalid-feedback">
                  Please enter staff designation
                </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="department" class="col-sm-2 col-form-label col-form-label-lg">Department</label>
                <div class="col-sm-10"   >
                <?php
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
                    $departmentlist=designationList();
                    $listcount=0;
                    if(count($departmentlist)>0){
                        ?>
                        <select name="department" id="department" class="form-control">
                        <option value="">-- Select Designation --</option>
                        <?php
                        foreach($departmentlist as $dep) {
                            ?> <option value="<?php echo $dep; ?>"><?php echo $dep; ?></option> <?php
                        }
                        ?>
</select>

                   <?php }else{ ?>
                  <input type="text" placeholder="Enter Staff Department"  class="form-control" id="department" name="department" required></input>

                   <?php } ?>
                <div class="invalid-feedback">
                  Please enter staff department
                </div>
                </div>
              </div>
*/ ?>
	            <div class="form-group row">
		            <label for="department" class="col-sm-2 col-form-label col-form-label-lg">Role</label>
		            <div class="col-sm-10"   >
			            <?php
				            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/role.php");
				         			                      ?>
					            <select name="role" id="role" class="form-control role">
						            <option value="">-- Select Role --</option>
						            <?php
							            echo loadAllRoles();
						            ?>
					            </select>
			            <div class="invalid-feedback">
				            Please enter role
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
                <input type="hidden" placeholder=""  class="form-control staffDesignation" id="staffDesignation" name="staffDesignation" required />

              <?php // additionalRegisterForm(); ?>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                  <div class="col-sm-10">
                      <button name='addStaff'
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
<script>
    $(document).ready(function () {
        $('.role').val(4);
    });
</script>
</body>
</html>
