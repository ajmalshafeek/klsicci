<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <style>
    table, td, th {
      border: 1px solid black;
      text-align:center;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th {
      height: 50px;
    }
    table tbody tr:hover td{
      background-color: #DEE2E6;
    }
    </style>
    <title></title>
  </head>
  <body>
    <?php echo $_SESSION['claimReportPrint'] ?>
    <script>
    window.print();
    </script>
    <?php echo '<meta http-equiv="refresh" content="2;URL=\'claimReport.php\'">'; ?>
  </body>
</html>
