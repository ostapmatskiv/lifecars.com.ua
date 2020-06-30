<footer>
    <div class="flex wrap footer__info container">
       <div class="w20 footer__account">
           <h5>Мій акаунт</h5>
           <a href="#">Мій кошик</a>
           <a href="#">Оформлення замовлення</a>
           <a href="#">Вхід</a>
       </div>
       <div class="w20 footer__information">
           <h5>Інформація</h5>
           <a href="#">Про нас</a>
           <a href="#">Інформація для покупця</a>
       </div>
       <div class="w20 footer__shop">
           <h5>Магазин</h5>
           <a href="<?=SITE_URL?>exchange-and-return">Повернення та гарантія</a>
          <a href="<?=SITE_URL?>delivery-and-payments">Оплата та доставка</a>          <a href="<?=SITE_URL?>oferta">Договір оферти</a>
       </div>
       <div class="w20 footer__contacts">
            <h5>Контакти</h5>
            <div class="flex h-start v-center contacts__address">
                <i class="fas fa-map-marker-alt"></i>
                <address>
                    <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9">Львів, вул. Виговського, 49</a>
                </address>
            </div>
            <div class="flex h-start v-center contacts__phone">
                <img src="/style/icons/telephone-5.svg" alt="telephone">
                <div>
                    <a href="tel:+380965836762">+38 096 583 67 62</a><br>
                    <a href="tel:+380582359854">+38 058 235 98 54</a>
                </div>
            </div>
       </div>
       <div class="w20 footer__payments">
           <h5>Приймаємо платежі</h5>
           <div class="payments__img">
                <img src="/style/icons/privat.svg" alt="privatbank">
                <img src="/style/icons/master.svg" alt="mastercard">
                <img src="/style/icons/visa.png" alt="visa">
                <img src="/style/icons/right.svg" alt="right">
            </div>
       </div>
       
    </div>
    <div class="footer__subtitle">
    <div class="flex wrap container">
        <div class="w50 subtitle__text">
         <p>&#169; Life Cars <?=date('Y')?> <span>Всі права захищені</span></p>
        </div>
        <div class="flex h-start v-center w50 subtitle__link">
            <img class="webspirit__img" src="/style/icons/WebSpirit_logo_mini.png" alt="webspirit">
            <p>Розробка сайту: <a href="https://webspirit.com.ua/">WebSpirit</a> creative agensy</p>
            <img class="genetka" src="/style/icons/genekta.png" alt="genekta">
            <p>Дизайн сайту: <a href="#">Genekta</a> visual communication studio</p>
        </div>
    </div>
</div>
</footer>