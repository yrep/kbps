<?php
/**
 * Template: Cake Catalog
 */
get_header();

echo '<h1>' . get_the_title() . '</h1>';

// Taxonomy filter
$terms = get_terms(['taxonomy' => 'cake_type']);
if ($terms) {
    echo '<div class="cake-filters">';
    foreach ($terms as $term) {
        echo '<a href="' . get_term_link($term) . '" class="filter-link">' . $term->name . '</a>';
    }
    echo '</div>';
}

// All cakes
$cakes = new WP_Query([
    'post_type' => 'cake',
    'posts_per_page' => 12
]);

if ($cakes->have_posts()) {
    echo '<div class="cake-grid">';
    while ($cakes->have_posts()) {
        $cakes->the_post();
        // Cake cart
        echo '<div class="cake-card">';
        the_post_thumbnail('medium');
        echo '<h3>' . get_the_title() . '</h3>';
        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
}

get_footer();