<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}

	$idEdit=$_SESSION['officerIdEdit'];
	$name=$_SESSION['officerName'];
	$position=$_SESSION['officerPosition'];
	$address=$_SESSION['officerAdd'];
	$tel=$_SESSION['officerTel'];

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
        <?php /*
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
        }
         */ ?>

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
            <li class="breadcrumb-item active">Payroll</li>
            <li class="breadcrumb-item active">Edit Officer Details</li>

        </ol>
    </div>
    <div class="container" >
        <form method="POST" action="../../phpfunctions/eaForm.php" class="needs-validation" novalidate >
            <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
            ?>
            <div id="criteriaForm">
	            <div class="form-group row">
		            <label for="designationId" class="col-sm-2 col-form-label col-form-label-lg">ID</label>
		            <div class="col-sm-10"   >
			            <input type="text" value="<?php echo $idEdit ?>"  class="form-control" id="id" name="designationId" required />
			            <div class="invalid-feedback">
				            Please enter designation id
			            </div>
		            </div>
	            </div>
	            <div class="form-group row">
		            <label for="name" class="col-sm-2 col-form-label col-form-label-lg">Officer Name</label>
		            <div class="col-sm-10"   >
			            <input type="text" placeholder="Enter Name" class="form-control" value="<?php echo $name ?>" id="name" name="name" required />
			            <div class="invalid-feedback">
				            Please enter name
			            </div>
		            </div>
	            </div>
	            <div class="form-group row">
		            <label for="position" class="col-sm-2 col-form-label col-form-label-lg">Officer Position</label>
		            <div class="col-sm-10"   >
			            <input type="text" placeholder="Enter position"  class="form-control" value="<?php echo $position ?>" id="position" name="position" required />
			            <div class="invalid-feedback">
				            Please enter position
			            </div>
		            </div>
	            </div>
	            <div class="form-group row">
		            <label for="address" class="col-sm-2 col-form-label col-form-label-lg">Name and Address of Employer</label>
		            <div class="col-sm-10"   >
			            <textarea placeholder="Enter address"  class="form-control" id="address" name="address" required ><?php echo $address ?></textarea>
			            <div class="invalid-feedback">
				            Please enter address details
			            </div>
		            </div>
	            </div>
	            <div class="form-group row">
		            <label for="tel" class="col-sm-2 col-form-label col-form-label-lg">Employer’s Telephone No.</label>
		            <div class="col-sm-10"   >
			            <input type="number" placeholder="Enter Employer’s Telephone No."  class="form-control" value="<?php echo $tel ?>" id="tel" name="tel" required />
			            <div class="invalid-feedback">
				            Please enter Employer’s Telephone No.
			            </div>
		            </div>
	            </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='editOfficerProcess' class="btn btn-primary btn-lg btn-block" type='submit' style="text-align: center;">Update</button>
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
<?php
/*
    <script type="text/javascript">
       loadLandscape(<?php echo $id; ?>);
    </script>
*/
?>
</body>
</html>
