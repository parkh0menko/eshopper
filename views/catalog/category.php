<?php require_once ROOT . '/views/layouts/header.php'; ?>    
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Каталог</h2>
                    <div class="panel-group category-products">
                        <?php foreach ($categories as $categoryItem): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="<?php echo $categoryItem['id'] ?>" 
                                           class="<?php if ($categoryId == $categoryItem['id']) echo 'active'; ?>">
                                            <?php echo $categoryItem['name']; ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        <?php endforeach; ?>                        
                    </div>
                </div>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Последние товары</h2>
                    <?php foreach ($categoryProducts as $product): ?>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="<?php echo Product::getImage($product['id']); ?>" alt="" />
                                    <h2><?php echo $product['price']; ?>$</h2>
                                    <a href="/eshopper/product/<?php echo $product['id']; ?>">    
                                        <p><?php echo $product['name']; ?></p>
                                    </a>
                                    <a href="/eshopper/cart/addAjax/<?php echo $product['id']; ?>" data-id="<?php echo $product['id']; ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>В корзину</a>
                                </div>
                                <?php if ($product['is_new']): ?>
                                    <img src="/eshopper/template/images/home/new.png" class="new" alt="">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>     

                    <?php echo $pagination->get(); ?>

                </div><!--features_items-->
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT . '/views/layouts/footer.php'; ?>