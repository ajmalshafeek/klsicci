<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
 session_start();
}
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
    <?php
//    echo "<script>window.print()</script>";
    echo $_SESSION['printStaffJob'];

    header( "Refresh:1; url=report.php");
    ?>
    </div>
    <script type="text/javascript">
    window.print();
    </script>
  </body>
</html>
