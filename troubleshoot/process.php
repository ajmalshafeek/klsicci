<?php
if (isset($_POST['submit'])) {
    echo "THIS MESSAGE WILL APPEAR IF THE HEADER() DOES NOT WORK";
    header("Location: main.php");
}
?>
