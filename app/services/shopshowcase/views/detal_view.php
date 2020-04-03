<main class="container">
    <section class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li><a href="<?= SITE_URL?>"><?=$this->text('Головна', 0)?></a></li>
                <li><a href="<?= SITE_URL.$_SESSION['alias']->alias?>"><?=$_SESSION['alias']->breadcrumb_name?></a></li>
                <?php if(!empty($_SESSION['alias']->breadcrumbs))
                foreach($_SESSION['alias']->breadcrumbs as $breadcrumb_name => $breadcrumb_link)
                {
                    if($breadcrumb_link == '') echo('<li class="active">'.$_SESSION['alias']->name.'</li>');
                    else echo('<li><a href="'.SITE_URL.$breadcrumb_link.'">'.$breadcrumb_name.'</a></li>');
                } ?>
            </ol>
        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-sm-5">
                <h3><?=$_SESSION['alias']->list?></h3><hr><br>
                <?php if(!empty($_SESSION['alias']->images)) { ?>
                <div class="product-images">
                    <div class="product-thumbnail">
                        <a href="<?=IMG_PATH.$_SESSION['alias']->images[0]->path?>" class="fancybox" rel="gallery">
                            <img src="<?=IMG_PATH.$_SESSION['alias']->images[0]->detal_path?>" style="width:auto; margin: 0 auto" class="img-responsive">
                        </a>
                    </div>
                    <div class="product-images-carousel">
                        <?php for ($i = 1; $i < count($_SESSION['alias']->images); $i++) {  ?>
                        <div class="item">
                            <a href="<?=IMG_PATH.$_SESSION['alias']->images[$i]->path?>" class="fancybox" rel="gallery">
                                <img src="<?=IMG_PATH.$_SESSION['alias']->images[$i]->thumb_path?>" class="img-responsive">
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="col-sm-6 col-sm-offset-1">
                <div class="product-details">
                    <div class="rating">
                        <span class="pull-right"><?=$this->text('Артикул')?>: <?= $product->article ?><span></span></span>
                    </div>

                    <?php $canBy = false;
                    if(!empty($product->group))
                        foreach ($product->group as $g) {
                            if($g->active)
                            {
                                $canBy = true;
                                break;
                            }
                        }
                     ?>
                    <div class="product-title">
                        <div class="row">
                            <h3 class="product-name"><?= $_SESSION['alias']->name ?></h3>
                            <hr>
                            <br>
                            <br>
                            <br>
                            <?php if($canBy) { ?>
                                <p class="price">
                                    <?php if($product->old_price != 0) { ?>
                                    <del>
                                        <span class="amount"><?= $product->old_price_format ?></span>
                                    </del>
                                    <?php } ?>
                                    <ins>
                                        <span class="amount" id="product-price"><?= $product->price_format ?></span>
                                    </ins>
                                </p>
                            <?php } ?>
                        </div>
                        
                    </div>

                    <div class="inputs-border">
                        
                        <?php $productOptionsChangePrice = array();
                        if(!empty($product->options))
                            foreach ($product->options as $key => $option) {
                                if(empty($option->value))
                                    continue;
                                if(!empty($option->changePrice))
                                    $productOptionsChangePrice[] = $option->id;
                                ?>
                                <div class="row">
                                    <h4 id="product-option-name-<?=$option->id?>"><?=$option->name?></h4>
                                    <?php if(is_array($option->value)) {
                                        foreach ($option->value as $value) { 
                                            if(is_object($value)) { ?>
                                                <div class="option">
                                                    <label>
                                                        <input type="radio" name="product-option-<?=$option->id?>" value="<?=$value->id?>" onchange="updateProductPrice()">
                                                        <?=$value->name?>
                                                    </label>
                                                </div>
                                            <?php } else
                                                echo "<p>{$value}</p>";
                                        } }
                                        else
                                            echo "<p>{$option->value}</p>"; ?>

                                        
                                        <?php //select
                                        /* if(is_array($option->value)) {
                                            echo '<select name="product-option-'.$option->id.'"  onchange="updateProductPrice()">';
                                        foreach ($option->value as $value) { 
                                            if(is_object($value))
                                                echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                            else
                                                echo '<option>'.$value.'</option>';
                                        } }
                                        else
                                            echo "<p>{$option->value}</p>"; */ ?>
                                </div>
                            <?php } ?>
                        
                        <div class="form-group">
                            <div class="row">
                                <label>
                                <?=$this->text('Вподобати сторінку',0)?> <?php
                                $likes = array();
                                $likes['content'] = $product->id;
                                $likes['name'] = $_SESSION['alias']->name;
                                $likes['link'] = $_SESSION['alias']->link;
                                $likes['image'] = (isset($_SESSION['alias']->images[0])) ? $_SESSION['alias']->images[0] : false;
                                $likes['additionall'] = "<p>{$product->price} грн</p>";
                                $this->load->function_in_alias('likes', '__show_Like_Btn', $likes); ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <?php $this->load->function_in_alias('cart', '__show_btn_add_product', $product); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['alias']->text || $_SESSION['alias']->list){ ?>
            <div class="col-sm-12">
                <div class="tabs-wrapper">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php if($_SESSION['alias']->text){ ?>
                        <li class="active">
                            <a href="#tab-description" aria-controls="tab-description" data-toggle="tab"><?=$this->text('Опис')?></a>
                        </li>
                        <?php } ?>
                    </ul>
                    <!-- Tab panes -->

                    <div class="tab-content">
                        
                        <div class="tab-pane active" id="tab-description">
                            <?=html_entity_decode($_SESSION['alias']->text)?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>

    <?php if($otherProductsByGroup = $this->shop_model->getProducts($product->group, $product->id)) { ?>
    <section>
        <h3><?=$this->text('Вас також може зацікавити')?></h3>
        <div id="related-products">
            <?php foreach ($otherProductsByGroup as $otherProduct) { ?>
            <div class="product">
                <div class="inner-product">
                    <?php if($otherProduct->photo != '') { ?>
                    <div class="product-thumbnail">
                        <img src="<?=IMG_PATH.$otherProduct->catalog_photo?>" class="img-responsive" alt="<?=$otherProduct->name?>">
                    </div>
                    <?php } ?>
                    <div class="product-details text-center">
                        <div class="product-btns">
                            <span data-toggle="tooltip" data-placement="top" title="View">
                                <a href="<?=SITE_URL.$otherProduct->link?>" class="li-icon view-details"><i class="lil-search"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
                <h3 class="product-title"><a href="<?=SITE_URL.$otherProduct->link?>"><?= $otherProduct->article ?></a></h3>
                <p class="product-price">
                    <ins>
                        <span class="amount"><?=$otherProduct->price?> грн </span>
                    </ins>
                    <?php if($otherProduct->old_price != 0 && $otherProduct->old_price > $otherProduct->price) { ?>
                    <del>
                        <span class="amount"><?=$otherProduct->old_price?> грн </span>
                    </del>
                    <?php } ?>
                </p>
            </div>
            <?php } ?>
        </div>
    </section>
    <?php } 

if(!empty($productOptionsChangePrice)) { ?>
<script>
    var productID = <?=$product->id?>;
    var productOptionsChangePrice = [<?=implode(',', $productOptionsChangePrice)?>];
</script>
<?php $_SESSION['alias']->js_load[] = 'js/'.$_SESSION['alias']->alias.'/product.js'; 
} ?>

</main>