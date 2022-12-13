    <?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

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

   <!--
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script src="js/bootstrap.min.js" ></script>
-->

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <style>
        .buttonAsLink{
            background:none!important;
            color:inherit;
            border:none;
            font: inherit;
            cursor: pointer;
        }
        /*
        a.buttonNav {
             -webkit-appearance: button;
             -moz-appearance: button;
             appearance: button;
             text-decoration: none;
             color: white;
             background-color:red;
         }
         */
        .bg-red{
            background-color: #E32526;
        }
    </style>
</head>

<body class="fixed-nav ">
<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

<div class="content-wrapper">
    <div class="container-fluid">
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol style="color: white" class="breadcrumb col-md-12">
        <li style = "color:white" class="breadcrumb-item">
          <a href="../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"></li>Help
      </ol>
    </div>
    <?php

    if($_SESSION['role']==42){ ?>
    <div class="container">
        <form method="POST" action="../phpfunctions/help.php" class="needs-validation" novalidate enctype="multipart/form-data" >
            <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
            ?>
            <div id="clientForm">
                <div class="form-group row">
                    <label for="website" class="col-sm-2 col-form-label col-form-label-lg">WEBSITE</label>
                    <div class="col-sm-10"   >
                        <input type="text" class="form-control" placeholder="Enter website" id="website" name="website" required />
                        <div class="invalid-feedback">
                            Please enter website
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label col-form-label-lg">EMAIL</label>
                    <div class="col-sm-10"   >
                        <input type="email"  placeholder="Email Address" required class="form-control" id="email" name="email" />
                        <div class="invalid-feedback">
                            Please enter client email address
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="call" class="col-sm-2  col-form-label col-form-label-lg">CONTACT NO</label>
                    <div class="col-sm-10"   >

                        <input type="text" class="form-control" placeholder="xx-xxx xxxx"  id="call" name="call" />
                        <div class="invalid-feedback">
                            Please enter call no.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="manual" class="col-sm-2 col-form-label col-form-label-lg">Manual</label>
                    <div class="col-sm-10"   >
                        <input type="file" class="form-control" placeholder="manual"  id="manual" name="manual" required />
                        <div class="invalid-feedback">
                            Please enter manual
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='updateHelp'
                            <?php
                            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

                            $isInLimit=isInLimit($_SESSION['orgId'],2,"client");
                            if($isInLimit){
                                ?>
                                disabled
                                class="btn btn-secondary btn-lg btn-block"
                                <?php
                            }else{
                                ?>
                                class="btn btn-primary btn-lg btn-block"
                                <?php
                            }
                            ?>
                                type='submit' >SUBMIT</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php } ?>
    <div class="container mb-5" style="text-align:justify;">
        <?php

        require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/help.php");

      $data = getHelpDetails(); ?>
      <h1 style="font-family:brush script mt;">Frequently Asked Question</h1><hr>
      <h5>Question: What browser that is best viewed for this Jobsheet?</h5>
      <p>Best viewed in Google Chrome or Mozilla Firefox</p>

      <h1 style="font-family:brush script mt;">User Manual</h1><hr>
      <a href="../resources/2/<?php echo $data['manual']; ?>" style="color:blue;" target="_blank"><p>Easy user manual version 1</p></a>
        <h6>Website Address: <a href="<?php echo $data['website']; ?>"><?php echo $data['website']; ?> </a></h6>
        <h6>Support E-Mail Address: <a href="mailto:<?php echo $data['email']; ?>"><?php echo $data['email']; ?></a></h6>
        <h6>Support Contact Number: <a href="tel:<?php echo $data['call']; ?>"><?php echo $data['call']; ?></a></h6>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
          </div>

          <div class="footer">
              <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>
</div>
</div>

</body>

</html>