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
    <title>Page Title</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .full-height {
            height: 100%;
            background: url(./client/bg-1.jpg);
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

<div class="full-height">
    <div class="center">

        <div class="card left" style="width: 18rem;height: 30rem;">
            <div class="card-header">
                <h3 class="card-title">Login</h3>
            </div>
            <div class="card-body">
                <div>

                </div>
                <form role="form"  id="formLogin" action="phpfunctions/login.php" method="POST" class="needs-validation" novalidate>
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
                    <button type="submit" name="login" class="btn btn-primary col-12" value="1">Submit</button>
                </form>
                <div class="mb-1 pt-3" style="text-align:center;font-size:smaller">
                    <p>Client Login? Click <a href="./client/">here</a></p>
                </div>
                <div class="mb-1" style="text-align:center;font-size:smaller">
                    <p>Client Registration? Click <a href="./client/registration.php">here</a></p>
                </div>
            </div>
            <p class="text-muted" style="text-align:center;font-size:small">Powered by Jsoft Solution</p>
        </div>

        <div class="right">
            <h1 class="right" style="font-style:oblique;font-size:60px">
                <b>SIMPLIFY</b> YOUR<br>BUSINESS <b>PROCESS</b>
            </h1>
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
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
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