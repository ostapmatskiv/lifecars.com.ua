<header>
    <div class="flex v-center">
        <div class="nav__mobile">
            <i class="fas fa-bars"></i>
            <a href="<?=SITE_URL?>" class="header__link">
                <img class="header__logo" src="<?=SERVER_URL?>style/images/logo.png" alt="logo">
            </a>
            <div class="mobile__shoping">
                <i class="label">0</i>
                <a href="<?=SITE_URL?>cart" class="header__cart">
                    <img src="<?=SERVER_URL?>style/icons/shopping-cart-16.png" alt="cart">
                </a>
            </div>
        </div>
        <form action="<?=SITE_URL?>search">
            <?php if(!empty($catalogAllGroups)) { ?>
                <select name="group">
                    <option value="0">Весь магазин</option>
                    <?php foreach ($catalogAllGroups as $h_group) {
                        if($h_group->parent == 0) {
                            echo "<option value=\"{$h_group->id}\">{$h_group->name}</option>";
                            foreach ($catalogAllGroups as $h_model) {
                                if($h_model->parent == $h_group->id) {
                                    echo "<option value=\"{$h_model->id}\">- {$h_model->name}</option>";
                                }
                            }
                        }
                    } ?>
                </select>
                <i class="fas fa-chevron-down"></i>
            <?php } ?>
            <input required="required" type="search" name="by" placeholder="Пошук">
            <button><i class="fas fa-search"></i></button>
        </form>
        <div class="flex v-center header__address">
            <i class="fas fa-map-marker-alt"></i>
            <address>
                <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9" target="_blank">Львів, вул.<br>
                Виговського, 49</a>
            </address>
        </div>
        <div class="flex v-center header__phone">
            <i class="fas fa-phone-alt"></i>
            <div>
                <a href="tel:+380965836762">+38 096 583 67 62</a><br>
                <a href="tel:+380582359854">+38 058 235 98 54</a>
            </div>
            <div class="header__login">
                <button class="header__user-in">Увійти</button>
            </div>
         </div>
        <div class="flex header__shoping">
            <i class="label">0</i>
            <a href="<?=SITE_URL?>cart" class="header__cart">
                <img src="<?=SERVER_URL?>style/icons/shopping-cart-16.png" alt="cart">
            </a>
            <div class="header__user">
                <button>
                    <img src="<?=SERVER_URL?>style/icons/user.png" alt="user">
                </button>
                <div class="user__login">
                    <?php if($this->userIs()) { ?>
                        <a href="<?=SITE_URL?>profile" class="user__in">Мій кабінет</a>
                        <?php if($this->userCan()) { ?>
                        <a href="<?=SITE_URL?>admin" class="user__registration">Панель керування</a>
                    <?php } } else { ?>
                        <a href="<?=SITE_URL?>login" class="user__in">Увійти</a>
                        <a href="<?=SITE_URL?>signup" class="user__registration">Реєстрація</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <nav class="flex v-center">
        <a href="<?=SITE_URL?>parts" <?=($_SESSION['alias']->alias == 'parts') ? 'class="active"' : ''?>>Каталог запчастин</a>
        <a href="<?=SITE_URL?>manufacturers" <?=($_SESSION['alias']->alias == 'manufacturers') ? 'class="active"' : ''?>>Виробники</a>
        <a href="<?=SITE_URL?>exchange-and-return" <?=($_SESSION['alias']->alias == 'exchange-and-return') ? 'class="active"' : ''?>>Повернення та гарантія</a>
        <a href="<?=SITE_URL?>delivery-and-payments" <?=($_SESSION['alias']->alias == 'delivery-and-payments') ? 'class="active"' : ''?>>Оплата та доставка</a>
        <a href="<?=SITE_URL?>contacts" <?=($_SESSION['alias']->alias == 'contacts') ? 'class="active"' : ''?>>Контакти</a>
        <?php if($this->userIs()) { ?>
            <a href="<?=SITE_URL?>profile" <?=($_SESSION['alias']->alias == 'profile') ? 'class="active"' : ''?>>Мій кабінет</a>
        <?php } else { ?>
            <a href="<?=SITE_URL?>login" <?=(in_array($_SESSION['alias']->alias, ['login', 'signup'])) ? 'class="active"' : ''?>>Увійти / Реєстрація</a>
        <?php } ?>
    </nav>
    <div class="mob__menu">
        <div class="close__menu">
            <i class="fas fa-times"></i>
        </div>
        <div class="mob__nav">
            <a href="<?=SITE_URL?>">
                <img class="mob__logo" src="/style/images/logo.png" alt="logo">
            </a>
            <a href="<?=SITE_URL?>login">Увійти</a>
            <a href="<?=SITE_URL?>signup">Реєстрація</a>
            <div class="mob__border"></div>
            <a href="<?=SITE_URL?>parts" <?=($_SESSION['alias']->alias == 'parts') ? 'class="active"' : ''?>>Каталог запчастин</a>
            <a href="<?=SITE_URL?>manufacturers" <?=($_SESSION['alias']->alias == 'manufacturers') ? 'class="active"' : ''?>>Виробники</a>
            <a href="<?=SITE_URL?>exchange-and-return" <?=($_SESSION['alias']->alias == 'exchange-and-return') ? 'class="active"' : ''?>>Повернення та гарантія</a>
            <a href="<?=SITE_URL?>delivery-and-payments" <?=($_SESSION['alias']->alias == 'delivery-and-payments') ? 'class="active"' : ''?>>Оплата та доставка</a>
            <a href="<?=SITE_URL?>contacts" <?=($_SESSION['alias']->alias == 'contacts') ? 'class="active"' : ''?>>Контакти</a>
            <div class="mob__border"></div>
            <a href="<?=SITE_URL?>cart">Мій кошик</a>
            <a href="#">Оформлення замовлення</a>
            <a href="<?=SITE_URL?>about">Про нас</a>
        </div>
    </div>
</header>