<?php
// Registration from
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
$count=1;
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
} ?>
<!DOCTYPE html>
<html>
<head>
    <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .full-height {
            height: 100%;
            background: url(bg-1.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .center{
            display: flex;
            /* justify-content: center; */
            align-items: center;
            height: 100%;
        }

        .left{
            float: left;
            margin-left: 100px;
        }

        .right{
            float: right;
            margin-left: 80px;
        }
    </style>
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
</head>
<body>

<div class="full-height">
    <div class="center">

        <div class="card left" style="width: 18rem;height: 30rem;">
            <div class="card-header">
                <h3 class="card-title">Login</h3>
            </div>
            <div class="card-body">
                <div>
                    <?php if(isset($_SESSION['message'])){ echo $_SESSION['message']; unset($_SESSION['message']); } ?>
            </div>
            <form action="../phpfunctions/login.php" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control userid" placeholder="* Login ID" required />
                    <div class="invalid-feedback">
                        Please enter Login ID
                    </div>
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control pass" placeholder="* Password" required />
                    <div class="invalid-feedback">
                        Please enter password
                    </div>
                </div>
                <!-- <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1">
                  <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                <button type="submit" name="loginClientByForm" class="btn btn-primary col-12" value="1">Submit</button>
            </form>

            <div class="mb-3 pt-3" style="text-align:center;font-size:smaller">
                <p>Don't have an account? Click <a href="registration.php">here</a></p>
                <p>Forget Password? Click <a href="forgetpassword.php">here</a></p>
            </div>
        </div>
        <p class="text-muted" style="text-align:center;font-size:small">Copyright @ Jsoft Solution Sdn Bhd</p>
    </div>

    <div class="right">
        <h1 class="right" style="font-style:oblique;font-size:60px">
            <b>SIMPLIFY</b> YOUR<br>BUSINESS <b>PROCESS</b>
        </h1>
    </div>
</div>
</div>
</body>
</html>