<pre><?php // print_r($filters); ?></pre>
<main>
    <div class="flex w50 auto__detal">
    	<?php $brend_id = $model_id = 0; $brend_link = 'parts';
    	if($group->parent == 0)
    		$brend_id = $group->id;
    	else
    	{
    		$brend_id = $group->parent;
    		$model_id = $group->id;
    	}
    	foreach ($catalogAllGroups as $g) {
    		if($g->id == $brend_id) { ?>
    			<a class="cars__model" href="#" data-group="brends">
		            <?php if($g->photo) { ?>
                        <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
                    <?php } ?>
		            <div class="detal__text"><?=$g->name?></div>
		            <i class="fas fa-chevron-down"></i>
		        </a>
    		<?php $brend_link = $g->link;
    		break; }
    	}
    	if($model_id == 0) { ?>        
	        <a class="cars__model" href="#" data-group="models">
	            <img src="/style/images/carslogo/cars.png" alt="car">
	            <div class="detal__text">Всі моделі</div>
	            <i class="fas fa-chevron-down"></i>
	        </a>
	    <?php } else { foreach ($catalogAllGroups as $g) {
    		if($g->id == $model_id) { ?>
    			<a class="cars__model" href="#" data-group="models">
    				<?php if($g->photo) { ?>
                        <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
                    <?php } ?>
		            <div class="detal__text"><?=$g->name?></div>
		            <i class="fas fa-chevron-down"></i>
		        </a>
    		<?php break; }
    	} } ?>
    </div>
    <div class="flex w66 h-evenly v-end main__logo logo__catalog brends__cars">
    	<?php $this->load->js_init('init__parts()');
		foreach ($catalogAllGroups as $g) { if($g->parent == 0) { ?>
			<a href="<?=SITE_URL.$g->link?>">
				<?php if($g->photo) { ?>
	            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
	            <?php } ?>
	            <div class="logo__text"><?=$g->name?></div>
	        </a>
		<?php } } ?>
    </div>
    <section class="flex h-center wrap cars__catalog models__cars">
    	<a href="<?=SITE_URL.$brend_link?>" class="base__detal">
			<img src="/style/images/carslogo/cars.png" alt="car">
            <div class="detal__text">Всі моделі</div>
        </a>
    	<?php foreach ($catalogAllGroups as $g) { if($g->parent == $brend_id) { ?>
			<a href="<?=SITE_URL.$g->link?>" class="base__detal">
				<?php if($g->photo) { ?>
	            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
	            <?php } ?>
	            <div class="detal__text"><?=$g->name?></div>
	        </a>
		<?php } } ?>
    </section>
   
	<?php if(!empty($filters))
    	foreach ($filters as $filter) {
    		if($filter->id == 2) { ?>
    		<div class="flex h-center wrap catalog__detal">
                <a href="<?=$this->data->get_link($filter->alias)?>">
                    <img src="/style/icons/catalog/component.svg" alt="all">
                    <div><?=$this->text('Всі запчастини')?></div>
                </a>
    			<?php foreach ($filter->values as $value) { ?>
    				<a href="<?=$this->data->get_link($filter->alias, $value->id)?>">
    					<?php if(!empty($value->photo)) { ?>
				            <img src="<?=$value->photo?>" alt="<?=$value->name?>">
				        <?php } ?>
			            <div><?=$value->name?></div>
			        </a>
            	<?php } ?>
            </div>
    <?php } }

    /* ?>
    <div class="flex h-evenly catalog__info">
        <a href="#">Блок цилиндров</a>
        <a href="#">Головка блока цилиндров</a>
        <a href="#">Подушки и кронштейны двинателя</a>
        <a href="#">Ремни и ролики двигателя</a>
        <a href="#">Сальники и прокладки</a>
    </div> */ ?>
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
    
    <section class="flex sale__catalog">
        <aside class="w25">
            <form>
            	<?php /*
                <div class="product__type">
                    <div class="flex v-center">
                        <p>Тип товару</p>
                        <i class="fas fa-chevron-circle-up"></i>
                    </div>
                    <form action="#">
                        <input type="checkbox" name="type" id="type__id-1">
                        <label for="type__id-1">Знижка<span>2 345</span></label>
                        <input type="checkbox" name="type" id="type__id-2">
                        <label for="type__id-2">Новинка <span>346</span></label>
                        <input type="checkbox" name="type" id="type__id-3">
                        <label for="type__id-3">Хіт продажу<span>5 687</span></label>
                    </form>
                </div>*/ 
                if($value = $this->data->get('sort'))
                    echo "<input type='hidden' name='sort' value='{$value}' >";
                if(!empty($filters))
                	foreach ($filters as $filter) {
                		if($filter->id == 2)
                        {
                            if($value = $this->data->get($filter->alias))
                                echo "<input type='hidden' name='{$filter->alias}' value='{$value}' >";
                			continue;
                        } ?>
    		            <div class="filter">
    		                <div class="flex v-center">
    		                    <p><?=$filter->name?></p>
    		                    <!-- <i class="fas fa-chevron-circle-up"></i> -->
    		                </div>
    		                <div class="values">
    		                	<?php foreach ($filter->values as $value) {
                                    $checked = (!empty($_GET[$filter->alias]) && is_array($_GET[$filter->alias]) && in_array($value->id, $_GET[$filter->alias])) ? 'checked' : '';
                                    ?>
    		                		<input type="checkbox" name="<?=$filter->alias?>[]" value="<?=$value->id?>" id="value__id-<?=$value->id?>" <?=$checked?>>
    		                    	<label for="value__id-<?=$value->id?>"><?=$value->name?><span><?=$value->count?></span></label>
    		                	<?php } ?>
                            </div>
    		            </div>
    		    <?php } ?>
            </form>
        </aside>
        <div class="flex w75 wrap sale__wrrap">
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
        </div>
        
    </section>
    <div class="flex h-center v-center w50 pagination">
        <button>
           <img src="style/icons/catalog/back-left.svg" alt="left">
        </button>
            <a href="">1</a>
            <a href="">486</a>
            <a href="">487</a>
            <a href="">488</a>
            <a href="">2546</a>
        <button>
           <img src="style/icons/catalog/back-right.svg" alt="right">
        </button>
    </div>


</main>











<section class="section products-grid second-style">
	<div class="container">
		<div class="row">

			<div class="col-md-<?= $subgroups ? '9' : '12'?>">
				<div class="row"  id="productRow">
					<?php if($products) { foreach ($products as $product) { ?>
					<div class="product col-md-4 col-sm-6 col-xs-12">
						<div class="inner-product">
		                    <?php if($product->photo != '') { ?>
		                    <div class="product-thumbnail">
		                          <a href="<?=SITE_URL.$product->link?>"><img src="<?=IMG_PATH.$product->catalog_photo?>" class="img-responsive" alt="<?=$product->name?>">
		                          <?php if($product->old_price != '0'){ ?>
		                          	<img src="<?=IMG_PATH?>sale.png" class="saleclass" alt="АКЦІЯ!">
		                          <?php } ?>
		                          </a>
		                    </div>
		                    <?php } ?>
		                    <div class="product-details text-center">
		                        <div class="product-btns">
		                            <span data-toggle="tooltip" data-placement="top" title="<?=$this->text('Переглянути',0)?>">
		                                <a href="<?=SITE_URL.$product->link?>" class="li-icon view-details"><i class="lil-search"></i></a>
		                            </span>
		                        </div>
		                    </div>
		                </div>
		                <h3 class="product-title"><a href="<?=SITE_URL.$product->link?>" title="<?= str_replace($product->article, '', $product->name)?>" ><?= $product->list ?></a></h3>
		                <p class="product-price">
		                    <?php if($product->old_price != 0) { ?>
		                    <del>
		                        <span class="amount"><?=$product->old_price?> грн </span>
		                    </del>
		                    <?php } ?>
		                    <ins>
		                        <span class="amount"><?=$product->price?> грн </span>
		                    </ins>
		                </p>
					</div>
					<?php } } ?>
				</div>
 				
				<?php  if(isset($_SESSION['option']->paginator_total) && $_SESSION['option']->paginator_total > 12) {?>
				<div class="clearfix text-center">
					<button class="btn btn-default" onclick="showMore(2, <?= isset($subGroupsForAjax) ? '['.implode(',', $subGroupsForAjax).']' : $group->id ?>, '<?=(isset($_GET['sort'])) ? $_GET['sort'] : ''?>')" id="showMore">
			            <?=$this->text('Показати наступні 12 товарів',0)?>
			        </button>
				</div>
		        <?php } ?>

			</div>

		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.products-grid -->
<section>
    <div class="container">
        <div class="row">
            <h4><?=$_SESSION['alias']->list?></h4>
            <p><?=html_entity_decode($_SESSION['alias']->text)?></p>
        </div>
    </div>
</section>