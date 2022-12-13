<?php
$config=parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();

    if(!isset($_POST['viewDetails']) && empty($_POST['viewDetails'])){
        header('Location: leaveDetails.php');
    }
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
        <li class="breadcrumb-item active">Leave Detail</li>
      </ol>
      </div>
     <div class="container-fluid">
         <div class="pd-20 card-box mb-30">
             <div class="clearfix">
                 <div class="pull-left">
                     <h4 class="text-blue h4">Leave Details</h4>
                     <p class="mb-20"></p>
                 </div>
             </div>
             <form method="post" action="">

                 <?php
                     $lid=$_POST['leaveId'];
                     $result=getLeaveFullDetail($lid);
                     $empid=$result['empid'];
                     $leaveOrg=getLeaveOrgInfo($empid);

                             ?>

                             <div class="row">
                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Full Name</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($leaveOrg['fullName']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Email Address</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($leaveOrg['email']);?>" />
                                     </div>
                                 </div>

                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Phone Number</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($leaveOrg['phone']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Leave Type</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result['LeaveType']);?>" />
                                     </div>
                                 </div>

                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Applied No. of Days</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly name="num_days" value="<?php echo htmlentities($result['num_days']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Available AL</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($leaveOrg['al_entitle']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4 col-sm-12">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Available MC</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($leaveOrg['mc_entitle']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Leave From</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result['FromDate']);?>" />
                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Leave To</b></label>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result['ToDate']);?>" />
                                     </div>
                                 </div>

                             </div>
                             <div class="form-group row">
                                 <label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Leave Reason</b></label>
                                 <div class="col-sm-12 col-md-10">
                                     <textarea name=""class="form-control text_area" readonly type="text"><?php echo htmlentities($result['Description']);?></textarea>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Level 1 Remarks</b></label>
                                 <div class="col-sm-12 col-md-10">
                                     <?php
                                     if ($result['AdminRemark']==""): ?>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>" />
                                     <?php else: ?>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result['AdminRemark']); ?>" />
                                     <?php endif ?>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Level 2 Remarks</b></label>
                                 <div class="col-sm-12 col-md-10">
                                     <?php
                                     if ($result['registra_remarks']==""): ?>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>" />
                                     <?php else: ?>
                                         <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result['registra_remarks']); ?>" />
                                     <?php endif ?>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Action Taken On</b></label>
                                         <?php
                                         if ($result['AdminRemarkDate']==""): ?>
                                             <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "NA"; ?>" />
                                         <?php else: ?>
                                             <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result['AdminRemarkDate']); ?>" />
                                         <?php endif ?>

                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Incharge Status</b></label>
                                         <?php $stats=''.$result['Status'];?>
                                         <?php
                                         if (strcasecmp($stats,"APPROVED")==0): ?>
                                             <input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Approved"; ?>" />
                                         <?php
                                         elseif (strcasecmp($stats,"REJECTED")==0): ?>
                                             <input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Rejected"; ?>" />
                                         <?php
                                         else: ?>
                                             <input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>" />
                                         <?php endif ?>
                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label style="font-size:16px;"><b>Manager Status</b></label>
                                         <?php $ad_stats=''.$result['admin_status'];?>
                                         <?php
                                         if (strcasecmp("APPROVED",$ad_stats)==0): ?>
                                             <input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Approved"; ?>" />
                                         <?php
                                         elseif (strcasecmp($ad_stats,"REJECTED")==0): ?>
                                             <input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Rejected"; ?>" />
                                         <?php
                                         else: ?>
                                             <input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>" />
                                         <?php endif ?>
                                     </div>
                                 </div>


                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label style="font-size:16px;"><b></b></label>
                                             <div class="modal-footer justify-content-center">
                                                 <?php
                                                 $check1= checkLeave1Approval($_SESSION['userid']);
                                                 if($check1 AND (strcasecmp($stats,'PENDING')==0) OR (strcasecmp($stats,'REJECTED')==0) AND $check1){ ?>
                                                <button class="btn btn-primary" id="action_take" data-toggle="modal" data-target="#success-modal1">In-Charge Action</button>
                                            <?php  }
                                                 $check2= checkLeave2Approval($_SESSION['userid']);
                                                 if($check2 AND (strcasecmp($ad_stats,'PENDING')==0) OR (strcasecmp($ad_stats,'REJECTED')==0) AND $check2){  ?>
                                                <button class="btn btn-primary" id="action_take" data-toggle="modal" data-target="#success-modal2">Manager Action</button>
                                            <?php } ?>
                                             </div>
                                         </div>
                                     </div>

                                 <?php
                                 if((strcasecmp($stats,'PENDING')==0) AND $check1 OR (strcasecmp($stats,'REJECTED')==0) AND $check1)
                                 {   ?>
                                     <form name="adminaction" method="post" action="../../phpfunctions/applyLeave.php">
                                         <div class="modal fade" id="success-modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                             <div class="modal-dialog modal-dialog-centered" role="document">
                                                 <div class="modal-content">
                                                     <div class="modal-body text-center font-18">
                                                         <h4 class="mb-20">In-Charge Action on Leave</h4>
                                                         <select name="status" required class="custom-select form-control">
                                                             <option value="">Choose your option</option>
                                                             <option value="APPROVED">Approved</option>
                                                             <option value="REJECTED">Rejected</option>
                                                         </select>

                                                         <div class="form-group">
                                                             <label></label>
                                                             <textarea id="textarea1" name="description" class="form-control" required placeholder="Description" length="300" maxlength="300"></textarea>
                                                         </div>
                                                     </div>
                                                        <input type="hidden" value="<?php echo $lid; ?>" name="lid" />
                                                     <div class="modal-footer justify-content-center">
                                                         <input type="submit" class="btn btn-primary" name="updateLevel1" value="Submit" />
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </form>
                                 <?php }
                                 if((strcasecmp($ad_stats,'PENDING')==0) AND $check2 OR (strcasecmp($ad_stats,'REJECTED')==0) AND $check2){
                                 ?>
                                     <form name="adminaction" method="post" action="../../phpfunctions/applyLeave.php">
                                         <div class="modal fade" id="success-modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                             <div class="modal-dialog modal-dialog-centered" role="document">
                                                 <div class="modal-content">
                                                     <div class="modal-body text-center font-18">
                                                         <h4 class="mb-20">Manager Action on Leave</h4>
                                                         <select name="status" required class="custom-select form-control">
                                                             <option value="">Choose your option</option>
                                                             <option value="APPROVED">Approved</option>
                                                             <option value="REJECTED">Rejected</option>
                                                         </select>

                                                         <div class="form-group">
                                                             <label></label>
                                                             <textarea id="textarea1" name="description" class="form-control" required placeholder="Description" length="300" maxlength="300"></textarea>
                                                         </div>
                                                     </div>
                                                     <input type="hidden" value="<?php echo $result['LeaveType'] ?>" name="leaveType" />
                                                     <input type="hidden" value="<?php echo $lid; ?>" name="lid" />
                                                     <input type="hidden" value="<?php echo $empid; ?>" name="empid" />
                                                     <input type="hidden" value="<?php echo $leaveOrg['al_entitle']; ?>" name="al_entitle" />
                                                     <input type="hidden" value="<?php echo $leaveOrg['mc_entitle']; ?>" name="mc_entitle" />
                                                     <input type="hidden" value="<?php echo $result['num_days']; ?>" name="num_days" />
                                                     <input type="submit" class="btn btn-primary" name="updateLevel2" value="Submit" />
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </form>



                                 <?php }?>
                             </div>


             </form>
         </div>

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
