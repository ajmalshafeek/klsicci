<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION)) 
{ 
 	session_name($config['sessionName']);
	session_start(); 
} 


?>




<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

   <!--
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <script src="js/bootstrap.min.js" ></script>
-->

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
            .iframe-container {
  overflow: hidden;
  padding-top: 56.25%;
  position: relative;
}

.iframe-container iframe {
   border: 0;
   height: 100%;
   left: 0;
   position: absolute;
   top: 0;
   width: 100%;
}

  </style>
   
</head>

<body class="fixed-nav " >
<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
            
<div class="content-wrapper" >
    <div class="container-fluid" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
      <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol style="color: white" class="breadcrumb col-md-12">
        <li style = "color:white" class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"></li>Schedule
      </ol>
  </div>
    <div class="container-fluid" style="text-align:justify;height:200vh;border-radius:0px;">
    
    <div id="cleanto" style="width:100%;height:100%;" class="direct-load" data-url=<?php echo '"https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/cleanto/admin"' ?>></div>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>cleanto/assets/js/embed.js?time=1543478075'></script>
    
      
   <!--
    
      
      

   -->
   <!--test-->

      
    </div>
   
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

   
          </div>

          <div class="footer">
              <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>
 
 
</body>

</html>

