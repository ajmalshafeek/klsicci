<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}

$style="<style>
table{
  border-spacing:0;
  width:100%
}
tr td {
  border:1px solid black;padding:5px;
}
</style>";

$script="
<script>
  window.print();
  setTimeout(closeWindow, 2000);
  function closeWindow(){
    window.close();
  }
</script>
";

  echo $style.$_SESSION['sentouttable'].$script;

?>
