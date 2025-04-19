<?php
get_header();
$term = get_queried_object();
?>
<main id="main-content">
    <header class="archive-header">
        <h1 class="archive-title"><?php echo esc_html($term->name); ?></h1>
    </header>

    <div class="cake-archive">
        <?php if (have_posts()) : while (have_posts()) : the_post(); 
            $model = get_post_meta(get_the_ID(), 'kbps_model', true);
            $filling_ids = get_post_meta(get_the_ID(), 'kbps_available_fillings', true);
            $filling_ids = $filling_ids ? explode(',', $filling_ids) : [];
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: '';
        ?>
            <div class="cake-card" data-cake-id="<?php the_ID(); ?>">
                <a href="<?php the_permalink(); ?>" class="cake-image-link">
                    <div class="cake-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
                </a>
                <button class="model-number" data-model="<?php echo esc_attr($model); ?>">
                    <?php echo esc_html($model); ?>
                </button>
                <div class="cake-modal" style="display: none;">
                    <div class="modal-content">
                        <span class="modal-close">×</span>
                        <h2><?php the_title(); ?></h2>
                        <?php if ($thumbnail) : ?>
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" style="max-width: 100px; height: auto;">
                        <?php endif; ?>
                        <h3><?php _e('Select Filling', 'kbps'); ?></h3>
                        <ul class="filling-list">
                            <?php foreach ($filling_ids as $filling_id) : 
                                $filling = get_post($filling_id);
                                if ($filling) :
                            ?>
                                <li>
                                    <a href="<?php echo esc_url(add_query_arg('filling_id', $filling_id, get_permalink())); ?>">
                                        <?php echo esc_html($filling->post_title); ?>
                                    </a>
                                </li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endwhile; else : ?>
            <p><?php _e('No cakes found.', 'kbps'); ?></p>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Копирование номера модели
    document.querySelectorAll('.model-number').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const model = this.getAttribute('data-model');
            navigator.clipboard.writeText(model).then(() => {
                console.log('Copied model number:', model);
            });
        });
    });

    // Обработка модального окна
    document.querySelectorAll('.cake-image-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const card = this.closest('.cake-card');
            const modal = card.querySelector('.cake-modal');
            if (modal) {
                modal.style.display = 'block';
            }
        });
    });

    // Закрытие модального окна
    document.querySelectorAll('.modal-close').forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.cake-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    // Закрытие при клике вне модального окна
    document.querySelectorAll('.cake-modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
});
</script>

<?php
get_footer();
?>