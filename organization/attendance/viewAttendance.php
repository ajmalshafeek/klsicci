<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/attendance.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
    <script>
    $(document).ready( function () {
      $('#attendanceTable').DataTable();
    } );

    function showAttendance(id){

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/attendance.php?',
            data : {attendanceDetail:id},
            success: function (data) {
                document.getElementById("attendanceDetails").innerHTML = data;
            }
        });
    }
    </script>
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
        <li class="breadcrumb-item active">View Attendance</li>
      </ol>
    </div>
    <div class="container" style="overflow: scroll">
        <?php attendanceList() ?>
    </div>
  </div>

  <!-- Attendance Modal START-->
  <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header">
       <h4>Attendance Detail</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
      </div>
      <div class="modal-body">
        <div id="attendanceDetails"></div>
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

 <script>
 function showLocation(id,latitude,longitude){
    /*REQUIRES GOOGLE STATIC MAP API*/
    var latlon = latitude + "," + longitude;
	 <?php
    // var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=300x200&sensor=false&key=AIzaSyDgH5mAegqWsg3LTQ8LSJW2RlMofmRsOfg"; ?>
    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=300x200&sensor=false&key=AIzaSyBItTTXWsLZVRnRyhEzwfC3pO__QzDKSQo";
    document.getElementById("staticMap" + id).innerHTML = "<img src='"+img_url+"'>";
 }
 
  function showLocationOut(id,latitude,longitude){
    /*REQUIRES GOOGLE STATIC MAP API*/
    var latlon = latitude + "," + longitude;
   <?php // var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=300x200&sensor=false&key=AIzaSyDgH5mAegqWsg3LTQ8LSJW2RlMofmRsOfg"; ?>
    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=300x200&sensor=false&key=AIzaSyBItTTXWsLZVRnRyhEzwfC3pO__QzDKSQo";
    document.getElementById("staticMapOut" + id).innerHTML = "<img src='"+img_url+"'>";
 }
 </script>
   <!-- Scroll to Top Button-->
   <a class="scroll-to-top rounded" href="#page-top">
     <i class="fa fa-angle-up"></i>
   </a>
   <div class="footer">
     <p>Powered by JSoft Solution Sdn. Bhd</p>
   </div>
</body>
</html>