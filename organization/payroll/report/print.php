<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
 session_start();
}

require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
	</style>
	<style>
        @font-face {
   font-family: arial;
   src: url(ARIAL.woff);
        }

* {
   font-family: arial;
}
        body{
            height: 99%;
            width: 824px;
              -webkit-print-color-adjust:exact;

        }
        .print:last-child {
            page-break-after: auto;
        }
        .fontsize{font-size: 8pt}
    .fontsize20 {font-size: 20pt;font-weight: 800; width: 50px;}
        .fontsize10 {font-size: 10pt;font-weight: 800}
        .dark{color:#ffffff;background-color: #000000;text-align: center;}
        td{padding:3px;
        }
        .small{width:17px}
        .insert{background-color:#ddebf7;font-weight: 600;border: 1px #000000 solid !important;letter-spacing: 1px;}
        .td1{width:70px}
        .td2{width:90px}
        .td3{width:90px}
        .td4{width:60px}
       table{align-content: center;}
        @media print {
            @page {
                margin-top: 0;
                margin-bottom: 0;
                size: auto;
                 }

        }
    </style>
  </head>
  <body>
    
    <?php
//    echo "<script>window.print()</script>";
    echo '<div class="print">'.$_SESSION['eaReport'].'</div>';
    unset($_SESSION['eaReport']);
    //header( "Refresh:5; url=../eareport.php");
    ?>
 
    <script type="text/javascript">
    window.print();
    </script>
  </body>
</html>