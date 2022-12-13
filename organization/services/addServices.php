<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
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
        <li class="breadcrumb-item ">Services</li>
        <li class="breadcrumb-item active">Add Services</li>
      </ol>
    </div>
    <div class="container">
        <?php
              if (isset($_SESSION['feedback'])) {
                  echo $_SESSION['feedback'];
                  unset($_SESSION['feedback']);
              }
        ?>
        <form action="#" method="post">
          <div id="addServicePanel">
            <div class="form-group row">
                <label for="service" class="col-sm-2 col-form-label col-form-label-lg">Type of Service</label>
                <div class="col-sm-10"   >
                    <input type="text" id="service" class="form-control" name="service" required>
                <div class="invalid-feedback">
                      Please enter Service name
                </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label col-form-label-lg"></div>
                <div class="col-sm-10 row">
                    <button onclick="addService()" class="btn col-sm-6" type="button" name="button">Save</button>
                    <button onclick="emptyServiceForm()" class="btn col-sm-6" type="button" name="button">Reset</button>
                </div>
            </div>
          </div>
          <div id="updateServicePanel" style="display:none">
            <div class="form-group row">
                <label for="updateService" class="col-sm-2 col-form-label col-form-label-lg">Update Service</label>
                <div class="col-sm-10"   >
                    <input type="text" id="updateService" class="form-control" name="updateService" required>
                <div class="invalid-feedback">
                      Please enter Service name
                </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label col-form-label-lg"></div>
                <div class="col-sm-10 row">
                    <input id="updateServiceId" type="text" hidden>
                    <button onclick="updateServiceExe()" class="btn col-sm-6" type="button" name="button">Update</button>
                    <button onclick="change()" class="btn col-sm-6" type="button" name="button">Reset</button>
                </div>
            </div>
          </div>
        </form>
        <div  style="overflow:scroll">
          <div class="form-group row">
            <span class="col-sm-12" id="servicesTable"></span>
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
  <script type="text/javascript">
  $(document).ready( function () {
    $('#servicesTableContent').DataTable();
  } );

  loadServices();

  function addService(){
    var service = document.getElementById("service").value;
    if (service != "") {
      $.ajax({

          type  : 'GET',
          url  : '../../phpfunctions/services.php?',
          data : {addService:service},
          success: function (data) {
            console.log(data);
            var confirm = window.confirm("Add " + service + " as a Service?");
            if (confirm) {
              window.location.href = "addServices.php";
            }
          }
      });
    }
  }

  function loadServices(){
    $.ajax({

        type  : 'GET',
        url  : '../../phpfunctions/services.php?',
        data : {loadServices:1},
        success: function (data) {
        document.getElementById("servicesTable").innerHTML = data;
        }
    });
  }

  function emptyServiceForm(){
    document.getElementById('service').value='';
  }

  function updateServiceExe(){
    var service = document.getElementById("updateService").value;
    var serviceId = document.getElementById("updateServiceId").value;
    $.ajax({
        type  : 'GET',
        url  : '../../phpfunctions/services.php?',
        data : {updateService:serviceId,service:service},
        success: function (data) {
          if (data == 0) {
            alert("Service is updated");
            window.location.href = "addServices.php";
          }else {
            alert("Service is Failed");
          }
        }
    });
  }

  function updateServiceCheck(id){
    document.getElementById("addServicePanel").style.display = "none";
    document.getElementById("updateServicePanel").style.display = "block";
    document.getElementById("updateServiceId").value = id;
    $.ajax({
        type  : 'GET',
        url  : '../../phpfunctions/services.php?',
        data : {updateServicesCheck:id},
        success: function (data) {
          document.getElementById("updateService").value = data;
        }
    });
  }

  function change(){
    var addServicesPanel = document.getElementById("addServicePanel").style.display;
    var updateServicePanel = document.getElementById("updateServicePanel").style.display;

    if (addServicesPanel == "block") {
      document.getElementById("addServicePanel").style.display = "none";
      document.getElementById("updateServicePanel").style.display = "block";
    }else {
      document.getElementById("addServicePanel").style.display = "block";
      document.getElementById("updateServicePanel").style.display = "none";
    }
  }

  function removeService(id){
    var remove = confirm("Are you sure you want to remove this Service?");
    if (remove) {

      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/services.php?',
          data : {removeService:id},
          success: function (data) {
            if (data == "0") {
              alert("Service Removed");
              window.location.href = "addServices.php";
            }else {
              alert("Error! Failed to remove");
            }
          }
      });
    }
  }

  function refreshDataTable(){
    loadServices();
    //$('#servicesTableContent').DataTable().ajax.reload();
  }
  </script>
  <!-- datatable -->
  <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
  <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
  <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
  <!-- datatable -->
</body>
</html>
