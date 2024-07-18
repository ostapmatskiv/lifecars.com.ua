<?php

class xpert_auto_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'Xpert-auto provider inited' . PHP_EOL;
        // for debug
        // print_r($this->get_products());
        // exit;
    }

    public function get_products() {
        $products = [];
        foreach ($this->file->channel->items as $item) {
            foreach ($item as $product) {
                $products[] = $product;
            }
            return $products;
        }
        return [];
    }

    /* return $product->
                        product_title
                        product_article
                        product_brand
                        price (float)
                        availability (int)
    */
    public function prepare_product($item) {
        $item->price = (float) str_replace(' ', '', $item->price);
        $item->availability = (int) $item->availability;
        foreach (['title', 'article', 'brand'] as $key) {
            $item->{"product_{$key}"} = $item->$key;
            unset($item->$key);
        }
        return (array) $item;
    }

}