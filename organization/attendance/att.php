<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript">
    function detectWebcam(callback) {
      let md = navigator.mediaDevices;
      if (!md || !md.enumerateDevices) return callback(false);
      md.enumerateDevices().then(devices => {
        callback(devices.some(device => 'videoinput' === device.kind));
      })
    }

    detectWebcam(function(hasWebcam) {
      if (hasWebcam) {
        var userAgent = window.navigator.userAgent;
        if(userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)){
            window.location.href = "attendanceOther.php";
        }else{
            window.location.href = "attendanceIdeal.php";
        }
      }else {
        window.location.href = "attendanceOther.php";
      }
    })
    </script>
  </head>
  <body>

  </body>
</html>
