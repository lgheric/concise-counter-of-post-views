<?php
/**
 * Plugin Name: Concise Counter of Post Views
 * Description: This plug-in counts the number of times the post has been visited and displays it at the bottom of the post. At the same time, you can also see this value in the post list in the admin panel.
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
