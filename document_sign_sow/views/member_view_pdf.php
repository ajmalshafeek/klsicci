<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Documents</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');
    * {
        padding: 0;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }
    
    canvas#signature-pad {
        background: #fff;
        width: 100%;
        height: 100%;
        cursor: crosshair;
    }
    a.form-control.btn {
        padding: 20px !important;
        height: fit-content !important;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <h1>Digital Sign</h1>
</div>
  <?php
  $urlParams = explode('/', $_SERVER['REQUEST_URI']);
  $arv = count($urlParams) - 1;

  $docId_ecn = $urlParams[$arv];
  $docId = base64_decode($urlParams[$arv]);
  require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/signPDF.php");
  $activeButton=getButtonStatus($docId);

  ?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/views/index.php">List</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav" style="background-color: #000;">
        <li class="active"><a href="javacript:void(0)"  class="form-control btn ct-btn-big" onclick="save_pdf_data()">save</a></li>
        <li class="active"><a href="#"><input type="file"  class="form-control" name="file" id="file" required ></a></li>
        <li class="active"><a href="javacript:void(0)"  class="form-control btn" onclick="upload_stamp()">Upload Stamp</a></li>
      </ul>

    </div>
  </div>
</nav>

<div class="container" >  
    <div class="row">
    <div class="col-xs-6">
        
        <div class="col-xs-10" style="border:1px solid black">
            <canvas id="signature-pad" width="400" height="200"></canvas>
        </div>
        <div class="clear-btn">
            <button id="clear"><span> Clear </span></button>
            <!-- <button id="attest"><span> Attest </span></button> -->
        </div>
    
    </div>
    <div class="col-xs-6" id="stamp_div">
        
            
        
    </div>
    </div><br/>
    <div class="row" >
        <?php if($activeButton){ ?><div class="col-sm-2"><button class="btn btn-default" onclick="atest_doc_single_sign_on_page('pdf_div','Title')">Attest Doc</button></div>
        <?php } else { ?><div class="col-sm-2"><button class="btn btn-default" onclick="atest_doc('pdf_div','Title')">Add Signature</button></div><?php } ?>
        <div class="col-sm-10" id="paginator"> 
        </div>
    </div>
    <br/>
    <div class="row" >
        <div class="col-sm-12" id="pdf_div" ></div>
        </div>    
        

    </div>
</div><br>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<script>
    get_pdf_data();
    var current_page =0;
    var is_attested =0;
    var pages_arr;
    function get_pdf_data(){

        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                if(xhttp.responseText=='approved'){
                    alert('Document already attested!');
                }else{
                    var pdf_div = document.getElementById("pdf_div")
                    var rep_arr = xhttp.responseText.split('****||****');
                    pages_arr = rep_arr.slice(0, -1); 
                    pdf_div.innerHTML =pages_arr[0];
                    paginate_pdf();
                }
                
            }
        };
        xhttp.open("GET", "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/get_pdf_saved_data/<?php echo $docId; ?>", true);
        xhttp.send();
    }

    function paginate_pdf(){
        
        if(pages_arr.length > 1 ){
            var paginator_div = document.getElementById("paginator")
            paginator_div.innerHTML = '<button class="btn btn-default" onclick="nextpage()" id="next_button" >Next Page</button><button class="btn btn-default" onclick="prevpage()" id="prev_button" >Prev Page</button>';
            
            $("#prev_button").hide();
        }
    }
    function nextpage(){

        pages_arr[current_page] = $("#pdf_div").html();
        current_page=current_page+1;
        if(current_page > pages_arr.length-1){current_page =pages_arr.length-1;}
        if(current_page == pages_arr.length-1){

            $("#next_button").hide();
        }
        
        $("#prev_button").show();
        
        var pdf_div = document.getElementById("pdf_div")
        pdf_div.innerHTML =pages_arr[current_page];

    }

    function prevpage(){

        pages_arr[current_page] = $("#pdf_div").html();
        current_page=current_page-1;
        if(current_page < 0 ){current_page =0;}
        if(current_page < pages_arr.length-1){

            $("#next_button").show();
        }
        if(current_page < 1){
            $("#prev_button").hide();
        }

        var pdf_div = document.getElementById("pdf_div")
        pdf_div.innerHTML =pages_arr[current_page];

    }
    function save_pdf_data(){

        if(is_attested){
            pages_arr[current_page] = $("#pdf_div").html();
            var data = new FormData();
            var pagehtml='';
            for(let index = 0; index < pages_arr.length; index++) {
                
                pagehtml +=pages_arr[index]+'****||****';
            }
            data.append('pages', pagehtml);
            
            
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                alert('saved!');
                }
            };
            xhttp.open("POST", "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/save_signed_pdf_data/<?php echo $docId; ?>", true);
            xhttp.send(data);
        }else{
            alert('Please attest  document');
        }
    }

    var canvas = document.getElementById("signature-pad");

    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }
       window.onresize = resizeCanvas;
       resizeCanvas();

       var signaturePad = new SignaturePad(canvas, {
       });

       document.getElementById("clear").addEventListener('click', function(){
        signaturePad.clear();
       })
       
       
        function atest_doc(){
            
            is_attested = 1
            var pdf_div = document.getElementById("pdf_div");
            var sign_image = '<img width="80" height="60" src="'+canvas.toDataURL()+'" />';
            var stamp_image = $('#stamp_image').attr('src');
            var sign_stamp_image = '<img width="80" height="60" src="'+stamp_image+'" />'+sign_image;
            for(var index = 0; index < pages_arr.length; index++) {
                
               
                pages_arr[index] = pages_arr[index].replace('Sign_Here',sign_image)
                pages_arr[index] = pages_arr[index].replace('Sign_Stamp_Here',sign_stamp_image)
                
                
            }
            pdf_div.innerHTML =pages_arr[current_page];
            
            
        }

        var doc = new jsPDF();

        function saveDiv(divId, title) {
            doc.fromHTML(`<html><head><title>${title}</title></head><body>` + document.getElementById(divId).innerHTML + `</body></html>`);
            console.log(document.getElementById(divId).innerHTML);
            doc.save('div.pdf');
            }

            function printDiv(divId,
            title) {

            let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

            mywindow.document.write(`<html><head><title>${title}</title>`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(divId).innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        } 


        function upload_stamp(){
            
            var fdata = new FormData();
            var files = $ ('#file' )[0].files[0];
            fdata.append( 'file', files );
            $.ajax({
                url: "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/get_dataurl/",
                type: "POST",
                data: fdata,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                { 
                    $('#stamp_div').html('<img id="stamp_image" width="60" height="60" src="'+data+'">');
                }
            });
        }
    

    




</script>
</body>
</html>
