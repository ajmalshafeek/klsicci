<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
 session_start();
}

require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    </style>
  </head>
  <body>
    <div class="">
        <img src='../../resources/2/myOrg/banner/<?php 
    	$orgId = $_SESSION['orgId'];
    	$bannerName = bannerName($orgId);
    	echo $bannerName; 
    	?>' width='100%' style="max-width: 700px;">
    <?php
//    echo "<script>window.print()</script>";
    echo $_SESSION['printStaffJob'];

//    header( "Refresh:1; url=report.php");
    ?>
    </div>
    <script type="text/javascript">
    window.print();
    </script>
  </body>
</html>
