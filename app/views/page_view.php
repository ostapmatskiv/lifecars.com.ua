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
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/magnific-popup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>style/ws__main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>style/style.css">
	
</head>
<body>
	<?php
		echo('<div class="container">');

		include "@commons/header.php";

		if(isset($view_file)) require_once($view_file.'.php');

		echo('</div>');
		
		include "@commons/footer.php";
	?>

	<script type="text/javascript" src="<?=SERVER_URL?>assets/jquery/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?=SERVER_URL?>assets/magnific-popup/magnific-popup.min.js"></script>
	<script type="text/javascript" src="<?=SERVER_URL?>assets/slick/slick.min.js"></script>
	<script type="text/javascript" src="js/user.js"></script>
	<?php
		if(!empty($_SESSION['alias']->js_load)) {
			foreach ($_SESSION['alias']->js_load as $js) {
				echo '<script type="text/javascript" src="'.SITE_URL.$js.'"></script> ';
			}
		}
	?>
	<script>
		var SERVER_URL = '<?=SERVER_URL?>';
		var SITE_URL = '<?=SITE_URL?>';
		var ALIAS_URL = '<?=SITE_URL.$_SESSION['alias']->alias?>/';
	    $(document).ready(function() {
	        <?php
			if(!empty($_SESSION['alias']->js_init)) {
				foreach ($_SESSION['alias']->js_init as $js) {
					echo $js.'; ';
				}
			}
			?>
	    });
	</script>	
</body>
</html>