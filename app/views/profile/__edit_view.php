<div id="tabs">
    <?php /*
    <ul class="flex">
        <li><a href="#main"><?=$this->text('Загальні дані')?></a></li>
        <li><a href="#security"><?=$this->text('Безпека')?></a></li>
    </ul>
    */ ?>

    <div id="main">
        <form action="<?= SITE_URL?>profile/saveUserInfo" method="POST">
            <table>
                <tbody>
                    <?php $showSave = false;
                    $name = explode(' ', $user->name);
                    $user->first_name = $name[0];
                    $user->last_name = $name[1] ?? '';
                    foreach(['first_name' => "Ім'я", 'last_name' => "Прізвище", 'email' => 'Еmail', 'phone' => 'Номер телефону'] as $key => $title) { ?>
                        <tr>
                            <td><?=$this->text($title)?></td>
                            <td>
                                <?php if(!empty($user->$key)) {
                                    if($key == 'phone') $user->$key = $this->data->formatPhone($user->$key); ?>
                                    <i class="fas fa-pencil-alt pull-right" data-name="<?= $key ?>"></i> <?= $user->$key ?>
                                <?php } else { $showSave = true; ?>
                                    <input type="<?= $key == 'email' ? 'email' : 'text' ?>" name="<?= $key ?>">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    foreach(['company_info' => 'Компанія'] as $key => $title) { ?>
                        <tr>
                            <td><?=$this->text($title)?></td>
                            <td>
                                <?php if(isset($user->info[$key])) { ?>
                                    <i class="fas fa-pencil-alt pull-right" data-name="<?= $key ?>"></i> <?= $user->info[$key] ?>
                                <?php } else { $showSave = true; ?>
                                    <input type='text' name='<?= $key ?>'>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?=$this->text('Додати/оновити фото')?></td>
                        <td id="fileupload"><input onchange="show_image(this)" type="file" name="photos"></td>
                    </tr>
                    <?php $this->load->library('facebook'); 
                    if($_SESSION['option']->facebook_initialise){
                        if(empty($user->info['facebook'])) { ?>
                            <tr>
                                <td>Facebook <i class="fab fa-facebook"></i></td>
                                <td><span class="media" onclick="return facebookSignUp()"><?=$this->text('Підключити авторизацію через')?> facebook</span></td>
                            </tr>

                            <script>
                                window.fbAsyncInit = function() {
                                    
                                    FB.init({
                                      appId      : '<?=$this->facebook->getAppId()?>',
                                      cookie     : true,
                                      xfbml      : true,
                                      version    : 'v3.1'
                                    });
                                };

                                (function(d, s, id){
                                    var js, fjs = d.getElementsByTagName(s)[0];
                                    if (d.getElementById(id)) {return;}
                                    js = d.createElement(s); js.id = id;
                                    js.src = "//connect.facebook.net/en_US/sdk.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));
                            </script>
                    <?php } else { ?>
                        <tr>
                            <td>Facebook <i class="fab fa-facebook"></i></td>
                            <td><a href="<?=SITE_URL?>profile/facebook_disable" class="media pull-right"> Відключити </a> <?=$this->text('Авторизацію підключено')?></td>
                        </tr>
                    <?php } } ?>
                    <tr>
                        <td><?=$this->text('Тип')?></td>
                        <td><?=$user->type_title?></td>
                    </tr>
                    <tr>
                        <td><?=$this->text('Останній вхід')?></td>
                        <td><?= $user->last_login ? date("d.m.Y H:i", $user->last_login) : date("d.m.Y H:i", $user->registered) ?></td>
                    </tr>
                    <tr>
                        <td><?=$this->text('Дата реєстрації')?></td>
                        <td><?=date("d.m.Y H:i", $user->registered)?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button class="<?= ($showSave) ? '' : 'hide' ?>" type="submit"><?=$this->text('Зберегти зміни')?></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
    <?php /*
    <div id="security">
        <h4><?=$this->text('Зміна паролю')?></h4>
        <form action="<?= SITE_URL?>profile/save_security" method="POST">
            <table>
                <tbody>
                    <?php if(!empty($user->password)) { ?>
                        <tr>
                            <td><?=$this->text("Введіть старий/поточний пароль")?></td>
                            <td><input type="password" name="old_password" placeholder="Поточний пароль" required></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?=$this->text("Введіть новий пароль")?></td>
                        <td><input type="password" name="new_password" placeholder="Новий пароль" required></td>
                    </tr>
                    <tr>
                        <td><?=$this->text("Повторіть новий пароль")?></td>
                        <td><input type="password" name="new_password_re" placeholder="Новий пароль" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit"><?=$this->text('Оновити пароль')?></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <h4><?=$this->text('Реєстр дій')?></h4>
        <table>
            <tbody>
                <?php if($registerDo) foreach ($registerDo as $register) { ?>
                    <tr>
                        <td><?= date("d.m.Y H:i", $register->date)?></td>
                        <td><?= $register->title_public?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    */ ?>
</div>

<?php
    $_SESSION['alias']->js_load[] = "assets/jquery-ui/ui/minified/jquery-ui.min.js";
    $_SESSION['alias']->js_load[] = "assets/blueimp/js/vendor/jquery.ui.widget.js";
    $_SESSION['alias']->js_load[] = "assets/blueimp/js/load-image.all.min.js";
    $_SESSION['alias']->js_load[] = "assets/blueimp/js/jquery.fileupload.js";
    $_SESSION['alias']->js_load[] = "assets/jquery.mask.min.js";
    $_SESSION['alias']->js_load[] = "js/user.js";
 ?>