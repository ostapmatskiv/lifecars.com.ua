<?php

class vostok_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'VOSTOK-PARTS provider inited' . PHP_EOL;
        // $this->showRows($file);
        // exit;
    }

    public function get_products() {
        $products = [];

        $i_article = 1;
        $i_title = 5;
        $i_brand = 2;
        $i_price = 7;
        // $i_availability = 10;
        if (!empty($this->file)) {
            foreach ($this->file as $Key => $Row) {
                if($Key < 4) continue;
                if($Key == 4) {
                    // 4: Array
                    // (
                    //     [0] => Кат.номер
                    //     [1] => OE Номер
                    //     [2] => Производитель
                    //     [3] => МАРКА
                    //     [4] => МОДЕЛЬ
                    //     [5] => Описание
                    //     [6] => Инфо.
                    //     [7] => Круп. опт.
                    // )
                    if($Row[$i_article] == 'OE Номер' && $Row[$i_title] == 'Описание' && $Row[$i_price] == 'Круп. опт.' && $Row[$i_brand] == 'Производитель') {
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
                $product->availability = 1;
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