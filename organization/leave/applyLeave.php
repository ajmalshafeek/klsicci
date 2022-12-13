<?php
$config=parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
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
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/applyLeave.php");
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
        <?php if(isset($_SESSION['feedback'])){
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        } ?>
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Leave</li>
        <li class="breadcrumb-item active">Apply Leave</li>
      </ol>
      </div>
     <div style="margin-left: 50px; margin-right: 50px;" class="pd-20 card-box mb-30">
         <div class="clearfix">
             <div class="pull-left">
                 <h4 class="text-blue h4">Leave Form</h4>
                 <p class="mb-20"></p>
             </div>
         </div>
         <div class="wizard-content">
             <form method="post" action="../../phpfunctions/applyLeave.php">
                 <section>
                      <?php $row =getMyLeaveModuleList(); ?>

                     <div class="row">
                         <div class="col-12">
                             <div class="form-group">
                                 <label >First Name </label>
                                 <input name="firstname" type="text" class="form-control wizard-required" required="true" readonly autocomplete="off" value="<?php echo $row['fullName']; ?>">
                             </div>
                         </div>
                         <!-- <div class="col-md-6 col-sm-12">
										<div class="form-group">
											<label >Last Name </label>
											<input name="lastname" type="text" class="form-control" readonly required="true" autocomplete="off" value="<?php echo $row['LastName']; ?>">
										</div>
									</div> -->
                     </div>
                     <div class="row">
                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>Email Address</label>
                                 <input name="email" type="text" class="form-control" required="true" autocomplete="off" readonly value="<?php echo $row['email']; ?>">
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>Available Leave Days </label>
                                 <input name="al_days" type="text" class="form-control" required="true" autocomplete="off" readonly value="<?php echo $row['al_entitle']; ?>">
                             </div>
                         </div>

                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>Available MC </label>
                                 <input name="mc" type="text" class="form-control" required="true" autocomplete="off" readonly value="<?php echo $row['mc_entitle']; ?>">
                             </div>
                         </div>
                         <?php //endif ?>
                     </div>
                     <div class="row">
                         <div class="col-md-12 col-sm-12">
                             <div class="form-group">
                                 <label>Leave Type :</label>
                                 <select name="leave_type" class="custom-select form-control" required="true" autocomplete="off">
                                     <option value="">Select leave type...</option>
                                     <?php $results=getLeaveTypeList(); ?>
                                 </select>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>Start Leave Date :</label>
                                 <input name="date_from" type="date" class="form-control date-picker" required="true" autocomplete="off">
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>End Leave Date :</label>
                                 <input name="date_to" type="date" class="form-control date-picker" required="true" autocomplete="off">
                             </div>
                         </div>

                         <div class="col-md-4 col-sm-12">
                             <div class="form-group">
                                 <label>Half Days :</label>
                                 <select name="half" class="custom-select form-control" required="true" autocomplete="off">
                                     <option value="NO">NO</option>
                                     <option value="AM">AM</option>
                                     <option value="PM">PM</option>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-8 col-sm-12">
                             <div class="form-group">
                                 <label>Reason For Leave :</label>
                                 <textarea id="textarea1" name="description" class="form-control" required length="150" maxlength="150" required="true" autocomplete="off" placeholder="Reason For Leave"></textarea>
                             </div>
                         </div>
                         <div class="col-md-12 col-sm-12">
                             <div class="form-group">
                                 <label style="font-size:16px;"><b></b></label>
                                 <div class="modal-footer justify-content-center">
                                     <button class="btn btn-primary" name="applyLeave" type="submit" id="apply" data-toggle="modal">Apply&nbsp;Leave</button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </section>
             </form>
         </div>
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
<script>
    $(document).ready(function () {
        $('.role').val(4);
    });
</script>
</body>
</html>
