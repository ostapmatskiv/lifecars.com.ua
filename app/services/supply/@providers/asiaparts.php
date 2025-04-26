<?php

class asiaparts_provider {

    private $file;

    public function init($file) {
        $this->file = $file;
        echo 'Asia Parts provider inited' . PHP_EOL;
    }

    public function get_products() {
        return $this->file->items->item;
    }

    /* return $product->
                        product_title
                        product_article
                        product_brand
                        price (float)
                        availability (int)
    */
    public function prepare_product($item) {
        $price = mb_ereg_replace('/\s+/', '', $item->{'priceРозн.грн'});
        $price = mb_ereg_replace(' ', '', $price);
        $item->price = (float) str_replace(',', '.', $price);
        $item->availability = (int) $item->availability;
        unset($item->image, $item->priceAгрн, $item->{'priceРозн.грн'});
        foreach (['title', 'article', 'brand'] as $key) {
            $item->{"product_{$key}"} = $item->$key;
            unset($item->$key);
        }
        
        if(substr($item->product_article, -3) == '-KM') {
            $item->product_article = substr($item->product_article, 0, -3);
        }
        return (array) $item;
    }

}