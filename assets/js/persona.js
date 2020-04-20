jQuery(document).ready(function () {
    $('.color').on('click', function () {
        $('.color').removeClass('on');
        $(this).addClass('on');
        reloadPersona();
    });

    $('.sex').on('click', function () {
        $('.sex').removeClass('on');
        $(this).addClass('on');
        reloadPersona();
    });

    function reloadPersona() {
        $('.persona').attr('src', $('.sex.on').attr('data-src').replace(/ID/g, ('000' + $('.color.on').attr('data-color')).slice(-4)));
    }

    $('.sub').each(function(){
        var idsub = '0001',
            fullNum = ('000' + $(this).attr('data-num')).slice(-4);
        if ($(this).attr('data-sub')) {
            idsub = ('000' + $(this).attr('data-sub')).slice(-4);
        }
        $('#' + $(this).attr('id') +' img').attr('src', $(this).attr('data-src').replace(/ID/g, idsub));
        $('#persona_creator_'+ $(this).attr('id')).val(fullNum);
        $('#persona').append('<img src="'+ $(this).attr('data-src').replace(/ID/g, fullNum) +'" class="'+ $(this).attr('id') +'"/>');
    });

    $('.previous').on('click', function () {
        var parent = $(this).parent(),
            max = parseInt(parent.attr('data-max')),
            num = parseInt(parent.attr('data-num')),
            newNum = (num-1 < 0) ? max : num-1,
            fullNum = ('000' + newNum).slice(-4);

        parent.attr('data-num', newNum);
        $('#persona_creator_'+ parent.attr('id')).val(fullNum);
        $('#persona .'+ parent.attr('id')).attr('src', parent.attr('data-src').replace(/ID/g, fullNum));
    });

    $('.next').on('click', function () {
        var parent = $(this).parent(),
            max = parseInt(parent.attr('data-max')),
            num = parseInt(parent.attr('data-num')),
            newNum = (num+1 > max) ? 0 : num+1,
            fullNum = ('000' + newNum).slice(-4);

        parent.attr('data-num', newNum);
        $('#persona_creator_'+ parent.attr('id')).val(fullNum);
        $('#persona .'+ parent.attr('id')).attr('src', parent.attr('data-src').replace(/ID/g, fullNum));
    });
});
