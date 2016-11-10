jQuery(document).ready(function($){
    $("#load_more_posts").on('click',function(){
        var btn = $(this);
        var page = btn.data('page');

        var data = {
            action : 'load_more_posts',
            nonce : custom_js.nonce,
            page: page
        };

        $.ajax({
            url: custom_js.url,
            type: 'post',
            data: data,
            dataType: 'html',
            success: function(result) {
                console.log(result);
                if(result=='lpage') {
                    btn.remove();
                }
                else {
                    btn.before(result);
                    btn.data('page', (page * 1.0 + 1));
                }
            }
        });
    });
});