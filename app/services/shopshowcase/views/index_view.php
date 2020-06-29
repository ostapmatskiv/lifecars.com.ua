<main class="cars__models">
	<?php if(!empty($_SESSION['alias']->text)) { ?>
	    <section class="row">
	        <h4><?=$_SESSION['alias']->list?></h4>
	        <p><?=html_entity_decode($_SESSION['alias']->text)?></p>
	    </section>
	<?php }
	/* ?>
    <div class="flex v-center model__menu">
        <a href="#">Маркі та моделі</a>
        <a href="#">Аксесуари</a>
        <a href="#">Масло та рідина</a>
        <a href="#">Лампочкі</a>
        <a href="#">Акумулятори</a>
    </div> */
    
    $this->load->js_init('init__parts()');
    if(!empty($catalogAllGroups))
		foreach ($catalogAllGroups as $group) { if($group->parent == 0) { ?>
			<div class="flex v-center cars__model" data-group="<?=$group->alias?>" data-link="<?=$group->link?>">
				<div class="flex v-center model_info">
					<?php if($group->photo) { ?>
					<div>
						<img src="<?=IMG_PATH.$group->photo?>" alt="<?=$group->name?>">
					</div>
					<?php } ?>
					<span><?=$group->name?></span>
				</div>
				<button class="flex h-center v-center models__btn">
					<img src="style/icons/model/arrow-down.svg" alt="arrow-down">
				</button>
			</div>
			<div class="flex h-start wrap model_cars <?=$group->alias?>__cars">
			<?php foreach ($catalogAllGroups as $model) {
				if($model->parent == $group->id) { ?>
				<a class="base__detal" href="<?=SITE_URL.$model->link?>">
					<?php if($model->photo) { ?>
						<img src="<?=IMG_PATH.$model->photo?>" alt="<?=$model->name?>">
					<?php } ?>
			        <img src="style/images/carslogo/cars.png" alt="car">
			        <div class="detal__text"><?=$model->name?></div>
			    </a>
			<?php } } ?>
			</div>
		<?php } } ?>
</main>