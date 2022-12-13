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
    <link rel="stylesheet" type="text/css" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/css/responsive.bootstrap4.min.css">

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

    .bt-custom{padding: 5px 10px !important; background-color: transparent; border: 0px !important; cursor: pointer !important;}
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
        <li class="breadcrumb-item active">All Staff Leave Update</li>
      </ol>
      </div>
     <div class="container-fluid">
         <div class="pd-20 card-box mb-30">
             <div class="clearfix">
                 <div class="pull-left">
                     <h4 class="text-blue h4">All Staff Annual Leave Update</h4>
                     <p class="mb-20"></p>
                 </div>
             </div>
             <form method="post" action="../../phpfunctions/applyLeave.php">

                 <div class="row">
                     <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                             <label>Annual Leave</label>
                             <input name="al_entitle" type="number" step=0.5 class="form-control wizard-required" autocomplete="off" value="" required />
                         </div>
                     </div>
                     <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                             <label>Medical Leave</label>
                             <input name="mc_entitle" type="number" step="0.5" class="form-control wizard-required" autocomplete="off" value="" required />
                         </div>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-12 col-sm-12">
                         <div class="form-group">
                             <label style="font-size:16px;"><b></b></label>
                             <div class="modal-footer justify-content-center">
                                         <button class="btn btn-primary" name="AddLeaves" id="addLeaves" data-toggle="modal">Add in Annual Leave</button>
                                 <button class="btn btn-primary" name="removeLeaves" id="removeLeaves" data-toggle="modal">Remove from Annual Leave</button>
                             </div>
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

            $('#table').DataTable();

    });

</script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/responsive.bootstrap4.min.js"></script>

<!-- buttons for Export datatable -->
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/buttons.print.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/buttons.html5.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/buttons.flash.min.js"></script>
<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/js/vfs_fonts.js"></script>

</body>
</html>
