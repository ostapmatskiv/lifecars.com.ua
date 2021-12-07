<link rel="stylesheet" type="text/css" href="<?= SERVER_URL ?>style/login.css">

<?php if ($_SESSION['option']->facebook_initialise) { ?>
   <script>
       window.fbAsyncInit = function () {
          <?php $this->load->library('facebook'); ?>
           FB.init({
               appId: '<?=$this->facebook->getAppId()?>',
               cookie: true,
               xfbml: true,
               version: 'v3.1'
           });
       };

       (function (d, s, id) {
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {
               return;
           }
           js = d.createElement(s);
           js.id = id;
           js.src = "//connect.facebook.net/en_US/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));

       function facebookSignUp() {
           FB.login(function (response) {
               if (response.authResponse) {
                   $("#divLoading").addClass('show');
                   var accessToken = response.authResponse.accessToken;
                   FB.api('/me?fields=email', function (response) {
                       if (response.email && accessToken) {
                           $('#authAlert').addClass('collapse');
                           $.ajax({
                               url: '<?=SITE_URL?>signup/facebook',
                               type: 'POST',
                               data: {
                                   accessToken: accessToken,
                                   ajax: true
                               },
                               complete: function () {
                                   $("div#divLoading").removeClass('show');
                               },
                               success: function (res) {
                                   if (res['result'] == true) {
                                       window.location.href = '<?=SITE_URL?>profile';
                                   } else {
                                       $('#authAlert').removeClass('collapse');
                                       $("#authAlertText").text(res['message']);
                                   }
                               }
                           })
                       } else {
                           $("div#divLoading").removeClass('show');
                           $("#clientError").text('Для авторизації потрібен e-mail');
                           setTimeout(function () {
                               $("#clientError").text('')
                           }, 5000);
                           FB.api("/me/permissions", "DELETE");
                       }
                   });
               } else {
                   $("div#divLoading").removeClass('show');
               }

           }, {scope: 'email'});
       }
   </script>
<?php } ?>

<div class="container <?= ($_SESSION['alias']->alias == 'signup') ? 'right-panel-active' : '' ?>" id="login-container">
   <div class="form-container sign-up-container">
      <?php if ($_SESSION['alias']->alias == 'signup') {
         if (!empty($_SESSION['notify']->errors)): ?>
            <div class="alert alert-danger fade in">
               <span class="close" data-dismiss="alert">×</span>
               <h4><?= (isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : $this->text('Помилка!', 0) ?></h4>
               <p><?= $_SESSION['notify']->errors ?></p>
            </div>
         <?php elseif (!empty($_SESSION['notify']->success)): ?>
            <div class="alert alert-success fade in">
               <span class="close" data-dismiss="alert">×</span>
               <h4>
                  <i class="fa fa-check"></i> <?= (isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : $this->text('Успіх!', 0) ?>
               </h4>
               <p><?= $_SESSION['notify']->success ?></p>
            </div>
         <?php endif;
         unset($_SESSION['notify']);
      } ?>

      <form action="<?= SITE_URL ?>signup/process" method="POST" id="signupForm" class="type-2">
         <h1><?= $this->text('Реєстрація') ?></h1>

         <?php if ($_SESSION['option']->facebook_initialise || $this->googlesignin->clientId) { ?>
            <div class="social-container" style="display: none">
               <?php if ($_SESSION['option']->facebook_initialise) { ?>
                  <a href="#" class="social facebook-login"
                     title="<?= $this->text('Швидка реєстрація за допомогою facebook', 4) ?>"><i
                           class="fab fa-facebook-f"></i></a>
               <?php }
               if ($this->googlesignin->clientId) { ?>
                  <a href="#" class="social google-login"
                     title="<?= $this->text('Швидкий вхід за допомогою google', 4) ?>"><i class="fab fa-google"></i></a>
               <?php } ?>
            </div>
            <!--<span>або за допомогою номеру телефону</span>-->
         <?php } /* ?>
            <div class="flex wrap">
                <input name="first_name" type="text" value="<?=$this->data->re_post('first_name')?>" placeholder="<?=$this->text('Ім\'я', 5)?>" required />
                <input name="last_name" type="text" value="<?=$this->data->re_post('last_name')?>" placeholder="<?=$this->text('Прізвище', 5)?>" required />
            </div>
            */ ?>
         <div class="input-group">
            <input name="phone" id="phone" type="text" value="<?= $this->data->re_post('phone') ?>" required minlength="17"/>
            <label for="phone"><?= $this->text('Номер телефону', 5) ?></label>
         </div>
         <h4 class="text-danger hide" id="phoneError"><?= $this->text('Користувач з таким номером телефону вже існує!') ?><button type="button" class="ghost hexa" onclick="document.getElementById('login-container').classList.remove('right-panel-active')"><?= $this->text('Увійти', 4) ?></button></h4>
         <h4 class="text-danger hide" id="phoneError2"></h4>

         <div class="input-group">
            <input name="first_name" id="first_name" type="text" value="<?= $this->data->re_post('first_name') ?>" required <?= $this->data->re_post('first_name') ? '' : 'disabled' ?> />
            <label for="first_name"><?= $this->text('Ім\'я', 5) ?></label>
         </div>
         <h4 class="text-danger hide" id="fnError"><?= $this->text('Тільки українські літери') ?></h4>

         <div class="input-group">
            <input name="last_name" id="last_name" type="text" value="<?= $this->data->re_post('last_name') ?>" required <?= $this->data->re_post('last_name') ? '' : 'disabled' ?> />
            <label for="last_name"><?= $this->text('Прізвище', 5) ?></label>
         </div>
         <h4 class="text-danger hide" id="lnError"><?= $this->text('Тільки українські літери') ?></h4>

         <div class="input-group <?= $this->data->re_post('code') ? '' : 'hide' ?>">
            <input name="code" type="number" value="<?= $this->data->re_post('code') ?>"/>
            <label for="last_name"><?= $this->text('Код з СМС', 5) ?></label>
         </div>
         <h4 class="text-danger hide" id="codeError"><?= $this->text('Помилка СМС коду! Перевірте дані') ?></h4>
         <p class="send_phone_code hide"><?= $this->text('Повторно відправити СМС з кодом') ?></p>

         <?php /*
            <input name="email" type="email" value="<?=$this->data->re_post('email')?>" placeholder="Email" required />
            <input name="password" type="password" value="<?=$this->data->re_post('password')?>" class="form-control" placeholder="<?=$this->text('Пароль', 4)?>" required />
            <input name="re-password" type="password" class="form-control" placeholder="<?=$this->text('Повторіть пароль', 5)?>" required />
            <span><?=$this->text('*пороль має містити від 5 до 20 символів', 5)?></span>
            <br>
            <?php
                $this->load->library('recaptcha');
                $this->recaptcha->form(); */ ?>
         <br>
         <button type="submit" class="hexa disabled"><?= $this->text('Зареєструватися', 5) ?></button>
         <a href="<?= SITE_URL ?>login"><?= $this->text('Увійти', 4) ?></a>
      </form>
   </div>
   <div class="form-container sign-in-container">
      <?php if ($_SESSION['alias']->alias == 'login') {
         if (!empty($_SESSION['notify']->errors)): ?>
            <div class="alert alert-danger fade in">
               <span class="close" data-dismiss="alert">×</span>
               <h4><?= (isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : $this->text('Помилка!', 0) ?></h4>
               <p><?= $_SESSION['notify']->errors ?></p>
            </div>
         <?php elseif (!empty($_SESSION['notify']->success)): ?>
            <div class="alert alert-success fade in">
               <span class="close" data-dismiss="alert">×</span>
               <h4>
                  <i class="fa fa-check"></i> <?= (isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : $this->text('Успіх!', 0) ?>
               </h4>
               <p><?= $_SESSION['notify']->success ?></p>
            </div>
         <?php endif;
         unset($_SESSION['notify']);
      } ?>
      <form action="<?= SITE_URL ?>login/process" method="POST" id="signInForm">
         <h1><?= $this->text('Вхід') ?></h1>
         <?php if ($_SESSION['option']->facebook_initialise || $this->googlesignin->clientId) { ?>
            <div class="social-container" style="display: none">
               <?php if ($_SESSION['option']->facebook_initialise) { ?>
                  <a href="#" class="social facebook-login"
                     title="<?= $this->text('Швидка реєстрація за допомогою facebook', 4) ?>"><i
                           class="fab fa-facebook-f"></i></a>
               <?php }
               if ($this->googlesignin->clientId) { ?>
                  <a href="#" class="social google-login"
                     title="<?= $this->text('Швидкий вхід за допомогою google', 4) ?>"><i class="fab fa-google"></i></a>
               <?php } ?>
            </div>
            <h4><?= $this->text('Для входу введіть свій номер телефону', 4) ?></h4>
         <?php } ?>
         <?php if (isset($_GET['redirect']) || $this->data->re_post('redirect')) { ?>
            <input type="hidden" name="redirect"
                   value="<?= $this->data->re_post('redirect', $this->data->get('redirect')) ?>">
         <?php } /* ?>
            <input type="email" name="email" value="<?=$this->data->re_post('email')?>" placeholder="Email" required />
            <input type="password" name="password" placeholder="<?=$this->text('Пароль', 4)?>" required />
            <a href="<?=SITE_URL?>reset"><?=$this->text('Забули пароль?', 4)?></a>
            */ ?>
         <input name="phone" type="text" value="<?= $this->data->re_post('phone') ?>" placeholder="+380" required
                minlength="17"/>

         <h4 class="text-danger hide" id="userExist"><?= $this->text('Користувач з таким номером телефону не існує!') ?>
            <button type="button" class="ghost hexa"
                    onclick="document.getElementById('login-container').classList.add('right-panel-active')"><?= $this->text('Зареєструватися', 4) ?></button>
         </h4>
         <h4 class="text-danger hide" id="phoneErrorView"></h4>

         <input name="code" type="number" value="<?= $this->data->re_post('code') ?>"
                placeholder="<?= $this->text('Код з СМС', 5) ?>" <?= $this->data->re_post('code') ? '' : 'class="hide"' ?> />
         <h4 class="text-danger hide" id="codeErrorIn"><?= $this->text('Помилка СМС коду! Перевірте дані') ?></h4>

         <p class="send_phone_code hide"><?= $this->text('Повторно відправити СМС з кодом') ?></p>

         <button type="submit" class="hexa"><?= $this->text('Увійти', 4) ?></button>

         <a href="<?= SITE_URL ?>signup"><?= $this->text('Зареєструватись', 4) ?></a>
      </form>
   </div>
   <div class="overlay-container m-hide">
      <div class="overlay">
         <div class="overlay-panel overlay-left">
            <h1><?= $this->text('Вже зареєстровані?', 4) ?></h1>
            <p><?= $this->text('увійти за допомогою email та паролю', 4) ?></p>
            <button class="ghost hexa" id="signIn"><?= $this->text('Увійти', 4) ?></button>
         </div>
         <div class="overlay-panel overlay-right">
            <h1><?= $this->text('Реєстрація', 5) ?></h1>
            <p><?= $this->text('Вкажіть свої персональні дані (емейл, телефон, назву компанії) і розпочнімо співпрацю з Life Cars!', 5) ?></p>
            <button class="ghost hexa" id="signUp"><?= $this->text('Зареєструватися', 5) ?></button>
         </div>
      </div>
   </div>
</div>

<style>
    .text-danger {
        color: red;
    }
</style>

<script type="text/javascript">
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('login-container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });

    <?php $this->load->js('assets/jquery.mask.min.js');
    if (!empty($_GET['redirect']) || $this->data->re_post('redirect')) {
       echo 'var redirect = "' . $this->data->re_post('redirect', $this->data->get('redirect')) . '";';
    } else echo "var redirect = false;"; ?>

    window.onload = function () {
        // $('#signInForm input[name=phone]').focus();

        $(document).on('focusout', 'form.type-2 input', function (){
            if($(this).val().length) {
                $(this).closest('.input-group').addClass('val');
            } else {
                $(this).closest('.input-group').removeClass('val')
            }
        });
        $(document).on('focus', 'form.type-2 input', function (){
            $(this).closest('.input-group').addClass('val');
        });


        $('.alert .close').click(function () {
            $('.alert').hide();
        });

        var mask_options = {
            translation: {
                'Z': {
                    pattern: 0, optional: false
                },
                'N': {
                    pattern: /[1-9]/, optional: false
                }
            }
            // onKeyPress: function(cep, e, field, options) {
            //   mask = '+38 000 000 00 00';
            //   if(cep == '+')
            //       field.mask(mask, mask_options);
            //   // else if(cep.length > 3)
            //   // {
            //   //     cep = cep.substr(0, 3);
            //   //     if(cep == '+38')
            //   //         $('input[name=phone]').mask('+38 000 000 00 00', mask_options);
            //   //     else
            //   //         field.mask(mask, mask_options);
            //   // }
            //   }
        };
        $('input[name=phone]').focus(function () {
            /*if (this.value.length == 0) {
                this.value = '+380';
            }*/
        }).mask('+38Z NN 000 00 00', mask_options);

        let signUpStage = 1;
        $('#signupForm input[name=phone]').change(function () {
            console.log('change');
            $('#signupForm #phoneError2').addClass('hide');
            if (this.value.length == 17) {
                $('#divLoading').addClass('show');
                let tel = this.value;
                $.ajax({
                    type: "POST",
                    url: SERVER_URL + 'signup/check_phone',
                    data: {
                        phone: this.value
                    },
                    success: function (res) {
                        $('#divLoading').removeClass('show');
                        if (res.status) {
                            console.log('norm');
                            signUpStage = 2;
                            $('#signupForm input[type=text]').attr('disabled', false);
                            $('#signupForm #phoneError').addClass('hide');
                            $('#signupForm input[name=first_name]').focus();
                        } else {
                            console.log('disabled');
                            signUpStage = 1;
                            $('#signInForm input[name=phone]').val(tel);
                            $('#signupForm input[name=first_name]').attr('disabled', true);
                            $('#signupForm input[name=last_name]').attr('disabled', true);
                            $('#signupForm #phoneError').removeClass('hide');
                        }
                    }
                });
            }
        });

        $('#signupForm input[name=first_name]').change(function () {
            if (this.value.length > 0) {
                if (/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯ]+$/.test(this.value) === false) {
                    $('#signupForm #fnError').removeClass('hide');
                    $('#signupForm input[name=first_name]').focus();
                } else {
                    $('#signupForm #fnError').addClass('hide');
                    $('#signupForm input[name=last_name]').focus();
                }
            }
        });

        $('#signupForm input[name=last_name]').change(function () {
            if (this.value.length > 0) {
                if (/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯ]+$/.test(this.value) === false) {
                    $('#signupForm #lnError').removeClass('hide');
                    $('#signupForm input[name=last_name]').focus();
                } else {
                    $('#signupForm #lnError').addClass('hide');
                }
            }
        });

        $('#signupForm input[name=code]').change(function () {
            $('#divLoading').addClass('show');
            $.ajax({
                type: "POST",
                url: SERVER_URL + 'signup/check_phone_code',
                data: {
                    phone: $('#signupForm input[name=phone]').val(),
                    code: $('#signupForm input[name=code]').val()
                },
                success: function (res) {
                    $('#divLoading').removeClass('show');
                    if (res.status) {
                        signUpStage = 4;
                        $('#signupForm #codeError').addClass('hide');
                        $('#signupForm').submit();
                    } else {
                        $('#signupForm #codeError').removeClass('hide');
                    }
                }
            });
        });

        $('#signupForm').submit(() => {
            first_name = $('#signupForm input[name=first_name]').val();
            last_name = $('#signupForm input[name=last_name]').val();
            phone = $('#signupForm input[name=phone]').val();

            if (signUpStage == 1) {
                if (first_name.length > 0 && last_name.length > 0 && phone.length > 0) {
                    signUpStage = 2;
                }
            }

            if (signUpStage == 2) {
                // verification for ukr letters
                if (/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯ]+$/.test(first_name) === false) {
                    $('#signupForm #fnError').removeClass('hide');
                    return false;
                }

                if (/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯ]+$/.test(last_name) === false) {
                    $('#signupForm #lnError').removeClass('hide');
                    return false;
                }

                code = $('#signupForm input[name=code]').val();
                if (code > 0) {
                    signUpStage = 4;
                } else {
                    $('#divLoading').addClass('show');
                    $.ajax({
                        type: "POST",
                        url: SERVER_URL + 'signup/send_phone_code',
                        data: {
                            phone: $('#signupForm input[name=phone]').val()
                        },
                        success: function (res) {
                            $('#divLoading').removeClass('show');
                            if (res.status) {
                                signUpStage = 3;
                                $('#signupForm input[name=phone]').attr('readonly', true);
                                $('#signupForm #phoneError2').addClass('hide');
                                $('#signupForm #codeError').addClass('hide');
                                $('#signupForm .send_phone_code').removeClass('hide');
                                $('#signupForm input[name=code]').focus().removeClass('hide').attr('required', true);
                            } else {
                                signUpStage = 2;
                                $('#signupForm input[name=phone]').attr('readonly', false).focus();
                                $('#signupForm #phoneError2').text(res.message).removeClass('hide');
                                $('#signupForm input[name=code]').addClass('hide').attr('required', false);
                                $('#signupForm .send_phone_code').addClass('hide');
                            }
                        }
                    });
                    return false;
                }
            }
            if (signUpStage == 3) {

            }
            if (signUpStage == 4) {
                return true;
            }
            return false;
        });

        let signInStage = 1;
        $('#signInForm input[name=phone]').change(function () {
            $('#signInForm #userExist').addClass('hide');
        });

        $('#signInForm input[name=code]').change(function () {
            $('#divLoading').addClass('show');
            $.ajax({
                type: "POST",
                url: SERVER_URL + 'signup/check_phone_code',
                data: {
                    phone: $('#signInForm input[name=phone]').val(),
                    code: $('#signInForm input[name=code]').val()
                },
                success: function (res) {
                    $('#divLoading').removeClass('show');
                    if (res.status) {
                        signInStage = 3;
                        $('#signInForm #codeErrorIn').addClass('hide');
                        $('#signInForm').submit();
                    } else {
                        $('#signInForm #codeErrorIn').removeClass('hide');
                    }
                }
            });
        });

        $('#signInForm').submit(() => {
            $('#signInForm #userExist').addClass('hide');
            $('#signInForm #phoneErrorView').addClass('hide');

            tel = $('#signInForm input[name=phone]').val();
            code = $('#signInForm input[name=code]').val();
            if (signInStage == 1 && tel.length == 17 && code > 0) {
                signInStage = 2;
            }

            if (signInStage == 1) {
                $('#divLoading').addClass('show');
                $.ajax({
                    type: "POST",
                    url: SERVER_URL + 'signup/check_phone',
                    data: {
                        phone: tel
                    },
                    success: function (res) {
                        $('#divLoading').removeClass('show');
                        if (res.status) {
                            $('#signInForm #userExist').removeClass('hide');
                            $('#signupForm input[name=phone]').val(tel);
                            $('#signupForm input[name=first_name]').attr('disabled', false);
                            $('#signupForm input[name=last_name]').attr('disabled', false);
                        } else {
                            $('#divLoading').addClass('show');
                            $.ajax({
                                type: "POST",
                                url: SERVER_URL + 'signup/send_phone_code',
                                data: {
                                    phone: tel
                                },
                                success: function (res) {
                                    $('#divLoading').removeClass('show');
                                    if (res.status) {
                                        signUpStage = 2;
                                        $('#signInForm input[name=phone]').attr('readonly', true);
                                        $('#signInForm #codeErrorIn').addClass('hide');
                                        $('#signInForm #phoneErrorView').addClass('hide');
                                        $('#signInForm .send_phone_code').removeClass('hide');
                                        $('#signInForm input[name=code]').focus().removeClass('hide').attr('required', true);
                                    } else {
                                        signUpStage = 1;
                                        $('#signInForm input[name=phone]').attr('readonly', false).focus();
                                        $('#signInForm #phoneErrorView').text(res.message).removeClass('hide');
                                        $('#signInForm input[name=code]').addClass('hide').attr('required', false);
                                        $('#signInForm .send_phone_code').addClass('hide');
                                    }
                                }
                            });
                        }
                    }
                });
            }

            if (signInStage == 2) {
                return false;
            }

            if (signInStage == 3) {
                return true;
            }
            return false;
        });

        $('.send_phone_code').click(function () {
            tel = $(this).closest('form').find('input[name=phone]').val();
            $('#divLoading').addClass('show');
            $.ajax({
                type: "POST",
                url: SERVER_URL + 'signup/send_phone_code',
                data: {
                    phone: tel
                },
                success: function (res) {
                    $('#divLoading').removeClass('show');
                    if (res.status) {
                        alert('Код відіслано');
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    };
</script>
<?php if ($_SESSION['option']->userSignUp && ($_SESSION['option']->facebook_initialise || $this->googlesignin->clientId)) {
   if ($this->googlesignin->clientId)
      echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
   $this->load->js('assets/white-lion/login.js');
} ?>

<style type="text/css">
    .send_phone_code {
        margin: 5px 0 15px !important;
        text-align: center;
        cursor: pointer;
    }
</style>