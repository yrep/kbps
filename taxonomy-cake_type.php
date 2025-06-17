<?php
get_header();
$term = get_queried_object();
?>

<main id="main-content">
    <header class="archive-header">
        <h1 class="archive-title cake-type hidden"><?php echo esc_html($term->name); ?></h1>
    </header>

    <?php
    $archive_text = get_option('kbps_wedding_cakes_archive_title_text', '');
    if (!empty($archive_text)) : ?>
        <div class="cake-type-title-text">
            <div class="left-column">
                <?php echo wpautop(wp_kses_post($archive_text)); ?>
            </div>
            <div class="right-column">
                <!-- Optional right content -->
            </div>
        </div>
    <?php endif; ?>

    <div class="cake-archive">
        <?php if (have_posts()) :
            while (have_posts()) : the_post();

                $model     = get_post_meta(get_the_ID(), 'kbps_model', true);
                $has_thumb = has_post_thumbnail();

                // Пропускаем посты без модели или без изображения
                if (empty($model) || !$has_thumb) continue;
                ?>

                <div class="cake-card cake-type" data-cake-id="<?php the_ID(); ?>">
                    <a href="<?php the_permalink(); ?>" class="cake-image-link">
                        <?php
                        the_post_thumbnail('large', [
                            'class' => 'cake-image__img',
                            'alt'   => esc_attr(get_the_title()),
                            'loading' => 'lazy',
                            'decoding' => 'async',
                        ]);
                        ?>
                        <?php if (!empty($model)) : ?>
                            <span class="cake-card__model" data-model="<?php echo esc_attr($model); ?>">
                                <?php echo esc_html($model); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>


            <?php endwhile;
        else : ?>
            <p><?php _e('V současné době nejsou k dispozici žádné modely k objednání.', 'kbps'); ?></p>
        <?php endif; ?>
    </div>

    <?php if (have_posts()) : ?>
        <div class="pagination">
            <?php
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => __('« Zpět', 'kbps'),
                'next_text' => __('Další »', 'kbps'),
            ]);
            ?>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
