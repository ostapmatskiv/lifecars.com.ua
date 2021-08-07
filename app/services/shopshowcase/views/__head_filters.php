<?php $subFilterKey = 2; $subFilterParentId = 0;
if(!empty($filters))
	foreach ($filters as $filter) {
		if($filter->id == 2) { $subFilterKey = $filter->alias; ?>
		<h4 class="subgroup-name">Група</h4>
		<div class="flex h-start wrap catalog__detal">
            <a href="<?=$this->data->get_link($filter->alias, '', 'page')?>" <?=empty($_GET[$filter->alias])?'class="active"':''?>>
                <!-- <img src="/style/icons/catalog/component.svg" alt="all"> -->
                <div><?=$this->text('Всі запчастини')?></div>
            </a>
			<?php foreach ($filter->values as $value) {
                if(isset($_GET[$filter->alias]) && $_GET[$filter->alias] == $value->id)
                    $subFilterParentId = empty($value->type) ? $value->id : $value->type;
            }
            foreach ($filter->values as $value) {
            if(empty($value->type)) {  ?>
				<a href="<?=$this->data->get_link($filter->alias, $value->id, 'page')?>" <?=isset($_GET[$filter->alias]) && $subFilterParentId == $value->id ?'class="active"':''?>>
					<?php if(!empty($value->photo)) { ?>
			            <img src="<?=$value->photo?>" alt="<?=$value->name?>">
			        <?php } ?>
		            <div><?=$value->name?> <span>(<?=$value->count?>)</span></div>
		        </a>
        	<?php } } ?>
        </div>
<?php } }



if(!empty($filters) && isset($_GET[$subFilterKey])) { ?>
<h4 class="subgroup-name">Підгрупа</h4>
<div class="flex h-start wrap catalog__detal">
    <?php foreach ($filters as $filter) {
        if($filter->id == 2) { 
        foreach ($filter->values as $value) if($value->type == $subFilterParentId) { ?>
            <a href="<?=$this->data->get_link($filter->alias, $value->id, 'page')?>" <?=isset($_GET[$filter->alias]) && ($_GET[$filter->alias] == $value->id) ?'class="active"':''?>>
                <?php if(!empty($value->photo)) { ?>
		            <img src="<?=$value->photo?>" alt="<?=$value->name?>">
		        <?php } ?>
	            <div><?=$value->name?> <span>(<?=$value->count?>)</span></div>
            </a>
    <?php } } } ?>
</div>
<?php } ?>