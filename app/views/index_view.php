<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/slick/slick.css">
<?php $this->load->js('assets/slick/slick.min.js'); 
$this->load->js_init('init__main()'); ?>

<main>
    <h1 class="hide m-block"><?=$_SESSION['alias']->name?></h1>

    <?php if(!empty($catalogAllGroups)) { ?>
    <div class="flex w50 m100 h-evenly v-end m-wrap m-h-between main__logo m-m0">
        <?php foreach ($catalogAllGroups as $group) {
            if($group->parent == 0) { ?>
                <a href="<?=SITE_URL.'parts/'.$group->alias?>" data-group="<?=$group->alias?>">
                    <?php if($group->photo) { ?>
                        <img src="<?=IMG_PATH.'parts/-'.$group->id.'/'.$group->photo?>" alt="<?=$group->name?>">
                    <?php } ?>
                    <div class="logo__text"><?=$group->name?></div>
                    <i class="fas fa-chevron-down m-hide"></i>
                </a>
        <?php } } ?>
    </div>
    <?php foreach ($catalogAllGroups as $group) {
            if($group->parent == 0) { ?>
                <section class="flex h-center wrap cars__base models__<?=$group->alias?>">
                    <?php foreach ($catalogAllGroups as $model) {
                        if($model->parent == $group->id) { ?>
                            <a href="<?=SITE_URL.'parts/'.$group->alias.'/'.$model->alias?>" class="base__detal">
                                <?php if($model->photo) { ?>
                                    <img src="<?=IMG_PATH.'parts/-'.$model->id.'/'.$model->photo?>" alt="<?=$model->name?>">
                                <?php } ?>
                                <div class="detal__text"><?=$model->name?></div>
                            </a>
                    <?php } } ?>
                </section>
        <?php } } ?>
    <?php } ?>
   
   <div class="slick__main m-hide" id="main__slick">
        <div>
            <img src="style/images/main_owl/auto1.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto2.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto3.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto4.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto5.png" alt="auto">
        </div>
        <div>
            <img src="style/images/main_owl/auto6.png" alt="auto">
        </div>
    </div>
    <section class="sale pt-50">
        <?php /*
        <div class="flex v-center sale__nav">
            <a href="#">Новинки &mdash; 5</a>
            <a href="#">Хіт продажу &mdash; 238</a>
            <a href="#">Знижки &mdash; 520</a>
            <a href="#">Обзори</a>
            <a href="#">Поради</a>
        </div> */ ?>
        <div class="flex wrap sale__wrrap  m-m0">
            <?php if($products = $this->load->function_in_alias('parts', '__get_Products', ['sort' => 'id DESC', 'limit' => 10, 'availability' => 1]))
            foreach($products as $product)
                require '@commons/__product_subview.php'; ?>
        </div>
    </section>
    
</main>