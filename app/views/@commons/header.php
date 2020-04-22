<header>
    <div class="flex v-center">
        <a href="<?=SITE_URL?>" class="header__link">
            <img src="style/images/logo.png" alt="">
        </a>
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
        <a href="#">Каталог запчастин</a>
        <a href="#">Виробники</a>
        <a href="#" class="active">Повернення та гарантія</a>
        <a href="#">Оплата та доставка</a>
        <a href="#">Контакти</a>
        <a href="#">Реєстрація</a>
    </nav>
</header>