<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}

$style='<style>
        @font-face {
   font-family: arial;
   src: url(ARIAL.woff);
        }

* {
   font-family: arial;
}
        body{
             height: 98%;
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
        @media print {
            @page {
                margin-top: 0;
                margin-bottom: 0;
                size: auto;
                 }

        }
    </style>';

$script="
<script>
  window.print();
  //setTimeout(closeWindow, 2000);
  function closeWindow(){
    window.close();
  }
</script>
";

$pdf=$_SESSION['eaReport'];
/*file_put_contents('eaForm.pdf', $pdf);
header('Content-Disposition: attachment; filename="eaForm.pdf"');
header('Content-Type: application/pdf');
header('Content-Length: ' . strlen($pdf));
header('Cache-Control: private'); */
echo '<html><head>'.$style.'</head><body><div class="print">'.$pdf.'</div>'.$script.'</body></html>';
unset($_SESSION['eaReport']);
?>