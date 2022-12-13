<?php $config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
?>
<div class="modal fade" id="product-view" style="padding-right: 17px;">
    <div class="modal-dialog">
        <div class="modal-content"><button class="modal-close icofont-close" data-dismiss="modal"></button>
            <div class="product-view">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="product-gallery">
                            <img src="./img/dummy.png" id="viewImg" alt="product" width="100%">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="product-details">
                            <h3 class="details-name" id="viewName"><a
                                    href="#">existing
                                    product
                                    name</a></h3>
                           <?php if($_SESSION['orgType']!=3){ ?>
                            <div class="details-meta" >SKU:<span class="details-sku" id="viewSKU">xxxx</span>BRAND:<a
                                    class="details-brand" id="viewBrand"
                                    href="#" >Test</a>
                            </div>
                            <?php } ?>
                            <h3 class="details-price"><span id="viewPrice">RM 999.99</span></h3>
                            <p class="details-desc" id="viewDesc">Lorem ipsum dolor sit amet consectetur adipisicing elit non tempora
                                magni repudiandae sint suscipit tempore quis maxime explicabo veniam eos reprehenderit
                                fuga</p>
                            <?php if($_SESSION['orgType']!=3){ ?> <div class="details-group"><label class="details-group-title">Model:</label>
                                <ul class="details-tag-list" id="viewTag">
                                    <li><a
                                            href="#" id="">gadgets</a>
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                            <div class="details-group"><label class="details-group-title">Category:</label>
                                <ul class="details-tag-list" id="viewCategory">
                                    <li><a
                                                href="#" id="">gadgets</a>
                                    </li>
                                </ul>
                            </div>
                            <hr class="details-devider">
                            <div id="item">
                            <div class="viewQuantity">
                                <button class="details-minus" title="Quantity Minus"><i class="icofont-minus"></i></button>
                                <input class="details-input" id="item_quantity"  title="Quantity Number" type="text" name="quantity" value="1">
                                <button class="details-plus" title="Quantity Plus"><i class="icofont-plus"></i></button>
                                <input type="hidden" id="item_id" value="" />
                                <input type="hidden" id="item_title" value="" />
                                <input type="hidden" id="item_price" value="" />
                                <input type="hidden" id="item_img" value="#" />
                            </div>
                            <div class="details-action-group">
                                <button class="quick-details-cart" onclick="cart('item')" title="Add Your Cartlist">
                                    <i class="icofont-cart"></i><span>add to cart</span></button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>