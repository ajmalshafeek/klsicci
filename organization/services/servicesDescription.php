<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
$userid = $_SESSION['userid'];
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/services.php");
?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
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
        <li class="breadcrumb-item ">Services</li>
        <li class="breadcrumb-item active">Services Description</li>
      </ol>
    </div>
    <div class="container">
        <?php
              if (isset($_SESSION['feedback'])) {
                  echo $_SESSION['feedback'];
                  unset($_SESSION['feedback']);
              }
        ?>
        <script type="text/javascript">

        </script>
        <div class="form-group row">
            <label for="service" class="col-sm-2 col-form-label col-form-label-lg">List of Services</label>
            <div class="col-sm-10"   >
                <select onchange="loadServiceDescTable()" id="service" class="form-control" name="service">
                  <?php echo servicesOptionList(); ?>
                </select>
            <div class="invalid-feedback">
                  Please enter Service name
            </div>
            </div>
        </div><hr/>
        <script type="text/javascript">
        function addServiceDes(){
          var serviceId = document.getElementById("service").value;
          var item = document.getElementById("item").value;
          var description = document.getElementById("description").value;
          var unitPrice = document.getElementById("unitPrice").value;
          var quantity = document.getElementById("quantity").value;
          if (serviceId != "empty" && item != "" && description != "" && unitPrice != "" && quantity != "") {
            $.ajax({
                type  : 'GET',
                url  : '../../phpfunctions/services.php?',
                data : {addServiceDesc:1,item:item,description:description,unitPrice:unitPrice,quantity:quantity,serviceId:serviceId},
                success: function (data) {
                  if (data == "0") {
                    alert("Service description is successfully added");
                    loadServiceDescTable();
                    clearForm();
                  }else {
                    alert("Error! Failed to add service description");
                  }
                }
            });
          }
        }

        function clearForm(){
          document.getElementById("item").value = "";
          document.getElementById("description").value = "";
          document.getElementById("unitPrice").value = "";
          document.getElementById("quantity").value = "";
        }

        function loadServiceDescTable(){
          var serviceId = document.getElementById("service").value;
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/services.php?',
              data : {loadServiceDescTable:serviceId},
              success: function (data) {
                document.getElementById("serviceDescTable").innerHTML = data;
              }
          });
        }

        function updateServiceDescCheck(serviceDescId){
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/services.php?',
              data : {updateServiceDescCheck:serviceDescId},
              success: function (data) {
              details= JSON.parse(data);
              document.getElementById("item").value = details.item;
              document.getElementById("description").value = details.description;
              document.getElementById("unitPrice").value = details.unitPrice;
              document.getElementById("quantity").value = details.quantity;
              document.getElementById("saveButton").setAttribute( "onClick", "javascript: updateServiceDesc("+ details.id +");" );
              document.getElementById("resetButton").setAttribute( "onClick", "javascript: resetForm();" );
              }
          });
        }

        function updateServiceDesc(serviceDescId){
          var item = document.getElementById("item").value;
          var description = document.getElementById("description").value;
          var unitPrice = document.getElementById("unitPrice").value;
          var quantity = document.getElementById("quantity").value;
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/services.php?',
              data : {updateServiceDesc:serviceDescId,item:item,description:description,unitPrice:unitPrice,quantity:quantity},
              success: function (data) {
                if (data == "0") {
                  alert("Service Description is successfully updated");
                  document.getElementById("saveButton").setAttribute( "onClick", "javascript: addServiceDes();" );
                  loadServiceDescTable();
                }else {
                  alert("Error! Failed to update service description");
                  document.getElementById("saveButton").setAttribute( "onClick", "javascript: addServiceDes();" );
                }
                clearForm();
              }
          });
        }

        function removeServiceDesc(serviceDescId){
          var remove = confirm("Are you sure you want to remove this service description?");
          if (remove) {
            $.ajax({
                type  : 'GET',
                url  : '../../phpfunctions/services.php?',
                data : {removeServiceDesc:serviceDescId},
                success: function (data) {
                  if (data == "0") {
                    alert("Service description is successfully removed");
                    loadServiceDescTable();
                  }
                  else {
                    alert("Error! Failed to remove service description");
                  }
                }
            });
          }
        }

        function resetForm(){
          clearForm();
          document.getElementById("saveButton").setAttribute( "onClick", "javascript: addServiceDes();" );
          document.getElementById("resetButton").setAttribute( "onClick", "javascript: clearForm();" );
        }
        </script>
        <div class="form-group row">
          <div class="col-sm-3">
            <input id="item" class="form-control"  type="text" placeholder="Item">
          </div>
          <div class="col-sm-3">
            <textarea id="description" class="form-control" rows="4" cols="80" placeholder="Description"></textarea>
          </div>
          <div class="col-sm-3">
            <input id="unitPrice" class="form-control"  type="number" placeholder="Unit Prices">
          </div>
          <div class="col-sm-3">
            <input id="quantity" class="form-control"  type="number" placeholder="Quantity">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-9"></div>
          <div class="col-sm-3 row">
            <div class="col-sm-6">
              <button id="saveButton" onclick="addServiceDes()" type="button" class="btn btn-block" name="button">Save</button>
            </div>
            <div class="col-sm-6">
              <button id="resetButton" onclick="clearForm()" type="button" class="btn btn-block" name="button">Reset</button>
            </div>
          </div>
        </div><hr/>
        <div class="form-group row">
          <div class="col-sm-12">
            <div id="serviceDescTable"></div>
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

</body>
<script>
    $(document).ready(function() {
        $('input.number-currency').keyup(function(event) {

            // skip for arrow keys
            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }

            $(this).val(function(index, value) {
                value = value.replace(/[^0-9]/g, '');
                return numberWithCommas(value);
            });
        });
        );
</script>
</html>
