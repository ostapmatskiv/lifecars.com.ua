<link rel="stylesheet" type="text/css" href="<?=SERVER_URL.'style/'.$_SESSION['alias']->alias.'/library.css'?>">

<main class="flex">
	<article class="w75">
		<h1><?=$_SESSION['alias']->name?></h1>
		<?php if($_SESSION['alias']->list)
				echo "<p>{$_SESSION['alias']->list}</p>";
		if(!empty($_SESSION['alias']->images)) {
			echo '<div class="owl-carousel owl-theme">';
			foreach ($_SESSION['alias']->images as $image) {
				if($image->title != $_SESSION['alias']->name)
				 	echo '<a href="'.SITE_URL.$image->title.'"><img src="'.IMG_PATH.$image->path.'"></a>';
				else
				 	echo '<img src="'.IMG_PATH.$image->path.'">';
				}
			echo "</div>";
			echo '<link rel="stylesheet" href="'.SERVER_URL.'assets/OwlCarousel2/assets/owl.carousel.min.css">';
			echo '<link rel="stylesheet" href="'.SERVER_URL.'assets/OwlCarousel2/assets/owl.theme.default.min.css">';
			$this->load->js('assets/OwlCarousel2/owl.carousel.min.js');
			$this->load->js_init("$('.owl-carousel').owlCarousel({ loop:true, items:1, nav:true, dots:false, navText:['<i class=\"fas fa-arrow-left\"></i>', '<i class=\"fas fa-arrow-right\"></i>'] })");
		}
		echo $_SESSION['alias']->text; ?>
	</article>
	<aside class="w25">
		<?php if($articles = $this->library_model->getArticles($article->group, $article->id))
			foreach ($articles as $aside) { ?>
				<figure class="bubba">
					<?php if($aside->photo) { ?>
						<img src="<?=IMG_PATH.$aside->photo?>" alt="<?=$aside->name?>">
					<?php } ?>
					<figcaption>
						<h2><?=$aside->name?></h2>
						<?php if($aside->list) { ?>
							<p><?=$this->data->getShortText($aside->list, 80)?></p>
						<?php } ?>
						<a href="<?=SITE_URL.$aside->link?>"><?=$aside->name?></a>
					</figcaption>			
				</figure>
			<?php } ?>
	</aside>
</main>