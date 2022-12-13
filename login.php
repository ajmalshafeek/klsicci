<?php
// Registration from

$config=parse_ini_file(__DIR__."/jsheetconfig.ini");

session_name($config['sessionName']);
session_start();
if(isset($_SESSION['userid']))
{
    header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php');

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .full-height {
            background: url(./client/bg-1.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .center{
            display: flex;justify-content: center;align-items: center;
            height: 100vh;
        }

        .left{
            float: left;
            margin-left: 100px;
        }

        .right{
            float: right;
            margin-left: 80px;
        }
        .card{border-radius:1.25em; max-width: 300px; margin-left: auto;margin-right: auto}

        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Raleway:ital,wght@0,500;0,600;0,700;0,800;0,900;1,500;1,600;1,700;1,800;1,900&display=swap');
        .card-title{font-weight: 800;color:#3e464d;}
        .card-header{
            background-color: rgba(0,0,0,.00);
            border-bottom: 0px solid rgba(0,0,0,.125);
            padding: 1.5rem 1rem 0rem 1rem;}
        .card-body{ font-weight: 700; }
        .ligthgrey{color:#787D81;}

        .form-control{font-weight: 700;}
        .form-control::placeholder{color:#CFCACB;}
        .hr{
            height: 5px !important;
            border-radius: 10px;
            background-color: #79dae8;
            width: 47px;
            margin-top: -7px;
        }
        .colorb{background-color: #ff0000 !important;border-color: #ff0000 !important;font-weight: 700 !important;}
        .bar{background-color: #B9C0C7;
            height: 2px;
            width: 100px;
            margin-left: auto;
            margin-right: auto;
            margin-top: -20px;}
        .btn-primary:hover {
            color: #fff;
            background-color: #79dae8;
            border-color: #79dae8;
        }
        .btn-primary {
            color: #fff;
            background-color: #79dae8;
            border-color: #79dae8;
        }
        .social {
            margin-top: -15px;
        }
        .social-icon img {
            padding: 10px;
            width: 50px;
        }
        .social-icon {
            width: 100px;
            margin-right: auto;
            margin-left: auto;
        }
        a{color:#5758A0 !important;text-decoration: none;cursor: pointer;}
        .modal-title{flex: auto !important;}
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
<body>

<div class="container-fluid" style="background-color: #79dae8">
    <div class="center row">
        <div class="col-sm-6 full-height center">
            <h1 class="" style="font-style:oblique;font-size:60px">
                <!--
                <b>IIAM</b> <b>MEMBERSHIP</b><br><b>PORTAL</b>

                <b>JSUITE CLOUD</b><br><b>PORTAL</b>
                   -->
            </h1>
        </div>
        <div class="col-sm-6 py-5" >
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">LOG IN</h3>
                <div class="hr"></div>
            </div>
            <div class="card-body">
                <div>
                </div>
                <form role="form"  id="formLogin" action="phpfunctions/login.php" method="POST" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <?php /*<label for="exampleInputEmail1" class="form-label">Username</label>*/ ?>
                        <input type="text" name="username" class="form-control userid" placeholder="Enter your email" required />
                        <div class="invalid-feedback">
                            Please enter Login ID
                        </div>
                        <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="mb-4">
                       <?php /* <label for="exampleInputPassword1" class="form-label">Password</label> */ ?>
                        <input type="password" name="password" class="form-control pass" placeholder="Enter your password" required />
                        <div class="invalid-feedback">
                            Please enter password
                        </div>
                    </div>
                    <!-- <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div> -->
                    <button type="submit" name="login" class="btn btn-primary col-12 colorb" value="1">SIGN IN</button>
                </form>
              <!--  <div class="mb-1 pt-3 ligthgrey" style="text-align:center;font-size:smaller">
                    <p>Client Login? <a href="./client/">Sign In</a></p>
                </div> -->
                <div class="mb-1 ligthgrey" style="text-align:center;font-size:smaller">
                    <p>Client Registration? <a href="./client/registration-for-membership.php">Sign up now</a></p>
                </div>
            </div>
            <div>
            <p class="text-muted mb-4 ligthgrey social" style="text-align:center;font-size:11px;margin-bottom:10px;font-weight: 700;">Find us at</p>
                <div class="bar"></div>
                <div class="social-icon"><a href="https://www.facebook.com/profile.php?id=100083639423314"><img src="./resources/app/fb.png" /></a><img src="./resources/app/insta.png" /></div>
            <p class="text-muted mb-4 ligthgrey" style="text-align:center;font-size:11px;margin-bottom:10px;font-weight: 800;">Powered by Jsoft Solution</p>
            </div>
            <div class="text-center mb-4">
                <small>
                    <a href="./PRIVACY%20POLICY.pdf" title="privacy policy" target="_blank">Privacy Policy</a>&nbsp;|&nbsp;
                    <a href="./REFUND%20POLICY.pdf" title="refund policy" target="_blank">Refund Policy</a>&nbsp;|&nbsp;
                    <a href="./TERMS%20AND%20CONDITIONS.pdf" title="terms & condition" target="_blank">Terms & Condition</a>
                </small>
            </div>
        </div>
    </div>

    </div>
</div>

<?php if(isset($_SESSION['message'])){ ?>
    <script>
        $(document).ready(function(){
            $("#myModal").modal('show');
        });
    </script>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Login Error
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']);  ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


<?php } ?>
</body>
</html>