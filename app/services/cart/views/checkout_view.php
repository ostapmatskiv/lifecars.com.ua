<link rel="stylesheet" type="text/css" href="<?=SERVER_URL.'style/'.$_SESSION['alias']->alias.'/cart.css'?>">
<link rel="stylesheet" type="text/css" href="<?=SERVER_URL.'style/'.$_SESSION['alias']->alias.'/checkout.css'?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<?php $this->load->js(['assets/jquery-ui/1.12.1/jquery-ui.min.js', 'assets/jquery.mask.min.js', 'js/'.$_SESSION['alias']->alias.'/cart.js', 'js/'.$_SESSION['alias']->alias.'/cities.js', 'js/'.$_SESSION['alias']->alias.'/checkout.js']); // 'assets/sticky.min.js',  
if ($products) {
	$ga4_event = 'begin_checkout';
	require_once '__ga4_events.php';
} ?>

<main id="cart" data-sticky-container>
	<a href="<?=SITE_URL.$_SESSION['alias']->alias?>" class="right"><i class="fas fa-undo"></i> <?=$this->text('Редагувати замовлення')?></a>

	<h1><?=$_SESSION['alias']->name?></h1>

	<div id="cart_notify" class="alert alert-danger <?=(empty($_SESSION['notify']->error)) ? 'hide' : ''?>">
		<span class="close"><i class="fas fa-times"></i></span>
		<p><?=$_SESSION['notify']->error ?? ''?></p>
	</div>

	<?php if(!empty($_SESSION['notify']->success)) { ?>
		<div class="alert alert-success">
		    <span class="close"><i class="fas fa-times"></i></span>
		    <h4><?=$_SESSION['notify']->success?></h4>
		</div>
	<?php } unset($_SESSION['notify']); ?>

	<div class="flex w100">
		<div class="w30 m100">
			<?php /* ?>
			<div id="percents" data-margin-top="0"><div class="active"></div><div class="text">15%</div></div>
			<div class="info" data-margin-top="27"><?=$this->text('Статус заповнення інформації')?></div>

			<?php */ if(!$this->userIs()) { /* ?>
				<div id="cart-signup" class="flex">
					<div data-tab="new-buyer" class="w50 active"><?=$this->text('Я новий покупець')?></div>
					<div data-tab="regular-buyer" class="w50"><?=$this->text('Я постійний покупець')?></div>
				</div>
				*/ ?>

				<div id="new-buyer">
					<h4><?=$this->text('Покупець')?></h4>

					<div class="input-group">
						<input id="phone" type="text" value="<?= $this->data->re_post('phone') ?>" required minlength="17"/>
						<label for="phone"><?= $this->text('Номер телефону', 5) ?></label>
						<h5 class="text-danger hide" id="phoneError"><?= $this->text('Введіть коректний номер телефону починаючи +380') ?></h5>
					</div>
					
					<div class="input-group">
						<input id="first_name" type="text" value="<?= $this->data->re_post('first_name') ?>" required />
						<label for="first_name"><?= $this->text('Ім\'я', 5) ?></label>
						<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
					</div>

					<div class="input-group">
						<input id="last_name" type="text" value="<?= $this->data->re_post('last_name') ?>" required />
						<label for="last_name"><?= $this->text('Прізвище', 5) ?></label>
						<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
					</div>
				</div>

				<?php /*
				<div id="regular-buyer" class="hide">
					<h4><?=$this->text('Вже купували?')?></h4>
		 			<p><?=$this->text('Увійдіть - це заощадить Ваш час')?></p>
					<form action="<?=SITE_URL.$_SESSION['alias']->alias?>/login" method="POST">
						<p class="message"></p>
						<input type="text" name="email" placeholder="<?=$this->text('email або телефон')?>*" value="<?=$this->data->re_post('email')?>">
						<input type="password" name="password" placeholder="<?=$this->text('Пароль')?>*">
						<div>
							<a href="<?=SITE_URL?>reset" class="right"><?=$this->text('Забув пароль?')?></a>
							<button type="submit"><?=$this->text('Увійти')?></button>
						</div>
					</form>
				</div>
				*/ ?>

				<?php $this->load->library('facebook'); 
				if(false && $_SESSION['option']->facebook_initialise){ ?>
					<div class="facebook">
						<p><?=$this->text('Швидкий вхід:')?></p>
						<button class="facebookSignUp" onclick="return facebookSignUp()">Facebook <i class="fab fa-facebook"></i></button>
					</div>

					<script>
						window.fbAsyncInit = function() {
							
						    FB.init({
						      appId      : '<?=$this->facebook->getAppId()?>',
						      cookie     : true,
						      xfbml      : true,
						      version    : 'v2.6'
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
				<?php }
			} ?>

			<form id="confirm" action="<?=SITE_URL.$_SESSION['alias']->alias?>/confirm" method="POST">
				<?php if(!$this->userIs()) { ?>
					<input type="text" name="email" value="<?=$this->data->re_post('email')?>" class="hide">
					<input type="text" name="phone" value="<?=$this->data->re_post('phone')?>" class="hide" required>
					<input type="text" name="name" value="<?=$this->data->re_post('name')?>" class="hide" required>
				<?php } /* else { ?>
					<div id="buyer">
						<h4><?=$this->text('Покупець')?></h4>
			 			<p>
				 			<?php echo $_SESSION['user']->name .'<br>'.$_SESSION['user']->email;
				 			if(empty($_SESSION['user']->phone)) { ?>
				 				</p><p><input type="text" id="phone" name="phone" value="<?=$this->data->re_post('phone')?>" placeholder="<?=$this->text('+380********* (Контактний номер)')?>" required>
				 			<?php } else echo '<br>'.$this->data->formatPhone($_SESSION['user']->phone); ?>
			 			</p>
					</div>
				<?php } */ if($shippings)
					require_once '__shippings_subview.php';
				if($payments) { ?>
					<h4><?=$this->text('Оплата')?></h4>
					<div id="payments" class="cart_section">
				    	<?php foreach ($payments as $payment) {
							$checked = (count($payments) == 1) ? 'checked' : '';
							if(!empty($userShipping))
							{
								if($userShipping->payment_alias == 0 && $payment->id == $userShipping->payment_id)
									$checked = 'checked';
								else if($userShipping->payment_alias > 0 && $userShipping->payment_alias == $payment->wl_alias)
									$checked = 'checked';
							} ?>
				    		<label <?=$checked ? 'class="active"' : ''?>>
					            <input type="radio" name="payment_method" value="<?=$payment->id?>" <?=$checked?>>
					            <?=$payment->name?>
					        </label>
					        <div class="payment-info <?=$checked ? '' : 'hide'?>" id="payment-<?=$payment->id?>">
				                <p><?=htmlspecialchars_decode($payment->info)?></p>
				            </div>
				    	<?php } ?>
					</div>
				<?php } ?>

				<h4><?=$this->text('Коментар')?></h4>
				<textarea name="comment" class="form-control" placeholder="<?=$this->text('Побажання до замовлення, наприклад щодо доставки')?>" rows="5"><?=$this->data->re_post('comment')?></textarea>

				<div class="price-box" data-margin-top="110">
					
					<?php if($bonusCodes && !empty($bonusCodes->info))
						foreach ($bonusCodes->info as $key => $discount) { ?>
						<p class="bonusCode">
							<?=$this->text('Бонус-код').': '.$key?>
							<strong class="right"><?=$discount?></strong>
						</p>
			        <?php } if($shippings && $shippings[0]->pay >= -1) { ?>
			        	<p class="shipping">
							<?=$this->text('Доставка')?>
							<strong class="right"><?=($shippings[0]->pay == -1)?$this->text('безкоштовно'):$shippings[0]->priceFormat?></strong>
						</p>
			        <?php } ?>

					<p class="price">
						<?=$this->text('До оплати')?>
						<strong class="right"><?=$totalFormat?></strong>
					</p>

					<?php if($discountTotal) { ?>
						<p class="discount"><?=$this->text('Ви економите')?> <strong class="right"><?=$discountTotalFormat ?></strong></p>
					<?php } ?>

					<?php if(!empty($_SESSION['option']->dogovirOfertiLink)) { ?>
						<label id="oferta">
							<input type="checkbox" name="oferta" <?=$this->userIs() ? 'checked' : ''?> required>
							<i class="<?=$this->userIs() ? 'fas fa-check-square' : 'far fa-square'?>"></i>
							<div><?=$this->text('Я погоджуюся з')?> <a href="<?=SERVER_URL.$_SESSION['option']->dogovirOfertiLink?>" target="_blank"><?=$this->text('Договором оферти')?></a></div>
						</label>
					<?php } ?>

					<button type="submit" class="checkout"><?=$this->text('Оформити замовлення')?></button>
				</div>
			</form>
		</div>

		<div class="w70-5 m-hide">
			<?php if ($bonusCodes && $bonusCodes->showForm) { ?>
				<form action="<?=SITE_URL.$_SESSION['alias']->alias?>/coupon" method="POST" class="coupon-form flex">
					<input type="text" name="code" class="w75" placeholder="<?=$this->text('Маєте купон на знижку? Введіть код купону сюди')?>" required>
					<button class="w25"><?=$this->text('Застосувати купон')?></button>
				</form>
			<?php } $actions = false;
			require_once '__cart_products_list2.php'; ?>
		</div>
	</div>
</main>

<script>
	window.onload = function () {
        // $('#signInForm input[name=phone]').focus();

        $(document).on('focusout', '.input-group input', function (){
            if($(this).val().length) {
                $(this).closest('.input-group').addClass('val');
            } else {
                $(this).closest('.input-group').removeClass('val')
            }
        });
        $(document).on('focus', '.input-group input', function (){
            $(this).closest('.input-group').addClass('val');
        });
	}
</script>