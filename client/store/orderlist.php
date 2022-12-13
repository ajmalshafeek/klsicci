<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    if(isset($_SESSION['ocount'])){
        unset($_SESSION['ocount']);
    }
}?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Jsuit Enterprise Solutions and Integrated Digital Signature (JES-IDS) - Orderlist</title>
      <?php require("./assets/css.php"); ?>
  <link rel="stylesheet" href="./css/orderlist.css">
  <style>
      .orderlist-deliver{height: auto !important;}
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
  <section class="single-banner" style="background: url(./img/banner.jpg) no-repeat center; background-size: cover;">
    <div class="container">
      <h2>your orderlist</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./store.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">orderlist</li>
      </ol>
    </div>
  </section>
  <section class="orderlist-part">
    <div class="container">
        <div class="row">
        <div class="col-lg-12">
            <form action="? " method="get">
          <div class="orderlist-filter">
            <h3>total order <span> - <?php echo getOrderCount(); ?></span></h3>
              <div class="filter-short"><label class="form-label">by month:</label>

                  <input type="month" class="form-select"  onchange="this.form.submit();" name="month" />
              </div>

            <div class="filter-short"><label class="form-label">sort by:</label>

                <select class="form-select"  onchange="this.form.submit();" name="status">
                    <option disabled selected>--Select--</option>
                    <option value="0"="">all order</option>
                <option value="1">received order</option>
                <option value="2">processed order</option>
                <option value="3">shipped order</option>
                <option value="4">delivered order</option>
              </select></div>
          </div>
            </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 order-container">
            <!-- order items -->
        </div>
      </div>
      <div class="row">
          <div class="col-lg-12 text-center loader">

          </div>
        <div class="col-lg-12">
          <div class="load-btn mt-5 click-load"><?php //<button class="btn btn-outline ">Load more items </button> ?></div>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer-part">
      <?php require("./assets/footer-content.php"); ?>
  </footer>
<?php require("./assets/js.php"); ?>
<script>
    (function($) {
        $.fn.loaddata = function(options) { // Settings
            var settings = $.extend({
                loading_gif_url: "<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/client/store/img/ajax-loader.gif", //url to loading gif
                end_record_text: 'No more records found!', //end of record text
                loadbutton_text: 'Load More Items', //load button text
                data_url: '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php', //url to PHP page
                start_page: 1 //initial page
            }, options);

            var el = this;
            loading = false;
            end_record = false;

            //initialize load button
            var load_more_btn = $('<button/>').text(settings.loadbutton_text).addClass('btn').click(
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
                        'orderList': settings.start_page
                        <?php if(!empty($_GET['status'])){ echo ", 'orderType': ".$_GET['status'].""; } ?>
                        <?php if(!empty($_GET['month'])){ echo ", 'orderMonth': '".$_GET['month']."'"; } ?>
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
                        $(".click-load").append(load_btn);
                        settings.start_page++; //page increment
                    })
            }
        }
        $(".order-container").loaddata();
    })(jQuery);
</script>
</body>

</html>