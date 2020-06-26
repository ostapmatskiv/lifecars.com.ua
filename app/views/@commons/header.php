<header>
    <div class="flex v-center">
        <div class="nav__mobile">
            <i class="fas fa-bars"></i>
            <a href="<?=SITE_URL?>" class="header__link">
                <img class="header__logo" src="style/images/logo.png" alt="logo">
            </a>
            <div class="mobile__shoping">
                <i class="label">238</i>
                <a href="#" class="header__cart">
                    <img src="../style/icons/shopping-cart-16.png" alt="cart">
                </a>
            </div>
        </div>
        <form>
            <select>
                <option value="t1">Категорії</option>
                <option value="t2">Категорії</option>
                <option value="t3">Категорії</option>
                <option value="t4">Категорії</option>
            </select>
            <i class="fas fa-chevron-down"></i>
            <input required="required" type="search" placeholder="Пошук">
            <button><i class="fas fa-search"></i></button>
        </form>
        <div class="flex v-center header__address">
            <i class="fas fa-map-marker-alt"></i>
            <address>
                <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9">Львів, вул.<br>
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
                <i class="label">238</i>
            <a href="#" class="header__cart">
                <img src="../style/icons/shopping-cart-16.png" alt="cart">
            </a>
            <div class="header__user">
                <button>
                    <img src="../style/icons/user.png" alt="user">
                </button>
                <div class="user__login">
                    <button class="user__in">Увійти</button>
                    <button class="user__registration">Реєстрація</button>
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
        <a href="<?=SITE_URL?>login" <?=(in_array($_SESSION['alias']->alias, ['login', 'signup'])) ? 'class="active"' : ''?>>Увійти / Реєстрація</a>
    </nav>
    <div class="mob__menu">
        <div class="close__menu">
            <i class="fas fa-times"></i>
        </div>
        <div class="mob__nav">
            <a href="<?=SITE_URL?>">
                <img class="mob__logo" src="style/images/logo.png" alt="logo">
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
            <a href="#">Мій кошик</a>
            <a href="#">Оформлення замовлення</a>
            <a href="#">Про нас</a>
        </div>
    </div>
</header>