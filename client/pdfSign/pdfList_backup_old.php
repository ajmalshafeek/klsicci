<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    if(isset($_SESSION['pdfFileName'])){
        unset($_SESSION['pdfFileName']);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - pdf list</title>
 <?php require("./assets/css.php"); ?>
  <link rel="stylesheet" href="./css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- datatable -->

    <script src='https://code.jquery.com/jquery-3.5.1.js'></script>
    <script src='https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js'></script>

    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>

    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>

    <!-- datatable -->
  <style>
      .container-fluid{padding: 0px;}
      iframe{height: 100%}
     .side ul {
          list-style: none;
          padding: 0;
      }
      .side li {
          padding-left: 2em;
          font-size: 1.2em;

      }
      .side li:before {
          content: "\f07b"; /* FontAwesome Unicode */
          font-family: FontAwesome;
          display: inline-block;
          margin-left: -1.3em; /* same as padding-left set on li */
          width: 1.3em; /* same as padding-left set on li */
      }
      .side li a{ color:#2a3746;}
      .menu-title {border-bottom: 2px solid #2a3746; padding: 0.7em;margin-bottom: 1em}
      .card-header a{ width: 100%}
      .no-gutters{padding-left: unset;padding-right: unset;}
      .no-gutters .card{border-radius: unset;}
  </style>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        } );


    </script>
</head>
<body>
<?php
// header menu
require("./assets/top-menu.php");
// side menu
require("./assets/side-menu.php");
// cart side menu
//require("./assets/cart-side-menu.php");
?>
  <?php /*<section class="single-banner">
    <div class="container">
      <h4>FILE MANAGER</h4>
    <!--  <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./store.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">profile</li>
      </ol> -->
    </div>
  </section> */ ?>
  <section class="file-part">
    <div class="container-fluid">
      <div class="row">
          <?php
          if(isset($_SESSION['message'])) {
              echo '<div class="col-12">'. $_SESSION['message'] . '</div>';
              unset($_SESSION['message']);
          } ?>

          <div class="col-12">
              <?php
              require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/signPDF.php");
              $table=fetchPdfList(null,1,$_SESSION['companyId'],1,1);
              ?>
          </div>

        </div>
    </div>
  </section>
<footer class="footer-part">
    <?php require("./assets/footer-content.php"); ?>
</footer>
<?php require("./assets/js.php"); ?>
</body>
<script>
//$('#myCollapsible').collapse({
//toggle: true;
//});
    $( document ).ready(function() {
    $("#reassignWorker,#assignWorker").on("click",function () {
        document.getElementById("loader").style.display="block";
    });
    document.getElementById("loader").style.display="none";
});

    function sendData(path, parameters, method='post') {

    const form = document.createElement('form');
    form.method = method;
    form.action = path;
    document.body.appendChild(form);

    for (const key in parameters) {
    const formField = document.createElement('input');
    formField.type = 'hidden';
    formField.name = key;
    formField.value = parameters[key];

    form.appendChild(formField);
}
    form.submit();
}
    function pdfclick(id, filename)
    {
        sendData("https://<?php echo $_SERVER['HTTP_HOST'] . $GLOBALS['config']['appRoot'];?>/client/pdfSign/pdf.php", {
            pfid: id,
            pdf:filename
        });
    }
    function pdfview(filename)
    {
        sendData("https://<?php echo $_SERVER['HTTP_HOST'] . $GLOBALS['config']['appRoot'];?>/client/pdfSign/viewPDF.php", {
            pdf:filename
        });
    }
function pdfsign(id, filename)
{
    sendData("https://<?php echo $_SERVER['HTTP_HOST'] . $GLOBALS['config']['appRoot'];?>/client/pdfSign/signPDF.php", {
        pfid: id,
        template:filename
    });
}
</script>

</html>