<script>
    dataLayer.push({
        ecommerce: null
    });
    dataLayer.push({
        event: "<?= $ga4_event ?>",
        ecommerce: {
            currency: "UAH",
            items: [
                <?php foreach ($products as $i => $product) { ?>
                {
                    item_name: "<?= $product->info->name . ' ' . mb_strtoupper($product->info->options['1-manufacturer']->value->name) ?>",
                    item_id: "<?= $product->info->id ?>",
                    price: <?= number_format($product->price, 2, '.', '') ?>,
                    item_brand: "<?= $product->info->options['1-manufacturer']->value->name ?>",
                    item_category: "<?php if (!empty($product->info->parents)) {
                            $name = [];
                            foreach ($product->info->parents as $group) {
                                $name[] = $group->name;
                            }
                            echo implode(' ', $name);
                        }
                        if (!empty($product->info->options['2-part']->value)) {
                            $part = [];
                            foreach ($product->info->options['2-part']->value as $value) {
                                $part[] = $value->name;
                            }
                            echo ' ' . implode(', ', $part);
                        } ?>",
                    quantity: <?= $product->quantity ?>
                },
                <?php } ?>
            ]
        }
    });
</script>