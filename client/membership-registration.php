<!DOCTYPE html>
<html>
<head>
    <title>Registration - Membership</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        img{width: 100vw !important;}
        .container-fluid{padding: 0 !important;}
        </style>
</head>
<body>
<div class="container-fluid">
    <img src="imgreg/signup.jpg" class="tech-1" />
    <img src="imgreg/indivual-1.jpg" class="tech-2" />
    <img src="imgreg/indivual-2.jpg" class="tech-3" />
    <img src="imgreg/indivual-3.jpg" class="tech-4" />
    <img src="imgreg/indivual-4.jpg" class="tech-5" />
    <img src="imgreg/indivual-5.jpg" class="tech-6" />
    <img src="imgreg/indivual-6.jpg" class="tech-7" />
    <img src="imgreg/indivual-7.jpg" class="tech-8" />
    <img src="imgreg/signup.jpg" class="tech-9" />
    <img src="imgreg/corporate-1.jpg" class="tech-10" />
    <img src="imgreg/corporate-2.jpg" class="tech-11" />
    <img src="imgreg/corporate-3.jpg" class="tech-12" />
    <img src="imgreg/corporate-4.jpg" class="tech-13" />
    <img src="imgreg/corporate-5.jpg" class="tech-14" />
    <img src="imgreg/corporate-6.jpg" class="tech-15" />
    <img src="imgreg/corporate-7.jpg" class="tech-16" />
    <!-- <img src="imgreg/not-mem-verified.jpg" class="tech-18" /> -->

    <script>
        $('.tech-2').css('display','none');$('.tech-3').css('display','none');$('.tech-4').css('display','none');
        $('.tech-5').css('display','none');$('.tech-6').css('display','none');$('.tech-7').css('display','none');
        $('.tech-8').css('display','none');$('.tech-9').css('display','none');$('.tech-10').css('display','none');
        $('.tech-11').css('display','none');$('.tech-12').css('display','none');$('.tech-13').css('display','none');
        $('.tech-14').css('display','none');$('.tech-15').css('display','none');$('.tech-16').css('display','none');

        $('.tech-1').on('click',function () { $('.tech-2').css('display','initial'); $('.tech-1').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-2').on('click',function () { $('.tech-3').css('display','initial'); $('.tech-2').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-3').on('click',function () { $('.tech-4').css('display','initial'); $('.tech-3').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-4').on('click',function () { $('.tech-5').css('display','initial'); $('.tech-4').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-5').on('click',function () { $('.tech-6').css('display','initial'); $('.tech-5').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-6').on('click',function () { $('.tech-7').css('display','initial'); $('.tech-6').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-7').on('click',function () { $('.tech-8').css('display','initial'); $('.tech-7').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-8').on('click',function () { $('.tech-9').css('display','initial'); $('.tech-8').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-9').on('click',function () { $('.tech-10').css('display','initial'); $('.tech-9').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-10').on('click',function () { $('.tech-11').css('display','initial'); $('.tech-10').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-11').on('click',function () { $('.tech-12').css('display','initial'); $('.tech-11').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-12').on('click',function () { $('.tech-13').css('display','initial'); $('.tech-12').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-13').on('click',function () { $('.tech-14').css('display','initial'); $('.tech-13').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-14').on('click',function () { $('.tech-15').css('display','initial'); $('.tech-14').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-15').on('click',function () { $('.tech-16').css('display','initial'); $('.tech-15').css('display','none'); $("html,body").scrollTop(0); });
        $('.tech-16').on('click',function () {  $('.tech-18').css('display','initial'); window.location.replace('https://iiam.jsuitecloud.com/client/index.php') });
        //$('.tech-18').on('click',function () { $('.tech-1').css('display','initial'); $('.tech-18').css('display','none'); $("html,body").scrollTop(0); });
    </script>

</div>
</body>
</html>