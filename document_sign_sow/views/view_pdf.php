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
  </style>
</head>
<body>
  <div class="container text-center">
    <h1>Digital Sign</h1>
</div>

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
      <ul class="nav navbar-nav">
        <li class="active"><a href="javacript:void(0)" onclick="save_pdf_data()">save</a></li>
        <li class="active"><a href="javascript:window.location.reload(true)">reset</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container" > 
    <div class="row" >
        <div class="col-sm-6" >
            <h4>Please choose type of attestation</h4>
        </div>
    </div>
    <div class="row" >
        <div class="col-sm-12" >
            
            <div class="col-sm-2" >
                <input type="radio" id="sign_only" name="fav_language" value="sign_only" checked>
                <label for="sign_only">Only Signature</label> 
            </div>
            <!-- <div class="col-sm-2" >
                <input type="radio" id="stamp_only" name="fav_language" value="stamp_only">
                <label for="stamp_only">Only Stamp</label> 
            </div> -->
            <div class="col-sm-2" >
                <input type="radio" id="signature_and_stamp" name="fav_language" value="signature_and_stamp">
                <label for="signature_and_stamp">Signature And Stamp</label>
            </div>
        </div>

    </div>  
    <div class="row" >
        <div class="col-sm-12" id="paginator"> 
        </div>
    </div>
    <div class="row" >
        <div class="col-sm-12" id="pdf_div" ></div>
        </div>    
        

    </div>
</div><br>

	</div>




<script>
    
    get_pdf_data();
    var current_page =0;
    var pages_arr;
    function get_pdf_data(){

        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var pdf_div = document.getElementById("pdf_div")
                var rep_arr = xhttp.responseText.split('****||****');
                pages_arr = rep_arr.slice(0, -1); 
                pdf_div.innerHTML =pages_arr[0];
                paginate_pdf();
            }
        };
        xhttp.open("GET", "<?php echo '../'; ?>get_pdf_data/<?php echo $_GET['id']?>", true);
        //xhttp.open("GET", "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/get_pdf_data/<?php echo $_GET['id']?>", true);
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
        xhttp.open("POST", "<?php echo '../'; ?>save_pdf_data/<?php echo $_GET['id']?>", true);
        xhttp.send(data);
    }

    function printMousePos(event) {
        
        var type = document.querySelector('input[name="fav_language"]:checked').value;
        var doc = document.documentElement;
        var left = (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0);
        var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
        console.log("position: " + top);

        const element = document.getElementById("pdf_div");
        var divtop = element.offsetTop;
        var divleft = element.offsetLeft;
        console.log(divleft);
        var x = event.clientX+left-divleft;
        var y = event.clientY+top-divtop;
        console.log("clientX: " + x + " - clientY: " + y);
        if(type ==='stamp_only'){
            var html = '<div class="txt" style="position:absolute; left:'+x+'px; top:'+y+'px;"><span class="f5" style="font-size:12px;vertical-align:baseline;color:rgba(0,0,0,1);"><button class="btn btn-default stamp_button" >Stamp_Here</button></span></div>';
        
        }else if(type ==='signature_and_stamp'){
            var html = '<div class="txt" style="position:absolute; left:'+x+'px; top:'+y+'px;"><span class="f5 sign_stamp" style="font-size:12px;vertical-align:baseline;color:rgba(0,0,0,1);" >Sign_Stamp_Here</span></div>';
         
        }else{
            var html = '<div class="txt" style="position:absolute; left:'+x+'px; top:'+y+'px;"><span class="f5 signonly" style="font-size:12px;vertical-align:baseline;color:rgba(0,0,0,1);">Sign_Here</span></div>';
         
        }
        var pdf_div = document.getElementById("pdf_div")
        pdf_div.innerHTML +=html;
        //<img width="40" height="30" src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/user_signature/signature.png" />
    }
    var pdf_div = document.getElementById("pdf_div")
    pdf_div.addEventListener("dblclick", printMousePos);

    
</script>
</body>
</html>
