function updateProductPrice() {
	if(productOptionsChangePrice.length > 0)
	{
		var options = [];
		for (var i = 0; i < productOptionsChangePrice.length; i++) {
			var id = productOptionsChangePrice[i];
			var value = false;
			var elem = $('[name=product-option-' + id + ']');
			if(elem)
			{
				if(elem.attr('type') == 'radio')
					value = $('[name=product-option-' + id + ']:checked').val()
				else if(elem.prop("tagName") == 'SELECT')
					value = elem.find(':selected').val()
			}
			if(value)
				options[id] = value;
		}
		if(options.length > 0)
		{
			$.ajax({
				url: ALIAS_URL+'ajaxupdateproductprice',
				type: 'POST',
				data: {
					'product' : productID,
					'options' : options
				},
				success:function(res){
					if(res.price){
						$('#product-price').html(res.price + ' грн');
					}
				}
			})
		}
	}
}

$('#tabs').tabs();

$('.product-gallery').lightSlider({
    gallery:true,
    item:1,
    auto:true,
    loop:true,
    thumbItem:4,
    slideMargin:0,
    enableDrag: false,
    mode: 'fade',
    speed: 1000,
    currentPagerPosition:'left',
    onSliderLoad: function(el) {
        el.lightGallery({
            selector: '.product-gallery figure'
        });
    }   
});


$('button#showContacts').click(function(event) {
	$(this).html('<img src="/style/images/icon-loading.gif" width="50">');
	$.ajax({
		url: '/seller/public_contacts',
		type: 'POST',
		data: { product_id: this.dataset.id },
	})
	.done(function(html) {
		showContacts.outerHTML = '<p id="showContacts">'+html+'</p>';
	});
});