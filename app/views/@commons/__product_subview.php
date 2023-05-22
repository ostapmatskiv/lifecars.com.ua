<div class="sale__card <?=($product->availability > 0)?'':'no_availabilaty'?>">
    <a href="<?=SITE_URL.$product->link?>" class="flex h-center v-center card__img <?=empty($product->photo)?'empty_photo' : ''?>">
        <?php if(!empty($product->photo)) { ?>
            <img src="<?=IMG_PATH.$product->catalog_photo?>" alt="<?=$product->article_show.' '.$product->name?>">
        <?php } if($product->old_price > $product->price) { ?>
            <i class="card__label">-<?=100-ceil($product->price / ($product->old_price / 100))?>%</i>
        <?php } if(!empty($product->date_add) && ($product->date_add + 3600 * 24 * 30) > time()) { ?>
            <i class="new__label">new</i>
        <?php } 
        if(!isset($product->manufacturer))
            $product->manufacturer = '';
        elseif(!empty($product->manufacturer__photo))
            echo "<img src='{$product->manufacturer__photo}' class='manufacturer__photo'>";
        ?>
    </a>
   
    <div class="card__text">
        <?=$product->name?>
    </div>
    <?php if(!empty($product->list)) { ?>
    <div class="card__border_in">
        <?=$product->list?>
    </div>
    <?php } ?>
    <div class="card__info">
        <div class="w33 info__article">
            <p><?=$this->text('Виробник', 0)?></p>
            <p><?=$this->text('Артикул', 0)?></p>
        </div>
        <div class="w66 info__name">
            <p><?=$product->manufacturer?></p>
            <p><?=$product->article_show?></p>
        </div>
    </div>
    <?php if($product->availability > 0) { ?>
    <div class="price__text"><?=$product->price_format ?></div>
    <div class="flex v-center card__price">
        <div class="flex check__number">
            <span class="minus">-</span>
            <input type="number" value="1" min="1" max="<?=$product->availability?>" />
            <span class="plus">+</span>
        </div>
        <div class="price__cart">
            <button class="cart__buy" data-product_key="<?="{$product->wl_alias}-{$product->id}"?>" data-product_name="<?="{$product->manufacturer} {$product->article_show} {$product->name}"?>">
                <img src="/style/icons/shopping-cart.png"> <span>Купити</span>
            </button>
        </div>
    </div>
    <?php } else { ?>
        <div class="flex v-center card__price">
            <div class="price__text"><?=$this->text('Під замовлення', 0)?></div>
            <div class="price__cart">
                <button class="cart__order">
                    <img src="/style/icons/telephone-2.svg" alt="telephone">
                </button>
            </div>
        </div>
        <div class="cart__modal">
            <h5><?=$this->text('Зв\'яжіться з нами', 0)?></h5>
            <p><?=$this->text('і ми проінформуємо Вас про можливість замовлення та оптимальну ціну на цей товар:', 0)?></p>
            <div class="modal__name">
                <?=$product->name?>
            </div>
            <div class="modal__product">
                <?=$product->manufacturer?> <?=$product->article_show?>
            </div>
            <div class="modal__phone">
                <a href="tel:<?=str_replace(' ', '', $site_tel_1)?>"><?=$site_tel_1?></a>
                <a href="tel:<?=str_replace(' ', '', $site_tel_2)?>"><?=$site_tel_2?></a>
            </div>
            <span>або</span>
            <button class="modal__request"><?=$this->text('Залишити заявку', 0)?></button>

            <div class="cart__form">
                <div class="form__name">
                    <?=$product->name?>
                </div>
                <div class="form__product">
                    <?=$product->manufacturer?> <?=$product->article_show?>
                </div>
                <form method="POST" action="<?=SITE_URL?>save/orders" class="save_orders">
                    <input type="hidden" name="product" value="<?=$product->manufacturer?> <?=$product->article_show?> <?=$product->name?>">
                    <input required name="city" list="city__select" type="text" placeholder="<?=$this->text('Місто', 0)?>">
                    <datalist id="city__select">
                        <option><?=$this->text('Львів', 0)?></option>
                        <option><?=$this->text('Київ', 0)?></option>
                        <option><?=$this->text('Харків', 0)?></option>
                        <option><?=$this->text('Одеса', 0)?></option>
                        <option><?=$this->text('Дніпро', 0)?></option>
                        <option><?=$this->text('Івано-Франківськ', 0)?></option>
                        <option><?=$this->text('Тернопіль', 0)?></option>
                        <option><?=$this->text('Запоріжжя', 0)?></option>
                    </datalist>
                    <input required name="name" type="text" placeholder="<?=$this->text('Ім\'я', 0)?>">
                    <input required name="phone" type="tel" placeholder="<?=$this->text('Телефон', 0)?>">
                    <input required name="email" type="email" placeholder="<?=$this->text('Електронна адреса', 0)?>">
                    <?php if(!$this->userIs()) { ?>
                        <br>
                        <br>
                        <?php
                            $this->load->library('recaptcha');
                            $this->recaptcha->form('recaptchaVerifyCallback_saveOrders', 'recaptchaExpiredCallback_saveOrders');
                        ?>
                    <?php } ?>
                    <button class="form__btn" <?=$this->userIs() ? '':'disabled title=\'Заповніть "Я не робот"\''?>><?=$this->text('Надіслати запит', 0)?></button>
                </form>
            </div>

            <button class="cart__hiden">
                <i class="fas fa-chevron-circle-up"></i>
            </button>
        </div>
    <?php } ?>
    <div class="flex v-center card__check">
        <div class="flex v-center check__pieces">
            <i class="fas <?=$product->availability > 0 ? 'fa-check-circle' : 'fa-times-circle'?>"></i>
            <p><?=$this->text('На складі', 0)?> <span class="pieces"><?=$product->availability?></span> шт.</p>
        </div>
        <div class="flex v-center card__rating">
            <?php if(empty($product->rating)) $product->rating = 5; ?>
            <div class="rating <?=empty($product->rating)?'empty':''?>" title="<?=empty($product->rating)?$this->text('Оцінка відсутня'):$this->text('Оцінка товару ').' '.$product->rating?>" style="color: #ffc508;">
                <?php for($i = 0; $i < round($product->rating); $i++) { ?>
                    <i class="fas fa-star" aria-hidden="true"></i>
                <?php } for($i = round($product->rating); $i < 5; $i++) { ?>
                    <i class="far fa-star" aria-hidden="true"></i>
                <?php } ?>
            </div>
        </div>
    </div>
</div>