<main>
    <h1><?=$_SESSION['alias']->name?></h1>

    <?php
    if($_SESSION['alias']->list != '')
        echo ("<strong>{$_SESSION['alias']->list}</strong>");
    if($_SESSION['alias']->images) { ?>

    <!-- OWL SLIDER -->
    <div class="owl-carousel buttons-autohide controlls-over" data-plugin-options='{"items": 1, "autoPlay": 4500, "autoHeight": false, "navigation": true, "pagination": true, "transitionStyle":"fadeUp", "progressBar":"false"}'>
        <?php foreach ($_SESSION['alias']->images as $photo) { ?>
            <a class="lightbox" href="<?=IMG_PATH.$photo->path?>" data-plugin-options='{"type":"image"}'>
                <img class="img-responsive" src="<?=IMG_PATH.$photo->path?>" alt="<?=$photo->title?>" />
            </a>
        <?php } ?>
    </div>
    <!-- /OWL SLIDER -->
    <?php }
    
    echo($_SESSION['alias']->text);

    if($_SESSION['alias']->videos) {
        echo('<div class="margin-bottom-20 embed-responsive embed-responsive-16by9">');
        $this->video->show_many($_SESSION['alias']->videos);
        echo('</div>');
    }
    ?>
</main>