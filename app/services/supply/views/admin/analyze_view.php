<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="<?= SITE_URL . 'admin/' . $_SESSION['alias']->alias ?>" class="btn btn-warning btn-xs"><i class="fa fa-list"></i> Dashboard</a>

                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Товари</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="m-b-5" id="supply_products_filter">
                        <strong><?= "1 USD = {$_SESSION['currency']['USD']} грн"; ?></strong>
                        <label class="m-l-10">
                            <input type="checkbox" name="in_is_available" value="1" <?= $this->data->get('in_is_available') == 1 ? 'checked' : '' ?> onchange="supply_products_filter.submit()">
                            <span>Товари в наявності на складі</span>
                        </label>
                        <label class="m-l-10">
                            <input type="checkbox" checked disabled>
                            <span>Товари в наявності на <strong>складах постачальників</strong></span>
                        </label>
                        <label class="m-l-10">
                            <input type="checkbox" checked disabled>
                            <span>Враховуючи <strong>мінус слова</strong></span>
                        </label>
                        <i>Знайдено: <strong><?= $inner_products->total ?></strong> товарів</i>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <select name="in_brand_id" class="form-control" onchange="supply_products_filter.submit()">
                                    <option value="0">Всі бренди</option>
                                    <?php foreach ($inner_products->brands as $brand) {
                                        $selected = $this->data->get('in_brand_id') == $brand->id ? 'selected' : '';
                                        echo "<option value='{$brand->id}' {$selected}>{$brand->name} ({$brand->count})</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="in_out_price" class="form-control" onchange="supply_products_filter.submit()">
                                    <option value="per_page">Всі товари посторінково</option>
                                    <option value="all" <?= $this->data->get('in_out_price') == 'all' ? 'selected' : '' ?>>Всі товари на сторінці</option>
                                    <option value="down" <?= $this->data->get('in_out_price') == 'down' ? 'selected' : '' ?>>Ціну потрібно знизити</option>
                                    <option value="up" <?= $this->data->get('in_out_price') == 'up' ? 'selected' : '' ?>>Ціну потрібно підняти</option>
                                    <option value="ok" <?= $this->data->get('in_out_price') == 'ok' ? 'selected' : '' ?>>З ціною все ОК</option>
                                    <option value="one" <?= $this->data->get('in_out_price') == 'one' ? 'selected' : '' ?>>Екслюзивний товар</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="m-b-5">
                        <?php $page = $this->data->get('page');
                        if (empty($page)) $page = 1;
                        $start = ($page - 1) * $_SESSION['option']->paginator_per_page + 1;
                        $finish = $start + $_SESSION['option']->paginator_per_page - 1;
                        ?>
                        Відхилення від максимальної ціни: <strong><?= $_SESSION['option']->deviation_max_price ?>%</strong>. Пропускати ціну, якщо різниця менше <strong><?= $this->supply_model->skip_diff_price_percent ?>%</strong>. Сторінка <strong><?= $page ?></strong>. На сторінці <strong><?= "[{$start}..{$finish}]" ?></strong>
                    </div>
                    <?php if ($minus_words = $this->db->getAllData('supply_minus_words')) {
                        echo "<div class='alert alert-warning m-b-10'>Враховуються мінус слова: ";
                        $words = [];
                        foreach ($minus_words as $word) {
                            $words[] = $word->word;
                        }
                        echo "<strong>" . implode(', ', $words) . "</strong>";
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
                        $rows_total = count($import_products);
                        $diff_total = $rows_total - $total_after_minus_words;
                        echo "<br>Записи до аналізу: {$rows_total}.  Після виключення: <strong>{$total_after_minus_words}</strong> товарів. Виключено: <strong>{$diff_total}</strong> товарів";
                        echo "</div>";
                    }
                    $minus_brands = [];
                    if ($supply_minus_brands = $this->db->getAllData('supply_minus_brands', 'brand')) {
                        foreach ($supply_minus_brands as $brand) {
                            $minus_brands[] = $brand->brand;
                        }
                        echo "<div class='alert alert-warning m-b-10'>Враховуються мінус бренди: ";
                        echo "<strong>" . implode(', ', $minus_brands) . "</strong>";
                        echo "</div>";
                    } ?>
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Товар</th>
                                <td>
                                    <table class="table">
                                        <tr>
                                            <td>Виробник</td>
                                            <?php foreach ($inner_storages as $storage) {
                                                echo "<th>{$storage->name}</th>";
                                            }
                                            foreach ($import_storages as $storage) {
                                                $last_import_at = $storage->last_import_at ? date('d.m.Y H:i', $storage->last_import_at) : '-';
                                                echo "<th>{$storage->name} <br> <small>{$last_import_at}</small></th>";
                                            } ?>
                                        </tr>
                                    </table>
                                </td>
                                <th>Поточна => <br> Рекомендована ціна</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($inner_products->rows)) {
                                $shop_admin_url = SITE_URL . 'admin/parts/';
                                $row_i = ($page - 1) * $_SESSION['option']->paginator_per_page;
                                foreach ($inner_products->rows as $in_product) {
                                    if(in_array($this->data->get('in_out_price'), ["down", "up", "ok", "one"])) {
                                        $price_min = 0;
                                        foreach ($import_products as $log) {
                                            if ($log->article_key == $in_product->article) {
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
                                                if ($out_price_usd < $price_min || $price_min == 0) {
                                                    $price_min = $out_price_usd;
                                                }
                                            }
                                        }
                                        if($this->data->get('in_out_price') == 'one' && $price_min > 0) {
                                            continue;
                                        }
                                        if ($this->data->get('in_out_price') != 'one' && $price_min == 0) {
                                            continue;
                                        }
                                        if ($price_min > 0) {
                                            // "рекомендована ціна" = "найнижча ціна" - ("найнижча ціна" / 100 * 5)
                                            $price_min = round($price_min, 2);
                                            $recommendation_price = $price_min - ($price_min / 100 * $_SESSION['option']->deviation_max_price);
                                            $recommendation_price_rounded = round($recommendation_price, 2);
                                            $diff_percent = $in_product->price_in ? round(($recommendation_price_rounded - $in_product->price_in) / $in_product->price_in * 100, 2) : 100;
                                            $price_recommendation = abs($diff_percent) > $this->supply_model->skip_diff_price_percent ? $recommendation_price_rounded : $in_product->price_in;
                                            if($this->data->get('in_out_price') == 'down' && $price_recommendation >= $in_product->price_in) {
                                                continue;
                                            }
                                            if($this->data->get('in_out_price') == 'up' && $price_recommendation <= $in_product->price_in) {
                                                continue;
                                            }
                                            if($this->data->get('in_out_price') == 'ok' && $price_recommendation != $in_product->price_in) {
                                                continue;
                                            }
                                        }
                                    }

                                    $row_i++;
                                    echo "<tr><td>{$row_i}</td>";
                                    echo "<td><a href=\"{$shop_admin_url}{$in_product->uri}\"><strong>{$in_product->article_show}</strong> {$in_product->brand_name} (#{$in_product->id})</a> <br> <small>{$in_product->name}</small></td>";

                                    echo "<td>";
                                    echo "<table class='table'>";
                                    echo "<tr><td></td>";
                                    foreach ($inner_storages as $storage) {
                                        echo "<th>{$storage->name}</th>";
                                    }
                                    foreach ($import_storages as $storage) {
                                        $link = SITE_URL . "admin/{$_SESSION['alias']->alias}/";
                                        foreach ($import_log as $i_log) {
                                            if ($i_log->storage_id == $storage->id) {
                                                $link .= $i_log->id;
                                                break;
                                            }
                                        }
                                        echo "<th><a href='{$link}?product_article={$in_product->article}'>{$storage->name}</a></th>";
                                    }
                                    echo "</tr>";
                                    echo "<tr><th>{$in_product->brand_name}</th>";
                                    // todo: need update if several inner storages with different price
                                    $in_price_uah = round($in_product->price_in * $_SESSION['currency']['USD'], 2);
                                    foreach ($inner_storages as $storage) {
                                        if ($storage->id == $in_product->storage_id) {
                                            echo "<td title='{$in_product->name}'>\${$in_product->price_in} ({$in_price_uah} грн)</td>";
                                        } else {
                                            echo "<td></td>";
                                        }
                                    }

                                    $min_price = $min_storage_id = 0;
                                    $min_product_name = $min_brand_name = '';

                                    // search price with origin brand on import storages
                                    foreach ($import_storages as $storage) {
                                        $found = false;
                                        if(!empty($import_products)) {
                                            foreach ($import_products as $log) {
                                                if ($log->storage_id == $storage->id && $log->article_key == $in_product->article && mb_strtolower($log->product_brand) == mb_strtolower($in_product->brand_name)) {
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
                                                    if ($out_price_usd < $min_price || $min_price == 0) {
                                                        $min_price = $out_price_usd;
                                                        $min_storage_id = $log->storage_id;
                                                        $min_product_name = $log->product_title;
                                                        $min_brand_name = $log->product_brand;
                                                    }
                                                    $price_color = $in_product->price_in < $out_price_usd ? 'text-success' : 'text-danger';
                                                    echo "<td class='{$price_color}' title='{$log->product_title}'>\${$out_price_usd} ({$log->price} грн)</td>";
                                                    $rows_showed[] = $log->id;
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if (!$found) {
                                            echo "<td></td>";
                                        }
                                    }
                                    echo "</tr>";

                                    $brand_showed = [mb_strtolower($in_product->brand_name)];
                                    // search price with other brand on import storages
                                    if (!empty($import_products)) {
                                        foreach ($import_products as $log) {
                                            if ($log->article_key == $in_product->article && mb_strtolower($log->product_brand) != mb_strtolower($in_product->brand_name)) {
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
                                                if (in_array(mb_strtolower($log->product_brand), $brand_showed)) {
                                                    continue;
                                                }
                                                $brand_showed[] = mb_strtolower($log->product_brand);

                                                echo "<tr><th>{$log->product_brand}</th>";
                                                foreach ($inner_storages as $storage) {
                                                    echo "<td></td>";
                                                }
                                                foreach ($import_storages as $storage) {
                                                    $found = false;
                                                    foreach ($import_products as $log2) {
                                                        if ($log2->storage_id == $storage->id && $log2->article_key == $in_product->article && mb_strtolower($log2->product_brand) == mb_strtolower($log->product_brand)) {
                                                            if ($minus_words) {
                                                                $is_minus = false;
                                                                foreach ($minus_words as $word) {
                                                                    if (mb_stripos($log2->product_title, $word->word) !== false) {
                                                                        $is_minus = true;
                                                                        break;
                                                                    }
                                                                }
                                                                if ($is_minus) {
                                                                    continue;
                                                                }
                                                            }

                                                            $out_price_usd = round($log2->price / $_SESSION['currency']['USD'], 2);
                                                            if ($out_price_usd < $min_price || $min_price == 0) {
                                                                $min_price = $out_price_usd;
                                                                $min_storage_id = $log2->storage_id;
                                                                $min_product_name = $log2->product_title;
                                                                $min_brand_name = $log2->product_brand;
                                                            }
                                                            $price_color = $in_product->price_in < $out_price_usd ? 'text-success' : 'text-danger';
                                                            echo "<td class='{$price_color}' title='{$log2->product_title}'>\${$out_price_usd} ({$log2->price} грн)</td>";
                                                            $found = true;
                                                            break;
                                                        }
                                                    }
                                                    if (!$found) {
                                                        echo "<td></td>";
                                                    }
                                                }
                                                echo '</tr>';
                                            }
                                        }
                                    }
                                    echo "</table>";
                                    echo "</td>";

                                    if ($min_price) {
                                        // "рекомендована ціна" = "найнижча ціна" - ("найнижча ціна" / 100 * 5)
                                        $min_price = round($min_price, 2);
                                        $recommendation_price = $min_price - ($min_price / 100 * $_SESSION['option']->deviation_max_price);
                                        $recommendation_price_rounded = round($recommendation_price, 2);
                                        $diff_percent = $in_product->price_in ? round(($recommendation_price_rounded - $in_product->price_in) / $in_product->price_in * 100, 2) : 100;
                                        if (abs($diff_percent) > $this->supply_model->skip_diff_price_percent) {
                                            // якщо рекомендована ціна більше за нашу помічаємо її зеленим, якщо нижча за нашу то помічаємо червноним
                                            $class_color = $in_product->price_in < $recommendation_price_rounded ? 'text-success' : 'text-danger';
                                            $icon = $in_product->price_in < $recommendation_price_rounded ? 'fa-arrow-up' : 'fa-arrow-down';
                                            if ($in_product->price_in == $recommendation_price_rounded) {
                                                $class_color = 'text-primary';
                                                $icon = 'fa-snowflake-o';
                                            }
                                        } else {
                                            $class_color = 'text-primary';
                                            $icon = 'fa-snowflake-o';
                                        }

                                        $storage_name = '';
                                        foreach ($import_storages as $storage) {
                                            if ($storage->id == $min_storage_id) {
                                                $storage_name = $storage->name;
                                                break;
                                            }
                                        }
                                        echo "<td>{$storage_name} <br> {$min_product_name} <br> {$min_brand_name} <br> <strong>\${$min_price}</strong> <hr class='m-5'> <strong class='{$class_color}'><i class=\"fa {$icon}\" aria-hidden=\"true\"></i>\${$in_product->price_in} => \${$recommendation_price_rounded} ({$diff_percent}%)</strong></td>";
                                    } else {
                                        echo "<td>Виключно наша наявность <hr class='m-5'> <strong class='text-warning'><i class=\"fa fa-free-code-camp\" aria-hidden=\"true\"></i> \${$in_product->price_in}</strong></td>";
                                    }

                                    echo '</tr>';
                                }
                            } ?>
                        </tbody>
                    </table>
                    <?php if (empty($this->data->get('in_out_price')) || $this->data->get('in_out_price') == 'per_page') {
                    $this->load->library('paginator');
                    echo $this->paginator->get();
                    } ?>
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