<?php
/**
 * Cake Card Main Template
 */
?>
<div class="wedding-block__big-photo-wrapper block-<?php echo $block_num; ?>">
    <div class="cake-image-container">
        <a href="<?php echo esc_url($cake['permalink']); ?>" class="cake-photo-link">
            <img src="<?php echo esc_url($cake['thumbnail']); ?>" 
                alt="<?php echo esc_attr($cake['title']); ?>" 
                class="kbps-thumbnail">
        </a>
        <?php if (!empty($cake['model'])): ?>
            <div class="cake-card__model">
                <?php echo esc_html($cake['model']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>