<?php
get_header();
if (have_posts()) : while (have_posts()) : the_post();
    $model = get_post_meta(get_the_ID(), 'kbps_model', true);
    $description = get_post_meta(get_the_ID(), 'kbps_description', true);
    $ingredients = get_post_meta(get_the_ID(), 'kbps_ingredients', true);
    $finishing = get_post_meta(get_the_ID(), 'kbps_finishing', true);
    $decoration = get_post_meta(get_the_ID(), 'kbps_decoration', true);
    $tiers = get_post_meta(get_the_ID(), 'kbps_tiers', true);
    $shape = get_post_meta(get_the_ID(), 'kbps_shape', true);
    $gallery = get_post_meta(get_the_ID(), 'kbps_gallery', true);
    $filling_ids = get_post_meta(get_the_ID(), 'kbps_available_fillings', true);
    $selected_filling_id = isset($_GET['filling_id']) ? absint($_GET['filling_id']) : 0;

    //$cake_archive_url = get_post_type_archive_link('cake');
    $terms = get_the_terms(get_the_ID(), 'cake_type');
    $back_link_url = '';

    if ($terms && !is_wp_error($terms)) {
        $first_term = array_shift($terms);
        $back_link_url = get_term_link($first_term->term_id, 'cake_type');

        if (is_wp_error($back_link_url)) {
            $back_link_url = '';
        }
    }

?>
<main id="main-content">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title hidden"><?php the_title(); ?></h1>
            
            <!-- Cake post must has a taxonomy to have back button! -->

            <?php if ($back_link_url) : ?>
                <a href="<?php echo esc_url($back_link_url); ?>" class="back-to-archive-link">
                    <button class="button-arrow back-to-archive" aria-label="<?php esc_attr_e('Back to Cakes catalog', 'kbps'); ?>">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
            <?php endif; ?>


        </header>



<div class="main">
    <?php if (has_post_thumbnail()) : ?>
        <div class="main-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>

    <?php
    $filling_ids_array = array();
    if (!empty($filling_ids)) {
        $filling_ids_array = array_map('absint', explode(',', $filling_ids));
    }

    if (!empty($filling_ids_array)) : ?>
        <section class="main available-fillings">
            <h2><?php _e('Náplně', 'kbps'); ?></h2>
            <div class="fillings-list">
                <?php
                $fillings_query = new WP_Query(array(
                    'post_type'          => 'filling',
                    'post__in'           => $filling_ids_array,
                    'posts_per_page'     => -1,
                    'orderby'            => 'post__in',
                    'ignore_sticky_posts' => true,
                ));

                if ($fillings_query->have_posts()) :
                    while ($fillings_query->have_posts()) : $fillings_query->the_post();
                ?>
                        <div class="filling-item">
                            <a href="<?php the_permalink(); ?>?filling_id=<?php echo get_the_ID(); ?>" class="select-filling-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="filling-thumbnail">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="filling-title"><?php the_title(); ?></h3>
                                </a>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
    <?php endif; ?>
</div>





        <div class="entry-content">

            <div class="entry-content__description">
                <?php the_content(); ?>
            </div>

            <section class="cake-details">
                <h2><?php _e('Cake Details', 'kbps'); ?></h2>
                <p><strong><?php _e('Model Number', 'kbps'); ?>:</strong> <?php echo esc_html($model); ?></p>
                <p><strong><?php _e('Description', 'kbps'); ?>:</strong> <?php echo esc_html($description); ?></p>
                <p><strong><?php _e('Ingredients', 'kbps'); ?>:</strong> <?php echo esc_html($ingredients); ?></p>
                <p><strong><?php _e('Finishing', 'kbps'); ?>:</strong> <?php echo esc_html($finishing); ?></p>
                <p><strong><?php _e('Decoration', 'kbps'); ?>:</strong> <?php echo esc_html($decoration); ?></p>
                <p><strong><?php _e('Tiers', 'kbps'); ?>:</strong> <?php echo esc_html($tiers); ?></p>
                <p><strong><?php _e('Shape', 'kbps'); ?>:</strong> <?php echo esc_html($shape); ?></p>
            </section>

            <?php if ($selected_filling_id && get_post($selected_filling_id)) :
                $filling = get_post($selected_filling_id);
                $filling_image = get_the_post_thumbnail($selected_filling_id, 'medium');
            ?>
                <section class="selected-filling">
                    <h2><?php _e('Selected Filling', 'kbps'); ?></h2>
                    <div class="filling-card">
                        <?php if ($filling_image) : ?>
                            <div class="filling-image"><?php echo $filling_image; ?></div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($filling->post_title); ?></h3>
                    </div>
                </section>
            <?php endif; ?>

            

            <?php if ($gallery) :
                $gallery_ids = explode(',', $gallery);
            ?>
                <section class="cake-gallery">
                    <h2><?php _e('Gallery', 'kbps'); ?></h2>
                    <div class="gallery-images">
                        <?php foreach ($gallery_ids as $image_id) :
                            $image = wp_get_attachment_image($image_id, 'medium');
                            if ($image) :
                        ?>
                            <div class="gallery-image"><?php echo $image; ?></div>
                        <?php endif; endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <button class="scroll-to-footer"><?php _e('Order Now', 'kbps'); ?></button>
        </div>
    </article>
    <div id="filling-modal" class="modal" style="display: none;">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div class="modal-body">
                <div class="modal-image-section">
                    <div class="image-placeholder-container">
                        <div class="image-placeholder-square"></div>
                        <img src="" alt="Filling Image Full" id="modal-filling-image-full" style="display: none;">
                    </div>
                </div>
                <div class="modal-details-section">
                    <h3 id="modal-filling-title"></h3>
                    <p id="modal-filling-description"></p>
                    </div>
            </div>
        </div>
    </div>
<?php

get_footer();
?>
</main>

<script>
/*

*/
</script>

<?php
endwhile; endif;
get_footer();
?>