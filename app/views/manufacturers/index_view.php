<main class="cars__manufacturers">
    <h1><?=$_SESSION['alias']->name?></h1>
    
   <div class="flex manufacturers__name">
    <div class="nane__english">
        <?php $manufactures = $this->db->select('s_shopshowcase_options as o', 'id, photo', ['group' => -1])
                                        ->join('s_shopshowcase_options_name as n', 'name', ['option' => '#o.id', 'language' => $_SESSION['language']])
                                        ->order('name', 'n')
                                        ->get('array');
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
                    $l = substr($m->name, 0, 1);
                    $l = strtoupper($l);
                    if($l != $letter)
                    {
                        if($letter != '')
                            echo "</div>";
                        echo '<div class="flex wrap h-start" id="'.$l.'">
                            <label for="#">'.$l.'</label>';
                        $letter = $l;
                    } ?>
                    <a href="#"> 
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