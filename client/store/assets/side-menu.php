<?php $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");
?>
<nav class="sidebar-nav">
    <div class="nav-container">
        <div class="nav-header">
            <button class="nav-close"><i class="icofont-close"></i></button>
            <a class="nav-logo"
               href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php"; ?>"><img
                        src="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/resources/" . $_SESSION['orgLogo'] . ".png"; ?>"
                        alt="logo"></a>
            <ul class="nav nav-tabs">
                <li><a href="#menu-list" class="nav-link active" data-toggle="tab">Menu</a></li>
                <?php if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
                <li><a href="#cate-list" class="nav-link" data-toggle="tab">ISTORE</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="nav-content tab-content">
            <div class="tab-pane active" id="menu-list">
                <div class="nav-profile"><a href="/profile.html"><img src="./img/<?php if($_SESSION['userid']==360){ echo 'uicon.png'; }elseif($_SESSION['userid']==354){echo 'usericon.jpeg';} else{echo 'user.png';} ?>" alt="user"></a>
                    <h4><a href="/index.html#"><?php echo $_SESSION['name']; ?></a></h4>
                </div>
                <ul class="nav-list">
                    <?php //if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php"; ?>"><i
                                    class="icofont-ui-home"></i><span>home</span></a></li>
                        <?php // } ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/profile.php"; ?>"><i
                                    class="icofont-ui-user"></i><span>my profile</span></a></li>
                    <?php // if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false||$_SESSION['memberRegOver']==true){ ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/renewal.php"; ?>"><i
                                    class="icofont-spinner-alt-3"></i><span>renewal </span></a></li>
                    <?php // } ?>
                   <?php // if($_SESSION['memberRegPending']){ ?><li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/upgrade.php"; ?>"><i
                                    class="icofont-swoosh-up"></i><span>Membership Plan </span></a></li> <?php //} ?>
                    <?php /* <li>
                        <a class="nav-link"
                           data-toggle="collapse" href="#professionalDevelopment" role="button" aria-expanded="false" aria-controls="professionalDevelopment"
                        >
                            <i class="icofont-user-suited"></i>
                            <span>Professional Development</span></a>
                        <!-- Collapsed content -->
                        <ul id="professionalDevelopment"
                            class="collapse list-group list-group-flush" >
                            <li class="list-group-item nav-link py-2">
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/pd-enquiry.php"; ?>" class="text-reset">Enquiries</a>
                            </li>
                            <li class="list-group-item nav-link py-2">
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php?cat=Professional%20Development#smenu"; ?>" class="text-reset">Registration</a>
                            </li>
                        </ul>
                    </li>

                    <li><a class="nav-link" href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/research-grant.php"; ?>"><i
                                    class="icofont-microscope-alt"></i><span>Research Grant</span></a></li>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/examination.php"; ?>"><i
                                    class="icofont-hat-alt"></i><span>examination</span></a></li>
                    <?php if($_SESSION['userid']==354){ ?>
                    <li><a class="nav-link" href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/epf-withdrawal.php"; ?>"><i
                                    class="icofont-pen-alt-4"></i><span>EPF Withdrawal</span></a></li>
                    <?php } ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/events.php"; ?>"><i
                                    class="icofont-calendar"></i><span>events </span></a></li>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/certification.php"; ?>"><i
                                    class="icofont-certificate-alt-1"></i><span>certification </span></a></li>
 */ ?>
                    <?php // if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/files/myFiles.php"; ?>"><i
                                    class="icofont-files-stack"></i><span>(My Drive)My Files</span></a></li>
                    <?php if ($_SESSION['orgType'] == 3) { ?>
                        <li><a class="nav-link"
                               href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/pdfSign/pdfList.php"; ?>"><i
                                        class="icofont-files-stack"></i><span>Digital Signature (PDF List)</span></a></li>
                    <?php } ?>
                   <?php /* <li><a class="nav-link" href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/technical-inquiry.php"; ?>"><i
                                    class="icofont-ui-clip-board"></i><span>Technical Inquiry</span></a></li>
                    <li><a class="nav-link" href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/technical-status.php"; ?>"><i
                                    class="icofont-ui-clip-board"></i><span>Technical Status</span></a></li>
                    */ ?>
                    <?php //} ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/account-termination.php"; ?>"><i
                                    class="icofont-ui-delete"></i><span>Termination Request</span></a></li>
                    <?php // if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/checkout.php"; ?>"><i
                                    class="icofont-ui-check"></i><span>checkout</span></a></li>
                    <?php //} if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/orderlist.php"; ?>"><i
                                    class="icofont-basket"></i><span>your order</span></a></li>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/contact-us.php"; ?>"><i
                                    class="ui-contact-list"></i><span>Contact Us</span></a></li>
                    <?php //} ?>
                    <li><a class="nav-link"
                           href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/logout.php?logout=true"; ?>"><i
                                    class="icofont-ui-lock"></i><span>logout</span></a></li>
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