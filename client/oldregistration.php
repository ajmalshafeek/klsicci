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
    <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Registration</title>

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
            min-height: 100%;
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
            margin-left: 40px;
        }
        .card{
            height: fit-content !important;
        }


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

        <div class="card left" style="width: 35rem;height: 30rem;">
            <div class="card-header">
                <h3 class="card-title">New Account</h3>
            </div>
            <div class="card-body">
                <div><?php if(isset($_SESSION['message'])){ echo $_SESSION['message']; unset($_SESSION['message']); } ?></div>
                <form action="../phpfunctions/registration.php" method="post">
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control name" placeholder="* Full Name" required />
                        <div class="invalid-feedback">
                            Please enter name
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="company" class="form-control company" placeholder="* Company Name" required />
                        <div class="invalid-feedback">
                            Please enter company name
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="register" class="form-control register" placeholder="* Registration No" required />
                        <div class="invalid-feedback">
                            Please enter registrartion no
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="business" class="form-control business" placeholder="* business type" required />
                        <div class="invalid-feedback">
                            Please enter business type
                        </div>
                    </div>
                    <div class="mb-3">
                        <small>Date of Incorporation</small>
                        <input type="date" name="incorp" class="form-control incorp" placeholder="* incorporated date" required />
                        <div class="invalid-feedback">
                            Please enter incorp
                        </div>
                    </div>
                    <div class="mb-3">
                        <small>Financial Year</small>
                        <input type="month" name="financialYear" class="form-control financialYear" placeholder="* Financial month end" required />
                        <div class="invalid-feedback">
                            Please enter financial year
                        </div></div>
                    <div class="mb-3">
                        <input type="text" name="address1" class="form-control address1" placeholder="* Address 1" required />
                        <div class="invalid-feedback">
                            Please enter address1
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="address2" class="form-control address2" placeholder="* Address 2" required />
                        <div class="invalid-feedback">
                            Please enter address2
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="city" class="form-control city" placeholder="* city" required />
                        <div class="invalid-feedback">
                            Please enter city
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="postalCode" class="form-control postalCode" placeholder="* postal code" required />
                        <div class="invalid-feedback">
                            Please enter postal code
                        </div>
                    </div>
                    <div class="mb-3">
                        <select name="state"  id="state" class="form-control" >
                            <option value="Johor">Johor</option>
                            <option value="Kedah">Kedah</option>
                            <option value="Kelantan">Kelantan</option>
                            <option value="Kuala Lumpur">Kuala Lumpur</option>
                            <option value="Labuan">Labuan</option>
                            <option value="Malacca">Malacca</option>
                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                            <option value="Pahang">Pahang</option>
                            <option value="Perak">Perak</option>
                            <option value="Perlis">Perlis</option>
                            <option value="Penang">Penang</option>
                            <option value="Sabah">Sabah</option>
                            <option value="Sarawak">Sarawak</option>
                            <option value="Selangor">Selangor</option>
                            <option value="Terengganu">Terengganu</option>
                        </select>
                        <div class="invalid-feedback">
                            Please enter state
                        </div>
                    </div>
                    <div class="mb-3">
                    <input type="text" name="country" class="form-control country" placeholder="* country" required />
                        <div class="invalid-feedback">
                            Please enter country
                        </div>
                    </div>



                    <div class="mb-3">
                        <input type="email" name="email" class="form-control email" placeholder="* Email" required />
                        <div class="invalid-feedback">
                            Please enter email
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email1" class="form-control email1" placeholder="* Verify Email" required />
                        <div class="invalid-feedback">
                            Please enter verify email
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="clientFaxNo" class="form-control clientFaxNo" placeholder="* Client Fax No" required />
                        <div class="invalid-feedback">
                            Please enter client Fax No
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="clientContactNo" class="form-control clientContactNo" placeholder="* Client Contact No" required />
                        <div class="invalid-feedback">
                            Please enter client Fax No
                        </div>
                    </div>
                    <!-- <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div> -->
                    <button type="submit"  name="regClientByForm" class="btn btn-primary col-12" value="1">Registration Request</button>
                </form>

                <div class="mb-3 pt-3" style="text-align:center;font-size:smaller">
                    <p>Already have an account? <a href="./">Login here</a></p>
                </div>
            </div>
            <p class="text-muted" style="text-align:center;font-size:small"><b>Copyright @ Jsoft Solution Sdn Bhd</p>
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