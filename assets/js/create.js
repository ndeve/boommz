jQuery(document).ready(function () {
    orderBox();

    if ($('.columns').length) {
        $('.columns').each(function () {
            $(this).attr('data-nbBox', $(this).children('.column').length);
        });
    }

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

    $('.persona').on('click', function () {
        $('.bubble.on img').attr('src', $(this).attr('src'));
        $('#' + $('.bubble.on').attr('data-id') + '_persona').val($(this).attr('data-id'));
        $('#actionsPersona').addClass('is-hidden');
    });

    $('#styleBubble').on('click', function () {
        var style = '';
        if ($('.bubble.on').hasClass('think')) {
            $('.bubble.on').removeClass('think').addClass('yell');
            style = 'yell';
        } else if ($('.bubble.on').hasClass('yell')) {
            $('.bubble.on').removeClass('yell');
            style = '';
        } else {
            $('.bubble.on').addClass('think');
            style = 'think';
        }
        $('#' + $('.bubble.on').attr('data-id') + '_style').val(style);
    });

    $('#changeBackground').on('click', function () {
        var id = parseInt($('#backgrounds').attr('data-num')) +1,
            max = parseInt($('#backgrounds').attr('data-max')),
            num = (id > max) ? 0 : id,
            bg = $('#backgrounds div[data-num='+ num +']');
        $('#backgrounds').attr('data-num', num);
        $('.column.on .ccolumn').css('background-image', 'url('+ bg.attr('data-src') +')');
        $('.column.on input.bg').val(bg.attr('data-id'));
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
        addPersona($(this).parent().parent().parent());
    });

    $('#removeBox').on('click', function(e){
        $('.column.on').remove();
        orderBox();
    });

    $('#addBox').on('click', function (e) {
        var page = $(this).parent().parent().parent().parent().parent(),
            nbBox = page.find('.column').length;

        var nameForm = page.attr('data-id').replace(/comic_pages_/g, 'comic[pages][') + ']';
        var formBox = page.attr('data-box-form')
            .replace(/comic_pages_0/g, page.attr('data-id'))
            .replace(/__name__/g, nbBox);
        formBox = formBox.replace(/comic\[pages\]\[0\]/g, nameForm);
        $('.column.on').after(formBox);
        selectBox($('[data-id="'+ page.attr('data-id') +'_boxes_'+ nbBox +'"]'));
        addPersona($('[data-id="'+ page.attr('data-id') +'_boxes_'+ nbBox +'"]'));
        orderBox();
        e.stopPropagation();
    });

    $('#resizeH').on('click', function () {
        resizeBox($(this).parent().parent(), size)
    });

    $('#resizeV').on('click', function () {
        resizeBox($(this).parent().parent(), height)
    });

    $('#changeColumns').on('click', function () {
        var line = $(this).parent().parent().parent(),
            next = line.next('.columns');

        if (nbBox != '1') {
            if (!next.length) {
                line.after('<div class="columns" data-nbBox="1"></div>');
            }
            var lastBox = line.find('.column:last-child');
            next = line.next('.columns');
            next.append(lastBox);
            line.children('.column').each(function () {
                resizeBox($(this), size, 'half');
            });
            line.attr('data-nbBox', nbBox-1);
        }
    })

    $('textarea').on('keyup', function () {
        $(this).css('height', 'auto');
        $(this).css('height', this.scrollHeight + 'px');
    });

    $('textarea').on('keydown', function () {
        var nbCar = $(this).val().length,
            perc = nbCar / nbCarMax;

        if (nbCar > 100) {
            $('#nbCar').html(nbCarMax - nbCar);
        }
        if (nbCar > 95) {
            $(this).removeClass('fs-14 fs-16 fs-18').addClass('fs-12').attr('data-height-row', 14);
            $('#circle').circleProgress({'fill': {gradient: ["#e8793a", "#ff5900"]}});
        } else if (nbCar > 65) {
            $('#nbCar').html('');
            $(this).removeClass('fs-12 fs-16 fs-18').addClass('fs-14').attr('data-height-row', 16);
            $('#circle').circleProgress({'fill': {gradient: ["#76a094", "#e8793a"]}});
        } else if (nbCar > 35) {
            $(this).removeClass('fs-14 fs-12 fs-18').addClass('fs-16').attr('data-height-row', 18);
            $('#circle').circleProgress({fill: {gradient: ["#9cd3c6", "#76a094"]}});
        } else {
            $(this).removeClass('fs-12 fs-14 fs-16').addClass('fs-18').attr('data-height-row', 23);
        }

        $('#circle').circleProgress('value', perc);
    });

    function addPersona(box) {
        var nbBubble = box.find('div blockquote').length;

        var nameForm = box.attr('data-id').replace(/comic_pages_/g, 'comic[pages][').replace(/_boxes_/g, '][boxes][') + ']';
        var formBubble = box.attr('data-bubble-form')
            .replace(/comic_pages_0_boxes_0/g, box.attr('data-id'))
            .replace(/__name__/g, nbBubble);
        formBubble = formBubble.replace(/comic\[pages\]\[0\]\[boxes\]\[0\]/g, nameForm);
        box.children('div:first').append(formBubble);

        //$('[data-id="'+ box.attr('data-id') +'_boxes_'+ nbBox +'"]')
    }

    function selectBox(box) {
        box.css('border-color', 'green');
        $('.column').removeClass('on');
        box.addClass('on');
        var element = $('#actions').detach();
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
        var element = $('#actions').detach();
        bubble.parent().parent().append(element);
        $('#actionsBubble').show();
        bubble.children('textarea').focus();
        if (bubble.hasClass('bubble')) {
            $('#addBubble').addClass('is-hidden');
            $('#removeBubble').removeClass('is-hidden');
        } else {
            $('#addBubble').removeClass('is-hidden');
            $('#removeBubble').addClass('is-hidden');
        }
    }

    function resizeBox(box, size, newValue) {
        var ok = 0,
            formId = box.attr('data-id');

        if (!newValue) {
            size.values.forEach(value => {
                if (ok == 1) {
                    newValue = value;
                    ok = 'end';
                }
                if (box.hasClass(size.prefix + value) && ok == 0) {
                    ok = 1;
                }
            });
            if (ok != 'end') {
                newValue = size.values[0];
            }
        }

        size.values.forEach(value => {
            box.removeClass(size.prefix + value);
        });

        box.addClass(size.prefix + newValue);
        $('#' + formId + '_' + size.key).val(newValue);
    }

    function orderBox() {
        var i = 0;
        $('.orderBox').each(function () {
            $(this).val(i);
            i++;
        });
    }
});
