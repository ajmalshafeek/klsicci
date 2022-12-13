<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
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
        <li class="breadcrumb-item active">Create Project</li>
      </ol>
    </div>
    <div class="container">
      <?php
      if (isset($_SESSION['feedback'])) {
          echo $_SESSION['feedback'];
          unset($_SESSION['feedback']);
      }
      ?>
      <form class="" action="../../phpfunctions/project.php" method="post">
        <div class="form-group row">
          <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">Project Name</label>
          <div class="col-sm-10"   >
            <input class="form-control" type="text" name="projectName">
            <div class="invalid-feedback">
            Please enter project name
          </div>
          </div>
        </div>
        <div class="form-group row">
          <!--START DATE-->
          <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">Start</label>
          <div class="col-sm-4"   >
            <input class="form-control" type="date" name="startDate" required>
            <div class="invalid-feedback">
            Please enter Start Date
          </div>
          </div>
          <!--END DATE-->
          <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">End</label>
          <div class="col-sm-4"   >
            <input class="form-control" type="date" name="endDate" required>
            <div class="invalid-feedback">
            Please enter End Date
          </div>
          </div>

        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
                <button name='addProject' class="btn btn-primary btn-lg btn-block" type='submit' name="addProject">Save</button>
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
