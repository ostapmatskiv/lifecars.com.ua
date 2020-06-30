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
}