$(document).ready(function () {

    $('.rating').each(function () {
        var rate = parseFloat($(this).attr('data-rate'));

        for (var i = 1; i <= 5; i++) {
            var classe = '';

            if (i <= rate) {
                classe = 'one';
            }
            else if (i-1 < rate && rate < i) {
                 classe = 'half';
            }
            $(this).append('<span data-rate="'+ i+'" class="'+ classe +'"></span>');
        }
    });

    $(document).on('mouseover', '.rating.enable span', function () {
        var value = $(this).attr('data-rate');
        for (var i = 1; i <= value; i++) {
            $('.rating.enable span[data-rate="'+ i+'"]').addClass('hover');
        }
    });
    $(document).on('mouseout', '.rating.enable span', function () {
        $('.rating span').removeClass('hover');
    });

    $(document).on('click', '.rating.enable span', function () {
        var url = $(this).parent().attr('data-url');
        var value = $(this).attr('data-rate');
        $.get( url, { rate: value} );

        $('.rating.enable span').removeClass('one hover')
        for (var i = 1; i <= value; i++) {
            $('.rating.enable span[data-rate="'+ i+'"]').addClass('one');
        }
    });

});