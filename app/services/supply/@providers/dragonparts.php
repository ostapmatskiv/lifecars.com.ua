<?php

class dragonparts_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'Dragon Parts provider inited' . PHP_EOL;
        // $this->showRows($file);
        // exit;
    }

    public function get_products() {
        $products = [];

        $i_article = 2;
        $i_title = 3;
        $i_brand = 5;
        $i_price = 7;
        $i_availability = 10;
        if (!empty($this->file)) {
            foreach ($this->file as $Key => $Row) {
                if($Key < 3) continue;
                if($Key == 3) {
                    // [0] => 
                    // [1] => Код
                    // [2] => Артикул ОЕМ
                    // [3] => Наименование
                    // [4] => 
                    // [5] => Производитель
                    // [6] => Применяемость
                    // [7] => Опт Грн.
                    // [8] => Харьков ЛОСК
                    // [9] => КИЕВ
                    // [10] => Остатки
                    // [11] => Заказать
                    if($Row[2] == 'Артикул ОЕМ' && $Row[3] == 'Наименование' && $Row[10] == 'Остатки' && $Row[5] == 'Производитель') {
                        continue;
                    }
                    else {
                        echo 'Check file column structure';
                        exit;
                    }
                }
                if(empty($Row[$i_article])) continue;
                // pp($Row);
                $product = new stdClass();
                $product->product_article = $Row[$i_article];
                $product->product_title = $Row[$i_title];
                $product->product_brand = $Row[$i_brand];
                $product->price = $Row[$i_price];
                $product->availability = $Row[$i_availability];
                $sub_availability = substr($product->availability, 0, 1);
                if($sub_availability == '>') {
                    $product->availability = substr($product->availability, 1) + 1;
                }
                else {
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