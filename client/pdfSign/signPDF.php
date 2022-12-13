<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Sign PDF </title>
    <?php require("./assets/css.php"); ?>
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        /*.no-gutters .card{border-radius: unset;*/
        }
         */

    </style>
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
<?php /*
<section class="single-banner">
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
                unset($_SESSION['message']);} ?>

<div class="col-12 px-sm-5 py-sm-5">
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

                $s->use_template_pdf($fileName, $_POST['template'],$pdfd);

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
                            <button type="submit" name="savepdfc" class="btn btn-primary btn-lg" style="margin-left: 15px" >Save Signed Pdf</button>
                        </div>
                    </form>
                <?php }}
            ?>
</div>
        <?php require("./assets/js.php"); ?>
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
            <?php require("./assets/js.php"); ?>
            <?php } ?>


        </div>
    </div>
</section>
<footer class="footer-part">
    <?php require("./assets/footer-content.php"); ?>
</footer>
<?php //require("./assets/js.php"); ?>
</body>

</html>