jQuery(document).ready(function () {
    orderBox();

    $('.actionsSlider').detach().appendTo('#actions');

    if ($('#actionsPersona').length) {
        bulmaCarousel.attach('#sliderPersona', {
            slidesToScroll: 6,
            slidesToShow: 7,
            infinite: true,
            pagination: false,
            breakpoints: [{ changePoint: 480, slidesToShow: 5, slidesToScroll: 4 }, { changePoint: 768, slidesToShow: 8, slidesToScroll: 7 } ]
        });
    }

    if ($('#actionsBg').length) {
        bulmaCarousel.attach('#sliderBg', {
            slidesToScroll: 2,
            slidesToShow: 3,
            infinite: true,
            pagination: false,
            breakpoints: [{ changePoint: 480, slidesToShow: 3, slidesToScroll: 2 }, { changePoint: 768, slidesToShow: 4, slidesToScroll: 3 } ]
        });
        $('#actionsBg').addClass('is-hidden');

        bulmaCarousel.attach('#sliderColor', {
            slidesToScroll: 5,
            slidesToShow: 8,
            infinite: true,
            pagination: false,
            breakpoints: [{ changePoint: 480, slidesToShow: 6, slidesToScroll: 4 }, { changePoint: 768, slidesToShow: 4, slidesToScroll: 3 } ]
        });
        $('#actionsColor').addClass('is-hidden');
    }

    if ($('.columns').length) {
        $('.columns').each(function () {
            $(this).attr('data-nbBox', $(this).children('.column.writable').length);
        });
    }

    var nbCarMax = 130,
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

    $(document).on('click', '#removeBubble', function (event) {
        $('blockquote.on').remove();
        event.stopPropagation();
    })

    $(document).on('click', '.column.writable blockquote', function (event) {
        selectBox($(this).parent().parent(), false);
        selectBubble($(this));
        event.stopPropagation();
    });

    $(document).on('click', '.writable .ccolumn', function () {
        selectBox($(this).parent(), true);
    });

    $('.persona').on('click', function (e) {
        $('blockquote.on img').attr('src', $(this).attr('src'));
        $('#' + $('blockquote.on').attr('id') + '_persona').val($(this).attr('data-id'));
        e.stopPropagation();
    });

    $('.bg').on('click', function (e) {
        $('.column.on .ccolumn').css('background-image', 'url(' + $(this).attr('data-src') + ')');
        $('.column.on input.bg').val($(this).attr('data-id'));
        e.stopPropagation();
    });

    $('.color').on('click', function (e) {
        $('.column.on .ccolumn').attr('style', $(this).attr('style'));
        $('.column.on input.bg').val($(this).attr('data-id'));
        e.stopPropagation();
    });

    $(document).on('click', '#levelBubble', function () {
        var lv = parseInt($('.bubble.on input.lv').val()),
            lv = (lv+1 >= 4) ? 0 : lv+1;

        $('.bubble.on').removeClass('lv-0 lv-1 lv-2 lv-3').addClass('lv-'+ lv);

        $('.bubble.on input.lv').val(lv);
    });

    $(document).on('click', '#styleBubble', function () {
        var style = '';
        if ($('blockquote.on').hasClass('think')) {
            $('blockquote.on').removeClass('think').addClass('yell');
            style = 'yell';
        } else if ($('blockquote.on').hasClass('yell')) {
            $('blockquote.on').removeClass('yell bubble');
            $('blockquote.on').attr('data-text', $('blockquote.on textarea').val());
            $('blockquote.on textarea').val('');
            style = '';
        } else if (!$('blockquote.on').hasClass('bubble')) {
            $('blockquote.on').addClass('bubble');
            $('blockquote.on textarea').val($('blockquote.on').attr('data-text'));
            $('blockquote.on').attr('data-text', '');
            style = '';
        } else {
            $('blockquote.on').addClass('think');
            style = 'think';
        }

        $('#' + $('blockquote.on').attr('id') + '_style').val(style);
    });

    $(document).on('click', '#displayBackground', function () {
        $('.actionsSlider').addClass('is-hidden');
        $('#actionsBg').removeClass('is-hidden');
        $(this).addClass('is-hidden');
        $('#displayColor').removeClass('is-hidden');
    });

    $(document).on('click', '#displayColor', function () {
        $('.actionsSlider').addClass('is-hidden');
        $('#actionsColor').removeClass('is-hidden');
        $(this).addClass('is-hidden');
        $('#displayBackground').removeClass('is-hidden');
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
            numBox = page.find('.column.writable').length,
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
        var max = 0;

        if ($(this).parent().parent().attr('data-order') == '') {
            $('.column.on blockquote.bubble').each(function () {
                if (max < $(this).attr('data-order')) {
                    max = $(this).attr('data-order');
                }
            });
            $(this).parent().parent().attr('data-order', parseInt(max) + 1);
        }

        //if ($(this).parent())
        var nbCar = $(this).val().length,
            classSize = '';

        if (6 <= nbCar && nbCar < 10) {
            classSize = 'w-1';
        }
        else if (10 <= nbCar && nbCar < 20) {
            classSize = 'w-2';
        }
        else if (20 <= nbCar && nbCar < 30) {
            classSize = 'w-3';
        }
        else if (30 <= nbCar && nbCar < 50) {
            classSize = 'w-4';
        }
        else if (50 <= nbCar) {
            classSize = 'w-5';
        }

        $('.bubble.on').removeClass('w-1 w-2 w-3 w-4').addClass(classSize);

    });

    function orderBubble() {
        var nb = $('.column.on blockquote').length,
            num = 1;

        $('.column.on blockquote').each(function(){

            $(this).removeClass('p-1-1 p-1-2 p-2-2 p-1-3 p-2-3 p-3-3 p-1-4 p-2-4 p-3-4 p-4-4 p-1-5 p-2-5 p-3-5 p-4-5 p-5-5').addClass('p-'+ (num++) +'-'+ nb);
        });
    }

    function addPersona(box) {
        var numBubble = box.find('div blockquote').length,
            nums = box.attr('id').match(/\d+/g),
            numBox = nums[1],
            numPage = nums[0],
            formBubble = $('#form_bubble_proto').html(),
            formBubble = formBubble.replace(/__NUMBUBBLE__/g, numBubble)
                .replace(/__NUMBOX__/g, numBox)
                .replace(/__NUMPAGE__/g, numPage);

        box.children('div:first').append(formBubble);
        orderBubble();
        selectBubble($('#comic_pages_'+ numPage +'_boxes_'+ numBox +'_bubbles_'+ numBubble));
    }

    function selectBox(box, selectFirstBubble = true) {
        $('.actionsSlider').addClass('is-hidden');
        $('#actionsPersona').removeClass('is-hidden');

        box.find('#clone').remove();
        location.href = '#' + box.attr('id');
        $('.column').removeClass('on');

        box.addClass('on');

        $('#actions').detach().appendTo(box);

        if (selectFirstBubble) {
            selectBubble(box.find('blockquote').first());
        }
    }

    function selectBubble(bubble) {
        $('blockquote').removeClass('on');
        bubble.addClass('on');

        bubble.find('textarea').focus();
        if (bubble.hasClass('bubble')) {
            $('#addBubble').addClass('is-hidden');
            $('#removeBubble').removeClass('is-hidden');
        } else {
            $('#addBubble').removeClass('is-hidden');
            $('#removeBubble').addClass('is-hidden');
        }
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
