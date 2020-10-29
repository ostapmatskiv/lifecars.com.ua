<main class="detal">
    <h1 class="detal__heading"><?=$product->options['1-manufacturer']->value.' '.$product->name?></h1>
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
                <?php /*
                <div class="flex h-evenly v-center card__rating detal__rating">
                    <div class="rating">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                    </div>
                    <div class="rating__comment">
                        <i class="rating__lebel">25</i>
                        <img src="style/icons/comment.png" alt="comment">
                    </div>
                </div>
                */ ?>
            </div>
            <div class="img__small">
                <?php if(!empty($_SESSION['alias']->images)) {
                    for ($i = 0; $i < count($_SESSION['alias']->images); $i++) {  ?>
                        <div class="flex h-center v-center small__img">
                            <a href="<?=IMG_PATH.$_SESSION['alias']->images[$i]->path?>" class="small__item"><img src="<?=IMG_PATH.$_SESSION['alias']->images[$i]->thumb_path?>"></a> 
                        </div>
                <?php } } ?>
            </div>
        </div>
        <div class="info__bonus">
            <div class="flex h-start v-center bonus__send">
               <div class="send__img">
                    <img src="/style/icons/detal/shipped.svg" alt="shipped">
               </div>
                <p><?=$this->text('Відправимо Вам товару')?><br><u><?=$this->text('день замовлення')?></u></p>
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
                <p><strike><?=round($product->old_price) ?></strike> ₴</p>
            </div>
            <?php } ?>
            <div class="new__price">
                <?=$product->price_format ?> ₴
            </div>
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
            <button class="detal__cart" data-product_key="<?="{$product->wl_alias}-{$product->id}"?>" data-product_name="<?="{$product->options['1-manufacturer']->value} {$product->article_show} {$product->name}"?>">
                <img src="/style/icons/detal/shopping-cart.svg" alt="cart">
                <?=$this->text('Додати до кошика')?>
            </button>
            <?php }
            /*
            <button class="detal__bay">
                <img src="/style/icons/detal/thunder.svg" alt="thunder">
                Купити в один клік
            </button>
            */ ?>
        </div>
    </section>
    <div id="tabs" class="detal__menu">
        <nav class="flex tabs-nav">
            <a href="#tab-1"><?=$this->text('Характеристики')?></a>
        </nav>
        <div id="tab-1" class="menu__info">
            <h4><?=$_SESSION['alias']->list?></h4><hr><br>

            <div class="flex w50 m100">
                <div><?=$this->text('Застосовується до')?></div>
                <div class="dots"></div>
                <div><?php 
                    if(!empty($product->parents)){
                        $name = [];
                        foreach ($product->parents as $group) {
                            $name[] = $group->name;
                        }
                        $link = SITE_URL.$product->group_link;
                        $name = implode(' ', $name);
                        echo "<a href='{$link}'>{$name}</a> ";
                    } ?></div>
            </div>
            <div class="flex w50 m100">
                <div><?=$this->text('Група запчастин')?></div>
                <div class="dots"></div>
                <div><?php 
                    if(!empty($product->options['2-part']->value)){
                        $part = [];
                        foreach ($product->options['2-part']->value as $value) {
                            $part[] = $value->name;
                        }
                        echo implode(', ', $part);
                    } ?></div>
            </div>
            <div class="flex w50 m100">
                <div><?=$this->text('Артикул')?></div>
                <div class="dots"></div>
                <div><?=$product->article_show?></div>
            </div>
            <div class="flex w50 m100">
                <div><?=$this->text('Виробник')?></div>
                <div class="dots"></div>
                <div><?=$product->options['1-manufacturer']->value?></div>
            </div>
            <div class="flex w50 m100">
                <div><?=$this->text('Країна виробник')?></div>
                <div class="dots"></div>
                <div><?=$this->text('Китай')?></div>
            </div>
            <div class="flex w50 m100">
                <div><?=$this->text('Код товару')?></div>
                <div class="dots"></div>
                <div><?=$product->id?></div>
            </div>
        </div>
        <div id="tab-2" class="menu__info">
            <?=html_entity_decode($_SESSION['alias']->text)?>
        </div>

        <?php if($product->similarProducts || $otherProductsByGroup) { ?>
            <div class="detal__line"></div>
            <p><?=$this->text('Аналоги')?></p>
        <?php } ?>
    </div>
    
    <?php if(false && $product->similarProducts) { ?>
        <div class="flex detal__cart">
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
    <?php } ?>
    <?php if($otherProductsByGroup) { ?>
        <div class="flex detal__cart">
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
</main>