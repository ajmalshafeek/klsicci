<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    if(!isset($_GET['pid'])){
          header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }
}?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Product</title>
  <?php require("./assets/css.php"); ?>
  <link rel="stylesheet" href="./css/product-single.css">
  <style>
      .thumb-slider li img {
          width: 90px;
      }

      .preview-slider li img {
          width: 430px;
      }

      .slick-slide {
          height: auto !important;
      }

      .slick-initialized .slick-slide {
          height: fit-content;
      }

      .product-filter-short::before {
          right: 33px;
      }

      .quick-details-cart {
          color: var(--white);
          background: var(--primary);
          border-radius: 5px;
          padding: 7px;
          text-transform: uppercase;
      }

      .viewQuantity {
          margin-bottom: 20px;
      }

      .viewQuantity .details-minus,
      .viewQuantity .details-plus {
          padding: 10px;
          background-color: #f2f2f2;
          border-radius: 5px;
          margin: 5px;
      }

      .viewQuantity input {
          padding: 5px;
          padding-left: 15px;
          padding-right: 15px;
          background-color: var(--primary);
          border-radius: 5px;
          margin: 5px;
          width: 70px;
          color: var(--white)
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

if(isset($_GET['pid'])){
    $pid=$_GET['pid'];
    $data=getProductDetails($pid);
}
?>
  <section class="single-banner mb-0" style="background: url(./img/banner.jpg) no-repeat center; background-size: cover;">
    <div class="container">
      <h2>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - product single</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./store.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">product single</li>
      </ol>
    </div>
  </section>
  <section class="product-single">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="product-gallery">
            <ul class="preview-slider">
                <?php if(!empty($data['img1'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img1'].'" alt="product"></li>';
                } if(!empty($data['img2'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img2'].'" alt="product"></li>';
                 } if(!empty($data['img3'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img3'].'" alt="product"></li>';
                 } ?>
            </ul>
            <ul class="thumb-slider">
                <?php if(!empty($data['img1'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img1'].'" alt="product"></li>';
                } if(!empty($data['img2'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img2'].'" alt="product"></li>';
                } if(!empty($data['img3'])){
                    echo '<li><img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img3'].'" alt="product"></li>';
                } ?>
            </ul>
          </div>
        </div>
        <div class="col-md-6 col-lg-6">
          <div class="product-details">
            <h3 class="details-name"><?php echo $data['title']; ?></h3>
              <?php if($_SESSION['orgType']!=3){ ?>
              <div class="details-meta" >SKU:<span class="details-sku" id="viewSKU"><?php echo $data['sku']; ?></span>BRAND:<a
                          class="details-brand" id="viewBrand"
                          href="#" ><?php echo $data['brand']; ?></a>
              </div>
              <?php } ?>
            <h3 class="details-price"><span><?php echo $data['price']==0?'Free':'RM '.$data['price'];  ?></span></h3>
            <p class="details-desc"><?php echo $data['description']; ?></p>
              <?php if($_SESSION['orgType']!=3){ ?>
              <div class="details-group"><label class="details-group-title">Model:</label>
                  <ul class="details-tag-list" id="viewTag">
                      <li><a
                                  href="#" id=""><?php echo $data['model']; ?></a>
                      </li>
                  </ul>
              </div>
              <?php } ?>
              <div class="details-group"><label class="details-group-title">Category:</label>
                  <ul class="details-tag-list" id="viewCategory">
                      <li><a
                                  href="#" id=""><?php echo $data['producttype']; ?></a>
                      </li>
                  </ul>
              </div>
            <hr class="details-devider">
              <div id="item">
                  <div class="viewQuantity">
                      <button class="details-minus" title="Quantity Minus"><i class="icofont-minus"></i></button>
                      <input class="details-input" id="item_quantity"  title="Quantity Number" type="text" name="quantity" value="1" min="1" max="<?php echo $data['quantity'] ?>">
                      <button class="details-plus" title="Quantity Plus"><i class="icofont-plus"></i></button>
                      <input type="hidden" id="item_id" value="<?php echo $data['id']; ?>" />
                      <input type="hidden" id="item_title" value="<?php echo $data['title']; ?>" />
                      <input type="hidden" id="item_price" value="<?php echo $data['price']; ?>" />
                      <input type="hidden" id="item_img" value="<?php echo "https://".$_SERVER['HTTP_HOST'] . $config['appRoot'].$data['img1']; ?>" />
                  </div>
                  <div class="details-action-group">
                      <button class="quick-details-cart" onclick="cart('item')" title="Add Your Cartlist">
                          <i class="icofont-cart"></i><span>add to cart</span></button>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
$products=storeItemsPaging($data['producttype'], null, null, 1, 12);
if(!empty($products)){
?>
  <section class="related-part">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="related-title">
            <h3>Related Product</h3>

          </div>
        </div>
      </div>
      <div class="row">
          <?php foreach ($products as $item){ ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3">
          <div class="product-card">
            <figure class="product-media">
              <a class="product-image" href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']. "/client/store/product.php?pid=".$item['id']; ?>"><img src="<?php echo "https://".$_SERVER['HTTP_HOST'] . $config['appRoot'].$item['img1']; ?>" alt="product"></a>
            </figure>
            <div class="product-content">
              <h5 class="product-price"><span><?php echo $item['price']==0?'Free':'RM '.$item['price'];  ?> </span></h5>
              <h5 class="product-name"><a href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']. "/client/store/product.php?pid=". $item['id']; ?>"><?php echo $item['title'] ?></a></h5>
            </div>
          </div>
        </div>
        <?php }  ?>
      </div>
    </div>
  </section>
<?php } ?>
  <div class="mobile-check"><button class="check-btn"><span class="check-item"><i class="icofont-basket"></i><span>0 items</span></span><span class="check-price">$00.00</span></button></div>
  <footer class="footer-part">
      <?php require("./assets/footer-content.php"); ?>
  </footer>
<?php require("./assets/js.php");
  /*<script src="./img/jquery-1.12.4.min.js"></script>
  <script src="./img/popper.min.js"></script>
  <script src="./img/bootstrap.min.js"></script>
  <script src="./img/header-part.js"></script>
  <script src="./img/product-part.js"></script>
  <script src="./img/product-view.js"></script>
  <script src="./img/select-option.js"></script>
  <script src="./img/dropdown.js"></script>
  <script src="./img/slick.min.js"></script>
  <script src="./img/slick.js"></script>
  <script src="./img/main.js"></script>
  */
  ?>
<script>
$(document).ready(function() {
$('.preview-slider').slick({
lazyLoad: 'ondemand',
slidesToShow: 1,
slidesToScroll: 1,
arrows: true,
nextArrow: '<i class="icofont-arrow-right dandik slick-arrow" style="display: block;"></i>',
prevArrow: '<i class="icofont-arrow-left bamdik slick-arrow" style="display: block;"></i>',
fade: true,
asNavFor: '.thumb-slider'
});
$('.thumb-slider').slick({
lazyLoad: 'ondemand',
slidesToShow: 3,
slidesToScroll: 1,
asNavFor: '.product-gallery',
dots: false,
centerMode: true,
focusOnSelect: true,
arrows:false
});
});
</script>
</body>

</html>