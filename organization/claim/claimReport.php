<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/claim.php");
$userid = $_SESSION['userid'];
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
    <script>
    $(document).ready( function () {
      $('#claimReportTable').DataTable();
    } );

    function showStaffInfo(){

    }
    </script>
    <!-- Data Table Import -->

    <style>
    table, td, th {
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
        <li class="breadcrumb-item ">Claim</li>
        <li class="breadcrumb-item active">Report</li>
      </ol>
    </div>
      <div class="container">
        <form action="../../phpfunctions/claim.php" method="POST" >
          <div class="form-group row">
              <!-- Staff Name -->
              <label for="staff" class="col-sm-1 col-form-label col-form-label-lg">Staff</label>
              <div class="col-sm-5">
                    <?php dropDownListOrgListActive($userid); ?>
              <div class="invalid-feedback">
                    Please choose staff
              </div>
              </div>

              <!-- Project Name -->
              <label for="project" class="col-sm-1 col-form-label col-form-label-lg">Project</label>
              <div class="col-sm-5">
                    <input class="form-control" type="text" name="project">
              <div class="invalid-feedback">
                    Please choose project
              </div>
              </div>
          </div>
          <div class="form-group row">
              <!-- From -->
              <label for="dateFrom" class="col-sm-1 col-form-label col-form-label-lg">From</label>
              <div class="col-sm-5">
                    <input class="form-control" type="date" name="dateFrom">
              <div class="invalid-feedback">
                    Please choose Start Date
              </div>
              </div>

              <!-- To -->
              <label for="dateTo" class="col-sm-1 col-form-label col-form-label-lg">To</label>
              <div class="col-sm-5">
                    <input class="form-control" type="date" name="dateTo">
              <div class="invalid-feedback">
                    Please choose End Date
              </div>
              </div>
          </div>
          <div class="form-group row">
              <!-- Status -->
              <label for="dateFrom" class="col-sm-1 col-form-label col-form-label-lg">Status</label>
              <div class="col-sm-5">
                <select class="form-control" name="status">
                    <option selected disabled>--Select--</option>
                  <option value="0">Submitted</option>
                  <option value="1">Draft</option>
                </select>
              <div class="invalid-feedback">
                    Please choose Start Date
              </div>
              </div>
          </div>
          <div class="form-group row">
              <!-- Search -->
              <div class="col-sm-12">
                <button class="btn btn-secondary btn-lg btn-block" type="submit" name="claimReport">Search</button>
              </div>
          </div>
        </form>
      </div>
      <div class="container">
        <?php
        if(isset($_SESSION['claimReport'])){
          echo $_SESSION['claimReport'];

          if ($_SESSION['claimReport']!="") {
            echo "<a href='printClaimReport.php'><button type='button' class='btn btn-secondary'>Print</button></a>";
          }
          $_SESSION['claimReport'] = "";
        }
        ?>
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
