<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function tableToExcel(table, name, filename) {
        let uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><title></title><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
        base64 = function(s) { return window.btoa(decodeURIComponent(encodeURIComponent(s))) },         format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; })}

        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}

        var link = document.createElement('a');
        link.download = filename;
        link.href = uri + base64(format(template, ctx));
        link.click();
        setTimeout(closeWindow, 2000);
}
</script>
<style>

tr.border_top td {
  border-top:1px solid black;
}
</style>
</head>
        
<body>
<!--<input type="button" onclick="tableToExcel('profitLossTable', 'name', 'Profit & Loss Report.xls')" value="Export to Excel">-->
    

<div style="display:none">
    
<?php 
   // ob_start();
    echo $_SESSION['productex'];
   // ob_end_clean();
    ?>

</div>

</body>
<script type="text/javascript">
  tableToExcel('productlist', 'name', 'Product List.xls');

  function closeWindow(){
    window.close();
  }

  var dots = window.setInterval( function() {
    var wait = document.getElementById("wait");
    if ( wait.innerHTML.length > 3 )
        wait.innerHTML = "";
    else
        wait.innerHTML += ".";
    }, 100);
</script>
</html>
<?php ob_end_clean();
// Headers for download 
header("Content-Disposition: attachment; filename=\"Product List ".date("d-m-Y-h-i-s").".xls\""); 
header("Content-Type: application/vnd.ms-excel"); 

echo($_SESSION['productex']); ?>