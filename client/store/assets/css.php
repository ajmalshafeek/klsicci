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
    //    color: #ffffff;
    }
    .nav-footer p {
    //    color: #ffffff;
    }
    .tab-content .cate-link {
    //    color: #ffffff;
    }
    .product-filter {
        padding: 0px 25px;
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
   //     background: #006299;
    }
    .list-group-item {
        background-color: #328fc3;
        color: #2a2a2a;
    }
    a.cate-link.dropdown {
    //   color: #fff ;
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
    .product-category .cate-list{
        background-color: white;
        border-radius: 10px;
        box-shadow: 0px 7px 20px -5px #00000020;
    }
    .product-category a.cate-link.dropdown:hover ,div.product-card {
        background-color: #cdcdcd;
    }
    body{ background: #f5f5f5;}
    .product-image img {
        border-radius: 50px;
    }
.product-filter{
    background-color :#9e9e9e2e; color: var(--primary);}
    .product-filter a, .product-filter a:hover{
    color: var(--primary);}
    a.btn.btn-dark.popup-btn.center {
        margin-left: -15px;
        border-radius: 50px;
        margin-top: -30px;
        border-color: #0c0c0c !important;
    }
    .clicktocall {
        position: fixed;
        right: 20px;
        bottom: 70px;
    }
    .plan {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .col-12.listView.product-card {
        padding: 0;
        margin: 0;
    }
    .col-12.listView.product-card .row  {
        padding: 5px !important;
        margin-bottom: 2px !important;
        display: flex;
        align-items: center;
    }
    .col-12.listView.product-card .row h5{
        margin: 0;
    }

</style>