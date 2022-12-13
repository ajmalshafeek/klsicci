<?php
if(isset($_POST['genbarcode']))
{
 $text=$_POST['srn'];
 echo "<img alt='testing' src='barcode.php?codetype=Code39&size=40&text=".$text."&print=true'/>";
}

$script="
<script>
  window.print();
  setTimeout(closeWindow, 2000);
  function closeWindow(){
    window.close();
  }
</script>
";
echo $script;
?>