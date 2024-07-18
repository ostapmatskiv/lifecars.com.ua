<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="<?= SITE_URL . "admin/{$_SESSION['alias']->alias}/analyze?in_is_available=1" ?>" class="btn btn-warning btn-xs"><i class="fa fa-bar-chart"></i> Аналізувати ціни</a>
                    <a href="<?= SITE_URL . 'admin/' . $_SESSION['alias']->alias ?>" class="btn btn-info btn-xs"><i class="fa fa-list"></i> Dashboard</a>

                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Товари</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="m-b-10" id="supply_products_filter">
                        <strong><?= "1 USD = {$_SESSION['currency']['USD']} грн"; ?></strong>
                        Для товарів в наявності на складі і постачальника, враховуючи <strong>мінус слова</strong> <br>
                        Відхилення від максимальної ціни: <strong><?= $_SESSION['option']->deviation_max_price ?>%</strong>
                    </form>
                    <?php if($minus_words = $this->db->getAllData('supply_minus_words')) {
                        echo "<div class='alert alert-warning m-t-10'>Враховуються мінус слова: ";
                        $words = [];
                        foreach ($minus_words as $word) {
                            $words[] = $word->word;
                        }
                        echo "<strong>". implode(', ', $words) ."</strong>";
                        echo "</div>";
                    
                    }
                    $minus_brands = [];
                    if($supply_minus_brands = $this->db->getAllData('supply_minus_brands', 'brand')) {
                        foreach ($supply_minus_brands as $brand) {
                            $minus_brands[] = $brand->brand;
                        }
                        echo "<div class='alert alert-warning m-t-10'>Враховуються мінус бренди: ";
                        echo "<strong>". implode(', ', $minus_brands) ."</strong>";
                        echo "</div>";
                    } ?>
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Поточна ціна</th>
                                <th>Найменша ціна від постачальника</th>
                                <th>Рекомендована ціна</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recommendation_price)) {
                                $analyze_url = SITE_URL . "admin/{$_SESSION['alias']->alias}/analyze?product_article=";
                                foreach ($recommendation_price as $row) {
                                    $class_color = $row->price_in < $row->price_recommendation ? 'text-success' : 'text-danger';
                                    $icon = $row->price_in < $row->price_recommendation ? 'up' : 'down';

                                    echo "<tr>";
                                    echo "<td><a href=\"{$analyze_url}{$row->product_article_key}\"><strong>{$row->product_article}</strong></a> <br> <small>{$row->product_name}, {$row->brand_name} (#{$row->product_id})</small></td>";
                                    echo "<td>\${$row->price_in}</td>";
                                    echo "<td>\${$row->price_min}</td>";
                                    echo "<td class='{$class_color}'><i class=\"fa fa-arrow-{$icon}\" aria-hidden=\"true\"></i> \${$row->price_recommendation}</td>";
                                    echo '</tr>';
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