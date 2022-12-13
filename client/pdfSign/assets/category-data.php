<?php
$config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store.php");

function categorySidePopMenu()
{
    $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");
    $dataList = getCategoryList();
    $menuCategory = '<ul class="cate-list">';
    foreach ($dataList as $data) {
        $menuCategory .= '
            <li><a class="cate-link dropdown" href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/store.php?cat=' . $data['type'] . '"><span>' . $data['type'] . '</span></a>
            </li>';
    }
    $menuCategory .= '</ul>';
    return $menuCategory;
}

function searchCategoryTopMnu()
{
    $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");
    $dataList = getCategoryList();
    $beforeSearch = '<ul class="option-list">';
    foreach ($dataList as $data) {
        $beforeSearch .= '<li><a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/store.php?cat=' . $data['type'] . '"><i class="icofont-grocery"></i><span>' . $data['type'] . '</span></a></li>';
    }
    $beforeSearch .= '</ul>';

    return $beforeSearch;
}

function productStoreList($cat, $title)
{
    $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");
    $dataList = storeItems($cat, $title);
    $products = '';
    foreach ($dataList as $data) {
        $products .= '<div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                            <div class="product-card">
                                <figure class="product-media">
                                    <!--<div class="product-label-group"><label
                                            class="product-label label-new">new</label><label
                                            class="product-label label-off">-10%</label>
                                            </div>-->
                                            <a class="product-image" href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] .
            '/query/product.php?pid=' . $data['id'] . '">
                                            <img src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . $data['img1'] .
            '" alt="product"></a></figure>
                                <div class="product-content">
                                    <h5 class="product-price"><span>'. $data['price']==0?'Free':'RM '.$data['price'] .  '</span></h5>
                                    <h5 class="product-name">
                                    <a href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/product.php?pid='
            . $data['id'] . '">' . $data['title'] . '</a></h5>
                                    <div class="product-action-group">
                                        <div class="product-action"><button class="action-wish" title="Product Wish"><i
                                                    class="icofont-ui-love"></i></button><button class="action-cart"
                                                title="Add to Cart"><span>add to cart</span></button><button
                                                class="action-view" title="Product View" data-toggle="modal"
                                                data-target="#product-view"><i class="icofont-eye-alt"></i></button>
                                        </div>
                                        <div class="product-action"><button class="action-minus"
                                                title="Quantity Minus"><i class="icofont-minus"></i></button><input
                                                class="action-input" title="Quantity Number" type="text" name="quantity"
                                                value="1"><button class="action-plus" title="Quantity Plus"><i
                                                    class="icofont-plus"></i></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>';
    }

    return $products;
}
if (isset($_GET['page'])) {
    $pages = $_GET['page'];
    $cat = "";
    $title = "";
    $view = "";
    $limit = 12;
    if (isset($_GET['cat']) && trim(strtolower($_GET['cat'])) != "all") {
        $cat = $_GET['cat'];
    }
    if (isset($_GET['title'])) {
        $title = $_GET['title'];
    }
    if (isset($_GET['view'])) {
        $view = $_GET['view'];
    }
    $config = parse_ini_file(__DIR__ . "/../../../jsheetconfig.ini");

    $dataList = storeItemsPaging($cat, $title, $view, $pages, $limit);
    $products = '';
    if (!isset($_SESSION['prodCount'])) {
        $_SESSION['prodCount'] = 1;
    }
    $countProduct = $_SESSION['prodCount'];
    foreach ($dataList as $data) {

        $products .= '<div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                            <div class="product-card"  id="item' . $countProduct . '">
                                <figure class="product-media">
                                    <!--<div class="product-label-group"><label
                                            class="product-label label-new">new</label><label
                                            class="product-label label-off">-10%</label>
                                            </div>-->
                                            <a class="product-image"
                                        href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/store/product.php?pid=' . $data['id'] . '"><img
                                            src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . $data['img1'] . '" alt="product"></a>
                                </figure>';
        $products .= '<div class="product-content">
                                    <h5 class="product-price"><span>'. $data['price']==0?'Free':'RM '.$data['price'] . ' </span></h5>
                                    <h5 class="product-name"><a
                                            href="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/query/product.php?pid=' . $data['id'] . '">
                                            ' . $data['title'] . '</a></h5>
                                            <input type="hidden" id="item' . $countProduct . '_id" value="' . $data['id'] . '" />
                                            <input type="hidden" id="item' . $countProduct . '_price" value="' . $data['price'] . '" />
                                            <input type="hidden" id="item' . $countProduct . '_title" value="' . $data['title'] . '" />
                                            <input type="hidden" id="item' . $countProduct . '_img" value="https://' . $_SERVER["HTTP_HOST"] . $config["appRoot"] . $data["img1"] . '" />
                                            <input type="hidden" id="item' . $countProduct . '_quantity" value="1" />';
        $products .= '<div class="product-action-group">
                                                    <div class="product-action"><button class="action-cart addtocart' . $countProduct . '" title="Add to Cart" onclick="cart(\'item' . $countProduct .
            '\')" ><span>add to cart</span></button>
                                                    <button class="action-view viewProd' . $countProduct .
            '" title="Product View" data-toggle="modal" data-target="#product-view" value="" onclick="quickView(' . $data["id"] . ')"><i class="icofont-eye-alt"></i></button>
                                        </div>
                                        <div class="product-action"><button class="action-minus prodminus' . $countProduct . '"
                                                title="Quantity Minus"><i class="icofont-minus"></i></button><input
                                                class="action-input" title="Quantity Number" class="quantityBox' . $countProduct . '" type="text" name="quantity' . $countProduct . '"
                                                value="1"><button class="action-plus prodplus' . $countProduct . '" title="Quantity Plus"><i
                                                    class="icofont-plus"></i></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $countProduct++;
    }
    $_SESSION['prodCount'] = $countProduct;

    echo $products;
}