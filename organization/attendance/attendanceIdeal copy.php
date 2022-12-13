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
	
	#screenshot {
   text-align:center;
  }
	
	@media only screen and (max-width: 600px) {
  #screenshot video{
   width: 100%; height: fit-content;
  }
  #screenshot img{
   width: inherit;
  }
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
                <audio id="shutterEffect" style="display:none">
                  <source src="shutterEffect.mp3" type="audio/mpeg">
                </audio>
                <div class="chartContainer">
                  <div style="padding-left:45%;padding-right:45%">
                    <div id="loader"></div>
                  </div>
                </div>
                <div id="content">
                    <div class="form-group row">
                      <div id="screenshot" style="width:100%; ">
                        <video id="videoDiv"  autoplay></video>
                        <img id="imgTaken" src="">
                      </div>
                      <canvas style="display:none;"></canvas>
                    </div>
                    <div class="form-group row">
                        <button id="screenshot-button" class="btn btn-primary btn-lg btn-block" type="button" name="button">Capture</button>
                        <button id="retake" class="btn btn-primary btn-lg btn-block" onclick="retake()" type="button" name="button" style="display:none">Retake</button>
                    </div>
                    <div class="form-group row">
                        <form method="POST" action="../../phpfunctions/attendance.php" enctype="multipart/form-data">
                            <input id="latitude" type="text" name="latitude" hidden>
                            <input id="longitude" type="text" name="longitude" hidden>
                            <input id="clockio" type="number" name="clockio" hidden>
                            <input id="base64img" type="text" name="base64img" hidden>
                            <input id="submitAtt1" type="submit" name="att1" hidden>
                        </form>
                        <div style="width:50%;padding:1px;">
                            <button id="clockInButton" class="btn btn-primary btn-lg btn-block" onclick="chooseFile(0)" type="button" style="display:none;">Clock In</button>
                        </div>
                        <div  style="width:50%">
                            <button id="clockOutButton" class="btn btn-primary btn-lg btn-block" onclick="chooseFile(1)" type="button" style="display:none;">Clock Out</button>
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
//(START)LIVE CAM/IMG SCREENSHOT
  function hasGetUserMedia() {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
  }

  if (hasGetUserMedia()) {
    const constraints = {
      video: {width: {min: 056}, height: {min: 144}}
    };

    const screenshotButton = document.querySelector('#screenshot-button');
    const img = document.querySelector('#screenshot img');
    const video = document.querySelector('#screenshot video');
    const canvas = document.createElement('canvas');

    screenshotButton.onclick = video.onclick = function() {
      document.getElementById("shutterEffect").play();
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);
      // Other browsers will fall back to image/png
      img.src = canvas.toDataURL('image/webp');
      
      document.getElementById("screenshot-button").style.display="none";
      document.getElementById("retake").style.display="block";
      document.getElementById("videoDiv").style.display="none";
      document.getElementById("clockInButton").style.display = "block";
      document.getElementById("clockOutButton").style.display = "block";
      document.getElementById("base64img").value = img.src;
    };

    function retake(){
      document.getElementById("screenshot-button").style.display="block";
      document.getElementById("retake").style.display="none";
      document.getElementById("videoDiv").style.display="inline-block";
      document.getElementById("clockInButton").style.display = "none";
      document.getElementById("clockOutButton").style.display = "none";
      img.src = "";
    }

    function handleSuccess(stream) {
      screenshotButton.disabled = false;
      video.srcObject = stream;
    }

    navigator.mediaDevices.getUserMedia(constraints).
      then((stream) => {video.srcObject = stream});
  } else {
    alert('getUserMedia() is not supported by your browser');
  }
//(END)LIVE CAM/IMG SCREENSHOT

function chooseFile(clockio){
    document.getElementById("clockio").value=clockio;
    document.getElementById("content").style.display = "none";
    document.getElementById("loader").style.display = "block";
    getLocation();
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
