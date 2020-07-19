<footer>
    <div class="flex wrap footer__info container">
       <div class="w20 footer__account">
           <h5>Мій акаунт</h5>
            <a href="<?=SITE_URL?>cart"><?=$this->text('Мій кошик', 0)?></a>
           <?php if($this->userIs()) { ?>
             <a href="<?=SITE_URL?>profile/<?=$_SESSION['user']->alias?>"><?=$this->text('Мої замовлення', 0)?></a>
             <a href="<?=SITE_URL?>profile/edit"><?=$this->text('Редагувати профіль', 0)?></a>
             <a href="/logout"><?=$this->text('Вийти', 0)?></a>
           <?php } else { ?>
                <a href="<?=SITE_URL?>login"><?=$this->text('Увійти', 0)?></a>
                <a href="<?=SITE_URL?>signup"><?=$this->text('Реєстрація', 0)?></a>
                <a href="<?=SITE_URL?>reset"><?=$this->text('Забув пароль', 0)?></a>
          <?php } ?>
       </div>
       <div class="w20 footer__information">
           <h5><?=$this->text('Інформація', 0)?></h5>
           <a href="<?=SITE_URL?>about"><?=$this->text('Про нас', 0)?></a>
           <a href="#">Інформація для покупця</a>
       </div>
       <div class="w20 footer__shop">
           <h5><?=$this->text('Магазин', 0)?></h5>
           <a href="<?=SITE_URL?>exchange-and-return">Повернення та гарантія</a>
          <a href="<?=SITE_URL?>delivery-and-payments">Оплата та доставка</a>
          <a href="<?=SITE_URL?>oferta">Договір оферти</a>
       </div>
       <div class="w20 footer__contacts">
            <h5><?=$this->text('Контакти', 0)?></h5>
            <div class="flex h-start v-center contacts__address">
                <i class="fas fa-map-marker-alt"></i>
                <address>
                    <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9"><?=$this->text('Львів, вул. Виговського, 49', 0)?></a>
                </address>
            </div>
            <div class="flex h-start v-center contacts__phone">
                <img src="/style/icons/telephone-5.svg" alt="telephone">
                <div>
                    <a href="tel:+380960000943">+38 096 0000 943</a><br>
                    <a href="tel:+380930000943">+38 093 0000 943</a>
                </div>
            </div>
       </div>
       <div class="w20 footer__payments">
           <h5><?=$this->text('Приймаємо платежі', 0)?></h5>
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