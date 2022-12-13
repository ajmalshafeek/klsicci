<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
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
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/custom-css.css" rel="stylesheet">

</head>
<style>
    #loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
      z-index:100;
      display:none;
    }

    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .chartContainer{
      width: 800px;
      margin-left:10%;
      margin-right:10%;
    }
</style>
<body style="background:#D8D8D8;">
  <div class="content-wrapper" style="background:#D8D8D8;">
    <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
    ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="chartContainer">
                  <div style="padding-left:45%;padding-right:45%">
                    <div id="loader"></div>
                  </div>
                </div>
                <div id="content">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <img src="notice.jpg" style="width:100%;" alt="This device does not support Media Capture API. Click any button below to open camera manually">
                        </div>
                    </div>
                    <div class="form-group row">
                        <form method="POST" action="../../phpfunctions/attendance.php" enctype="multipart/form-data">
                            <input id="fileName" onchange="proceedFile()" type="file" name="fileName" hidden>
                            <input id="latitude" type="text" name="latitude" hidden>
                            <input id="longitude" type="text" name="longitude" hidden>
                            <input id="clockio" type="number" name="clockio" hidden>
                            <input id="submitAtt1" type="submit" name="att2" hidden>
                        </form>
                        <div style="width:50%;padding:1px;">
                            <button class="btn btn-primary btn-lg btn-block" onclick="chooseFile(0)" type="button">Clock In</button>
                        </div>
                        <div  style="width:50%">
                            <button class="btn btn-primary btn-lg btn-block" onclick="chooseFile(1)" type="button">Clock Out</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card"  style="border:0px !important">
            <a href="../../home.php" style="color:blue;"><i>&lt;&lt;Back</i></a>
        </div>
    </div>
  </div>
</body>
<script>
function chooseFile(clockio){
    document.getElementById("clockio").value=clockio;
    document.getElementById("fileName").click();
}

function proceedFile(){
    document.getElementById("content").style.display = "none";
    document.getElementById("loader").style.display = "block";
    var fileName = document.getElementById("fileName").value;
    if(fileName != ""){
        getLocation();
    }else{
        alert("Empty");
    }
}

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.watchPosition(showPosition);
    } else {
      alert("This browser does not support Geolocation");
    }
  }

  function showPosition(position) {
    
    document.getElementById("latitude").value = position.coords.latitude;
    document.getElementById("longitude").value = position.coords.longitude;
    document.getElementById("submitAtt1").click();
  }
</script>
</html>
