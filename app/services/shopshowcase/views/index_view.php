<link rel="stylesheet" type="text/css" href="<?=SERVER_URL.'style/'.$_SESSION['alias']->alias.'/shop.css'?>">

<main class="container">
	<div class="row">
		<h1><?=$_SESSION['alias']->name?></h1>
	</div>
	<section class="groups">
		<?php if(!empty($catalogAllGroups))
			foreach ($catalogAllGroups as $group) { ?>
				<figure>
					<?php if($group->photo) { ?>
						<img src="<?=IMG_PATH.$group->photo?>" alt="<?=$group->name?>">
					<?php } ?>
					<figcaption>
						<h2><?=$group->name?></h2>
						<?php if($group->list) { ?>
							<p><?=$group->list?></p>
						<?php } ?>
						<a href="<?=SITE_URL.$group->link?>"><?=$group->name?></a>
					</figcaption>			
				</figure>
			<?php }
			$addDiv = count($catalogAllGroups) % 3;
			while ($addDiv++ < 3) {
				echo "<figure class='empty'></figure>";
			} ?>
	</section>
	<?php if(!empty($_SESSION['alias']->text)) { ?>
	    <section class="row">
	        <h4><?=$_SESSION['alias']->list?></h4>
	        <p><?=html_entity_decode($_SESSION['alias']->text)?></p>
	    </section>
	<?php } ?>
</main>