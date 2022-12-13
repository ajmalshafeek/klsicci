
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
 session_name($config['sessionName']);
   session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");	
?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

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
        <li class="breadcrumb-item ">Vendor</li>
        <li class="breadcrumb-item active">Assign Client</li>

      </ol>
    </div>
        <div class="container" >
            <form method="POST" action="../../phpfunctions/vendorClient.php" class="needs-validation" novalidate >
            
                <div id="linkVendorClientForm">
                <?php
                    if (isset($_SESSION['feedback'])) {
                        echo $_SESSION['feedback'];
                        unset($_SESSION['feedback']);
                    }
                ?>
                <?php
                dropDownListVendorActive2();
                ?>
                <?php
                dropDownListOrganizationClientCompanyActive();
                ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='addVendorClient' class="btn btn-primary btn-lg btn-block" type='submit' >Submit</button>
                    </div>
                </div>

                
                </div>
            </form>  
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

</body>
</html>

