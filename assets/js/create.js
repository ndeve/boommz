import absCeil from "../../public/bundles/sonatacore/vendor/moment/src/lib/utils/abs-ceil";

jQuery(document).ready(function () {
    var size = {
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
        selectbox($(this).parent().parent());
        selectBubble($(this));
        event.stopPropagation();
    });

    $(document).on('focus', '.column textarea', function () {
        if (!$(this).parent().hasClass('on')) {
            selectbox($(this).parent().parent().parent());
            selectBubble($(this).parent());
        }
    });

    $(document).on('click', '.column', function () {
        selectbox($(this));
    });

    $('html').on('keydown', function (e) {
        if (39 === e.keyCode && $('.column.on').next().length) {
            selectbox($('.column.on').next());
        } else if (37 === e.keyCode && $('.column.on').prev().length) {
            selectbox($('.column.on').prev());
        }
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

});

function selectbox(box) {
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

$('textarea').on('keydown', function () {
    var perc = absCeil(($(this).val().length/150)*100);
    console.log(perc);
    $('#circle').circleProgress({
        value: perc,
        size: 30,
        thickness: 3,
        fill: {
            gradient: ["#76a094", "#9cd3c6"]
        }
    });
})