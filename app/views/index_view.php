<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/slick/slick.css">
<?php $this->load->js('assets/slick/slick.min.js'); 
$this->load->js_init('init__main()'); ?>

<main>
    <h1>Запчастини для китайських автомобілів</h1>
    
    <?php if(!empty($catalogAllGroups)) { ?>
    <div class="flex w66 h-evenly v-end main__logo">
        <?php foreach ($catalogAllGroups as $group) {
            if($group->parent == 0) { ?>
                <a href="<?=SITE_URL.'parts/'.$group->alias?>" data-group="<?=$group->alias?>">
                    <?php if($group->photo) { ?>
                        <img src="<?=IMG_PATH.$group->photo?>" alt="<?=$group->name?>">
                    <?php } ?>
                    <div class="logo__text"><?=$group->name?></div>
                    <i class="fas fa-chevron-down"></i>
                </a>
        <?php } } ?>
    </div>
    <?php foreach ($catalogAllGroups as $group) {
            if($group->parent == 0) { ?>
                <section class="flex h-center wrap cars__base models__<?=$group->alias?>">
                    <?php foreach ($catalogAllGroups as $model) {
                        if($model->parent == $group->id) { ?>
                            <a href="<?=SITE_URL.'parts/'.$group->alias.'/'.$model->alias?>" class="base__detal">
                                <?php if($model->photo) { ?>
                                    <img src="<?=IMG_PATH.$model->photo?>" alt="<?=$model->name?>">
                                <?php } ?>
                                <div class="detal__text"><?=$model->name?></div>
                            </a>
                    <?php } } ?>
                </section>
        <?php } } ?>
    <?php } ?>
   
   <div class="slick__main" id="main__slick">
        <div>
            <img src="style/images/main_owl/auto1.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto2.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto3.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto4.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto5.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto6.png" alt="auto">
        </div>
    </div>
    <section class="sale">
        <div class="flex v-center sale__nav">
            <a href="#">Новинки &mdash; 5</a>
            <a href="#">Хіт продажу &mdash; 238</a>
            <a href="#">Знижки &mdash; 520</a>
            <a href="#">Обзори</a>
            <a href="#">Поради</a>
        </div>
        <div class="flex wrap sale__wrrap">
            <div class="sale__card ">
                <div class="flex h-center v-center card__img">
                    <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                </div>
                <div class="flex v-center card__rating">
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
                </div>

                <div class="card__text">
                    Амортизатор кришки 
                    багажника Cery Eastra
                </div>
                <div class="card__info">
                    <div class="w33 info__article">
                        <p>Виробник</p>
                        <p>Артикул</p>
                    </div>
                    <div class="w66 info__name">
                        <p>Fetish</p>
                        <p>B11-2915010</p>
                    </div>
                </div>
                <div class="flex v-center card__check">
                    <div class="flex v-center check__pieces">
                        <i class="fas fa-check-circle"></i>
                        <p>В наявності <span class="pieces">586</span> шт.</p>
                    </div>
                    <div class="flex check__number">
                        <span class="minus">-</span>
                        <input type="text" value="1" size="5"/>
                        <span class="plus">+</span>
                    </div>
                </div>
                <div class="flex v-center card__price">
                    <div class="price__text">2 500 ₴</div>
                    <div class="price__cart">
                        <button class="cart__buy">
                            <img src="../style/icons/shopping-cart.png" alt="">
                        </button>
                    </div>
                </div>
            </div>
            <div class="sale__card">
                <div class="flex h-center v-center card__img">
                    <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                    <i class="card__label">-50%</i>
                </div>
                <div class="flex v-center card__rating">
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
                </div>
                <div class="card__text">
                    Амортизатор кришки
                </div>
                <div class="card__info">
                    <div class="w33 info__article">
                        <p>Виробник</p>
                        <p>Артикул</p>
                    </div>
                    <div class="w66 info__name">
                        <p>Fetish</p>
                        <p>B11-2915010</p>
                    </div>
                </div>
                <div class="flex v-center card__check">
                    <div class="flex v-center check__pieces">
                        <i class="fas fa-check-circle"></i>
                        <p>В наявності <span class="pieces">586</span> шт.</p>
                    </div>
                    <div class="flex check__number">
                        <span class="minus">-</span>
                        <input type="text" value="1" size="5"/>
                        <span class="plus">+</span>
                    </div>
                </div>
                <div class="flex v-center card__price">
                    <div class="price__text">2 500 ₴</div>
                    <div class="price__cart">
                        <button class="cart__buy">
                            <img src="../style/icons/shopping-cart.png" alt="">
                        </button>
                    </div>
                </div>
            </div>
            <div class="sale__card no_availabilaty">
                <div class="flex h-center v-center card__img">
                    <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                    <i class="card__label">-50%</i>
                    <i class="new__label">new</i>
                </div>
                <div class="flex v-center card__rating">
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
                </div>
                <div class="card__text">
                    Амортизатор кришки 
                    багажника Cery Eastra
                </div>
                <div class="card__info">
                    <div class="w33 info__article">
                        <p>Виробник</p>
                        <p>Артикул</p>
                    </div>
                    <div class="w66 info__name">
                        <p>Fetish</p>
                        <p>B11-2915010</p>
                    </div>
                </div>
                <div class="flex v-center card__check">
                    <div class="flex v-center check__pieces">
                        <i class="fas fa-times-circle"></i>
                        <p>В наявності <span class="pieces">0</span> шт.</p>
                    </div>
                    <div class="flex check__number">
                        <span class="minus">-</span>
                        <input type="text" value="1" size="5"/>
                        <span class="plus">+</span>
                    </div>
                </div>
                <div class="cart__modal">
                    <h5>Зв'яжіться з нами</h5>
                    <p>і ми проінформуємо Вас про можливість замовлення та оптимальну ціну на цей товар:</p>
                    <div class="modal__name">
                        Шланг гальмівний задній Chery Eastar
                    </div>
                    <div class="modal__product">
                        B11-2915010
                    </div>
                    <div class="modal__phone">
                        <a href="tel:+380930000943">+38 093 0000 943</a>
                        <a href="tel:+380960000946">+38 096 0000 946</a>
                    </div>
                    <span>або</span>
                    <button class="modal__request">Залишити заявку</button>

                    <div class="cart__form">
                        <div class="form__name">
                            Шланг гальмівний задній Chery Eastar
                        </div>
                        <div class="form__product">
                            B11-2915010
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
                            <img src="../style/icons/telephone-2.svg" alt="telephone">
                        </button>
                    </div>
                </div>
            </div>
            <div class="sale__card">
                <div class="flex h-center v-center card__img">
                    <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                    <i class="card__label">-50%</i>
                    <i class="new__label">new</i>
                </div>
                <div class="flex v-center card__rating">
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
                </div>
                <div class="card__text">
                    Амортизатор кришки 
                    багажника Cery Eastra
                    Амортизатор кришки 
                </div>
                <div class="card__info">
                    <div class="w33 info__article">
                        <p>Виробник</p>
                        <p>Артикул</p>
                    </div>
                    <div class="w66 info__name">
                        <p>Fetish</p>
                        <p>B11-2915010</p>
                    </div>
                </div>
                <div class="flex v-center card__check">
                    <div class="flex v-center check__pieces">
                        <i class="fas fa-check-circle"></i>
                        <p>В наявності <span class="pieces">586</span> шт.</p>
                    </div>
                    <div class="flex check__number">
                        <span class="minus">-</span>
                        <input type="text" value="1" size="5"/>
                        <span class="plus">+</span>
                    </div>
                </div>
                <div class="flex v-center card__price">
                    <div class="price__text">2 500 ₴</div>
                    <div class="price__cart">
                        <button class="cart__buy">
                            <img src="../style/icons/shopping-cart.png" alt="shopping">
                        </button>
                    </div>
                </div>
            </div>
            <div class="sale__card">
                <div class="flex h-center v-center card__img">
                    <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                    <i class="new__label">new</i>
                </div>
                <div class="flex v-center card__rating">
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
                </div>
                <div class="card__text">
                    Амортизатор кришки 
                    багажника Cery Eastra
                </div>
                <div class="card__info">
                    <div class="w33 info__article">
                        <p>Виробник</p>
                        <p>Артикул</p>
                    </div>
                    <div class="w66 info__name">
                        <p>Fetish</p>
                        <p>B11-2915010</p>
                    </div>
                </div>
                <div class="flex v-center card__check">
                    <div class="flex v-center check__pieces">
                        <i class="fas fa-check-circle"></i>
                        <p>В наявності <span class="pieces">586</span> шт.</p>
                    </div>
                    <div class="flex check__number">
                        <span class="minus">-</span>
                        <input type="text" value="1" size="5"/>
                        <span class="plus">+</span>
                    </div>
                </div>
                <div class="flex v-center card__price">
                    <div class="price__text">2 500 ₴</div>
                    <div class="price__cart">
                        <button class="cart__buy">
                            <img src="../style/icons/shopping-cart.png" alt="shopping">
                        </button>
                    </div>
                </div>
            </div>
            <div class="sale__card">
                    <div class="flex h-center v-center card__img">
                        <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                        <i class="card__label">-50%</i>
                        <i class="new__label">new</i>
                    </div>
                    <div class="flex v-center card__rating">
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
                    </div>
                    <div class="card__text">
                        Амортизатор кришки 
                        багажника Cery Eastra
                    </div>
                    <div class="card__info">
                        <div class="w33 info__article">
                            <p>Виробник</p>
                            <p>Артикул</p>
                        </div>
                        <div class="w66 info__name">
                            <p>Fetish</p>
                            <p>B11-2915010</p>
                        </div>
                    </div>
                    <div class="flex v-center card__check">
                        <div class="flex v-center check__pieces">
                            <i class="fas fa-check-circle"></i>
                            <p>В наявності <span class="pieces">586</span> шт.</p>
                        </div>
                        <div class="flex check__number">
                            <span class="minus">-</span>
                            <input type="text" value="1" size="5"/>
                            <span class="plus">+</span>
                        </div>
                    </div>
                    <div class="flex v-center card__price">
                        <div class="price__text">2 500 ₴</div>
                        <div class="price__cart">
                            <button class="cart__buy">
                                <img src="../style/icons/shopping-cart.png" alt="shopping">
                            </button>
                        </div>
                    </div>
            </div>
            <div class="sale__card no_availabilaty">
                    <div class="flex h-center v-center card__img">
                        <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                        <i class="card__label">-50%</i>
                        <i class="new__label">new</i>
                    </div>
                    <div class="flex v-center card__rating">
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
                    </div>
                    <div class="card__text">
                        Амортизатор кришки 
                        багажника Cery Eastra
                    </div>
                    <div class="card__info">
                        <div class="w33 info__article">
                            <p>Виробник</p>
                            <p>Артикул</p>
                        </div>
                        <div class="w66 info__name">
                            <p>Fetish</p>
                            <p>B11-2915010</p>
                        </div>
                    </div>
                    <div class="flex v-center card__check">
                        <div class="flex v-center check__pieces">
                            <i class="fas fa-times-circle"></i>
                            <p>В наявності <span class="pieces">0</span> шт.</p>
                        </div>
                        <div class="flex check__number">
                            <span class="minus">-</span>
                            <input type="text" value="1" size="5"/>
                            <span class="plus">+</span>
                        </div>
                    </div>
                    <div class="cart__modal cart__modal-two">
                        <h5>Зв'яжіться з нами</h5>
                        <p>і ми проінформуємо Вас про можливість замовлення та оптимальну ціну на цей товар:</p>
                        <div class="modal__name">
                            Шланг гальмівний задній Chery Eastar
                        </div>
                        <div class="modal__product">
                            B11-2915010
                        </div>
                        <div class="modal__phone">
                            <a href="tel:+380930000943">+38 093 0000 943</a>
                            <a href="tel:+380960000946">+38 096 0000 946</a>
                        </div>
                        <span>або</span>
                        <button class="modal__request">Залишити заявку</button>

                        <div class="cart__form">
                            <div class="form__name">
                                Шланг гальмівний задній Chery Eastar
                            </div>
                            <div class="form__product">
                                B11-2915010
                            </div>
                            <form action="#">
                                <input required name="city" list="city__select" type="text" placeholder="Виберіть мшісто">
                                <datalist id="city__select"></datalist>
                                <input required name="#" type="text" placeholder="Ім'я">
                                <input required name="#" type="tel" placeholder="Телефон">
                                <input required name="#" type="email" placeholder="Електронна адреса">
                                <button class="form__btn">Надіслати запит</button>
                            </form>
                        </div>
                        <button class="cart__hiden cart__hiden-two">
                            <i class="fas fa-chevron-circle-up"></i>
                        </button>
                    </div>
                    <div class="flex v-center card__price">
                        <div class="price__text">Під замовлення</div>
                        <div class="price__cart">
                            <button class="cart__order">
                                <img src="../style/icons/telephone-2.svg" alt="telephone">
                            </button>
                        </div>
                    </div>
            </div>
            <div class="sale__card">
                    <div class="flex h-center v-center card__img">
                        <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                        <i class="card__label">-50%</i>
                        <i class="new__label">new</i>
                    </div>
                    <div class="flex v-center card__rating">
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
                    </div>
                    <div class="card__text">
                        Амортизатор кришки 
                        багажника Cery Eastra
                    </div>
                    <div class="card__info">
                        <div class="w33 info__article">
                            <p>Виробник</p>
                            <p>Артикул</p>
                        </div>
                        <div class="w66 info__name">
                            <p>Fetish</p>
                            <p>B11-2915010</p>
                        </div>
                    </div>
                    <div class="flex v-center card__check">
                        <div class="flex v-center check__pieces">
                            <i class="fas fa-check-circle"></i>
                            <p>В наявності <span class="pieces">586</span> шт.</p>
                        </div>
                        <div class="flex check__number">
                            <span class="minus">-</span>
                            <input type="text" value="1" size="5"/>
                            <span class="plus">+</span>
                        </div>
                    </div>
                    <div class="flex v-center card__price">
                        <div class="price__text">2 500 ₴</div>
                        <div class="price__cart">
                            <button class="cart__buy">
                                <img src="../style/icons/shopping-cart.png" alt="shopping">
                            </button>
                        </div>
                    </div>
            </div>
            <div class="sale__card">
                    <div class="flex h-center v-center card__img">
                        <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                        <i class="card__label">-50%</i>
                        <i class="new__label">new</i>
                    </div>
                    <div class="flex v-center card__rating">
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
                    </div>
                    <div class="card__text">
                        Амортизатор кришки 
                        багажника Cery Eastra
                    </div>
                    <div class="card__info">
                        <div class="w33 info__article">
                            <p>Виробник</p>
                            <p>Артикул</p>
                        </div>
                        <div class="w66 info__name">
                            <p>Fetish</p>
                            <p>B11-2915010</p>
                        </div>
                    </div>
                    <div class="flex v-center card__check">
                        <div class="flex v-center check__pieces">
                            <i class="fas fa-check-circle"></i>
                            <p>В наявності <span class="pieces">586</span> шт.</p>
                        </div>
                        <div class="flex check__number">
                            <span class="minus">-</span>
                            <input type="text" value="1" size="5"/>
                            <span class="plus">+</span>
                        </div>
                    </div>
                    <div class="flex v-center card__price">
                        <div class="price__text">2 500 ₴</div>
                        <div class="price__cart">
                            <button class="cart__buy">
                                <img src="../style/icons/shopping-cart.png" alt="shopping">
                            </button>
                        </div>
                    </div>
            </div>
            <div class="sale__card">
                    <div class="flex h-center v-center card__img">
                        <img src="../../style/images/card_img/amortizator.png" alt="amortizator">
                        <i class="card__label">-50%</i>
                        <i class="new__label">new</i>
                    </div>
                    <div class="flex v-center card__rating">
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
                    </div>
                    <div class="card__text">
                        Амортизатор кришки 
                        багажника Cery Eastra
                    </div>
                    <div class="card__info">
                        <div class="w33 info__article">
                            <p>Виробник</p>
                            <p>Артикул</p>
                        </div>
                        <div class="w66 info__name">
                            <p>Fetish</p>
                            <p>B11-2915010</p>
                        </div>
                    </div>
                    <div class="flex v-center card__check">
                        <div class="flex v-center check__pieces">
                            <i class="fas fa-check-circle"></i>
                            <p>В наявності <span class="pieces">586</span> шт.</p>
                        </div>
                        <div class="flex check__number">
                            <span class="minus">-</span>
                            <input type="text" value="1" size="5"/>
                            <span class="plus">+</span>
                        </div>
                    </div>
                    <div class="flex v-center card__price">
                        <div class="price__text">2 500 ₴</div>
                        <div class="price__cart">
                            <button class="cart__buy">
                                <img src="../style/icons/shopping-cart.png" alt="shopping">
                            </button>
                        </div>
                    </div>
            </div>


        </div>
    </section>
    
</main>