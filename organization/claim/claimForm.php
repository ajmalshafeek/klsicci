<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/claim.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vehicle.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/trip.php");
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
    <script>
    function addCtr(){
        var x = document.getElementById("counter").value;
        x++;
        addAtt(x)
        document.getElementById("counter").value = x;
    }

    function subCtr(){
        var x = document.getElementById("counter").value;
        if(x >= 1){
            subAtt();
            x--;
            document.getElementById("counter").value = x;
        }
    }

    function addAtt(x){
        var row="<input class='form-control' type='file' name='file"+x+"' id='fileToUpload'>";
        document.getElementById("rowStore").value += row;
        var xRow = document.getElementById("rowStore").value;
        document.getElementById("displayRow").innerHTML = xRow;
    }

    function subAtt(){
        var x = document.getElementById("counter").value;
        var rowStore = document.getElementById("rowStore").value;
        var subRow = "<input class='form-control' type='file' name='file"+x+"' id='fileToUpload'>";
        var nxRow = rowStore.replace(subRow, "");
        document.getElementById("rowStore").value = nxRow;
        document.getElementById("displayRow").innerHTML = nxRow;
    }

    function showStaffInfo(){

    }
    </script>
    <style>
    table {
      border-collapse: collapse;
      width:100%;
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
        <li class="breadcrumb-item active">Claim Form</li>
      </ol>
    </div>
      <div class="container">
        <?php
              if (isset($_SESSION['feedback'])) {
                  echo $_SESSION['feedback'];
                  unset($_SESSION['feedback']);
              }
        ?>
        <form action="../../phpfunctions/claim.php" method="POST" enctype="multipart/form-data">
            <!--STAFF NAME-->

            <div class="form-group row">
                <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">Staff Name</label>
                <div class="col-sm-10"   >
                      <?php dropDownListOrgListActive($userid); ?>
                <div class="invalid-feedback">
                      Please choose staff
                </div>
                </div>
            </div>

            <!--PROJECT-->
            <div class="form-group row">
                <label for="project" class="col-sm-2 col-form-label col-form-label-lg">Description</label>
                <div class="col-sm-10"   >
                    <input type="text" id="project" class="form-control" name="project" required>
                <div class="invalid-feedback">
                      Please enter description
                </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="project" class="col-sm-2 col-form-label col-form-label-lg">Request For</label>
                <div class="col-sm-10"   >
                    <select name="claimType" id="claimType" class="form-control">
                        <option value="0">-- Select --</option>
                        <?php getClaimType(); ?>
                    </select>
                    <div class="invalid-feedback">
                        Please enter request type
                    </div>
                </div>
            </div>
            <?php if($_SESSION['orgType']==2){ ?>
            <div class="form-group row">
                <label for="client" class="col-sm-2 col-form-label col-form-label-lg">Trip</label>
                <div class="col-sm-10">
                    <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
                    dropDownListTrip();   ?>
                    <div class="invalid-feedback">
                        Please select client
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="vehiclecategory" class="col-sm-2 col-form-label col-form-label-lg">Vehicle Number</label>
                <div class="col-sm-10">
                    <select name="vehicleno" id="vehicleno" class="form-control">
                        <option value="" selected>--Select Vehicle Number--</option>
                        <?php getVehicleNumber(); ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a Vehicle Number
                    </div>
                </div>

            </div>
            <?php } else{ ?>
            <input type="hidden" name="vehicleno" />
            <?php } ?>
            <!--CLAIM AMOUNT-->
            <div class="form-group row">
                <label for="claim" class="col-sm-2 col-form-label col-form-label-lg">Claim Amount(RM)</label>
                <div class="col-sm-10"   >
                    <input type="number" id="claim" class="form-control claim" name="claim" required onchange="checkValue(this)" min="0.00" step="0.01"
                    >
                <div class="invalid-feedback">
                      Please enter claim amount(RM)
                </div>
                </div>
            </div>

            <!--ATTACHMENT-->
            <hr>
            <div class="form-group row">
                <label for="attachment" class="col-sm-2 col-form-label col-form-label-lg">Attachment</label>
                <div class="col-sm-10">
                        <input class="form-control" type="file" name="file0" id="file0" required>
                        <span id="displayRow"></span>
                </div>
            </div>
            <!--ADD ATTACHMENT-->
            <div class="form-group row">
                <input id="counter" name="counter" type=text value="0" hidden>
                <input id="rowStore" type=text value="" hidden>
                <!-- <span id="rowStore" style="display:none"><input class='form-control' type='file' name='file0' id='fileToUpload'></span> -->

                <label for="addButton" class="col-sm-2 col-form-label col-form-label-lg"></label>
                <div class="col-sm-8">
                    <button type="button" id="addAttButton" onclick="addCtr()" class="form-control btn btn-lg btn-block btn-success fa fa-plus"></button>
                </div>
                <div class="col-sm-2">
                    <button type="button" id="subAttButton" onclick="subCtr()" class="form-control btn btn-lg btn-block btn-success fa fa-minus"></button>
                </div>
            </div>
            <hr>
            <!-- SUBMIT BUTTON -->
            <div class="form-group row">
                <div class="col-sm-6">
                  <button class="btn btn-primary btn-lg btn-block" name="claimSubmit" value="1">Save as Draft</button>
                </div>
                <div class="col-sm-6">
                  <button class="btn btn-primary btn-lg btn-block" name="claimSubmit" value="0">Submit</button>
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

<script>
    function checkValue(id){
        var x = parseFloat(id.value);
        id.value=x.toFixed(2);
    }


</script>
</body>
</html>
