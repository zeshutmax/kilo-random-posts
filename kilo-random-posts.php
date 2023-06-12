<?php

/**
 * Plugin Name:       Kilo Random Posts
 * Description:       This plugin fetches random posts from an external API and displays them in an aesthetic way via the shortcode [random_posts].
 * Author:            Max Zeshut
 * Author URI:        https://github.com/zeshutmax
 * Plugin URI:        https://github.com/zeshutmax
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kilo-random-posts
 * Domain Path:       /languages
 * 
 * @since             1.0.0
 * @package           Kilo_Random_Posts
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Current plugin version.
 */

define('KILO_RANDOM_POSTS_VERSION', '1.0.0');

/**
 * External URL to get posts for [random_posts] shortcode
 */

define('KILO_RANDOM_POSTS_REMOTE_URL', 'https://jsonplaceholder.typicode.com/posts');

/**
 * Default count of posts in the [random_posts] shortcode grid
 */

define('KILO_RANDOM_POSTS_DEFAULT_COUNT', 20);

/**
 * Default order of posts in the [random_posts] shortcode grid. ID field is used for ordering.
 */

define('KILO_RANDOM_POSTS_DEFAULT_ORDER', 'DESC');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path(__FILE__) . 'includes/class-kilo-random-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_kilo_random_posts()
{
    $plugin = new Kilo_Random_Posts();
    $plugin->run();
}

run_kilo_random_posts();
