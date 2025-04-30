<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

class Tracker {

    public static $meta_key_total = '_ccopv_total';
    public static $meta_key_today_prefix = '_ccopv_today_';

    public function __construct() {
        add_action('wp_head', [$this, 'track_views']);
        add_action('wp_ajax_nopriv_ccopv_add_view', [$this, 'track_views_ajax']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function track_views() {
        if (!is_singular('post')) return;


        $post_id = get_the_ID();
        if (!$post_id) return;

        //error_log('Track views triggered for post ID: ' . $post_id);

        // 检查是否开启浏览量统计
        if ( get_option('concise_counter_of_post_views_enabled', '1') !== '1' ) {
            return;
        }

        // 总浏览数
        $total = (int) get_post_meta($post_id, self::$meta_key_total, true);
        update_post_meta($post_id, self::$meta_key_total, $total + 1);
        //error_log('Total views: ' . $total);

        // 今日浏览数
        $today_key = self::$meta_key_today_prefix . gmdate('Ymd');
        $today = (int) get_post_meta($post_id, $today_key, true);
        update_post_meta($post_id, $today_key, $today + 1);
        //error_log('Updating total views for post ID: ' . $post_id . ' to: ' . ($total + 1));
    }

    public function track_views_ajax() {
        check_ajax_referer('track_views_nonce', 'nonce'); // 验证 nonce，'nonce' 是前端提交的字段名

        $post_id = absint($_POST['post_id'] ?? 0);
        if (!$post_id) wp_send_json_error();

        $this->track_views(); // 同样处理逻辑
        wp_send_json_success(['ok' => true]);
    }

    public function enqueue_scripts() {
        if (!is_singular('post')) return;

        wp_enqueue_script('ccopv-tracker', plugin_dir_url(__DIR__) . 'assets/js/tracker.js', ['jquery'], CONCISE_COUNTER_VERSION , true);
        wp_localize_script('ccopv-tracker', 'ccopv_data', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
            'nonce'    => wp_create_nonce('track_views_nonce'),
        ]);
    }
}
