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
 * Version: 3.0
 * Author: Robert South
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
        add_action( 'plugins_loaded', [$this, 'load_textdomain']);

        new Tracker();
        new Display();
        new AdminColumns();
        new SettingsPage();
    }

    function load_textdomain() {
        load_plugin_textdomain( 'concise-counter-of-post-views', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
}

new ConciseCounterOfPostViews();
