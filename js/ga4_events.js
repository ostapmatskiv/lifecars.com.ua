let ga4 = {
    add_to_cart(res) {
        // console.log(res);

        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "add_to_cart",
            ecommerce: {
                currency: "UAH",
                items: [{
                    item_name: res.product.name,
                    item_id: res.product.id,
                    price: res.product.price,
                    item_brand: res.product.brand,
                    item_category: res.product.category,
                    quantity: res.product.quantity
                }]
            }
        });
    },

    remove_from_cart(res) {
        // console.log(res);

        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "remove_from_cart",
            ecommerce: {
                currency: "UAH",
                items: [{
                    item_name: res.product.name,
                    item_id: res.product.id,
                    price: res.product.price,
                    item_brand: res.product.brand,
                    item_category: res.product.category,
                    quantity: res.product.quantity
                }]
            }
        });
    }
}