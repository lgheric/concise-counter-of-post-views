<?php
/**
* Plugin Name: Concise Post Views Counter
* Description: Show post views at the bottom of the post and in the post list of the admin panel.
* Version: 1.0.0
* Author: Robert South
* License: GPLv3 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
*
*/

if ( !defined( 'ABSPATH' ) ) {
    die( esc_html(__( "Can't load this file directly", 'concise-post-views-counter' ) ));
}

// Increment or update the view counter
function cpvc_track_post_views($post_id) {
    if (!is_single() || empty($post_id)) {
        return;
    }

    $views = get_post_meta($post_id, '_ccpvc_view_count', true);
    $views = empty($views) ? 0 : $views;
    $views++;
    update_post_meta($post_id, '_cpvc_view_count', $views);
}
add_action('wp_head', function () {
    if (is_singular('post')) {
        cpvc_track_post_views(get_the_ID());
    }
});

// Display the view count at the bottom of the post
function cpvc_display_post_views($content) {
    if (is_single() && in_the_loop() && is_main_query()) {
        $views = get_post_meta(get_the_ID(), '_cpvc_view_count', true);
        $views = empty($views) ? 0 : $views;
        $content .= '<p>Views：' . esc_html($views) . '</p>';
    }
    return $content;
}
add_filter('the_content', 'cpvc_display_post_views');

// Add a view count column in the admin post list
function cpvc_add_views_column($columns) {
    $columns['cpvc_view_count'] = 'CPVC_Views';
    return $columns;
}
add_filter('manage_post_posts_columns', 'cpvc_add_views_column');

// Display view count data in the column
function cpvc_display_views_column($column, $post_id) {
    if ($column === 'cpvc_view_count') {
        $views = get_post_meta($post_id, '_cpvc_view_count', true);
        echo esc_html($views ? $views : 0);
    }
}
add_action('manage_post_posts_custom_column', 'cpvc_display_views_column', 10, 2);

// Make the column sortable
function cpvc_make_views_column_sortable($columns) {
    $columns['cpvc_view_count'] = 'cpvc_view_count';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'cpvc_make_views_column_sortable');

// Handle sorting logic
function cpvc_sort_views_column($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ('cpvc_view_count' === $orderby) {
        $query->set('meta_key', '_cpvc_view_count');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'cpvc_sort_views_column');