<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>

    </script>
    <style>
        .cardContainer:hover{
            opacity:0.5;
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
        <li class="breadcrumb-item ">Share</li>
      </ol>
      </div>
      <div class="container">
        <div data-toggle='modal' data-target='#shareModal' class="cardContainer col-md-4">
          <?php
          echo "<img width='100%' src='../../resources/2/card/".$_SESSION['userid']."/card.png'></object>";
          /*if ($_SESSION['userid']==7 || $_SESSION['userid']==19) {
            echo "<img width='100%' src='../../resources/2/card/".$_SESSION['userid']."/front.png'></object>";
          }else{
            echo "<i>No Card</i>";
          }*/
          ?>
        </div>
      </div>
      <!-- Show Share Modal START-->
      <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="shareModalTitle">Attachment</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <object width='100%' data='../../resources/2/card/<?php echo $_SESSION['userid'] ?>/card.pdf'></object>
           </div>
           <div class="modal-footer">
             <a href='../../resources/2/card/<?php echo $_SESSION['userid'] ?>/card.pdf' download>
             <button type="button" class="btn btn-secondary btn-lg">
 				      Share
 			 </button>
             </a>
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			 </button>
           </div>
         </div>
       </div>
     </div>
     <!-- Show Share Modal END-->

    <!-- Show Share Modal START-->
      <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="uploadModalTitle">Attachment</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <object width='100%' data='../../resources/2/card/card.pdf'></object>
           </div>
           <div class="modal-footer">
             <a href='../../resources/2/card/card.pdf' download>
             <button type="button" class="btn btn-secondary btn-lg">
 				      Share
 			 </button>
             </a>
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			 </button>
           </div>
         </div>
       </div>
     </div>
     <!-- Show Share Modal END-->

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
