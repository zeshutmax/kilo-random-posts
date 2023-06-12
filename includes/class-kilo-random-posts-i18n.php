<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/includes
 * @author     Max Zeshut <maxzeshut@gmail.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Kilo_Random_Posts_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'kilo-random-posts',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
