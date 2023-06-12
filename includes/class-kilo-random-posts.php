<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/includes
 * @author     Max Zeshut <maxzeshut@gmail.com>
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Kilo_Random_Posts
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Kilo_Random_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
     */

    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */

    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */

    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */

    public function __construct()
    {
        if (defined('KILO_RANDOM_POSTS_VERSION')) {
            $this->version = KILO_RANDOM_POSTS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'kilo-random-posts';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Kilo_Random_Posts_Loader. Orchestrates the hooks of the plugin.
     * - Kilo_Random_Posts_i18n. Defines internationalization functionality.
     * - Kilo_Random_Posts_Admin. Defines all hooks for the admin area.
     * - Kilo_Random_Posts_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */

    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-kilo-random-posts-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-kilo-random-posts-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-kilo-random-posts-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-kilo-random-posts-public.php';

        $this->loader = new Kilo_Random_Posts_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Kilo_Random_Posts_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */

    private function set_locale()
    {
        $plugin_i18n = new Kilo_Random_Posts_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */

    private function define_admin_hooks()
    {

        $plugin_admin = new Kilo_Random_Posts_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_menu', $plugin_admin, 'register_settings_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_shortcode_settings');
        $this->loader->add_filter('plugin_action_links_kilo-random-posts/kilo-random-posts.php', $plugin_admin, 'output_plugin_action_links', 10, 2);
        $this->loader->add_filter('plugin_row_meta', $plugin_admin, 'output_plugin_meta_links', 10, 4);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */

    private function define_public_hooks()
    {

        $plugin_public = new Kilo_Random_Posts_Public($this->get_plugin_name(), $this->get_version());

        // $plugin_public->register_random_posts_shortcode();
        $this->loader->add_action('init', $plugin_public, 'register_random_posts_shortcode');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */

    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */

    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Kilo_Random_Posts_Loader    Orchestrates the hooks of the plugin.
     */

    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */

    public function get_version()
    {
        return $this->version;
    }
}
