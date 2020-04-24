jQuery(document).ready(function () {
    orderBox();

    if ($('#actionsPersona').length) {
        var element = $('#actionsPersona').detach();
        $('.column.on').append(element);
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
        var box = $(this).parent().parent(),
            previousBox = box.prev().html(),
            previousBox = previousBox.replace(/comic_pages_(\d+)_boxes_(\d+)/g, box.attr('id')),
            nums = box.attr('id').match(/[\d+]/g),
            previousBox = previousBox.replace(/pages\]\[(\d+)\]\[boxes\]\[(\d+)\]/g, 'pages][' + nums[0] + '][boxes][' + nums[1] + ']');
        $(this).hide();
        var element = $('#actions').detach();
        $('#actionsHidden').append(element);
        box.html(previousBox);
        selectBox(box);
        e.stopPropagation();
    });

    $(document).on('click', '.column blockquote', function (event) {
        selectBox($(this).parent().parent());
        selectBubble($(this));
        event.stopPropagation();
    });

    $(document).on('focus', '.column textarea', function (event) {
        $(this).css('height', this.scrollHeight + 'px');
        if (!$(this).parent().hasClass('on')) {
            selectBox($(this).parent().parent().parent());
            selectBubble($(this).parent());
        }
        event.stopPropagation();
    });

    $(document).on('click', '.ccolumn', function () {
        selectBox($(this).parent());
    });

    $('.persona').on('click', function (e) {
        $('blockquote.on img').attr('src', $(this).attr('src'));
        $('#' + $('blockquote.on').attr('id') + '_persona').val($(this).attr('data-id'));
        e.stopPropagation();
    });

    $('#styleBubble').on('click', function () {
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

    $('#changeBackground').on('click', function () {
        var id = parseInt($('#backgrounds').attr('data-num')) + 1,
            max = parseInt($('#backgrounds').attr('data-max')),
            num = (id > max) ? 0 : id,
            bg = $('#backgrounds div[data-num=' + num + ']');
        $('#backgrounds').attr('data-num', num);
        $('.column.on .ccolumn').css('background-image', 'url(' + bg.attr('data-src') + ')');
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

    $('#removeBox').on('click', function (e) {
        var removeBpx = $('.column.on'),
            newSelectedBox = $('.column.on').next().length ? $('.column.on').next() : $('.column.on').prev();
        selectBox(newSelectedBox);
        removeBpx.remove();
        orderBox();
    });

    $('#addBox').on('click', function (e) {
        var page = $(this).parent().parent().parent().parent().parent(),
            numPage = page.attr('id').replace(/comic_pages_/g, ''),
            numBox = page.find('.column').length,
            formBox = $('#form_box_proto').html(),
            formBox = formBox.replace(/__NUMPAGE__/g, numPage).replace(/__NUMBOX__/g, numBox);

        $('.column.on').after(formBox);
        selectBox($('#comic_pages_' + numPage + '_boxes_' + numBox));
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
            line.attr('data-nbBox', nbBox - 1);
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
        //$('#actionsPersona').hide();
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

        var element = $('#actionsPersona').detach();
        bubble.parent().parent().append(element);
        $('#actionsPersona').show();

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
});
