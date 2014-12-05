require.config({
    baseUrl: 'assets/js',
    /* 不用缓冲,在js的链接后面增加?_=xxxx*/
    urlArgs: '_t=' + TIMESTAMP
});

require(['jquery'], function($) {

    $('body').on('click', 'a[href^="ajax:"]', function(e) {
        var $link = $(this);
        if ($link.data('delegated')) return false;

        e.preventDefault();

        $link.trigger('ajax-before');

        $.ajax({
            type: "GET",
            url: $link.attr('href').substring(5),
            success: function(html) {
                $link.trigger('ajax-success', html);
                $('body').append(html);
            },
            complete: function() {
                $link.trigger('ajax-complete');
            }
        });

        return false;
    });

    $('body').on('submit', 'form[action^="ajax:"]', function(e) {
        if ($(this).data('delegated')) return false;

        e.preventDefault();

        var $form = $(this);

        $form.trigger('ajax-before');

        $.ajax({
            type: $form.attr('method') || "POST",
            url: $form.attr('action').substring(5),
            data: $form.serialize(),
            success: function(html) {
                $form.trigger('ajax-success', html);
                $('body').append(html);
            },
            complete: function() {
                $form.trigger('ajax-complete');
            }
        });

        return false;
    });

    // cleanup script with data-ajax
    setInterval(function(){
        $('script[data-ajax]').remove();
    }, 1000);


});
