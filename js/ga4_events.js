let ga4 = {
    add_to_cart(res) {
        console.log(res);

        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "add_to_cart",
            ecommerce: {
                items: [{
                    item_name: res.product.name,
                    item_id: res.product.id, // Передаємо ID товару, під яким він записаний у базі даних сайту
                    price: res.product.price,      // Передаємо ціну товару враховуючи знижку та з урахуванням кількості товару. Важливо! Розділювач крапка. Після крапки дві цифри.
                    item_brand: res.product.brand,       // Передаємо бренд товару. 
                    item_category: res.product.category,             // Передаємо категорію товару.
                    quantity: res.product.quantity
                }]
            }
        });
    },

    remove_from_cart() {
        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "remove_from_cart",
            ecommerce: {
                items: [{
                    item_name: "Premium Biselado Fasad Vit 10x30 cm",      // Передаємо назву товару
                    item_id: "KASC4018", // Передаємо ID товару, під яким він записаний у базі даних сайту
                    price: 287.00,      // Передаємо ціну товару враховуючи знижку та з урахуванням кількості товару. Важливо! Розділювач крапка. Після крапки дві цифри.
                    item_brand: "Premium Biselado",       // Передаємо бренд товару. 
                    item_category: "Kakel",             // Передаємо категорію товару.
                    item_variant: "10x30",                // Передаємо розмір плитки або товщину кріплень
                    quantity: 1                           // Передаємо кількість товару
                }]
            }
        });
    }
}