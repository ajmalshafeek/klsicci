<?php
// Registration from

$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

session_name($config['sessionName']);
session_start();
header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/registration-for-membership.php');
if(isset($_SESSION['userid']))
{
    header('Location:https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php');

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>

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
            background: url(bg-1.jpg);
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
            background-color: #D90000;
            width: 47px;
            margin-top: -7px;
        }
        .colorb{background-color: #D90000;font-weight: 700 !important;}
        .bar{background-color: #B9C0C7;
            height: 2px;
            width: 100px;
            margin-left: auto;
            margin-right: auto;
            margin-top: -20px;}
        .btn-primary:hover {
            color: #fff;
            background-color: #d90000;
            border-color: #cc0000;
        }
        .btn-primary {
            color: #fff;
            background-color: #ea2b2b;
            border-color: #ea2b2b;
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
        .py-5{overflow: auto;background-color: #D90000;
            overflow-y: scroll; /* Add the ability to scroll */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .py-5::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .py-5 {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
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
        @media only screen and (max-width: 599px) {
        .py-5{overflow: unset;
                overflow-y: unset; /* Add the ability to scroll */
            }
        }
    </script>
</head>
<body>

<div class="container-fluid" style="background-color: #ea2b2b">
    <div class="center row">
        <div class="col-sm-6 full-height center">
            <h1 class="" style="font-style:oblique;font-size:60px">
                <b>SIMPLIFY</b> YOUR<br>BUSINESS <b>PROCESS</b>
            </h1>
        </div>
        <div class="col-sm-6 py-5" style="height: 100vh" >
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registration</h3>
                <div class="hr"></div>
            </div>
            <div class="card-body">
                <div>
                </div>
                <form role="form"  id="formLogin" action="../phpfunctions/registration.php" method="POST" class="needs-validation" >
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control name" placeholder="* Full Name" required="required" />
                        <div class="invalid-feedback">
                            Please enter name
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="company" class="form-control company" placeholder="* Company Name" required="required" />
                        <div class="invalid-feedback">
                            Please enter company name
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="register" class="form-control register" placeholder="* Registration No" required="required" />
                        <div class="invalid-feedback">
                            Please enter registrartion no
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="business" class="form-control business" placeholder="* business type" required="required" />
                        <div class="invalid-feedback">
                            Please enter business type
                        </div>
                    </div>
                    <div class="mb-3">
                        <small>Date of Incorporation</small>
                        <input type="date" name="incorp" class="form-control incorp" placeholder="* incorporated date" required="required" />
                        <div class="invalid-feedback">
                            Please enter incorp
                        </div>
                    </div>
                    <div class="mb-3">
                        <small>Financial Year</small>
                        <input type="month" name="financialYear" class="form-control financialYear" placeholder="* Financial month end" required="required" />
                        <div class="invalid-feedback">
                            Please enter financial year
                        </div></div>
                    <div class="mb-3">
                        <input type="text" name="address1" class="form-control address1" placeholder="* Address 1" required="required" />
                        <div class="invalid-feedback">
                            Please enter address1
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="address2" class="form-control address2" placeholder="* Address 2" required="required" />
                        <div class="invalid-feedback">
                            Please enter address2
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="city" class="form-control city" placeholder="* city" required="required" />
                        <div class="invalid-feedback">
                            Please enter city
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="postalCode" class="form-control postalCode" placeholder="* postal code" required="required" />
                        <div class="invalid-feedback">
                            Please enter postal code
                        </div>
                    </div>
                    <div class="mb-3">
                        <select name="state"  id="state" class="form-control" required="required" >
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
                        <input type="text" name="country" class="form-control country" placeholder="* country" required="required" />
                        <div class="invalid-feedback">
                            Please enter country
                        </div>
                    </div>



                    <div class="mb-3">
                        <input type="email" name="email" class="form-control email" placeholder="* Email" required="required" />
                        <div class="invalid-feedback">
                            Please enter email
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email1" class="form-control email1" placeholder="* Verify Email" required="required" />
                        <div class="invalid-feedback">
                            Please enter verify email
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="clientFaxNo" class="form-control clientFaxNo" placeholder="* Client Fax No" required="required" />
                        <div class="invalid-feedback">
                            Please enter client Fax No
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="clientContactNo" class="form-control clientContactNo" placeholder="* Client Contact No" required="required" />
                        <div class="invalid-feedback">
                            Please enter client Fax No
                        </div>
                    </div>
                    <!-- <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div> -->
                    <button type="submit"  name="regClientByForm" class="btn btn-primary col-12 colorb" value="1">Registration Request</button>
                </form>
                <div class="mb-1 pt-3 ligthgrey" style="text-align:center;font-size:smaller">
                    <p>Already have an account? <a href="./">Login here</a></p>
                </div>
            </div>
            <div>
            <p class="text-muted mb-4 ligthgrey social" style="text-align:center;font-size:11px;margin-bottom:10px;font-weight: 700;">Find us at</p>
                <div class="bar"></div>
                <div class="social-icon"><img src="../resources/app/fb.png" /><img src="../resources/app/insta.png" /></div>
            <p class="text-muted mb-4 ligthgrey" style="text-align:center;font-size:11px;margin-bottom:10px;font-weight: 800;">Powered by Jsoft Solution</p>
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

                    <h4 class="modal-title"><?php if(isset($_SESSION["msgTitle"])){ echo $_SESSION["msgTitle"];unset($_SESSION["msgTitle"]);} else{ echo "Error Message"; } ?>
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