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
            .bg-red{
                background-color: #E32526;
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
        <li class="breadcrumb-item ">Attendance</li>
        <li class="breadcrumb-item active">Check-in</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/attendance.php" enctype="multipart/form-data">
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div class="form-group row">
                <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">Staff Name</label>
                <div class="col-sm-10"   >
                  <?php dropDownListOrgListActive($_SESSION['userid']); ?>
                  <div class="invalid-feedback">
                  Please choose staff
                </div>
                </div>
              </div>

              <div class="form-group row">
                  <label for="attachment" class="col-sm-2 col-form-label col-form-label-lg">Image</label>
                  <div class="col-sm-10">
                      <input class="form-control" type="file" name="fileName" required>
                  </div>
              </div>

              <div class="form-group row">
                  <label for="attachment" class="col-sm-2 col-form-label col-form-label-lg">Geolocation</label>
                  <div class="col-sm-10">
                      <button id="getLocationId" type="button" onclick="getLocation();enableSubmitButton()">Get Location</button>
                      <input id="latitude" type="text" name="latitude" hidden>
                      <input id="longitude" type="text" name="longitude" hidden>
                  </div>
              </div>
              
              <div class="form-group row">
                    <div id="staticMap" style="width:100%"></div>
                    <p id='map'></p>
              </div>
              
              <div class="form-group row">
                  <button id="submitButton" name='checkin' class="btn btn-primary btn-lg btn-block" type='submit' disabled>Submit</button>
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
  var x = document.getElementById("map");

  function enableSubmitButton(){
      document.getElementById("submitButton").disabled = false;
  }

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.watchPosition(showPosition);
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function showPosition(position) {
    /*REQUIRES GOOGLE STATIC MAP API*/
    var latlon = position.coords.latitude + "," + position.coords.longitude;
   <?php
    // var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=400x300&sensor=false&key=AIzaSyDgH5mAegqWsg3LTQ8LSJW2RlMofmRsOfg"; ?>
    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=400x300&sensor=false&key=AIzaSyBItTTXWsLZVRnRyhEzwfC3pO__QzDKSQo";
    console.log(img_url);
    document.getElementById("staticMap").innerHTML = "<img src='"+img_url+"'>"; 
    
    x.innerHTML="Latitude: " + position.coords.latitude +
    " | Longitude: " + position.coords.longitude;
    
    document.getElementById("latitude").value = position.coords.latitude;
    document.getElementById("longitude").value = position.coords.longitude;
  }
  </script>
</body>
</html>
