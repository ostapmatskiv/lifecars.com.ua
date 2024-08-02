<!DOCTYPE html>
<html lang="<?= $_SESSION['language'] ?>" prefix="og: http://ogp.me/ns#">

<head>
	<title><?= $_SESSION['alias']->title ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta name="title" content="<?= $_SESSION['alias']->title ?>">
	<meta name="description" content="<?= $_SESSION['alias']->description ?>">
	<meta name="keywords" content="<?= $_SESSION['alias']->keywords ?>">
	<meta name="author" content="webspirit.com.ua">

	<meta property="og:locale" content="<?= $_SESSION['language'] ?>_UA" />
	<meta property="og:title" content="<?= $_SESSION['alias']->title ?>" />
	<meta property="og:description" content="<?= $_SESSION['alias']->description ?>" />
	<?php if (!empty($_SESSION['alias']->image)) { ?>
		<meta property="og:image" content="<?= IMG_PATH . $_SESSION['alias']->image ?>" />
	<?php } ?>

	<?= html_entity_decode($_SESSION['option']->global_MetaTags, ENT_QUOTES) ?>
	<?= html_entity_decode($_SESSION['alias']->meta, ENT_QUOTES) ?>

	<?php require_once '@commons/__head_ga4_events.php'; ?>

	<link rel="canonical" href="<?= SITE_URL_UK ?>">
	<link rel="alternate" hreflang="uk-UA" href="<?= SITE_URL_UK ?>">
	<link rel="alternate" hreflang="ru-UA" href="<?= SITE_URL_RU ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?= SERVER_URL ?>favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?= SERVER_URL ?>style/ws__main.css?v=1.3">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SERVER_URL ?>assets/magnific-popup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="<?= SERVER_URL ?>style/style.css?v=0.7.3">
</head>

<body class="<?= $_SESSION['alias']->alias ?>">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBZH89T" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->


	<?php
	$site_tel_1 = $this->text('+38 096 0000 943', 0);
	$site_tel_2 = $this->text('+38 093 0000 943', 0);
	require_once '@commons/__all_groups.php';

	echo ('<div class="container">');

	include "@commons/header.php";

	if (isset($view_file)) require_once($view_file . '.php');

	echo ('</div>');

	include "@commons/footer.php";
	?>
	<div id="modal-bg"></div>
	<div id="modal-add_success">
		<div class="bg-white">
			<img src="<?= SERVER_URL ?>style/images/logo.png" alt="logo">
			<h4 class="product_name"></h4>
			<h4><?= $this->text('Товар у корзині', 0) ?></h4>
			<div class="flex">
				<a class="close" href="javascript:void(0)"><?= $this->text('Продовжити покупки', 0) ?></a>
				<a href="<?= SITE_URL ?>cart"><?= $this->text('До корзини', 0) ?></a>
			</div>
		</div>
	</div>

	<?php if (!in_array($_SESSION['alias']->alias, ['login', 'signup', 'cart'])) {
		$first_name = $last_name = '';
		if ($this->userIs() && !empty($_SESSION['user']->name)) {
			$name = explode(' ', $_SESSION['user']->name, 2);
			$first_name = $name[0];
			$last_name = $name[1];
		} ?>
		<div id="modal-buyProduct">
			<form action="<?= SITE_URL ?>cart/buyProduct" method="post" class="bg-white type-2">
				<!-- <img src="<?= SERVER_URL ?>style/images/logo.png" alt="logo"> -->
				<h4><?= $this->text('Купити в один клік', 0) ?></h4>
				<!-- <h4 class="product_name"></h4> -->

				<input type="hidden" name="productKey">
				<input type="hidden" name="quantity" min="1" title="<?= $this->text('Кількість од.') ?>">

				<div class="input-group <?= $this->userIs() && !empty($_SESSION['user']->phone) ? 'val' : '' ?>">
					<label for="phone-1"><?= $this->text('Телефон', 0) ?>*</label>
					<input type="text" id="phone-1" name="phone" class="input" minlength="17" value="<?= $this->userIs() ? $_SESSION['user']->phone : '' ?>" required>
					<h5 class="text-danger hide" id="phoneError-1"><?= $this->text('Введіть коректний номер телефону починаючи +380') ?></h5>
				</div>

				<div class="input-group <?= !empty($first_name) ? 'val' : '' ?>">
					<label for="firstname-1"><?= $this->text("Ім'я", 0) ?>*</label>
					<input type="text" id="firstname-1" name="first_name" class="input _validLettersUK" value="<?= $first_name ?>" required>
					<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
				</div>

				<div class="input-group <?= $last_name ? 'val' : '' ?>">
					<label for="lastname-1"><?= $this->text("Прізвище", 0) ?>*</label>
					<input type="text" id="lastname-1" name="last_name" class="input _validLettersUK" value="<?= $last_name ?>" required>
					<h5 class="text-danger hide"><?= $this->text('Тільки кирилиця') ?></h5>
				</div>
				
				<div class="flex w100" style="margin-top: 10px;">
					<a class="close" href="javascript:void(0)"><?= $this->text('Закрити', 0) ?></a>
					<button><img src="<?= SERVER_URL ?>style/icons/detal/shopping-cart.svg" alt="cart" style="height: 15px;margin-bottom: 0;"> <?= $this->text('Купити', 0) ?></button>
				</div>
			</form>
		</div>
	<?php } ?>

	<script type="text/javascript" src="<?= SERVER_URL ?>assets/jquery/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?= SERVER_URL ?>assets/magnific-popup/magnific-popup.min.js"></script>
	<script type="text/javascript" src="<?= SERVER_URL ?>assets/jquery.mask.min.js"></script>
	<script type="text/javascript">
		var SERVER_URL = '<?= SERVER_URL ?>';
		var SITE_URL = '<?= SITE_URL ?>';
		var ALIAS_URL = '<?= SITE_URL . $_SESSION['alias']->alias ?>/';

		var buyProductRecaptchaVerifyCallback = function(response) {
			$('#modal-buyProduct form button').attr('disabled', false);
			$('#modal-buyProduct form button').attr('title', false);
		};
		var buyProductRecaptchaExpiredCallback = function(response) {
			$('#modal-buyProduct form button').attr('disabled', true);
			$('#modal-buyProduct form button').attr('title', 'Заповніть "Я не робот"');
		};
	</script>
	<script type="text/javascript" src="<?= SERVER_URL ?>js/ga4_events.js"></script>
	<script type="text/javascript" src="<?= SERVER_URL ?>js/site.js?v2.0"></script>
	<?php if (!empty($_SESSION['alias']->js_load))
		foreach ($_SESSION['alias']->js_load as $js) {
			echo '<script type="text/javascript" src="' . SERVER_URL . $js . '"></script> ';
		}
	if (!empty($_SESSION['alias']->js_init)) {
		echo "<script> $(document).ready(function() {";
		foreach ($_SESSION['alias']->js_init as $js) {
			echo $js . '; ';
		}
		echo "}) </script>";
	} ?>

	<script type="text/javascript">
		(function(d, w, s) {
			var widgetHash = 'ztk0ko4afd8t9xnvro01',
				gcw = d.createElement(s);
			gcw.type = 'text/javascript';
			gcw.async = true;
			gcw.src = '//widgets.binotel.com/getcall/widgets/' + widgetHash + '.js';
			var sn = d.getElementsByTagName(s)[0];
			sn.parentNode.insertBefore(gcw, sn);
		})(document, window, 'script');
	</script>
</body>

</html>