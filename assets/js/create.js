jQuery(document).ready(function () {
    var nbCarMax = 140,
        size = {
            'prefix': 'is-',
            'key': 'size',
            'values': ['one-quarter', 'one-third', 'two-fifths', 'half', 'three-fifths', 'two-thirds', 'three-quarters', 'full']
        },
        height = {
            'prefix': 'h-',
            'key': 'height',
            'values': ['1', '2', '3']
        };

    $(document).on('click', '.column blockquote', function (event) {
        selectBox($(this).parent().parent());
        selectBubble($(this));
        event.stopPropagation();
    });

    $(document).on('focus', '.column textarea', function () {
        $(this).css('height', this.scrollHeight + 'px');
        if (!$(this).parent().hasClass('on')) {
            selectBox($(this).parent().parent().parent());
            selectBubble($(this).parent());
        }
    });

    $(document).on('click', '.column', function () {
        selectBox($(this));
    });

    $('#editPersona').on('click', function () {
        var element = $('#actionsPersona').detach();
        $('.column.on').append(element);
        $('#actionsPersona').removeClass('is-hidden');

        bulmaCarousel.attach('#slidePersona', {
            slidesToScroll: 2,
            slidesToShow: 4,
            infinite: true,
            pagination: false
        });
    });

    $('.persona').on('click', function(){
        $('.bubble.on img').attr('src', $(this).attr('src'));
        $('#'+ $('.bubble.on').attr('data-id') + '_persona').val($(this).attr('data-id'));
        $('#actionsPersona').addClass('is-hidden');
    });

    $('#styleBubble').on('click', function () {
        var style = '';
        if ($('.bubble.on').hasClass('think')) {
            $('.bubble.on').removeClass('think').addClass('yell');
            style = 'yell';
        }
        else if ($('.bubble.on').hasClass('yell')) {
            $('.bubble.on').removeClass('yell');
            style = '';
        }
        else {
            $('.bubble.on').addClass('think');
            style = 'think';
        }
        $('#'+ $('.bubble.on').attr('data-id') + '_style').val(style);
    });

    $('#removeBubble').on('click', function () {
        $('blockquote.on').removeClass('bubble');
        $('blockquote.on textarea').val('');
        $('#addBubble').removeClass('is-hidden');
        $('#removeBubble').addClass('is-hidden');
    });

    $('#addBubble').on('click', function () {
        $('blockquote.on').addClass('bubble');
        $('#addBubble').addClass('is-hidden');
        $('#removeBubble').removeClass('is-hidden');
    });

    $('#addPersona').on('click', function () {
        var box = $(this).parent().parent(),
            nbBubble = box.find('div blockquote').length;

        var nameForm = box.attr('data-id').replace(/comic_pages_/g, 'comic[pages][').replace(/_boxes_/g, '][boxes][') + ']';
        var formBubble = $('#actionsBox').attr('data-bubble')
            .replace(/comic_pages_0_boxes_0/g, box.attr('data-id'))
            .replace(/__name__/g, nbBubble);
        formBubble = formBubble.replace(/comic\[pages\]\[0\]\[boxes\]\[0\]/g, nameForm);
        box.children('div:first').append(formBubble);
    });

    $('#resizeH').on('click', function () {
        resizeBox($(this).parent().parent(), size)
    });

    $('#resizeV').on('click', function () {
        resizeBox($(this).parent().parent(), height)
    });

    $('textarea').on('keyup', function () {
        $(this).css('height', 'auto');
        $(this).css('height', this.scrollHeight + 'px');
    });
    $('textarea').on('keydown', function () {
        var nbCar = $(this).val().length,
            perc = nbCar/nbCarMax;

        if (nbCar > 100) {
            $('#nbCar').html(nbCarMax-nbCar);
        }
        if (nbCar > 95) {
            $(this).removeClass('fs-14 fs-16 fs-18').addClass('fs-12').attr('data-height-row', 14);
            $('#circle').circleProgress({'fill': {gradient: ["#e8793a", "#ff5900"]}});
        }
        else if (nbCar > 65) {
            $('#nbCar').html('');
            $(this).removeClass('fs-12 fs-16 fs-18').addClass('fs-14').attr('data-height-row', 16);
            $('#circle').circleProgress({'fill': {gradient: ["#76a094", "#e8793a"]}});
        }
        else if (nbCar > 35) {
            $(this).removeClass('fs-14 fs-12 fs-18').addClass('fs-16').attr('data-height-row', 18);
            $('#circle').circleProgress({fill: { gradient: ["#9cd3c6", "#76a094"]}});
        }
        else {
            $(this).removeClass('fs-12 fs-14 fs-16').addClass('fs-18').attr('data-height-row', 23);
        }

        $('#circle').circleProgress('value', perc);
    });

    function selectBox(box) {
        $('.column').removeClass('on');
        box.addClass('on');
        var element = $('#actionsBox').detach();
        box.append(element);
        if (!box.find('div blockquote.on').length) {
            $('blockquote').removeClass('on');
            $('#actionsBubble').hide();
        }
    }

    function selectBubble(bubble) {
        $('#circle').circleProgress({
            value: 0,
            size: 30,
            thickness: 3,
            animation: false,
            fill: {
                gradient: ["#9cd3c6", "#76a094"]
            }
        });

        $('.bubble').removeClass('on');
        bubble.addClass('on');
        var element = $('#actionsBubble').detach();
        bubble.parent().parent().append(element);
        $('#actionsBubble').show();
        bubble.children('textarea').focus();
        if (bubble.hasClass('bubble')) {
            $('#addBubble').addClass('is-hidden');
            $('#removeBubble').removeClass('is-hidden');
        }
        else {
            $('#addBubble').removeClass('is-hidden');
            $('#removeBubble').addClass('is-hidden');
        }
    }

    function resizeBox(box, size) {
        var ok = 0,
            newValue = '',
            formId = box.attr('data-id');

        size.values.forEach(value => {
            if (ok == 1) {
                newValue = value;
                ok = 'end';
            }
            if (box.hasClass(size.prefix + value) && ok == 0) {
                box.removeClass(size.prefix + value);
                ok = 1;
            }
        });
        if (ok != 'end') {
            newValue = size.values[0];
        }

        box.addClass(size.prefix + newValue);
        $('#' + formId + '_' + size.key).val(newValue);
    }
});
