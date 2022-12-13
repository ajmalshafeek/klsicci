<?php if(!isset($_SESSION['name'])){
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/");
} ?>
<link rel="stylesheet" href="./css/icofont.min.css">
<link rel="stylesheet" href="./css/flaticon.css">
<link rel="stylesheet" href="./css/slick.css">
<link rel="stylesheet" href="./css/bootstrap.css">
<link rel="stylesheet" href="./css/main.css">
<link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
<style>
    :root{
        --primary-hover: #6a91ea;
        --primary: #3665d2;
    }
    .nav-content {
   //     background: #006299;
    }
    .nav-profile h4 a {
    //    color: #ffffff;
    }

    .nav-list .nav-link {
   //     color: #ffffff;
    }
    .nav-footer p {
   //     color: #ffffff;
    }
    .tab-content .cate-link {
    //    color: #ffffff;
    }
    .product-filter {
        padding: 5px 25px;
    }
    .single-banner::before {
        background: -webkit-gradient(linear,left bottom,left top,from(rgba(0,0,0,.6)),to(rgb(153 202 60 / 80%)));
        background: linear-gradient(to top,rgba(0,0,0,.6),rgb(153 202 60 / 80%));
    }
    .header-part {
        background: #fff;
    }
    .text{
        color: #dd4040;
        font-width: 400 !important;
    }
    body {
    //    background: #006299;
    }
    .icon-check{
        width: 170px;
    }
    .icofont-trash{
        cursor: pointer;
    }
    .alert-info {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px 20px;
        border-radius: 3px;
        background: var(--white);
        border-top: 3px solid var(--primary);
        -webkit-box-shadow: 0 2px 48px 0 rgb(0 0 0 / 8%);
        box-shadow: 0 2px 48px 0 rgb(0 0 0 / 8%);
    }
    .alert-info p {
        font-weight: 700;
        color: var(--primary);
        text-shadow: var(--primary-shadow);
    }
    .product-filter, div.product-card, .nav-header, button.nav-close, .header-icon-group button i{
        background-color: #79dae8;
    }
    body{ background: #e8f8fd;}
</style>