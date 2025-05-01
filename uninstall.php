<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

if ( is_multisite() ) {
    $site_ids = function_exists( 'get_sites' )
        ? get_sites( array( 'fields' => 'ids' ) )
        : wp_list_pluck( wp_get_sites(), 'blog_id' );

    foreach ( $site_ids as $blog_id ) {
        switch_to_blog( $blog_id );
        ccopv_delete_all_options();
        restore_current_blog();
    }
} else {
    ccopv_delete_all_options();
}

function ccopv_delete_all_options() {
    $option_names = array(
        'concise_counter_of_post_views_enabled'
    );

    foreach ( $option_names as $option_name ) {
        delete_option( sanitize_key( $option_name ) );
    }
}

require_once plugin_dir_path(__FILE__) . 'includes/Tracker.php';

global $wpdb;

if (class_exists('\ConciseCounterOfPostViewsPlugin\Tracker')) {
    $meta_key_total = \ConciseCounterOfPostViewsPlugin\Tracker::$meta_key_total;
    $query = $wpdb->prepare(
        "DELETE FROM $wpdb->postmeta WHERE meta_key = %s",
        $meta_key_total
    );
    $wpdb->query($query);
}

