$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: SERVER_URL+'cart/getCountProductsInCart',
        success: function(res)
        {
            $('.__CountProductsInCart').text(res.count);
        }
    });

    $('.sale__card.no_availabilaty .cart__order').click(function () {
        $(this).closest('.no_availabilaty').find('.cart__modal').slideDown();
    })
    $('.sale__card.no_availabilaty .modal__request').click(function () {
        $(this).closest('.cart__modal').find('.cart__form').slideDown();
        $(this).closest('.cart__modal').find('h5').slideUp();
    })
    $('.sale__card.no_availabilaty .cart__hiden').click(function () {
        $(this).closest('.cart__modal').slideUp();
        $(this).closest('.cart__modal').find('.cart__form').css("z-index", "-1");
    })
    $('.sale__card .cart__buy').click(function () {
        product_name = $(this).data('product_name');
        $.ajax({
            url: SITE_URL+'cart/addProduct',
            type: 'POST',
            data: {
                'productKey' : $(this).data('product_key'),
                'quantity' : $(this).closest('.sale__card').find('input').val(),
                'options' : false
            },
            success:function(res){
                if(res.result)
                {
                    $('.__CountProductsInCart').text(res.productsCountInCart);
                    $('#modal-add_success').css('display', 'flex');
                    var logo_path = '/style/images/logo.png';
                    if(res.product.cart_photo)
                        logo_path = res.product.cart_photo;
                    $('#modal-add_success img').attr('src', logo_path);
                    $('#modal-add_success h4.product_name').text(product_name);
                }
            }
        });
    });
    $('main.detal button.detal__cart').click(function () {
        product_name = $(this).data('product_name');
        $.ajax({
            url: SITE_URL+'cart/addProduct',
            type: 'POST',
            data: {
                'productKey' : $(this).data('product_key'),
                'quantity' : $(this).closest('.info__price').find('input').val(),
                'options' : false
            },
            success:function(res){
                if(res.result)
                {
                    $('.__CountProductsInCart').text(res.productsCountInCart);
                    $('#modal-add_success').css('display', 'flex');
                    var logo_path = '/style/images/logo.png';
                    if(res.product.cart_photo)
                        logo_path = res.product.cart_photo;
                    $('#modal-add_success img').attr('src', logo_path);
                    $('#modal-add_success h4.product_name').text(product_name);
                }
            }
        });
    });
    $('#modal-add_success a.close').click(function(event) {
        event.preventDefault;
        $('#modal-add_success').hide()
    });
    $('.modal .close, .modal .fa-times').click(function(event) {
        event.preventDefault;
        $(this).closest('.modal').hide()
        $('#modal-bg').hide()
    });
    $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        count = parseInt($input.val()) + 1;
        if(count > $input.attr('max'))
            count = $input.attr('max');
        $input.val(count);
        $input.change();
        return false;
    });

    $('.fa-bars').click(function() {
        $('.close__menu').css("display", "block");
        $('.mob__nav').css("left", "0");
     });

     $('.fa-times').click(function() {
        $('.mob__nav').css("left", "-281px");
        $('.close__menu').css("display", "none");
     })

     $('.nav__mobile').removeClass("default");
     $(window).scroll(function() {
         if ($(this).scrollTop() > 85) {
             $('.nav__mobile').addClass("default").fadeIn('slow');
             $('.fa-bars').css("color", "#F2F2F2");
         } else {
             $('.nav__mobile').removeClass("default").fadeIn('slow');
             $('.fa-bars').css("color", "#777");
         };
     });

     $('.small__item').magnificPopup({
        type : 'image',
        gallery : {
            enabled : true
        }
     });

     $('.main__link').click(function() {
        event.preventDefault();
       if ($('div.logo__catalog').slideToggle().css("display", "flex")) {
        $('section.cars__catalog').slideToggle().css("display", "none");
       }
    });
     
    $('.cross__eastar').click(function() {
        event.preventDefault();
        if ($('section.cars__catalog').slideToggle().css("display", "flex")) {
            $('div.logo__catalog').slideToggle().css("display", "none");
        }
    });
});

function init__main() {
    $('#main__slick').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });

    $('section.cars__base').hide();
    $('.main__logo a').click(function() {
        var group_alias = $(this).data('group');
        $('section.cars__base').slideUp();
        $('.main__logo a').removeClass('active');
        if ($('.cars__base.models__' + group_alias + ' > a').length > 0) {
        	event.preventDefault();
        	$(this).addClass('active');
            $('.cars__base.models__' + group_alias).slideDown();
        }
    });
}

function init__parts() {
    $('.cars__model').click(function() {
        var group_alias = $(this).data('group');
        if ($('.' + group_alias + '__cars > a').length > 0) {
            var model_cars = $('.' + group_alias + '__cars');
            if (model_cars.is(':visible')) {
                model_cars.slideUp();
            } else {
                $(this).find('button > img').attr('src', '/style/icons/model/arrow-down.svg');
                model_cars.css("display", "flex")
                    .hide()
                    .slideDown();
                $(this).find('button > img').attr('src', '/style/icons/model/arrow-up.svg');
            }
        } else
            window.location.href = SITE_URL + $(this).data('link');
    });
    $('form .filter input').change(function() {
        $(this).closest('form').submit();
    });
}

function init__p_detal() {
    $('#tabs nav a').on('click', function() {
        if(!$(this).hasClass('active'))
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('#tabs').find('.menu__info').removeClass('active').eq($(this).index()).addClass('active');
        return false;
    });
}

var recaptchaVerifyCallback_saveOrders = function(response) {
    $('form.save_orders button').attr('disabled', false);
    $('form.save_orders button').attr('title', '');
};
var recaptchaExpiredCallback_saveOrders = function(response) {
    $('form.save_orders button').attr('disabled', true);
    $('form.save_orders button').attr('title', 'Заповніть "Я не робот"');
};