<?php

class AutoAsia_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'AutoAsia provider inited' . PHP_EOL;

        ini_set('max_execution_time', 90);
        // ini_set('memory_limit', '1024M');
        // $this->showRows($file);
        // exit;
    }

    public function get_products() {
        $products = [];

        $i_article = 2;
        $i_title = 4;
        $i_brand = 5;
        $i_price = 8;
        $i_availability = 9;
        if (!empty($this->file)) {
            foreach ($this->file as $Key => $Row) {
                if($Key < 11) continue;
                if($Key == 11 || $Key == 8) {
                    // 11: Array
                    // (
                    //     [0] => Бренд
                    //     [1] => Модель
                    //     [2] => Артикул
                    //     [3] => Код
                    //     [4] => Наименование
                    //     [5] => Производитель
                    //     [6] => Страна
                    //     [7] => Опт, USD
                    //     [8] => Опт, грн.
                    //     [9] => Склад
                    // )
                    if($Row[$i_article] == 'Артикул' && $Row[$i_title] == 'Наименование' && $Row[$i_price] == 'Опт, грн.' && $Row[$i_brand] == 'Производитель') {
                        continue;
                    }
                    else {
                        echo 'Check file column structure';
                        exit;
                    }
                }
                if (empty($Row[$i_article])) continue;
                // pp($Row);
                $product = new stdClass();
                $product->product_article = $Row[$i_article];
                $product->product_title = $Row[$i_title];
                $product->product_brand = $Row[$i_brand];
                $product->price = $Row[$i_price];
                $product->availability = $Row[$i_availability];
                $sub_availability = substr($product->availability, 0, 1);
                if ($sub_availability == '>') {
                    $product->availability = substr($product->availability, 1) + 1;
                } else if ($sub_availability == '<') {
                    $product->availability = substr($product->availability, 1) - 1;
                }
                $products[] = $product;
            }
        }
        // pp($products);
        return $products;
    }

    /* return $product->
                        product_title
                        product_article
                        product_brand
                        price (float)
                        availability (int)
    */
    public function prepare_product($item) {
        $price = mb_ereg_replace('/\s+/', '', $item->price);
        // $price = mb_ereg_replace(' ', '', $price);
        $item->price = (float) str_replace(',', '.', $price);
        $item->availability = (int) $item->availability;
        return (array) $item;
    }

    public function showRows($spreadsheet, $limit = 50)
    {
        echo('<meta charset="utf-8"><pre>');
        // echo ('<meta charset="utf-8">');
        $i = 0;
        if (!empty($spreadsheet))
        foreach ($spreadsheet as $Key => $Row) {
            echo $Key . ': ';
            if ($Row)
            print_r($Row);
            else
            var_dump($Row);
            $i++;
            if ($limit > 0 && $i > $limit)
            exit;
        }
        exit;
    }

}