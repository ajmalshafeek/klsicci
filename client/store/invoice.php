<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store.php");
    if(!isset($_GET['oid'])){
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }
    $oid=$_GET['oid'];
    $detail= getOrderDetailById($oid);

    if(sizeof($detail)==0){
        echo sizeof($detail);
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Invoice</title>
    <?php require("./assets/css.php"); ?>
  <link rel="stylesheet" href="./css/invoice.css">
    <style>
        .account-card{padding: 0px;}
        .account-title{padding: 18px;border-radius: 10px 10px 0 0;}
        .account-content{padding: 18px;border-radius: 0 0 10px 10px;}
        .rorder .account-title{background-color: #6dff90;}
        .dorder .account-title{background-color: #6be3fc;}
        .aorder .account-title{background-color: #fd7291;}
        </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>-->
</head>

<body id="">
<?php
// header menu
require("./assets/top-menu.php");
// side menu
require("./assets/side-menu.php");
// cart side menu
require("./assets/cart-side-menu.php");


?>
  <section class="single-banner" style="background: url(./img/banner.jpg) no-repeat center; background-size: cover;">
    <div class="container">
      <h2>order invoice</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./store.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">invoice</li>
      </ol>
    </div>
  </section>
  <section class="section profile-part" id="invoice">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="alert-info">
            <p>Thank you! We have recieved your order.</p>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="account-card rorder">
            <div class="account-title">
              <h4>order recieved</h4>
            </div>
            <div class="account-content">
              <div class="invoice-recieved">
                <h5>order number <span><?php echo $detail['id']; ?></span></h5>
                <h5>order date <span>
                        <?php
                        $date = date_create($detail['orderdate']);
                        echo date_format($date, 'l d m Y');
                        ?></span></h5>
                <h5>total amount <span>RM <?php echo $detail['grandtotal']?></span></h5>
                <h5>status <span>Order received</span></h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="account-card dorder">
            <div class="account-title">
              <h4>Order Details</h4>
            </div>
            <div class="account-content">
              <ul class="invoice-details">
                <li>
                  <h5>Order Time</h5>
                  <p><?php echo date_format($date, 'h:ia d-m-Y'); ?></p>
                </li>
                <li>
                  <h5>Delivery Location</h5>
                  <p><?php echo $detail['address']?></p>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="account-card aorder">
            <div class="account-title">
              <h4>Amount Details</h4>
            </div>
            <div class="account-content">
              <ul class="invoice-details">
                <li>
                  <h5>Total item</h5>
                  <p><?php echo getOrderItemCount($oid); ?></p>
                </li>
                <li>
                  <h5>Total<small>(Incl. VAT)</small></h5>
                  <p>RM <?php echo $detail['grandtotal']?></p>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="table-scroll">
              <?php $itemlist=getOrderItemDetails($oid); ?>
            <table class="table-list">
              <thead>
                <tr>
                  <th scope="col">SL No</th>
                  <th scope="col">Product</th>
                  <th scope="col">Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Total</th>
                </tr>
              </thead>
              <tbody>

              <?php
              $count=1;
              foreach($itemlist as $oitem){ ?>
                <tr>
                  <td>
                    <h5><?php echo $count; ?></h5>
                  </td>
                  <td><img src="<?php echo $oitem['img1'] ?>" alt="product"></td>
                  <td>
                    <h5><?php echo $oitem['title']; ?></h5>
                  </td>
                  <td>
                    <h5><?php echo $oitem['price']?></h5>
                  </td>
                  <td>
                    <h5><?php echo $oitem['quantity']?></h5>
                  </td>
                  <td>
                    <h5>RM <?php echo $oitem['total']?></h5>
                  </td>
                </tr>
            <?php $count++; } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 text-center mt-5">
            <?php //<a class="btn btn-inline" href="/invoice.html#"><i class="icofont-download"></i><span>download invoice</span></a> ?>
          <div class="back-home"><a href="store.php">Back to Home</a> | <a href="print.php?oid=<?php echo $_GET['oid']; ?>" id="print">Print Invoice</a></div>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer-part">
      <?php require("./assets/footer-content.php"); ?>
  </footer>
<?php require("./assets/js.php"); ?>
<script>
    function printScreen() {
       /* var doc = new jsPDF();
        doc.fromHTML(document.getElementById("invoice"), // page element which you want to print as PDF
            15,
            15,
            {
                'width': 292
            },
            function(a)
            {
                doc.save("htmlinvoice.pdf");
            }); */
      //  $('.header-fixed').css("position","relative !important");
      //  window.print();
       /* var printwin = window.open("");
        printwin.document.write(document.getElementById("invoice").innerHTML);
        printwin.print();

        */
     //   $('.header-fixed').css("position","sticky");
    }
</script>
</body>
</html>