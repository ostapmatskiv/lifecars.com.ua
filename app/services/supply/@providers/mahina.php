<?php

class mahina_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'Mahina provider inited' . PHP_EOL;
        // $this->showRows($file);
        // exit;
    }

    public function get_products() {
        $products = [];

        $i_article = 2;
        $i_title = 3;
        $i_brand = 7;
        $i_price = 5;
        $i_availability = 4;
        if (!empty($this->file)) {
            foreach ($this->file as $Key => $Row) {
                if($Key < 7) continue;
                if($Key == 7 || $Key == 8) {
                    // [0] => Марка
                    // [1] => Марка\Модель
                    // [2] => Артикул
                    // [3] => Товар
                    // [4] => Остаток
                    // [5] => Спец 1
                    // [6] => Код. Внутр
                    // [7] => Производитель
                    // [8] => Новый код товара
                    // [9] => Харьков
                    // [10] => Одесса
                    if($Row[2] == 'Артикул' && $Row[3] == 'Товар' && $Row[4] == 'Остаток' && $Row[7] == 'Производитель') {
                        continue;
                    }
                    else {
                        echo 'Check file column structure';
                        exit;
                    }
                }
                // pp($Row);
                $product = new stdClass();
                $product->product_article = $Row[$i_article];
                $product->product_title = $Row[$i_title];
                $product->product_brand = $Row[$i_brand];
                $product->price = $Row[$i_price];
                $product->availability = (int) $Row[$i_availability];
                $products[] = $product;
            }
        }
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