jQuery(document).ready(function($) {
    // 点击时触发浏览量增加的Ajax请求
    $(document).on('click', '.track-views', function() {
        var post_id = $(this).data('post-id');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'increment_post_views',
                post_id: post_id,
                nonce: ccopv_data.nonce // 通过 wp_localize_script() 输出
            },
            success: function(response) {
                alert('浏览量已更新');
            }
        });
    });
});
