<form action="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/storage_save"?>" method="POST" class="form-horizontal">
    <input type="hidden" name="storage_id" value="<?= $storage->id ?>">

    <div class="form-group">
        <label for="storage-name" class="col-sm-3 control-label">Провайдер (парсер)</label>
        <div class="col-sm-9">
            <select name="active" class="form-control">
                <?php foreach (['Відключено', 'Активний'] as $id => $name) {
                    $selected = $id == $storage->active ? 'selected' : '';
                    echo "<option value=\"{$id}\" {$selected}>{$name}</option>";
                } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="storage-name" class="col-sm-3 control-label">Провайдер (парсер)</label>
        <div class="col-sm-9">
            <select name="provider" class="form-control">
                <?php $providers = scandir(APP_PATH . 'services/supply/@providers');
                foreach ($providers as $provider) {
                    if ($provider !== '.' && $provider !== '..') {
                        $option = pathinfo($provider, PATHINFO_FILENAME);
                        $selected = $option == $storage->provider ? 'selected' : '';
                        echo "<option value=\"{$option}\" {$selected}>{$option}</option>";
                    }
                } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="storage-name" class="col-sm-3 control-label">Назва складу</label>
        <div class="col-sm-9">
            <input type="text" name="name" value="<?= $storage->name ?>" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <label for="storage-link" class="col-sm-3 control-label">Посилання на прайс</label>
        <div class="col-sm-9">
            <input type="text" name="link" value="<?= $storage->link ?>" class="form-control" placeholder="Пусто = Парсинг за розкладом">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-success">Зберегти</button>
        </div>
    </div>
</form>