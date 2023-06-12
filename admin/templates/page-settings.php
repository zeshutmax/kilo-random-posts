<?php

/**
 * Provides a Plugin Settings page view.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <form action="options.php" method="POST">
        <?php
        settings_fields('kilo_shortcode_settings');
        do_settings_sections('kilo-random-posts');
        submit_button();
        ?>
    </form>
</div>