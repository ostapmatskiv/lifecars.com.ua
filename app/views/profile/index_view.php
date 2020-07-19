<link href="<?=SERVER_URL?>style/profile.css"rel="stylesheet" />

<main>
    <h1><?=$this->text('Кабінет клієнта').' '.$user->name?></h1>

    <div class="flex">
        <aside class="w20 m-hide">
            <?php $user_photo = ($user->photo) ? IMG_PATH.'profile/s_'.$user->photo : IMG_PATH.'empty-avatar.png';
            if(isset($_SESSION['alias']->link) && $_SESSION['alias']->link == 'profile/edit') { ?>
                <div id="photo-block" class="mob_user_photo">
                    <img id="photo" src="<?=$user_photo?>">
                    <img id="loading" src="<?=SERVER_URL?>style/images/icon-loading.gif" >
                </div>
            <?php }
                else
                    echo '<img src="'.$user_photo.'" class="mob_user_photo">';

            if($this->userIs()) { ?>
                <a href="<?= SITE_URL?>profile/<?=$user->alias?>" <?=($_SESSION['alias']->alias == 'cart') ? 'class="active"' : ''?>><i class="fas fa-shopping-cart"></i> <?=$this->text('Мої замовлення')?></a>
                <!-- <a href="<?= SITE_URL?>profile/<?=$user->alias?>"><i class="fa fa-user"></i> <?=$this->text('Профіль')?></a> -->
                <a href="<?=SITE_URL?>profile/edit" <?=(isset($_SESSION['alias']->link) && $_SESSION['alias']->link == 'profile/edit') ? 'class="active"' : ''?>><i class="fas fa-user-cog"></i> <?=$this->text('Редагувати профіль')?></a>

                <?php $where_alias = array('alias' => '#ac.alias2', 'content' => '0');
                if($_SESSION['language'])
                    $where_alias['language'] = $_SESSION['language'];
                $this->db->select('wl_aliases_cooperation as ac', 'alias2 as id', array('alias1' => '<0', 'type' => '__link_profile'));
                $this->db->join('wl_aliases', 'alias, admin_ico as ico', '#ac.alias2');
                $this->db->join('wl_ntkd', 'name', $where_alias);
                $this->db->order('alias1');

                if($links = $this->db->get('array'))
                foreach ($links as $link) { ?>
                    <a href="<?=SITE_URL.$link->alias?>" <?=($_SESSION['alias']->id == $link->id) ? 'class="active"' : ''?>><i class="fa <?=$link->ico?>"></i> <?=$link->name?></a>
                <?php } ?>
            <?php } if($this->userCan()) { ?>
                <a href="<?=SITE_URL?>admin"><i class="fas fa-cogs"></i> Панель керування</a>
            <?php } ?>

            <a href="<?=SITE_URL?>logout"><i class="fas fa-sign-out-alt"></i> Вийти</a>
        </aside>
        <article class="w80-5 m100">
            <?php if(!empty($_SESSION['notify']->errors)) { ?>
               <div class="alert alert-danger">
                    <span class="close" data-dismiss="alert">×</span>
                    <h4><i class="fas fa-exclamation-triangle"></i> <?=(isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : 'Помилка!'?></h4>
                    <p><?=$_SESSION['notify']->errors?></p>
                </div>
            <?php } elseif(!empty($_SESSION['notify']->success)) { ?>
                <div class="alert alert-success">
                    <span class="close" data-dismiss="alert">×</span>
                    <h4><i class="fas fa-check"></i> <?=(isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : 'Успіх!'?></h4>
                    <p><?=$_SESSION['notify']->success?></p>
                </div>
            <?php } unset($_SESSION['notify']);

            if(!empty($sub_page))
                require_once $sub_page;
            ?>
        </article>
    </div>
</main>