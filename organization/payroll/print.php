<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/sb-admin.css" rel="stylesheet">

    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/custom-css.css" rel="stylesheet">
    <style type="text/css" media="print">
      /*@page { size: landscape; }*/
      p{
          font-size:11px;
      }
      h3{
          font-size:19px;
      }
      h2{
          font-size:19px;
      }
    </style>

</head>
<body>

<?php
  echo $_SESSION['payslip'];
?>
<script>
window.print();
</script>
<?php echo '<meta http-equiv="refresh" content="2;URL=\'payroll.php\'">'; ?>
</body>
</html>
