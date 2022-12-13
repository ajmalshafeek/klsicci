<?php $config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
?>
<nav class="sidebar-nav">
    <div class="nav-container">
      <div class="nav-header"><button class="nav-close"><i class="icofont-close"></i></button><a class="nav-logo" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/store/store.php"; ?>"><img src="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png"; ?>" alt="logo"></a>
        <ul class="nav nav-tabs">
          <li><a href="#menu-list" class="nav-link active" data-toggle="tab">Menu</a></li>
          <li><a href="#cate-list" class="nav-link" data-toggle="tab">ISTORE</a></li>
        </ul>
      </div>
      <div class="nav-content tab-content">
        <div class="tab-pane active" id="menu-list">
          <div class="nav-profile"><a href="/profile.html"><img src="../store/img/user.png" alt="user"></a>
            <h4><a href="/index.html#"><?php echo $_SESSION['name']; ?></a></h4>
          </div>
          <ul class="nav-list">
        <li><a class="nav-link" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/files/myFiles.php"; ?>"><i class="icofont-ui-home"></i><span>(My Drive)My Files</span></a></li>
              <?php if($_SESSION['orgType']==3){ ?>
              <li><a class="nav-link" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/pdfSign/pdfList.php"; ?>"><i class="icofont-files-stack"></i><span>PDF List</span></a></li>
              <?php } ?>
        <li><a class="nav-link" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/store/store.php"; ?>"><i class="icofont-ui-check"></i><span>Store</span></a></li>
        <li><a class="nav-link" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/store/orderlist.php"; ?>"><i class="icofont-basket"></i><span>your order</span></a></li>
        <li><a class="nav-link" href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/contact-us.php"; ?>"><i class="ui-contact-list"></i><span>Contact Us</span></a></li>
        <li><a class="nav-link" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/logout.php?logout=true"; ?>"><i class="icofont-ui-lock"></i><span>logout</span></a></li>
          </ul>
        </div>
        <div class="tab-pane" id="cate-list">
<?php echo categorySidePopMenu(); ?>
        </div>
        <div class="nav-footer">
          <p>© Copyright by <a href="#">Jsoft Solution</a></p>
        </div>
      </div>
    </div>
  </nav>