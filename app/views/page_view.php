<!DOCTYPE html>
<html lang="<?=$_SESSION['language']?>" prefix="og: http://ogp.me/ns#">
<head>
	<title><?=$_SESSION['alias']->title?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="title" content="<?=$_SESSION['alias']->title?>">
    <meta name="description" content="<?=$_SESSION['alias']->description?>">
    <meta name="keywords" content="<?=$_SESSION['alias']->keywords?>">
    <meta name="author" content="webspirit.com.ua">

    <meta property="og:locale"             content="<?=$_SESSION['language']?>_UA" />
    <meta property="og:title"              content="<?=$_SESSION['alias']->title?>" />
    <meta property="og:description"        content="<?=$_SESSION['alias']->description?>" />
    	<?php if(!empty($_SESSION['alias']->image)) { ?>
	<meta property="og:image"			   content="<?=IMG_PATH.$_SESSION['alias']->image?>" />
    	<?php } ?>

	<?=html_entity_decode($_SESSION['option']->global_MetaTags, ENT_QUOTES)?>
    <?=html_entity_decode($_SESSION['alias']->meta, ENT_QUOTES)?>

	<link rel="canonical" href="">
	<link rel="alternate" hreflang="uk-UA" href="<?=SITE_URL_UK?>">
	<link rel="alternate" hreflang="ru-UA" href="<?=SITE_URL_RU?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?=SERVER_URL?>favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>style/ws__main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/magnific-popup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>style/style.css">
</head>
<body>
	<?php
		require_once '@commons/__all_groups.php';
		echo('<div class="container">');

		include "@commons/header.php";

		if(isset($view_file)) require_once($view_file.'.php');

		echo('</div>');
		
		include "@commons/footer.php";
	?>
	<div id="modal-bg"></div>
	<div id="modal-add_success">
		<img src="<?=SERVER_URL?>style/images/logo.png" alt="logo">
		<h4 class="product_name"></h4>
		<h4><?=$this->text('Товар у корзині', 0)?></h4>
		<div class="flex">
			<a class="close" href="#"><?=$this->text('Продовжити покупки', 0)?></a>
			<a href="<?=SITE_URL?>cart"><?=$this->text('До корзини', 0)?></a>
		</div>
	</div>

	<script type="text/javascript" src="<?=SERVER_URL?>assets/jquery/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?=SERVER_URL?>assets/magnific-popup/magnific-popup.min.js"></script>
	<script type="text/javascript">
		var SERVER_URL = '<?=SERVER_URL?>';
		var SITE_URL = '<?=SITE_URL?>';
		var ALIAS_URL = '<?=SITE_URL.$_SESSION['alias']->alias?>/';
	</script>
	<script type="text/javascript" src="<?=SERVER_URL?>js/site.js"></script>
	<?php if(!empty($_SESSION['alias']->js_load))
		foreach ($_SESSION['alias']->js_load as $js) {
			echo '<script type="text/javascript" src="'.SERVER_URL.$js.'"></script> ';
		}
		if(!empty($_SESSION['alias']->js_init)) {
			echo "<script> $(document).ready(function() {";
			foreach ($_SESSION['alias']->js_init as $js) {
				echo $js.'; ';
			}
			echo "}) </script>";
		} ?>
</body>
</html>