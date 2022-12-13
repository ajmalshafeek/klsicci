<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/signPDF.php");
$detail=null;
if(isset($_POST['pfid'])){
  $detail = getpdfdetails($_POST['pfid']);
}elseif(isset($_POST['id'])){
    $detail = getpdfdetails($_POST['id']);
}

?>
<!DOCTYPE>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");

    ?>
    <style>
    .buttonAsLink {

        background: none !important;

        color: inherit;

        border: none;

        font: inherit;

        cursor: pointer;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th {
        background: grey;
        color: black;
    }
    table,

    td,

    th {

        border: 1px solid black;

        text-align: center;

    }

    </style>

    <link rel="stylesheet" href="./../../css/jquery-ui.min.css">

    <!--<script src="./../../js/jquery-ui.js"></script> -->

  <script src="./../../js/jquery-ui.min.js"></script>

  <script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

     </script>

</head>
<body class="fixed-nav">
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

                <li class="breadcrumb-item">PDF Sign</li>

                <li class="breadcrumb-item active">Sign Pdf</li>

            </ol>
        </div>
        <div class="container">
            <?php
                if(isset($_POST)){
            if(isset($_POST['sign']) && $_POST['sign'] != '' && isset($_POST['img']) && $_POST['img'] != ''){
                require($_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/organization/pdf/signature.class.php');
                $s = new signature;
                $date = date('YmdHis');
                $fileName="";
                if(!isset($_SESSION['pdfFileName'])) {
                    $fileName = 'Signed' . $date.".pdf";
                    $_SESSION['pdfFileName'] = $fileName;
                }else{
                      $fileName=$_SESSION['pdfFileName'];
                }
                $pdfd=getpdfdetails($_POST['id']);

                $s->use_template_pdf($fileName, $_POST['template'],$pdfd); //

                $s->delete_signature();

                $msg = '<h5>Successful Signed.</h5>';
                $signImage = $_POST['sign'];
            }
            ?>
            <p>Makes possible to sign on a monitor (even better on a tablet) and places the signature in a new pdf, existing pdf or embed it in a html page</p>
            <hr>
            <h4>Place your signature on the line in the square below and click on 'Sign!'.</h4>
            <div id="signature" style="width:600px; height:150px; border: dotted 2px black; margin: 15px 0px"></div>
            <form action="" id="f" method="post" class="needs-validation">
                <input type="hidden" name="sign" id="sign" />
                <input type="hidden" name="img" id="img" />
                <input type="hidden" name="id" id="id" value="<?php if(isset($_POST['pfid'])){ echo $_POST['pfid'];}else{echo $detail['id'];} ?>" />
                <input type="hidden" name="template" id="template" value="<?php if(isset($_POST['template'])&&!empty($_POST['template'])){ echo $_POST['template'];}else{echo $detail['pdfName'];} ?>" />
                <div class="form-group row"> <button type="button" class="btn btn-primary btn-lg" onclick="signature()" style="margin-left: 15px" >Sign</button></div>
            </form>
            <?php
            if(isset($msg)) echo($msg);
            if(isset($_SESSION['pdfFileName'])){
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/2/pdf/".$_SESSION['pdfFileName'])) {
                echo('<p><a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/pdf/".$_SESSION['pdfFileName'].'" class="btn" target="_blank">Check Signature on PDF</a></p>'); ?>

                    <form action="https://<?php echo $_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/signPDF.php" id="g" method="post" class="needs-validation">
                        <input type="hidden" name="output" id="output" value="<?php if(isset($_SESSION['pdfFileName'])){ echo $_SESSION['pdfFileName'];} ?>" />
                        <input type="hidden" name="id" id="id" value="<?php if(isset($_POST['pfid'])){ echo $_POST['pfid'];}else{echo $detail['id'];} ?>" />
                        <div class="form-group row">
                            <button type="submit" name="savepdf" class="btn btn-primary btn-lg" style="margin-left: 15px" >Save Signed Pdf</button>
                        </div>
                    </form>
            <?php }}
            ?>
            <script src="http://code.jquery.com/jquery-latest.min.js"></script>
            <!--[if lt IE 9]>
            <script type="text/javascript" src="flashcanvas.js"></script>
            <![endif]-->
            <script src="jSignature.min.js"></script>
            <script>
                $(document).ready(function() {
                    $("#signature").jSignature()
                })
                function signature(){
                    var $sigdiv = $("#signature");
                    var datax = $sigdiv.jSignature("getData"); // for embedding is html page
                    $('#sign').val(datax);
                    var datax = $sigdiv.jSignature("getData","image"); // for creating image
                    $('#img').val(datax);
                    $sigdiv.jSignature("reset")
                    $('#f').submit();
                }
            </script>
            <?php } else { ?>
                <p>Document not found</p>
            <?php } ?>

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