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
        $this->redirect('/');
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
                    $this->db->updateRow('supply_storages', ['import_flag' => 0], $storage->id);
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

    // init import by cron: set import_flag to 1 for all active storages
    public function import_init() {
        $this->db->updateRow('supply_storages', ['import_flag' => 1], ['active' => 1]);
        $total = $this->db->getCount('supply_storages', ['import_flag' => 1, 'active' => 1]);
        echo "Total inited: {$total} storages are ready for import.";
        exit;
    }

    // start importing per each storage by cron
    public function import_start() {
        echo '<pre>';
        if($storage = $this->db->select('supply_storages', '*', ['active' => 1, 'import_flag' => 1])->limit(1)->get()) {
            if(empty($storage->provider) || empty($storage->link)) {
                $this->db->updateRow('supply_storages', ['import_flag' => 0], $storage->id);
                exit('empty provider or link');
            }

            // for dev test
            // $file_path = 'import/supply/asiaparts.xml';
            // $file_path = 'import/supply/xpert-auto.xml';
            // $this->import_process($storage, $file_path);
            // exit;
            
            $date_dmy = date('y-m-d');
            $date_dmyhis = date('dmy-his');
            $folder_path = "import/supply/{$date_dmy}";
            if(!is_dir($folder_path)) mkdir($folder_path, 0777, true);
            $file_path = "{$folder_path}/{$storage->provider}_st{$storage->id}_{$date_dmyhis}.xml";
            if(file_put_contents($file_path, file_get_contents($storage->link))) {
                $this->import_process($storage, $file_path);
            }
        }
        exit;
    }

    private function import_process($storage, $file_path) {
        $time = time();
        $this->db->updateRow('supply_storages', ['import_flag' => 0, 'last_import_at' => $time], $storage->id);

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
            $this->db->executeQuery("SELECT `id`, `article` FROM `s_shopshowcase_products`");
            while($obj = $this->db->result->fetch_object()) {
                $_allProducts[$obj->article] = $obj->id;
            }

            $import_id = $this->db->insertRow('supply_import_log', ['storage_id' => $storage->id, 'created_at' => $time, 'link' => $storage->link, 'local_file' => $file_path]);

            $i = $found = $inserted = 0; $rows = [];
            foreach ($provider->get_products() as $product) {
                $found++;
                $data = $provider->prepare_product($product);
                $article = $this->supply_model->prepareArticleKey($data['product_article']);
                if(isset($_allProducts[$article])) {
                    $inserted++;
                    $data['article_key'] = $article;
                    $rows[] = $data;
                }
                // print_r($data);
                if(++$i == 200) {
                    $this->db->insertRows('supply_products', ['import_id' => $import_id, 'article_key', 'product_article', 'product_brand', 'price', 'availability', 'product_title'], $rows, 50, ['article_key' => 'text']);
                    $i = 0; $rows = [];
                }
            }
            echo "Found: {$found}. Inserted: {$inserted}";
        }
    }

    public function export_recomendation_prices() {
        $this->load->smodel('supply_model');
        $storages = $this->supply_model->get_storages(['active' => 1]);
        if (empty($storages)) {
            echo 'Немає активних постачальників';
            return;
        }

        $last_imports_for_storages = $this->supply_model->get_last_imports_for_storages();
        if (empty($last_imports_for_storages)) {
            echo 'Empty import data';
            return;
        }

        $where_inner = ['#s.amount' => '>0'];
        $where = ['import_id' => [], 'availability' => '>0'];
        $where['&'] = 'i.product_brand NOT IN (SELECT `brand` FROM `supply_minus_brands`)';
        foreach ($last_imports_for_storages as $import) {
            $where['import_id'][] = $import->id;
        }

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