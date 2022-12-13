<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
  session_name($config['sessionName']);
  session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");


?>
<!DOCTYPE >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
  <!--  
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>

    <script src="../../js/bootstrap.min.js" ></script>
    
    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
--> 
<?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?> <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->


	  <script>
  
    </script>
    <style>

    
    
          
    </style>
   
</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
<div class="content-wrapper">
<div class="container-fluid">

    <span id="pageTitleBlock" class="d-block p-3 bg-primary ">My Booking</span>  
    <br/>
        <div id="cleanto" class="direct-load" data-url=<?php echo '"https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/cleanto/admin/my-appointments.php"' ?>></div>
        <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>cleanto/assets/js/jquery-2.1.4.min.js'></script>
        <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>cleanto/assets/js/embed.js?time=1543478075'></script>
    
</div>
</div>
</body>
</html>
