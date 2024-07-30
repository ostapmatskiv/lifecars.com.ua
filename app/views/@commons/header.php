<div class="flex v-center" id="top_header">
   <div class="header__address">
      <?php /* <i class="fas fa-map-marker-alt"></i>
        <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9" target="_blank">Львів, вул. Виговського, 49</a> */ ?>
   </div>
   <div class="m100 m-hide">
      <div class="language m-m0">
         <a href="<?= SITE_URL_UK ?>" <?= $_SESSION['language'] == 'uk' ? 'class="active"' : '' ?>>UA</a> | <a
               href="<?= SITE_URL_RU ?>" <?= $_SESSION['language'] == 'ru' ? 'class="active"' : '' ?>>RU</a>
      </div>
      <div class="profile">
         <?php if ($this->userIs()) { ?>
            <?= $this->text('Вітаємо', 0) ?>,
            <a href="<?= SITE_URL ?>profile/<?= $_SESSION['user']->alias ?>" class="active" title="Мій кабінет">
               <img src="<?= SERVER_URL ?>style/icons/user.png" alt="user">
               <?= $_SESSION['user']->name ?>
            </a>
            <?php if ($this->userCan()) { ?>
               <a href="/admin" class="admin">Admin</a>
            <?php } ?>
            <a href="/logout"><?= $this->text('Вийти', 0) ?></a>
         <?php } else { ?>
            <a href="<?= SITE_URL ?>login"><?= $this->text('Увійти', 0) ?></a>
            <a href="<?= SITE_URL ?>signup"><?= $this->text('Реєстрація', 0) ?></a>
         <?php } ?>
      </div>
   </div>
</div>
<header>
   <div class="flex v-center">
      <div class="nav__mobile">
         <i class="fas fa-bars"></i>
         <a href="<?= SITE_URL ?>" class="header__link flex v-center">
            <img class="header__logo" src="<?= SERVER_URL ?>style/images/logo.png" alt="logo">
         </a>
         <div class="mobile__shoping">
            <i class="label __CountProductsInCart m-hide">0</i>
            <a href="<?= SITE_URL ?>cart" class="header__cart">
               <img src="<?= SERVER_URL ?>style/icons/shopping-cart.svg" alt="cart">
            </a>
         </div>
      </div>
      <form id="main-search" action="<?= SITE_URL ?>parts/search">
         <?php if (!empty($catalogAllGroups)) {
            $selected_id = $this->data->get('group');
            $selected_parent_id = 0;
            if (isset($group) && is_object($group)) {
               $selected_id = $group->id;
               $selected_parent_id = $group->parent == 0 ? $group->id : $group->parent;
            }
            ?>
            <div class="m-hide">
               <select id="carMobileGroup">
                  <option value="0"><?= $this->text('Всі авто', 0) ?></option>
                  <?php foreach ($catalogAllGroups as $h_group) {
                     if ($h_group->parent == 0) {
                        $selected = $selected_parent_id == $h_group->id ? 'selected' : '';
                        echo "<option value=\"{$h_group->id}\" {$selected}>{$h_group->name}</option>";
                     }
                  } ?>
               </select>
               <i class="fas fa-chevron-down"></i>
               <select name="group" id="modelMobileGroup">
                  <option value="0"><?= $this->text('Всі моделі', 0) ?></option>
                  <?php foreach ($catalogAllGroups as $h_group) {
                     if ($h_group->parent == 0) {
                        $selected = $selected_id == $h_group->id ? 'selected' : ' class="m-hide"';
                        echo "<option value=\"{$h_group->id}\" {$selected}>{$h_group->name}</option>";
                        foreach ($catalogAllGroups as $h_model) {
                           if ($h_model->parent == $h_group->id) {
                              $selected = $selected_id == $h_model->id ? 'selected' : '';
                              $class = $selected_parent_id == $h_model->parent ? '' : 'class="m-hide"';
                              echo "<option value=\"{$h_model->id}\" data-parent=\"{$h_model->parent}\" {$selected} {$class}>- {$h_model->name}</option>";
                           }
                        }
                     }
                  } ?>
               </select>
               <i class="fas fa-chevron-down"></i>
            </div>
         <?php } ?>
         <div class="search-btn">
            <input required="required" type="text" name="name" value="<?= $this->data->get('name') ?>"
                   placeholder="<?= $this->text('Я шукаю...', 0) ?>">
            <button class="m-hide"><i class="fas fa-search"></i></button>
            <button class="t-show hide"><?= $this->text('Знайти', 0) ?></button>
         </div>
      </form>
      <div class="flex v-center header__address">
         <i class="fas fa-map-marker-alt"></i>
         <address>
            <a href="https://goo.gl/maps/ZVp3Y1JQkubKnLBR9" target="_blank"><?= $this->text('Львів, вул.', 0) ?><br>
               <?= $this->text('Виговського, 49', 0) ?></a>
         </address>
      </div>
      <div class="flex v-center header__phone m-hide">
         <i class="fas fa-phone-alt"></i>
         <div>
            <a href="tel:<?= str_replace(' ', '', $site_tel_1) ?>"><?= $site_tel_1 ?></a><br>
            <a href="tel:<?= str_replace(' ', '', $site_tel_2) ?>"><?= $site_tel_2 ?></a>
         </div>
      </div>
      <div class="flex header__shoping">
         <i class="label __CountProductsInCart">0</i>
         <a href="<?= SITE_URL ?>cart" class="header__cart">
            <img src="<?= SERVER_URL ?>/style/icons/shopping-cart.svg" alt="cart">
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
      <a href="<?= SITE_URL ?>parts" <?= ($_SESSION['alias']->alias == 'parts') ? 'class="active"' : '' ?>><?= $this->text('Каталог запчастин', 0) ?></a>
      <a href="<?= SITE_URL ?>manufacturers" <?= ($_SESSION['alias']->alias == 'manufacturers') ? 'class="active"' : '' ?>><?= $this->text('Виробники', 0) ?></a>
      <a href="<?= SITE_URL ?>exchange-and-return" <?= ($_SESSION['alias']->alias == 'exchange-and-return') ? 'class="active"' : '' ?>><?= $this->text('Повернення та гарантія', 0) ?></a>
      <a href="<?= SITE_URL ?>delivery-and-payments" <?= ($_SESSION['alias']->alias == 'delivery-and-payments') ? 'class="active"' : '' ?>><?= $this->text('Оплата та доставка', 0) ?></a>
      <a href="<?= SITE_URL ?>contacts" <?= ($_SESSION['alias']->alias == 'contacts') ? 'class="active"' : '' ?>><?= $this->text('Контакти', 0) ?></a>
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
         <i class="fa fa-times"></i>
      </div>
      <div class="mob__nav">
         <a href="<?= SITE_URL ?>">
            <img class="mob__logo" src="<?= SITE_URL ?>/style/images/logo.png" alt="logo">
         </a>
         <div class="lang-wrapper">
            <div class="language m-m0">
               <a href="<?= SITE_URL_UK ?>" <?= $_SESSION['language'] == 'uk' ? 'class="active"' : '' ?>>UA</a> | <a
                     href="<?= SITE_URL_RU ?>" <?= $_SESSION['language'] == 'ru' ? 'class="active"' : '' ?>>RU</a>
            </div>
         </div>
         <?php if ($this->userIs()) { ?>
            <a href="<?= SITE_URL ?>profile/<?= $_SESSION['user']->alias ?>"
               title="<?= $this->text('Мій кабінет', 0) ?>" class="logged-user">
               <svg viewBox="0 0 24 24">
                  <path fill="#7DBE4A"
                        d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z"/>
               </svg>
               <div>
                  <div><?= $_SESSION['user']->name ?></div>
                  <div><?= $_SESSION['user']->email ?></div>
               </div>
            </a>
            <?php if ($this->userCan()) { ?>
               <a href="/admin" class="admin">Admin</a>
            <?php } ?>
            <a href="<?= SITE_URL ?>profile/edit" class="pl-45"><?= $this->text('Редагувати профіль', 0) ?></a>
            <a href="/logout" class="pl-45"><?= $this->text('Вийти', 0) ?></a>
         <?php } else { ?>
            <div class="login-wrapper">
               <svg viewBox="0 0 24 24">
                  <path fill="#7DBE4A"
                        d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M12,13C14.67,13 20,14.33 20,17V20H4V17C4,14.33 9.33,13 12,13M12,14.9C9.03,14.9 5.9,16.36 5.9,17V18.1H18.1V17C18.1,16.36 14.97,14.9 12,14.9Z"/>
               </svg>
               <div class="inner-wrapper">
                  <a href="<?= SITE_URL ?>login"><?= $this->text('Вхід', 0) ?></a>
                  <a href="<?= SITE_URL ?>signup"><?= $this->text('Реєстрація', 0) ?></a>
               </div>
            </div>
         <?php } ?>
         <?php if (!empty($catalogAllGroups)) { ?>
            <div class="mob__border"></div>
            <a href="#" class="icon-wrapper tab">
               <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                  <path fill="#7DBE4A" d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z"/>
               </svg><?= $this->text('Каталог товарів', 0) ?> <i class="fas fa-chevron-down"></i></a>
            <div class="main__logo-wrapper">
               <div class="main__logo m-m0 tab-item category-mobile-menu">
                  <div class="flex w50 m100 h-evenly v-end m-wrap m-h-between">
                     <?php foreach ($catalogAllGroups as $group) {
                        if ($group->parent == 0) { ?>
                           <a href="<?= SITE_URL . 'parts/' . $group->alias ?>" data-group="<?= $group->alias ?>">
                              <div class="img-wrap">
                                 <?php if ($group->photo) { ?>
                                    <img src="<?= IMG_PATH ?><?= $_SESSION['alias']->alias == 'parts' ? $group->photo : 'parts/-' . $group->id . '/thumb_' . $group->photo ?>"
                                         alt="<?= $group->name ?>">
                                 <?php } ?>
                              </div>
                              <div class="logo__text"><?= $group->name ?></div>
                              <i class="fas fa-chevron-down"></i>
                           </a>
                        <?php }
                     } ?>

                     <?php foreach ($catalogAllGroups as $group) {
                        if ($group->parent == 0) { ?>
                           <section class="flex h-center wrap cars__base models__<?= $group->alias ?>">
                              <?php foreach ($catalogAllGroups as $model) {
                                 if ($model->parent == $group->id) { ?>
                                    <a href="<?= SITE_URL . 'parts/' . $group->alias . '/' . $model->alias ?>"
                                       class="base__detal">
                                       <?php if ($model->photo) { ?>
                                          <img src="<?= IMG_PATH ?><?= $_SESSION['alias']->alias == 'parts' ? $model->photo : 'parts/-' . $model->id . '/thumb_' . $model->photo ?>"
                                               alt="<?= $model->name ?>">
                                       <?php } ?>
                                       <div class="detal__text"><?= $model->name ?></div>
                                    </a>
                                 <?php }
                              } ?>
                           </section>
                        <?php }
                     } ?>
                  </div>
               </div>
            </div>
         <?php } ?>
         <div class="mob__border"></div>
         <?php if ($this->userIs()) { ?>
            <a href="<?= SITE_URL ?>#" class="icon-wrapper">
               <svg viewBox="0 0 24 24">
                  <path fill="#7DBE4A"
                        d="M3 5V19H20V5H3M7 7V9H5V7H7M5 13V11H7V13H5M5 15H7V17H5V15M18 17H9V15H18V17M18 13H9V11H18V13M18 9H9V7H18V9Z"/>
               </svg><?= $this->text('Мої замовлення', 0) ?>
            </a>
         <?php } ?>
         <a href="<?= SITE_URL ?>cart" class="icon-wrapper">
            <svg viewBox="0 0 24 24">
               <path fill="#7DBE4A"
                     d="M17,18A2,2 0 0,1 19,20A2,2 0 0,1 17,22C15.89,22 15,21.1 15,20C15,18.89 15.89,18 17,18M1,2H4.27L5.21,4H20A1,1 0 0,1 21,5C21,5.17 20.95,5.34 20.88,5.5L17.3,11.97C16.96,12.58 16.3,13 15.55,13H8.1L7.2,14.63L7.17,14.75A0.25,0.25 0 0,0 7.42,15H19V17H7C5.89,17 5,16.1 5,15C5,14.65 5.09,14.32 5.24,14.04L6.6,11.59L3,4H1V2M7,18A2,2 0 0,1 9,20A2,2 0 0,1 7,22C5.89,22 5,21.1 5,20C5,18.89 5.89,18 7,18M16,11L18.78,6H6.14L8.5,11H16Z"/>
            </svg><?= $this->text('Кошик', 0) ?></a>
         <a href="tel:380960000943" class="icon-wrapper">
            <svg viewBox="0 0 24 24">
               <path fill="#7DBE4A"
                     d="M20,15.5C18.8,15.5 17.5,15.3 16.4,14.9C16.3,14.9 16.2,14.9 16.1,14.9C15.8,14.9 15.6,15 15.4,15.2L13.2,17.4C10.4,15.9 8,13.6 6.6,10.8L8.8,8.6C9.1,8.3 9.2,7.9 9,7.6C8.7,6.5 8.5,5.2 8.5,4C8.5,3.5 8,3 7.5,3H4C3.5,3 3,3.5 3,4C3,13.4 10.6,21 20,21C20.5,21 21,20.5 21,20V16.5C21,16 20.5,15.5 20,15.5M5,5H6.5C6.6,5.9 6.8,6.8 7,7.6L5.8,8.8C5.4,7.6 5.1,6.3 5,5M19,19C17.7,18.9 16.4,18.6 15.2,18.2L16.4,17C17.2,17.2 18.1,17.4 19,17.4V19Z"/>
            </svg>
            +38 096 0000 943</a>
         <div class="mob__border"></div>

         <?php if (!empty($_SESSION['cart'])) { ?>
            <a href="<?= SITE_URL ?>cart/checkout"><?= $this->text('Оформлення замовлення', 0) ?></a>
         <?php } ?>

         <a href="<?= SITE_URL ?>exchange-and-return"><?= $this->text('Повернення та гарантія', 0) ?></a>
         <a href="<?= SITE_URL ?>delivery-and-payments"><?= $this->text('Оплата та доставка', 0) ?></a>
         <a href="<?= SITE_URL ?>oferta"><?= $this->text('Договір оферти', 0) ?></a>
      </div>
   </div>
</header>