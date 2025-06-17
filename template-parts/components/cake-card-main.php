<?php
/**
 * Cake Card Main Template
 */
?>
<div class="wedding-block__big-photo-wrapper block-<?php echo $block_num; ?>">
    <div class="cake-image-container">
        <a href="<?php echo esc_url($cake['permalink']); ?>" class="cake-photo-link">
            <?php if (!empty($cake['thumbnail_id'])) : ?>
                <?php echo wp_get_attachment_image($cake['thumbnail_id'], 'large', false, ['class' => 'kbps-thumbnail']); ?>
            <?php elseif (!empty($cake['thumbnail'])): ?>
                <img src="<?php echo esc_url($cake['thumbnail']); ?>" 
                    alt="<?php echo esc_attr($cake['title']); ?>" 
                    class="kbps-thumbnail">
            <?php else: ?>
                <img src="<?php echo esc_url(KBPS_THEME_URI . 'assets/images/placeholders/cake_placeholder.png'); ?>" 
                    alt="<?php echo esc_attr($cake['title']); ?>" 
                    class="kbps-thumbnail">
            <?php endif; ?>
        </a>
        <?php if (!empty($cake['model'])): ?>
            <div class="cake-card__model model-number" data-model="<?php echo esc_attr($cake['model']); ?>">
                <?php echo esc_html($cake['model']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>