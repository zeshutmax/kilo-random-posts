<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/public
 * @author     Max Zeshut <maxzeshut@gmail.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Kilo_Random_Posts_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Registers the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kilo-random-posts-public.css', array(), $this->version, 'all');
    }

    /**
     * Registers the [random_posts] shortcode.
     *
     * @since    1.0.0
     */
    public function register_random_posts_shortcode()
    {
        add_shortcode('random_posts', [$this, 'random_posts_shortcode']);
    }

    /**
     * Builds the [random_posts] shortcode output.
     *
     * This implements the functionality of the Random Posts Shortcode for displaying random posts from an external API.
     *
     * @since 1.0.0
     *
     * @param array $attr {
     *     Attributes of the [random_posts] shortcode.
     *
     *     @type int          $count      Number of posts to display. 
     *                                    Default is set by KILO_RANDOM_POSTS_DEFAULT_COUNT constant
     *     @type string       $order      Order of the posts in the grid. Accepts 'ASC', 'DESC'. 
     *                                    Default is set by KILO_RANDOM_POSTS_DEFAULT_ORDER constant
     *                                    ID field is used for ordering.
     * }
     * @return string HTML content to display gallery.
     */
    public function random_posts_shortcode($atts)
    {
        $atts = shortcode_atts([
            'count' => get_option('kilo_shortcode_count'),
            'order' => get_option('kilo_shortcode_order'),
        ], $atts);

        $count = absint($atts['count']);
        if ($count <= 0) {
            $count = KILO_RANDOM_POSTS_DEFAULT_COUNT;
        }

        $order = strtoupper($atts['order']);
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = KILO_RANDOM_POSTS_DEFAULT_ORDER;
        }

        $posts = $this->get_random_posts($count, $order);

        if (is_wp_error($posts)) {
            return '<p class="krp-card krp-card--error">' . $posts->get_error_message() . '</p>';
        }

        ob_start();
        load_template(
            plugin_dir_path(__FILE__) . 'templates/grid-posts.php',
            false,
            [
                'posts' => $posts,
                'count' => $count,
                'order' => $order
            ]
        );
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Fetches posts from External URL.
     *
     * @since   1.0.0
     * 
     * @param   string  $url        URL to fetch posts from.
     *                              Default is set by KILO_RANDOM_POSTS_REMOTE_URL constant.
     * 
     * @return  array|WP_Error      Posts array on success or WP_Error on failure.
     */
    public function fetch_remote_posts($url = KILO_RANDOM_POSTS_REMOTE_URL)
    {
        $response = wp_safe_remote_get($url);

        if (
            is_wp_error($response) ||
            wp_remote_retrieve_response_code($response) !== 200
        ) {
            return new WP_Error('404', __('No posts available!', 'kilo-random-posts'));
        }

        $body = wp_remote_retrieve_body($response);
        $posts = json_decode($body, true);

        return $posts;
    }

    /**
     * Retrives and orders posts for [random_posts] shortcode based on its parameters
     *
     * @since   1.0.0
     * 
     * @param   int     $count  Number of posts to display.
     * @param   string  $order  Order of the posts in the grid. Default 'DESC'. Accepts 'ASC', 'DESC'. 
     *                          ID field is used for ordering.
     * 
     * @return  array|WP_Error  Posts array on success or WP_Error on failure.
     */
    public function get_random_posts($count, $order)
    {

        $posts = $this->fetch_remote_posts();

        if (is_wp_error($posts) || empty($posts)) {
            return new WP_Error('404', __('No posts available!', 'kilo-random-posts'));
        }

        shuffle($posts);

        $posts = array_slice($posts, 0, $count);

        if (array_key_exists('id', $posts[0])) {
            $ids = array_column($posts, 'id');
            array_multisort($ids, $order == 'ASC' ? SORT_ASC : SORT_DESC, $posts);
        }

        return $posts;
    }
}
