<a href="https://wa.me/15551234567?text=I'm%20interested%20in%20your%20offer" class="clicktocall" target="_blank" ><img src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/whatsapp-logo.png'; ?>" /></a>
<script src="./js/jquery-1.12.4.min.js"></script>
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/header-part.js"></script>
<script src="./js/product-part.js"></script>
<script src="./js/product-view.js"></script>
<script src="./js/select-option.js"></script>
<script src="./js/dropdown.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="./js/slick.min.js"></script>
<script src="./js/slick.js"></script>
<script src="./js/main.js"></script>
<script>
    $(document).ready(function () {
        $(".details-minus").on("click", function() {
            let count = $(".details-input").val();
            count--;
            if (count > 0) {
                $(".details-input").val(count);
            }
        });
        $(".details-plus").on("click", function() {
            let count = $(".details-input").val();
            count++;
            let max = $(".details-input").attr("max");

            if (max < count) {
                $(".details-input").val(max);
            } else {
                $(".details-input").val(count);
            }
        });
        $(".details-input").on("change", function() {
            let count = $(".details-input").val();
            let max = $(".details-input").attr("max");
            if (count > max) {
                $(".details-input").val(max);
            } else if (count < 1) {
                $(".details-input").val(0);
            }
        });
    });
    function quickView(id) {

        $.ajax({
            type: 'post',
            url: '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data: {
                id: id
            },
            success: function(res) {
                res = JSON.parse(res);
                console.log(res);
                var pr="";
                if(res.price==0){
                pr='Free';}
                 else{
                pr='RM '+res.price;
            }

                document.getElementById("viewName").innerText = res.title;
               <?php if($_SESSION['orgType']!=3){ ?>
                <document.getElementById> document.getElementById("viewSKU").innerText = res.sku;
                document.getElementById("viewBrand").innerText = res.brand; <?php } ?>

                document.getElementById("viewPrice").innerText = pr;
                    <?php if($_SESSION['orgType']!=3){ ?>
                    document.getElementById("viewTag").innerHTML = '<li><a href="#">' + res.model + '</a></li>'; <?php } ?>
                document.getElementById("viewCategory").innerHTML = '<li><a href="#">' + res.producttype +
                    '</a></li>';
                document.getElementById("viewDesc").innerText = res.description;
                document.getElementById("viewImg").src =
                    "<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>" + res.img1;
                if(res.quantity>0){
                    $(".details-input").attr("max", res.quantity + "");
                }else{
                    $(".details-input").attr("max", res.quantity + "");
                }
                $(".details-input").attr("min", "1");

                document.getElementById("item_id").value = res.id;
                document.getElementById("item_title").value = res.title;
                document.getElementById("item_price").value = res.price;
                document.getElementById("item_img").value =  "<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>" + res.img1;
                document.getElementById("item_quantity").value=1;
                $("#item_quantity").attr(max, res.quantity);

            }
        });
    }

    function cart(tag) {
        var id=tag;
        var ele = document.getElementById(id);
        var pid = document.getElementById(id + "_id").value;
        var name = document.getElementById(id + "_title").value;
        var price = document.getElementById(id + "_price").value;
        var img_src = document.getElementById(id + "_img").value;
        var quantity = document.getElementById(id + "_quantity").value;

        $.ajax({
            type: 'post',
            url: '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data: {
                'item_id': pid,
                'item_name': name,
                'item_price': price,
                'item_src': img_src,
                'item_quantity': quantity
            },
            success: function(response) {

            }
        });

        show_cart();
        updateTotal();
    }
    function show_cart()
    {
        $.ajax({
            type:'post',
            url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data:{
                showcart:"cart"
            },
            success:function(res) {
                if(res==0){document.getElementById("viewCart").innerHTML='<center>No item found</center>';}
                else{
                    document.getElementById("viewCart").innerHTML=res;
                }
            }
        });
        updateTotal();
    }
    function updateTotal(){
        $.ajax({
            type:'post',
            url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data:{
                cartTotalCount:"cart"
            },
            success:function(res) {
                if(res==0){document.getElementById("viewCart").innerHTML=0;}
                else{
                    const json=JSON.parse(res);
                    console.log(json);
                    console.log(json.cartotal);
                    $(".cartTotal").html("RM "+(parseFloat(json.cartotal).toFixed(2)));
                    $(".itemCount").html(json.itemcount);
                }
            }
        });
    }

    function removeCart(key){
        $.ajax({
            type:'post',
            url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data:{
                removeItemFromCart:key
            },
            success:function(res) {
                if(res) {
                    show_cart();
                    updateTotal();
                }
            } });
    }
    function cartMinus(tag){
        let id = tag;
        var price = document.getElementById(id + "_price").value;
        var quantity = document.getElementById(id + "_qty").value;
        var pid = document.getElementById(id + "_pid").value;
        if(quantity>1){
            let qty=parseInt(quantity)-1
            document.getElementById(id + "_qty").value=qty;
            let total=parseFloat(price)*parseInt(qty);
            document.getElementById(id + "_total").innerHTML="RM "+(total.toFixed(2));
            updateCart(pid,qty,total.toFixed(2));
        }
    }
    function cartPlus(tag){
        let id = tag;
        var price = document.getElementById(id + "_price").value;
        var quantity = document.getElementById(id + "_qty").value;
        var pid = document.getElementById(id + "_pid").value;
        var max=document.getElementById(id + "_qty").max;
        let qty=0;
        if(quantity>-1){
            if(quantity>=max){qty=max;}
            else{qty=parseInt(quantity)+1;}

            document.getElementById(id + "_qty").value=qty;
            let total=parseFloat(price)*parseInt(qty);
            document.getElementById(id + "_total").innerHTML="RM "+(total.toFixed(2));
            updateCart(pid,qty,total.toFixed(2));
        }

    }
    function updateCartItem(tag){

        let id=tag
        var price = document.getElementById(id + "_price").value;
        var qty = document.getElementById(id + "_qty").value;
        var pid = document.getElementById(id + "_pid").value;
        var max=document.getElementById(id + "_qty").max;

        if(parseInt(qty)>-1){
            if(parseInt(qty)>=max){
                qty=max;
                document.getElementById(id + "_qty").value=max;
            }
            let total=parseFloat(price)*parseInt(qty);
        updateCart(pid,qty,total.toFixed(2));
    }
    }
    function updateCart(pid,qty,total){
        $.ajax({
            type:'post',
            url:'<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']; ?>/phpfunctions/store.php',
            data:{
                cartSingleItemUpdate:true,
                pid:pid,
                qty:qty,
                total:total
            },
            success:function(res) {
                if(res) {
                    updateTotal();
                }
            } });
    }
    function removeItem(tag){
        removeCart(tag);
        viewCheckout();
        window.location.reload();
    }
</script>