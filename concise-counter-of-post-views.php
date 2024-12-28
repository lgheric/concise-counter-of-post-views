<?php
namespace ConciseCounterOfPostViews;
/**
 * Plugin Name: Concise Counter of Post Views
 * Description: This plug-in counts the number of times the post has been visited and displays it at the bottom of the post.
 * At the same time, you can also see this value in the post list in the admin panel.
 * Version: 2.0
 * Author: Robert South
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    die("Can't load this file directly");
}

class ConciseCounterOfPostViews {

    private static $meta_key = '_ccopv_view_count';

    public function __construct() {
        add_action('wp_head', [$this, 'trackPostViews']);
        add_filter('the_content', [$this, 'displayPostViews']);
        add_filter('manage_post_posts_columns', [$this, 'addViewsColumn']);
        add_action('manage_post_posts_custom_column', [$this, 'displayViewsColumn'], 10, 2);
        add_filter('manage_edit-post_sortable_columns', [$this, 'makeViewsColumnSortable']);
        add_action('pre_get_posts', [$this, 'sortViewsColumn']);
    }

    public function trackPostViews() {
        if (is_singular('post')) {
            $post_id = get_the_ID();
            if (!$post_id) {
                return;
            }

            $views = get_post_meta($post_id, self::$meta_key, true);
            $views = empty($views) ? 0 : (int)$views;
            $views++;
            update_post_meta($post_id, self::$meta_key, $views);
        }
    }

    public function displayPostViews($content) {
        if (is_single() && in_the_loop() && is_main_query()) {
            $views = get_post_meta(get_the_ID(), self::$meta_key, true);
            $views = empty($views) ? 0 : (int)$views;
            $content .= '<p>Views: ' . esc_html($views) . '</p>';
        }
        return $content;
    }

    public function addViewsColumn($columns) {
        $columns['ccopv_view_count'] = 'CCOPV_Views';
        return $columns;
    }

    public function displayViewsColumn($column, $post_id) {
        if ($column === 'ccopv_view_count') {
            $views = get_post_meta($post_id, self::$meta_key, true);
            echo esc_html($views ? $views : 0);
        }
    }

    public function makeViewsColumnSortable($columns) {
        $columns['ccopv_view_count'] = 'ccopv_view_count';
        return $columns;
    }

    public function sortViewsColumn($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');
        if ('ccopv_view_count' === $orderby) {
            $query->set('meta_key', self::$meta_key);
            $query->set('orderby', 'meta_value_num');
        }
    }
}

// Initialize the plugin
new ConciseCounterOfPostViews();
