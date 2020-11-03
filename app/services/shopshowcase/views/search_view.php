<main>
    <h1><?=$_SESSION['alias']->name?></h1>
    <?php if($products) { ?>
    <div class="flex h-evenly catalog__sorted">
        <div>
            <a href="<?=$this->data->get_link('sort', 'price_down')?>" <?=($this->data->get('sort') == 'price_down')?'class="active"':''?>><?=$this->text('Спершу дешеві')?></a>
            <a href="<?=$this->data->get_link('sort', 'price_up')?>" <?=($this->data->get('sort') == 'price_up')?'class="active"':''?>><?=$this->text('Спершу дорожчі')?></a>
            <a href="<?=$this->data->get_link('sort', 'name')?>" <?=($this->data->get('sort') == 'name')?'class="active"':''?>>А &mdash; Я</a>
            <a href="<?=$this->data->get_link('sort', 'name_desc')?>" <?=($this->data->get('sort') == 'name_desc')?'class="active"':''?>>Я &mdash; А</a>
        </div>
        <div class="quantity__goods">
            <?=$this->text('Кількість товарів', 0)?> &mdash; <span><?=$_SESSION['option']->paginator_total?></span>
        </div>
    </div>
    <?php } ?>

    <section class="sale">
        <div class="flex wrap sale__wrrap">
            <?php if($products) {
                foreach ($products as $product) {
                     require APP_PATH.'views/@commons/__product_subview.php';
                 } 
                 $add_block = 5 - count($products) % 5;
                 if($add_block < 5)
                    for ($i=0; $i < $add_block; $i++) { 
                        echo "<div class='sale__card empty'></div>";
                    }
            } else { ?>
                <div class="alert alert-danger w100">
                    <h4><?=$this->text('Вибачте, за даним запитом нічого не знайдено! Спробуйте повторити пошук змінивши артикул/назву товару')?></h4>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>

    <?php $this->load->library('paginator');
    $this->paginator->style('ul', 'flex h-center v-center w50 pagination');
    echo $this->paginator->get(); ?>
</main>