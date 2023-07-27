<link rel="stylesheet" type="text/css" href="<?= SERVER_URL . 'style/' . $_SESSION['alias']->alias . '/cart.css?v1' ?>">
<link rel="stylesheet" type="text/css" href="<?= SERVER_URL . 'style/' . $_SESSION['alias']->alias . '/checkout.css?v1' ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<?php $this->load->js(['assets/jquery-ui/1.12.1/jquery-ui.min.js', 'assets/jquery.mask.min.js', 'js/' . $_SESSION['alias']->alias . '/cart.js', 'js/' . $_SESSION['alias']->alias . '/cities.js', 'js/' . $_SESSION['alias']->alias . '/checkout.js']); // 'assets/sticky.min.js',  
if ($products) {
	$ga4_event = 'begin_checkout';
	require_once '__ga4_events.php';
} ?>

<main id="cart" data-sticky-container>
	<a href="<?= SITE_URL . $_SESSION['alias']->alias ?>" class="right m-hide"><i class="fas fa-undo"></i> <?= $this->text('Редагувати замовлення') ?></a>
	
	<h1>
		<a href="<?= SITE_URL . $_SESSION['alias']->alias ?>" class="hide m-block to_cart"><i class="fas fa-arrow-left"></i></a>
		<?= $_SESSION['alias']->name ?>
	</h1>

	<div id="cart_notify" class="alert alert-danger <?= (empty($_SESSION['notify']->error)) ? 'hide' : '' ?>">
		<span class="close"><i class="fas fa-times"></i></span>
		<p><?= $_SESSION['notify']->error ?? '' ?></p>
	</div>

	<?php if (!empty($_SESSION['notify']->success)) { ?>
		<div class="alert alert-success">
			<span class="close"><i class="fas fa-times"></i></span>
			<h4><?= $_SESSION['notify']->success ?></h4>
		</div>
	<?php }
	unset($_SESSION['notify']); ?>

	<div class="flex w100 m-column-reverse">
		<div class="w30 m100">

			<?php if (!$this->userIs()) { ?>
				<h4><?= $this->text('Покупець') ?></h4>

				<div class="cart_section">
					<div class="input-group">
						<input id="phone" type="text" value="<?= $this->data->re_post('phone') ?>" required minlength="17" />
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
			<?php } else {
				$name = explode(' ', $_SESSION['user']->name);
				$phoneFormat = $this->data->formatPhone($_SESSION['user']->phone); ?>
				<h4><small class="pull-right" onclick="$('#UserName, #form_editUserName').slideToggle()"><?= $this->text('Редагувати') ?></small> <?= $this->text('Покупець') ?></h4>

				<?= "<div id=\"UserName\" class=\"cart_section\">{$_SESSION['user']->name} <br> {$phoneFormat}</div>"; ?>
				<form action="<?= SITE_URL ?>profile/saveUserInfo" method="post" id="form_editUserName" style="display: none;" class="text-center">
					<div class="input-group val">
						<input name="first_name" id="first_name" type="text" value="<?= $name[0] ?>" required />
						<label for="first_name"><?= $this->text('Ім\'я', 5) ?></label>
						<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
					</div>

					<div class="input-group <?= empty($name[1]) ? '' : 'val' ?>">
						<input name="last_name" id="last_name" type="text" value="<?= $name[1] ?? '' ?>" required />
						<label for="last_name"><?= $this->text('Прізвище', 5) ?></label>
						<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
					</div>

					<button class=""><?= $this->text('Зберегти') ?></button>
				</form>
			<?php } ?>

			<form id="confirm" action="<?= SITE_URL . $_SESSION['alias']->alias ?>/confirm" method="POST">
				<?php if (!$this->userIs()) { ?>
					<input type="text" name="email" value="<?= $this->data->re_post('email') ?>" class="hide">
					<input type="text" name="phone" value="<?= $this->data->re_post('phone') ?>" class="hide" required>
					<input type="text" name="name" value="<?= $this->data->re_post('name') ?>" class="hide" required>
				<?php }

				if ($shippings)
					require_once '__shippings_subview.php';

				if ($payments) { ?>
					<h4><?= $this->text('Оплата') ?></h4>
					<div id="payments" class="cart_section">
						<?php foreach ($payments as $payment) {
							$checked = (count($payments) == 1) ? 'checked' : '';
							if (!empty($userShipping)) {
								if ($userShipping->payment_alias == 0 && $payment->id == $userShipping->payment_id)
									$checked = 'checked';
								else if ($userShipping->payment_alias > 0 && $userShipping->payment_alias == $payment->wl_alias)
									$checked = 'checked';
							} ?>
							<label <?= $checked ? 'class="active"' : '' ?>>
								<input type="radio" name="payment_method" value="<?= $payment->id ?>" <?= $checked ?>>
								<?= $payment->name ?>
							</label>
							<div class="payment-info <?= $checked ? '' : 'hide' ?>" id="payment-<?= $payment->id ?>">
								<p><?= htmlspecialchars_decode($payment->info) ?></p>
							</div>
						<?php } ?>
					</div>
				<?php } ?>

				<h4><?= $this->text('Коментар') ?></h4>
				<textarea name="comment" class="form-control" placeholder="<?= $this->text('Побажання до замовлення, наприклад щодо доставки') ?>" rows="5"><?= $this->data->re_post('comment') ?></textarea>
			</form>

			<?php if ($bonusCodes && $bonusCodes->showForm) { ?>
				<h4 style="margin-bottom: 0;"><?= $this->text('Маєте купон на знижку?') ?></h4>
				<form action="<?= SITE_URL . $_SESSION['alias']->alias ?>/coupon" method="POST" class="coupon-form text-center">
					<div class="input-group">
						<input type="text" name="code" placeholder="<?= $this->text('Код купону') ?>" class="w100" required>
						<label for="code"><?= $this->text('Код купону') ?></label>
					</div>
					<button class=""><?= $this->text('Застосувати купон') ?></button>
				</form>
			<?php } ?>

			<div class="price-box" data-margin-top="110">
				<?php if (false && $bonusCodes && !empty($bonusCodes->info)) { ?>
					<p class="bonusCode">
						<?= $this->text('Разом') . ': ' ?>
						<strong class="right"><?= $subTotalFormat ?></strong>
					</p>
					<?php foreach ($bonusCodes->info as $key => $discount) { ?>
						<p class="bonusCode discount">
							<?= $this->text('Бонус-код') . ': ' . $key ?>
							<strong class="right"><?= $discount ?></strong>
						</p>
					<?php }
				}
				if ($shippings && $shippings[0]->pay >= -1) { ?>
					<p class="shipping">
						<?= $this->text('Доставка') ?>
						<strong class="right"><?= ($shippings[0]->pay == -1) ? $this->text('безкоштовно') : $shippings[0]->priceFormat ?></strong>
					</p>
				<?php } ?>

				<?php if ($discountTotal || true) { ?>
					<p class="bonusCode">
						<?= $this->text('Разом') . ': ' ?>
						<strong class="right"><?= $subTotalFormat ?></strong>
					</p>
					<p class="discount"><?= $this->text('Знижка') ?> <strong class="right"><?= $discountTotalFormat ?></strong></p>
				<?php } ?>

				<p class="price">
					<?= $this->text('До оплати') ?>
					<strong class="right"><?= $totalFormat ?></strong>
				</p>

				<?php if (!empty($_SESSION['option']->dogovirOfertiLink)) { ?>
					<label id="oferta">
						<input type="checkbox" name="oferta" <?= $this->userIs() ? 'checked' : '' ?> required form="confirm">
						<i class="<?= $this->userIs() ? 'fas fa-check-square' : 'far fa-square' ?>"></i>
						<div><?= $this->text('Я погоджуюся з') ?> <a href="<?= SERVER_URL . $_SESSION['option']->dogovirOfertiLink ?>" target="_blank"><?= $this->text('Договором оферти') ?></a></div>
					</label>
				<?php } ?>

				<button type="submit" class="checkout" form="confirm"><?= $this->text('Оформити замовлення') ?></button>
			</div>
		</div>

		<div class="w70-5 m100">
			<?php $actions = false;
			require_once '__cart_products_list2.php'; ?>
		</div>
	</div>
</main>

<script>
	window.onload = function() {
		// $('#signInForm input[name=phone]').focus();
	}
</script>