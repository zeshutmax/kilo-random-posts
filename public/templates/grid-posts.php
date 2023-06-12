<?php

/**
 * Provides a [random_posts] shortcode view.
 *
 * @link       https://zeshut.dev
 * @since      1.0.0
 *
 * @package    Kilo_Random_Posts
 * @subpackage Kilo_Random_Posts/public/partials
 */

?>

<div class="krp-wrapper">
    <?php

    if (
        isset($args)
        && is_array($args)
        && array_key_exists('count', $args)
        && array_key_exists('order', $args)
    ) {
        $order = $args['order'] == 'ASC' ? __('Ascending', 'kilo-random-posts') : __('Descending', 'kilo-random-posts');
        echo '<div class="krp-wrapper__statusbar">';
        echo '<span>' . __('Showing', 'kilo-random-posts') . ' ' . $args['count'] . ' ' . _n('post', 'posts', $args['count'], 'kilo-random-posts') . '</span>';
        echo '<span>' . __('Order', 'kilo-random-posts') . ': ' . $order . ' ' . __("by post ID", 'kilo-random-posts') . '</span>';
        echo '</div>';
    }

    ?>
    <div class="krp-wrapper__grid krp-grid">
        <?php
        if (
            isset($args) &&
            is_array($args) &&
            array_key_exists('posts', $args)
        ) {
            foreach ($args['posts'] as $p) {
                echo '<article class="krp-grid__item krp-card krp-card--post">';
                echo '<span class="krp-card__id">' . $p['id'] . '</span>';
                echo '<h3 class="krp-card__title">' . $p['title'] . '</h3>';
                echo '<p class="krp-card__excerpt">' . $p['body'] . '</p>';
                echo '</article>';
            }
        } else {
            echo '<p class="krp-card krp-card--error">' . __('No posts available!', 'kilo-random-posts') . '</p>';
        }
        ?>
    </div>

</div>