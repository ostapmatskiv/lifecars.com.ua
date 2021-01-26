<div class="flex v-center" id="top_header">
    <div class="header__address">
        <?php /* <i class="fas fa-map-marker-alt"></i>
        <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9" target="_blank">Львів, вул. Виговського, 49</a> */ ?>
    </div>
    <div class="m-flex m100">
        <div class="language m-m0">
            <a href="<?=SITE_URL_UK?>" <?=$_SESSION['language'] == 'uk' ? 'class="active"' : ''?>>UA</a> | <a href="<?=SITE_URL_RU?>" <?=$_SESSION['language'] == 'ru' ? 'class="active"' : ''?>>RU</a>
        </div>
        <div class="profile">
            <?php if($this->userIs()) { ?>
                <?=$this->text('Вітаємо', 0)?>, 
                <a href="<?=SITE_URL?>profile/<?=$_SESSION['user']->alias?>" class="active" title="Мій кабінет">
                    <img src="<?=SERVER_URL?>style/icons/user.png" alt="user">
                    <?=$_SESSION['user']->name?>
                </a>
                <?php if($this->userCan()) { ?>
                    <a href="/admin" class="admin">Admin</a>
                <?php } ?>
                <a href="/logout"><?=$this->text('Вийти', 0)?></a>
            <?php } else { ?>
                <a href="<?=SITE_URL?>login"><?=$this->text('Увійти', 0)?></a>
                <a href="<?=SITE_URL?>signup"><?=$this->text('Реєстрація', 0)?></a>
            <?php } ?>
        </div>
    </div>
</div>
<header>
    <div class="flex v-center">
        <div class="nav__mobile">
            <i class="fas fa-bars"></i>
            <a href="<?=SITE_URL?>" class="header__link">
                <img class="header__logo" src="<?=SERVER_URL?>style/images/logo.png" alt="logo">
            </a>
            <div class="mobile__shoping">
                <i class="label __CountProductsInCart">0</i>
                <a href="<?=SITE_URL?>cart" class="header__cart">
                    <img src="<?=SERVER_URL?>style/icons/shopping-cart-16.png" alt="cart">
                </a>
            </div>
        </div>
        <form action="<?=SITE_URL?>parts/search">
            <?php if(!empty($catalogAllGroups)) {
                $selected_id = $this->data->get('group');
                if(isset($group) && is_object($group))
                    $selected_id = $group->id;
                ?>
                <select id="carMobileGroup">
                    <option value="0"><?=$this->text('Всі авто', 0)?></option>
                    <?php foreach ($catalogAllGroups as $h_group) {
                        if($h_group->parent == 0) {
                            $selected = $selected_id == $h_group->id ? 'selected' : '';
                            echo "<option value=\"{$h_group->id}\" {$selected}>{$h_group->name}</option>";
                        }
                    } ?>
                </select>
                <select name="group">
                    <option value="0"><?=$this->text('Де шукати?', 0)?></option>
                    <?php foreach ($catalogAllGroups as $h_group) {
                        if($h_group->parent == 0) {
                            $selected = $selected_id == $h_group->id ? 'selected' : '';
                            echo "<option value=\"{$h_group->id}\" {$selected}>{$h_group->name}</option>";
                            foreach ($catalogAllGroups as $h_model) {
                                if($h_model->parent == $h_group->id) {
                                    $selected = $selected_id == $h_model->id ? 'selected' : '';
                                    echo "<option value=\"{$h_model->id}\" {$selected}>- {$h_model->name}</option>";
                                }
                            }
                        }
                    } ?>
                </select>
                <i class="fas fa-chevron-down"></i>
            <?php } ?>
            <input required="required" type="search" name="name" value="<?=$this->data->get('name')?>" placeholder="<?=$this->text('Пошук за артикулом або назвою товару', 0)?>">
            <button><i class="fas fa-search"></i></button>
        </form>
        <div class="flex v-center header__address">
            <i class="fas fa-map-marker-alt"></i>
            <address>
                <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9" target="_blank"><?=$this->text('Львів, вул.', 0)?><br>
                <?=$this->text('Виговського, 49', 0)?></a>
            </address>
        </div>
        <div class="flex v-center header__phone">
            <i class="fas fa-phone-alt"></i>
            <div>
                <a href="tel:<?=str_replace(' ', '', $site_tel_1)?>"><?=$site_tel_1?></a><br>
                <a href="tel:<?=str_replace(' ', '', $site_tel_2)?>"><?=$site_tel_2?></a>
            </div>
         </div>
        <div class="flex header__shoping">
            <i class="label __CountProductsInCart">0</i>
            <a href="<?=SITE_URL?>cart" class="header__cart">
                <img src="<?=SERVER_URL?>style/icons/shopping-cart-16.png" alt="cart">
            </a>
            <?php /* if($this->userIs()) { ?>
                <a href="<?=SITE_URL?>profile" class="header__user" title="<?=$this->text('Мій кабінет', 0)?>">
                    <img src="<?=SERVER_URL?>style/icons/user.png" alt="user">
                </a>
            <?php } else { ?>
                <a href="<?=SITE_URL?>signup" class="header__user" title="<?=$this->text('Реєстрація', 0)?>">
                    <img src="<?=SERVER_URL?>style/icons/user.png" alt="user">
                </a>
            <?php } */ ?>
        </div>
    </div>
    <nav class="flex v-center">
        <a href="<?=SITE_URL?>parts" <?=($_SESSION['alias']->alias == 'parts') ? 'class="active"' : ''?>><?=$this->text('Каталог запчастин', 0)?></a>
        <a href="<?=SITE_URL?>manufacturers" <?=($_SESSION['alias']->alias == 'manufacturers') ? 'class="active"' : ''?>><?=$this->text('Виробники', 0)?></a>
        <a href="<?=SITE_URL?>exchange-and-return" <?=($_SESSION['alias']->alias == 'exchange-and-return') ? 'class="active"' : ''?>><?=$this->text('Повернення та гарантія', 0)?></a>
        <a href="<?=SITE_URL?>delivery-and-payments" <?=($_SESSION['alias']->alias == 'delivery-and-payments') ? 'class="active"' : ''?>><?=$this->text('Оплата та доставка', 0)?></a>
        <a href="<?=SITE_URL?>contacts" <?=($_SESSION['alias']->alias == 'contacts') ? 'class="active"' : ''?>><?=$this->text('Контакти', 0)?></a>
        <?php /* if($this->userIs()) { if($this->userCan()) { ?>
            <a href="<?=SITE_URL?>admin">Admin</a>
        <?php } else { ?>
            <a href="<?=SITE_URL?>profile" <?=($_SESSION['alias']->alias == 'profile') ? 'class="active"' : ''?>><?=$this->text('Мій кабінет', 0)?></a>
        <?php } } else { ?>
            <a href="<?=SITE_URL?>login" <?=(in_array($_SESSION['alias']->alias, ['login', 'signup'])) ? 'class="active"' : ''?>><?=$this->text('Увійти / Реєстрація', 0)?></a>
        <?php } */ ?>
    </nav>
    <div class="mob__menu">
        <div class="close__menu">
            <i class="fas fa-times"></i>
        </div>
        <div class="mob__nav">
            <a href="<?=SITE_URL?>">
                <img class="mob__logo" src="/style/images/logo.png" alt="logo">
            </a>
            <?php if($this->userIs()) { ?>
                <a href="<?=SITE_URL?>profile/<?=$_SESSION['user']->alias?>" title="<?=$this->text('Мій кабінет', 0)?>">
                    <img src="<?=SERVER_URL?>style/icons/user.png" alt="user">
                    <?=$_SESSION['user']->name?>
                </a>
                <?php if($this->userCan()) { ?>
                    <a href="/admin" class="admin">Admin</a>
                <?php } ?>
                <a href="<?=SITE_URL?>profile/edit"><?=$this->text('Редагувати профіль', 0)?></a>
                <a href="/logout"><?=$this->text('Вийти', 0)?></a>
            <?php } else { ?>
                <a href="<?=SITE_URL?>login"><?=$this->text('Увійти', 0)?></a>
                <a href="<?=SITE_URL?>signup"><?=$this->text('Реєстрація', 0)?></a>
            <?php } ?>
            <div class="mob__border"></div>
            <a href="<?=SITE_URL?>parts" <?=($_SESSION['alias']->alias == 'parts') ? 'class="active"' : ''?>><?=$this->text('Каталог запчастин', 0)?></a>
            <a href="<?=SITE_URL?>manufacturers" <?=($_SESSION['alias']->alias == 'manufacturers') ? 'class="active"' : ''?>><?=$this->text('Виробники', 0)?></a>
            <a href="<?=SITE_URL?>exchange-and-return" <?=($_SESSION['alias']->alias == 'exchange-and-return') ? 'class="active"' : ''?>><?=$this->text('Повернення та гарантія', 0)?></a>
            <a href="<?=SITE_URL?>delivery-and-payments" <?=($_SESSION['alias']->alias == 'delivery-and-payments') ? 'class="active"' : ''?>><?=$this->text('Оплата та доставка', 0)?></a>
            <a href="<?=SITE_URL?>contacts" <?=($_SESSION['alias']->alias == 'contacts') ? 'class="active"' : ''?>><?=$this->text('Контакти', 0)?></a>
            <div class="mob__border"></div>
            <a href="<?=SITE_URL?>cart"><?=$this->text('Мій кошик', 0)?></a>
            <?php if (!empty($_SESSION['cart'])) { ?>
                <a href="<?=SITE_URL?>cart/checkout"><?=$this->text('Оформлення замовлення', 0)?></a>
            <?php } ?>
            <a href="<?=SITE_URL?>about"><?=$this->text('Про нас', 0)?></a>
        </div>
    </div>
</header>