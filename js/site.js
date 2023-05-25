$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: SERVER_URL+'cart/getCountProductsInCart',
        success: function(res)
        {
            $('.__CountProductsInCart').text(res.count);
        }
    });

    if($(window).width() < 500)
    {
        let car_val = $('#carMobileGroup').val();
        if(car_val != '0')
        {
            $('#modelMobileGroup option[value=0]').addClass('m-hide');
            $('#modelMobileGroup option[value='+car_val+']').removeClass('m-hide').text('Всі моделі');
        }
    }

    $('#carMobileGroup').change(function(event) {
        let value = $('#carMobileGroup').val();
        if(value > 0)
        {
            $('#modelMobileGroup option').addClass('m-hide').each(function(index, el) {
                if($(el).data('parent') == value)
                    $(el).removeClass('m-hide');
            });
            $('#modelMobileGroup option[value=0]').addClass('m-hide');
            $('#modelMobileGroup option[value='+value+']').prop('selected', true).text('Всі моделі').removeClass('m-hide');
        }
        else
        {
            $('#modelMobileGroup option').addClass('m-hide');
            $('#modelMobileGroup option[value=0]').removeClass('m-hide').prop('selected', true).text('Оберіть марку');
        }
    });

    $('#modelMobileGroup').change(function(event) {
        let val = $(this).closest('form').find('input').val();
        if(val != '')
            $(this).closest('form').submit()
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

                    ga4.add_to_cart(res);
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

                    ga4.add_to_cart(res);
                }
            }
        });
    });
    $('#modal-add_success a.close').click(function(event) {
        event.preventDefault;
        $('#modal-add_success').hide()
    });
    $('main.detal button.detal__bay').click(function () {
        $('#modal-buyProduct').css('display', 'flex');

        $('#modal-buyProduct h4.product_name').text( $(this).data('product_name') );
        $('#modal-buyProduct input[name=productKey]').val( $(this).data('product_key') );
        $('#modal-buyProduct input[name=quantity]').val( $(this).closest('.info__price').find('input').val() );
    });
    $('#modal-buyProduct a.close').click(function(event) {
        event.preventDefault;
        $('#modal-buyProduct').hide()
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
        $('.mob__menu').toggleClass('active');
        $('.close__menu').css("display", "block");
        $('.mob__nav').css("left", "0");
     });

     $('.fa-times').click(function() {
         $('.mob__menu, #filter-bar').removeClass('active');
        $('.mob__nav').css("left", "-281px");
        $('.close__menu').css("display", "none");
     });

    $('.show-filters').click(function() {
        $('#filter-bar').addClass('active').removeClass('m-hide');
        $('.close__menu').css("display", "block");
    });

     $('.nav__mobile').removeClass("default");
     $(window).scroll(function() {
         if ($(this).scrollTop() > 85) {
             $('.nav__mobile').addClass("default").fadeIn('slow');
             $('#main-search').addClass('scrolled');
         } else {
             $('.nav__mobile').removeClass("default").fadeIn('slow');
             $('#main-search').removeClass('scrolled');
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
        $('.main__logo a').removeClass('active');
        if ($('.cars__base.models__' + group_alias + ' > a').length > 0) {
        	event.preventDefault();
            $(this).closest('.main__logo-wrapper').find('.cars__base').stop().slideUp();
        	$(this).addClass('active').toggleClass('open').closest('.main__logo-wrapper').find('.cars__base.models__' + group_alias).stop().slideToggle();
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

    if(window.location.hash == '#reviews')
    {
        $('#tabs nav a, #tabs .menu__info').removeClass('active');
        $('#tabs nav a[href$="reviews"]').addClass('active');
        $('#tab-reviews').addClass('active');
    }
}

var recaptchaVerifyCallback_saveOrders = function(response) {
    $('form.save_orders button').attr('disabled', false);
    $('form.save_orders button').attr('title', '');
};
var recaptchaExpiredCallback_saveOrders = function(response) {
    $('form.save_orders button').attr('disabled', true);
    $('form.save_orders button').attr('title', 'Заповніть "Я не робот"');
};


$(document).on('click','.tab', function () {
    $(this).toggleClass('active');
    $(this).parent().find('.tab-item').slideToggle();
});

$('.category-mobile-menu>.flex>a').each(function (index){
    $(this).css('order', index);
});
$('.category-mobile-menu>.flex>section').each(function (index){
    $(this).css('order', index);
});

$(document).on('focusout', 'form.type-2 input', function (){
    if($(this).val().length) {
        $(this).closest('.input-group').addClass('val');
    } else {
        $(this).closest('.input-group').removeClass('val')
    }
});
$(document).on('focus', 'form.type-2 input', function (){
    $(this).closest('.input-group').addClass('val');
});

var mask_options = {
    translation: {
        'Z': {
            pattern: 0, optional: false
        },
        'N': {
            pattern: /[1-9]/, optional: false
        }
    }
    // onKeyPress: function(cep, e, field, options) {
    //   mask = '+38 000 000 00 00';
    //   if(cep == '+')
    //       field.mask(mask, mask_options);
    //   // else if(cep.length > 3)
    //   // {
    //   //     cep = cep.substr(0, 3);
    //   //     if(cep == '+38')
    //   //         $('input[name=phone]').mask('+38 000 000 00 00', mask_options);
    //   //     else
    //   //         field.mask(mask, mask_options);
    //   // }
    //   }
};
$('input#phone-1').focus(function () {
    if (this.value.length == 0) {
        this.value = '+380';
    }
}).mask('+38Z NN 000 00 00', mask_options);
$('input#phone-1').change(function(){
    let input = $(this),
        phoneError = $('#phoneError-1');
    input.removeClass('with-error');
    phoneError.addClass('hide');
    if (this.value.substr(0, 4) != '+380' || this.value.length != 17) {
        phoneError.removeClass('hide');
        input.addClass('with-error').focus();
        // alert('Введіть коректний номер телефону починаючи +380');
    }
});

$("._validLettersUK").on("change", function() {
    var input = $(this),
        text = input.val(),
        h5_error = input.parent().find('h5.text-danger');

    input.removeClass('with-error');
    h5_error.addClass('hide');

    if(/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯыЫёЁъЪЭэ]+$/.test(text) === false) {
        input.addClass('with-error').focus();
        h5_error.removeClass('hide');
        // alert('Тільки кирилиця');
        return false;
    }
});

$('#modal-buyProduct form').submit(function(){
    let with_error = false;
    $('#modal-buyProduct input').each(function(){
        if($(this).val() == '') {
            $(this).addClass('with-error').focus();
            with_error = true;
        }
    });
    $('#modal-buyProduct input._validLettersUK').each(function(){
        var input = $(this),
            text = input.val(),
            h5_error = input.parent().find('h5.text-danger');
    
        if(/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯыЫёЁъЪЭэ]+$/.test(text) === false) {
            input.addClass('with-error').focus();
            h5_error.removeClass('hide');
            with_error = true;
        }
    });
    phone = $("#phone-1").val();
    if(phone.substr(0, 4) != '+380' || phone.length != 17) {
        $("#phone-1").addClass('with-error').focus();
        $("#phone-1").parent().find('h5.text-danger').removeClass('hide');
        with_error = true;
    }
    if(with_error) {
        return false;
    }
});