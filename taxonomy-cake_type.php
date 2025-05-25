<?php
get_header();

$term = get_queried_object(); // 'cake-type'
?>

<main id="main-content">
    <header class="archive-header">
        <h1 class="archive-title cake-type hidden"><?php echo esc_html($term->name); ?></h1>
    </header>

    <?php
    $archive_text = get_option('kbps_wedding_cakes_archive_title_text', '');
    if (!empty($archive_text)) :
    ?>
        <div class="cake-type-title-text">
            <div class="left-column">
                <?php echo wpautop(wp_kses_post($archive_text)); ?>
            </div>
            <div class="right-column">
                <!-- must be empty -->
            </div>
        </div>
    <?php endif; ?>

    <div class="cake-archive">
        <?php
        $args = [
            'post_type'      => 'cake',
            'posts_per_page' => -1,
            'meta_key'       => 'kbps_model',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'cake-type',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ]
            ],
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $model = get_post_meta(get_the_ID(), 'kbps_model', true);
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: '';
        ?>
                <div class="cake-card cake-type" data-cake-id="<?php the_ID(); ?>">
                    <a href="<?php the_permalink(); ?>" class="cake-image-link">
                        <div class="cake-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
                    </a>
                    <button class="model-number cake-card__model" data-model="<?php echo esc_attr($model); ?>">
                        <?php echo esc_html($model); ?>
                    </button>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p><?php _e('No cakes found.', 'kbps'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
