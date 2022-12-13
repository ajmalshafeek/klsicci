<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
use setasign\Fpdi\Fpdi;
require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['config']['appRoot'].'/organization/pdf/vendor/autoload.php');
require($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['config']['appRoot'].'/organization/pdf/signature.class.php');

$fileName="";
$pfid="";
$path="https://".$_SERVER['HTTP_HOST'] . $GLOBALS['config']['appRoot'];
if(isset($_POST['pdf'])){
    $fileName=$_POST['pdf'];
}else{
    die('PDF File not Valid');
}


$s = new signature;
$details=$s->getDetails($fileName);
if(!empty($details)) {
    $px=0.2645833333;
    $mm=3.7795275591;
    $pageSize=((($details['h']*1.031)*$mm)*$details['p']);
    $pageSize=round($pageSize,0);
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Sign PDF - view pdf</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
    <embed id="embed_pdf" type='application/pdf' src="<?php echo $path.'/resources/2/pdf/'.$fileName?>" width="100%" style="position: absolute; height: <?php echo $pageSize."px;";?>"  />
    <p>Error message</p>
    <div id="test" style="position: absolute;width: 100%;height: <?php echo $pageSize."px;";?> background-color: rgba(204,204,204,0.2);"></div>
    <script>
        var px=<?php echo $px; ?>;
        var mm=<?php echo $mm; ?>;
        var h=<?php echo $details['h']; ?>;
        var w=<?php echo $details['w']; ?>;
        var p=<?php echo $details['p']; ?>;
        var clickY="";
        var width=$(window).width();
        $(window).resize( function(){
            //$('embed').attr('height', 200);
            height = $(window).height() - 75;
            //alert(height);
            $('embed').attr('height', height);
        });
    </script>
    </body>
    </html>
<?php }else{ ?>
    <!doctype html>
    <html lang="en">
    <head>
        <title>Not Valid</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
    <p>Error! Document is not valid</p>
    </body>
    </html>
<?php } ?>