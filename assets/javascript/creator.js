jQuery(document).ready(function () {
    orderBox();

    if ($('#actionsPersona').length) {
        var element = $('#actionsPersona').detach().appendTo('#actions');
        bulmaCarousel.attach('#slidePersona', {
            slidesToScroll: 5,
            slidesToShow: 7,
            infinite: true,
            pagination: false
        });
    }

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

    $(document).on('click', '#clone', function (e) {
        var box = $(this).parent().parent();
        cloneBox(box);
        e.stopPropagation();
    });

    $(document).on('dblclick', '.column blockquote', function (event) {
        $(this).remove();
        event.stopPropagation();
    });
    $(document).on('click', '.removeBubble', function (event) {
        $(this).parent().parent().remove();
        event.stopPropagation();
    })

    $(document).on('click', '.column blockquote', function (event) {
        selectBox($(this).parent().parent(), false);
        selectBubble($(this));
        event.stopPropagation();
    });

    $(document).on('click', '.ccolumn', function () {
        selectBox($(this).parent(), true);
    });

    $('.persona').on('click', function (e) {
        $('blockquote.on img').attr('src', $(this).attr('src'));
        $('#' + $('blockquote.on').attr('id') + '_persona').val($(this).attr('data-id'));
        e.stopPropagation();
    });

    $(document).on('click', '#styleBubble', function () {
        var style = '';
        if ($('.bubble.on').hasClass('think')) {
            $('.bubble.on').removeClass('think').addClass('yell');
            style = 'yell';
        } else if ($('.bubble.on').hasClass('yell')) {
            $('.bubble.on').removeClass('yell bubble');
            $('blockquote.on').attr('data-text', $('blockquote.on textarea').val());
            $('blockquote.on textarea').val('');
            style = '';
        } else if (!$('blockquote.on').hasClass('bubble')) {
            $('blockquote.on').addClass('bubble');
            $('blockquote.on textarea').val($('blockquote.on').attr('data-text'));
            $('blockquote.on').attr('data-text', '');
            style = '';
        } else {
            $('.bubble.on').addClass('think');
            style = 'think';
        }
        $('#' + $('.bubble.on').attr('id') + '_style').val(style);
    });

    $(document).on('click', '#changeBackground', function () {
        var id = parseInt($('#backgrounds').attr('data-num')) + 1,
            max = parseInt($('#backgrounds').attr('data-max')),
            num = (id > max) ? 0 : id,
            bg = $('#backgrounds div[data-num=' + num + ']');
        $('#backgrounds').attr('data-num', num);
        $('.column.on .ccolumn').css('background-image', 'url(' + bg.attr('data-src') + ')');
        $('.column.on input.bg').val(bg.attr('data-id'));
    });

    $(document).on('click', '#addPersona', function () {
        addPersona($(this).parent().parent().parent());
    });

    $(document).on('click', '#removeBox', function (e) {
        var removeBpx = $('.column.on'),
            newSelectedBox = $('.column.on').next().length ? $('.column.on').next() : $('.column.on').prev();
        selectBox(newSelectedBox);
        removeBpx.remove();
        orderBox();
    });

    $(document).on('click', '#addBox', function (e) {
        var page = $(this).parent().parent().parent().parent().parent(),
            numPage = page.attr('id').replace(/comic_pages_/g, ''),
            numBox = page.find('.column').length,
            formBox = $('#form_box_proto').html(),
            formBox = formBox.replace(/__NUMPAGE__/g, numPage).replace(/__NUMBOX__/g, numBox);

        $('.column.on').after(formBox);
        cloneBox($('#comic_pages_' + numPage + '_boxes_' + numBox));
        orderBox();
        e.stopPropagation();
    });

    $(document).on('keyup', 'textarea', function () {
        $(this).css('height', 'auto');
        $(this).css('height', this.scrollHeight + 'px');
    });

    $(document).on('keydown', 'textarea', function () {
        var nbCar = $(this).val().length,
            perc = nbCar / nbCarMax;

        if (nbCar > 100) {
            $('#nbCar').html(nbCarMax - nbCar);
        }
        if (nbCar > 95) {
            $(this).removeClass('fs-14 fs-16 fs-18').addClass('fs-12').attr('data-height-row', 14);
            $('#circle').circleProgress({'fill': {gradient: ["#e8793a", "#ff5900"]}});
        } else if (nbCar > 55) {
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
        var numBubble = box.find('div blockquote').length,
            nums = box.attr('id').match(/[\d+]/g),
            numBox = nums[1],
            numPage = nums[0];

        var formBubble = $('#form_bubble_proto').html()
        formBubble = formBubble.replace(/__NUMBUBBLE__/g, numBubble)
            .replace(/__NUMBOX__/g, numBox)
            .replace(/__NUMPAGE__/g, numPage);

        box.children('div:first').append(formBubble);
    }

    function selectBox(box, selectFirstBubble = true) {
        box.find('#clone').remove();

        $('.column').removeClass('on');

        box.addClass('on');

        $('#actions').detach().appendTo(box);

        if(selectFirstBubble) {
            selectBubble(box.find('blockquote').first());
        }
    }

    function selectBubble(bubble) {
        $('.bubble').removeClass('on');
        bubble.addClass('on');

        bubble.children('textarea').focus();
        if (bubble.hasClass('bubble')) {
            $('#addBubble').addClass('is-hidden');
            $('#removeBubble').removeClass('is-hidden');
        } else {
            $('#addBubble').removeClass('is-hidden');
            $('#removeBubble').addClass('is-hidden');
        }

        $('#circle').circleProgress({
            value: 0,
            size: 30,
            thickness: 3,
            animation: false,
            fill: {
                gradient: ["#9cd3c6", "#76a094"]
            }
        });
    }

    function resizeBox(box, size, newValue) {
        var ok = 0,
            formId = box.attr('id');

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

    function cloneBox(box) {
        $('#actions').detach().appendTo('#actionsHidden');

        var previousBox = box.prev().html(),
            previousBox = previousBox.replace(/comic_pages_(\d+)_boxes_(\d+)/g, box.attr('id')),
            nums = box.attr('id').match(/\d+/g),
            previousBox = previousBox.replace(/pages\]\[(\d+)\]\[boxes\]\[(\d+)\]/g, 'pages][' + nums[0] + '][boxes][' + nums[1] + ']');
        box.html(previousBox);
        selectBox(box, true);

    }
});
