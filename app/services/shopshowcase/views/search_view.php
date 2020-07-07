<main>
    <div class="flex h-evenly catalog__sorted">
        <div>
            <a href="<?=$this->data->get_link('sort', 'price_down')?>">Спершу дешеві</a>
            <a href="<?=$this->data->get_link('sort', 'price_up')?>">Спершу дорожчі</a>
            <a href="<?=$this->data->get_link('sort', 'name')?>">А &mdash; Я</a>
            <a href="<?=$this->data->get_link('sort', 'name_desc')?>">Я &mdash; А</a>
        </div>
        <div class="quantity__goods">
            Кількість товарів &mdash; <span><?=$_SESSION['option']->paginator_total?></span>
        </div>
    </div>

    <section class="sale">
        <div class="flex wrap sale__wrrap">
            <?php if($products) {
                foreach ($products as $product) {
                     require APP_PATH.'views/@commons/__product_subview.php';
                 } 
                 $add_block = 5 - count($products) % 5;
                 if($add_block < 5)
                    for ($i=0; $i < $add_block; $i++) { 
                        echo "<div class='sale__card'></div>";
                    } 
            } ?>
        </div>
    </section>

    <?php /*
    <div class="flex h-center v-center w50 pagination">
        <button>
           <img src="/style/icons/catalog/back-left.svg" alt="left">
        </button>
            <a href="">1</a>
            <a href="">486</a>
            <a href="">487</a>
            <a href="">488</a>
            <a href="">2546</a>
        <button>
           <img src="/style/icons/catalog/back-right.svg" alt="right">
        </button>
    </div>
    */ ?>
</main>