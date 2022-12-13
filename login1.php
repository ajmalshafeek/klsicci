<?php

$config=parse_ini_file(__DIR__."/jsheetconfig.ini");

session_name($config['sessionName']);
session_start();
if(isset($_SESSION['userid']))
{
    header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php');

}

?>

<link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/custom-css.css" rel="stylesheet">

<!DOCTYPE html >

<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta https-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome5/css/all.min.css" rel="stylesheet" type="text/css">
    <title>SMART OFFICE</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php"); // require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <style>
    #showMobile{
        display: none;
      }
    @media screen and (max-width: 600px) {
      #tagLine{ 
        display: none;
      }
      #showMobile{
        display: block;
      }
    }
    .bgimage {
        background: url(resources/app/jobsheet_bg3.png) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-repeat: no-repeat;

        }
            .loginTable{
                width:100%;
                text-align:center;
                border:1px solid black;

            }
            .loginTable td{
                border:1px solid black;
            }
            .loginBtn{
                width:100%;

            }
            .loginBtn:hover{
                background-color:#E32526;
            }
            .modal-signUp {
                min-width: 60%;
            }

    .shadowAllSides{
      width:350px;height:200px;
      /*border: solid 1px #555;
      background-color: #eed;*/
      box-shadow:  0 0 30px 5px rgba(0,0,0,0.6);
      -moz-box-shadow: 0 0 30px  5px rgba(0,0,0,0.6);
      -webkit-box-shadow: 0 0 30px 5px rgba(0,0,0,0.6);
      -o-box-shadow: 0 0 30px 5px rgba(0,0,0,0.6);
    }

    @font-face{
     font-family: "sansationbold";
     src: url(SansationBold.woff) format('woff');
     font-weight: bold;
    }

    @font-face{
     font-family: "agencyb";
     src: url(AGENCYB.woff) format('woff');
     font-weight: bold;
    }

    @font-face{
     font-family: "harabara";
     src: url(Harabara.woff) format('woff');
     font-weight: bold;
    }

    .textCustom{
      text-align: center;
      font-family: "sansationbold" !important;
      font-size: 25px;
    }
    
    .textCustomMobile{
      text-align: center;
      font-family: "sansationbold" !important;
    }

    .inputCustom{
      border-bottom:0px !important;
      border-radius:0px;
      font-family: "agencyb" !important;
    }

    .submitCustom{
      background: white !important;
      color: black !important;
      border: 1px solid #BDBCBC !important;
      border-radius: 0px !important;
    }

    .submitCustom:hover{
      background: #BDBCBC !important;
      color: white !important;
    }

    input[type="email"], input[type="password"], input[type="text"]{
      -webkit-box-shadow: inset 0 0 0px 9999px #BDBCBC;
      background-color : #BDBCBC !important;
      color:white !important;
    }

    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
      color: white !important;
      opacity: 1 !important; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
      color: white !important;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
      color: white !important;
    }
    </style>
   <script>
    $(document).ready(function() {
            $('#formLogin').submit(function() {
                $('#formLogin').addClass('was-validated');
                $('#formLogin input').filter('[required]').each(function(){
                    if( $.trim( $(this).val() ) === ""){

                        event.preventDefault();
                        event.stopPropagation();
                    }
                });

            });
            <?php
                if (isset($_GET['regSuccess'])) {
                    echo "$('#signUpSuccessModel').modal('show');";

                }
            ?>
            $("#signUpSuccessModel").on("hidden.bs.modal", function () {
                $(location).attr('href', <?php echo '"https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/"'; ?>);
            });

    });

    </script>
</head>
<body class="bgimage" >

<div class="container py-5" >
    <div class="row align-items-center justify-content-center" style="height:400px !important">
        <div id="tagLine" class="col-md-3 pt-5 shadowAllSides" style="background: rgba(255,255,255,0.3);height:100%" >
          <center><h1 style="font-family: 'harabara';color:#EF0B06;"><i>SMART OFFICE<i class="fab fa-phoenix-framework"></i></i></h1></center>
          <!--<center><h1 style="font-family: 'harabara';color:#EF0B06;"><i>EASY<i class="fas fa-shipping-fast"></i></i></h1></center>-->
          <p class="textCustom"><b>SIMPLIFY</b></p>
          <p class="textCustom"><b>YOUR</b></p>
          <p class="textCustom"><b>BUSINESS</b></p>
          <p class="textCustom"><b>PROCESS</b></p>
        </div>
        <div class="col-md-5 shadowAllSides" style="background: rgba(255,255,255,0.8);height:100%" >
            <div id="showMobile">
            <span style="font-family: 'harabara';color:#EF0B06;">SMART OFFICE </span><span class="textCustomMobile"><b>| <u>SIMPLIFY YOUR BUSINESS PROCESS</u></b></span>
            </div>
            <div class="row">
                <div class="col-md-12 mx-auto" >
                  <div class="card" style="background:transparent;border:0;">
                        <!--<div style="background-color:transparent; border-color:transparent;" class="card-header pt-0">
                         <?php
                         if (isset($_GET['feedback'])) {
                            echo "EMAIL HAS BEEN SENT";
                            unset($_GET['feedback']);
                        }
                        ?>
                      </div>-->
                        <div style="background-color: transparent; border-color:black;" class="card-body">
                            <form class="form" role="form"  id="formLogin" action="phpfunctions/login.php" method="POST" class="needs-validation" novalidate >
                            <input type="text" value="2" name="orgId" hidden/>
                            <div class='form-group' >
                              <center><h1 style="font-family: 'agencyb' !important;">LOGIN</h1></center>
                              <div class="form-group">
                              <div class="input-group mb-3">
                                  <input  type="text"  class="form-control form-control-lg inputCustom" name="username" id="username" placeholder="username" required>
                                  <div class="alert alert-danger invalid-feedback">Please enter Username</div>
                              </div>
                              </div>
                              <div class="form-group">
                                  <div class="input-group mb-3">
                                  <input type="password"  class="form-control form-control-lg inputCustom" name="password" id="password" placeholder="password" required>
                                  <div class="alert alert-danger invalid-feedback">Please enter password</div>
                               </div>
                               <button name="login" class="btn btn-lg btn-block submitCustom"  type="submit">Login <i class="fas fa-share-square"></i></button>
                               <div style="margin:2vh;"></div>
                               <table style="width:100%;">
                                  <tr>
                                      <td style="width:50%;">
                                      </td>
                                      <td style="text-align:right">
                                      </td>
                                  </tr>
                              </table>
                              </div>
                            </div>
                            </form>
                        </div>
                  </div>
                </div>
            </div>
        </div>
      </div>
</div>



<!-- FORGET MODAL START-->
<form class="form" role="form"  id="formFroget" action="phpfunctions/forgotPassword.php" method="POST">
<div class="modal fade " data-backdrop="static" id="forgetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="quotation2InvoiceModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" >
            <h5 class="modal-title" id="forgetPasswordModalTitle">Reset Password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

         <!--
          <label>Select type</label>

           <select style="background-color: rgb(250, 255, 189); font-size: 16px; width: 415px;" name='type' class='form-control form-control-lg' id='typeforgot' required>
                        <option value="1" >JSOFTSOLUTION SDN BHD</option>
                        <option value="0" >VENDOR</option>
                        <option value="-1" >CLIENT</option>
            </select>
            -->
          <label for="username" style="margin:5px;">Please enter your email</label>
          <input type="email" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
          border-radius: 4px;box-sizing: border-box;" id="emailAddressForgot" name="username" placeholder="Email.." required>


          </div>
          <div class="modal-footer">

		      <button type="submit" style="width:150px;" name="forgetPassword" class="btn btn-primary">Forget Password</button>
          </div>

        </div>
      </div>
    </div>
    </form>
    <!-- FORGET MODAL END -->

    <!-- SIGN UP MODAL START-->
    <form class="form" role="form"  id="formSignUp" action="phpfunctions/signup.php" method="POST">
        <div class="modal fade "  data-backdrop="static" id="signUpModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-signUp"  role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signUpModalTitle">Register an Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <input type="password" style="display:none">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="exampleInputName">First name</label>
                                <input type="text" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
                                border-radius: 4px;box-sizing: border-box;" name="firstName" id="firstName"  placeholder="Enter first name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputLastName">Last name</label>
                                <input type="text" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
                                border-radius: 4px;box-sizing: border-box;" name="lastName" id="lastName" placeholder="Enter last name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                        <div class="col-md-6">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" minlength="8" autocomplete="off" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
                            border-radius: 4px;box-sizing: border-box;" id="password2" name="password" placeholder="Enter Password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="exampleConfirmPassword">Phone Number</label>
                            <input type="tel" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
                            border-radius: 4px;box-sizing: border-box;" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" required>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;
                        border-radius: 4px;box-sizing: border-box;" id="email" name="email" placeholder="Enter email" required>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" name="signUp" id="formSignUp" class="btn btn-primary">Sign Up</button>
                    <button type="button" class="btn btn-secondary" title="CLOSE DIALOG" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
    </form>
          <!-- SIGN UP MODAL END -->

    <!-- SIGN UP SUCCESS MODEL START -->
    <div class="modal fade " data-backdrop="static" id="signUpSuccessModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">

                <?php
                    if(isset($_GET['regSuccess'])){
                    //if($_GET['regSuccess']=='true'){
                ?>
                    <div class="modal-header alert-success">
                    <h5 class="modal-title" id="signUpSuccessModelTitle">REGISTRATION SUCCESS!</h5>
                <?php
                    }else{
                ?>
                    <div class="modal-header alert-danger">
                    <h5 class="modal-title" id="signUpSuccessModelTitle">REGISTRATION FAILED!</h5>
                <?php
                    }
                ?>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div class="modal-body">

                        <?php
                            if(isset($_GET['regSuccess'])){
                            //if($_GET['regSuccess']=='true'){
                        ?>
                            Your account has been successfully created, kindly login to use our service.

                        <?php
                            }else{
                        ?>
                            Sorry, we are unable to create your account.
                        <?php
                            }
                        ?>
                    </div>

                </div>
        </div>
    </div>
<!-- SIGN UP SUCCESS MODEL END -->
</body>
</html>
