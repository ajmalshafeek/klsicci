<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();


    if (isset($_SESSION['memberRegOver']) && $_SESSION['memberRegOver'] == true) {
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/renewal.php");
    }
    if (isset($_SESSION['memberRegPending']) && $_SESSION['memberRegPending'] == true) {
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/upgrade.php");
    }



    $category = null;
    $title = null;
    $view = null;
    $onPage = null;
    $listView = null;
    if (isset($_GET['page'])) {
        $onPage = $_GET['page'];
    } else {
        $onPage = 1;
    }
    if (isset($_GET['cat'])) {
        $category = $_GET['cat'];
    }
    if (isset($_GET['title'])) {
        $title = $_GET['title'];
    }
    if (isset($_GET['view'])) {
        $view = $_GET['view'];
    }
    if (isset($_GET['listView'])) {
        $listView = $_GET['listView'];
    }
    $_SESSION['prodCount'] = 1;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Client - Home</title>
    <?php require("./assets/css.php"); ?>

    <link rel="stylesheet" href="./css/index.css">
    <style></style>
    <style>
        .promo-slider li a img {
            width: 60%;
        }

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

        @media (min-width: 768px) {

            .col-md-4,
            .col-lg-4,
            .col-xl-4 {
                max-width: calc(33.333% - 15px);
                margin-left: 7px;
                margin-right: 7px;
            }

            .col-xl-3 {
                max-width: calc(25% - 15px);
                margin-left: 7px;
                margin-right: 7px;
            }
        }

        @media (min-width: 576px) and (max-width: 767px) {

            .col-6,
            .col-sm-6 {
                max-width: calc(50% - 15px);
                margin-left: 7px;
                margin-right: 7px;
            }

            .col-xl-3 {
                max-width: calc(33.333% - 15px);
                margin-left: 7px;
                margin-right: 7px;
            }
        }

        @media (min-width: 100px) and (max-width: 575px) {

            .col-6,
            .col-sm-6 {
                max-width: calc(50% - 15px);
                margin-left: 7px;
                margin-right: 7px;
            }

            .col-xl-3 {
                max-width: calc(100% - 15px);
                margin-left: 7px;
                margin-right: 7px;
                flex: 0 0 100%;
            }

            .product-filter {
                padding: 15px 25px;
            }
        }

        button.close.css-close {
            background-color: #ffffff;
            padding: 5px 10px;
            border-radius: 25px;
            position: relative;
            top: -16px;
            right: 17px;
        }

        .hidden {
            display: none !important;
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
    <?php if ($_SESSION['orgType'] != 3) { ?>
        <section class="banner-part">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="banner-content" style="background: url(./img/banner.jpg) no-repeat center; background-size: cover;">
                            <h1>get your items quickly.</h1>
                            <p>We are always ready to deliver product to your doorstep every day</p>
                            <form class="banner-form" action="">
                                <div class="select-option"><i class="icon icofont-grocery"></i><span class="text"><?php if (isset($_GET['cat'])) {
                                                                                                                        echo $_GET['cat'];
                                                                                                                    } else { ?>All
                                    <?php } ?></span>
                                    <?php echo searchCategoryTopMnu();  ?>
                                </div><input type="text" name="title" placeholder="Search in store...">
                                <input type="hidden" name="cat" value="<?php if (isset($_GET['cat'])) {
                                                                            echo $_GET['cat'];
                                                                        } else { ?>All <?php } ?>">
                                <button type="submit"><i class="icofont-ui-search"></i><span>search</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </<?php } ?> <?php $data = getBannerList();
                        if (!empty($data)) {
                        ?> <section>
        <div class="container mb-3">

            <div id="slider" class="carousel slide" data-ride="carousel" data-interval="3000">

                <!-- Indicators -->
                <ul class="carousel-indicators">
                    <?php
                            $i = 0;
                            foreach ($data as $banner) { ?>

                        <li data-target="#slider" data-slide-to="
                        <?php echo $i . '"   class="';
                                if ($i == 0) {
                                    echo 'active';
                                } ?>
                                "></li>
                    <?php $i++;
                            }
                            $i = 0; ?>

                </ul>

                <!-- The slideshow -->
                <div class=" carousel-inner">
                    <?php foreach ($data as $banner) { ?>
                        <div class="carousel-item
                                <?php if ($i == 0) {
                                    echo 'active';
                                } ?> ?>">
                            <img src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/" .
                                            $banner['path']; ?>" style="width: 100%;border-radius: 10px;">
                        </div>
                    <?php $i++;
                            } ?>
                </div>

                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#slider" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#slider" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>

        </div>
        </section>
    <?php
                        }
                        /*
<section>
    <div class="container mb-4">
        <div class="row">
        <div class="col-sm-4 py-3 px-3">
            <a href="https://www.kwsp.gov.my/ms/home" target="_blank"><img src="img/icon-kwsp.jpg" style="width: 100%;border-radius: 10px;"></a>
        </div>
        <div class="col-sm-4 py-3 px-3">
            <a href="https://www.perkeso.gov.my/" target="_blank"><img src="img/icon-perkeso.jpg" style="width: 100%;border-radius: 10px;"></a>
        </div>
        <div class="col-sm-4 py-3 px-3">
            <a href="https://www.ssm.com.my/" target="_blank"><img src="img/icon-ssm.jpg" style="width: 100%;border-radius: 10px;"></a>
        </div>
        </div>
    </div>
</section> */ ?>
    <section class="promo-part">
        <?php
        if ($_SESSION['userid'] != 360) {
            require("./assets/promo-slider.php");
        }
        ?>
        <section class="product-part" id="smenu">
            <div class="container">
                <div class="row">
                    <?php /*  <div class="col-lg-3 category-menu">
                    <?php require("./assets/side-category-menu-desktop.php"); ?>
                </div> */ ?>
                    <div class="col-lg-12">
                        <?php if (isset($_SESSION['message'])) {
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        }  ?>
                    </div>
                </div>
            </div>
        </section>


    </section>

    <section class="product-part" id="smenu">
        <div class="container">
            <div class="row">
                <?php /*  <div class="col-lg-3 category-menu">
                    <?php require("./assets/side-category-menu-desktop.php"); ?>
                </div> */ ?>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product-filter">
                                <div class="product-filter-short"><label class="form-label">sort by:</label>
                                    <form method="get">
                                        <select class="form-select" name="view">
                                            <option value="1">Default </option>
                                            <option value="2">Default Desc</option>
                                            <option value="3">Title Asc</option>
                                            <option value="4">Title Desc</option>
                                            <option value="5">Price Asc</option>
                                            <option value="6">Price Desc</option>
                                        </select>
                                    </form>

                                </div>
                                <a href="./store.php" class="my-3">Clear Filter</a>
                                <ul class="product-filter-list">
                                    <li><button type="submit" class="column-3" data-toggle="tooltip" title="Three Column"><i class="icofont-justify-all" name="listView" value="0"></i></button></li>
                                    <li><button type="submit" class="column-4" data-toggle="tooltip" title="Four Column"><i class="icofont-indent" name="listView" value="0"></i></button></li>
                                    <li><button type="submit" class="column-5" data-toggle="tooltip" title="List view"><i class="icofont-listing-box form-viewList" name="listView" value="1"></i></button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row product-list"><?php
                                                    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store.php");
                                                    checkPassword();

                                                    echo storeItemShow();
                                                    //   echo productStoreList($category,$title)
                                                    ?></div>
                    <div class="row">
                        <?php /* <div class="col-lg-12 text-center loader">

                        </div> */ ?>
                        <div class="col-lg-12">
                            <?php /*<div class="load-btn click-load">
                                <?php //<button class="btn btn-outline">Load more items </button> ?>
                            </div>
                            */ ?>
                            <div class="pagination">
                                <?php
                                getPagination($category, $title, $onPage);
                                ?>
                            </div>

                            <div class="footer-part footer-content">ll
                                <?php require("./assets/footer-content.php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require("./assets/quick-view-product.php"); ?>
    <div class="mobile-check"><button class="check-btn" onclick="show_cart()"><span class="check-item"><i class="icofont-basket"></i><span><span class="itemCount"><?php if (isset($_SESSION['src'])) {
                                                                                                                                                                        echo count($_SESSION['src']);
                                                                                                                                                                    } else {
                                                                                                                                                                        '0';
                                                                                                                                                                    } ?></span>
                    items</span></span><span class="check-price cartTotal">RM <?php if (isset($_SESSION['src'])) {
                                                                                    echo $_SESSION['cartTotal'];
                                                                                } else {
                                                                                    '00.00';
                                                                                } ?></span></button></div>

    <?php
    if (isset($_SEESION['samePass']) && $_SEESION['samePass']) { ?>
        <!-- passwprd reset model start -->
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('#myModalsamePass').modal("show");
                }, 3000);
            });
        </script>
        <!-- Modal -->
        <div class="modal fade" id="myModalsamePass" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Password
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Kindly change default password from profile page <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/profile.php">link</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- passwprd reset model end -->
    <?php } ?>
    <?php require("./assets/js.php"); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.promo-slider').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                nextArrow: '<i class="icofont-arrow-right dandik slick-arrow" style="display: block;"></i>',
                prevArrow: '<i class="icofont-arrow-left bamdik slick-arrow" style="display: block;"></i>',
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        infinite: true,
                        settings: {
                            arrows: false
                        }
                    }
                }, {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        settings: {
                            arrows: false
                        }
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        settings: {
                            arrows: false
                        }
                    }
                }]
            });

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
                slidesToShow: 4,
                slidesToScroll: 1,
                asNavFor: '.preview-slider',
                dots: false,
                centerMode: true,
                focusOnSelect: true,
                arrows: false
            });

            $('.form-select').on("change", function() {
                <?php
                $reqparam = "";
                if (!empty($category)) {
                    $reqparam = "cat=" . $category . "&";
                }
                if (!empty($listView)) {
                    $reqparam = "listView=" . $listView . "&";
                }
                if (!empty($title) && !empty($category)) {
                    $reqparam .= "title=" . $title . "&";
                } elseif (!empty($title)) {
                    $reqparam .= "title=" . $title . "&";
                }

                echo "let param=\"" . $reqparam . "\";";
                ?>
                param += "view=" + $('.form-select').val();

                window.location.href =
                    "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/store.php?" +
                    param;
            });

            $('.form-viewList').on("click", function() {
                <?php
                $reqparam = "";
                if (!empty($category)) {
                    $reqparam = "cat=" . $category . "&";
                }
                if (!empty($view)) {
                    $reqparam = "view=" . $view . "&";
                }
                if (!empty($title) && !empty($category)) {
                    $reqparam .= "title=" . $title . "&";
                } elseif (!empty($title)) {
                    $reqparam .= "title=" . $title . "&";
                }

                echo "let param=\"" . $reqparam . "\";";
                ?>
                param += "listView=" + $('.form-viewList').attr("value");

                window.location.href = "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/store.php?" +
                    param;
            });

            $('.pagination-b').on("click", function() {
                <?php
                $reqparam = "";
                if (!empty($category)) {
                    $reqparam = "cat=" . $category . "&";
                }
                if (!empty($view)) {
                    $reqparam = "view=" . $view . "&";
                }
                if (!empty($title) && !empty($category)) {
                    $reqparam .= "title=" . $title . "&";
                } elseif (!empty($title)) {
                    $reqparam .= "title=" . $title . "&";
                }
                if (!empty($listView)) {
                    $reqparam = "listView=" . $listView . "&";
                }
                echo "let param=\"" . $reqparam . "\";";
                ?>
                param += "page=" + $(this).attr("value");

                window.location.href = "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/store.php?" + param;

            });
        });
        /*
            (function($) {
                $.fn.loaddata = function(options) { // Settings
                    var settings = $.extend({
                        loading_gif_url: "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/img/ajax-loader.gif", //url to loading gif
                        end_record_text: 'No more records found!', //end of record text
                        loadbutton_text: 'Load More Items', //load button text
                        data_url: '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/client/store/assets/category-data.php', //url to PHP page
                        start_page: <?php echo $onPage; ?> //initial page
                    }, options);

                    var el = this;
                    loading = false;
                    end_record = false;

                    //initialize load button
                    var load_more_btn = $('<button/>').text(settings.loadbutton_text).addClass('btn hidden').click(
                        function(e) {
                            contents(el, this, settings); //load data on click
                        });

                    contents(el, load_more_btn, settings); //initial data load
                };

                //Ajax load function
                function contents(el, load_btn, settings) {
                    var load_img = $('<img/>').attr('src', settings.loading_gif_url).addClass(
                        'loading-image'); //loading image
                    var record_end_txt = $('<div/>').text(settings.end_record_text).addClass(
                        'end-record-info'); //end record text

                    if (loading == false && end_record == false) {
                        loading = true; //set loading flag on
                        $('loader').html(load_img);
                        //el.append(load_img); //append loading image

                        //temporarily remove button on click
                        if (load_btn.type === 'submit' || load_btn.type === 'click') {
                            load_btn.remove(); //remove loading img
                        }

                        $.get(settings.data_url, {
                                'page': settings.start_page
                                <?php if (!empty($listView)) {
                                    echo ", 'listView': '" . $listView . "'";
                                }
                                if (!empty($category)) {
                                    echo ", 'cat': '" . $category . "'";
                                }
                                if (!empty($title)) {
                                    echo ", 'title': '" . $title . "'";
                                }
                                if (!empty($view)) {
                                    echo ", 'view': '" . $view . "'";
                                } ?>
                            },
                            function(data) { //jQuery Ajax post
                                if (data.trim().length == 0) { //if no more records
                                    el.append(record_end_txt); //show end record text
                                    load_img.remove(); //remove loading img
                                    load_btn.remove(); //remove load button
                                    end_record = true; //set end record flag on
                                    return; //exit
                                }
                                loading = false; //set loading flag off
                                load_img.remove(); //remove loading img
                                el.append(data).append(load_btn); //append content and button
                                //$(".click-load").append(load_btn);
                                settings.start_page++; //page increment
                            })
                    }
                }
                $(".product-list").loaddata();
            })(jQuery);
        <?php if (
            $category != null ||
            $title != null ||
            $view != null
        ) {
            echo ' location.href = "#smenu";';
        } ?>
            /* equal height */
        /*
        function setEqualHeight(columns) {
            var tallestcolumn = 0;
            columns.each(
                function() {
                    currentHeight = $(this).height();
                    if(currentHeight > tallestcolumn) {
                        tallestcolumn  = currentHeight;
                    }
                }
            );
        columns.height(tallestcolumn);
        }

        var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();
        var winsize = $(window).width();
        if (winsize > 769){
            $('.product-card').css('height','auto'); //solve for all you browser stretchers out there!
            setEqualHeight($('.product-card'));
            $(window).resize(function() {
                delay(function(){
                    $('.product-card').css('height','auto'); //solve for all you browser stretchers out there!
                    setEqualHeight($('.product-card'));
                });
            });
        } */
    </script>
    <!-- Image Map Generated by http://www.image-map.net/ -->
    <img src="popup.png" usemap="#image-map">

    <map name="image-map">
        <area target="_blank" alt="gkacc" title="gkacc" href="https://gkacca.com/contact-us/" coords="344,702,100,632" shape="rect">
    </map>

</body>

</html>