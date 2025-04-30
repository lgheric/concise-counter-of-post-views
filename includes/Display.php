<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

use ConciseCounterOfPostViewsPlugin\Tracker;

class Display {

    public function __construct() {
        // 添加短代码来显示浏览量
        add_shortcode('concise_post_views', [$this, 'display_post_views']);
    }

    // 显示文章浏览量
    public function display_post_views($atts) {
        global $post;
        $post_id = $post->ID;
        $views = get_post_meta($post_id, Tracker::$meta_key_total, true);

        return $views ? esc_html($views) : '0'; // 如果没有浏览量，则显示 0
    }
}
