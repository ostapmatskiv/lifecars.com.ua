<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="<?= SITE_URL . 'admin/' . $_SESSION['alias']->alias ?>" class="btn btn-warning btn-xs"><i class="fa fa-list"></i> Dashboard</a>
                    <a href="<?= SITE_URL . $import_log->local_file ?>" class="btn btn-primary btn-xs"><i class="fa fa-file"></i> Local input file</a>

                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Товари</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="m-b-10" id="supply_products_filter">
                        <strong><?= "1 USD = {$_SESSION['currency']['USD']} грн"; ?></strong>
                        <label class="m-l-10">
                            <input type="checkbox" name="out_is_available" value="1" <?= $this->data->get('out_is_available') == 1 ? 'checked' : '' ?> onchange="supply_products_filter.submit()">
                            <span>Товари в наявності на <strong><?= $storage->name ?></strong></span>
                        </label>
                        <label class="m-l-10">
                            <input type="checkbox" name="minus_words" value="1" <?= $this->data->get('minus_words') == 1 ? 'checked' : '' ?> onchange="supply_products_filter.submit()">
                            <span>Враховувати мінус слова</span>
                        </label>
                        <label class="m-l-10">
                            <input type="checkbox" name="minus_brands" value="1" <?= $this->data->get('minus_brands') == 1 ? 'checked' : '' ?> onchange="supply_products_filter.submit()">
                            <span>Враховувати мінус бренди</span>
                        </label>
                        <i>Знайдено: <strong><?= !empty($import_products) ? count($import_products) : 0 ?></strong> товарів</i>
                    </form>
                    <?php $minus_words = $this->data->get('minus_words') == 1 ? $this->db->getAllData('supply_minus_words') : false;
                    if($minus_words) {
                        echo "<div class='alert alert-warning m-t-10'>Враховуються мінус слова: ";
                        $words = [];
                        foreach ($minus_words as $word) {
                            $words[] = $word->word;
                        }
                        echo "<strong>". implode(', ', $words) ."</strong>";
                        
                        $total_after_minus_words = 0;
                        foreach ($import_products as $log) {
                            $is_minus = false;
                            foreach ($minus_words as $word) {
                                if (mb_stripos($log->product_title, $word->word) !== false) {
                                    $is_minus = true;
                                    break;
                                }
                            }
                            if ($is_minus) {
                                continue;
                            }
                            $total_after_minus_words++;
                        }
                        $diff_total = count($import_products) - $total_after_minus_words;
                        echo "<br>Знайдено: <strong>{$total_after_minus_words}</strong> товарів. Виключено: <strong>{$diff_total}</strong> товарів";
                        echo "</div>";
                    }
                    if($this->data->get('minus_brands') == 1) {
                        $minus_brands = [];
                        if($supply_minus_brands = $this->db->getAllData('supply_minus_brands', 'brand')) {
                            foreach ($supply_minus_brands as $brand) {
                                $minus_brands[] = $brand->brand;
                            }
                            echo "<div class='alert alert-warning m-t-10'>Враховуються мінус бренди: ";
                            echo "<strong>". implode(', ', $minus_brands) ."</strong>";
                            echo "</div>";
                        } 
                    } ?>
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th colspan="2">Товар</th>
                                <th><?= $import_log->storage_name ?></th>
                                <th>Наявність</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($import_products)) {
                                $active_product_id = 0;
                                $shop_admin_url = SITE_URL . 'admin/parts/';
                                foreach ($import_products as $log) {
                                    if ($minus_words) {
                                        $is_minus = false;
                                        foreach ($minus_words as $word) {
                                            if (mb_stripos($log->product_title, $word->word) !== false) {
                                                $is_minus = true;
                                                break;
                                            }
                                        }
                                        if ($is_minus) {
                                            continue;
                                        }
                                    }
                                    $out_price_usd = round($log->price / $_SESSION['currency']['USD'], 2);
                                    echo "<tr><th>{$log->product_article}</th><td><strong>{$log->product_brand}</strong> <small>{$log->product_title}</small></td><td><strong>\${$out_price_usd}</strong> ({$log->price} грн)</td><td>{$log->availability}</td></tr>";
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-productInfo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <img src="<?= SITE_URL ?>style/admin/images/icon-loading.gif" width=40> Завантаження...
            </div>
        </div>
    </div>
</div>