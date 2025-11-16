<?php
/**
 * Template Name: Recipes & Courses Archive
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

global $kbpsCore;
?>

<div <?php $kbpsCore->getClassAttr(['container']); ?>>
    <header class="page-header">
        <h1><?php _e('Recipes & Cooking Courses', 'kbps'); ?></h1>
        <p><?php _e('Digital downloads for your culinary journey', 'kbps'); ?></p>
    </header>

    <div class="recipe-products-grid">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'recipes-courses',
                ),
            ),
            'meta_query' => array(
                array(
                    'key'     => '_virtual',
                    'value'   => 'yes',
                    'compare' => '=',
                )
            )
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No recipes or courses found.', 'kbps') . '</p>';
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>