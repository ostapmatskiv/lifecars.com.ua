<?php $_SESSION['alias']->js_load[] = 'assets/white-lion/wl_images.js'; ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
            	<div class="panel-heading-btn">
                	<a href="<?=SITE_URL?>admin/wl_images/<?=$alias->alias?>" class="btn btn-info btn-xs">До зображень <?=$alias->alias?></a>
                </div>
                <h4 class="panel-title">Редагувати зміну розміру</h4>
            </div>
            <div class="panel-body">
    	        <form action="<?=SITE_URL?>admin/wl_images/save" method="POST" class="form-horizontal">
    	        	<input type="hidden" name="id" value="<?=$wl_image->id?>">
                    <input type="hidden" name="alias" value="<?=$wl_image->alias?>">
    	        	<input type="hidden" name="alias_name" value="<?=$alias->alias?>">
    	        	<div class="form-group">
                        <label class="col-md-3 control-label">Стан</label>
                        <div class="col-md-9">
                            <label><input type="checkbox" name="active" value="1" <?=($wl_image->active)?'checked':''?>> зміну розміру активна</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Назва</label>
                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control" value="<?=$wl_image->name?>" required placeholder="Назва/признчення мініатюри" />
                        </div>
                    </div>
                    <?php if($wl_image->prefix == '') { ?>
                        <div class="alert alert-warning fade in m-b-15">
                        <strong>Увага!</strong>
                        <p>Префікс не задано! При завантаженні зображення зміна розміру впливатиме на оригінал.</p>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Префікс</label>
                        <div class="col-md-9">
                            <input type="text" name="prefix" class="form-control" value="<?=$wl_image->prefix?>" placeholder="Префікс мініатюри" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Тип</label>
                        <div class="col-md-9">
                            <select name="maintype" class="form-control" onchange="setType(this)">
                            	<option value="resize" <?=(in_array($wl_image->type, array(1, 11, 12))) ? 'selected':''?>>resize (збереження пропорцій)</option>
                            	<option value="preview" <?=(in_array($wl_image->type, array(2, 21, 22))) ? 'selected':''?>>preview (точні розміри)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="div-type-resize" <?=(in_array($wl_image->type, array(1, 11, 12))) ? '' : 'style="display:none"'?>>
                        <label class="col-md-3 control-label">Режим роботи</label>
                        <div class="col-md-9">
                            <select id="type-resize" name="<?=(in_array($wl_image->type, array(1, 11, 12))) ? 'type' : 'none'?>" class="form-control">
                                <option value="1" <?=($wl_image->type == 1) ? 'selected':''?>>Авто (по довшій стороні)</option>
                                <option value="11" <?=($wl_image->type == 11) ? 'selected':''?>>По ширині (висота змінна)</option>
                                <option value="12" <?=($wl_image->type == 12) ? 'selected':''?>>По висоті (ширина змінна)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="div-type-preview" <?=(in_array($wl_image->type, array(2, 21, 22))) ? '' : 'style="display:none"'?>>
                        <label class="col-md-3 control-label">Режим роботи</label>
                        <div class="col-md-9">
                            <select id="type-preview" name="<?=(in_array($wl_image->type, array(2, 21, 22))) ? 'type' : 'none'?>" class="form-control">
                                <option value="2" <?=($wl_image->type == 2) ? 'selected':''?>>Авто по центру</option>
                                <option value="21" <?=($wl_image->type == 21) ? 'selected':''?>>Орієнтація на верхній лівий край</option>
                                <option value="22" <?=($wl_image->type == 22) ? 'selected':''?>>Орієнтація на нижній правий край</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-3 control-label">Ширина (px)</label>
                        <div class="col-md-9">
                            <input type="number" name="width" class="form-control" value="<?=$wl_image->width?>" required placeholder="Ширина" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Висота (px)</label>
                        <div class="col-md-9">
                            <input type="number" name="height" class="form-control" value="<?=$wl_image->height?>" required placeholder="Висота" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Якість (%)</label>
                        <div class="col-md-9">
                            <input type="number" name="quality" class="form-control" value="<?=$wl_image->quality?>" required placeholder="Якість" min="50" max="100" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-sm btn-success ">Зберегти</button>
                        </div>
                    </div>
    	        </form>
            </div>
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Водяний знак</h4>
            </div>
            <div class="panel-body">
                <form action="<?=SITE_URL?>admin/wl_images/save_watermark" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$wl_image->id?>">

                    <div class="form-group">
                        <label class="col-md-3 control-label">Зображення</label>
                        <div class="col-md-9">
                            <select name="use_watermark" class="form-control" onchange="watermarkMode(this)">
                                <option value="0" <?= empty($wl_image->watermark) ? 'selected':''?>>без водяного знаку</option>
                                <option value="1" <?= !empty($wl_image->watermark) ? 'selected':''?>>накласти</option>
                            </select>
                        </div>
                    </div>

                    <div id="watermark-options" class="<?= empty($wl_image->watermark) ? 'hide' : '' ?>">
                        <?php $watermark = new stdClass();
                        $watermark->file_path = '';
                        $watermark->top = $watermark->bottom = $watermark->left = $watermark->right = $watermark->width = $watermark->height = 0;
                        $watermark->opacity = 50;

                        if(!empty($wl_image->watermark)) {
                            $watermark = unserialize($wl_image->watermark);
                        } ?>

                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Файл</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>

                        <?php if(!empty($watermark->file_path)) { ?>
                            <div class="text-center">
                                <img src="<?= SITE_URL . $watermark->file_path ?>" style="max-width: 350px;">
                            </div>
                        <?php } ?>

                        <h4 class="text-center">Розміри</h4>
                        <p class="text-center">0px - без змін</p>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ширина (px)</label>
                            <div class="col-md-9">
                                <input type="number" name="width" class="form-control" value="<?=$watermark->width?>" min="0" max="<?=$wl_image->width?>" required placeholder="Ширина" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Висота (px)</label>
                            <div class="col-md-9">
                                <input type="number" name="height" class="form-control" value="<?=$watermark->height?>" min="0" max="<?=$wl_image->height?>" required placeholder="Висота" />
                            </div>
                        </div>

                        <h4 class="text-center">Розташування</h4>
                        <p class="text-center">Лівий верхній кут має пріорітет. Якщо 0 - тоді нижній правий кут</p>

                        <?php foreach(['left' => 'Лівий бік', 'top' => 'Згори', 'right' => 'Правий бік', 'bottom' => 'Знизу'] as $key => $title) { ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?= $title ?> (px)</label>
                                <div class="col-md-9">
                                    <input type="number" name="<?= $key ?>" class="form-control" value="<?= $watermark->$key ?>" required placeholder="<?= $title ?> (px)" />
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Інтенсивність [0-100]</label>
                            <div class="col-md-9">
                                <input type="number" name="opacity" class="form-control" value="<?=$watermark->opacity?>" min="0" max="100" required placeholder="Інтенсивність" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-sm btn-success">Зберегти</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4 class="panel-title">Видалити зміну розміру</h4>
            </div>
            <div class="panel-body">
                <form action="<?=SITE_URL?>admin/wl_images/delete" method="POST" class="form-horizontal">
                    <input type="hidden" name="id" value="<?=$wl_image->id?>">
                    <input type="hidden" name="alias_name" value="<?=$alias->alias?>">
                    <input type="hidden" name="alias" value="<?=$wl_image->alias?>">
                    <input type="hidden" name="prefix" value="<?=$wl_image->prefix?>">
                    <?php $number = rand(0, 1000); ?>
                    <input type="hidden" name="close_number" value="<?=$number?>">
                    <?php if(isset($_SESSION['notify_error_delete'])) { ?>
                        <div class="alert alert-danger fade in m-b-15">
                        <strong>Помилка!</strong>
                        <?=$_SESSION['notify_error_delete']?>
                        <span class="close" data-dismiss="alert">&times;</span>
                    </div>
                    <?php unset($_SESSION['notify_error_delete']); } ?>
                    <div class="row" style="padding: 0 15px;">
                        <p>Увага! При видаленні зміни розміру зображення, видаляться всі картинки з префіксом <strong><?=$wl_image->prefix?></strong> у <strong><?=$alias->alias?></strong>.
                        </p>
                    </div>
                    <div class="text-center">Захисту від випадкового видалення:</div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Число <b><?=$number?></b></label>
                        <div class="col-md-9">
                            <input type="number" name="user_namber" class="form-control" min="0" max="1000" required placeholder="Введіть число зліва" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-sm btn-danger ">Видалити</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">Скопіювати зміну розміру</h4>
            </div>
            <div class="panel-body">
                <form action="<?=SITE_URL?>admin/wl_images/copy" method="POST" class="form-horizontal">
                    <input type="hidden" name="id" value="<?=$wl_image->id?>">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Копія для головної адреси</label>
                        <div class="col-md-9">
                            <select name="alias" class="form-control">
                                <?php 
                                $aliases = $this->db->getAllData('wl_aliases');
                                foreach ($aliases as $a) {
                                    $selected = '';
                                    if($a->id == $alias->id) $selected = 'selected';
                                    echo "<option value='{$a->id}' {$selected}>{$a->alias}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-3 control-label">Нова назва</label>
                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control" value="<?=(isset($_POST['name'])) ? $this->data->post('name') : $wl_image->name?>" required placeholder="Назва/признчення мініатюри" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Новий префікс</label>
                        <div class="col-md-9">
                            <input type="text" name="prefix" class="form-control" value="<?=(isset($_POST['prefix'])) ? $this->data->post('prefix') : $wl_image->prefix?>" required placeholder="Префікс мініатюри" />
                        </div>
                    </div>
                    <?php $number = rand(0, 1000); ?>
                    <input type="hidden" name="close_number" value="<?=$number?>">
                    <div class="text-center">Захисту від випадкового копіювання:</div>
                    <?php if(isset($_SESSION['notify_error_copy'])) { ?>
                        <div class="alert alert-danger fade in m-b-15">
                        <strong>Помилка!</strong>
                        <?=$_SESSION['notify_error_copy']?>
                        <span class="close" data-dismiss="alert">&times;</span>
                    </div>
                    <?php unset($_SESSION['notify_error_copy']); } ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Число <b><?=$number?></b></label>
                        <div class="col-md-9">
                            <input type="number" name="user_namber" class="form-control" min="0" max="1000" required placeholder="Введіть число зліва" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-sm btn-info ">Скопіювати</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">Видалити зображення з префіксом <strong><?=$wl_image->prefix?></strong></h4>
            </div>
            <div class="panel-body">
                <form action="<?=SITE_URL?>admin/wl_images/deleteImages" method="POST" class="form-horizontal">
                    <input type="hidden" name="alias" value="<?=$wl_image->alias?>">
                    <input type="hidden" name="prefix" value="<?=$wl_image->prefix?>">
                    <?php if(isset($_SESSION['notify_error_deleteImages'])) { ?>
                        <div class="alert alert-danger fade in m-b-15">
                        <strong>Помилка!</strong>
                        <?=$_SESSION['notify_error_deleteImages']?>
                        <span class="close" data-dismiss="alert">&times;</span>
                    </div>
                    <?php unset($_SESSION['notify_error_deleteImages']); } ?>
                    <div class="row" style="padding: 0 15px;">
                        <p>Видалити зображення з префіксом <strong><?=$wl_image->prefix?></strong> у 
                            <?php foreach ($aliases as $a) {
                                if($a->id == $wl_image->alias)
                                {
                                    echo("<strong>{$a->alias}</strong>");
                                    break;
                                }
                            } ?>. Нові зображення будуть згенеровано автоматично за запитом браузера згідно заданих розмірів та режиму роботи ресайзера.
                        </p>
                    </div>
                    <?php $number = rand(0, 1000); ?>
                    <input type="hidden" name="close_number" value="<?=$number?>">
                    <div class="text-center">Захисту від випадкового копіювання:</div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Число <b><?=$number?></b></label>
                        <div class="col-md-9">
                            <input type="number" name="user_namber" class="form-control" min="0" max="1000" required placeholder="Введіть число зліва" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-sm btn-info ">Видалити зображення</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function watermarkMode(e) {
    if(e.value == '1') {
        $('#watermark-options').removeClass('hide');
    } else {
        $('#watermark-options').addClass('hide');
    }
}
</script>