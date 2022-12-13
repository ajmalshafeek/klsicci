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
tr.border_top td {
  border-top:1px solid black;
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

echo $style.$_SESSION['productex'].$script;
?>
