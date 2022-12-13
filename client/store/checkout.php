<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();


}?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Checkout</title>
    <?php require("./assets/css.php"); ?>
  <link rel="stylesheet" href="./css/checkout.css">
    <style>
        textarea#notes {
            background-color: #f2f2f4;
            border-radius: 10px;
            width: 100%;
            margin-top: 20px;
            padding: 10px;
        }
        </style>
</head>

<body>
<?php
// header menu
require("./assets/top-menu.php");
// side menu
require("./assets/side-menu.php");
// cart side menu
require("./assets/cart-side-menu.php");
?>
  </aside>
  <section class="single-banner" style="background: url(./img/banner.jpg) no-repeat center; background-size: cover;">
    <div class="container">
      <h2>checkout</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/index.html">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">checkout</li>
      </ol>
    </div>
  </section>
  <section class="checkout-part">
    <div class="container">

      <div class="row">
          <?php
            if(isset($_SESSION['message'])) {
              echo '<div class="col-12">'. $_SESSION['message'] . '</div>';
              // unset($_SESSION['message']);
          } ?>
        <div class="col-lg-12">
          <div class="account-card">
            <div class="account-title">
              <h4>Your order</h4>
            </div>
            <div class="account-content">
              <div class="table-scroll">
                  <!-- cart item table -->
              </div>
              <div class="checkout-charge">
                  <!-- cart total -->
              </div>
            </div>
          </div>
        </div>
          <?php
      /*  $delivery = '<div class="col-lg-12">
          <div class="account-card">
            <div class="account-title">
              <h4>delivery Schedule</h4>
            </div>
            <div class="account-content">
              <div class="row">
                <div class="col-md-6 col-lg-4 alert fade show">
                  <div class="profile-card address active">
                    <h5>Express</h5>
                    <p>90 min express delivery</p>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 alert fade show">
                  <div class="profile-card address">
                    <h5>8am-10pm</h5>
                    <p>8.00 AM - 10.00 PM</p>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 alert fade show">
                  <div class="profile-card address mb-0">
                    <h5>Next day</h5>
                    <p>Next day or Tomorrow</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>'; */
        ?>
          <div class="col-lg-12">
              <div class="account-card">
                  <div class="account-title">
                      <h4>contact number</h4><a href="./profile.php">add contact</a>
                  </div>
                  <div class="account-content">
                      <div class="row">
                          <?php $contact= contactCartList();
                          echo $contact;
                          ?>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-lg-12">
              <div class="account-card">
                  <div class="account-title">
                      <h4>address for invoice</h4><a href="./profile.php">add address</a>
                  </div>
                  <div class="account-content">
                      <div class="row">
                          <?php $address= addressCartList();
                          echo $address; ?>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-lg-12">
              <div class="account-card mb-0">
                  <div class="account-title">
                      <h4>Book Order</h4><?php //'<button data-toggle="modal" data-target="#payment-add">add card</button>'; ?>
                  </div>
                  <form action="../../phpfunctions/store.php" method="post" >

                  <div class="account-content">
                      <label>Notes if any</label>
                      <div class="notes"><textarea name="notes" id="notes" ></textarea></div>
                      <div class="checkout-check"><input type="checkbox"<?php if((isset($_SESSION['src'])&&count($_SESSION['src'])<=0)||!isset($_SESSION['src'])){ echo "disabled";} ?>><label>By making this purchase you agree to our <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/TERMS%20AND%20CONDITIONS.pdf" target="_blank">terms and conditions</a>.</label></div>
                      <div class="checkout-proced"><button type="button" class="btn btn-inline checkout" name="checkoutCart" value="true" data-toggle="modal" data-target="#checkout-info">Proceed</button></div>
                  </div>
          </form>
              </div>
          </div>

      </div>
    </div>
  </section>

  <footer class="footer-part">
      <?php require("./assets/footer-content.php"); ?>
  </footer>
<div class="modal fade" id="checkout-info" class="contact-edit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"><button class="modal-close" data-dismiss="modal"><i class="icofont-close"></i></button>
            <form class="modal-form" action="" method="post">
                <div class="form-title">
                    <h3>Checkout Alert</h3>
                </div>
                <div class="form-group" style="font-size: large;text-align: center; ">Agree with <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/TERMS%20AND%20CONDITIONS.pdf" target="_blank">all terms and conditions</a> by check box check

                </div>
                <button type="button" class="btn btn-inline checkout" data-dismiss="modal" style="width: 100%">CLOSE</button>
            </form>
        </div>
    </div>
</div>
<?php require("./assets/js.php"); ?>
<script>
    $(document).ready(function () {

       // $('.checkout').attr("disabled","true");
        $('.checkout').attr("type","button");
        $('.checkout-check input[type="checkbox"]').on("click",function () {

            let temp;
            temp = $('.checkout-check input[type="checkbox"]').attr("checked");
            if(temp=="checked"){
          //  $('.checkout').removeAttr("disabled");
            $('.checkout').removeAttr("data-target");
            $('.checkout').attr("type","submit");

            }else{
              //  $('.checkout').attr("disabled","true");
                $('.checkout').attr("data-target","#checkout-info");
                $('.checkout').attr("type","button");

            }
        });
        viewCheckout();
        $('#notes').keyup(function() {
            if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
            }
        });
    });
function viewCheckout(){
    $.ajax({
        type:'post',
        url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
        data:{
            tableCart:true
        },
        success:function(res) {
            console.log(res);
            if (res == 0) {
                let cart = "<div class='my-5'><center>Your cart is empty</center></div>";
                $(".table-scroll").html(cart);
                $(".checkout-charge").html("");
            } else {
                let amount=0;
                $.ajax({
                    type:'post',
                    url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
                    data:{
                        cartTotal:true
                    },
                    success:function(price) {
                        amount=price;
                        let total='<ul class="mt-3"><li><span>Total<small>(Incl. VAT)</small></span><span>RM '+amount+'</span></li></ul>';
                        $(".checkout-charge").html(total);
                    }
                });

                $(".table-scroll").html(res);

            }
        }
    });
}

        function edit(tag,num){
        hag="#"+tag;
        let idtag=hag+num;
        let title=$(idtag+" #title"+num).val();
        let value=$(idtag+" #value"+num).val();
        let id=$(idtag+" #id"+num).val();
        title=String(title);
        let code=""
        if(title.toLowerCase()==="primary") {
        code = '<option value="primary" selected>primary</option>'+
        '<option value = "secondary"> secondary </option>';
    }else if(title.toLowerCase()==="secondary") {
        code = '<option value="primary">primary</option>'+
        '<option value = "secondary" selected > secondary </option>';
    }else if(title.toLowerCase()==="home") {
        code = '<option value="home" selected >home</option>'+
        '<option value = "office"> office </option>'+
        '<option value="business">business</option>'+
        '<option value="academy">academy</option>'+
        '<option value="others">others</option>';
    }else if(title.toLowerCase()==="office") {
        code = '<option value="home">home</option>'+
        '<option value = "office" selected > office </option>'+
        '<option value="business">business</option>'+
        '<option value="academy">academy</option>'+
        '<option value="others">others</option>';
    }else if(title.toLowerCase()==="business") {
        code = '<option value="home">home</option>'+
        '<option value = "office"> office </option>'+
        '<option value="business" selected >business</option>'+
        '<option value="academy">academy</option>'+
        '<option value="others">others</option>';
    }else if(title.toLowerCase()==="academy") {
        code = '<option value="home" >home</option>'+
        '<option value = "office"> office </option>'+
        '<option value="business">business</option>'+
        '<option value="academy" selected >academy</option>'+
        '<option value="others">others</option>';
    }else if(title.toLowerCase()==="others") {
        code = '<option value="home">home</option>'+
        '<option value = "office"> office </option>'+
        '<option value="business">business</option>'+
        '<option value="academy">academy</option>'+
        '<option value="others" selected >others</option>';
    }
        document.getElementById(tag+"_title").innerHTML=code;
        document.getElementById(tag+"_value").value=value;
        document.getElementById(tag+"_id").value=id;
    }
    function active(tag,id){
        $.ajax({
            type:'post',
            url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data:{
                makeActive:true,
                id:id,
                tag:tag
            },
            success:function (res) {
                if(res){ alert("select "+tag+" set default");}
                else{alert("select "+tag+" not set default");}
            }
        });
    }

</script>
</body>

</html>