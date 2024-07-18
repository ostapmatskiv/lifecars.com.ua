<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">Відхилення від максимальної ціни, %</h4>
            </div>
            <div class="panel-body">
                <form action="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/deviation_max_price" ?>" method="post">
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="number" class="form-control" name="deviation_max_price" value="<?= $_SESSION['option']->deviation_max_price ?>">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success">Зберегти</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/recommendation_price" ?>" class="btn btn-success btn-xs"><i class="fa fa-bar-chart"></i> Рекомендовані ціни</a>
                    <a href="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/analyze?in_is_available=1" ?>" class="btn btn-warning btn-xs"><i class="fa fa-bar-chart"></i> Аналізувати ціни</a>
                </div>
                <h4 class="panel-title">Історія імпорту</h4>
            </div>
            <div class="panel-body">
                <table id="supply_import_log" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Склад</th>
                            <th>Дата імпорту (#ID)</th>
                            <th>Посилання</th>
                        </tr>
                    <tbody>
                        <?php if ($supply_import_log) {
                            foreach ($supply_import_log as $log) {
                                foreach (['created_at'] as $key) {
                                    $log->$key = $log->$key ? date('d.m.Y H:i', $log->$key) : '-';
                                }
                                if (empty($log->link)) {
                                    $log->link = $log->local_file;
                                }
                                echo "<tr>";
                                echo "<td><a href='/admin/{$_SESSION['alias']->alias}/{$log->id}'>#{$log->storage_id}. {$log->storage_name}</a></td>";
                                echo "<td><a href='/{$log->local_file}' target='_blank'>{$log->created_at} (#{$log->id})</a></td>";
                                echo "<td>{$log->link}</td>";
                                echo '</tr>';
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="#add-storage-modal" class="btn btn-warning btn-xs" data-toggle="modal"><i class="fa fa-plus"></i> Додати склад</a>
                </div>
                <h4 class="panel-title">Склади</h4>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#ID. Назва</th>
                            <th>Посилання на прайс</th>
                            <th>Останній імпорт</th>
                            <th>Додано</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($supply_storages) {
                            $import_link = SITE_URL . "{$_SESSION['alias']->alias}/import";
                            foreach ($supply_storages as $storage) {
                                foreach (['last_import_at', 'created_at'] as $key) {
                                    $storage->$key = $storage->$key ? date('d.m.Y H:i', $storage->$key) : '-';
                                }
                                $tr_class = $storage->active ? '' : 'class="danger"';
                                echo "<tr {$tr_class}>";
                                echo "<td><a href=\"#edit-storage-modal\" class=\"btn btn-info btn-xs\" data-toggle=\"modal\" data-storage_id=\"{$storage->id}\">#{$storage->id}. {$storage->name}</a></td>";
                                if (!empty($storage->link)) {
                                    echo "<td><a href='{$storage->link}' target='_blank'>{$storage->link}</a> <a href='{$import_link}?storage_id={$storage->id}' class='btn btn-xs btn-warning'>Імпортувати</a></td>";
                                } else {
                                    echo '<td>'; ?>
                                    <form action="<?= "{$import_link}?storage_id={$storage->id}" ?>" method="POST" enctype="multipart/form-data" id="import_form_<?= $storage->id ?>">
                                        <input type="file" name="file" accept=".xlsx,.xls" onchange="import_form_<?= $storage->id ?>.submit()">
                                    </form>
                        <?php
                                    echo '</td>';
                                }
                                echo "<td>{$storage->last_import_at}</td>";
                                echo "<td>{$storage->created_at}</td>";
                                echo '</tr>';
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">Мінус слова</h4>
            </div>
            <div class="panel-body">
                <form action="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/minus_words_add" ?>" method="post" class="row m-b-15">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="word" value="" required>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Додати</button>
                    </div>
                </form>

                <table class="table table-striped table-bordered">
                    <tbody>
                        <?php if ($minus_words = $this->db->getAllData('supply_minus_words', 'word')) {
                            $del_link = SITE_URL . "admin/{$_SESSION['alias']->alias}/minus_words_delete";
                            foreach ($minus_words as $word) {
                                echo "<tr>";
                                echo "<td><a href='{$del_link}?word_id={$word->id}' class='pull-right btn btn-xs btn-danger'>x</a> <strong>{$word->word}</strong></td>";
                                echo '</tr>';
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4 class="panel-title">Мінус товари</h4>
            </div>
            <div class="panel-body">
                <div class="row m-b-15">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="product_article_search" value="" placeholder="Артикул товару">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-success" onclick="search_minus_product()"><i class="fa fa-search" aria-hidden="true"></i> Шукати</button>
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="search_minus_products-table">
                </table>

                <table class="table table-striped table-bordered">
                    <tbody>
                        <?php if ($minus_products) {
                            $del_link = SITE_URL . "admin/{$_SESSION['alias']->alias}/minus_product_delete";
                            foreach ($minus_products as $product) {
                                echo "<tr>";
                                echo "<th>{$product->article_show}</th><td>{$product->brand_name}</td><td><a href='{$del_link}?product_id={$product->id}' class='pull-right btn btn-xs btn-danger'>x</a> {$product->name}</td>";
                                echo '</tr>';
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">Бренди</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <?php $minus_brands = [];
                        if ($supply_minus_brands = $this->db->getAllData('supply_minus_brands')) {
                            foreach ($supply_minus_brands as $brand) {
                                $minus_brands[] = $brand->brand;
                            }
                        }
                        if ($product_brand = $this->db->select('supply_products', 'DISTINCT `product_brand`')->order('product_brand')->get('array')) {
                            foreach ($product_brand as $i => $row) {
                                if ($i % 3 == 0) {
                                    echo $i == 0 ? "<tr>" : "</tr><tr>";
                                }
                                $checked = in_array($row->product_brand, $minus_brands) ? '' : 'checked';
                                echo "<td><label><input type='checkbox' name='brend' value='{$row->product_brand}' {$checked} class='minus_brands-checkbox'> {$row->product_brand}</label></td>";
                            }
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-storage-modal" tabindex="-1" role="dialog" aria-labelledby="add-storage-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="add-storage-modal-label">Додати склад</h4>
            </div>
            <div class="modal-body">
                <form action="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/storage_save" ?>" method="POST" class="form-horizontal">
                    <input type="hidden" name="storage_id" value="0">
                    <input type="hidden" name="active" value="1">

                    <div class="form-group">
                        <label for="storage-name" class="col-sm-3 control-label">Провайдер (парсер)</label>
                        <div class="col-sm-9">
                            <select name="provider" class="form-control">
                                <?php $providers = scandir(APP_PATH . 'services/supply/@providers');
                                foreach ($providers as $provider) {
                                    if ($provider !== '.' && $provider !== '..') {
                                        $option = pathinfo($provider, PATHINFO_FILENAME);
                                        echo "<option value=\"{$option}\">{$option}</option>";
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="storage-name" class="col-sm-3 control-label">Назва складу</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="storage-link" class="col-sm-3 control-label">Посилання на прайс</label>
                        <div class="col-sm-9">
                            <input type="text" name="link" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success">Зберегти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-storage-modal" tabindex="-1" role="dialog" aria-labelledby="edit-storage-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-storage-modal-label">Редагувати склад</h4>
            </div>
            <div class="modal-body">
                loading...
            </div>
        </div>
    </div>
</div>

<script>
    function init__supply_dashboard() {
        $('#edit-storage-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var storage_id = button.data('storage_id');
            var modal = $(this);
            modal.find('.modal-body').load('/admin/<?= $_SESSION['alias']->alias ?>/storage_edit_modal/' + storage_id);
        });

        $('.minus_brands-checkbox').change(function() {
            var brand = $(this).val(),
                checked = $(this).prop('checked');
            // alert(brand + ' ' + checked);
            $.post('/admin/<?= $_SESSION['alias']->alias ?>/minus_brands', {
                brand: brand,
                checked: checked
            });
        });
    }

    function search_minus_product() {
        let article = $('#product_article_search').val(),
            table = $('#search_minus_products-table');
        if (article) {
            table.html('<tr><td>loading...</td></tr>');
            $.post('/admin/<?= $_SESSION['alias']->alias ?>/minus_product_search', {
                article: article
            }).done(function(data) {
                table.html(data);
            });
        }
    }
</script>

<?php $_SESSION['alias']->js_init[] = 'init__supply_dashboard()'; ?>