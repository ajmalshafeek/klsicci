<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    if(isset($_SESSION['name'])){
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }else{
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client");
    }

}
