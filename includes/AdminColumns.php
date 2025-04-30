<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

use ConciseCounterOfPostViewsPlugin\Tracker;

class AdminColumns {

    public function __construct() {
        // 在文章列表中添加浏览量列
        add_filter('manage_posts_columns', [$this, 'add_views_column']);
        add_action('manage_posts_custom_column', [$this, 'display_views_column'], 10, 2);
    }

    // 添加“浏览量”列到文章列表
    public function add_views_column($columns) {
        $columns['post_views'] = '浏览量';
        return $columns;
    }

    // 显示浏览量数据
    public function display_views_column($column, $post_id) {
        if ('post_views' === $column) {
            $views = get_post_meta($post_id, Tracker::$meta_key_total, true);
            echo esc_html($views) ? esc_html($views) : '0';
        }
    }
}
