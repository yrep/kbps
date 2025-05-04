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
            // Нам больше не нужны filling_ids и thumbnail здесь для встроенного модального окна
            // $filling_ids = get_post_meta(get_the_ID(), 'kbps_available_fillings', true);
            // $filling_ids = $filling_ids ? explode(',', $filling_ids) : [];
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: ''; // Оставляем получение thumbnail для фона div
        ?>
            <div class="cake-card" data-cake-id="<?php the_ID(); ?>">
                <a href="<?php the_permalink(); ?>" class="cake-image-link">
                    <div class="cake-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
                </a>
                <button class="model-number" data-model="<?php echo esc_attr($model); ?>">
                    <?php echo esc_html($model); ?>
                </button>
                <?php
                    // Удалена HTML структура встроенного модального окна
                    // <div class="cake-modal" style="display: none;"> ... </div>
                ?>
            </div>
        <?php endwhile; else : ?>
            <p><?php _e('No cakes found.', 'kbps'); ?></p>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // JavaScript для кнопки копирования номера модели
    document.querySelectorAll('.model-number').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const model = this.getAttribute('data-model');
            navigator.clipboard.writeText(model).then(() => {
                console.log('Copied model number:', model);
            });
        });
    });

    <?php
        // Удален JavaScript, связанный с открытием/закрытием встроенных модальных окон:
        // document.querySelectorAll('.cake-image-link').forEach(link => { ... });
        // document.querySelectorAll('.modal-close').forEach(close => { ... });
        // document.querySelectorAll('.cake-modal').forEach(modal => { ... });
    ?>

    // Стандартное поведение ссылки .cake-image-link теперь не перехватывается,
    // поэтому она будет вести на страницу поста торта по своему href.
});
</script>

<?php
get_footer();
?>