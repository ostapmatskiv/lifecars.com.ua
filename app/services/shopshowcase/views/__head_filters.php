<div class="flex wrap">
<?php if(!empty($filters))
	foreach ($filters as $filter) {
		if($filter->id == 2) {
            foreach ($filter->values as $value) {
                if(empty($value->type)) {
                    $subFilterParentId = empty($value->type) ? $value->id : $value->type;
                    $filter_class = 'm-w50';
                    if(!empty($_GET[$filter->alias])) {
                        $filter_class = $_GET[$filter->alias] == $value->id ? 'm100' : 'm-hide';
                    }
                    ?>
                    <div class="w25 <?= $filter_class ?>">
                        <h4 class="subgroup-name">
                            <a href="<?=$this->data->get_link($filter->alias, $value->id, 'page')?>" <?=isset($_GET[$filter->alias]) && $_GET[$filter->alias] == $value->id ? 'class="active"':''?>>
                                <?php if(!empty($value->photo)) { ?>
                                    <img src="<?=$value->photo?>" alt="<?=$value->name?>">
                                <?php } ?>
                                <div><?=$value->name?> <span>(<?=$value->count?>)</span></div>
                            </a>
                        </h4>
                        <div class="flex column v-start catalog__detal">
                            <?php foreach ($filters as $filter_2) {
                                if($filter_2->id == 2) { 
                                foreach ($filter_2->values as $value) if($value->type == $subFilterParentId) { ?>
                                    <a href="<?=$this->data->get_link($filter_2->alias, $value->id, 'page')?>" <?=isset($_GET[$filter_2->alias]) && ($_GET[$filter_2->alias] == $value->id) ? 'class="active"':''?>>
                                        <?php /* if(!empty($value->photo)) { ?>
                                            <img src="<?=$value->photo?>" alt="<?=$value->name?>">
                                        <?php } */ ?>
                                        <?=$value->name?> <span>(<?=$value->count?>)</span>
                                    </a>
                            <?php } } } ?>
                        </div>
                    </div>
                <?php }
            }
        }
    } ?>
</div>