<?php
/**
 * Plugin Name: Concise Counter of Post Views
 * Description: Concise Counter of Post Views is a lightweight and efficient plugin that tracks and displays post view counts.
 * It now includes the following features:
 * ✅ Total post views tracking
 * ✅ Today's post views tracking
 * ✅ Ajax-based view count increment (compatible with caching plugins)
 * ✅ Initial framework of the settings page in the admin panel
 * ✅ Shortcode [concise_views] to display the view count anywhere
 * Ideal for bloggers and content creators who want simple and accurate view tracking without bloat.
 * Version: 3.1
 * Author: Robert South
 * Author URI: https://robertwp.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: concise-counter-of-post-views
 * Domain Path: /languages
 */
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

require_once __DIR__ . '/includes/Tracker.php';
require_once __DIR__ . '/includes/Display.php';
require_once __DIR__ . '/includes/AdminColumns.php';
require_once __DIR__ . '/includes/SettingsPage.php';

define('CONCISE_COUNTER_VERSION', '3.0.0');


class ConciseCounterOfPostViews {

    public function __construct() {
        // Filters
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_action_links']);
        add_filter('plugin_row_meta', [$this, 'add_meta_links'], 10, 2);
        add_action('admin_notices', [$this, 'concise_counter_admin_notice']);

        new Tracker();
        new Display();
        new AdminColumns();
        new SettingsPage();
    }

    public function concise_counter_admin_notice() {
        echo '<div class="notice notice-warning"><p><strong>Concise Counter of Post Views</strong> has been replaced by <a href="https://wordpress.org/plugins/rw-postviewstats-lite/" target="_blank">RW PostViewStats Lite</a>. Please install the new version for continued support.</p></div>';
    }

    public function add_action_links($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=concise_counter_settings') . '">' . __('Settings', 'concise-counter-of-post-views') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    function add_meta_links($links, $file) {
        if ($file === plugin_basename(__FILE__)) {
            $links[] = '<a href="http://ko-fi.com/robertsouth" target="_blank">❤</a>';
        }
        return $links;
    }

}

new ConciseCounterOfPostViews();
