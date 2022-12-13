<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
?>
<!DOCTYPE html >
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta https-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <style>

    </style>

</head>
<body class="fixed-nav ">
<?php
include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb col-md-12">
            <li class="breadcrumb-item">
                <a href="../../home.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item ">Client</li>
            <li class="breadcrumb-item active">File Manager</li>
        </ol>
    </div>

    <?php
    if (isset($_SESSION['feedback'])) {
        echo $_SESSION['feedback'];
        unset($_SESSION['feedback']);
    }
    ?>
    <div class='card mb-3'>
        <div class='card-header'>
            <script>
                function adjustIframe(obj) {
                    if(obj.contentWindow.document.body.scrollHeight>800){
                        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                    }else{
                        obj.style.height='800px';
                    }
                }
            </script>
            <iframe src="../../tiny/admmanager.php" style="border: 0px;width:100%;height: 800px" id="myIframe" onload="adjustIframe(this)"></iframe>

        </div>

    </div>
</div>
</div>

<!-- (START)Show Client Product -->
<div class="modal fade" id="clientProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientProductModalTitle">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="clientProductId">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- (END)Show Clinet Product -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
</a>

<div class="footer">
    <p>Powered by JSoft Solution Sdn. Bhd</p>
</div>
</div>

</body>
</html>
