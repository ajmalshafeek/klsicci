
<?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 if(!isset($_SESSION)) 
 { 
  session_name($config['sessionName']);
	session_start(); 
} 
	
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");	

?>
<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->
    <script>

      function clientDelete(str){
       
        $("#clientIdToDelete").val(str.value);

      }

      function showPassword(pwdId) {
        
        var x = document.getElementById(pwdId);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        
    }
    </script>
    <style>
    .buttonAsLink{
      background:none!important;
      color:inherit;
      border:none; 
      font: inherit;
      cursor: pointer;
    }
          
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
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">CLIENT</li>
        <li class="breadcrumb-item active">VIEW CLIENT</li>
      </ol>

      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i> 
            CLIENT LIST
				  </div>
          <?php
            clientListTable();
          ?>

          </div>
        </div>
    </div>
   
    <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/clientCompany.php" ?>" >

<div class="modal fade" id="clientDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="clientDeleteModalTitle">REMOVE CLIENT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='staffDeleteContent' >
              Are you sure want to remove client ?
            </div>

          <div class="modal-footer">
            <input type="text" hidden name="clientIdToDelete" id="clientIdToDelete" value=""  />

            <button type="submit" name='removeClient' class="btn btn-primary" >YES</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
          </div>
        </div>
      </div>
    </div>   
  </form>     
    
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

