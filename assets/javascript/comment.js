$(document).ready(function () {

    if ($('#comments').length) {
        var url = $('#comments').attr('data-url');
        $.get( url )
            .done(function(data) {
                $('#comments').prepend(data);
            });

        $(document).on('keyup', 'textarea', function () {
            $(this).css('height', 'auto');
            $(this).css('height', this.scrollHeight + 'px');
        });
    }

    $(document).on('submit', '#comments form', function (e) {
        e.preventDefault();

        var form = $(this),
            url = $('#comments').attr('data-url');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(data)
            {
                if (data.success) {
                    $('#comment form textarea').val('');
                    $('#comments form').after(data.html);
                }
            }
        });

        return false;
    });

    $(document).on('click', '#comments .delete', function (e) {
        e.preventDefault();
        var url = $(this).attr('href'),
            comment = $(this).parent().parent();

        $.get( url )
            .done(function() {
                comment.remove();
            });
    });

});