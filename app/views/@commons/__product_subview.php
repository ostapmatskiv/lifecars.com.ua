<div class="sale__card <?=($product->availability > 0)?'':'no_availabilaty'?>">
    <a href="<?=SITE_URL.$product->link?>" class="flex h-center v-center card__img <?=empty($product->photo)?'empty_photo' : ''?>">
        <?php if(!empty($product->photo)) { ?>
            <img src="<?=IMG_PATH.$product->catalog_photo?>" alt="<?=$product->article_show.' '.$product->name?>">
        <?php } if($product->old_price > $product->price) { ?>
            <i class="card__label">-<?=100-ceil($product->price / ($product->old_price / 100))?>%</i>
        <?php } if(!empty($product->date_add) && ($product->date_add + 3600 * 24 * 30) > time()) { ?>
            <i class="new__label">new</i>
        <?php } ?>
    </a>
    <?php /*<div class="flex v-center card__rating">
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
        <img src="../../style/icons/comment.png" alt="comment">
     </div>
    </div>*/ ?>
    <div class="card__text">
        <?=$product->name?>
    </div>
    <div class="card__info">
        <div class="w33 info__article">
            <p>Виробник</p>
            <p>Артикул</p>
        </div>
        <div class="w66 info__name">
            <p><?=$product->manufacturer?></p>
            <p><?=$product->article_show?></p>
        </div>
    </div>
    <div class="flex v-center card__check">
        <div class="flex v-center check__pieces">
            <i class="fas <?=$product->availability > 0 ? 'fa-check-circle' : 'fa-times-circle'?>"></i>
            <p>В наявності <span class="pieces"><?=$product->availability?></span> шт.</p>
        </div>
        <?php if($product->availability > 0) { ?>
        <div class="flex check__number">
            <span class="minus">-</span>
            <input type="number" value="1" min="1" max="<?=$product->availability?>" />
            <span class="plus">+</span>
        </div>
        <?php } ?>
    </div>
    <?php if($product->availability > 0) { ?>
    <div class="flex v-center card__price">
        <div class="price__text"><?=number_format($product->price, 0, '.', ' ') ?> ₴</div>
        <div class="price__cart">
            <button class="cart__buy" data-product_key="<?="{$product->wl_alias}-{$product->id}"?>" data-product_name="<?="{$product->manufacturer} {$product->article_show} {$product->name}"?>">
                <img src="/style/icons/shopping-cart.png" alt="">
            </button>
        </div>
    </div>
    <?php } else { ?>
    <div class="cart__modal">
        <h5>Зв'яжіться з нами</h5>
        <p>і ми проінформуємо Вас про можливість замовлення та оптимальну ціну на цей товар:</p>
        <div class="modal__name">
            <?=$product->name?>
        </div>
        <div class="modal__product">
            <?=$product->manufacturer?> <?=$product->article_show?>
        </div>
        <div class="modal__phone">
            <a href="tel:+380930000943">+38 093 0000 943</a>
            <a href="tel:+380960000946">+38 096 0000 946</a>
        </div>
        <span>або</span>
        <button class="modal__request">Залишити заявку</button>

        <div class="cart__form">
            <div class="form__name">
                <?=$product->name?>
            </div>
            <div class="form__product">
                <?=$product->manufacturer?> <?=$product->article_show?>
            </div>
            <form action="#">
                <input required name="city" list="city__select" type="text" placeholder="Виберіть мшісто">
                <datalist id="city__select"></datalist>
                <input required type="text" placeholder="Ім'я">
                <input required type="tel" placeholder="Телефон">
                <input required type="email" placeholder="Електронна адреса">
                <button class="form__btn">Надіслати запит</button>
            </form>
        </div>

        <button class="cart__hiden">
            <i class="fas fa-chevron-circle-up"></i>
        </button>
    </div>
    <div class="flex v-center card__price">
        <div class="price__text">Під замовлення</div>
        <div class="price__cart">
            <button class="cart__order">
                <img src="/style/icons/telephone-2.svg" alt="telephone">
            </button>
        </div>
    </div>
    <?php } ?>
</div>