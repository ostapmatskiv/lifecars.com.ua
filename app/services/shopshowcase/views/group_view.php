<main>
    <?php if(!empty($_SESSION['alias']->image)) { ?>
        <div class="flex m-wrap">
            <div class="w25 text-center m100">
                <img src="<?=IMG_PATH.$_SESSION['alias']->image?>" alt="<?=$_SESSION['alias']->name?>" class="w80" style="max-width: 250px">
                <h1><?=(!empty($group) && !empty($group->parents)) ? $group->parents[0]->name : '' ?> <?=$_SESSION['alias']->name?><br><?=$_SESSION['alias']->list?></h1>
            </div>
            <div class="w75-5 m100">
                <?php require '__head_filters.php'; ?>
            </div>
        </div>
    <?php } else require '__head_filters.php'; ?>
    

    <?php /*
    <div class="flex w50 auto__detal">
    	<?php $brend_id = $model_id = 0; $brend_link = 'parts';
        if(!empty($group))
        {
        	if($group->parent == 0)
        		$brend_id = $group->id;
        	else
        	{
        		$brend_id = $group->parent;
        		$model_id = $group->id;
        	}
        	foreach ($catalogAllGroups as $g) {
        		if($g->id == $brend_id) { ?>
        			<a class="cars__model" href="#" data-group="brends">
    		            <?php if($g->photo) { ?>
                            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
                        <?php } ?>
    		            <div class="detal__text"><?=$g->name?></div>
    		            <i class="fas fa-chevron-down"></i>
    		        </a>
        		<?php $brend_link = $g->link;
        		break; }
        	}
        	if($model_id == 0) { ?>        
    	        <a class="cars__model" href="#" data-group="models">
    	            <img src="/style/images/carslogo/cars.png" alt="car">
    	            <div class="detal__text">Всі моделі</div>
    	            <i class="fas fa-chevron-down"></i>
    	        </a>
    	    <?php } else { foreach ($catalogAllGroups as $g) {
        		if($g->id == $model_id) { ?>
        			<a class="cars__model" href="#" data-group="models">
        				<?php if($g->photo) { ?>
                            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
                        <?php } ?>
    		            <div class="detal__text"><?=$g->name?></div>
    		            <i class="fas fa-chevron-down"></i>
    		        </a>
        		<?php break; }
    	} } } ?>
    </div>
    <div class="flex w66 h-evenly v-end main__logo logo__catalog brends__cars">
    	<?php
		foreach ($catalogAllGroups as $g) { if($g->parent == 0) { ?>
			<a href="<?=SITE_URL.$g->link?>">
				<?php if($g->photo) { ?>
	            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
	            <?php } ?>
	            <div class="logo__text"><?=$g->name?></div>
	        </a>
		<?php } } ?>
    </div>
    <section class="flex h-center wrap cars__catalog models__cars">
    	<a href="<?=SITE_URL.$brend_link?>" class="base__detal">
			<img src="/style/images/carslogo/cars.png" alt="car">
            <div class="detal__text">Всі моделі</div>
        </a>
    	<?php foreach ($catalogAllGroups as $g) { if($g->parent == $brend_id) { ?>
			<a href="<?=SITE_URL.$g->link?>" class="base__detal">
				<?php if($g->photo) { ?>
	            <img src="<?=IMG_PATH.$g->photo?>" alt="<?=$g->name?>">
	            <?php } ?>
	            <div class="detal__text"><?=$g->name?></div>
	        </a>
		<?php } } ?>
    </section>
   
	<?php */ $this->load->js_init('init__parts()'); ?>
        
    <section class="flex sale__catalog pt-50">
        <aside class="w25">
            <div class="hide m-flex">
                <button class="btn w50-5" onclick="$(this).closest('aside').find('form').toggleClass('m-hide')"><?=$this->text('Фільтр')?></button>
                <button class="btn w50-5" onclick="$(this).closest('section.sale__catalog').find('.catalog__sorted').toggleClass('m-hide')"><?=$this->text('Сортувати')?></button>
            </div>
            <form class="m-hide">
                <div class="filter">
                    <p><?=$this->text('Назва товару')?></p>
                    <input type="text" name="name" value="<?=$this->data->get('name')?>" placeholder="<?=$this->text('Пошук за назвою товару', 0)?>">
                </div>
                <?php if(empty($group)) { ?>
                    <div class="filter">
                        <div class="flex v-center">
                            <p><?=$this->text('Марка авто')?></p>
                            <!-- <i class="fas fa-chevron-circle-up"></i> -->
                        </div>
                        <div class="values">
                            <?php $parent_id = []; foreach ($catalogAllGroups as $g) {
                                if($g->parent > 0) continue;
                                $checked = '';
                                if(!empty($_GET['group'])) {
                                    if(is_array($_GET['group']) && in_array($g->id, $_GET['group']))
                                    {
                                        $parent_id[] = $g->id;
                                        $checked = 'checked';
                                    }
                                    else if(is_numeric($_GET['group'] && $_GET['group'] == $g->id))
                                    {
                                        $parent_id[] = $g->id;
                                        $checked = 'checked';
                                    }
                                } elseif(!empty($group)) {
                                    if($group->id == $g->id || $group->parent == $g->id)
                                    {
                                        $parent_id[] = $g->id;
                                        $checked = 'checked';
                                    }
                                } ?>
                                <input type="checkbox" name="group[]" value="<?=$g->id?>" id="group__id-<?=$g->id?>" <?=$checked?>>
                                <label for="group__id-<?=$g->id?>"><?=$g->name?></label>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(!empty($parent_id)) { ?>
                        <div class="filter">
                            <div class="flex v-center">
                                <p><?=$this->text('Модель авто')?></p>
                                <!-- <i class="fas fa-chevron-circle-up"></i> -->
                            </div>
                            <div class="values">
                                <?php foreach ($catalogAllGroups as $g) {
                                    if(!in_array($g->parent, $parent_id)) continue;
                                    $checked = '';
                                    if(!empty($_GET['subgroup'])) {
                                        if(is_array($_GET['subgroup']) && in_array($g->id, $_GET['subgroup']))
                                        {
                                            $parent_id[] = $g->id;
                                            $checked = 'checked';
                                        }
                                        else if(is_numeric($_GET['subgroup'] && $_GET['subgroup'] == $g->id))
                                        {
                                            $parent_id[] = $g->id;
                                            $checked = 'checked';
                                        }
                                    } elseif(!empty($group)) {
                                        if($group->id == $g->id)
                                        {
                                            $parent_id[] = $g->id;
                                            $checked = 'checked';
                                        }
                                    } ?>
                                    <input type="checkbox" name="subgroup[]" value="<?=$g->id?>" id="group__id-<?=$g->id?>" <?=$checked?>>
                                    <label for="group__id-<?=$g->id?>"><?=$g->name?></label>
                                <?php } ?>
                            </div>
                        </div>
                <?php } }
                /*
                <div class="product__type">
                    <div class="flex v-center">
                        <p>Тип товару</p>
                        <i class="fas fa-chevron-circle-up"></i>
                    </div>
                    <form action="#">
                        <input type="checkbox" name="type" id="type__id-1">
                        <label for="type__id-1">Знижка<span>2 345</span></label>
                        <input type="checkbox" name="type" id="type__id-2">
                        <label for="type__id-2">Новинка <span>346</span></label>
                        <input type="checkbox" name="type" id="type__id-3">
                        <label for="type__id-3">Хіт продажу<span>5 687</span></label>
                    </form>
                </div>*/ 
                if($value = $this->data->get('sort'))
                    echo "<input type='hidden' name='sort' value='{$value}' >";
                if(!empty($filters))
                	foreach ($filters as $filter) {
                		if($filter->id == 2)
                        {
                            if($value = $this->data->get($filter->alias))
                                echo "<input type='hidden' name='{$filter->alias}' value='{$value}' >";
                			continue;
                        } ?>
    		            <div class="filter">
    		                <div class="flex v-center">
    		                    <p><?=$filter->name?></p>
    		                    <!-- <i class="fas fa-chevron-circle-up"></i> -->
    		                </div>
    		                <div class="values">
    		                	<?php foreach ($filter->values as $value) {
                                    $checked = (!empty($_GET[$filter->alias]) && is_array($_GET[$filter->alias]) && in_array($value->id, $_GET[$filter->alias])) ? 'checked' : '';
                                    ?>
    		                		<input type="checkbox" name="<?=$filter->alias?>[]" value="<?=$value->id?>" id="value__id-<?=$value->id?>" <?=$checked?>>
    		                    	<label for="value__id-<?=$value->id?>"><?=$value->name?> <span>(<?=$value->count?>)</span></label>
    		                	<?php } ?>
                            </div>
    		            </div>
    		    <?php } ?>
            </form>
        </aside>
        <div class="w75">
            <div class="flex catalog__sorted m-hide" style="margin-top: 10px;">
                <div>
                    <a href="<?=$this->data->get_link('sort', 'price_down')?>" <?=($this->data->get('sort') == 'price_down')?'class="active"':''?>><?=$this->text('Спершу дешеві')?></a>
                    <a href="<?=$this->data->get_link('sort', 'price_up')?>" <?=($this->data->get('sort') == 'price_up')?'class="active"':''?>><?=$this->text('Спершу дорожчі')?></a>
                    <a href="<?=$this->data->get_link('sort', 'name')?>" <?=($this->data->get('sort') == 'name')?'class="active"':''?>>А &mdash; Я</a>
                    <a href="<?=$this->data->get_link('sort', 'name_desc')?>" <?=($this->data->get('sort') == 'name_desc')?'class="active"':''?>>Я &mdash; А</a>
                </div>
                <div class="quantity__goods m-hide">
                    <?=$this->text('Кількість товарів', 0)?> &mdash; <span><?=$_SESSION['option']->paginator_total?></span>
                </div>
            </div>
            <div class="flex wrap sale__wrrap">
                <?php if($products) {
                    foreach ($products as $product) {
                        if($product->availability > 0)
                            require APP_PATH.'views/@commons/__product_subview.php';
                     }
                    foreach ($products as $product) {
                        if($product->availability <= 0)
                            require APP_PATH.'views/@commons/__product_subview.php';
                     }
                     $in_row = 5;
                     if(!empty($filters))
                        $in_row = 4;
                     $add_block = $in_row - count($products) % $in_row;
                     if($add_block < $in_row)
                        for ($i=0; $i < $add_block; $i++) { 
                            echo "<div class='sale__card empty'></div>";
                        } 
                } else { ?>
                    <div class="alert alert-danger w100">
                        <h4><?=$this->text('Товари відсутні!')?></h4>
                        <p><?=$_SESSION['alias']->name?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php $this->load->library('paginator');
    $this->paginator->style('ul', 'flex h-center v-center w50 pagination');
    echo $this->paginator->get(); ?>

    <section>
        <h4><?=$_SESSION['alias']->list?></h4>
        <p><?=html_entity_decode($_SESSION['alias']->text)?></p>
    </section>
</main>

<style>
    .auto__detal a.cars__model {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>