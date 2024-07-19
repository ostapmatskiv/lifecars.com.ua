<?php

/*

    Аналіз постачальників
	for adatrade.com.ua

*/

/**
 * @property \supply_model    $supply_model
 * @property \db              $db
 * @property \Data            $data
 * @property \Loader          $load
 * @property \Paginator       $paginator
 */

class supply extends Controller {
				
    function _remap($method, $data = array())
    {
        if(isset($_SESSION['alias']->name))
            $_SESSION['alias']->breadcrumb = array($_SESSION['alias']->name => '');
        if (method_exists($this, $method))
            return $this->$method($data);
        else
            $this->index($method);
    }

    public function index()
    {
        // $this->redirect('/');
    }

    // import manual by link
    public function import()
    {
        if($storage_id = (int) $this->data->get('storage_id')) {
            if($storage = $this->db->select('supply_storages', '*', ['id' => $storage_id, 'active' => 1])->get()) {
                $date_dmy = date('y-m-d');
                $date_dmyhis = date('dmy-his');
                $folder_path = "import/supply/{$date_dmy}";
                if (!is_dir($folder_path)) mkdir($folder_path, 0777, true);

                if(empty($storage->provider)) {
                    $this->db->updateRow('supply_storages', ['active' => 0], $storage->id);
                    exit('empty provider');
                }
                if(empty($storage->link)) {
                    if(isset($_FILES['file'])) {
                        $file = $_FILES['file'];
                        if($file['error'] === UPLOAD_ERR_OK) {
                            $path_info = pathinfo($_FILES['file']['name']);
                            $extension = $path_info['extension'];

                            $allowed_ext = ['xml', 'xlsx', 'xls'];
                            if(in_array($extension, $allowed_ext)) {
                                $file_path = "{$folder_path}/{$storage->provider}_st{$storage->id}_{$date_dmyhis}.{$extension}";
                                if(move_uploaded_file($file['tmp_name'], $file_path)) {
                                    if($this->userCan()) {
                                        ob_start();
                                    }
                                    $this->import_process($storage, $file_path);
                                    if($this->userCan()) {
                                        $output = ob_get_clean();
                                        $_SESSION['notify'] = new stdClass();
                                        $_SESSION['notify']->success = $output;
                                        $this->redirect('/admin/supply');
                                    }
                                    exit;
                                } else {
                                    exit('Failed to save file');
                                }
                            } else {
                                exit('Error uploading file. Allowed extensions: '. implode(', ', $allowed_ext));
                            }
                        } else {
                            pr($file);

                            $phpFileUploadErrors = array(
                                0 => 'There is no error, the file uploaded with success',
                                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                                3 => 'The uploaded file was only partially uploaded',
                                4 => 'No file was uploaded',
                                6 => 'Missing a temporary folder',
                                7 => 'Failed to write file to disk.',
                                8 => 'A PHP extension stopped the file upload.',
                            );
                            
                            exit("Error uploading file: <strong>{$phpFileUploadErrors[$file['error']]}</strong>");
                        }
                    } else {
                        exit('No file uploaded');
                    }
                }
                else {
                    $file_path = "{$folder_path}/{$storage->provider}_st{$storage->id}_{$date_dmyhis}.xml";
                    if (file_put_contents($file_path, file_get_contents($storage->link))) {
                        // $this->import_process($storage, $file_path);
                        if($this->userCan()) {
                            ob_start();
                        }
                        $this->import_process($storage, $file_path);
                        if($this->userCan()) {
                            $output = ob_get_clean();
                            $_SESSION['notify'] = new stdClass();
                            $_SESSION['notify']->success = $output;
                            $this->redirect('/admin/supply');
                        }
                        exit;
                    }
                }                
            }
        }
    }

    // start importing per each storage by cron
    public function import_cron() {
        echo '<pre>';
        $time = time();
        if($storages = $this->db->select('supply_storages', '*', ['active' => 1, 'import_cron_flag' => 1])->order('last_import_at ASC')->get('array')) {
            foreach ($storages as $storage) {
                if(empty($storage->provider)) {
                    $this->db->updateRow('supply_storages', ['active' => 0, 'last_import_at' => $time], $storage->id);
                    continue;
                }
                $provider_path = "app/services/supply/@providers/{$storage->provider}.php";
                if(!file_exists($provider_path)) {
                    $this->db->updateRow('supply_storages', ['active' => 0, 'last_import_at' => $time], $storage->id);
                    continue;
                }

                $this->load->smodel('supply_model');
                $product = $this->supply_model->get_next_inner_product(['id' => '>'.$storage->last_import_product_id]);
                if(empty($product)) {
                    // start from the beginning
                    $product = $this->supply_model->get_next_inner_product();
                }
                if(empty($product)) {
                    // remove active = 0 to parse circle again
                    $this->db->updateRow('supply_storages', ['active' => 0, 'last_import_product_id' => 0, 'last_import_at' => $time], $storage->id);
                    continue;
                }
                $this->db->updateRow('supply_storages', ['last_import_product_id' => $product->id, 'last_import_at' => $time], $storage->id);

                require $provider_path;
                $provider_name = str_replace('-', '_', "{$storage->provider}_provider");
                $provider = new $provider_name;
                $out_products = $provider->parse($product);

                pr($product);
                pr($out_products);

                if(!empty($out_products)) {
                    $search = ['storage_id' => $storage->id, 'article_key' => $product->article];
                    $in_products = $this->db->select('supply_products', '*', $search)->get();
                    if(empty($in_products)) {
                        $this->db->insertRows('supply_products', ['created_at' => $time, 'storage_id' => $storage->id, 'article_key' => $product->article, 'product_article', 'product_brand', 'price', 'availability', 'product_title'], $out_products, 50, ['article_key' => 'text', 'product_article' => 'text']);
                    }
                    else {
                        foreach ($out_products as $out) {
                            $brand_found = false;
                            foreach ($in_products as $in) {
                                if($out->product_brand == $in->product_brand && $out->product_article == $in->product_article) {
                                    $brand_found = true;
                                    if($out->price != $in->price || $out->availability != $in->availability) {
                                        $this->db->updateRow('supply_products', ['created_at' => $time, 'price' => $out->price, 'availability' => $out->availability, 'product_title' => $out->product_title], $in->id);
                                    }
                                    break;
                                }
                            }
                            if(!$brand_found) {
                                $this->db->insertRow('supply_products', ['created_at' => $time, 'storage_id' => $storage->id, 'article_key' => $product->article, 'product_article' => $out->product_article, 'product_brand' => $out->product_brand, 'price' => $out->price, 'availability' => $out->availability, 'product_title' => $out->product_title]);
                            }
                        }
                    }
                }
            }
        }
        exit;
    }

    private function import_process($storage, $file_path) {
        $time = time();
        $this->db->updateRow('supply_storages', ['last_import_at' => $time], $storage->id);

        $provider_path = "app/services/supply/@providers/{$storage->provider}.php";
        if(file_exists($provider_path)) {
            $this->load->smodel('supply_model');
            $finfo = explode('.', $file_path);
            if(end($finfo) == 'xml') {
                $file = simplexml_load_file($file_path);
            }
            else {
                require(SYS_PATH . 'libraries' . DIRSEP . 'spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
                require(SYS_PATH . 'libraries' . DIRSEP . 'spreadsheet-reader-master/SpreadsheetReader.php');
                $file = new SpreadsheetReader($file_path);
            }
            
            require $provider_path;
            $provider_name = str_replace('-', '_', "{$storage->provider}_provider");
            $provider = new $provider_name;
            $provider->init($file);

            $_allProducts = []; // article => id
            $this->supply_model->db_adatrade->executeQuery("SELECT `id`, `article` FROM `s_shopshowcase_products`");
            while($obj = $this->supply_model->db_adatrade->result->fetch_object()) {
                $_allProducts[$obj->article] = $obj->id;
            }

            $in_products = $this->db->select('supply_products', '*', ['storage_id' => $storage->id])->get();

            $found = $inserted = $updated = $skiped = 0; $rows_insert = [];
            foreach ($provider->get_products() as $product) {
                $found++;
                $data = $provider->prepare_product($product);
                $article = $this->supply_model->prepareArticleKey($data['product_article']);
                if(isset($_allProducts[$article])) {
                    if(empty($in_products)) {
                        $inserted++;
                        $data['article_key'] = $article;
                        $rows_insert[] = $data;
                    }
                    else {
                        $brand_found = false;
                        foreach ($in_products as $in) {
                            if($data['product_brand'] == $in->product_brand && $article == $in->article_key) {
                                $brand_found = true;
                                if(round($data['price']) != round($in->price) || $data['availability'] != $in->availability) {
                                    $updated++;
                                    $this->db->updateRow('supply_products', ['created_at' => $time, 'price' => $data['price'], 'availability' => $data['availability'], 'product_title' => $data['product_title']], $in->id);
                                }
                                else {
                                    $skiped++;
                                }
                                break;
                            }
                        }
                        if(!$brand_found) {
                            $inserted++;
                            $data['article_key'] = $article;
                            $rows_insert[] = $data;
                        }
                    }
                }
                // print_r($data);
            }
            if(!empty($rows_insert)) {
                $this->db->insertRows('supply_products', ['created_at' => $time, 'storage_id' => $storage->id, 'article_key', 'product_article', 'product_brand', 'price', 'availability', 'product_title'], $rows_insert, 50, ['article_key' => 'text', 'product_article' => 'text']);
            }
            echo "Found: {$found}. Inserted: {$inserted}, Updated: {$updated}, Skiped: {$skiped}";
        }
    }

    public function export_recomendation_prices() {
        $this->load->smodel('supply_model');
        $storages = $this->supply_model->get_storages(['active' => 1]);
        if (empty($storages)) {
            echo 'Немає активних постачальників';
            return;
        }

        $where_inner = ['#s.amount' => '>0'];
        $where = ['availability' => '>0'];
        $where['&'] = 'i.product_brand NOT IN (SELECT `brand` FROM `supply_minus_brands`)';

        $inner_products = $this->supply_model->get_inner_products($where_inner, -1);
        $import_products = $this->supply_model->get_import_products($where);

        $inner_products = $this->supply_model->get_inner_products($where_inner, -1);
        $import_products = $this->supply_model->get_import_products($where);
        if($recommendation_price = $this->supply_model->recommendation_price($inner_products, $import_products)) {
            foreach ($recommendation_price as $row) {
                $row->price_min = (string) $row->price_min;
                $row->price_recommendation = (string) $row->price_recommendation;
            }
        }
        $this->load->json($recommendation_price);
    }

    public function __get_Search($content)
    {
        return false;
    }
	
}