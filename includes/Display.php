<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

use ConciseCounterOfPostViewsPlugin\Tracker;

class Display {

    public function __construct() {
        // Add a short code to show the number of views
        add_shortcode('concise_post_views', [$this, 'display_post_views']);
    }

    // Show article views
    public function display_post_views($atts) {
        global $post;
        $post_id = $post->ID;
        $views = get_post_meta($post_id, Tracker::$meta_key_total, true);

        return $views ? esc_html($views) : '0'; // If there are no views, display 0.
    }
}
