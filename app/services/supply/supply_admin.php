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

class supply_admin extends Controller {
				
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
        $this->load->smodel('supply_model');

        $_SESSION['alias']->name = $_SESSION['alias']->title = 'Постачальники. Аналіз товарів';
        $this->load->admin_view('index_view', [
            'supply_storages' => $this->supply_model->get_storages(),
            'last_import_products' => $this->supply_model->get_import_products([], 50),
            'minus_products' => $this->supply_model->get_minus_products()
        ]);
    }

    public function recommendation_price() {
        $_SESSION['alias']->name = $_SESSION['alias']->title = 'Постачальники. Рекомендовані ціни';

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
        $this->load->admin_view('recommendation_price_view', [
            'recommendation_price' => $this->supply_model->recommendation_price($inner_products, $import_products)
        ]);
    }

    public function storage_edit_modal() {
        if($storage_id = $this->data->uri(3)) {
            if(is_numeric($storage_id)) {
                if($storage = $this->db->getAllDataById('supply_storages', $storage_id)) {
                    $this->load->view('admin/storage_edit_modal', ['storage' => $storage]);
                }
            }
        }
        exit;
    }

    public function storage_save() {
        $storage_id = $this->data->post('storage_id');
        $data = $this->data->prepare(['provider', 'name', 'link', 'active']);
        $data['import_cron_flag'] = empty($data['link']) ? 1 : 0;
        if($storage_id == 0) {
            $data['created_at'] = time();
            $data['last_import_at'] = $data['last_import_product_id'] = 0;
            $this->db->insertRow('supply_storages', $data);
        } else {
            $this->db->updateRow('supply_storages', $data, $storage_id);
        }
        $this->redirect();
    }

    public function analyze() {
        $_SESSION['alias']->name = $_SESSION['alias']->title = 'Постачальники. Аналіз товарів';
        $_SESSION['option']->paginator_per_page = 50;
        $page = $this->data->get('page');
        if (empty($page)) $page = 1;

        $this->load->smodel('supply_model');
        $storages = $this->supply_model->get_storages(['active' => 1]);
        if(empty($storages)) {
            echo 'Немає активних постачальників';
            return;
        }
        $import_storages = [];
        foreach ($storages as $storage) {
            $import_storages[$storage->id] = $storage;
        }

        $where_inner = [];
        $where = ['availability' => '>0'];
        $where['&'] = 'i.product_brand NOT IN (SELECT `brand` FROM `supply_minus_brands`)';
        if($this->data->get('in_is_available')) $where_inner['#s.amount'] = '>0';

        if ($product_article = $this->data->get('product_article')) {
            $where['article_key'] = $this->supply_model->prepareArticleKey($product_article);
            $where_inner['article'] = $this->supply_model->prepareArticleKey($product_article);
        }
        if($in_brand_id = $this->data->get('in_brand_id')) {
            $where_inner['#po.value'] = $in_brand_id;
        }
        if(!empty($this->data->get('in_out_price')) && $this->data->get('in_out_price') != 'per_page') {
            $page = -1;
        }
        
        $inner_products = $this->supply_model->get_inner_products($where_inner, $page, $_SESSION['option']->paginator_per_page);
        $_SESSION['option']->paginator_total = $inner_products->total;

        if(empty($where['article_key']) && $inner_products->total > 0 && $page > 0) {
            $where['article_key'] = [];
            foreach ($inner_products->rows as $product) {
                $where['article_key'][] = $product->article;
            }
        }

        $this->load->admin_view('analyze_view', [
            'import_storages' => $import_storages,
            'inner_storages' => $this->supply_model->get_inner_storages(),
            'inner_products' => $inner_products,
            'import_products' => $this->supply_model->get_import_products($where)
        ]);
    }

    public function deviation_max_price() {
        $service = $_SESSION['service']->id;
        $alias = 0;
        $name = 'deviation_max_price';
        $value = $_SESSION['option']->deviation_max_price = $this->data->post('deviation_max_price');
        if($wl_option = $this->db->getAllDataById('wl_options', compact('service', 'alias', 'name'))) {
            $this->db->updateRow('wl_options', compact('value'), $wl_option->id);
        } else {
            $this->db->insertRow('wl_options', compact('service', 'alias', 'name', 'value'));
        }
        if (isset($_SESSION['alias-cache'][$_SESSION['alias']->id])) {
            unset($_SESSION['alias-cache'][$_SESSION['alias']->id]);
        }
        $this->db->cache_delete($_SESSION['alias']->alias, 'wl_aliases');
        $this->redirect();
    }

    public function minus_words_add() {
        if($word = $this->data->post('word')) {
            $word = trim($word);
            if(!empty($word)) {
                if($this->db->getAllDataById('supply_minus_words', compact('word'))) {
                } else {
                    $this->db->insertRow('supply_minus_words', compact('word'));
                }
            }
            $this->redirect();
        }
    }

    public function minus_words_delete() {
        if($word_id = $this->data->get('word_id')) {
            if(is_numeric($word_id)) {
                $this->db->deleteRow('supply_minus_words', $word_id);
            }
        }
        $this->redirect();
    }

    public function minus_brands() {
        $checked = $this->data->post('checked');
        $brand = $this->data->post('brand');
        if($checked == 'false') {
            $this->db->insertRow('supply_minus_brands', compact('brand'));
        } else {
            $this->db->deleteRow('supply_minus_brands', compact('brand'));
        }
    }

    public function minus_product_search() {
        $this->load->smodel('supply_model');
        $result_table = "<tr><td class='text-danger'>Помилка: пустий артикул товару</td></tr>";
        if($article = $this->data->post('article')) {
            $article = $this->supply_model->prepareArticleKey($article);
            $products = $this->supply_model->db_adatrade->select('s_shopshowcase_products as p', 'id, article_show, alias as uri', compact('article'))
                                    ->join('s_shopshowcase_product_options as po', 'value as brand_id', ['product' => '#p.id', 'option' => 1])
                                    ->join('s_shopshowcase_options_name as b', 'name as brand_name', ['option' => '#po.value'])
                                    ->join('wl_ntkd as n', 'name', ['alias' => '#p.wl_alias', 'content' => '#p.id'])
                                    ->get('array');
            $result_table = "<tr><td class='text-danger'>Товари за артикулом <strong>{$article}</strong> не знайдено!</td></tr>";                                
            if($products) {
                $result_table = '';
                $add_link = SITE_URL . "admin/{$_SESSION['alias']->alias}/minus_product_add";
                foreach ($products as $product) {
                    $result_table .= "<tr><td><a href='{$add_link}?product_id={$product->id}' class='pull-right btn btn-xs btn-success'><i class=\"fa fa-plus\" aria-hidden=\"true\"></i></a> <strong>{$product->article_show} {$product->brand_name}</strong> {$product->name}</td></tr>";
                }
            }
        }
        echo $result_table;
    }

    public function minus_product_add() {
        if($product_id = $this->data->get('product_id')) {
            if(is_numeric($product_id)) {
                if(!$this->db->getAllDataById('supply_minus_products', compact('product_id'))) {
                    $this->db->insertRow('supply_minus_products', compact('product_id'));
                }
            }
        }
        $this->redirect();
    }

    public function minus_product_delete() {
        if($product_id = $this->data->get('product_id')) {
            if(is_numeric($product_id)) {
                $this->db->deleteRow('supply_minus_products', compact('product_id'));
            }
        }
        $this->redirect();
    }

    public function __get_Search($content)
    {
        return false;
    }
	
}