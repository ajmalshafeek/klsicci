<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/project.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
      $('#projectTable').DataTable();
    } );
    </script>
    <style>
    table, td, th {
        text-align: center;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th {
      height: 50px;
      color:white;
      background: #212529;
    }
    table tbody tr:hover td{
      background-color: #DEE2E6;
      cursor:pointer;
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
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Project</li>
        <li class="breadcrumb-item active">Groups</li>
      </ol>
    </div>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo $_SESSION['feedback'];
        unset($_SESSION['feedback']);
    }
    ?>
    <div class="container">
      <div class="form-group row">
        <div class="col-md-12">
          <button data-toggle='modal' data-target='#addGroupModal' class="btn btn-primary btn-lg btn-block" type="button" name="button">Add Group</button>
        </div>
      </div><hr>
      <?php //projectList(); ?>
    </div>
  </div>
  <!-- View Projust Modal START-->
  <script type="text/javascript">
  function showStaffInfo(){

  }

  function addStaffRow(){
    var script = document.getElementById("groupModalScript").value;
    var extraRow = "<div class='form-group row'><div class='col-md-12'>Add Staff Drop down List</div></div>";
    var newScript = script + extraRow;
    document.getElementById("groupModalContent").innerHTML = newScript;
    document.getElementById("groupModalScript").value = newScript;
  }

  function addOtherRow(){
    var script = document.getElementById("groupModalScript").value;
    var extraRow = "<div class='form-group row'><div class='col-md-12'>Add Other Form</div></div>";
    var newScript = script + extraRow;
    document.getElementById("groupModalContent").innerHTML = newScript;
    document.getElementById("groupModalScript").value = newScript;
  }
  </script>
  <div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="addGroupModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title col-md-10"><input id="groupName" class="form-control" type="text" name="" value="" placeholder="Group Name"></h5>
             <button type="button col-md-2" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div id="groupModalContent"></div>
             <div class="form-group row">
               <div class="col-md-6">
                 <button onclick="addStaffRow()" class="btn btn-primary btn-lg btn-block" type="button" name="button">Add From Staff</button>
               </div>
               <div class="col-md-6">
                 <button onclick="addOtherRow()" class="btn btn-primary btn-lg btn-block" type="button" name="button">Add Other</button>
               </div>
             </div>
             <input id="groupModalScript" type="text" hidden>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			       </button>
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
</body>
</html>
