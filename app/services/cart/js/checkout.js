$(document).ready(function (){
    var mask_options = {
        translation: {
            'Z': {
                pattern: 0, optional: false
            },
            'N': {
                pattern: /[1-9]/, optional: false
            }
        }
    };
    $('input#phone, input[name=recipientPhone]').focus(function () {
        if (this.value.length == 0) {
            this.value = '+380';
        }
    }).mask('+38Z N0 000 00 00', mask_options);

    // if (typeof Sticky === "function")
    // {
    //     var sticky_percents = new Sticky('#cart #percents');
    //     var sticky_percents_info = new Sticky('#cart #percents+.info');
    // }
    
    setPercents();

    if (typeof initShipping === "function")
        initShipping();

    if (typeof cities !== "undefined")
    {
        if (typeof autocomplete === "function")
            $("#shipping-cities input").autocomplete({ source: cities }).attr('autocomplete', 'none');
        else
            cities.forEach(function(city) {
                $("#shipping-cities-list").append('<option>'+city+'</option>');
            })
    }
});

$('form.coupon-form').submit(function(){
    $('.couponPreSave').remove();
    $('form#confirm').find('input[type=text], input[type=hidden], input[type=radio]:checked, textarea').each(function(){
        $( this ).clone().attr('required', false).addClass('couponPreSave hide').appendTo("form.coupon-form");
    });
    return true;
});

$('#cart-signup div').click(function(){
    $(this).parent().find('div').removeClass('active');
    $(this).addClass('active');
    var tab = $(this).data('tab');
    if(tab == 'new-buyer')
    {
        $('#new-buyer').removeClass('hide');
        $('#regular-buyer').addClass('hide');
    }
    else
    {
        $('#new-buyer').addClass('hide');
        $('#regular-buyer').removeClass('hide');
    }
});

$('form input[name=payment_method]').change(function(){
    $('#payments label').removeClass('active');
    $(this).closest('label').addClass('active');
    $('#payments .payment-info').slideUp();
    $('#payment-'+$(this).val()).slideDown();
})

$( '#cart #oferta' ).change(function() {
    if($( this ).find('input').is(":checked"))
        $( this ).parent().find('i').attr('class', 'fas fa-check-square');
    else
        $( this ).parent().find('i').attr('class', 'far fa-square');
})

$( 'form#confirm' ).find( 'select, textarea, input' ).change(function() { setPercents() })
function setPercents() {
    var all_elements = 0, empty_elements = 0, percents = 100;
    radio_names = [];
    $( 'form#confirm' ).find( 'select, textarea, input' ).each(function(){
        if( $( this ).prop( 'required' )){
            var type = $( this ).prop( 'type' ),
                name = $( this ).prop( 'name' );
            if(type == 'radio') {
                if(! radio_names.includes(name))
                {
                    radio_names.push(name);
                    all_elements++;
                    if(! $( 'form#confirm' ).find('input:radio[name="'+name+'"]').is(":checked"))
                        empty_elements++;
                }
            } else
                all_elements++;

            if(type == 'checkbox') {
                if(! $( this ).is(":checked"))
                    empty_elements++;
            } else if ( ! $( this ).val() )
                empty_elements++;
        }
    });
    if(all_elements > 0)
        percents = Math.ceil((all_elements - empty_elements) / all_elements * 100);
    $('table.__cart_products_list .name_action ~ table tbody tr td.amount input').each(function() {
        var val = $( this ).val(),
            value = isInt(val) ? parseInt(val) : 0;
        if(value <= 0 && percents > 0)
            percents--;
    })
    // $('#percents .active').animate({width: percents+'%'});
    // $('#percents .text').text(percents+'%');
    if(percents == 100)
        $('form#confirm button.checkout').attr('disabled', false).addClass('active');
    else
        $('form#confirm button.checkout').removeClass('active');
        // $('form#confirm button.checkout').attr('disabled', true).removeClass('active');
}

function changeShipping(el) {
    active_shipping_method = $(el).val();

    if(shippingsInformation[active_shipping_method] != '')
    {
        $("#shipping-info").text(shippingsInformation[active_shipping_method]);
        $("#shipping-info").slideDown();
    }
    else
        $("#shipping-info").slideUp();

    $('#Shipping_to_cart').html('');
    $("#shipping-cities, #shipping-departments, #shipping-address").addClass('hide');
    $("#shipping-cities input, #shipping-departments input, #shipping-address textarea, #Shipping_to_cart input, #Shipping_to_cart textarea, #Shipping_to_cart select").attr('required', false);

    shippingType = shippingsTypes[active_shipping_method];
    if(shippingType == '0')
    {
        $("#divLoading").addClass('show');
        $.ajax({
            url: SITE_URL + 'cart/get_Shipping_to_cart',
            type: 'POST',
            data: {
                shipping: active_shipping_method,
                ajax: true
            },
            complete: function() {
                $("div#divLoading").removeClass('show');
                if (typeof initShipping === "function")
                    initShipping();
            },
            success: function(html) {
                $('#Shipping_to_cart').html(html);
            }
        })
    }
    else if(shippingType == '1')
    {
        $("#shipping-cities, #shipping-address").removeClass('hide');
        $("#shipping-cities input, #shipping-address textarea").attr('required', true);
    }
    else if(shippingType == '2')
    {
        $("#shipping-cities, #shipping-departments").removeClass('hide');
        $("#shipping-cities input, #shipping-departments input").attr('required', true);
    }
}

// $("#cart input#email").on("change", function() {
//     $("#divLoading").addClass('show');
//     email = $(this).val();
//     $.ajax({
//         url: SITE_URL + 'cart/checkEmail',
//         type: 'POST',
//         data: {
//             email: email,
//             ajax: true
//         },
//         complete: function() {
//             $("div#divLoading").removeClass('show');
//         },
//         success: function(res) {
//             if (res.result == true)
//             {
//                 $('#new-buyer').addClass('hide');
//                 $('#regular-buyer, #cart_notify').removeClass('hide');
//                 $('#cart_notify').removeClass('alert-danger').addClass('alert-success');
//                 $('#cart #regular-buyer input[name=email]').val(res.email);
//                 $('#cart #cart_notify p').html(res.message);
//                 $('#cart #regular-buyer input[name=password]').focus();
//             }
//             else
//             {
//                 $( 'form#confirm' ).find( 'input[name="email"]' ).val(email);
//                 $('#new-buyer').removeClass('hide');
//                 $('#regular-buyer').addClass('hide');
//                 if($('#recipientName').val() == '')
//                     $('#recipientName').val($('#cart input#name').val());
//                 setPercents()
//             }
//         }
//     })
// });
$("#cart input#phone").on("change", function() {
    var phone = $(this).val(),
        recipientPhone = $('form#confirm input[name="recipientPhone"]'),
        input = $(this),
        h5_error = input.parent().find('h5.text-danger');

    input.removeClass('with-error');
    h5_error.addClass('hide');
    $( 'form#confirm' ).find( 'input[name="phone"]' ).val(phone);
    
    // if(recipientPhone.val() == '')
    if (this.value.substr(0, 4) == '+380' && this.value.length == 17) {
        // $('#phoneError').addClass('hide');
        recipientPhone.val(phone);
        setPercents();
    }
    else {
        input.addClass('with-error').focus();
        h5_error.removeClass('hide');
        // $('#phoneError').text('Введіть коректний номер телефону починаючи +380').removeClass('hide');
        // alert('Введіть коректний номер телефону починаючи +380');
        return false;
    }
});

$("#cart input#first_name, #cart input#last_name").on("change", function() {
    var first_name = $('#cart input#first_name').val(),
        last_name = $('#cart input#last_name').val(),
        recipientName = $('form#confirm input[name="recipientName"]'),
        input = $(this),
        h5_error = input.parent().find('h5.text-danger');

        input.removeClass('with-error');
        h5_error.addClass('hide');

    if(/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯыЫёЁъЪЭэ]+$/.test(first_name) === false) {
        input.addClass('with-error').focus();
        h5_error.removeClass('hide');
        // alert('Тільки кирилиця');
        return false;
    }
    if(last_name.length > 0)
        if(/^[аАбБвВгГґҐдДеЕєЄжЖзЗиИіІїЇйЙкКлЛмМнНоОпПрРсСтТуУфФхЧцЦчЧшШщЩьЬюЮяЯыЫёЁъЪЭэ]+$/.test(last_name) === false) {
            input.addClass('with-error').focus();
            h5_error.removeClass('hide');
            // alert('Тільки кирилиця');
            return false;
        }

    name = first_name + ' ' + last_name;

    $( 'form#confirm' ).find( 'input[name="name"]' ).val(name);
    // if(recipientName.val() == '')
        recipientName.val(name);
    setPercents()
});

function facebookSignUp() {
    FB.login(function(response) {
        if (response.authResponse) {
            $("#divLoading").addClass('show');
            var accessToken = response.authResponse.accessToken;
            FB.api('/me?fields=email', function(response) {
                if (response.email && accessToken) {
                    $('#authAlert').addClass('collapse');
                    $.ajax({
                        url: SITE_URL + 'signup/facebook',
                        type: 'POST',
                        data: {
                            accessToken: accessToken,
                            ajax: true
                        },
                        complete: function() {
                            $("div#divLoading").removeClass('show');
                        },
                        success: function(res) {
                            if (res['result'] == true) {
                                location.reload();
                            } else {
                                $('#authAlert').removeClass('collapse');
                                $("#authAlertText").text(res['message']);
                            }
                        }
                    })
                } else {
                    $("div#divLoading").removeClass('show');
                    $("#clientError").text('Для авторизації потрібен e-mail');
                    setTimeout(function(){$("#clientError").text('')}, 5000);
                    FB.api("/me/permissions", "DELETE");
                }
            });
        } else {
            $("div#divLoading").removeClass('show');
        }

    }, { scope: 'email' });
    return false;
}

$('form#confirm button.checkout').click(function() {
    let with_error = false;
    $('#new-buyer input').each(function(){
        if($(this).val() == '') {
            $(this).addClass('with-error').focus();
            with_error = true;
        }
    });
    phone = $("#cart input#phone").val();
    if(phone.substr(0, 4) != '+380' || phone.length != 17) {
        $("#cart input#phone").addClass('with-error').focus();
        $("#cart input#phone").parent().find('h5.text-danger').removeClass('hide');
        with_error = true;
    }
    if(with_error) {
        return false;
    }
});

$('form#confirm').on('submit', function () {
    $("#divLoading").addClass('show');
})