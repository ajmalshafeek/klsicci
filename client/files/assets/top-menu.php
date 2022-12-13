<?php $config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
require("./assets/category-data.php");
?>
<header class="header-part header-fixed">
    <div class="container">
      <div class="header-left">
        <div class="header-icon-group"><button class="icon-nav"><i class="icofont-align-left"></i></button><a class="header-logo" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/client/store/store.php"; ?>"><img src="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png"; ?>" alt="logo" height="70px" /></a><button class="icon-cross"><i class="icofont-close"></i></button></div>
          <div class="select-menu header-user"><img class="img" src="../store/img/<?php if($_SESSION['userid']==360){ echo 'uicon.png'; }elseif($_SESSION['userid']==354){echo 'usericon.jpeg';} else{echo 'user.png';} ?>"" alt="user"><span class="text"><?php echo $_SESSION['name']; ?></span></div>
        </div>
    <form class="header-middle"> File Manager<!--
        <div class="select-option"><i class="icon icofont-grocery"></i><span class="text"><?php if(isset($_GET['cat'])){ echo $_GET['cat']; }else{ ?>All <?php } ?></span>
          <?php   echo searchCategoryTopMnu(); ?>
        </div><input type="text" name="title" placeholder="Search anything...">
          <input type="hidden" name="cat" value="<?php if(isset($_GET['cat'])){ echo $_GET['cat']; }else{ ?>All<?php } ?>">
          <button type="submit">
              <i class="icofont-ui-search"></i></button>
       --></form>
      <div class="header-right">
<?php /*        <div class="select-menu header-user"><img class="img" src="../store/img/<?php if($_SESSION['userid']==360){ echo 'uicon.png'; }elseif($_SESSION['userid']==354){echo 'usericon.jpeg';} else{echo 'user.png';} ?>"" alt="user"><span class="text"><?php echo $_SESSION['name']; ?></span></div>
<?php /*          <div class="header-icon-group"><button class="icon-check" onclick="show_cart()"><i class="icofont-shopping-cart"></i><span class="cartTotal">RM <?php if(isset($_SESSION['cartTotal'])){ echo number_format($_SESSION['cartTotal'], 2);} else {echo '00.00';}?></span><sup class="itemCount"><?php if(isset($_SESSION['title'])){echo count($_SESSION['title']); } else { echo "0";} ?></sup></button></div> */ ?>
      </div>
    </div>
  </header>