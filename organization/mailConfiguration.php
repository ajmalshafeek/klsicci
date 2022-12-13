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
        <li class="breadcrumb-item active">E-Mail Configuration SMTP</li>
      </ol>
    </div>
    <?php

    if($_SESSION['role']==1){ ?>
    <div class="container">
        <form method="POST" action="../phpfunctions/mailConfig.php" class="needs-validation" novalidate >
            <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
            ?>
            <div id="clientForm">
                <div class="form-group row">
                    <label for="host" class="col-sm-2 col-form-label col-form-label-lg">HOST</label>
                    <div class="col-sm-10" >
                       <input type="text" class="form-control" placeholder="Enter host" id="host" name="host" required />
                    <?php /*    <select class="form-control" placeholder="Enter host" id="host" name="host" required>
                            <option value="">-- Select --</option>
                            <option value="smtp.gmail.com">smtp.gmail.com</option>
                            <option value="smtp.live.com">smtp.live.com</option>
                        </select> */ ?>
                        <div class="invalid-feedback">
                            Please enter host
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="port" class="col-sm-2 col-form-label col-form-label-lg">PORT</label>
                    <div class="col-sm-10"   >
                        <input type="number" placeholder="Enter port" required class="form-control" id="port" name="port" step="1" />
                        <div class="invalid-feedback">
                            Please enter client port
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="user" class="col-sm-2  col-form-label col-form-label-lg">USERNAME</label>
                    <div class="col-sm-10"   >
                        <input type="text" class="form-control" placeholder="username"  id="user" name="user" />
                        <div class="invalid-feedback">
                            Please enter username.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pass" class="col-sm-2 col-form-label col-form-label-lg">PASSWORD</label>
                    <div class="col-sm-10"   >
                        <input type="password" class="form-control" placeholder="password"  id="pass" name="pass" required />
                        <div class="invalid-feedback">
                            Please enter password
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="smptsecure" class="col-sm-2 col-form-label col-form-label-lg">SMTP Secure</label>
                    <div class="col-sm-10"   >
                     <?php //   <input type="password" class="form-control" placeholder="SSL/TLS"  id="smptsecure" name="smptsecure" required /> ?>
                        <select class="form-control" placeholder="Enter host" id="smptsecure" name="smptsecure" required>
                            <option value="">-- Select --</option>
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                            <option value="STARTTLS">STARTTLS</option>
                        </select>
                        <div class="invalid-feedback">
                            Please enter smtp secure
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                    <div class="col-sm-10">
                        <button name='updateMailConfiguration'
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

     //   require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/help.php");

      ?>
        <form method="POST" action="../phpfunctions/mailConfig.php" class="needs-validation" novalidate >
      <h3 style="font-family:brush script mt;">Test Email</h3><hr>
        <button type="submit" class="btn-default btn" name="testEmail">Test Email</button>
        </form>

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