<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
$fileName="";
$pfid="";
$path="https://".$_SERVER['HTTP_HOST'] . $GLOBALS['config']['appRoot'];
if(isset($_POST['pdf'])){
    $fileName=$_POST['pdf'];
}else{
    die('PDF File not Valid');
}
if(isset($_POST['pfid'])){
    $_SESSION['pfid']=$_POST['pfid'];
}

$same=0;
if(isset($_POST['same'])&&$_POST['same']==1){
    $_SESSION['same']=$_POST['same'];
    $same=$_POST['same'];
}
use setasign\Fpdi\Fpdi;
require_once('../pdf/vendor/autoload.php');
require('../pdf/signature.class.php');
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
    <title>Sign PDF - Sign Location</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
button {position: fixed;
top:0;
left: 40%;
z-index: 999999;}
</style>
</head>
<body>
<div>
    <form action="<?php echo $path; ?>/phpfunctions/signPDF.php" method="post">
        <input type="hidden" name="same" value="<?php echo $same; ?>" />
        <input type="hidden" name="pdfFile" value="<?php echo $fileName; ?>" />
        <div class="dy"></div>
    <button type="submit" name="saveSignLocation">Save Signature Location</button>
    </form>

</div>
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
    const cy=[];
    const cx=[];
    const cp=[];
    let ct=0;
    var width=$(document).width();
    $(document).resize( function(){
        //$('embed').attr('height', 200);
        height = $(document).height() - 75;
        //alert(height);
        $('embed').attr('height', height);
    });
    $('#test').on("click",function(e) {
        clickY=(e.clientY+scrollY);
        clickX=e.clientX;
        ph=h*mm;
        var clickP=Math.floor(clickY/ph)+1;
        var temp;
        var py;
        var temp=(clickY-(14*clickP)-52)%ph;

        py=temp*px;
        pw=w*mm;
        x=(width-pw)/2;
        x=(clickX-x)*px;
        const same=<?php echo $same; ?>;


        var a=confirm("Are you sure put signature on this click point place");
        if(a){
          // window.location.href="./signPdf.php?template=<?php echo $fileName; ?>&x="+x.toFixed(3)+"&y="+py.toFixed(3)+"&p="+clickP;
           //window.location.href="<?php echo $path; ?>/organization/pdfSign/signPdf.php?template=<?php echo $fileName; ?>&x="+x.toFixed(3)+"&y="+py.toFixed(3)+"&p="+clickP;
        /*   function sendData(path, parameters, method='post') {

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

            sendData('./signPdf.php', {x: x.toFixed(3), y: py.toFixed(3),p:clickP,template:'<?php echo $fileName; ?>'});
            */
            cx[ct]=x.toFixed(3);
            cy[ct]=py.toFixed(3);
            cp[ct]=clickP;
            alert(cx.toString());

            $('.dy').append("<input type='hidden' name='x[]' value='"+cx[ct]+"' />");
            $('.dy').append("<input type='hidden' name='y[]' value='"+cy[ct]+"' />");
            $('.dy').append("<input type='hidden' name='p[]' value='"+cp[ct]+"' />");
if(same==0){
            ct=ct+1;
}
        }

    });
   function message(){
        alert("Click the starting point of signature");
    }
    setTimeout(message, 3000);

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