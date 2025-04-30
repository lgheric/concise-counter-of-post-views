<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

use ConciseCounterOfPostViewsPlugin\Tracker;

class AdminColumns {

    public function __construct() {
        // Add a page view column to the article list
        add_filter('manage_posts_columns', [$this, 'add_views_column']);
        add_action('manage_posts_custom_column', [$this, 'display_views_column'], 10, 2);
    }

    // Add the "Views" column to the article list
    public function add_views_column($columns) {
        $columns['post_views'] =  __('ccopv_views', 'concise-counter-of-post-views');
        return $columns;
    }

    // Display page view data
    public function display_views_column($column, $post_id) {
        if ('post_views' === $column) {
            $views = get_post_meta($post_id, Tracker::$meta_key_total, true);
            echo esc_html($views) ? esc_html($views) : '0';
        }
    }
}
