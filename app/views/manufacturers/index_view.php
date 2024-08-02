<main class="cars__manufacturers">
    <h1><?=$_SESSION['alias']->name?></h1>
    
   <div class="flex manufacturers__name">
    <div class="nane__english">
        <?php 
        if($manufactures) {
            $letter = '';
            foreach ($manufactures as $m) {
                if(!empty($m->name))
                {
                    $l = substr($m->name, 0, 1);
                    $l = strtoupper($l);
                    if($l != $letter)
                    {
                        echo "<a href=\"#{$l}\">{$l}</a>";
                        $letter = $l;
                    }
                }
            }
        } ?>
    </div>
   </div>
   <section class="catalog__name">
    <?php if($manufactures) {
            $letter = '';
            foreach ($manufactures as $m) {
                if(!empty($m->name))
                {
                    $img = '/style/images/no_image2.png';
                    if(!empty($m->photo))
                        $img = '/images/parts/options/1-manufacturer/'.$m->photo;
                    $l = substr($m->name, 0, 1);
                    $l = strtoupper($l);
                    if($l != $letter)
                    {
                        if($letter != '')
                            echo "</div></div>";
                        echo '<div class="flex h-start" id="'.$l.'">
                            <label for="#">'.$l.'</label>';
                        $letter = $l;
                        echo '<div class="flex wrap h-start">';
                    } ?>
                    <a href="<?=SITE_URL?>manufacturers/<?=$m->link?>"> 
                        <figure>
                            <img src="<?=$img?>" alt="<?=$m->name?>">
                            <figcaption><?=$m->name?></figcaption>
                        </figure>
                    </a>
                <?php }
            }
        } ?>
   </section>
</main>