<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/fm.php");
$query="";
getMyFiles();
if(isset($_GET['p'])&&!empty($_GET['p'])){
    $query="?p=".$_GET['p'];}
if(isset($_GET['dl'])&&!empty($_GET['dl'])){
    if(!empty($_GET['p'])){
    $query.="&dl=".$_GET['dl'];}
    else{$query.="p=&dl=".$_GET['dl']; }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - MyFiles</title>
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
      .no-gutters .card{border-radius: unset;}
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


             <?php
             $folder_root=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/files";
             $direc = $folder_root."/".$_SESSION['fm'];
             $dirList=array();
             function getDirContents($dir) {
                 $files = scandir($dir);

                 foreach($files as $key => $value){

                     $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
                     if(!is_dir($path)) {
                         continue;
                         yield $path;

                     } else if($value != "." && $value != "..") {
                      //   yield from getDirContents($path);
                         yield $path;
                     }
                 }
             }

             $path=str_replace("/","\\",$direc);
             $dlist=getDirContents($path);

             if(isset($_SESSION['fm']) && !empty($_SESSION['fm'])){ ?>
                 <div class="col-sm-2 no-gutters">

                     <div class="accordion" id="accordionSideMenu">
                         <div class="card">
                             <div class="card-header" id="headingOne">
                                 <h5 class="mb-0">
                                     <a class="" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                         My Drive
                                     </a>
                                 </h5>
                             </div>

                             <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionSideMenu">
                                 <div class="card-body">
                                     <?php
                                     $dir = new DirectoryIterator($direc);
                                     echo '<ul class="side">';
                                     foreach ($dir as $fileinfo)
                                     {
                                         if (!$fileinfo->isDot()) {
                                             if($fileinfo->isDir()){
                                                 echo '<li><a href="myFiles.php?p='.$fileinfo->getFilename().'">'.$fileinfo->getFilename().'</a></li>';
                                                 echo '</li>';}
                                         }
                                     }
                                     echo '</ul>';
                                     ?>
                                 </div>
                             </div>
                         </div>
                      <?php  /* <div class="card">
                             <div class="card-header" id="headingTwo">
                                 <h5 class="mb-0">
                                     <a class="collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                         Shared With Me
                                     </a>
                                 </h5>
                             </div>
                             <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSideMenu">
                                 <div class="card-body">
                                     <?php
                                     getSharedFolders();
                                     echo '<ul class="side">';
                                     foreach($_SESSION['fsm'] as $flist){
                                         if(is_dir($folder_root."/".$flist['path'])){
                                             $folder=explode("/",$flist['path']);
                                             $count= sizeof($folder);
                                             $fname=$folder[$count-1];
                                             echo '<li><a href="sharedMe.php?p='.$flist['path'].'">'.$fname.'</a></li>';
                                             echo '</li>';
                                         } else {
                                             continue;
                                             echo "\n<br>".$flist['path'].": N; ";
                                         }
                                     }
                                     echo '</ul>';
                                     ?>
                                 </div>
                             </div>
                         </div> */ ?>
                     </div>
                 </div>
          <div class="col-sm-10 no-gutters">
              <script>
                  function adjustIframe(obj) {
                      if(obj.contentWindow.document.body.scrollHeight>800){
                          obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                  }else{
                      obj.style.height='800px';
                  }
                  }
              </script>

            <iframe src="../../tiny/fmanager.php<?php echo $query; ?>" style="border: 0px;width:100%;height: 800px" id="myIframe" onload="adjustIframe(this)"></iframe>


          </div>
              <?php } else { ?>
                <div class="col-12 no-gutters">
                 <h3 style="margin-top:50px; text-align: center;">You are not authorize/assign for <br>FILE MANAGEMENT <br>for access check with your administrator</h3>
                </div>
              <?php  } ?>
        </div>
    </div>
  </section>
<footer class="footer-part">
    <?php require("./assets/footer-content.php"); ?>
</footer>
<?php require("./assets/js.php"); ?>
</body>
<script>
$('#myCollapsible').collapse({
toggle: true;
});
    </script>
</html>