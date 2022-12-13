<?php $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini"); ?>
<aside class="sidebar-check">
    <div class="check-container">
      <div class="check-header"><button class="check-close"><i class="icofont-close"></i></button>
        <div class="cart-total"><i class="icofont-basket"></i>
          <h5><span>total item</span>(<span class="itemCount"><?php if(isset($_SESSION['title'])){echo count($_SESSION['title']);}else{echo "0";} ?></span>)</h5>
        </div>
      </div>
      <ul class="cart-list " id="viewCart">

      </ul>
      <div class="check-footer">
          <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/client/store/checkout.php" class="check-btn"><span class="check-title">checkout</span><span class="check-price cartTotal">RM <?php if(isset($_SESSION['cartTotal'])){ echo number_format($_SESSION['cartTotal'], 2);} else {echo '00.00';}?></span></a>
      </div>
    </div>
  </aside>