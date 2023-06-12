<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/admin
 * @author     Max Zeshut <maxzeshut@gmail.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Kilo_Random_Posts_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Adds a Plugin Settings submenu page to the Settings main menu.
     * 
     * Defines sections and inputs of this page. 
     *
     * @since 1.0.0
     */
    public function register_settings_page()
    {
        add_options_page(
            __('Kilo Random Posts Settings', 'kilo-random-posts'),
            __('Kilo Random Posts', 'kilo-random-posts'),
            'manage_options',
            'kilo-random-posts',
            [$this, 'output_settings_page']
        );

        add_settings_section(
            'kilo_shortcode_settings_default',
            __('Shortcode default values', 'kilo-random-posts'),
            function () {
                $example = '[random_posts count="10" order="desc"]';
                echo '<p>You can override these setting by adding count and order parameters to the shortcode.</p>';
                echo '<p>Example (click on shortcode to copy): <pre style="display:inline; cursor:pointer;" onClick="navigator.clipboard.writeText(\'' . esc_js($example) . '\'); alert(\'Copied!\')">' . $example . '</pre></p>';
            },
            'kilo-random-posts'
        );

        add_settings_field(
            'kilo_shortcode_count',
            __('Count of Posts', 'kilo-random-posts'),
            function () {
                echo '<input name="kilo_shortcode_count" type="number" required min="1" max="100" value="' . esc_attr(get_option('kilo_shortcode_count')) . '" />';
            },
            'kilo-random-posts',
            'kilo_shortcode_settings_default'
        );

        add_settings_field(
            'kilo_shortcode_order',
            'Order direction (by ID)',
            function () {
                $current = get_option('kilo_shortcode_order');
                $options = ['ASC' => __('Ascending', 'kilo-random-posts'), 'DESC' => __('Descending', 'kilo-random-posts')];

                echo '<select name="kilo_shortcode_order" required>';
                foreach ($options as $value => $title) {
                    $selected = $value === $current ? 'selected' : '';
                    echo '<option ' . $selected . ' value="' . $value . '">' . $title . '</option>';
                }
                echo '</select>';
            },
            'kilo-random-posts',
            'kilo_shortcode_settings_default'
        );
    }

    /**
     * Registers Plugin settings and its default data.
     *
     * @since 1.0.0
     */
    public function register_shortcode_settings()
    {
        register_setting('kilo_shortcode_settings', 'kilo_shortcode_count', [
            'sanitize_callback' => 'intval',
            'default'           => KILO_RANDOM_POSTS_DEFAULT_COUNT,
        ]);

        register_setting('kilo_shortcode_settings', 'kilo_shortcode_order', [
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => KILO_RANDOM_POSTS_DEFAULT_ORDER,
        ]);
    }

    /**
     * Loads a template for a Plugin Settings page.
     *
     * @since 1.0.0
     */
    public function output_settings_page()
    {
        load_template(plugin_dir_path(__FILE__) . 'templates/page-settings.php');
    }

    /**
     * Adds action links to plugin on WordPress Plugins page
     *
     * @since 1.0.0
     */
    public function output_plugin_action_links($plugin_links)
    {
        $url = admin_url('options-general.php?page=kilo-random-posts');
        $settings_link = '<a href="' . esc_url($url) . '">' . __('Shortcode default settings', 'kilo-random-posts') . '</a>';

        array_push($plugin_links, $settings_link);
        return $plugin_links;
    }

    /**
     * Adds meta links to plugin on WordPress Plugins page
     *
     * @since 1.0.0
     */
    public function output_plugin_meta_links($plugin_meta, $plugin_file, $plugin_data, $status)
    {
        $url = admin_url('options-general.php?page=kilo-random-posts');
        $settings_link = '<a href="' . esc_url($url) . '">' . __('How to use', 'kilo-random-posts') . '</a>';

        if (strpos($plugin_file, 'kilo-random-posts.php') !== false) {
            array_push($plugin_meta, $settings_link);
        }

        return $plugin_meta;
    }
}
