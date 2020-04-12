jQuery(document).ready(function () {
    $('textarea').autogrow();

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

    $('.column').on('click', function () {
        selectbox($(this));
    });

    $('html').on('keydown', function (e) {
        if (39 === e.keyCode && $('.column.on').next().length) {
            selectbox($('.column.on').next());
        } else if (37 === e.keyCode && $('.column.on').prev().length) {
            selectbox($('.column.on').prev());
        }
    });

    $('#addBubble').on('click', function () {
        var formBubble = $('#actionsBox').attr('data-bubble');
        $(this).parent().parent().children('div').append(formBubble);
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
