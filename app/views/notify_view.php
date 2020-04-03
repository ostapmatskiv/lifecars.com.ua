<div class="row">
    <div class="container content">
        <?php if(!empty($errors)): ?>
           <div class="alert alert-danger fade in">
                <h4><?=$this->text('Помилка!', 0)?></h4>
                <p><?=$errors?></p>
                <p>
                    <?php if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){ ?>
                        <a class="btn-u btn-u-red" href="<?=$_SERVER['HTTP_REFERER']?>"><?=$this->text('Повернутися назад!', 0)?></a>
                    <?php } ?>
                    <a class="btn-u btn-u-sea" href="<?=SITE_URL?>"><?=$this->text('На головну!', 0)?></a>
                </p>
            </div>
        <?php elseif(!empty($success)): ?>
            <div class="alert alert-success fade in">
                <h4><?=$this->text('Успіх!', 0)?></h4>
                <p><?=$success?></p>
                <p>
                    <?php if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){ ?>
                        <a class="btn-u btn-u-red" href="<?=$_SERVER['HTTP_REFERER']?>"><?=$this->text('Повернутися назад!', 0)?></a>
                    <?php } ?>
                    <a class="btn-u btn-u-sea" href="<?=SITE_URL?>"><?=$this->text('На головну!', 0)?></a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
