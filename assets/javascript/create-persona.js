jQuery(document).ready(function () {
    $('.actions-main .plus').on('click', function () {
        $('.actions-main').addClass('is-hidden');
        $('.actions-main.'+ $(this).attr('data-sect')).removeClass('is-hidden');

        if ($(this).attr('data-sect') === 'clothes') {
            $('.sub.top').addClass('on');
            $('.actionsg').addClass('is-hidden');
            $('.actionsg.top').removeClass('is-hidden');
        }
        else if ($(this).attr('data-sect') === 'head') {
            $('.sub.hair').addClass('on');
            $('.actionsg').addClass('is-hidden');
            $('.actionsg.hair').removeClass('is-hidden');
        }
    });

    $('.actions-main .back').on('click', function () {
        $('.actions-main').addClass('is-hidden');
        $('.actions-main[data-id="main"]').removeClass('is-hidden');
        $('.actionsg').addClass('is-hidden');
        $('.actionsg.colors').removeClass('is-hidden');
    });

    $('.color').on('click', function () {
        var color = $(this).attr('data-color');
        $('.actions-main .color div').attr('class', 'color-'+ color).parent().attr('data-color', color);
        $('.actionsg .color').removeClass('on');
        $(this).addClass('on');
        reloadPersona();
    });

    $('.sex').on('click', function () {
        $('.sex').removeClass('on');
        $(this).addClass('on');
        reloadPersona();
    });

    $('.dice').on('click', function () {
        randomPersona();
    })

    function reloadPersona() {
        var persona = $('.actions-sex .on').attr('data-src').replace(/ID/g, ('000' + $('.actions-main .color').attr('data-color')).slice(-4));
        $('.persona').attr('src', persona);
        $('#persona_creator_persona').val(persona);
    }

    $('.sub').each(function(){
        var max = parseInt($(this).attr('data-max')),
            src = $(this).attr('data-src'),
            sect = $(this).attr('data-sect'),
            type = $(this).attr('data-type');

        $('#persona').after('<div class="actions actionsg '+ type +' is-hidden"></div>');

        for(var i = 0; i <= max; i++) {
            var fullId = ('000' + i).slice(-4),
                html = '<div class="button is-light '+ sect +'" data-type="'+ type +'" data-id="'+ fullId +'">'
                    + ((sect == 'head') ? '<img src="/persona/creator/head.png"/>' : '<img src="/persona/creator/women/0000.png"/>')
                    +'<img src="'+ src.replace(/ID/g, fullId) +'" /></div>';

            $('.actionsg.'+ type).append(html);
        }

        var id = (sect == 'head' && type != 'hat') ? '0002' : '0000';
        $('#persona_creator_' + type).val(id);
        $('#persona').append('<img src="' + $(this).attr('data-src').replace(/ID/g, id) + '" class="' + type + '"/>');

    });

    $('.sub').on('click', function () {
        $('.sub').removeClass('on');
        $(this).addClass('on');
        $('.actionsg').addClass('is-hidden');
        $('.actionsg.'+ $(this).attr('data-type')).removeClass('is-hidden');
    });

    $(document).on('click', '.actionsg .button', function () {
        var type = '#persona .'+ $(this).attr('data-type'),
            id = $(this).attr('data-id'),
            src = $(type).attr('src').replace(/\d+/g, id);

        $(type).attr('src', src);
        $('#persona_creator_'+ $(this).attr('data-type')).val(id);
    })

    function randomPersona() {
        $('.actions-main .color').attr('data-color', Math.floor(Math.random() * Math.floor(11)));
        reloadPersona();

        $('.sub').each(function() {
            var max = parseInt($(this).attr('data-max')),
                rand = ('000' + Math.floor(Math.random() * Math.floor(max))).slice(-4),
                src = $(this).attr('data-src').replace(/ID/g, rand);

            $('#persona_creator_' + $(this).attr('data-type')).val(rand);

            $('#persona img.'+ $(this).attr('data-type')).attr('src', src);
        });
    }
});
