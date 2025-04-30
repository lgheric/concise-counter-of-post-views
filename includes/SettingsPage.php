<?php
namespace ConciseCounterOfPostViewsPlugin;

defined('ABSPATH') || exit;

class SettingsPage {

    public function __construct() {
        // Add Settings Page
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_menu() {
        add_options_page(
            __('Page view statistics settings', 'concise-counter-of-post-views'),          // page title
            __('Page view statistics', 'concise-counter-of-post-views'),              // menu title
            'manage_options',          // Permission requirement
            'concise_counter_settings', // menu slug
            [$this, 'render_settings_page'] // Callback function
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Page view statistics settings', 'concise-counter-of-post-views');?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('concise_counter_settings_group');
                do_settings_sections('concise_counter_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('concise_counter_settings_group', 'concise_counter_of_post_views_enabled', [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_checkbox'],
            'default' => '1',
        ]);

        add_settings_section(
            'concise_counter_settings_section',
            __('Function settings', 'concise-counter-of-post-views'),
            null,
            'concise_counter_settings'
        );

        add_settings_field(
            'concise_counter_of_post_views_enabled',
            __('Enable page view statistics', 'concise-counter-of-post-views'),
            [$this, 'render_checkbox_field'],
            'concise_counter_settings',
            'concise_counter_settings_section'
        );
    }

    public function render_checkbox_field() {
        $value = get_option('concise_counter_of_post_views_enabled', '1');
        ?>
        <input type="checkbox" name="concise_counter_of_post_views_enabled" value="1" <?php checked($value, '1'); ?> />
        <?php
    }

    public function sanitize_checkbox($input) {
        return $input === '1' ? '1' : '0';
    }

}
