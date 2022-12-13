<?php
$config=parse_ini_file(__DIR__."/jsheetconfig.ini");
?>

    <!--link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/sb-admin.css" rel="stylesheet">

    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/custom-css.css" rel="stylesheet">

<?php if(isset($_SESSION['theme'])){ ?>
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/theme/<?php echo $_SESSION['theme'] ?>" rel="stylesheet">
<?php } ?>

    <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/jquery/jquery.min.js"></script>
    <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/js/bootstrap.bundle.min.js"></script>
  
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/jquery-easing/jquery.easing.min.js'></script>
  
   <!-- 
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<script type="text/javascript">

var jQuery_2_2_4 = $.noConflict(true);
jQuery_2_2_4(window).load(function() {

jQuery_2_2_4(".bg-loader").fadeOut("slow");

});

function numberWithSpaces(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
}

</script>

<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin.min.js"></script>

<style>

.bg-green{
    background-color:#43BFC7;

  }
  .inset {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  box-shadow:
    inset 0 0 0 2px rgba(255,255,255,0.6),
    0 1px 1px rgba(0,0,0,0.1);
  background-color: transparent !important;
  z-index: 999;
}

.inset img {
  border-radius: inherit;
  width: inherit;
  height: inherit;
  display: block;
  position: relative;
  z-index: 998;
}


</style>

 <style media="screen">
      .tooltipTheme {
      position: relative;
      display: inline-block;
      }

      .tooltipTheme .tooltiptextTheme {
      visibility: hidden;
      width: 120px;
      background-color: black;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px 0;

      /* Position the tooltip */
      position: absolute;
      z-index: 1;
      margin-top:50px;
      }

      .tooltipTheme:hover .tooltiptextTheme {
      visibility: visible;
      }

      .themes{
        margin:17px;
        margin-right:0px;
        cursor: pointer;
      }

      .themeArrows{
        color:white;
        margin:17px;
      }
      a.active , a.active:hover {
          background-color: #000;
      }
      li.active a, li.active a:hover {
          background-color: #b11016 !important;
          color:#fff !important;
      }
      </style>