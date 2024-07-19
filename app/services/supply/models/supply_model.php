<?php

class supply_model {

    public $skip_diff_price_percent = 0.5;
    public $db_adatrade;
    // private $db_adatrade_config = [
    //     'host' => 'localhost',
    //     'user' => 'root',
    //     'password' => '',
    //     'database' => 'adatrade.com.ua',
    //     'port' => 3306
    // ];
    private $db_adatrade_config = [
        'host' 		=> 'lifecars.mysql.tools',
        'user' 		=> 'lifecars_adatrade',
        'password'	=> '*363*brcUC',
        'database'	=> 'lifecars_adatrade',
        'port' => 3306
    ];

    function __construct() {
        $this->db_adatrade = new db($this->db_adatrade_config);
    }

    public function get_storages($where = []) {
        return empty($where) ? $this->db->getAllData('supply_storages') : $this->db->getAllDataByFieldInArray('supply_storages', $where);
    }


    private $inner_storages;
    public function get_inner_storages() {
        if(empty($this->inner_storages)) {
            $this->inner_storages = $this->db_adatrade->select('s_shopstorage', 'id, name', ['active' => 1])->get('array');
        }
        return $this->inner_storages;
    }

    public function get_next_inner_product(array $where = []) {
        $s_shopstorage = $this->get_inner_storages();
        $storage_id = (count($s_shopstorage) > 1) ? [] : $s_shopstorage[0]->id;
        if(is_array($storage_id)) {
            foreach ($s_shopstorage as $storage) {
                $storage_id[] = $storage->id;
            }
        }

        $where['#s.amount'] = '>0';
        return $this->db_adatrade->select('s_shopshowcase_products as p', 'id, article, article_show', $where)
                        ->join('s_shopstorage_products as s', 'storage as storage_id, price_in, amount', ['product' => '#p.id', 'storage' => $storage_id])
                        ->order('id ASC')
                        ->limit(1)
                        ->get();
    }

    public function get_import_products(array $where) {
        // $where['&'] = 'i.product_brand NOT IN (SELECT `brand` FROM `supply_minus_brands`)';
        return $this->db_adatrade->select('supply_products as i', '*', $where)
                        ->join('supply_import_log as l', 'storage_id as storage_id', '#i.import_id')
                        ->get('array');
    }

    public function get_minus_products() {
        return $this->db_adatrade->select('supply_minus_products as m')
                        ->join('s_shopshowcase_products as p', 'id, article_show, alias as uri', '#m.product_id')
                        ->join('s_shopshowcase_product_options as po', 'value as brand_id', ['product' => '#p.id', 'option' => 1])
                        ->join('s_shopshowcase_options_name as b', 'name as brand_name', ['option' => '#po.value'])
                        ->join('wl_ntkd as n', 'name', ['alias' => '#p.wl_alias', 'content' => '#p.id'])
                        ->order('name ASC', 'b')
                        ->get('array');
    }

    public function recommendation_price($inner_products, array $import_products) : array {
        $products_recommendation_price = [];
        $minus_words = $this->db_adatrade->getAllData('supply_minus_words');
        if (!empty($inner_products) && !empty($import_products)) {
            foreach ($inner_products->rows as $in_product) {
                $active = new stdClass();
                $active->id_1c = $in_product->id_1c;
                $active->product_id = $in_product->id;
                $active->product_uri = $in_product->uri;
                $active->product_article_key = $in_product->article;
                $active->product_article = $in_product->article_show;
                $active->product_name = $in_product->name;
                $active->brand_name = $in_product->brand_name;
                $active->price_in = $in_product->price_in;
                $active->price_min = 0;
                $active->price_recommendation = 0;
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
                        if ($out_price_usd < $active->price_min || $active->price_min == 0) {
                            $active->price_min = $out_price_usd;
                        }
                    }
                }
                if ($active->price_min > 0) {
                    // "рекомендована ціна" = "найнижча ціна" - ("найнижча ціна" / 100 * 5)
                    $active->price_min = round($active->price_min, 2);
                    $recommendation_price = $active->price_min - ($active->price_min / 100 * $_SESSION['option']->deviation_max_price);
                    $recommendation_price_rounded = round($recommendation_price, 2);
                    $diff_percent = $in_product->price_in ? round(($recommendation_price_rounded - $in_product->price_in) / $in_product->price_in * 100, 2) : 100;
                    if (abs($diff_percent) > $this->skip_diff_price_percent) {
                        $active->price_recommendation = $recommendation_price_rounded;
                        $products_recommendation_price[] = clone $active;
                    }
                }
            }
        }
        return $products_recommendation_price;
    }

    public function prepareArticleKey($text)
	{
		$text = (string) $text;
		$text = trim($text);
		$text = mb_strtolower($text, "utf-8");
        $ua = array('-', '_', ' ', '`', '~', '!', '@', '#', '$', '%', '^', '&', '"', ',', '\.', '\?', '/', ';', ':', '\'', '[+]', '“', '”');
        $en = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        for ($i = 0; $i < count($ua); $i++) {
            $text = mb_eregi_replace($ua[$i], $en[$i], $text);
        }
        $text = mb_eregi_replace("[-]{2,}", '-', $text);
        return $text;
	}

}