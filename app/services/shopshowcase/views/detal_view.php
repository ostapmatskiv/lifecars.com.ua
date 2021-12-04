<main class="detal">
    <h1 class="detal__heading"><?=$product->name.' '.mb_strtoupper($product->options['1-manufacturer']->value->name).' ('.$product->article_show.')'?></h1>

    <?php if(!empty($_SESSION['alias']->list)) { ?>
        <div class="card__border_in">
            <?=$_SESSION['alias']->list?>
        </div>
    <?php } ?>

    <?php if(!empty($_SESSION['notify']->success)): ?>
        <div id="comment_add_success" class="alert alert-success">
            <span class="close" data-dismiss="alert">×</span>
            <h4><?=(isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : $this->text('Success!')?></h4>
            <p><?=$_SESSION['notify']->success?></p>
        </div>
    <?php endif;
    if(!empty($_SESSION['notify']->errors)) { ?>
       <div id="comment_add_error" class="alert alert-danger">
            <span class="close" data-dismiss="alert">×</span>
            <h4><?=(isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : 'Error!'?></h4>
            <p><?=$_SESSION['notify']->errors?></p>
        </div>
    <?php } unset($_SESSION['notify']); 

    if(empty($product->rating)) $product->rating = 0;
    ?>

    <section class="flex detal__info">
        <div class="flex h-start info__img"> 
            <div class="m__right">
                <div class="flex h-center v-center img__big <?=(empty($product->photo)) ? 'm-hide':''?>">
                    <?php if(!empty($product->photo)) { ?>
                        <img src="<?=IMG_PATH.$product->detal_photo?>" alt="<?=$product->article_show.' '.$product->name?>">
                    <?php } if($product->old_price > $product->price) { ?>
                        <i class="img__discount">-<?=100-ceil($product->price / ($product->old_price / 100))?>%</i>
                    <?php } if(($product->date_add + 3600 * 24 * 30) > time()) { ?>
                        <i class="img__new">NEW</i>
                    <?php } ?>
                    <!-- <i class="img__hit">ХІТ</i> -->
                </div>
                <div class="flex h-evenly v-center card__rating">
                    <div class="rating <?=empty($product->rating)?'empty':''?>" title="<?=empty($product->rating)?$this->text('Оцінка відсутня'):$this->text('Оцінка товару ').' '.$product->rating?>">
                        <?php for($i = 0; $i < round($product->rating); $i++) { ?>
                            <i class="fas fa-star" aria-hidden="true"></i>
                        <?php } for($i = round($product->rating); $i < 5; $i++) { ?>
                            <i class="far fa-star" aria-hidden="true"></i>
                        <?php } ?>
                    </div>
                    <div class="rating__comment">
                        <img src="/style/icons/comment.png" alt="comment">
                        <i><?=$product->rating_votes ?? 0?></i>
                    </div>
                </div>
            </div>
            <div class="img__small">
                <?php if(!empty($_SESSION['alias']->images)) {
                    for ($i = 0; $i < count($_SESSION['alias']->images); $i++) {  ?>
                        <div class="flex h-center v-center small__img">
                            <a href="<?=IMG_PATH.$_SESSION['alias']->images[$i]->detal_path?>" class="small__item"><img src="<?=IMG_PATH.$_SESSION['alias']->images[$i]->thumb_path?>"></a> 
                        </div>
                <?php } } ?>
            </div>
        </div>
        <div class="info__bonus">
            <div class="flex h-start v-center bonus__send">
               <div class="send__img">
                    <img src="/style/icons/detal/shipped.svg" alt="shipped">
               </div>
                <p><?=$this->text('Відправимо Вам товар у')?><br><u><?=$this->text('день замовлення')?></u></p>
            </div>
            <div class=" flex h-start v-center bonus__guarantee">
                <div class="guarantee__img">
                    <img src="/style/icons/detal/guarantee.svg" alt="guarantee">
                </div>
                <p><?=$this->text('Надійна')?> <u><?=$this->text('гарантія')?></u> <?=$this->text('товару')?></p>
            </div>
            <div class="flex h-start v-center bonus__return">
                <div class="return__img">
                    <img src="/style/icons/detal/return.svg" alt="return">
                </div>
                <p><?=$this->text('Безпроблемне')?> <u><?=$this->text('повернення')?></u><br><?=$this->text('товару')?></p>
            </div>
            <div class="flex h-start v-center bonus__extra">
                <div class="extra__img">
                    <img src="/style/icons/detal/extra.svg" alt="extra">
                </div>
                <p class="etra__text"><?=$this->text('Нараховуваєм Вам 5% від суми замовлення, при наявності бонусної картки обо за зроблене замовлення при реєстрації на нашому сайті')?></p>
            </div>
        </div>
        <div class="text-right info__price">
            <?php if($this->userCan()) { ?>
                <a href="/admin/<?=$product->link?>">Редагувати Admin</a>
            <?php } if($product->old_price > $product->price) { ?>
            <div class="old__price">
                <p><strike><?=round($product->old_price) ?></strike></p>
            </div>
            <?php } ?>
            <div class="new__price"><?=$product->price_format ?></div>
            <?php if($product->old_price > $product->price) { ?>
            <div class="flex h-end v-center discount">
                <div class="discount__procent">
                    -<?=100-ceil($product->price / ($product->old_price / 100))?>%
                </div>
                <p><?=$this->text('Економія за')?><br><?=$this->text('рахунок знижки')?></p>
                <div class="discount__price"><?=round($product->old_price - $product->price) ?> ₴</div>
            </div>
            <?php } ?>
            <div class="flex h-end v-center card__check detal__check">
                <div class="flex v-center check__pieces">
                    <i class="fas <?=$product->availability > 0 ? 'fa-check-circle' : 'fa-times-circle'?>"></i>
                    <p><?=$this->text('В наявності')?> <span class="pieces"><?=$product->availability?></span> шт.</p>
                </div>
                <?php if($product->active && $product->availability > 0) { ?>
                <div class="flex check__number">
                    <span class="minus">-</span>
                    <input type="number" value="1" min="1" max="<?=$product->availability?>" />
                    <span class="plus">+</span>
                </div>
                <?php } ?>
            </div>
            <?php if($product->active && $product->availability > 0) { ?>
            <button class="detal__cart" data-product_key="<?="{$product->wl_alias}-{$product->id}"?>" data-product_name="<?="{$product->options['1-manufacturer']->value->name} {$product->article_show} {$product->name}"?>">
                <img src="/style/icons/detal/shopping-cart.svg" alt="cart">
                <?=$this->text('Додати до кошика')?>
            </button>
            <button class="detal__bay" data-product_key="<?="{$product->wl_alias}-{$product->id}"?>" data-product_name="<?="{$product->options['1-manufacturer']->value->name} {$product->article_show} {$product->name}"?>">
                <img src="/style/icons/detal/thunder.svg" alt="thunder">
                <?=$this->text('Купити в один клік', 0)?>
            </button>
            <?php } ?>
        </div>
    </section>
    <?php $_SESSION['option']->paginator_per_page = 5;
        $_GET['article'] = $product->article;
        if($otherProductsByGroup = $this->shop_model->getProducts($product->group, $product->id))
            $this->setProductsPrice($otherProductsByGroup);
        $_SESSION['option']->paginator_per_page = 20; ?>
    <div id="tabs" class="detal__menu">
        <nav class="flex tabs-nav <?=($product->similarProducts || $otherProductsByGroup)?'tabs-3':''?>">
            <?php if($product->similarProducts || $otherProductsByGroup) { ?>
                <a href="#tab-similar" class="active"><?=$this->text('Аналоги')?></a>
                <a href="#tab-info"><?=$this->text('Опис')?></a>
            <?php } else { ?>
                <a href="#tab-info" class="active"><?=$this->text('Опис')?></a>
            <?php } ?>
            <a href="#tab-guarantie"><?=$this->text('Гарантія')?></a>
            <a href="#tab-reviews"><?=$this->text('Відгуки')?></a>
        </nav>

        <?php $mainProduct = clone $product;
        if($product->similarProducts || $otherProductsByGroup) { ?>
        <div id="tab-similar" class="menu__info active">
            <?php if(false && $product->similarProducts) { ?>
                <div class="flex m-wrap detal__cart">
                    <?php $add_block = 5 - count($product->similarProducts) % 5;
                    foreach ($product->similarProducts as $product) {
                            $product->wl_alias = $_SESSION['alias']->id;
                             require APP_PATH.'views/@commons/__product_subview.php';
                         }
                    if($add_block < 5)
                        for ($i=0; $i < $add_block; $i++) { 
                            echo "<div class='sale__card empty'></div>";
                        } ?>
                </div>
            <?php }
            if($otherProductsByGroup) { ?>
                <div class="flex m-wrap sale__wrrap">
                    <?php foreach ($otherProductsByGroup as $product) {
                             require APP_PATH.'views/@commons/__product_subview.php';
                         }
                    $add_block = 5 - count($otherProductsByGroup) % 5;
                    if($add_block < 5)
                        for ($i=0; $i < $add_block; $i++) { 
                            echo "<div class='sale__card empty'></div>";
                        } ?>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
        <div id="tab-info" class="menu__info <?=($mainProduct->similarProducts || $otherProductsByGroup) ? '' : 'active' ?>">
            <div class="flex m-wrap">
                <div class="w50 m100">
                    <?php if(false && !empty($_SESSION['alias']->list)) { ?>
                    <h4><?=$_SESSION['alias']->list?></h4><hr>
                    <br>
                    <?php } ?>
                    <div class="flex row">
                        <div><?=$this->text('Застосовується до')?></div>
                        <div class="dots"></div>
                        <div><?php 
                            if(!empty($mainProduct->parents)){
                                $name = [];
                                foreach ($mainProduct->parents as $group) {
                                    $name[] = $group->name;
                                }
                                $link = SITE_URL.$mainProduct->group_link;
                                $name = implode(' ', $name);
                                echo "<a href='{$link}'>{$name}</a> ";
                            } ?></div>
                    </div>
                    <div class="flex row">
                        <div><?=$this->text('Група запчастин')?></div>
                        <div class="dots"></div>
                        <div><?php 
                            if(!empty($mainProduct->options['2-part']->value)){
                                $part = [];
                                foreach ($mainProduct->options['2-part']->value as $value) {
                                    $part[] = $value->name;
                                }
                                echo implode(', ', $part);
                            } ?></div>
                    </div>
                    <div class="flex row">
                        <div><?=$this->text('Артикул')?></div>
                        <div class="dots"></div>
                        <div><?=$mainProduct->article_show?></div>
                    </div>
                    <?php if(!empty($mainProduct->options['1-manufacturer']->value)){ ?>
                    <div class="flex row">
                        <div><?=$this->text('Виробник')?></div>
                        <div class="dots"></div>
                        <div><?=$mainProduct->options['1-manufacturer']->value->name?></div>
                    </div>
                    <div class="flex row">
                        <div><?=$this->text('Країна виробник')?></div>
                        <div class="dots"></div>
                        <div><?=empty($mainProduct->options['1-manufacturer']->value->sufix) ? $this->text('Китай') : $mainProduct->options['1-manufacturer']->value->sufix?></div>
                    </div>
                    <?php } else { ?>
                    <div class="flex row">
                        <div><?=$this->text('Країна виробник')?></div>
                        <div class="dots"></div>
                        <div><?=$this->text('Китай')?></div>
                    </div>
                    <?php } ?>
                    <div class="flex row">
                        <div><?=$this->text('Код товару')?></div>
                        <div class="dots"></div>
                        <div><?=$mainProduct->id?></div>
                    </div>
                </div>
                <div class="w50 m100">
                    <?=$_SESSION['alias']->text?>
                </div>
            </div>
        </div>
        <div id="tab-guarantie" class="menu__info">
            <?php echo $this->load->function_in_alias('exchange-and-return', '__get_Text'); ?>
            <style>#tab-guarantie p { text-align: left }</style>
        </div>
        <div id="tab-reviews" class="menu__info">
            <div class="flex m-wrap">
                <?php $this->load->library('comments');
                $this->comments->show(); ?>
            </div>
        </div>
        
        <?php $this->load->js_init('init__p_detal()'); ?>
    </div>
</main>