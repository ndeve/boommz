jQuery(document).ready(function () {

    $('.previous').on('click', function () {
        var parent = $(this).parent(),
            max = parseInt(parent.attr('data-max')),
            num = parseInt(parent.attr('data-num')),
            newNum = (num-1 < 0) ? max : num-1;

        parent.attr('data-num', newNum);
        $('#persona .'+ parent.attr('id')).attr('src', parent.attr('data-src').replace(/ID/g, ('000' + newNum).slice(-4)));
    });

    $('.next').on('click', function () {
        var parent = $(this).parent(),
            max = parseInt(parent.attr('data-max')),
            num = parseInt(parent.attr('data-num')),
            newNum = (num+1 > max) ? 0 : num+1;

        parent.attr('data-num', newNum);
        $('#persona .'+ parent.attr('id')).attr('src', parent.attr('data-src').replace(/ID/g, ('000' + newNum).slice(-4)));
    });
});
