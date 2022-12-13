<div class="product-category" id="category">
    <?php if($_SESSION['memberRegPending']==false&&$_SESSION['memberRegOver']==false){ ?>
    <?php  echo categorySidePopMenu(); ?>
    <?php } ?>
</div>